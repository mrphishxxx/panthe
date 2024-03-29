<?php

error_reporting(E_ALL);
include dirname(__FILE__) . '/../' . 'config.php';
include dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
include dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';
include dirname(__FILE__) . '/../' . 'includes/getbotapi.php';

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$day_start = 1432069200; //20.05.2015, 0:00:00
$day_end = time() - 90000; //90000 - отнимаем сутки + 1 час (время сервера)
$api = new GetbotApi(GETBOT_APIKEY);

$body = "<h3>Запуск задач в GetBot от " . date("d-m-Y H:i:s") . "</h3>";
$body .= "<small>Выгружаются задачи, дата создания у которых не более " . date("d-m-Y H:i:s", $day_end) . " (" . $day_end . ")</small><br><br>";
// Проверяем баланс, если он больше нуля, то запускаем новые задачи в гетбот
$user = $api->userBalance();

if ($user->balance > 0) {
    $tasks_created = $api->tasksList(GetbotApi::STATUS_CREATED);
    if (!empty($tasks_created)) {
        $body .= "<strong>Задачи, которые уже есть в GetBot и осталось только запустить:</strong><br>";
        foreach ($tasks_created as $task_in_gb) {
            if ($task_in_gb->can_launch) {
                $result = $api->taskLaunch($task_in_gb->id); // StdClass: status
                if ($result->status == 'ok') {
                    $body .= $task_in_gb->id . " -> Задание успешно запущено<br>";
                    echo "Задание успешно запущено";
                } else if (!empty($result->errors)) {
                    $body .= $task_in_gb->id . " -> Ошибка запуска задания:<br>";
                    foreach ($result->errors as $error) {
                        $body .= "&emsp; - " . $error . "<br>";
                        echo "Ошибка запуска задания: " . $error . "<br>";
                    }
                }
            }
        }
        $body .= "<br>";
    }
}

$links = $ids = array();
// Заново проверяем баланс, если он Больше нуля, то выгружаем новые задачи в гетбот и запускаем их
$balance = $api->userBalance();
if ($balance->balance > 0) {
    // Вытаскиваем все ВЫПОЛНЕНЫЕ задачи, у которых есть ссылка на статью и которые ещё не загружались в ГетБот
    // ВАЖНО! Задания должны попасть в дату БОЛЬШУЮ начала выгрузки (чтобы не загружать в гетбот лишнее) и МЕНЬШУЮ "сегодня минус 1 день"
    $zadaniya = $db->Execute("SELECT id, url_statyi FROM zadaniya WHERE type_task !=3 AND vipolneno='1' AND getbot='0' AND (date > '$day_start' AND date < '$day_end') AND (url_statyi != '' AND url_statyi IS NOT NULL) ORDER BY date")->GetAll();
    if (!empty($zadaniya)) {
        $body .= "<br><strong>Выгружаем новые задачи в GetBot  и запускаем их:</strong><br>";
        foreach ($zadaniya as $value) {
            if (strripos($value["url_statyi"], "http") !== false) {
                $links[$value["id"]] = $value["url_statyi"];
                $ids[] = $value["id"];
            }
        }
        $data = date("d-m-Y H:i:s");
        $description = implode(", ", $ids);
        $task = $api->taskCreate($data, $links, GetbotApi::MODE_ABSOLUTE_UPDATE, $description);
        if (isset($task->can_launch)) {
            $id = $task->id;
            $result = $api->taskLaunch($id); // >>StdClass: status
            if ($result->status == 'ok') {
                $body .= "Задания ($description) успешно запущены<br>";
                $db->Execute("UPDATE zadaniya SET getbot='$id' WHERE id IN ($description)");
                echo "Задания ($description) успешно запущены<br>";
            } else if (!empty($result->errors)) {
                $body .= "Ошибка запуска задания:<br>";
                foreach ($result->errors as $error) {
                    $body .= "&emsp; - " . $error . "<br>";
                    echo "Ошибка запуска задания: " . $error . "<br>";
                }
            }
        } else if (isset($task->errors)) {
            $body .= "<br><p style='color:red;'><strong>Ошибка запуска задания:<strong></p>";
            foreach ($task->errors as $error) {
                $body .= "&emsp; - <span style='color:red;'>" . $error . "</span>";
                echo "Ошибка запуска задания: " . $error . "<br>";
            }
        }
    }
} else {
    $body .= "Невозможно запустить проекты, так как не хватает средств на счету";
    echo "Невозможно запустить проекты, так как не хватает средств на счету";
}

$mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
$message = array();
$message["html"] = $body;
$message["text"] = "";
$message["subject"] = "Запуск задач в GetBot";
$message["from_email"] = "news@iforget.ru";
$message["from_name"] = "iforget";
$message["to"] = array();
$message["to"][0] = array("email" => MAIL_ADMIN);
$message["to"][1] = array("email" => MAIL_DEVELOPER);
$message["track_opens"] = null;
$message["track_clicks"] = null;
$message["auto_text"] = null;

try {
    $mandrill->messages->send($message);
} catch (Exception $e) {
    echo $e;
    echo $body;
}
echo "THE END\r\n";
exit();
