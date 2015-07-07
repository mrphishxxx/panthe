<?php

error_reporting(E_ALL ^ E_NOTICE);
include(dirname(__FILE__) . '/../' . 'config.php');
include dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
require_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';
include dirname(__FILE__) . '/../' . 'modules/admins/class_admin_admins.php';
$admins = new admins();

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

require_once( PATH . 'modules' . DIRECTORY_SEPARATOR . 'angry_curl' . DIRECTORY_SEPARATOR . 'RollingCurl.class.php');
require_once( PATH . 'modules' . DIRECTORY_SEPARATOR . 'angry_curl' . DIRECTORY_SEPARATOR . 'AngryCurl.class.php');

global $AC;
global $errors;

function callback($response, $info, $request) {
    $db = ADONewConnection(DB_TYPE);
    @$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
    $db->Execute('set charset utf8');
    $db->Execute('SET NAMES utf8');
    global $AC;
    global $errors;

    $response = iconv("windows-1251", "utf-8", $response);
    $post = json_decode($request->post_data);

    $query = $db->Execute("SELECT * FROM sayty WHERE id=" . $post->sid);
    $res = $query->FetchRow();
    $uid = $res['uid'];
    $gid = $res['gid'];
    $sid = $post->sid;
    $cookie_jar = $request->options[CURLOPT_COOKIEJAR];
    $errors .= "";
    if ($info['http_code'] !== 200) {
        AngryCurl::add_debug_msg("->\t" . $request->options[CURLOPT_PROXY] . "\t FAILED INTO FUNCTION(sid=" . $post->sid . ",uid=" . $uid . ") \t" . $info['http_code'] . "\t" . $info['total_time'] . "\t" . $info['url']);
        $key = array_search($request->options[CURLOPT_PROXY], $AC->array_proxy, true);
        unset($AC->array_proxy[$key]);
        $AC->__set('n_proxy', $AC->__get('n_proxy') - 1);
        $AC->request('https://gogetlinks.net/', 'POST', $request->post_data, null, array(CURLOPT_COOKIEJAR => $cookie_jar));
    } else {
        $data = array('e_mail' => $post->e_mail,
            'password' => trim($post->password),
            'remember' => "");
        $query_p = http_build_query($data);

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
            $rez = curl_exec($curl);
            curl_close($curl);
        }
        $rez = iconv("windows-1251", "utf-8", $rez);
        if ($rez == "Некорректный Логин или Пароль" || $rez == "Некорректный email или Пароль") {
            return FALSE;
        }

        $urlg = "https://gogetlinks.net/web_task.php?action_change=change_count_in_page&in_site_id=" . $gid;
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
            $out = curl_exec($curl);
            $out = iconv("windows-1251", "utf-8", $out);
            curl_close($curl);
        }
        $vipolneno = array();
        $result = $db->Execute("SELECT id, b_id, vipolneno FROM zadaniya WHERE b_id IS NOT NULL AND (sistema = 'https://gogetlinks.net/' OR sistema = 'ГГЛ')");
        while ($add = $result->FetchRow()) {
            $buf3[] = $add["b_id"];
            if ($add["vipolneno"] == 1) {
                $vipolneno[$add["id"]] = $add["b_id"];
            }
        }


        $open_now = str_get_html($out);
        if (!empty($open_now) && $open_now->find('script,link,comment')) {
            foreach ($open_now->find('script,link,comment') as $tmp) {
                $tmp->outertext = '';
            }
        }
        echo "sid=" . $sid . ",uid=" . $uid . " <br>";
        $j = 0;
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

                    if (!in_array($ggl_id, $buf3)) {
                        $buf3[] = $ggl_id;
                        if ($curl = curl_init()) {
                            curl_setopt($curl, CURLOPT_URL, 'https://gogetlinks.net/' . $url);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curl, CURLOPT_POST, true);
                            curl_setopt($curl, CURLOPT_PROXY, $request->options[CURLOPT_PROXY]);
                            curl_setopt($curl, CURLOPT_PROXYUSERPWD, $request->options[CURLOPT_PROXYUSERPWD]);
                            curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
                            curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
                            @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                            $out2 = curl_exec($curl);
                            $out2 = iconv("windows-1251", "utf-8", $out2);
                            curl_close($curl);
                        }

                        $open = str_get_html($out2);
                        if (!empty($open)) {
                            foreach ($open->find('script,link,comment') as $tmp)
                                $tmp->outertext = '';
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
                                            if (empty($ankor))
                                                $ankor = $open->find('div[class=tv_params_block] div[class=params] div[class=block_value]', $i)->plaintext;
                                            break;
                                        case "&nbsp;Атрибут title":
                                            $title = $open->find('div[class=tv_params_block] div[class=params] div[class=block_value]', $i)->plaintext;
                                            if (empty($key_words))
                                                $key_words = $open->find('div[class=tv_params_block] div[class=params] div[class=block_value]', $i)->plaintext;
                                            break;
                                    }
                                    $i++;
                                }
                                $task_text = $open->find('div[class=tv_params_block] div[class=params] div[class=block_value]', $i)->plaintext;
                            }
                            $open->clear();
                        }
                        unset($open);
                        if (!empty($ankor)) {
                            $first1 = mb_strtoupper(mb_substr($ankor, 0, 1, 'UTF-8'), 'UTF-8'); //первая буква
                            $first = str_replace("?", "", $first1);
                            $last1 = mb_strtolower(mb_substr($ankor, 1), 'UTF-8'); //все кроме первой буквы
                            $last = ($last1[0] == "?") ? mb_substr($last1, 1) : $last1;
                            $tema = mysql_real_escape_string($first . $last);

                            $cut_end = mb_strpos($tema, "(", 0, "UTF-8");
                            $tema = trim(mb_substr($tema, 0, $cut_end, 'UTF-8'));
                        }
                        if ($type == "Ссылка-картинка") {
                            $ankor .= " (!ссылка-картинка!)";
                        }
                        if (!empty($ankor) && !empty($to_url)) {
                            $db->Execute("INSERT INTO zadaniya(sid, b_id, uid, sistema, ankor, url, tema, comments, vipolneno, date, keywords, nof_chars) VALUES ('" . $sid . "', '" . $ggl_id . "','" . $uid . "', 'https://gogetlinks.net/', '" . $ankor . "', '" . $to_url . "', '" . $tema . "', '" . mysql_real_escape_string($index . "\n" . $task_text) . "', '0', '" . $date . "', '" . $key_words . "', '2000')");
                            $j++;
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
    return;
}

$AC = new AngryCurl('callback');
$AC->init_console();
$AC->load_proxy_list(PATH . 'modules' . DIRECTORY_SEPARATOR . 'angry_curl' . DIRECTORY_SEPARATOR . 'proxy_list.txt', 70, 'http', 'https://gogetlinks.net/');


$new_tasks = array();
include(PATH . 'includes/simple_html_dom.php');

$last_task = $db->Execute("SELECT id FROM zadaniya WHERE sistema='https://gogetlinks.net/' ORDER BY id DESC LIMIT 1")->FetchRow();
$last_id = $last_task['id'];

$birjs = $db->Execute("select * from birjs WHERE birj = 1");
$ggl_to_uid = array();
while ($res = $birjs->FetchRow()) {
    $ggl_to_uid[] = $res['uid'];
}
$users = $db->Execute("SELECT * FROM admins WHERE active=1 AND type='user'");
$act_uids = array();
while ($res = $users->FetchRow()) {
    $balance = $admins->getUserBalans($res['id'], $db, 1);
    if ($balance >= 60 || (($res['id'] == 20) || ($res['id'] == 55))) {
        if (in_array($res['id'], $ggl_to_uid)) {
            $act_uids[] = $res['id'];
        }
    }
}
$act_uids = "(" . implode(",", $act_uids) . ")";

$sayty = $db->Execute("SELECT * FROM sayty WHERE uid IN $act_uids");

$sid_to_uid = array();
while ($res = $sayty->FetchRow()) {
    $sid_to_uid[$res['id']] = $res['uid'];
}
$count = 0;
if ($AC->get_count_proxy() == 0) {
    while ($AC->get_count_proxy() == 0) {
        if ($count >= 2) {
            break;
        }
        $AC->load_proxy_list(PATH . 'modules' . DIRECTORY_SEPARATOR . 'angry_curl' . DIRECTORY_SEPARATOR . 'proxy_list.txt', 30, 'http', 'https://gogetlinks.net/');
        $count++;
    }
}

foreach ($sid_to_uid as $sid => $uid) {
    $cookie_jar = tempnam(PATH . 'temp', "cookie");

    $res = $db->Execute("select * from birjs where birj=1 AND uid=$uid")->FetchRow();
    $res2 = $db->Execute("SELECT gid FROM sayty WHERE id=$sid")->FetchRow();
    $gid = $res2['gid'];

    if ($gid == 0){
        continue;
    }
    if ($res['login'] == null || $res['pass'] == null){
        continue;
    }
    $data = array('e_mail' => $res['login'],
        'password' => $res['pass'],
        'sid' => $sid,
    );
    $query_p = json_encode($data);

    $AC->request('https://gogetlinks.net/', 'POST', $query_p, null, array(CURLOPT_COOKIEJAR => $cookie_jar));
}

$AC->execute(30);
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
//$message["to"][1] = array("email" => MAIL_DEVELOPER);
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
