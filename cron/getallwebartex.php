<?php

error_reporting(E_ALL);
include(dirname(__FILE__) . '/../' . 'config.php');
include(dirname(__FILE__) . '/../' . 'includes/simple_html_dom.php');
include dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
require_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';
include_once dirname(__FILE__) . '/../' . 'modules/admins/class_admin_admins.php';
$admins = new admins();

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$last_task = $db->Execute("SELECT id FROM zadaniya WHERE sistema='http://webartex.ru/' ORDER BY id DESC LIMIT 1")->FetchRow();
if (!empty($last_task))
    $last_id = $last_task['id'];
else
    $last_id = 0;

$query = $db->Execute("SELECT * FROM birjs b LEFT JOIN admins a ON b.uid = a.id WHERE birj = 6 AND a.active=1 AND type='user' AND b.active=1");
$webart_to_uid = array();
while ($res = $query->FetchRow()) {
    $webart_to_uid[] = $res['uid'];
}
foreach ($webart_to_uid as $uid) {
    //if ($uid != 601)continue;
    $balance = $admins->getUserBalans($uid, $db, 1);
    if ($balance >= 60 || $uid == 20 || $uid == 55) {
        getNewTask($db, $uid);
    }
}

function getNewTask($db, $uid) {
    $birjs = $db->Execute("select * from birjs where birj=6 AND uid=$uid")->FetchRow();
    if ($birjs['login'] == null || $birjs['pass'] == null)
        return false;

    $login = $birjs['login'];
    $pass = $birjs['pass'];
    $new_tasks = array();
    /*     * **************************************\
     *      ВЫТАСКИВАЕМ ЗАЯВКИ НА НАПИСАНИЕ
      /*************************************** */
    // Вытаскиваем новые заявки на написание и размещение!
    if ($curl = curl_init()) {
        curl_setopt($curl, CURLOPT_URL, "https://api.webartex.ru/api/webmaster/articles/list?status=new");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERPWD, $login . ":" . $pass);
        @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        $out = curl_exec($curl);
        curl_close($curl);
    }
    $tasks = json_decode($out);
    if (isset($tasks->list) && !empty($tasks->list)) {
        foreach ($tasks->list as $task) {
            //Получаем детальную информацию по каждой заяке
            if ($curl = curl_init()) {
                curl_setopt($curl, CURLOPT_URL, "https://api.webartex.ru/api/webmaster/articles/view/" . $task->id);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_USERPWD, $login . ":" . $pass);
                @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                $out = curl_exec($curl);
                curl_close($curl);
            }
            $view = json_decode($out);
            $new_tasks[] = $view->object;
        }
    }

    /*     * **********************************************\
     *          ВЫТАСКИВАЕМ ЗАЯВКИ НА РАЗМЕЩЕНИЕ
      /*********************************************** */
    if ($curl = curl_init()) {
        curl_setopt($curl, CURLOPT_URL, "https://api.webartex.ru/api/webmaster/articles/list?status=wait");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERPWD, $login . ":" . $pass);
        @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        $out = curl_exec($curl);
        curl_close($curl);
    }
    $tasks_layout = json_decode($out);
    if (isset($tasks_layout->list) && !empty($tasks_layout->list)) {
        foreach ($tasks_layout->list as $task) {
            //Получаем детальную информацию по каждой заяке
            if ($curl = curl_init()) {
                curl_setopt($curl, CURLOPT_URL, "https://api.webartex.ru/api/webmaster/articles/view/" . $task->id);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_USERPWD, $login . ":" . $pass);
                @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                $out = curl_exec($curl);
                curl_close($curl);
            }
            $view = json_decode($out);
            $new_tasks[] = $view->object;
        }
    }
    /*
     *          ОБРАБАТЫВАЕМ ПОЛУЧЕННЫЕ ЗАЯВКИ
     */
    //Собираем список выгруженных задач
    $tasks_in_iforget = array();
    $zadaniya = $db->Execute("SELECT webartex_id FROM zadaniya WHERE (webartex_id IS NOT NULL AND webartex_id != 0) AND sistema = 'http://webartex.ru/' AND uid = $uid");
    if (!empty($zadaniya)) {
        while ($task = $zadaniya->FetchRow()) {
            $tasks_in_iforget[] = $task["webartex_id"];
        }
    }
    //Собираем список сайтов Пользователя
    $sites_to_user = array();
    $sayty = $db->Execute("SELECT * FROM sayty WHERE uid=$uid AND (webartex_id IS NOT NULL AND webartex_id != 0)");
    while ($site = $sayty->FetchRow()) {
        $sites_to_user[$site["id"]] = $site["webartex_id"];
    }
    
    if (count($new_tasks) > 0) {
        foreach ($new_tasks as $task) {
            $task = (array) $task;
            // Если:
            // - нет ни одной заявки
            // - такой заявки в iforget нет
            // - сайт и ID webartex есть в iforget
            if ((count($tasks_in_iforget) == 0 || !in_array($task["id"], $tasks_in_iforget)) && in_array($task["site_id"], $sites_to_user)) {
                $lay_out_navyklad = 0;
                // Если задача НА ВЫКЛАДЫВАНИЕ
                if ($task["status"] == "wait") {
                    $text = $db->escape($task["body"]);
                    $lay_out_navyklad = 1;
                } else {
                    if ($curl = curl_init()) {
                        curl_setopt($curl, CURLOPT_URL, "https://api.webartex.ru/api/webmaster/articles/accept/" . $task["id"]);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($curl, CURLOPT_USERPWD, $login . ":" . $pass);
                        @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                        $out = curl_exec($curl);
                        curl_close($curl);
                    }
                    continue;
                }

                $sid = array_search($task["site_id"], $sites_to_user);
                $url = $ankor = array();
                foreach ($task["keywords"] as $link) {
                    $url[] = "http://" . $task["project_name"] . $link->url;
                    $ankor[] = $db->escape($link->title);
                }
                $keywords = implode($ankor, ",");

                // СОздаем Тему задачи
                if ((!isset($task["title"]) || empty($task["title"])) && isset($ankor[0])) {
                    $first = mb_strtoupper(mb_substr($ankor[0], 0, 1, 'UTF-8'), 'UTF-8'); //первая буква
                    $first = str_replace("?", "", $first);
                    $last = mb_strtolower(mb_substr($ankor[0], 1), 'UTF-8'); //все кроме первой буквы
                    $last = ($last[0] == "?") ? mb_substr($last, 1) : $last;
                    $tema = mysql_real_escape_string($first . $last);
                } else {
                    $tema = $task["title"];
                }

                //В зависимости от количества ссылок добавляем задачу
                if (count($ankor) != 0 && count($url) != 0) {
                    $date = time();
                    switch (count($url)) {
                        case 1:
                            $db->Execute("INSERT INTO zadaniya (text, lay_out, navyklad, tema, sid, webartex_id, uid, sistema, ankor, url, date, keywords) VALUES ('" . $text . "', '" . $lay_out_navyklad . "', '" . $lay_out_navyklad . "', '" . $tema . "', '" . $sid . "', '" . $task["id"] . "','" . $uid . "', 'http://webartex.ru/', '" . $ankor[0] . "', '" . $url[0] . "', '" . $date . "', '" . $keywords . "')");
                            break;
                        case 2:
                            $db->Execute("INSERT INTO zadaniya (text, lay_out, navyklad, tema, sid, webartex_id, uid, sistema, ankor, ankor2, url, url2, date, keywords) VALUES ('" . $text . "', '" . $lay_out_navyklad . "', '" . $lay_out_navyklad . "', '" . $tema . "', '" . $sid . "', '" . $task["id"] . "','" . $uid . "', 'http://webartex.ru/', '" . $ankor[0] . "', '" . @$ankor[1] . "', '" . $url[0] . "', '" . $url[1] . "', '" . $date . "', '" . $keywords . "')");
                            break;
                        case 3:
                            $db->Execute("INSERT INTO zadaniya (text, lay_out, navyklad, tema, sid, webartex_id, uid, sistema, ankor, ankor2, ankor3,url, url2, url3, date, keywords) VALUES ('" . $text . "', '" . $lay_out_navyklad . "', '" . $lay_out_navyklad . "', '" . $tema . "', '" . $sid . "', '" . $task["id"] . "','" . $uid . "', 'http://webartex.ru/', '" . $ankor[0] . "', '" . @$ankor[1] . "', '" . @$ankor[2] . "', '" . $url[0] . "', '" . $url[1] . "', '" . $url[2] . "', '" . $date . "', '" . $keywords . "')");
                            break;
                        case 4:
                            $db->Execute("INSERT INTO zadaniya (text, lay_out, navyklad, tema, sid, webartex_id, uid, sistema, ankor, ankor2, ankor3, ankor4, url, url2, url3, url4, date, keywords) VALUES ('" . $text . "', '" . $lay_out_navyklad . "', '" . $lay_out_navyklad . "', '" . $tema . "', '" . $sid . "', '" . $task["id"] . "','" . $uid . "', 'http://webartex.ru/', '" . $ankor[0] . "', '" . @$ankor[1] . "', '" . @$ankor[2] . "', '" . @$ankor[3] . "', '" . $url[0] . "', '" . $url[1] . "', '" . $url[2] . "', '" . $url[3] . "', '" . $date . "', '" . $keywords . "')");
                            break;
                        case 5:
                            $db->Execute("INSERT INTO zadaniya (text, lay_out, navyklad, tema, sid, webartex_id, uid, sistema, ankor, ankor2, ankor3, ankor4, ankor5, url, url2, url3, url4, url5, date, keywords) VALUES ('" . $text . "', '" . $lay_out_navyklad . "', '" . $lay_out_navyklad . "', '" . $tema . "', '" . $sid . "', '" . $task["id"] . "','" . $uid . "', 'http://webartex.ru/', '" . $ankor[0] . "', '" . @$ankor[1] . "', '" . @$ankor[2] . "', '" . @$ankor[3] . "', '" . @$ankor[4] . "', '" . $url[0] . "', '" . $url[1] . "', '" . $url[2] . "', '" . $url[3] . "', '" . $url[4] . "', '" . $date . "', '" . $keywords . "')");
                            break;
                        default :
                            $db->Execute("INSERT INTO zadaniya (text, lay_out, navyklad, tema, sid, webartex_id, uid, sistema, ankor, ankor2, ankor3, ankor4, ankor5, url, url2, url3, url4, url5, date, keywords) VALUES ('" . $text . "', '" . $lay_out_navyklad . "', '" . $lay_out_navyklad . "', '" . $tema . "', '" . $sid . "', '" . $task["id"] . "','" . $uid . "', 'http://webartex.ru/', '" . $ankor[0] . "', '" . @$ankor[1] . "', '" . @$ankor[2] . "', '" . @$ankor[3] . "', '" . @$ankor[4] . "', '" . $url[0] . "', '" . $url[1] . "', '" . $url[2] . "', '" . $url[3] . "', '" . $url[4] . "', '" . $date . "', '" . $keywords . "')");
                            break;
                    }
                    if ($lay_out_navyklad == 1) {
                        $lastId = $db->Insert_ID();
                        $db->Execute("INSERT INTO completed_tasks (uid, zid, date, price, status) VALUES ('$uid', '" . $lastId . "', '" . date("Y-m-d H:i:s") . "', '15', 1)");
                    }
                    $tasks_in_iforget[] = $task["id"];
                }
            }
        }
    }
}

$new_tasks = array();
$add_task = $db->Execute("SELECT * FROM zadaniya WHERE id > '$last_id' AND sistema='http://webartex.ru/'");
while ($task = $add_task->FetchRow()) {
    $new_tasks[$task['id']] = "http://iforget.ru/admin.php?module=admins&action=zadaniya&uid=" . $task['uid'] . "&sid=" . $task['sid'] . "&action2=edit&id=" . $task['id'];
}

if (count($new_tasks) !== 0) {
    $body = "Добрый день!<br/><br/>
            На сайт выгружены новые задачи из биржи <b>Webartex</b>.<br/><br/>";
    $subject = "[" . count($new_tasks) . " новых задач из биржи Webartex]";
    foreach ($new_tasks as $knt => $vnt) {
        $body .= "<a href='$vnt'>$knt</a><br/>";
    }
} else {
    $body = "Добрый день!<br/><br/>
            В бирже Webartex не найдено ни одной новой задачи.<br/><br/>";
    $subject = "[0 новых задач из биржи Webartex]";
}
$body .= "data:" . date("d-m-Y H:i:s") . "";
$mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
$message = array();
$message["html"] = $body;
$message["text"] = "";
$message["subject"] = $subject;
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
    echo $body;
}
exit();
?>
