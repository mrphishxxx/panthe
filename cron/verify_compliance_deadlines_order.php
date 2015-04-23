<?php

error_reporting(E_ALL ^ E_NOTICE);
include(dirname(__FILE__) . '/../' . 'config.php');
include dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
require_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');
$tasks = $db->Execute("SELECT * FROM zadaniya WHERE vrabote = 1 AND vipolneno != 1");

$pass = ETXT_PASS;
$num = 0;
$minute = date("i");

$new_data = array();
$date = new DateTime(date("Y-m-d", time()));
$date->add(new DateInterval('P1D'));
$new_data['day'] = $date->format('d.m.Y');
$new_data['time'] = "17:00";
$body = "Есть просроченные задания в системе ETXT\r\n";
/* Проверка старых заданий */
while ($row = $tasks->FetchRow()) {
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
    if (!empty($task_stat)) {
        foreach ($task_stat as $vl) {
            $vl = (array) $vl;
            if ($vl['status'] == 5) {
                //print_r($vl);

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
                $result = (array) $result;
                if (!empty($result['id_copy'])) {
                    $body .= "Задача " . $row['id'] . ": ETXT old id - " . $vl['id'] . " => new id - " . $result['id_copy'] . "\r\n";
                    $db->Execute("UPDATE zadaniya SET task_id = " . $result['id_copy'] . " WHERE id=" . $row['id']);
                }
            }
        }
    }
}
//die();
/* Проверка новых заданий */
$tasks_new = $db->Execute("SELECT * FROM zadaniya_new WHERE vrabote = 1 AND vipolneno != 1");
while ($row = $tasks_new->FetchRow()) {
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
    if (!empty($task_stat)) {
        foreach ($task_stat as $vl) {
            $vl = (array) $vl;
            if ($vl['status'] == 5) {
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
                $result = (array) $result;
                if (!empty($result['id_copy'])) {
                    $num++;
                    $body .= "Задача " . $row['id'] . ": ETXT old id - " . $vl['id'] . " => new id - " . $result['id_copy'] . "\r\n";
                    $db->Execute("UPDATE zadaniya_new SET task_id = " . $result['id_copy'] . " WHERE id=" . $row['id']);
                }
            }
        }
    }
}


if ($num == 0) {
    $body = "Нет просроченных заданий.\r\n";
}

$mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
$message = array();
$message["html"] = $body;
$message["text"] = "";
$message["subject"] = "Перезапуск заданий";
$message["from_email"] = "news@iforget.ru";
$message["from_name"] = "iforget";
$message["to"] = array();
$message["to"][1] = array("email" => MAIL_DEVELOPER);
//$message["to"][0] = array("email" => MAIL_ADMIN);
$message["track_opens"] = null;
$message["track_clicks"] = null;
$message["auto_text"] = null;

try {
    //if((int) $minute < 10)
        //$mandrill->messages->send($message);
    echo $body;
} catch (Exception $e) {
    echo 'Ошибка отправления!';
}

exit();
?>
