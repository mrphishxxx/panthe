<?php

error_reporting(E_ALL ^ E_NOTICE);
include(dirname(__FILE__) . '/../' . 'config.php');
include dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
require_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$subject_admin = $body_admin = "";
$result = $db->Execute("UPDATE admins SET `new_user` = '1'");
if(!$result) {
    $subject_admin = "ERROR! Ошибка перевода всех на новые цены!";
    $body_admin = "ОШИБКА ИЗМЕНЕНИЯ ЗНАЧЕНИЙ БАЗЫ ДАННЫХ: " . $db->ErrorMsg();
} else {
    $body_admin = "Добрый день!<br/><br/>
                   Все пользователи переведены на новые цены!
                   <br/><br/>С Уважением, Администрация сервиса iforget.ru";
    $subject_admin = "Новые цены для всех пользователей iForget!";
}

$mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
$message = array();
$message["html"] = $body_admin;
$message["text"] = "";
$message["subject"] = $subject_admin;
$message["from_email"] = "news@iforget.ru";
$message["from_name"] = "iforget";
$message["to"] = array();
$message["to"][0] = array("email" => MAIL_ADMIN);
$message["to"][1] = array("email" => "abashevav@gmail.com");
$message["track_opens"] = null;
$message["track_clicks"] = null;
$message["auto_text"] = null;

try {
    $mandrill->messages->send($message);
} catch (Exception $e) {
    echo $e;
    echo $body_admin;
}
exit();


?>
