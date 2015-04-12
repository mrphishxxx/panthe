<?php

include(dirname(__FILE__) . '/../' . 'config.php');
error_reporting(E_ALL);
include(dirname(__FILE__) . '/../' . 'includes/simple_html_dom.php');
include dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
require_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$last_task = $db->Execute("SELECT id FROM zadaniya_new WHERE sistema='http://pr.sape.ru/' AND from_sape=1 ORDER BY id DESC LIMIT 1")->FetchRow();
if (!empty($last_task))
    $last_id = $last_task['id'];
else
    $last_id = 0;

$buff = array();
$result = $db->Execute("SELECT * FROM zadaniya_new WHERE from_sape=1 AND (sape_id IS NOT NULL AND sape_id != 0) AND sistema = 'http://pr.sape.ru/'");
while ($add = $result->FetchRow()) {
    $buff[$add["id"]] = $add["sape_id"];
}

$data = xmlrpc_encode_request('performer.login', array(LOGIN_IN_SAPE, PASS_IN_SAPE));
$cookie_jar = tempnam(PATH . 'temp', "cookie");

/* Авторизуемся */
$url = "http://api.articles.sape.ru/performer/index/";
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
$id_user_sape = xmlrpc_decode($out);

$data = xmlrpc_encode_request('performer.orderList', array(array("status" => 5), array("status" => 1), 1, 100));
if ($curl = curl_init()) {
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
    @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    $out2 = curl_exec($curl);
    curl_close($curl);
}
$tasks = xmlrpc_decode($out2);
$rectificate = "<br />Вернулись задачи:<br /><br />";
$task_add = $task_old = array();

if (!empty($tasks["items"])) {
    foreach ($tasks["items"] as $task) {
        $data = xmlrpc_encode_request('performer.orderDetails', array((int) $task["id"]));
        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
            @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            $orderDetails = curl_exec($curl);
            curl_close($curl);
        }
        $task_new = xmlrpc_decode($orderDetails);

        if (!in_array($task["id"], $buff)) {
            $task_add[] = $task_new;
        } else {
            $old = $db->Execute("SELECT * FROM zadaniya_new WHERE sape_id=" . $task_new["id"])->FetchRow();
            if ($old['rectificate'] == 1) {
                $new_date = time();
                $task_old[] = $task_new;
                $db->Execute("UPDATE zadaniya_new SET rectificate='0', vrabote='0', vipolneno='0', dorabotka='0', navyklad='0', vilojeno='0', date='$new_date' WHERE sape_id=" . $task_new["id"]);
                $id = array_search($task_new['id'], $buff);
                $rectificate .= "<a href='http://iforget.ru/admin.php?module=admins&action=articles&action2=edit&id=" . $id . "'>" . $id . "</a><br/>";
            }
        }
    }
}
//$out_tasks_percent = 100; // процент выдаваемых задач
//$end_task = floor((count($task_add) / 100) * $out_tasks_percent); //ceil - округляет в большую сторону
//$num_task = 0;
foreach ($task_add as $task) {
    if (!empty($task)) {
        $price = 20;
        $for_copywriter = 1;
        switch ($task["format_id"]) {
            case 102:
                $type = 1; //"Обзор"
                break;
            case 112:
                $type = 1; //"Обзор"
                $price = 30;
                break;
            case 101:
                $type = 2; //"Новость"
                break;
            case 111:
                $type = 2; //"Новость"
                $price = 30;
                break;
            default : $type = 0;
        }

        $links = $keywords = $tema = "";
        $url = $anchor = array();
        foreach ($task["url_list"] as $link) {
            $url[] = $link["url"];
            $anchor[] = $link["anchor"];
        }
        for ($i = 0; $i < 5; $i++) {
            if (isset($anchor[$i]) && !empty($anchor[$i])) {
                $links .= "'" . mysql_real_escape_string($anchor[$i]) . "',";
                $keywords .= mysql_real_escape_string($anchor[$i]) . ",";
            } else {
                $links .= "'',";
            }
        }

        for ($i = 0; $i < 5; $i++) {
            if (isset($url[$i]) && !empty($url[$i])) {
                $links .= "'" . mysql_real_escape_string($url[$i]) . "',";
            } else {
                $links .= "'',";
            }
        }
        $links = trim($links, ",");
        $keywords = trim($keywords, ",");

        if ($type == 2 && isset($anchor[0])) {
            $first = mb_strtoupper(mb_substr($anchor[0], 0, 1, 'UTF-8'), 'UTF-8'); //первая буква
            $first = str_replace("?", "", $first);
            $last = mb_strtolower(mb_substr($anchor[0], 1), 'UTF-8'); //все кроме первой буквы
            $last = ($last[0] == "?") ? mb_substr($last, 1) : $last;
            $tema = mysql_real_escape_string($first . $last);
        } else {
            $name = mb_strtolower($url[0], 'UTF-8'); //переводим в нижний регистр url
            $tema = mysql_real_escape_string("Обзор сайта " . $name); // добавляем в начало "Обзор сайта"
        }
        /*if (($num_task <= $end_task) && $task["nof_chars"] == 2000) { // 
            $for_copywriter = 1;
            $num_task++;
        }*/

        $db->Execute("INSERT INTO zadaniya_new (from_sape, for_copywriter, sid, sape_id, uid, sistema, price, type_task, ankor, ankor2, ankor3, ankor4, ankor5, url, url2, url3, url4, url5, tema, comments, date, nof_chars, title, keywords) VALUES (
          '1',
          '$for_copywriter',
          '1',
          '" . $task["id"] . "',
          '1',
          'http://pr.sape.ru/',
          '$price',
          '$type',
          " . $links . ",
          '" . $tema . "',
          '" . mysql_real_escape_string($task["description"]) . "',
          '" . time() . "',
          '" . $task["nof_chars"] . "',
          '" . $task["title"] . "',
          '" . $keywords . "')");
    }
}
$new = $task_copywriter = array();
$add_task = $db->Execute("SELECT * FROM zadaniya_new WHERE id > '$last_id' AND sistema='http://pr.sape.ru/' AND from_sape=1 ORDER BY id ASC");
while ($task = $add_task->FetchRow()) {
    $new[$task['id']] = "http://iforget.ru/admin.php?module=admins&action=articles&action2=edit&id=" . $task['id'];
    /*if ($task['for_copywriter'] == 1) {
        $task_copywriter[] = $task['id'];
    }*/
}

if (count($new) != 0) {
    $body = "Добрый день!<br/><br/>
            На сайт выгружены новые задачи для написания текстов из  <b>http://articles.sape.ru</b>.<br/><br/>";
    $subject = "[" . count($new) . " - задания из sape]";
    foreach ($new as $knt => $vnt) {
        $body .= "<a href='$vnt'>$knt</a><br/>";
    }
} else {
    $body = "Добрый день!<br/><br/>
            В бирже articles.sape.ru не найдено ни одной новой задачи.<br/><br/>";
    $subject = "[0 заданий из sape]";
}
if (!empty($task_old)) {
    if (count($new) == 0) {
        $subject = "[Вернулись задачи с доработкой из sape]";
        $body = "Добрый день!<br/><br/>" . $rectificate;
    } else {
        $body .= $rectificate;
    }
}

$mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
$message = array();
$message["html"] = $body;
$message["text"] = "";
$message["subject"] = $subject;
$message["from_email"] = "news@iforget.ru";
$message["from_name"] = "iforget";
$message["to"] = array();
$message["to"][1] = array("email" => "abashevav@gmail.com");
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

exit();
?>
