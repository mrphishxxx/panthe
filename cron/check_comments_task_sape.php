<?php

error_reporting(E_ALL);

require_once dirname(__FILE__) . '/../' . 'config.php';
require_once dirname(__FILE__) . '/../' . 'includes/simple_html_dom.php';
require_once dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
require_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';

$cookie_jar = tempnam(PATH . 'temp', "cookie");
$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$tasks = array();
$model = $db->Execute("SELECT * FROM zadaniya_new WHERE sistema='http://pr.sape.ru/' AND from_sape=1 AND (vipolneno=0 AND rectificate=0)");
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

$body = "Проверка заданий на наличие новых комментарий:<br><br>";
$num = 0;
if (!empty($id_user_sape) && !empty($tasks)) {
    foreach ($tasks as $id => $sape_id) {
        $data = xmlrpc_encode_request('performer.messageList', array(array("order_id" => (int) $sape_id), array("date" => "DESC"), 0, 100));
        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
            @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            $out = curl_exec($curl);
            curl_close($curl);
        }
        $messages = xmlrpc_decode($out);
        $message_text = "";
        if (isset($messages["items"]) && !empty($messages["items"])) {
            foreach ($messages["items"] as $item) {
                $message_text .= $item["message_date"] . PHP_EOL;
                $message_text .= $item["message_text"];
                $message_text .= PHP_EOL . PHP_EOL;
            }

            $task = $db->Execute("SELECT * FROM zadaniya_new WHERE id=$id")->FetchRow();
            if ($task['admin_comments'] != $message_text) {
                $body .= "Добавлен комментарий к задаче, ID = <a href ='http://iforget.ru/admin.php?module=admins&action=articles&action2=edit&id=$id'>" . $id . "</a><br>";
                $num++;
                $db->Execute("UPDATE zadaniya_new SET admin_comments='$message_text', new_comment=1 WHERE id=" . $id);
            }
        }
    }
}
if($num == 0){
    $body = "Новых комментариев нет.";
}
$mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
$message = array();
$message["html"] = $body;
$message["text"] = "";
$message["subject"] = "$num новых комментариев в Sape";
$message["from_email"] = "news@iforget.ru";
$message["from_name"] = "iforget";
$message["to"] = array();
$message["to"][0] = array("email" => MAIL_ADMIN);
//$message["to"][1] = array("email" => "abashevav@gmail.com");
$message["track_opens"] = null;
$message["track_clicks"] = null;
$message["auto_text"] = null;

try {
    $mandrill->messages->send($message);
} catch (Exception $e) {
    echo $body;
}
die("END");
?>
