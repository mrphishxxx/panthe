<?php

error_reporting(E_ALL);
include(dirname(__FILE__) . '/../' . 'config.php');
include dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
require_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';
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

foreach ($rota_to_uid as $uid) {
    $balance = $admins->getUserBalans($uid, $db, 1);
    if ($balance >= 45 || (($res['id'] == 20) || ($res['id'] == 55))) {
        callback($uid, $db);
    }
}

function callback($uid, $db) {
    $header = $data = array();

    $data_birjs = $db->Execute("select * from birjs where birj=3 AND uid=$uid")->FetchRow();
    if ($data_birjs['login'] == null || $data_birjs['pass'] == null)
        return false;

    $url = 'https://www.rotapost.ru/api.ashx';

    $data['Hash'] = md5($data_birjs['login'] . $data_birjs['pass']);
    $data['RequestType'] = 2008;
    $post = "<Root><Hash>" . $data['Hash'] . "</Hash><RequestType>" . $data['RequestType'] . "</RequestType></Root>";

    $header[0] = "Content-type: text/xml";
    $header[1] = "Content-length: " . strlen($post);
    if ($curl = curl_init()) {
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        $out = curl_exec($curl);
        curl_close($curl);
    }
    $out = '<?xml version="1.0" encoding="UTF-8"?><xml>' . $out;
    $out .= '</xml>';
    $out = simplexml_load_string($out);

    $sites = array();
    $i = 0;

    if (!isset($out->Root->Error)) {
        foreach ($out->Root->Blogs->Blog as $item) {
            foreach ($item->attributes() as $a => $b) {
                $sites[$i][$a] = str_replace("\"", "", trim(str_replace("id=", "", str_replace("url=", "", $b->asXML()))));
            }
            $i++;
        }

        if (!empty($sites)) {
            $user_sites = array();
            $sayty = $db->Execute("SELECT * FROM sayty WHERE uid=$uid");
            while ($res = $sayty->FetchRow()) {
                $user_sites[$res["id"]] = $res["url"];
            }
            $tasks = array();
            $i = 0;
            foreach ($sites as $site) {
                $url_site = $id = null;
                if (isset($site["url"]) && !empty($site["url"]))
                    $url_site = $site["url"];
                if (isset($site["id"]) && !empty($site["id"]))
                    $id = $site["id"];

                if (!empty($id) && (!empty($url_site))) {
                    $site_in_iforget = false;
                    foreach ($user_sites as $key => $value) {
                        if (stristr($url_site, $value) || stristr($value, $url_site) || $url_site == $value) {
                            $site_in_iforget = true;
                            $sid = $key;
                        }
                    }
                    if (!$site_in_iforget) {
                        continue;
                    }

                    $data['BlogKey'] = $id;
                    $data['RequestType'] = 2001;

                    $post = "<Root><Hash>" . $data['Hash'] . "</Hash><BlogKey>" . $data['BlogKey'] . "</BlogKey><RequestType>" . $data['RequestType'] . "</RequestType></Root>";
                    $header[1] = "Content-length: " . strlen($post);
                    if ($curl = curl_init()) {
                        curl_setopt($curl, CURLOPT_URL, $url);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_POST, true);
                        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                        @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                        $out = curl_exec($curl);
                        curl_close($curl);
                    }
                    $buff = array();
                    $result = $db->Execute("SELECT rotapost_id FROM zadaniya WHERE (rotapost_id IS NOT NULL AND rotapost_id != 0) AND uid=$uid");
                    while ($add = $result->FetchRow()) {
                        $buff[] = $add["rotapost_id"];
                    }

                    $out = '<?xml version="1.0" encoding="UTF-8"?><xml>' . $out;
                    $out .= '</xml>';
                    $out = simplexml_load_string($out);

                    foreach ($out->Root->OfferBlogs->rp_offer_blog as $item) {
                        $id_task = str_replace("<" . $a . ">", "", str_replace("</" . $a . ">", "", $item->id->asXML()));
                        $id_task = (int) $id_task;
                        if (!in_array($id_task, $buff)) {
                            $tasks[$i]["sid"] = $sid;
                            foreach ($item as $a => $b) {
                                $tasks[$i][$a] = str_replace("<" . $a . ">", "", str_replace("</" . $a . ">", "", str_replace("<" . $a . "/>", "", $b->asXML())));
                            }
                            $i++;
                        }
                    }
                    //print_r($tasks);
                }
            }
            foreach ($tasks as $task) {
                if (!in_array($task["id"], $buff)) {
                    if ((!empty($task["postovoi_anchor"]) || !empty($task["title"])) && (!empty($task["postovoi_url"]) || !empty($task["description"]))) {
                        $ankor = (!empty($task["postovoi_anchor"]) ? $task["postovoi_anchor"] : $task["title"]);
                        $comment = $task["comment"];
                        if (!empty($task["postovoi_url"])) {
                            $to_url = $task["postovoi_url"];
                        } elseif (!empty($task["description"])) {
                            $to_url = $task["description"];
                            $to_url = (!empty($task["postovoi_url"]) ? $task["postovoi_url"] : ($task["description"]));
                            $to_url = substr($to_url, strpos($to_url, "http"));
                            $to_url = substr($to_url, 0, strpos(html_entity_decode($to_url), "&quot;"));
                            $comment = $task["description"] . $comment;
                        }

                        if (!empty($ankor)) {
                            $first1 = mb_strtoupper(mb_substr($ankor, 0, 1, 'UTF-8'), 'UTF-8'); //первая буква
                            $first = str_replace("?", "", $first1);
                            $last1 = mb_strtolower(mb_substr($ankor, 1), 'UTF-8'); //все кроме первой буквы
                            $last = ($last1[0] == "?") ? mb_substr($last1, 1) : $last1;
                            $tema = mysql_real_escape_string($first . $last);
                        }

                        $db->Execute("INSERT INTO zadaniya (sid, b_id, rotapost_id, uid, sistema, tema, ankor, url, comments, vipolneno, date) VALUES ('" . $task["sid"] . "', '0', '" . $task["id"] . "','" . $uid . "', 'http://rotapost.ru/', '" . $tema . "', '" . $ankor . "', '" . $to_url . "', '" . $comment . "', '0', '" . time() . "')");
                        $buff[] = $task["id"];
                    }
                }
            }
        }
    } else {
        echo ("User id = $uid : " . $out->Root->Error->asXML() . "<br> \n\r");
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
//$message["to"][1] = array("email" => "abashevav@gmail.com");
$message["to"][0] = array("email" => MAIL_ADMIN);
$message["track_opens"] = null;
$message["track_clicks"] = null;
$message["auto_text"] = null;

try {
    $mandrill->messages->send($message);
} catch (Exception $e) {
    echo $body;
}
die("END");
exit();
?>
