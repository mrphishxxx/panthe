<?php

$start = time();

include_once dirname(__FILE__) . '/../' . 'config.php';
include_once dirname(__FILE__) . '/../' . 'includes/simple_html_dom.php';
include_once dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
include_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';
include_once dirname(__FILE__) . '/../' . 'modules/admins/class_admin_admins.php';
$admins = new admins();
error_reporting(E_ALL);
echo "data:" . date("d-m-Y H:i:s") . " \r\n";

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$new_tasks = array();
$last_task = $db->Execute("SELECT id FROM zadaniya WHERE sistema='http://pr.sape.ru/' ORDER BY id DESC LIMIT 1")->FetchRow();
if (!empty($last_task))
    $last_id = $last_task['id'];
else
    $last_id = 0;

$query = $db->Execute("SELECT a.id FROM birjs b LEFT JOIN admins a ON b.uid = a.id WHERE b.birj = 4 AND a.active=1 AND b.active=1 AND a.type='user'");
while ($res = $query->FetchRow()) {
    //if($res["id"] != 421)continue;
    $balance = $admins->getUserBalans($res['id'], $db, 1);
    if ($balance >= 60 || (($res['id'] == 20) || ($res['id'] == 55))) {
        getTask($db, $res['id']);
    }
}

function getTask($db, $uid) {
    $cookie_jar = tempnam(PATH . 'temp', "cookie");
    $url = "http://api.pr.sape.ru/xmlrpc/";

    $user = $db->Execute("SELECT * FROM birjs WHERE birj=4 AND uid=$uid")->FetchRow();
    if ($user['login'] == null || $user['pass'] == null)
        return false;

    $data = xmlrpc_encode_request('sape_pr.login', array($user["login"], $user["pass"]));
    $header[0] = "Content-type: text/xml";
    $header[1] = "Content-length: " . strlen($data);

    if ($curl = curl_init()) {
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
        //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $out = curl_exec($curl);
        curl_close($curl);
    }
    $id_user_sape = xmlrpc_decode($out);

    if (is_array($id_user_sape)) {
        $user = $db->Execute("SELECT * FROM admins WHERE id=$uid")->FetchRow();
        $body = "Добрый день " . $user["login"] . "! <br/>" .
                "На сайте <strong>iForget</strong>, у Вас внесены не верные данные для входа в биржу Sape. <br/>" .
                "Пожалуйста внесите актуальные логин и пароль, так как Ваши задачи из Sape сейчас не выгружаются.<br/>" .
                "Для этого перейдите по ссылке в <a href='http://iforget.ru/user.php?action=birj'>Личный кабинет</a>.<br/><br/>" .
                "С уважением! Администрация iForget.";
        $message = array();
        $message["html"] = $body;
        $message["text"] = "";
        $message["subject"] = "Введите верные данные входа в биржу Sape";
        $message["from_email"] = "news@iforget.ru";
        $message["from_name"] = "iforget";
        $message["to"] = array();
        $message["to"][1] = array("email" => "abashevav@gmail.com");
        $message["to"][0] = array("email" => $user["email"]);
        $message["track_opens"] = null;
        $message["track_clicks"] = null;
        $message["auto_text"] = null;
        $mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
        try {
            //$mandrill->messages->send($message);
            //$db->Execute("UPDATE birjs SET active='0' WHERE birj=4 AND uid=$uid");
        } catch (Exception $e) {
            echo $body;
        }
    } else {
        $sites_to_user = $sites = array();
        $sayty = $db->Execute("SELECT * FROM sayty WHERE uid=$uid AND (sape_id IS NOT NULL AND sape_id != 0)");
        while ($site = $sayty->FetchRow()) {
            $sites_to_user[$site["id"]] = $site["sape_id"];
            $sites[] = $site["id"];
        }
        $sites = implode(",", $sites);

        $tasks_in_iforget = array();
        $zadaniya = $db->Execute("SELECT sape_id FROM zadaniya WHERE (sape_id IS NOT NULL AND sape_id != 0) AND sistema = 'http://pr.sape.ru/' AND sid IN ($sites)");
        if (!empty($zadaniya)) {
            while ($task = $zadaniya->FetchRow()) {
                $tasks_in_iforget[] = $task["sape_id"];
            }
        }

        $data = xmlrpc_encode_request('sape_pr.site.adverts', array(array("status_codes" => array(5)), 1, 500, true)); //"site_ids" => array(38665), 
        $header[1] = "Content-length: " . strlen($data);
        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            $out = curl_exec($curl);
            curl_close($curl);
        }
        $site_adverts = xmlrpc_decode($out);

        if (!empty($site_adverts)) {
            foreach ($site_adverts as $key => $adverts) {
                if (!in_array($adverts["site_id"], $sites_to_user)) {
                    continue;
                }
                if ($adverts["type"] == "archive") {
                    unset($site_adverts[$key]);
                    continue;
                }

                if (count($tasks_in_iforget) == 0 || !in_array($adverts["id"], $tasks_in_iforget)) {
                    $new_task = array();
                    $new_task["url"] = array();
                    $new_task["ankor"] = array();
                    $new_task["text"] = "";
                    $lay_out = 0;
                    foreach ($adverts["links"] as $link) {
                        $new_task["url"][] = $db->escape($link["href"]);
                        $new_task["ankor"][] = $db->escape($link["text"]);
                    }
                    if ($adverts["type"] == "news")
                        $type = 0;
                    elseif ($adverts["type"] == "link")
                        $type = 2;
                    else
                        $type = 1;

                    $new_task["comments"] = $adverts["description"];
                    if (!empty($new_task["comments"]) && $type == 0) {
                        foreach ($new_task["ankor"] as $key_ankor => $ankor) {
                            if (strpos($new_task["comments"], $ankor) && strpos($new_task["comments"], $new_task["url"][$key_ankor])) {
                                $new_task["text"] = $adverts["description"];
                                $new_task["comments"] = '';
                                $lay_out = 1;
                            }
                        }
                    }
                    $new_task["comments"] = $db->escape($new_task["comments"]);
                    if (!empty($adverts["keywords"])) {
                        $new_task["keywords"] = $adverts["keywords"];
                    } else {
                        $new_task["keywords"] = implode($new_task["ankor"], ",");
                    }

                    $new_task["tema"] = (isset($adverts["title"]) ? $adverts["title"] : "");
                    $new_task["sape_id"] = $adverts["id"];
                    $new_task["uid"] = $uid;
                    $new_task["sid"] = array_search($adverts["site_id"], $sites_to_user);

                    if (($type == 2 || $type == 0) && isset($new_task["ankor"][0])) {
                        $first = mb_strtoupper(mb_substr($new_task["ankor"][0], 0, 1, 'UTF-8'), 'UTF-8'); //первая буква
                        $first = str_replace("?", "", $first);
                        $last = mb_strtolower(mb_substr($new_task["ankor"][0], 1), 'UTF-8'); //все кроме первой буквы
                        $last = ($last[0] == "?") ? mb_substr($last, 1) : $last;
                        $new_task["tema"] = mysql_real_escape_string($first . $last);
                    } elseif (isset($new_task["url"][0])) {
                        $new_task["tema"] = mysql_real_escape_string("Обзор сайта " . $new_task["url"][0]);
                    }
                    $date = time();
                    if (count($new_task["ankor"]) != 0 && count($new_task["url"]) != 0) {
                        $new_task["ankor"][1] = (isset($new_task["ankor"][1]) && !empty($new_task["ankor"][1])) ? $new_task["ankor"][1] : '';
                        $new_task["ankor"][2] = (isset($new_task["ankor"][2]) && !empty($new_task["ankor"][2])) ? $new_task["ankor"][2] : '';
                        $new_task["ankor"][3] = (isset($new_task["ankor"][3]) && !empty($new_task["ankor"][3])) ? $new_task["ankor"][3] : '';
                        $new_task["ankor"][4] = (isset($new_task["ankor"][4]) && !empty($new_task["ankor"][4])) ? $new_task["ankor"][4] : '';
                        
                        $new_task["url"][1] = (isset($new_task["url"][1]) && !empty($new_task["url"][1])) ? $new_task["url"][1] : '';
                        $new_task["url"][2] = (isset($new_task["url"][2]) && !empty($new_task["url"][2])) ? $new_task["url"][2] : '';
                        $new_task["url"][3] = (isset($new_task["url"][3]) && !empty($new_task["url"][3])) ? $new_task["url"][3] : '';
                        $new_task["url"][4] = (isset($new_task["url"][4]) && !empty($new_task["url"][4])) ? $new_task["url"][4] : '';
                        
                        $db->Execute("INSERT INTO zadaniya (tema, text, lay_out, sid, b_id, sape_id, uid, sistema, type_task, ankor, ankor2, ankor3, ankor4, ankor5, url, url2, url3, url4, url5, comments, navyklad, date, keywords, nof_chars) VALUES ('" . $new_task["tema"] . "', '" . $db->escape($new_task["text"]) . "', '" . $lay_out . "', '" . $new_task["sid"] . "', '0', '" . $new_task["sape_id"] . "','" . $uid . "', 'http://pr.sape.ru/', '$type', '" . $new_task["ankor"][0] . "', '" . $new_task["ankor"][1] . "', '" . $new_task["ankor"][2] . "', '" . $new_task["ankor"][3] . "', '" . $new_task["ankor"][4] . "', '" . $new_task["url"][0] . "', '" . $new_task["url"][1] . "', '" . $new_task["url"][2] . "', '" . $new_task["url"][3] . "', '" . $new_task["url"][4] . "', '" . $new_task["comments"] . "', '" . $lay_out . "', '" . $date . "', '" . $new_task["keywords"] . "', '2000')");

                        if ($lay_out == 1) {
                            $lastId = $db->Insert_ID();
                            $db->Execute("INSERT INTO completed_tasks (uid, zid, date, price, status) VALUES ('$uid', '" . $lastId . "', '" . date("Y-m-d H:i:s") . "', '15', 1)");
                        }
                        $tasks_in_iforget[] = $adverts["id"];
                    }
                    unset($new_task);
                }
            }
        }
    }
}

$add_task = $db->Execute("SELECT * FROM zadaniya WHERE id > '$last_id' AND sistema='http://pr.sape.ru/'");
while ($task = $add_task->FetchRow()) {
    $new_tasks[$task['id']] = "http://iforget.ru/admin.php?module=admins&action=zadaniya&uid=" . $task['uid'] . "&sid=" . $task['sid'] . "&action2=edit&id=" . $task['id'];
}

if (count($new_tasks) !== 0) {

    $body = "Добрый день!<br/><br/>
            На сайт выгружены новые задачи из биржи <b>pr.sape.ru</b>.<br/><br/>";
    $subject = "[" . count($new_tasks) . " новых задач из биржи sape]";
    foreach ($new_tasks as $knt => $vnt) {
        $body .= "<a href='$vnt'>$knt</a><br/>";
    }
} else {
    $body = "Добрый день!<br/><br/>
            В бирже pr.sape.ru не найдено ни одной новой задачи.<br/><br/>";
    $subject = "[0 новых задач из биржи sape]";
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
//$message["to"][1] = array("email" => MAIL_DEVELOPER);
$message["to"][0] = array("email" => MAIL_ADMIN);
$message["track_opens"] = null;
$message["track_clicks"] = null;
$message["auto_text"] = null;

try {
    $mandrill->messages->send($message);
} catch (Exception $e) {
    echo $body;
}

$end = time();
echo ((int) $end - (int) $start);
echo "sec. \r\n";
exit();
?>
