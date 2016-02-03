<?php

error_reporting(E_ALL ^ E_NOTICE);
include(dirname(__FILE__) . '/../' . 'config.php');
include dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
require_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$pass = ETXT_PASS;
$num = 0;
$minute = date("i");
$error_burse = $error_sape = "";

$new_data = array();
$date = new DateTime(date("Y-m-d", time()));
$date->add(new DateInterval('P1D'));
$new_data['day'] = $date->format('d.m.Y');
$new_data['time'] = "17:00";
$body = "<h1>Перезапуск задач со статусом 'Просрочен'</h1>";


$tasks = $db->Execute("SELECT * FROM zadaniya WHERE vrabote = 1 AND vipolneno != 1 AND task_id IS NOT NULL")->GetAll();
/* Проверка старых заданий */
if (!empty($tasks)) {
    $body .= "<h3>Проверка задач из биржи</h3>";
    foreach ($tasks as $row) {
        $params = array('method' => 'tasks.listTasks', 'token' => '29aa0eec2c77dd6d06e23b3faaef9eed', 'id' => $row['task_id']);
        ksort($params);
        $data = array();
        $data2 = array();
        foreach ($params as $k => $v) {
            $data[] = $k . '=' . $v;
            $data2[] = $k . '=' . urlencode($v);
        }
        $sign = md5(implode('', $data) . md5($pass . 'api-pass'));
        $url = 'https://www.etxt.ru/api/json/?' . implode('&', $data2) . '&sign=' . $sign;
        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $cur_out = curl_exec($curl);
            curl_close($curl);
        }
        $task_stat = json_decode($cur_out);
        if (!empty($task_stat) && !isset($task_stat->error)) {
            foreach ($task_stat as $vl) {
                $vl = (array) $vl;
                if ($vl['status'] == 5) {
                    $body .= "<p>";
                    $body .= "Задача <a href='http://iforget.ru/admin.php?module=admins&action=zadaniya&uid=" . $row["uid"] . "&sid=" . $row["sid"] . "&action2=edit&id=" . $row["id"] . "'>" . $row["id"] . "</a> - просрочена в ETXT<br />";
                    $num++;

                    $params = array('method' => 'tasks.failTask', 'token' => '29aa0eec2c77dd6d06e23b3faaef9eed', 'id' => $vl['id'], 'copy' => 1, 'deadline' => $new_data['day'], 'timeline' => $new_data['time']);
                    ksort($params);
                    $data = array();
                    $data2 = array();
                    foreach ($params as $k => $v) {
                        $data[] = $k . '=' . $v;
                        $data2[] = $k . '=' . urlencode($v);
                    }
                    $sign = md5(implode('', $data) . md5($pass . 'api-pass'));
                    $url = 'https://www.etxt.ru/api/json/?' . implode('&', $data2) . '&sign=' . $sign;
                    if ($curl = curl_init()) {
                        curl_setopt($curl, CURLOPT_URL, $url);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                        $cur_out = curl_exec($curl);
                        curl_close($curl);
                    }

                    $result = json_decode($cur_out);
                    if (!empty($result['id_copy'])) {
                        $body .= "<span>Задача перезапущена!Старый ETXT_ID = " . $vl['id'] . " => Новый ETXT_ID = " . $result->id_copy . "</span>";
                        $db->Execute("UPDATE zadaniya SET task_id = " . $result->id_copy . " WHERE id=" . $row['id']);
                    } else {
                        $body .= "<span style='color: red;'>Ошибка перезапуска задачи!</span>";
                    }
                    $body .= "</p>";
                }
            }
        } else {
            $error_burse .= "<p style='color: red;'>Задача <a href='http://iforget.ru/admin.php?module=admins&action=zadaniya&uid=" . $row["uid"] . "&sid=" . $row["sid"] . "&action2=edit&id=" . $row["id"] . "'>" . $row["id"] . "</a> не найдена в ETXT!</p>";
        }
    }
    if ($error_burse != "") {
        $body .= "<h4>Некоторые задачи из Бирж не были найдены в ETXT!</h4>";
        $body .= $error_burse;
        $body .= "<br><br>";
    }
}


/* Проверка новых заданий */
$tasks_new = $db->Execute("SELECT * FROM zadaniya_new WHERE vrabote = 1 AND vipolneno != 1 AND task_id IS NOT NULL AND task_id != 0")->GetAll();
if (!empty($tasks_new)) {
    $body .= "<h3>Проверка задач из SAPE</h3>";

    foreach ($tasks_new as $row) {
        $params = array('method' => 'tasks.listTasks', 'token' => '29aa0eec2c77dd6d06e23b3faaef9eed', 'id' => $row['task_id']);
        ksort($params);
        $data = array();
        $data2 = array();
        foreach ($params as $k => $v) {
            $data[] = $k . '=' . $v;
            $data2[] = $k . '=' . urlencode($v);
        }
        $sign = md5(implode('', $data) . md5($pass . 'api-pass'));
        $url = 'https://www.etxt.ru/api/json/?' . implode('&', $data2) . '&sign=' . $sign;
        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $cur_out = curl_exec($curl);
            curl_close($curl);
        }
        $task_stat = json_decode($cur_out);

        if (!empty($task_stat) && !isset($task_stat->error)) {
            foreach ($task_stat as $vl) {
                $vl = (array) $vl;
                if ($vl['status'] == 5) {
                    $body .= "<p>";
                    $body .= "Задача <a href='http://iforget.ru/admin.php?module=admins&action=articles&action2=edit&id=" . $row["id"] . "'>" . $row["id"] . "</a> - просрочена в ETXT<br />";
                    $num++;

                    $params = array('method' => 'tasks.failTask', 'token' => '29aa0eec2c77dd6d06e23b3faaef9eed', 'id' => $vl['id'], 'copy' => 1, 'deadline' => $new_data['day'], 'timeline' => $new_data['time']);
                    ksort($params);
                    $data = array();
                    $data2 = array();
                    foreach ($params as $k => $v) {
                        $data[] = $k . '=' . $v;
                        $data2[] = $k . '=' . urlencode($v);
                    }
                    $sign = md5(implode('', $data) . md5($pass . 'api-pass'));
                    $url = 'https://www.etxt.ru/api/json/?' . implode('&', $data2) . '&sign=' . $sign;
                    if ($curl = curl_init()) {
                        curl_setopt($curl, CURLOPT_URL, $url);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                        $cur_out = curl_exec($curl);
                        curl_close($curl);
                    }

                    $result = json_decode($cur_out);
                    if (!empty($result['id_copy'])) {
                        $body .= "<span>Задача перезапущена! Старый ETXT_ID = " . $vl['id'] . " => Новый ETXT_ID = " . $result->id_copy . "</span>";
                        $db->Execute("UPDATE zadaniya_new SET task_id = " . $result->id_copy . " WHERE id=" . $row['id']);
                    }
                    $body .= "</p>";
                }
            }
        } else {
            $error_sape .= "<p style='color: red;'>Задача <a href='http://iforget.ru/admin.php?module=admins&action=articles&action2=edit&id=" . $row["id"] . "'>" . $row["id"] . "</a> не найдена в ETXT!</p>";
        }
    }
    if ($error_sape != "") {
        $body .= "<h4>Некоторые задачи из Sape не были найдены в ETXT!</h4>";
        $body .= $error_sape;
        $body .= "<br><br>";
    }
}

if ($num != 0 || $error_sape != "" || $error_burse != "") {
    $mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
    $message = array();
    $message["html"] = $body;
    $message["text"] = "";
    $message["subject"] = "Перезапуск заданий";
    $message["from_email"] = "news@iforget.ru";
    $message["from_name"] = "iforget";
    $message["to"] = array();
    $message["to"][1] = array("email" => MAIL_DEVELOPER);
    $message["to"][0] = array("email" => MAIL_ADMIN);
    $message["track_opens"] = null;
    $message["track_clicks"] = null;
    $message["auto_text"] = null;

    try {
        $mandrill->messages->send($message);
    } catch (Exception $e) {
        echo 'Ошибка отправления!';
    }
} else {
    $body .= "<h3>Нет просроченных заданий.</h3>";
}
echo $body;
exit();
?>
