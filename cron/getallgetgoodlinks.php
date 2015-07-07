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

require_once( PATH . 'modules/angry_curl/RollingCurl.class.php');
require_once( PATH . 'modules/angry_curl/AngryCurl.class.php');

global $AC;

function callback($response, $info, $request) {
    $db = ADONewConnection(DB_TYPE);
    @$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
    $db->Execute('set charset utf8');
    $db->Execute('SET NAMES utf8');
    global $AC;

    $post = json_decode($request->post_data);

    $query = $db->Execute("SELECT * FROM sayty WHERE id=" . $post->sid);
    $res = $query->FetchRow();
    $uid = $res['uid'];
    $gid = $res['getgoodlinks_id'];
    $sid = $post->sid;
    $cookie_jar = $request->options[CURLOPT_COOKIEJAR];

    if ($info['http_code'] !== 200) {
        $key = array_search($request->options[CURLOPT_PROXY], $AC->array_proxy, true);
        unset($AC->array_proxy[$key]);
        $AC->__set('n_proxy', $AC->__get('n_proxy') - 1);
        $AC->request('http://getgoodlinks.ru/', 'POST', $request->post_data, null, array(CURLOPT_COOKIEJAR => $cookie_jar));
    } else {
        $data = array('e_mail' => $post->e_mail,
            'password' => $post->password,
            'remember' => "");
        $query_p = http_build_query($data);

        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, 'http://getgoodlinks.ru/login.php');
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

        $error = strpos($rez, "Неверное имя пользователя или пароль. Пожалуйста, попробуйте ещё раз.");
        if ($error !== false && !empty($error) && $error != 0) {
            continue;
        }

        $urlg = "http://getgoodlinks.ru/web_task.php?in_site_id=" . $gid;

        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $urlg);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_PROXY, $request->options[CURLOPT_PROXY]);
            curl_setopt($curl, CURLOPT_PROXYUSERPWD, $request->options[CURLOPT_PROXYUSERPWD]);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
            @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            $out = curl_exec($curl);
            curl_close($curl);
        }

        $result = $db->Execute("SELECT b_id FROM zadaniya WHERE b_id IS NOT NULL AND (sistema = 'http://getgoodlinks.ru/')");
        $buf3 = array();
        while ($add = $result->FetchRow()) {
            if ($add["b_id"] != 0)
                $buf3[] = $add["b_id"];
        }

        $open_now = str_get_html($out);
        if (!empty($open_now) && $open_now->find('script,link,comment')) {
            foreach ($open_now->find('script,link,comment') as $tmp) {
                $tmp->outertext = '';
            }
        }

        $j = 0;
        if ($open_now->innertext != '' and count($open_now->find('td[class^=row_] a[onmouseover]'))) {
            $tmpInd = 0;
            while ($open_now->find('tr[class^=table_content_rows]', $tmpInd)) {
                echo "sid=" . $sid . ",uid=" . $uid . "<br>";

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
                        curl_setopt($curl, CURLOPT_URL, 'http://getgoodlinks.ru/' . $url);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_POST, true);
                        curl_setopt($curl, CURLOPT_PROXY, $request->options[CURLOPT_PROXY]);
                        curl_setopt($curl, CURLOPT_PROXYUSERPWD, $request->options[CURLOPT_PROXYUSERPWD]);
                        curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
                        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
                        @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                        $out2 = curl_exec($curl);
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
                        $index = NULL;      //Индексация
                        $to_url = NULL;     //Куда ссылаться
                        $ankor = NULL;      //Якорь
                        $alt = NULL;        //Атрибут alt у картинки
                        $title = NULL;      //Атрибут title у картинки
                        $task_text = NULL;  //Текст задания
                        $key_words = NULL;  //Ключевые слова
                        $tema = "";         //Тема задачи (генерируется из Анкора)
                        $date = time();


                        if ($open->innertext != '' and count($open->find('div[class=params] div[class=param] div[class=block_name]'))) {
                            $i = 0;
                            foreach ($open->find('div[class=params] div[class=param] div[class=block_name]') as $b) {

                                switch ($b->plaintext) {

                                    case " Тип обзора: ":
                                        $type = $open->find('div[class=params] div[class=param] div[class=block_value]', $i)->plaintext;
                                        break;

                                    case " Внешних ссылок: ":
                                        $out_url = $open->find('div[class=params] div[class=param] div[class=block_value]', $i)->plaintext;
                                        break;

                                    case " Внутренних ссылок: ":
                                        $int_url = $open->find('div[class=params] div[class=param] div[class=block_value]', $i)->plaintext;
                                        break;

                                    case " Уровень вложенности: ":
                                        $int_lvl = $open->find('div[class=params] div[class=param] div[class=block_value]', $i)->plaintext;
                                        break;

                                    case " PR страницы с обзором: ":
                                        $index = $open->find('div[class=params] div[class=param] div[class=block_value]', $i)->plaintext;
                                        break;

                                    case " Адрес, куда ссылаться":
                                        $to_url = $open->find('div[class=params] div[class=param] div[class=block_value]', $i)->plaintext;
                                        break;

                                    case " Текст ссылки (анкор) ":
                                        $ankor = $open->find('div[class=params] div[class=param] div[class=block_value]', $i)->plaintext;
                                        break;

                                    case " Ключевые слова ":
                                        $key_words = $open->find('div[class=params] div[class=param] div[class=block_value]', $i)->plaintext;
                                        break;
                                    case " Атрибут alt ":
                                        $alt = $open->find('div[class=params] div[class=param] div[class=block_value]', $i)->plaintext;
                                        if (empty($ankor))
                                            $ankor = $open->find('div[class=param] div[class=params] div[class=block_value]', $i)->plaintext;
                                        break;

                                    case " Атрибут title ":
                                        $title = $open->find('div[class=params] div[class=param] div[class=block_value]', $i)->plaintext;
                                        if (empty($key_words))
                                            $key_words = $open->find('div[class=param] div[class=params] div[class=block_value]', $i)->plaintext;
                                        break;
                                }
                                $i++;
                            }
                            $task_text = $open->find('div[class=params] div[id=layer]', 0)->plaintext;
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
                    // То, что не записывается в базу данных
                    // $type, $out_url, $int_url, $int_lvl, $alt, $title, $index
                    if (!empty($ankor) && !empty($to_url)) {
                        $db->Execute("INSERT INTO zadaniya(sid, b_id, uid, sistema, ankor, url, tema, comments, vipolneno, date, keywords, vrabote, navyklad, overwrite, nof_chars) VALUES ('" . $sid . "', '" . $ggl_id . "','" . $uid . "', 'http://getgoodlinks.ru/', '" . $db->escape(trim($ankor)) . "', '" . $db->escape($to_url) . "', '" . $tema . "', '" . $db->escape($task_text) . "', '0', '" . $date . "', '" . $key_words . "', 0, 0, 0, '2000')");
                        $j++;
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
$AC->load_proxy_list(PATH . 'modules/angry_curl/proxy_list.txt', 70, 'http', 'http://www.getgoodlinks.ru/');

$new_tasks = array();
include(PATH . 'includes/simple_html_dom.php');

$last_task = $db->Execute("SELECT id FROM zadaniya WHERE sistema='http://getgoodlinks.ru/' ORDER BY id DESC LIMIT 1")->FetchRow();
$last_id = $last_task['id'];
if (empty($last_id))
    $last_id = 0;

// special for getgoodlinks.ru (birj = 2)
$query = $db->Execute("select * from birjs WHERE birj = 2");
$ggl_to_uid = array();
while ($res = $query->FetchRow()) {
    $ggl_to_uid[] = $res['uid'];
}

$birjs = $db->Execute("SELECT * FROM admins WHERE active=1 AND type='user'");
$act_uids = array();
while ($res = $birjs->FetchRow()) {
    $balance = $admins->getUserBalans($res['id'], $db, 1);
    if ($balance >= 60 || (($res['id'] == 20) || ($res['id'] == 55))) {
        if (in_array($res['id'], $ggl_to_uid)) {
            $act_uids[] = $res['id'];
        }
    }
}
$act_uids = "(" . implode(",", $act_uids) . ")";

$sayty = $db->Execute("SELECT * FROM sayty WHERE uid IN $act_uids");
$act_sites = array();
$sid_to_uid = array();
while ($res = $sayty->FetchRow()) {
    $act_sites[] = $res['id'];
    $sid_to_uid[$res['id']] = $res['uid'];
}

if ($AC->get_count_proxy() == 0) {
    $AC->load_proxy_list(PATH . 'modules/angry_curl/proxy_list_noprice.txt', 70, 'http', 'http://getgoodlinks.ru');
}

foreach ($sid_to_uid as $sid => $uid) {
    $cookie_jar = tempnam(PATH . 'temp', "cookie");

    $res = $db->Execute("select * from birjs where birj=2 AND uid=$uid")->FetchRow();
    $res2 = $db->Execute("SELECT getgoodlinks_id FROM sayty WHERE id=$sid")->FetchRow();
    $gid = $res2['getgoodlinks_id'];

    if ($gid == 0)
        continue;
    if ($res['login'] == null || $res['pass'] == null)
        continue;

    $data = array('e_mail' => $res['login'],
        'password' => $res['pass'],
        'sid' => $sid,
    );
    $query_p = json_encode($data);

    $AC->request('http://www.getgoodlinks.ru', 'POST', $query_p, null, array(CURLOPT_COOKIEJAR => $cookie_jar));
}

$AC->execute(10);
unset($AC);

$add_task = $db->Execute("SELECT * FROM zadaniya WHERE id > '$last_id' AND sistema='http://getgoodlinks.ru/'");

while ($task = $add_task->FetchRow()) {
    $new_tasks[$task['id']] = "http://iforget.ru/admin.php?module=admins&action=zadaniya&uid=" . $task['uid'] . "&sid=" . $task['sid'] . "&action2=edit&id=" . $task['id'];
}

if (count($new_tasks) !== 0) {

    $body = "Добрый день!<br/><br/>
            На сайт выгружены новые задачи из биржи <b>getgoodlinks.ru</b>.<br/><br/>";
    $subject = "[" . count($new_tasks) . " новых задач из биржи getgoodlinks]";
    foreach ($new_tasks as $knt => $vnt) {
        $body .= "<a href='$vnt'>$knt</a><br/>";
    }
} else {
    $body = "Добрый день!<br/><br/>
            В бирже GETGOODLINKS не найдено ни одной новой задачи.<br/><br/>";
    $subject = "[0 новых задач из биржи getgoodlinks]";
}

$mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
$message = array();
$message["html"] = $body;
$message["text"] = "";
$message["subject"] = $subject;
$message["from_email"] = "news@iforget.ru";
$message["from_name"] = "iforget";
$message["to"] = array();
//$message["to"][1] = array("email" => "abashevav@gmail.com");
$message["to"][0] = array("email" => MAIL_ADMIN);
$message["track_opens"] = null;
$message["track_clicks"] = null;
$message["auto_text"] = null;

try {
    $mandrill->messages->send($message);
} catch (Exception $e) {
    echo $e;
}

exit();
?>
