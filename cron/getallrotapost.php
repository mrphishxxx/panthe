<?php

echo date("d-m-Y H:i:s") . "<br>\r\n";

error_reporting(E_ALL);
include_once dirname(__FILE__) . '/../' . 'config.php';
include_once dirname(__FILE__) . '/../' . 'includes/Rotapost.php';
include_once dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
require_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';
include_once dirname(__FILE__) . '/../' . 'includes/idna_convert.class.php';
include_once dirname(__FILE__) . '/../' . 'modules/admins/class_admin_admins.php';
$admins = new admins();

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$new_tasks = array();
include(PATH . 'includes/simple_html_dom.php');

$last_task = $db->Execute("SELECT id FROM zadaniya WHERE sistema='http://rotapost.ru/' ORDER BY id DESC LIMIT 1")->FetchRow();
if (!empty($last_task))
    $last_id = $last_task['id'];
else
    $last_id = 0;

$query = $db->Execute("SELECT * FROM birjs b LEFT JOIN admins a ON b.uid = a.id WHERE birj = 3 AND a.active=1 AND type='user'");
$rota_to_uid = array();
while ($res = $query->FetchRow()) {
    $rota_to_uid[] = $res['uid'];
}
$main_error = array();
foreach ($rota_to_uid as $uid) {
    //if ($uid != 601)continue;
    $balance = $admins->getUserBalans($uid, $db, 1);
    if ($balance >= 60 || (($res['id'] == 20) || ($res['id'] == 55))) {
        //echo $uid . "\r\n";
        $main_error[] = callback($uid, $db);
    }
}
$output_error = "";
if (!empty($main_error)) {
    foreach ($main_error as $err) {
        $output_error .= $err . "<br>\r\n";
    }
}

function callback($uid, $db) {
    $error = array();
    $data_birjs = $db->Execute("select * from birjs where birj=3 AND uid=$uid")->FetchRow();
    $rotapost = new Rotapost\Client();
    $auth = $rotapost->loginAuth($data_birjs['login'], md5($data_birjs['login'] . $data_birjs['pass']));
    if (($data_birjs['login'] == null || $data_birjs['pass'] == null) || (isset($auth->Success) && $auth->Success == "false") || !isset($auth->ApiKey)) {
        //  Если нет логина или пароля от биржи, отправляем админу письмо с ошибкой 
        $err = (array) $auth->Error;
        if ($data_birjs['login'] == null || $data_birjs['pass'] == null) {
            return "UID $uid - Errors:" . "Отсутствует логин или пароль для доступа к биржи Rotapost";
        } elseif ($auth->Success == "false" && isset($err["Description"])) {
            return "UID $uid - Errors:" . $err["Description"];
        }
    } else {
        //  ИНАЧЕ 
        // (1) Вытаскиваем задачи в статусе Ожидает одобрения, и подтверждаем их!
        $New = $rotapost->taskWebmaster("New");
        if ((isset($New->Success) && $New->Success == "true")) {
            $result = array();
            if (isset($New->Tasks->Task) && !empty($New->Tasks->Task)) {
                foreach ($New->Tasks->Task as $task) {
                    $result[$task->Id] = $rotapost->taskTake($task->Id);
                }
            }
        } elseif ($New->Success == "false") {
            //  Иначе отправляем ошибку админу  
            $err = (array) $result->Error;
            $error[] = $err["Description"];
        }

        $buff = array();
        $result = $db->Execute("SELECT rotapost_id FROM zadaniya WHERE (rotapost_id IS NOT NULL AND rotapost_id != 0) AND uid=$uid");
        while ($add = $result->FetchRow()) {
            $buff[] = $add["rotapost_id"];
        }
        $user_sites = array();
        $sayty = $db->Execute("SELECT * FROM sayty WHERE uid=$uid");
        while ($res = $sayty->FetchRow()) {
            $user_sites[$res["id"]] = $res["url"];
        }
        // (2) - Выгружаем задания в статусе "Ожидает выполнения" и сохраняем их к нам
        $ToDo = $rotapost->taskWebmaster("ToDo");
        if ((isset($ToDo->Success) && $ToDo->Success == "true")) {
            if (isset($ToDo->Tasks->Task) && !empty($ToDo->Tasks->Task)) {
                foreach ($ToDo->Tasks->Task as $task) {
                    $url_site = $task->Site;
                    $site_in_iforget = false;
                    foreach ($user_sites as $key => $value) {
                        if(mb_detect_encoding($value) == "UTF-8") {
                            $idn = new idna_convert(array('idn_version'=>2008));
                            $value = $idn->encode($value);
                        }
                        if (mb_strpos($url_site, $value) || mb_strpos($value, $url_site) || $url_site == $value) {
                            $site_in_iforget = true;
                            $sid = $key;
                        }
                    }
                    if (!in_array($task->Id, $buff) && $site_in_iforget == true) {
                        //echo $task->Id . "<BR>\r\n";
                        $tema = $url = $ankor = "";
                        if ($task->Type == "Postovoi") {
                            $ankor_start = mb_stripos($task->Text, ">", 0, "utf-8") + 1;
                            $ankor_end = mb_stripos($task->Text, "<", $ankor_start, "utf-8");
                            $ankor = mb_substr($task->Text, $ankor_start, $ankor_end - $ankor_start, "utf-8");

                            $url_start = mb_stripos($task->Text, 'href="', 0, "utf-8") + 6;
                            $url_end = mb_stripos($task->Text, '"', $url_start, "utf-8");
                            $url = mb_substr($task->Text, $url_start, $url_end - $url_start, "utf-8");
                            $comments = $task->Comment;
                        } else {
                            $comments = $task->Text;
                        }

                        if (!empty($ankor)) {
                            $first1 = mb_strtoupper(mb_substr($ankor, 0, 1, 'UTF-8'), 'UTF-8'); //первая буква
                            $first = str_replace("?", "", $first1);
                            $last1 = mb_strtolower(mb_substr($ankor, 1), 'UTF-8'); //все кроме первой буквы
                            $last = ($last1[0] == "?") ? mb_substr($last1, 1) : $last1;
                            $tema = mysql_real_escape_string($first . $last);
                        }

                        $db->Execute("INSERT INTO zadaniya (
                                                            sid, 
                                                            rotapost_id, 
                                                            uid, 
                                                            sistema, 
                                                            tema, 
                                                            ankor, 
                                                            url,
                                                            comments, 
                                                            date
                                                            ) 
                                                    VALUES (
                                                            '" . $sid . "',
                                                            '" . $task->Id . "',
                                                            '" . $uid . "',
                                                            'http://rotapost.ru/', 
                                                            '" . $tema . "', 
                                                            '" . $ankor . "', 
                                                            '" . $url . "', 
                                                            '" . $comments . "',
                                                            '" . time() . "'
                        )");
                        $buff[] = $task->Id;
                    } else {
                        if ($site_in_iforget == false)
                            $error[] = "Не найден сайт в системе ($url_site)";
                        else {
                            $isset = $db->Execute("SELECT * FROM zadaniya WHERE rotapost_id = '" . $task->Id . "'")->FetchRow();
                            $error[] = "Данная задача уже есть в системе (<a href='http://iforget.ru/admin.php?module=admins&action=zadaniya&uid=" . $isset['uid'] . "&sid=" . $isset['sid'] . "&action2=edit&id=" . $isset['id'] ."'>" . $isset['id'] . "</a>)";
                        }
                    }
                }
            }
        } elseif ($ToDo->Success == "false") {
            //  Иначе отправляем ошибку админу  
            $err = (array) $result->Error;
            $error[] = $err["Description"];
        }
    }

    if (!empty($error)) {
        return "UID $uid - Errors:" . implode(", ", $error);
    } else {
        return false;
    }
}

$add_task = $db->Execute("SELECT * FROM zadaniya WHERE id > '$last_id' AND sistema='http://rotapost.ru/'");
while ($task = $add_task->FetchRow()) {
    $new_tasks[$task['id']] = "http://iforget.ru/admin.php?module=admins&action=zadaniya&uid=" . $task['uid'] . "&sid=" . $task['sid'] . "&action2=edit&id=" . $task['id'];
}

if (count($new_tasks) !== 0) {

    $body = "Добрый день!<br/><br/>
            На сайт выгружены новые задачи из биржи <b>rotapost.ru</b>.<br/><br/>";
    $subject = "[" . count($new_tasks) . " новых задач из биржи rotapost]";
    foreach ($new_tasks as $knt => $vnt) {
        $body .= "<a href='$vnt'>$knt</a><br/>";
    }
    if (!empty($output_error)) {
        $body .= "<br><br><em>Во время выгрузки были замечены некоторые ошибки, пожалуйста обратите внимание на них!</em>" . $output_error;
    }
} else {
    $body = "Добрый день!<br/><br/>
            В бирже rotapost не найдено ни одной новой задачи.<br/><br/>";
    $subject = "[0 новых задач из биржи rotapost]";
}

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
    echo $body;
} catch (Exception $e) {
    echo $body;
}
exit();
