<?php

$start = time();
set_time_limit(0);
ini_set("memory_limit", "1024M");
ini_set("max_execution_time", "0");

header('Content-Type: text/html; charset=utf-8');
echo "data:" . date("d-m-Y H:i:s") . " \r\n";

include_once dirname(__FILE__) . '/../' . 'config.php';
include_once dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
include_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';
include_once dirname(__FILE__) . '/../' . 'includes/selenium/lib/__init__.php';
include_once dirname(__FILE__) . '/../' . 'modules/admins/class_admin_admins.php';

$admins = new admins();
error_reporting(E_ALL);

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$new_tasks = array();
$last_task = $db->Execute("SELECT id FROM zadaniya WHERE sistema='https://blogun.ru/' ORDER BY id DESC LIMIT 1")->FetchRow();
if (!empty($last_task))
    $last_id = $last_task['id'];
else
    $last_id = 0;

// Special for BLOGUN (birj = 8)
$query = $db->Execute("SELECT a.id FROM birjs b LEFT JOIN admins a ON b.uid = a.id WHERE b.birj = 8 AND a.active=1 AND b.active=1 AND a.type='user'");
while ($res = $query->FetchRow()) {
    //if ($res["id"] != 601)continue;
    $balance = $admins->getUserBalans($res['id'], $db, 1);
    if ($balance >= 60 || (($res['id'] == 20) || ($res['id'] == 55))) {
        getTask($db, $res['id']);
    }
}

function getTask($db, $uid) {
    $proxy_file = file(dirname(__FILE__) . '/../' . "modules/angry_curl/proxy_list.txt");
    $proxies = array();
    foreach ($proxy_file as $p) {
        $proxies[] = array(
            'proxy_host' => substr($p, 0, strpos($p, ":")),
            'proxy_port' => (int) substr($p, strpos($p, ":") + 1),
            'proxy_user' => PROXY_LOGIN,
            'proxy_pass' => PROXY_PASS
        );
    }
    $user = $db->Execute("SELECT * FROM birjs WHERE birj=8 AND uid=$uid")->FetchRow();
    $proxy = $proxies[rand(0, count($proxies) - 1)];
    $data = array();

    $host = 'http://127.0.0.1:4444/wd/hub'; // this is the default
    $capabilities = array(WebDriverCapabilityType::BROWSER_NAME => "firefox");
    if (!is_null($proxy)) {
        /*$proxy_capabilities = array(WebDriverCapabilityType::PROXY => array('proxyType' => 'manual',
                'httpProxy' => '' . $proxy['proxy_host'] . ':' . $proxy['proxy_port'] . '', 'sslProxy' => '' . $proxy['proxy_host'] . ':' . $proxy['proxy_port'] . '', 'socksUsername' => '' . $proxy['proxy_user'] . '', 'socksPassword' => '' . $proxy['proxy_pass'] . ''));

        array_push($capabilities, $proxy_capabilities);
		*/
    }
    $driver = RemoteWebDriver::create($host, $capabilities, 300000);
    $driver->manage()->window()->maximize();
    $driver->manage()->timeouts()->implicitlyWait(20);
    $driver->manage()->deleteAllCookies();

    //LOGIN
    $data['uid'] = $user['uid'];
    $data['login'] = $user['login'];
    $data['pass'] = $user['pass'];

    $loginpage = $driver->get('https://blogun.ru/');
    $driver->wait(15);
    
	$logins = $driver->findElements(WebDriverBy::xpath("//input[@name='login']"));
	if(count($logins)==0) {$driver->quit(); return null;}
    $logins[0]->sendKeys($data['login']);
	
    $pass = $driver->findElements(WebDriverBy::xpath("//input[@name='password']"));
	if(count($pass)==0) {$driver->quit(); return null;}
    $pass[0]->sendKeys($data['pass']);
	
    $btns = $driver->findElements(WebDriverBy::xpath("//button[@type='submit']"));
	if(count($btns)==0) {$driver->quit(); return null;}
    $btns[0]->click();

    if (count($driver->findElements(WebDriverBy::xpath("//a[@class='amount']"))) === 0) {
        $driver->quit();
        return null;
    }
    //END LOGIN


    $driver->get('https://blogun.ru/requests.php?tasks=1');

    $rows = $driver->findElements(WebDriverBy::xpath("//form[@id='mainform']//div//table//tbody//tr[starts-with(@id, 'row')]"));
    $types = array();
    $hrefs = array();
    for ($i = 0; $i < count($rows); $i++) {
		$hs = $rows[$i]->findElements(WebDriverBy::xpath(".//a[@class='descript_text']"));
		if(count($hs)==0) continue;
		$ts = $rows[$i]->findElements(WebDriverBy::xpath(".//td[7]"));
		if(count($ts)==0) continue;
        array_push($hrefs, $hs[0]->getAttribute('href'));
        array_push($types, $ts[0]->getText());
    }

    if (count($rows) === 0) {
        echo "No tasks:" . $data['uid'] . "<br>";
        $driver->quit();
        return null;
    }

    echo "TASKS:" . $data['uid'] . "<br>";

    $sites_to_user = array();
    $sayty = $db->Execute("SELECT * FROM sayty WHERE uid='" . $data['uid'] . "' AND (blogun_id IS NOT NULL AND blogun_id != 0)");
    while ($site = $sayty->FetchRow()) {
        $sites_to_user[$site["id"]] = $site["blogun_id"];
    }

    for ($i = 0; $i < count($rows); $i++) {
        $data['type_task'] = 0;
        if ($types[$i] == 'Подробный обзор')
            $data['type_task'] = 1;
        if ($types[$i] == 'Краткий обзор')
            $data['type_task'] = 1;
        if ($types[$i] == 'Постовой')
            $data['type_task'] = 0;

        $href = $hrefs[$i];

        //https://blogun.ru/getcode.php?id=337124&idblog=158557&submenu=2&menu=tsk
        preg_match('/id=(\d+)&idblog=(\d+)/i', $href, $subs);
        $data['id'] = $subs[1];
        $data['idblog'] = $subs[2];
        if (!in_array($data['idblog'], $sites_to_user)) {
            continue;
        }

        $driver->get($href);
        $description = $driver->findElements(WebDriverBy::xpath("//p[@class='getcodeText']"));
		if(count($description)==0) continue;
		
        $data['comments'] = $description[0]->getText();
        $data['url'] = '*';
        $data['url2'] = '*';
        $data['url3'] = '*';
        $data['url4'] = '*';
        $data['url5'] = '*';
        $data['ankor'] = '*';
        $data['ankor2'] = '*';
        $data['ankor3'] = '*';
        $data['ankor4'] = '*';
        $data['ankor5'] = '*';
        $keywords = array();
        if (count($driver->findElements(WebDriverBy::xpath("//textarea[@readonly='']"))) === 0) {
            //ESLI ZAYAVKA BEZ URL ILI ANKORA????
            //continue;
        } else {
            $url = $driver->findElements(WebDriverBy::xpath("//textarea[@readonly='']"));
            for ($j = 0; $j < count($url); $j++) {
                $txt = $url[$j]->getText();

                if ($j == 0)
                    $urlKey = 'url';
                else
                    $urlKey = 'url' . ($j + 1);
                if ($j == 0)
                    $ankorKey = 'ankor';
                else
                    $ankorKey = 'ankor' . ($j + 1);

                if (preg_match('/<a href="([^"]+)">([^<]+)<\/a>/i', $txt, $subs)) {
                    $data[$urlKey] = $subs[1];
                    $data[$ankorKey] = $subs[2];
                    $keywords[] = $subs[2];
                } else {
                    $data[$urlKey] = $txt;
                    $data[$ankorKey] = '';
                }
            }
        }

        $exists = $db->Execute('SELECT * FROM zadaniya WHERE sistema="https://blogun.ru/" AND b_id=' . $data['id'] . ' AND sid="'.array_search($data['idblog'], $sites_to_user).'" AND uid=' . $data['uid'])->FetchRow();
        if (empty($exists)) {
            $first = mb_strtoupper(mb_substr($data["ankor"], 0, 1, 'UTF-8'), 'UTF-8'); //первая буква
            $first = str_replace("?", "", $first);
            $last = mb_strtolower(mb_substr($data["ankor"], 1), 'UTF-8'); //все кроме первой буквы
            $last = ($last[0] == "?") ? mb_substr($last, 1) : $last;
            $data["tema"] = mysql_real_escape_string($first . $last);
            $data["keywords"] = implode($keywords, ",");
            $db->Execute("INSERT into zadaniya(sistema, sid, b_id, comments, 
                    url, ankor, 
                    url2, ankor2,
                    url3, ankor3,
                    url4, ankor4,
                    url5, ankor5,
                    uid, vrabote, navyklad, vilojeno, vipolneno, dorabotka, type_task, date, tema, keywords) VALUES 
                    ('https://blogun.ru/', '" . (array_search($data['idblog'], $sites_to_user)) . "', '" . $data['id'] . "', '" . $data['comments'] . "',
                        '" . $data['url'] . "','" . $data['ankor'] . "',
                        '" . $data['url2'] . "','" . $data['ankor2'] . "',
                        '" . $data['url3'] . "','" . $data['ankor3'] . "',
                        '" . $data['url4'] . "','" . $data['ankor4'] . "',
                        '" . $data['url5'] . "','" . $data['ankor5'] . "',
                        '" . $data['uid'] . "',0,0,0,0,0,'" . $data['type_task'] . "', '" . time() . "', '".$data["tema"]."', '".$data["keywords"]."')");
        } else {
            echo $data['id'] . " - TASK EXIST\r\n";
            //dont do anything
        }
    }
    $driver->quit();
    return;
}

$add_task = $db->Execute("SELECT * FROM zadaniya WHERE id > '$last_id' AND sistema='https://blogun.ru/'");
while ($task = $add_task->FetchRow()) {
    $new_tasks[$task['id']] = "http://iforget.ru/admin.php?module=admins&action=zadaniya&uid=" . $task['uid'] . "&sid=" . $task['sid'] . "&action2=edit&id=" . $task['id'];
}

if (count($new_tasks) !== 0) {

    $body = "Добрый день!<br/><br/>
            На сайт выгружены новые задачи из биржи <b>blogun</b>.<br/><br/>";
    $subject = "[" . count($new_tasks) . " новых задач из биржи blogun]";
    foreach ($new_tasks as $knt => $vnt) {
        $body .= "<a href='$vnt'>$knt</a><br/>";
    }
} else {
    $body = "Добрый день!<br/><br/>
            В бирже blogun не найдено ни одной новой задачи.<br/><br/>";
    $subject = "[0 новых задач из биржи blogun]";
}

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
echo " sec. \r\n";
exit();
?>
