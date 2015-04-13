<?php

include dirname(__FILE__) . '/../' . 'config.php';
include dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
require_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';
error_reporting(E_ALL);

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$task_copywriter = array();
$add_task = $db->Execute("SELECT * FROM zadaniya_new WHERE copywriter=0 AND for_copywriter=1 ORDER BY id ASC");
while ($task = $add_task->FetchRow()) {
    $task_copywriter[] = $task['id'];
}

if (count($task_copywriter) != 0) {
    $body = "Добрый день!<br/><br/>
            На сайт <a href='http://iforget.ru/'>iForget</a> выгружены новые задачи для написания текстов.<br/>
            Для того чтобы взять в работу одну или несколько из этих задач перейдите в Ваш <a href='http://iforget.ru/copywriter.php'>личный кабинет</a>.<br/><br/>
            Спасибо!<br/>
            Администрация iForget.
            <br /><small><a href='http://iforget.ru/copywriter.php?action=unsubscribe'>Отписаться от рассылки</a></small>";
    
    $mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
    $message = array();
    $message["html"] = $body;
    $message["text"] = "";
    $message["subject"] = "[" . count($task_copywriter) . " новых задач]";
    $message["from_email"] = "news@iforget.ru";
    $message["from_name"] = "iforget";
    $message["track_opens"] = null;
    $message["track_clicks"] = null;
    $message["auto_text"] = null;
    $message["to"] = array();
    $copywriters = $db->Execute("SELECT * FROM admins WHERE type='copywriter' AND active=1");
    while ($copywriter = $copywriters->FetchRow()) {
        if($copywriter["mail_period"] > 0 && $copywriter["banned"] == 0){
            $message["to"][] = array("email" => $copywriter["email"], "name" => $copywriter["login"]);
        }
    }
    try {
        if(count($message["to"]) > 0)
            $mandrill->messages->send($message);
    } catch (Exception $e) {
        echo $e;
        echo $body;
    }
}
exit();
?>
