<?php

error_reporting(E_ALL);
include(dirname(__FILE__) . '/../' . 'config.php');
include dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
require_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';
$mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$arr = array();
$body = "Несколько задач были сняты с копирайтеров, т.к. текст не был написан более суток.<br/>";
//Задача для крона, проверяет, не прошло ли 1 день с момента принятия заявки в работу копирайтером
// если прошло - снимает с него задачу, убирает статус В работе, задача становится сбодной для других копирайтеров
// 
// Осуществляется проверка: если до снятия осталось 2 часа, то отправляется напоминание Копирайтеру, о том, что надо сдать задачу
$tasks = $tasks2 = $db->Execute("SELECT z.*, a.login, a.email, a.mail_period FROM zadaniya_new z LEFT JOIN admins a ON a.id=z.copywriter WHERE z.copywriter!=0 AND (z.vrabote=1 OR z.rework=1) AND z.date_in_work IS NOT NULL");
if(!empty($tasks)) {
    while ($row = $tasks->FetchRow()) {
        $now = time();
        $in_work_time = $row['date_in_work'];
        $timeDiff = abs($now - $in_work_time);
        $numberDays = $timeDiff / 86400;  // 86400 seconds in one day
        if ($numberDays >= 1) {
            $arr[] = $row['id'];
            $db->Execute("UPDATE zadaniya_new SET date_in_work=NULL, vrabote=0, rework=0, dorabotka=0, copywriter=0 WHERE id=" . $row['id']); //text='', 
            $db->Execute("INSERT INTO prohibition_taking_tasks (user_id, task_id) VALUE ('".$row['copywriter']."', '".$row['id']."')");
            $body .= "Задача <a href='http://iforget.ru/admin.php?module=admins&action=articles&action2=edit&id=" . $row['id'] . "'>" . $row['id'] . "</a> снята с копирайтера '" . $row['login'] . "'<br/>";
        
            /*$prohibition = $db->Execute("SELECT COUNT(*) AS cnt, `user_id` FROM `prohibition_taking_tasks` WHERE `user_id` = '".$row['copywriter']."' GROUP BY `user_id`")->FetchRow();
            if($prohibition["cnt"] == LIMIT_ERROR_FROM_COPYWRITER) {
                $db->Execute("UPDATE admins SET banned = '1' WHERE id = '".$row['copywriter']."'");
                $body .= "Данный копирайтер просрочил уже ".LIMIT_ERROR_FROM_COPYWRITER. " задачи! Он переведен в статус Забанен!<br>";
            }*/
        }
    }
    $body .= "<br/>С уважением, Администрация iForget!";
    if (count($arr) > 0) {
        $message = array();
        $message["html"] = $body;
        $message["text"] = "Некоторые задачи не были сделаны копирайтерами. Эти задачи вернулись для выбора.";
        $message["subject"] = "[Задачи сняты с копирайтеров]";
        $message["from_email"] = "news@iforget.ru";
        $message["from_name"] = "iforget";
        $message["to"] = array();
        $message["to"][0] = array("email" => MAIL_ADMIN);
        //$message["to"][1] = array("email" => MAIL_DEVELOPER);
        $message["track_opens"] = null;
        $message["track_clicks"] = null;
        $message["auto_text"] = null;

        try {
            $mandrill->messages->send($message);
            echo $body;
        } catch (Exception $e) {
            echo 'Сообщение не отправлено!<br/>';
            echo $body;
        }
    }
    
    while ($row = $tasks2->FetchRow()) {
        $now = time();
        $in_work_time = $row['date_in_work'];
        $timeDiff = abs($now - $in_work_time);
        $numberDays = $timeDiff / 86400;  // 86400 seconds in one day
        if ($timeDiff < 7200 && $numberDays < 1) { // 7200 seconds in two hours
            $body = "<p>Добрый день ".$row['login'] ."!</p>";
            if(empty($row["text"])){
                $body .= "<p>До сдачи задания <a href='http://iforget.ru/copywriter.php?action=tasks&action2=edit&id=" . $row['id'] . "'>" . $row['id'] . "</a> осталось 2 часа!</p>
                          <p>Поторопитесь! Иначе задача уйдет к другому копирайтеру!</p>
                          <p><br /><small><a href='http://iforget.ru/copywriter.php?action=unsubscribe'>Отписаться от рассылки</a></small></p>";
            } else {
                $body .= "<p>До сдачи задания <a href='http://iforget.ru/copywriter.php?action=tasks&action2=edit&id=" . $row['id'] . "'>" . $row['id'] . "</a> осталось 2 часа!</p>
                          <p>Если текст готов, то измените статус задачи на 'Готов'!</p> 
                          <p>Иначе задача уйдет к другому копирайтеру, текст удалится и задача не будет засчитана!</p>
                          <p><br /><small><a href='http://iforget.ru/copywriter.php?action=unsubscribe'>Отписаться от рассылки</a></small></p>";
            }
            $body .= "С Уважением!<br/>Администрация iForget.";
            $message["html"] = $body;
            $message["text"] = "Время на работу заканчивается! Поспешите!";
            $message["subject"] = "[Задание скоро будет снято]";
            $message["to"][0] = array("email" => $row['email']);
            try {
                if($row["mail_period"] > 0){
                    $mandrill->messages->send($message);
                }
                echo $body . "<br/><br/>";
            } catch (Exception $e) {
                echo 'Сообщение не отправлено копирайтеру - '.$row['login'].'! Ему осталось два часа на задачу - '.$row['id'].'!<br/>';
            }
        }
    }
}
exit();
?>
