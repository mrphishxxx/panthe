<?php

error_reporting(E_ALL);
echo "\r\n";
$UID = 649;
$start = time();
include_once dirname(__FILE__) . '/../' . '/../' . 'config.php';
include_once dirname(__FILE__) . '/../' . '/../' . 'includes/adodb5/adodb.inc.php';
include_once dirname(__FILE__) . '/../' . '/../' . 'includes/phpQuery/phpQuery.php';
include_once dirname(__FILE__) . '/../' . '/../' . 'includes/gt_functions.php';
include_once dirname(__FILE__) . '/../' . '/../' . 'modules/admins/class_admin_admins.php';

define("MIRALINKS_URL", "http://www.miralinks.ru/");
//phpQuery::$debug = true;

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$admins = new admins();
$balance = $admins->getUserBalans($UID, $db, 1);
if($balance < 45){
    exit();
} else {

    $proxy_data = $buff = array();
    $result = $db->Execute("SELECT miralinks_id FROM zadaniya WHERE (miralinks_id IS NOT NULL AND miralinks_id != 0) AND sistema = 'http://miralinks.ru/' AND uid=$UID");
    while ($add = $result->FetchRow()) {
        $buff[] = $add["miralinks_id"];
    }
    $proxy_counter = mt_rand(0, 69);
    $proxy = file(PATH . "modules/angry_curl/proxy_list.txt");

    foreach ($proxy as $p) {
        $proxy_data[] = array(
            'proxy_host' => substr($p, 0, strpos($p, ":")),
            'proxy_port' => (int) substr($p, strpos($p, ":") + 1),
            'proxy_user' => 'RUS79476',
            'proxy_pass' => 'H987tURQLo'
        );
    }

    $mira_to_uid = array('lexltd' => '7021292v');

    $new_tasks = array();
    $num = 0;
    foreach ($mira_to_uid as $login => $pass) {
        phpQuery::$proxy_host = $proxy_data[$proxy_counter]['proxy_host'];
        phpQuery::$proxy_port = $proxy_data[$proxy_counter]['proxy_port'];
        phpQuery::$proxy_user = $proxy_data[$proxy_counter]['proxy_user'];
        phpQuery::$proxy_pass = $proxy_data[$proxy_counter]['proxy_pass'];
        echo date("Y-m-d H:i:s")."\r\n";
        echo "IP - ".$proxy_data[$proxy_counter]['proxy_host'];echo "\r\n";
        try {
            $result_data = getTasks($login, $pass);
        } catch (Zend_Http_Client_Exception $e) {
            echo $e;
        }
    }

    print_r(count($result_data["write_place"]));
    echo " - ";
    print_r(count($result_data["place"]));
    //$file = 'temp_file/miralinks/601-(' . time() . ').txt';
    //file_put_contents(PATH.$file, print_r($result_data, true));
    if (!empty($result_data)) {
        foreach ($result_data as $type => $type_tasks) {
            foreach ($type_tasks as $key => $task) {
                if ($type == "write_place") {
                    if ($task["AllUserArticle.cm_status"] == "accepted") {
                        $new_tasks[$num] = array();
                        $new_tasks[$num]["links"] = array();
                        $new_tasks[$num]["id"] = $task["AllUserArticle.cm_offer_id"];
                        $new_tasks[$num]["sid"] = $task["AllUserArticle.ground_id"];
                        $new_tasks[$num]["site"] = isset($task["Ground.folder_url_wl"]) ? $task["Ground.folder_url_wl"] : null;
                        $new_tasks[$num]["tema"] = isset($task["AllUserArticle.header"]) ? $task["AllUserArticle.header"] : "";
                        $new_tasks[$num]["status"] = $task["AllUserArticle.cm_status"];
                        $new_tasks[$num]["lay_out"] = 0;
                        $new_tasks[$num]["text"] = '';
                        $new_tasks[$num]["keywords"] = '';
                        $new_tasks[$num]["url_statyi"] = ''; //Адрес url
                        $new_tasks[$num]["title"] = '';
                        if (isset($task["links.0.acceptor"])) {
                            $new_tasks[$num]["links"][] = array($task["links.0.anchor"] => $task["links.0.acceptor"]);
                        }
                        if (isset($task["links.1.acceptor"])) {
                            $new_tasks[$num]["links"][] = array($task["links.1.anchor"] => $task["links.1.acceptor"]);
                        }
                        if (isset($task["links.2.acceptor"])) {
                            $new_tasks[$num]["links"][] = array($task["links.2.anchor"] => $task["links.2.acceptor"]);
                        }
                        if (isset($task["links.3.acceptor"])) {
                            $new_tasks[$num]["links"][] = array($task["links.3.anchor"] => $task["links.3.acceptor"]);
                        }
                        if (isset($task["links.4.acceptor"])) {
                            $new_tasks[$num]["links"][] = array($task["links.4.anchor"] => $task["links.4.acceptor"]);
                        }
                        if (empty($new_tasks[$num]["links"])) {
                            if (!empty($task["extended_data"])) {
                                if (!empty($task["extended_data"]["links"])) {
                                    foreach ($task["extended_data"]["links"] as $l) {
                                        $new_tasks[$num]["links"][] = array($l->anchor => $l->acceptor);
                                    }
                                }
                            }
                        }

                        if (!empty($task["extended_data"])) {
                            $new_tasks[$num]["comments"] = $task["extended_data"]["description"];
                        }
                        $num++;
                    }
                } else {
                    if ($task["Article.current_status"] == "ground_choosed") {
                        $new_tasks[$num] = array();
                        $new_tasks[$num]["links"] = array();
                        $new_tasks[$num]["id"] = $task["AllUserArticle.article_id"];
                        $new_tasks[$num]["sid"] = $task["AllUserArticle.ground_id"];
                        $new_tasks[$num]["site"] = isset($task["Ground.folder_url_wl"]) ? $task["Ground.folder_url_wl"] : null;
                        $new_tasks[$num]["tema"] = isset($task["AllUserArticle.header"]) ? $task["AllUserArticle.header"] : "";
                        $new_tasks[$num]["status"] = $task["Article.current_status"];
                        $new_tasks[$num]["lay_out"] = 1;

                        $new_tasks[$num]["text"] = $task['extended_data']['ex_article_text'];
                        $new_tasks[$num]["keywords"] = $task['extended_data']['ex_keywords'];
                        $new_tasks[$num]["url_statyi"] = $task['extended_data']['ex_url']; //Адрес url
                        $new_tasks[$num]["title"] = $task['extended_data']['ex_title'];
                        $new_tasks[$num]["comments"] = '';

                        foreach ($task['extended_data']['ex_links'] as $links) {
                            $links = (array) $links;
                            $new_tasks[$num]["links"][] = array($links["anchor"] => $links["full_acceptor"]);
                        }
                        $num++;
                    }
                }
            }
        }
    }
    unset($result_data);
    $count = 0;
    if (!empty($new_tasks)) {
        echo "\r\n";
        //print_r($new_tasks);
        foreach ($new_tasks as $add_task) {
            if (!in_array($add_task["id"], @$buff)) {
                $site = $db->Execute("SELECT * FROM sayty WHERE miralinks_id=" . $add_task["sid"])->FetchRow();
                if (!empty($site["id"])) {
                    $url = $anchor = array();
                    $links = "";

                    foreach ($add_task["links"] as $arr) {
                        foreach ($arr as $text => $link) {
                            $url[] = $link;
                            $anchor[] = $text;
                        }
                    }
                    for ($i = 0; $i < 5; $i++) {
                        if (isset($anchor[$i]) && !empty($anchor[$i])) {
                            $links .= "'" . addslashes($anchor[$i]) . "',";
                        } else {
                            $links .= "'',";
                        }
                    }
                    for ($i = 0; $i < 5; $i++) {
                        if (isset($url[$i]) && !empty($url[$i])) {
                            $links .= "'" . addslashes($url[$i]) . "',";
                        } else {
                            $links .= "'',";
                        }
                    }

                    if (empty($add_task["tema"]) && isset($anchor[0])) {
                        $first1 = mb_strtoupper(mb_substr($anchor[0], 0, 1, 'UTF-8'), 'UTF-8'); //первая буква
                        $first = str_replace("?", "", $first1);
                        $last1 = mb_strtolower(mb_substr($anchor[0], 1), 'UTF-8'); //все кроме первой буквы
                        $last = ($last1[0] == "?") ? mb_substr($last1, 1) : $last1;
                        $add_task["tema"] = mysql_real_escape_string($first . $last);
                    }
                    $anchor_url = trim($links, ",");
                    $db->Execute("INSERT INTO zadaniya(
                                            sid, 
                                            miralinks_id, 
                                            uid, 
                                            sistema, vrabote,
                                            ankor, ankor2, ankor3, ankor4, ankor5, url, url2, url3, url4, url5,
                                            date, 
                                            comments, 
                                            url_statyi,
                                            text,
                                            keywords,
                                            tema,
                                            navyklad,
                                            lay_out
                                            ) VALUES (
                                                '" . $site["id"] . "', 
                                                '" . $add_task["id"] . "',
                                                '" . $site["uid"] . "', 
                                                'http://miralinks.ru/', 0,
                                                " . $anchor_url . ",
                                                '" . time() . "',
                                                '" . addslashes($add_task["comments"]) . "',
                                                '" . addslashes($add_task["url_statyi"]) . "',
                                                '" . addslashes($add_task["text"]) . "',
                                                '" . addslashes($add_task["keywords"]) . "',
                                                '" . addslashes($add_task["tema"]) . "',
                                                '" . $add_task["lay_out"] . "',
                                                '" . $add_task["lay_out"] . "')");
                    if ($add_task["lay_out"] == 1) {
                        $lastId = $db->Insert_ID();
                        $db->Execute("INSERT INTO completed_tasks (uid, zid, date, price, status) VALUES ('" . $site["uid"] . "', '" . $lastId . "', '" . date("Y-m-d H:i:s") . "', '15', 1)");
                    }
                    $buff[] = $add_task["id"];
                    $count++;
                }
            }
        }
    }
    $count_tasks = $db->Execute("SELECT * FROM task_from_mira WHERE uid = '$UID'")->FetchRow();
    if($count != 0){
        if(isset($count_tasks["id"])) {
            $count += $count_tasks["count"];
            $db->Execute("UPDATE task_from_mira SET count = ".  $count." WHERE uid = $UID");
        } else {
            $db->Execute("INSERT INTO task_from_mira (uid, count) VALUE ($UID, ".  $count.")");
        }
    }
    echo "\r\n";
    echo time() - $start;
    echo " sec.\r\n";
    echo "\r\n";
}
?>
