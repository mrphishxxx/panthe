<?php

include_once dirname(__FILE__) . '/../' . 'config.php';
include_once dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
include_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';
include_once dirname(__FILE__) . '/../' . 'includes/textRu.php';

error_reporting(E_ALL);

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$API = new APITextRu(TEXTRU_APIKEY);
$tasks = $db->Execute('SELECT id, text FROM zadaniya_new WHERE copywriter != 0 AND navyklad=1 AND textru_id IS NULL AND (text != "" AND text IS NOT NULL) ORDER BY id DESC')->GetAll();

if (!empty($tasks)) {
    foreach ($tasks as $task) {
        $result = $API->addPost($task["text"]);
        if (is_string($result)) {
            $db->Execute('UPDATE zadaniya_new SET textru_id = "' . $result . '" WHERE id = ' . $task["id"]);
        } elseif (is_array($result)) {
            require_once 'includes/mandrill/mandrill.php';
            $mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
            $mail = array();
            $mail["html"] = "<br>Не получилось отправить задачу в text.ru (проверка текста в задаче " . $task["id"] . " - не возможна). <br> ОШИБКА: " . $result["code"] . "-> " . $result["description"];
            $mail["text"] = "";
            $mail["subject"] = "[ОШИБКА отправления в TEXT.RU]";
            $mail["from_email"] = "news@iforget.ru";
            $mail["from_name"] = "iforget";
            $mail["to"] = array();
            $mail["to"][0] = array("email" => MAIL_ADMIN);
            $mail["to"][1] = array("email" => MAIL_DEVELOPER);
            $mail["track_opens"] = null;
            $mail["track_clicks"] = null;
            $mail["auto_text"] = null;

            try {
                $mandrill->messages->send($mail);
            } catch (Exception $e) {
                echo 'Сообщение не отправлено!';
            }
        }
    }
}


die("die");
?>
