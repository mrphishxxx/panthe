<?php

header("Content-Type: text/html; charset=utf-8");
mb_internal_encoding("UTF-8");
set_time_limit(0);
ini_set("memory_limit", "1024M");
ini_set("max_execution_time", "0");

define("GOGETLINKS", 1);
define("GETGOODLINKS", 2);
define("ROTAPOST", 3);
define("SAPE", 4);
define("MIRALINKS", 5);
define("WEBARTEX", 6);
define("BLOGUN", 8);

define("GOGETLINKS_URL", "https://gogetlinks.net/");
define("GETGOODLINKS_URL", "http://getgoodlinks.ru/");
define("ROTAPOST_URL", "");
define("SAPE_URL", "http://api.pr.sape.ru/xmlrpc/");
define("WEBARTEX_URL", "https://api.webartex.ru");
define("MIRALINKS_URL", "http://miralinks.ru/");

include_once dirname(__FILE__) . '/../' . 'config.php';
include_once dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
include_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';
include_once dirname(__FILE__) . '/../' . 'includes/Rotapost.php';
include_once dirname(__FILE__) . '/../' . 'includes/selenium/lib/__init__.php';

error_reporting(E_ALL);

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$body = "";
$tasks = $db->Execute("SELECT * FROM zadaniya WHERE "
        . "sistema IN ("
        . "'http://miralinks.ru/'"        
        . ") AND (miralinks_id != 0) AND vilojeno='1'");
//. 
$count = $tasks->NumRows();
//print_r($tasks->GetAll());die();
$yes = $no = "";
if ($count > 0) {
    while ($task = $tasks->FetchRow()) {
        $sinfo = $db->Execute("SELECT * FROM sayty WHERE id=" . $task["sid"])->FetchRow();
        $sinfo["url"] = str_replace("/", "", str_replace("http://", "", str_replace("www.", "", $sinfo["url"])));
        if ((!empty($task["url_statyi"]) && $task["url_statyi"] != "" && strstr($task["url_statyi"], $sinfo["url"]))) {
            echo $task["id"] ." -- ";
            switch ($task["sistema"]) {
                case 'http://miralinks.ru/':
                    if (!empty($task["miralinks_id"]) && $task["miralinks_id"] != 0) {
                        $err = setTaskMiralinks($db, $task);
                    } else {
                        $err = "Отсутствует ID задачи. Скорей всего задача заведена руками!";
                        continue;
                    }
                    var_dump($err);
                    break;
                default : $err = 'В данную биржу (' . $task["sistema"] . ') задания отправить не возможно!';
            }
            
            $main = "Задача <a href='http://iforget.ru/admin.php?module=admins&action=zadaniya&uid=" . $task['uid'] . "&sid=" . $task['sid'] . "&action2=edit&id=" . $task['id'] . "'>" . $task['id'] . "</a> - ";
            if (!$err) {
                $db->Execute($q = "UPDATE zadaniya SET vipolneno='1', dorabotka=0, vrabote=0, navyklad=0, vilojeno='0' WHERE id=" . $task["id"]);
                $yes .= $main . "Отправлена в биржу [" . $task["sistema"] . "]!<br/><br/>";
            } else {
                $no .= $main . "Ошибка отправления в биржу [" . $task["sistema"] . "]<br/>'<strong>$err</strong>'!<br/><br/>";
            }
        }
    }
    $body = "<h2>Повторная отправка задач в биржи</h2><h3>Задачи, которые были отправлены:</h3> " . $yes;
    $body .= "<br /><br /> <h3>Задачи, которые НЕ отправлены:</h3> " . $no;
} else {
    $body = NULL;
}

echo $body;
exit();

function setTaskMiralinks($db, $task) {
    
    $birj = $db->Execute("select * from birjs where birj='" . MIRALINKS . "' AND uid=" . $task["uid"])->FetchRow();
    $proxy_file = file(dirname(__FILE__) . '/../' . "modules/angry_curl/proxy_list.txt");
    $proxies = array();
    foreach ($proxy_file as $p) {
        $proxies[] = array(
            'domain' => substr($p, 0, strpos($p, ":")),
            'port' => (int) substr($p, strpos($p, ":") + 1),
            'user' => 'RUS79476',
            'pass' => 'H987tURQLo'
        );
    }
    
    $proxy = $proxies[rand(0, count($proxies) - 1)];
    $host = 'http://localhost:4444/wd/hub'; // this is the default
    $capabilities = array(WebDriverCapabilityType::BROWSER_NAME => "chrome");
    if (!is_null($proxy)) {
        $proxy_capabilities = array(WebDriverCapabilityType::PROXY => array('proxyType' => 'manual',
                'httpProxy' => '' . $proxy['domain'] . ':' . $proxy['port'] . '', 'sslProxy' => '' . $proxy['domain'] . ':' . $proxy['port'] . '', 'socksUsername' => '' . $proxy['user'] . '', 'socksPassword' => '' . $proxy['pass'] . ''));
    }
    $driver = RemoteWebDriver::create($host, $capabilities, 300000);
    $driver->manage()->window()->maximize();
    $driver->manage()->timeouts()->implicitlyWait(20);
    $driver->manage()->deleteAllCookies();

    //**************************************************LOGIN
    $loginpage = $driver->get('http://www.miralinks.ru/users/login');
    while (true) { // Handle timeout somewhere
        $ajaxIsComplete = $driver->executeScript("return jQuery.active == 0;");
        if ($ajaxIsComplete)
            break;
        sleep(1);
    }
    $login = $driver->findElement(WebDriverBy::xpath("//input[@id='UserLogin']"));
    $login->sendKeys($birj['login']);
    $pass = $driver->findElement(WebDriverBy::xpath("//input[@id='UserPassword']"));
    $pass->sendKeys($birj['pass']);
    while (true) { // Handle timeout somewhere
        $ajaxIsComplete = $driver->executeScript("return jQuery.active == 0;");
        if ($ajaxIsComplete)
            break;
        sleep(1);
    }
    $driver->getKeyboard()->pressKey(WebDriverKeys::ENTER);
    while (true) { // Handle timeout somewhere
        $ajaxIsComplete = $driver->executeScript("return jQuery.active == 0;");
        if ($ajaxIsComplete)
            break;
        sleep(1);
    }
    if (count($driver->findElements(WebDriverBy::xpath("//div[@data-action='doLogout']"))) === 0) {
        $driver->quit();
        continue;
    }
    //*********************************************************END LOGIN
    //ПЕРВЫЙ ТИП(ПРОСТО ССЫЛКА)
    if ($task['lay_out'] == 1) {
        $driver->get("http://www.miralinks.ru/project_articles/view/" . $task['miralinks_id']);

        while (true) { // Handle timeout somewhere
            $ajaxIsComplete = $driver->executeScript("return jQuery.active == 0;");
            if ($ajaxIsComplete)
                break;
            sleep(1);
        }

        $places = $driver->findElements(WebDriverBy::xpath("//a[@data-actionid='aa_place']"));
        if (count($places) === 0) {
            return "ERROR. Нет кнопки разместить(" . $task['miralinks_id'] . ")<br>";
            continue;
        }
        $places[0]->click();

        while (true) { // Handle timeout somewhere
            $ajaxIsComplete = $driver->executeScript("return jQuery.active == 0;");
            if ($ajaxIsComplete)
                break;
            sleep(1);
        }

        $urlInput = $driver->findElement(WebDriverBy::xpath("//input[@placeholder='Введите новый URL']"));
        $urlInput->sendKeys($task['url_statyi']);

        while (true) { // Handle timeout somewhere
            $ajaxIsComplete = $driver->executeScript("return jQuery.active == 0;");
            if ($ajaxIsComplete)
                break;
            sleep(1);
        }

        $btns = $driver->findElements(WebDriverBy::xpath("//a[@data-action='confirm']"));
        foreach ($btns as $btn) {
            if ($btn->isDisplayed())
                $btn->click();
        }

        sleep(3);
        $driver->wait(3);

        $error = $driver->findElement(WebDriverBy::xpath("//span[@class='invalidMessage']"));
        if ($error->isDisplayed()) {
            return "ERROR." . $error->getText() . "<br>";
        } else {
            return false;
        }
    }//end ПЕРВЫЙ ТИП
    //ВТОРОЙ ТИП 
    elseif ($task['lay_out'] == 0) {
        continue;
        $driver->get("http://www.miralinks.ru/ground_articles/articlePlacement/" . $task['miralinks_id']);
        while (true) { // Handle timeout somewhere
            $ajaxIsComplete = $driver->executeScript("return jQuery.active == 0;");
            if ($ajaxIsComplete)
                break;
            sleep(1);
        }

        $header = $driver->findElement(WebDriverBy::xpath("//input[@name='data[Article][header]']"));
        $header->sendKeys($task['tema']);

        while (true) { // Handle timeout somewhere
            $ajaxIsComplete = $driver->executeScript("return jQuery.active == 0;");
            if ($ajaxIsComplete)
                break;
            sleep(1);
        }


        $url = $driver->findElement(WebDriverBy::xpath("//input[@name='data[Article][article_url]']"));
        $url->sendKeys($task['url_statyi']);

        while (true) { // Handle timeout somewhere
            $ajaxIsComplete = $driver->executeScript("return jQuery.active == 0;");
            if ($ajaxIsComplete)
                break;
            sleep(1);
        }


        $text = $driver->findElement(WebDriverBy::xpath("//iframe[starts-with(@id, 'textarea_for_:widget')]"));
        $text->sendKeys($task['text']);

        while (true) { // Handle timeout somewhere
            $ajaxIsComplete = $driver->executeScript("return jQuery.active == 0;");
            if ($ajaxIsComplete)
                break;
            sleep(1);
        }


        $btn = $driver->findElement(WebDriverBy::xpath("//a[@data-action='submit']"));
        $btn->click();

        while (true) { // Handle timeout somewhere
            $ajaxIsComplete = $driver->executeScript("return jQuery.active == 0;");
            if ($ajaxIsComplete)
                break;
            sleep(1);
        }

        $tmp = $driver->findElements(WebDriverBy::xpath("//input[@name='data[Article][article_url]']"));
				
       if(count($tmp)!=0)
       {
            return "ERROR. Статья с написанием не размещена(" . $task['miralinks_id'] . ")<br>";
        } else {
            return false;
        }
    }//end ВТОРОЙ ТИП
}

die();
?>
