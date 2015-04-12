<?php

$start = time();
include(dirname(__FILE__) . '/../' . 'config.php');
include dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
require_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';
error_reporting(E_ALL);

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');


$result = $db->Execute("SELECT SUM(count) as sum FROM task_from_mira")->FetchRow();


if ($result["sum"] != 0) {

    $body = "Добрый день!<br/><br/>
            На сайт выгружены новые задачи из биржи <b>miralinks.ru</b>.<br/><br/>";
    $subject = "[" . $result["sum"] . " новых задач из биржи miralinks]";
    $tasks = $db->Execute("SELECT * FROM zadaniya WHERE (miralinks_id IS NOT NULL AND miralinks_id != 0) AND sistema = 'http://miralinks.ru/' ORDER BY id DESC LIMIT ".$result["sum"]);
    while ($task = $tasks->FetchRow()) {
        $body .= "<a href='http://iforget.ru/admin.php?module=admins&action=zadaniya&uid=".$task['uid'] . "&sid=" . $task['sid'] . "&action2=edit&id=" . $task['id']."'>".$task['id']."</a><br/>";
    }
} else {
    $body = "Добрый день!<br/><br/>
            В бирже miralinks не найдено ни одной новой задачи.<br/><br/>";
    $subject = "[0 новых задач из биржи miralinks]";
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
    echo $body;
} catch (Exception $e) {
    echo $body;
}
$db->Execute("UPDATE task_from_mira SET count=0");
die();
?>
