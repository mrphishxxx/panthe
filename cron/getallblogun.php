<?php

echo "data:" . date("d-m-Y H:i:s") . " \r\n";
$start = time();
set_time_limit(0);
ini_set("memory_limit", "1024M");
ini_set("max_execution_time", "0");

header('Content-Type: text/html; charset=utf-8');

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
    if ($balance >= 45 || (($res['id'] == 20) || ($res['id'] == 55))) {
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
            'proxy_user' => 'RUS79476',
            'proxy_pass' => 'H987tURQLo'
        );
    }
    $user = $db->Execute("SELECT * FROM birjs WHERE birj=8 AND uid=$uid")->FetchRow();
    $proxy = $proxies[rand(0, count($proxies) - 1)];
    $data = array();

    $host = 'http://localhost:4444/wd/hub'; // this is the default
    $capabilities = array(WebDriverCapabilityType::BROWSER_NAME => "firefox");
    if (!is_null($proxy)) {
        $proxy_capabilities = array(WebDriverCapabilityType::PROXY => array('proxyType' => 'manual',
                'httpProxy' => '' . $proxy['proxy_host'] . ':' . $proxy['proxy_port'] . '', 'sslProxy' => '' . $proxy['proxy_host'] . ':' . $proxy['proxy_port'] . '', 'socksUsername' => '' . $proxy['proxy_user'] . '', 'socksPassword' => '' . $proxy['proxy_pass'] . ''));

        array_push($capabilities, $proxy_capabilities);
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
    $login = $driver->findElement(WebDriverBy::xpath("//input[@name='login']"));
    $login->sendKeys($data['login']);
    $pass = $driver->findElement(WebDriverBy::xpath("//input[@name='password']"));
    $pass->sendKeys($data['pass']);
    $btn = $driver->findElement(WebDriverBy::xpath("//button[@type='submit']"));
    $btn->click();

    if (count($driver->findElements(WebDriverBy::xpath("//a[@class='amount']"))) === 0) {
        $driver->close();
        return null;
    }
    //END LOGIN


    $driver->get('https://blogun.ru/requests.php?tasks=1');

    $rows = $driver->findElements(WebDriverBy::xpath("//form[@id='mainform']//div//table//tbody//tr[starts-with(@id, 'row')]"));
    $types = array();
    $hrefs = array();
    for ($i = 0; $i < count($rows); $i++) {
        array_push($hrefs, $rows[$i]->findElement(WebDriverBy::xpath(".//a[@class='descript_text']"))->getAttribute('href'));
        array_push($types, $rows[$i]->findElement(WebDriverBy::xpath(".//td[7]"))->getText());
    }

    if (count($rows) === 0) {
        $driver->close();
        return null;
    }
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
        $description = $driver->findElement(WebDriverBy::xpath("//p[@class='getcodeText']"));
        $data['comments'] = $description->getText();
        $data['url'] = NULL;
        $data['ankor'] = NULL;
        if (count($driver->findElements(WebDriverBy::xpath("//textarea[@readonly='']"))) === 0) {
            //ESLI ZAYAVKA BEZ URL ILI ANKORA????
            continue;
        } else {
            $url = $driver->findElement(WebDriverBy::xpath("//textarea[@readonly='']"));
            $txt = $url->getText();

            if (preg_match('/<a href="([^"]+)">([^<]+)<\/a>/i', $txt, $subs)) {
                $data['url'] = $subs[1];
                $data['ankor'] = $subs[2];
            } else {
                $data['url'] = $txt;
                $data['ankor'] = '';
            }
            if (($data['type_task'] == 0) && !empty($data['ankor'])) {
                $first = mb_strtoupper(mb_substr($data['ankor'], 0, 1, 'UTF-8'), 'UTF-8'); //первая буква
                $first = str_replace("?", "", $first);
                $last = mb_strtolower(mb_substr($data['ankor'], 1), 'UTF-8'); //все кроме первой буквы
                $last = ($last[0] == "?") ? mb_substr($last, 1) : $last;
                $data["tema"] = mysql_real_escape_string($first . $last);
            } elseif (isset($data["url"])) {
                $data["tema"] = mysql_real_escape_string("Обзор сайта " . $data['url']);
            }
            $data['site'] = array_search($data['idblog'], $sites_to_user);
            //check if task exists in db
            $exists = $db->Execute("SELECT * FROM zadaniya WHERE b_id='" . $data['id'] . "' AND uid='" . $data['uid'] . "' AND sistema = 'https://blogun.ru/'")->FetchRow();
            if (empty($exists)) {
                $s = "INSERT into zadaniya(date, sistema, tema, sid, b_id, comments, url, ankor, uid, type_task, vrabote, navyklad, vilojeno, vipolneno, dorabotka) 
                           VALUES ('" . time() . "', 'https://blogun.ru/', '" . $data["tema"] . "', '" . $data['site'] . "','" . $data['id'] . "','" . $data['comments'] . "','" . $data['url'] . "','" . $data['ankor'] . "','" . $data['uid'] . "','" . $data['type_task'] . "','0','0','0','0','0')";
                $db->Execute($s);
            }
        }
    }
    $driver->close();
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
