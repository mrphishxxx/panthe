<?php

echo date("d-m-Y H:i:s") . PHP_EOL;
include_once dirname(__FILE__) . '/../' . 'config.php';
include_once dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
include_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';

error_reporting(E_ALL);

require_once( PATH . 'modules/angry_curl/RollingCurl.class.php');
require_once( PATH . 'modules/angry_curl/AngryCurl.class.php');
global $db;
global $AC;
global $yes, $no;

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$AC = new AngryCurl('sendTaskGGL');
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

$users = $db->Execute("SELECT uid FROM zadaniya WHERE sistema = 'https://gogetlinks.net/' AND removed = 1 AND b_id != 0 GROUP BY uid");
while ($user = $users->FetchRow()) {
    $act_uids[] = $user['uid'];
}
foreach ($act_uids as $uid) {
    $cookie_jar = tempnam(PATH . 'temp', "cookie");

    $data = array(
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
$AC->execute(3);
unset($AC);

if ($yes != "" || $no != "") {
    $body = "<h2>Отправка задач на удаление</h2>"
            . "<h3>Задачи, которые были отправлены:</h3> " . $yes;
    $body .= "<br /><br /> <h3>Задачи, которые НЕ отправлены:</h3> " . $no;

    $mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
    $message = array();
    $message["html"] = $body;
    $message["text"] = "";
    $message["subject"] = "Отправка задач на удаление GGL";
    $message["from_email"] = "news@iforget.ru";
    $message["from_name"] = "iforget";
    $message["to"] = array();
    $message["to"][0] = array("email" => MAIL_DEVELOPER);
    $message["to"][1] = array("email" => MAIL_ADMIN);
    $message["track_opens"] = null;
    $message["track_clicks"] = null;
    $message["auto_text"] = null;

    try {
        $mandrill->messages->send($message);
        //echo $body;
    } catch (Exception $e) {
        echo $e;
        //echo $body;
    }
}
exit();

function sendTaskGGL($response, $info, $request) {
    global $db;
    global $AC;
    global $yes, $no;

    $yes .= "";
    $no .= "";
    $post = json_decode($request->post_data);
    $cookie_jar = $request->options[CURLOPT_COOKIEJAR];
    echo $post->uid . "{" . PHP_EOL;
    if ($info['http_code'] !== 200) {
        AngryCurl::add_debug_msg("FAILED INTO FUNCTION (uid=" . $post->uid . ") " . $request->options[CURLOPT_PROXY] . "| CODE:" . $info['http_code'] . "| TIME:" . $info['total_time'] . "| URL:" . $info['url']);
        $key = array_search($request->options[CURLOPT_PROXY], $AC->array_proxy, true);
        unset($AC->array_proxy[$key]);
        $AC->__set('n_proxy', $AC->__get('n_proxy') - 1);
        $AC->request('https://gogetlinks.net/', 'POST', $request->post_data, null, array(CURLOPT_COOKIEJAR => $cookie_jar));
    } else {
        $tasks = $db->Execute("SELECT * FROM zadaniya WHERE sistema = 'https://gogetlinks.net/' AND removed = 1 AND b_id != 0 AND uid = " . $post->uid)->GetAll();
        if (!empty($tasks)) {
            $birj = $db->Execute("SELECT * FROM birjs WHERE birj='1' AND uid=" . $post->uid)->FetchRow();
            $data = array('e_mail' => $birj['login'], 'password' => trim($birj['pass']), 'remember' => "");

            $cookie_jar = tempnam(PATH . 'temp', "cookie");
            $auth = executeRequest('POST', 'https://gogetlinks.net/login.php', null, $cookie_jar, array(), $data, null, $request->options[CURLOPT_PROXY], $request->options[CURLOPT_PROXYUSERPWD]);
            $page = iconv("windows-1251", "utf-8", $auth);
            //echo $page;
            if ($page == "Некорректный Логин или Пароль" || $page == "Некорректный email или Пароль") {
                /*  Если НЕ залогинились отправляем ошибку админу  */
                $no .= "Не получилось войти (uid = " . $post->uid . "), ошибка: " . $page;
                return;
            }
            if (strstr($page, "Вам заблокирован доступ к сайту")) {
                $no .= "Прокси (".$request->options[CURLOPT_PROXY].") попал в бан, попробуйте позже (uid = " . $post->uid . ")";
                return;
            }
            foreach ($tasks as $task) {
                echo $task["id"] . PHP_EOL;
                
                $data = array('curr_id' => $task["b_id"]);
                $send = executeRequest('POST', 'https://gogetlinks.net/template/check_remove_link.php', null, $cookie_jar, array(), $data, null, $request->options[CURLOPT_PROXY], $request->options[CURLOPT_PROXYUSERPWD]);
                $out = json_decode($send);
                if(!isset($out->message)) {
                    $no .= "Ошибка отправления (uid=" . $post->uid . ", taskId=" . $task["id"] . "):" . $out;
                    return;
                }
                if (strstr($out->message, "Обзор успешно удален")) {
                    /*  Если ответ с ГГЛ "НОРМ", то подтверждаем отправку  */
                    $yes .= "Задача <a href='http://iforget.ru/admin.php?module=admins&action=zadaniya&uid=" . $task["uid"] . "&sid=" . $task["sid"] . "&action2=edit&id=" . $task["id"] . "'>" . $task["id"] . "</a> сдана в GGL.<br />";
                    $db->Execute("UPDATE zadaniya SET vipolneno=1, removed=0 WHERE id = " . $task["id"]);
                    $exist = $db->Execute("SELECT * FROM completed_tasks WHERE zid = " . $task["id"])->FetchRow();
                    if (!empty($exist)) {
                        $db->Execute("UPDATE completed_tasks SET price=15 WHERE id = " . $exist["id"]);
                    } else {
                        $db->Execute("INSERT INTO completed_tasks (uid, zid, date, price, status) VALUES ('" . $task["uid"] . "', '" . $task["id"] . "', '" . date("Y-m-d H:i:s") . "', '15',1)");
                    }
                } else {
                    echo $out->message . PHP_EOL;
                    /*  Иначе отправляем ошибку админу  */
                    $no .= "Задача <a href='http://iforget.ru/admin.php?module=admins&action=zadaniya&uid=" . $task["uid"] . "&sid=" . $task["sid"] . "&action2=edit&id=" . $task["id"] . "'>" . $task["id"] . "</a> Не сдана! Ошибка: " . $out->message . "<br />";
                }
            }
        }
    }
    echo "}" . PHP_EOL;
    return;
}

function executeRequest($method, $url, $useragent, $cookie, $query, $body, $header, $proxy, $proxy_pass) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    if (count($query) > 0) {
        $url = $url . '&' . http_build_query($query);
    }
    if ($cookie) {
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
    }
    if ($useragent) {
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    }
    if ($method == 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }
    if ($header) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    
    curl_setopt($ch, CURLOPT_PROXY, $proxy);
    curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy_pass);
    
    curl_setopt($ch, CURLOPT_URL, $url);
    $response = curl_exec($ch);
    if (!$response) {
        curl_close($ch);
        return curl_error($ch);
    }
    curl_close($ch);
    return $response;
}
