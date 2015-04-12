<?php

error_reporting(E_ALL ^ E_NOTICE);
include(dirname(__FILE__).'/../'.'config.php');
include dirname(__FILE__).'/../'.'includes/adodb5/adodb.inc.php';

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');


//Задача для крона, проверяет, не прошло ли 5 дней с момента завершения заявки, если прошло - пишет статус = 1, после этого из баланса начинает вычитаться эта сумма
$completed_tasks = $db->Execute("SELECT * FROM completed_tasks WHERE status=0");
while ($row = $completed_tasks->FetchRow()) {
    $startTimeStamp = strtotime($row['date']);
    $endTimeStamp = strtotime(date("Y-m-d H:i:s"));

    $timeDiff = abs($endTimeStamp - $startTimeStamp);

    $numberDays = $timeDiff / 86400;  // 86400 seconds in one day
    $numberDays = intval($numberDays);

    if ($numberDays > 5) {
        $ct_id = $row['id'];
        $db->Execute("UPDATE completed_tasks SET status=1 WHERE id=$ct_id");
    }
}
exit();
?>
