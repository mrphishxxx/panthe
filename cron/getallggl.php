<?php

error_reporting(E_ALL ^ E_NOTICE);
include(dirname(__FILE__) . '/../' . 'config.php');
include dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
require_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';
include dirname(__FILE__) . '/../' . 'modules/admins/class_admin_admins.php';
$admins = new admins();

require_once( PATH . 'modules/angry_curl/RollingCurl.class.php');
require_once( PATH . 'modules/angry_curl/AngryCurl.class.php');
global $db;
global $AC;
global $errors;

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

function getTaskGGL($response, $info, $request) {
    global $db;
    global $AC;
    global $errors;
    
    $errors .= "";
    $post = json_decode($request->post_data);
    $cookie_jar = $request->options[CURLOPT_COOKIEJAR];
    $vipolneno = $buffer = array();

    if ($info['http_code'] !== 200) {
        AngryCurl::add_debug_msg("FAILED INTO FUNCTION (uid=" . $post->uid . ") " . $request->options[CURLOPT_PROXY] . "| CODE:" . $info['http_code'] . "| TIME:" . $info['total_time'] . "| URL:" . $info['url']);
        $key = array_search($request->options[CURLOPT_PROXY], $AC->array_proxy, true);
        unset($AC->array_proxy[$key]);
        $AC->__set('n_proxy', $AC->__get('n_proxy') - 1);
        $AC->request('https://gogetlinks.net/', 'POST', $request->post_data, null, array(CURLOPT_COOKIEJAR => $cookie_jar));
    } else {
        
        // Вытаскиваем все задачи у данного пользователя (для исключения дубликатов)
        $task_exists = $db->Execute("SELECT id, b_id, vipolneno FROM zadaniya WHERE uid = '" . $post->uid . "' AND b_id IS NOT NULL AND (sistema = 'https://gogetlinks.net/' OR sistema = 'ГГЛ')");
        while ($add = $task_exists->FetchRow()) {
            $buffer[] = $add["b_id"];
            if ($add["vipolneno"] == 1) {
                $vipolneno[$add["id"]] = $add["b_id"];
            }
        }

        // 1) Залогиниваемся в ГГЛ`
        $data = array('e_mail' => $post->e_mail,
            'password' => trim($post->password),
            'remember' => "");
        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, 'https://gogetlinks.net/login.php');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_PROXY, $request->options[CURLOPT_PROXY]);
            curl_setopt($curl, CURLOPT_PROXYUSERPWD, $request->options[CURLOPT_PROXYUSERPWD]);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
            @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($curl);
            curl_close($curl);
        }
        $connect = iconv("windows-1251", "utf-8", $result);
        if ($connect == "Некорректный Логин или Пароль" || $connect == "Некорректный email или Пароль") {
            return FALSE;
        }
        
        echo " UID = " . $post->uid . "{" . PHP_EOL;
        $sites = $db->Execute("SELECT * FROM sayty WHERE gid != 0 AND gid IS NOT NULL AND uid=" . $post->uid);
        while ($site = $sites->FetchRow()) {
            // В ЦИКЛЕ ПО САЙТАМ 
            echo "sid = " . $site["id"] . PHP_EOL;
            // 2) Переходим на страницу новых задач для определенного сайта
            $urlg = "https://gogetlinks.net/web_task.php?action_change=change_count_in_page&in_site_id=" . $site["gid"];
            if ($curl = curl_init()) {
                curl_setopt($curl, CURLOPT_URL, $urlg);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_PROXY, $request->options[CURLOPT_PROXY]);
                curl_setopt($curl, CURLOPT_PROXYUSERPWD, $request->options[CURLOPT_PROXYUSERPWD]);
                curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
                curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
                @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, array('count_in_page' => 50));
                $result = curl_exec($curl);
                $task_page = iconv("windows-1251", "utf-8", $result);
                curl_close($curl);
            }

            // 3) Очищаем полученную страницу от лишнего
            $open_now = str_get_html($task_page);
            if (!empty($open_now) && $open_now->find('script,link,comment')) {
                foreach ($open_now->find('script,link,comment') as $tmp) {
                    $tmp->outertext = '';
                }
            }

            // 4) Проверяем наличие новых заявок.
            //    Проходим по ним, вытаскивая GGL_ID и URL задачи (чтобы потом вытащить все данные)
            if ($open_now->innertext != '' and count($open_now->find('td[class^=row_] a[onmouseover]'))) {
                $type_page = $open_now->find('div[class^=top_menu_class2_selected] div[class=count_views_in_table_label] a[class=top_menu_write_label]', 0)->plaintext;
                if ($type_page == "Новые") {
                    $tmpInd = 0;
                    while ($tr = $open_now->find('tr[class^=table_content_rows]', $tmpInd)) {

                        if ($open_now->find('tr[class^=table_content_rows]', $tmpInd)->children(3) == null) {
                            $tmpInd++;
                            continue;
                        }
                        if ($open_now->find('tr[class^=table_content_rows]', $tmpInd)->children(3)->children(0) == null) {
                            $tmpInd++;
                            continue;
                        }

                        $url = $open_now->find('tr[class^=table_content_rows]', $tmpInd)->children(3)->children(0)->href;
                        $row = $open_now->find('tr[class^=table_content_rows]', $tmpInd)->children(0)->class;

                        if (!$row) {
                            $tmpInd++;
                            continue;
                        }

                        $ggl_id = mb_substr("$row", 4);
                        $tmpInd++;

                        if (!in_array($ggl_id, $buffer)) {
                            $buffer[] = $ggl_id;
                            if ($curl = curl_init()) {
                                curl_setopt($curl, CURLOPT_URL, 'https://gogetlinks.net/' . $url);
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl, CURLOPT_POST, true);
                                curl_setopt($curl, CURLOPT_PROXY, $request->options[CURLOPT_PROXY]);
                                curl_setopt($curl, CURLOPT_PROXYUSERPWD, $request->options[CURLOPT_PROXYUSERPWD]);
                                curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
                                curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
                                @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                                $out = curl_exec($curl);
                                $task = iconv("windows-1251", "utf-8", $out);
                                curl_close($curl);
                            }

                            $open = str_get_html($task);
                            if (!empty($open)) {
                                foreach ($open->find('script,link,comment') as $tmp) {
                                    $tmp->outertext = '';
                                }
                                $type = NULL;       //Тип обзора
                                $out_url = NULL;    //Внешних ссылок
                                $int_url = NULL;    //Внутренних ссылок
                                $int_lvl = NULL;    //Уровень вложенности
                                $index = "";        //Индексация
                                $to_url = NULL;     //Куда ссылаться
                                $ankor = NULL;      //Якорь
                                $alt = NULL;        //Атрибут alt у картинки
                                $title = NULL;      //Атрибут title у картинки
                                $task_text = "";    //Текст задания
                                $key_words = NULL;  //Ключевые слова
                                $tema = "";         //Тема статьи (генерируется из анкора)
                                $date = time();

                                if ($open->innertext != '' and count($open->find('div[class=tv_params_block] div[class=params] div[class=block_name]'))) {
                                    $i = 0;
                                    foreach ($open->find('div[class=tv_params_block] div[class=params] div[class=block_name]') as $b) {
                                        switch ($b->plaintext) {

                                            case "&nbsp;Тип обзора":
                                                $type = $open->find('div[class=tv_params_block] div[class=params] div[class=block_value]', $i)->plaintext;
                                                break;

                                            case "&nbsp;Внешних ссылок":
                                                $out_url = $open->find('div[class=tv_params_block] div[class=params] div[class=block_value]', $i)->plaintext;
                                                break;

                                            case "&nbsp;Внутренних ссылок":
                                                $int_url = $open->find('div[class=tv_params_block] div[class=params] div[class=block_value]', $i)->plaintext;
                                                break;

                                            case "&nbsp;Уровень вложенности":
                                                $int_lvl = $open->find('div[class=tv_params_block] div[class=params] div[class=block_value]', $i)->plaintext;
                                                break;

                                            case "&nbsp;Индексация страниц":
                                                $index = $open->find('div[class=tv_params_block] div[class=params] div[class=block_value]', $i)->plaintext;
                                                break;

                                            case "&nbsp;Адрес, куда ссылаться":
                                                $to_url = $open->find('div[class=tv_params_block] div[class=params] div[class=block_value]', $i)->plaintext;
                                                break;

                                            case "&nbsp;Текст ссылки (анкор)":
                                                $ankor = $open->find('div[class=tv_params_block] div[class=params] div[class=block_value]', $i)->plaintext;
                                                break;

                                            case "&nbsp;Ключевые слова":
                                                $key_words = $open->find('div[class=tv_params_block] div[class=params] div[class=block_value]', $i)->plaintext;
                                                break;
                                            case "&nbsp;Атрибут alt":
                                                $alt = $open->find('div[class=tv_params_block] div[class=params] div[class=block_value]', $i)->plaintext;
                                                if (empty($ankor)) {
                                                    $ankor = $open->find('div[class=tv_params_block] div[class=params] div[class=block_value]', $i)->plaintext;
                                                }
                                                break;
                                            case "&nbsp;Атрибут title":
                                                $title = $open->find('div[class=tv_params_block] div[class=params] div[class=block_value]', $i)->plaintext;
                                                if (empty($key_words)) {
                                                    $key_words = $open->find('div[class=tv_params_block] div[class=params] div[class=block_value]', $i)->plaintext;
                                                }
                                                break;
                                        }
                                        $i++;
                                    }
                                    if (isset($open->find('div[class=tv_params_block] div[class=params] div[class=block_value]', $i)->plaintext)) {
                                        $task_text = $open->find('div[class=tv_params_block] div[class=params] div[class=block_value]', $i)->plaintext;
                                    }
                                }
                                $open->clear();
                            }
                            unset($open);
                            if (!empty($ankor)) {
                                $first1 = mb_strtoupper(mb_substr($ankor, 0, 1, 'UTF-8'), 'UTF-8'); //первая буква
                                $first = str_replace("?", "", $first1);
                                $last1 = mb_strtolower(mb_substr($ankor, 1), 'UTF-8'); //все кроме первой буквы
                                $last = (isset($last1[0]) && $last1[0] == "?") ? mb_substr($last1, 1) : $last1;
                                $tema = mysql_real_escape_string($first . $last);

                                $cut_end = mb_strpos($tema, "(", 0, "UTF-8");
                                $tema = trim(mb_substr($tema, 0, $cut_end, 'UTF-8'));
                            }
                            if ($type == "Ссылка-картинка") {
                                $ankor .= " (!ссылка-картинка!)";
                            }
                            if (!empty($ankor) && !empty($to_url)) {
                                $db->Execute("INSERT INTO zadaniya(sid, b_id, uid, sistema, ankor, url, tema, comments, vipolneno, date, keywords, nof_chars) VALUES ('" . $site["id"] . "', '" . $ggl_id . "','" . $post->uid . "', 'https://gogetlinks.net/', '" . $ankor . "', '" . $to_url . "', '" . $tema . "', '" . mysql_real_escape_string($index . "\n" . $task_text) . "', '0', '" . $date . "', '" . $key_words . "', '2000')");
                            }
                        } else {
                            if (in_array($ggl_id, $vipolneno)) {
                                $task_id = array_search($ggl_id, $vipolneno);
                                $task = $db->Execute("SELECT * FROM zadaniya WHERE id='$task_id'")->FetchRow();
                                $db->Execute("UPDATE zadaniya SET vipolneno='0', vilojeno='1' WHERE id='$task_id'")->FetchRow();
                                $errors .= "Задача <a href='http://iforget.ru/admin.php?module=admins&action=zadaniya&uid=" . $task['uid'] . "&sid=" . $task['sid'] . "&action2=edit&id=" . $task['id'] . "'>" . $task['id'] . "</a> была в статусе 'Выполнено', но ссылка была не отправлена!! <b>Проверьте в чем причина, отправив задачу руками</b><br>";
                            }
                        }
                    }
                }
            }
            if (!empty($open_now)) {
                $open_now->clear();
                unset($open_now);
            }
        }
        echo "}" . PHP_EOL;
    }
    return;
}

$AC = new AngryCurl('getTaskGGL');
$AC->init_console();
$AC->load_useragent_list(PATH . 'modules/angry_curl/useragent_list.txt');
$AC->load_proxy_list(PATH . 'modules/angry_curl/proxy_list.txt', 20, 'http', 'https://gogetlinks.net/'); //_noprice
if ($AC->get_count_proxy() == 0) {
    for ($i = 0; $i < 2; $i++) {
        if ($AC->get_count_proxy() > 0) {
            break;
        }
        $AC->load_proxy_list(PATH . 'modules/angry_curl/proxy_list_noprice.txt', 30, 'http', 'https://gogetlinks.net/');
    }
}
$balance_all = $admins->getAllUserBalans($db);

$new_tasks = $ggl_to_uid = $act_uids = array();
include(PATH . 'includes/simple_html_dom.php');

$last_task = $db->Execute("SELECT id FROM zadaniya WHERE sistema='https://gogetlinks.net/' ORDER BY id DESC LIMIT 1")->FetchRow();
$last_id = $last_task['id'];

$birjs = $db->Execute("SELECT uid FROM birjs WHERE birj = 1");
while ($res = $birjs->FetchRow()) {
    $ggl_to_uid[] = $res['uid'];
}

$users = $db->Execute("SELECT * FROM admins WHERE active=1 AND type='user' AND id IN (" . implode(',', $ggl_to_uid) . ")");
while ($user = $users->FetchRow()) {
    if (isset($balance_all[$user['id']]) && $balance_all[$user['id']] >= 60 || (($user['id'] == 20) || ($user['id'] == 55))) {
        $act_uids[] = $user['id'];
    }
}
// 0) Создаем запросы на выгрузку для каждого пользователя
foreach ($act_uids as $uid) {
    $cookie_jar = tempnam(PATH . 'temp', "cookie");

    $burse = $db->Execute("SELECT * FROM birjs WHERE birj=1 AND uid=$uid")->FetchRow();
    if ($burse['login'] == null || $burse['pass'] == null) {
        continue;
    }
    $data = array(
        'e_mail' => $burse['login'],
        'password' => $burse['pass'],
        'uid' => $uid
    );
    $query_p = json_encode($data);
    $options = array(
        CURLOPT_COOKIEJAR => $cookie_jar,
        CURLOPT_SSL_VERIFYPEER => false
    );
    $AC->request('https://gogetlinks.net/', 'POST', $query_p, null, $options);
}
// Запускаем выгрузку (количество потоков)
$AC->execute(2);
unset($AC);

$add_task = $db->Execute("SELECT * FROM zadaniya WHERE id > '$last_id' AND sistema='https://gogetlinks.net/'");
while ($task = $add_task->FetchRow()) {
    $new_tasks[$task['id']] = "http://iforget.ru/admin.php?module=admins&action=zadaniya&uid=" . $task['uid'] . "&sid=" . $task['sid'] . "&action2=edit&id=" . $task['id'];
}


if (count($new_tasks) !== 0) {
    $body = "Добрый день!<br/><br/>
            На сайт выгружены новые задачи из биржи <b>gogetlinks.net</b>.<br/><br/>";
    $subject = "[" . count($new_tasks) . " новых задач из биржи gogetlinks]";
    foreach ($new_tasks as $knt => $vnt) {
        $body .= "<a href='$vnt'>$knt</a><br/>";
    }
} else {
    $body = "Добрый день!<br/><br/>
            В бирже gogetlinks не найдено ни одной новой задачи.<br/><br/>";
    $subject = "[0 новых задач из биржи gogetlinks]";
}

$mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
$message = array();
$message["html"] = $body;
$message["text"] = "";
$message["subject"] = $subject;
$message["from_email"] = "news@iforget.ru";
$message["from_name"] = "iforget";
$message["to"] = array();
$message["to"][1] = array("email" => MAIL_DEVELOPER);
$message["to"][0] = array("email" => MAIL_ADMIN);
$message["track_opens"] = null;
$message["track_clicks"] = null;
$message["auto_text"] = null;

try {
    $mandrill->messages->send($message);
} catch (Exception $e) {
    echo $e;
    echo $body;
}

if ($errors != "") {
    $message["html"] = $errors;
    $message["to"][1] = array("email" => MAIL_DEVELOPER);
    $message["subject"] = "[Задачи были в неправильном статусе!]";
    try {
        $mandrill->messages->send($message);
    } catch (Exception $e) {
        echo $e;
        echo $errors;
    }
}

exit("THE END");
