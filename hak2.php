<?php

error_reporting(E_ALL ^ E_NOTICE);
include('config.php');
include 'includes/adodb5/adodb.inc.php';
require_once 'includes/mandrill/mandrill.php';

$cookie_jar = tempnam(PATH . 'temp', "cookie");

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$tasks = $tasks_dont_accept = array();
$model = $db->Execute("SELECT * FROM zadaniya_new WHERE sistema='http://pr.sape.ru/' AND vipolneno!=1 AND rectificate=0 AND etxt=1");
while ($res = $model->FetchRow()) {
    $tasks[$res["id"]] = $res["sape_id"];
}

/* Авторизуемся */
$url = "http://api.articles.sape.ru/performer/index/";
if ($curl = curl_init()) {
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
    @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, xmlrpc_encode_request('performer.login', array(LOGIN_IN_SAPE, PASS_IN_SAPE)));
    $out = curl_exec($curl);
    curl_close($curl);
}
$id_user_sape = xmlrpc_decode($out);

$body = "Проверка заданий для пользователя $id_user_sape:<br>";
if (!empty($id_user_sape) && !empty($tasks)) {
    foreach ($tasks as $id => $sape_id) {
        $data = xmlrpc_encode_request('performer.orderDetails', array((int) $sape_id));
        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
            @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            $detail = curl_exec($curl);
            curl_close($curl);
        }
        $task = xmlrpc_decode($detail);
        $body .= $sape_id . "(" . $task["status"] . ");<br> ";
        if ($task["status"] == 5) {
            $tasks_dont_accept[] = $sape_id;
        }
    }
}
if (!empty($id_user_sape) && !empty($tasks_dont_accept)) {
    $data = xmlrpc_encode_request('performer.orderAccept', array($tasks_dont_accept));
    if ($curl = curl_init()) {
        curl_setopt($curl, CURLOPT_URL, "http://api.articles.sape.ru/performer/index/");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
        @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $out = curl_exec($curl);
        curl_close($curl);
    }
    $accept = xmlrpc_decode($out);
}
print_r($accept);
$mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
$message = array();
$message["html"] = $body;
$message["text"] = "";
$message["subject"] = "Хак! Перевод заданий из Сапы в статус принято.";
$message["from_email"] = "news@iforget.ru";
$message["from_name"] = "iforget";
$message["to"] = array();
$message["to"][0] = array("email" => "abashevav@gmail.com");
$message["track_opens"] = null;
$message["track_clicks"] = null;
$message["auto_text"] = null;

try {
    $mandrill->messages->send($message);
    echo $body;
} catch (Exception $e) {
    echo $body;
}
die("DIE");
?>
