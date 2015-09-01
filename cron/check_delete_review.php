<?php

echo date("d-m-Y H:i:s");
include_once dirname(__FILE__) . '/../' . 'config.php';
include_once dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
include_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';

error_reporting(E_ALL);

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$tasks = $db->Execute("SELECT * FROM zadaniya WHERE sistema = 'https://gogetlinks.net/' AND removed = 1 AND b_id != 0")->GetAll();
$body = $yes = $no = "";
foreach ($tasks as $task) {
    $birj = $db->Execute("select * from birjs where birj='1' AND uid=" . $task["uid"])->FetchRow();
    $data = array('e_mail' => $birj['login'], 'password' => trim($birj['pass']), 'remember' => "");

    $cookie_jar = tempnam(PATH . 'temp', "cookie");
    $auth = executeRequest('POST', 'https://gogetlinks.net/login.php', null, $cookie_jar, array(), $data, null);
    $page = iconv("windows-1251", "utf-8", $auth);
    
    if ($page == "Некорректный Логин или Пароль" || $page == "Некорректный email или Пароль") {
        /*  Если НЕ залогинились отправляем ошибку админу  */
        $no .= "Не получилось войти, ошибка: ".$page;
    } else {
        echo $task["id"] . PHP_EOL;
        /*  ИНАЧЕ отправляем ссылку в ГГЛ  */
        $data = array('curr_id' => $task["b_id"]);
        $send = executeRequest('POST', 'https://gogetlinks.net/template/check_remove_link.php', null, $cookie_jar, array(), $data, null);
        $out = json_decode($send);
        if (strstr($out->message, "Обзор успешно удален")) {
            /*  Если ответ с ГГЛ "НОРМ", то подтверждаем отправку  */
            $yes .= "Задача <a href='http://iforget.ru/admin.php?module=admins&action=zadaniya&uid=".$task["uid"]."&sid=".$task["sid"]."&action2=edit&id=".$task["id"]."'>".$task["id"]."</a> сдана в GGL.<br />";
            $db->Execute("UPDATE zadaniya SET vipolneno=1, removed=0 WHERE id = ".$task["id"]);
            $exist = $db->Execute("SELECT * FROM completed_tasks WHERE zid = ".$task["id"])->FetchRow();
            if(!empty($exist)) {
                $db->Execute("UPDATE completed_tasks SET price=15 WHERE id = ".$exist["id"]);
            } else {
                $db->Execute("INSERT INTO completed_tasks (uid, zid, date, price, status) VALUES ('".$task["uid"]."', '".$task["id"]."', '" . date("Y-m-d H:i:s") . "', '15',1)");
            }
        } else {
            echo $out->message . PHP_EOL;
            /*  Иначе отправляем ошибку админу  */
            $no .= "Задача <a href='http://iforget.ru/admin.php?module=admins&action=zadaniya&uid=".$task["uid"]."&sid=".$task["sid"]."&action2=edit&id=".$task["id"]."'>".$task["id"]."</a> Не сдана! Ошибка: ".$out->message."<br />";
        }
    }
}
if($yes != "" || $no != ""){
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

function executeRequest($method, $url, $useragent, $cookie, $query, $body, $header) {
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
    curl_setopt($ch, CURLOPT_URL, $url);
    $response = curl_exec($ch);
    if (!$response) {
        curl_close($ch);
        return curl_error($ch);
    }
    curl_close($ch);
    return $response;
}