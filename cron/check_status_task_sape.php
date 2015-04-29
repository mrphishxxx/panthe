<?php

include(dirname(__FILE__) . '/../' . 'config.php');
error_reporting(E_ALL);
include(dirname(__FILE__) . '/../' . 'includes/simple_html_dom.php');
include dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
require_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';

$cookie_jar = tempnam(PATH . 'temp', "cookie");
$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$tasks = array();
$model = $db->Execute("SELECT * FROM zadaniya_new WHERE sistema='http://pr.sape.ru/' AND from_sape=1 AND vilojeno=1");
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
$body = "Проверка заданий:<br>";
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
        $body .= $sape_id."(".$task["status"].");<br> ";
        if ($task["status"] == 50) {
            //echo "Выполнена задача, ID = ".$id."<br>";
            $body .= "Выполнена задача, ID = ".$id."<br>";
            $db->Execute("UPDATE zadaniya_new SET vilojeno='0', vipolneno='1', dorabotka = '0', navyklad = '0' WHERE id=" . $id);
        } else if ($task["status"] == 20) {
            //echo "Вернулась на доработку, ID = ".$id."<br>";
            $body .= "Вернулась на доработку, ID = ".$id."<br>";
            $db->Execute("UPDATE zadaniya_new SET vilojeno='0', dorabotka='1' WHERE id=" . $id);
        }

    }
}
$mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
$message = array();
$message["html"] = $body;
$message["text"] = "";
$message["subject"] = "Проверка заданий из Сапы";
$message["from_email"] = "news@iforget.ru";
$message["from_name"] = "iforget";
$message["to"] = array();
$message["to"][0] = array("email" => "abashevav@gmail.com");
//$message["to"][1] = array("email" => MAIL_ADMIN);
$message["track_opens"] = null;
$message["track_clicks"] = null;
$message["auto_text"] = null;

try {
    //$mandrill->messages->send($message);
    echo $body;
} catch (Exception $e) {
    echo $body;
}
die("END");
?>
