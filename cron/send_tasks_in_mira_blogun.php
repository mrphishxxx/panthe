<?php

header("Content-Type: text/html; charset=utf-8");
mb_internal_encoding("UTF-8");
set_time_limit(0);
ini_set("memory_limit", "1024M");
ini_set("max_execution_time", "0");
echo date("d-m-Y H:i:s");
define("MIRALINKS", 5);
define("BLOGUN", 8);

define("MIRALINKS_URL", "http://miralinks.ru/");

include_once dirname(__FILE__) . '/../' . 'config.php';
include_once dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
include_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';
include_once dirname(__FILE__) . '/../' . 'includes/selenium/lib/__init__.php';

error_reporting(E_ALL);

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$body = "";
$tasks = $db->Execute("SELECT * FROM zadaniya WHERE "
        . "sistema IN ("
        . "'https://blogun.ru/', "
        . "'http://miralinks.ru/'"
        . ") AND (b_id != 0 OR miralinks_id != 0) AND vilojeno = 1");
$count = $tasks->NumRows();
$yes = $no = "";
if ($count > 0) {
    while ($task = $tasks->FetchRow()) {
        $sinfo = $db->Execute("SELECT * FROM sayty WHERE id=" . $task["sid"])->FetchRow();
        $sinfo["url"] = str_replace("/", "", str_replace("http://", "", str_replace("www.", "", $sinfo["url"])));
        if ((!empty($task["url_statyi"]) && $task["url_statyi"] != "" && strstr($task["url_statyi"], $sinfo["url"])) && ($task["vipolneno"] != 1)) {
            echo "\r\nTASK ID: " . $task["id"] . " -- \r\n";
            switch ($task["sistema"]) {
                case 'https://blogun.ru/':
                    if (!empty($task["b_id"]) && $task["b_id"] != 0) {
                        $err = setTaskBlogun($db, $task);
                    } else {
                        $err = "Отсутствует ID задачи. Скорей всего задача заведена руками!";
                        continue;
                    }
                    break;
                case 'http://miralinks.ru/':
                    if (!empty($task["miralinks_id"]) && $task["miralinks_id"] != 0) {
                        $err = setTaskMiralinks($db, $task);
                    } else {
                        $err = "Отсутствует ID задачи. Скорей всего задача заведена руками!";
                        continue;
                    }
                    break;
                default : $err = 'В данную биржу (' . $task["sistema"] . ') задания отправить не возможно/ Это другой скрипт!';
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

$mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
$message = array();
$message["html"] = $body;
$message["text"] = "";
$message["subject"] = "Отправка задач в Blogun и Miralinks";
$message["from_email"] = "news@iforget.ru";
$message["from_name"] = "iforget";
$message["to"] = array();
//$message["to"][1] = array("email" => MAIL_DEVELOPER);
$message["to"][0] = array("email" => MAIL_ADMIN);
$message["track_opens"] = null;
$message["track_clicks"] = null;
$message["auto_text"] = null;

try {
    if (!empty($body)) {
        $mandrill->messages->send($message);
        echo $body;
    }
} catch (Exception $e) {
    echo $e;
    echo $body;
}
exit();

function setTaskBlogun($db, $task) {
    $birj = $db->Execute("select * from birjs where birj='" . BLOGUN . "' AND uid=" . $task["uid"])->FetchRow();
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

    $proxy = $proxies[rand(0, count($proxies) - 1)];
    $host = 'http://127.0.0.1:4444/wd/hub'; // this is the default
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

    $driver->get('https://blogun.ru/');
    $driver->wait(15);
    $logins = $driver->findElements(WebDriverBy::xpath("//input[@name='login']"));
    if (count($logins) == 0) {
        $driver->quit();
        return "Ошибка отправления, поле 'input[@name='login']' - не найдено";
    }
    $logins[0]->sendKeys($birj['login']);

    $pass = $driver->findElements(WebDriverBy::xpath("//input[@name='password']"));
    if (count($pass) == 0) {
        $driver->quit();
        return "Ошибка отправления, поле 'input[@name='password']' - не найдено";
    }
    $pass[0]->sendKeys($birj['pass']);

    $btns = $driver->findElements(WebDriverBy::xpath("//button[@type='submit']"));
    if (count($btns) == 0) {
        $driver->quit();
        return "Ошибка отправления, поле 'input[@type='submit']' - не найдено";
    }
    $btns[0]->click();

    if (count($driver->findElements(WebDriverBy::xpath("//a[@class='amount']"))) === 0) {
        $driver->quit();
        return "Ошибка отправления, поле 'a[@class='amount']' - не найдено";
    }

    $sayty = $db->Execute("SELECT * FROM sayty WHERE uid='" . $task['uid'] . "' AND id='" . $task['sid'] . "'")->FetchRow();
    $driver->get('https://blogun.ru/getcode.php?id=' . $task['b_id'] . '&idblog=' . $sayty['blogun_id'] . '&submenu=2&menu=tsk');
    //echo 'https://blogun.ru/getcode.php?id=' . $task['b_id'] . '&idblog=' . $sayty['blogun_id'] . '&submenu=2&menu=tsk'.'\r\n';
    $urls = $driver->findElements(WebDriverBy::xpath("//input[@id='url']"));
    if (count($urls) == 0) {
        $driver->quit();
        return "Ошибка отправления, поле 'input[@id='url']' - не найдено";
    }
    $urls[0]->sendKeys($task['url_statyi']);
    $btns = $driver->findElements(WebDriverBy::xpath("//input[@name='submit']"));
    if (count($btns) == 0) {
        $driver->quit();
        return "Ошибка отправления, поле 'input[@name='submit']' - не найдено";
    }
    $btns[0]->click();

    $driver->quit();
    return FALSE;
}

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
    $host = 'http://127.0.0.1:4444/wd/hub'; // this is the default
    $capabilities = array(WebDriverCapabilityType::BROWSER_NAME => "firefox");
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
        $ajaxIsComplete = $driver->executeScript("if(window.jQuery){return jQuery.active==0;}else{return 1;}");
        if ($ajaxIsComplete) {
            break;
        }
        sleep(1);
    }
    $logins = $driver->findElements(WebDriverBy::xpath("//input[@id='UserLogin']"));
    if (count($logins) == 0) {
        $driver->quit();
        return "Ошибка отправления, поле 'input[@name='UserLogin']' - не найдено";
    }
    $logins[0]->sendKeys($birj['login']);

    $pass = $driver->findElements(WebDriverBy::xpath("//input[@id='UserPassword']"));
    if (count($pass) == 0) {
        $driver->quit();
        return "Ошибка отправления, поле 'input[@name='UserPassword']' - не найдено";
    }
    $pass[0]->sendKeys($birj['pass']);

    echo "MIRA: " . $birj['login'] . " --- " . $birj['pass'] . "\r\n";

    while (true) { // Handle timeout somewhere
        $ajaxIsComplete = $driver->executeScript("if(window.jQuery){return jQuery.active==0;}else{return 1;}");
        if ($ajaxIsComplete) {
            break;
        }
        sleep(1);
    }
    $driver->getKeyboard()->pressKey(WebDriverKeys::ENTER);
    while (true) { // Handle timeout somewhere
        $ajaxIsComplete = $driver->executeScript("if(window.jQuery){return jQuery.active==0;}else{return 1;}");
        if ($ajaxIsComplete) {
            break;
        }
        sleep(1);
    }
    if (count($driver->findElements(WebDriverBy::xpath("//div[@data-action='doLogout']"))) === 0) {
        $driver->quit();
        return "Не залогинилось";
    }
    //*********************************************************END LOGIN
    //ПЕРВЫЙ ТИП(ПРОСТО ССЫЛКА)
    if ($task['lay_out'] == 1) {
        $driver->get("http://www.miralinks.ru/project_articles/view/" . $task['miralinks_id']);

        while (true) { // Handle timeout somewhere
            $ajaxIsComplete = $driver->executeScript("if(window.jQuery){return jQuery.active==0;}else{return 1;}");
            if ($ajaxIsComplete) {
                break;
            }
            sleep(1);
        }

        $places = $driver->findElements(WebDriverBy::xpath("//a[@data-actionid='aa_place']"));
        if (count($places) === 0) {
            $driver->quit();
            return "ERROR. Нет кнопки разместить(" . $task['miralinks_id'] . ")<br>";
        }
        $places[0]->click();

        while (true) { // Handle timeout somewhere
            $ajaxIsComplete = $driver->executeScript("if(window.jQuery){return jQuery.active==0;}else{return 1;}");
            if ($ajaxIsComplete) {
                break;
            }
            sleep(1);
        }

        $urlInputs = $driver->findElements(WebDriverBy::xpath("//input[@placeholder='Введите новый URL']"));
        if (count($urlInputs) == 0) {
            $driver->quit();
            return "Ошибка отправления, поле 'input[@placeholder='Введите новый URL']' - не найдено";
        }
        $urlInputs[0]->sendKeys($task['url_statyi']);

        echo "URL statyi: " . $task['url_statyi'] . "<br>";

        while (true) { // Handle timeout somewhere
            $ajaxIsComplete = $driver->executeScript("if(window.jQuery){return jQuery.active==0;}else{return 1;}");
            if ($ajaxIsComplete) {
                break;
            }
            sleep(1);
        }
        
        $btns = $driver->findElements(WebDriverBy::xpath("//a[@data-action='confirm']"));
        foreach ($btns as $btn) {
            if ($btn->isDisplayed()) {
                $btn->click();
                break;
            }
        }
        
        while (true) { // Handle timeout somewhere
            $ajaxIsComplete = $driver->executeScript("if(window.jQuery){return jQuery.active==0;}else{return 1;}");
            if ($ajaxIsComplete) {
                break;
            }
            sleep(1);
            //echo "Sleeping a second<br>";
        }
        
        $btns = $driver->findElements(WebDriverBy::xpath("//a[@data-action='confirm']"));
        foreach ($btns as $btn) {
            if ($btn->isDisplayed()) {
                $btn->click();
                break;
            }
        }
        
        sleep(3);
        $driver->wait(3);

        $error = $driver->findElements(WebDriverBy::xpath("//span[@class='invalidMessage']"));
        
        //print_r($error[0]->getText());
        if (isset($error[0]) && $error[0]->isDisplayed()) {
            $error_text = $error[0]->getText();
            $driver->quit();
            echo "ERROR." . $error_text . "<br>";
            return "ERROR." . $error_text . "<br>";
        } else {
            $driver->quit();
            return false;
        }
    }//end ПЕРВЫЙ ТИП
    //ВТОРОЙ ТИП 
    elseif ($task['lay_out'] == 0) {
        $driver->get("http://www.miralinks.ru/ground_articles/articlePlacement/" . $task['miralinks_id']);
        echo "http://www.miralinks.ru/ground_articles/articlePlacement/" . $task['miralinks_id'] . "<br>";
        while (true) { // Handle timeout somewhere
            $ajaxIsComplete = $driver->executeScript("if(window.jQuery){return jQuery.active==0;}else{return 1;}");
            if ($ajaxIsComplete) {
                break;
            }
            sleep(1);
        }

        $headers = $driver->findElements(WebDriverBy::xpath("//input[@name='data[Article][header]']"));
        if (count($headers) == 0) {
            $driver->quit();
            return "Ошибка отправления, поле 'input[@name='data[Article][header]']' - не найдено";
        }
        $headers[0]->sendKeys($task['tema']);

        while (true) { // Handle timeout somewhere
            $ajaxIsComplete = $driver->executeScript("if(window.jQuery){return jQuery.active==0;}else{return 1;}");
            if ($ajaxIsComplete) {
                break;
            }
            sleep(1);
        }


        $urls = $driver->findElements(WebDriverBy::xpath("//input[@name='data[Article][article_url]']"));
        if (count($urls) == 0) {
            $driver->quit();
            return "Ошибка отправления, поле 'input[@name='data[Article][article_url]']' - не найдено";
        }
        $urls[0]->sendKeys($task['url_statyi']);

        while (true) { // Handle timeout somewhere
            $ajaxIsComplete = $driver->executeScript("if(window.jQuery){return jQuery.active==0;}else{return 1;}");
            if ($ajaxIsComplete) {
                break;
            }
            sleep(1);
        }


        //$texts = $driver->findElements(WebDriverBy::xpath("//iframe[starts-with(@id, 'textarea_for_:widget')]"));
        //if(count($texts)==0) return "Ошибка отправления, поле 'iframe[starts-with(@id, 'textarea_for_:widget')]' - не найдено";
        //$frameid = $texts[0]->getID();
        //echo "FRAMEID:".$frameid."<br>";
        //$driver->switchTo()->frame($texts[0]);
        //echo $task['text']."\r\n";
        //$wysywigs = $driver->findElements(WebDriverBy::xpath("//body[@id='tinymce']"));
        //if(count($wysywigs)==0) return "Ошибка отправления, поле 'input[@name='body[@id='tinymce']' - не найдено";
        //$wysywigs[0]->sendKeys("test");
        $task['text'] = str_replace(array("\r", "\n"), '', $task['text']);
        $driver->executeScript("tinyMCE.activeEditor.setContent('" . $task['text'] . "');");

        //$handles=$driver->getWindowHandles();
        //$driver->switchTo()->window($handles[0]);
        //$driver->switchTo()->defaultContent();
        //echo "Switched back<br>";

        sleep(3);

        $btns = $driver->findElements(WebDriverBy::xpath("//a[@data-action='submit']"));
        if (count($btns) == 0) {
            $driver->quit();
            return "Ошибка отправления, поле 'a[@data-action='submit']' - не найдено";
        }
        $btns[0]->click();

        while (true) { // Handle timeout somewhere
            $ajaxIsComplete = $driver->executeScript("if(window.jQuery){return jQuery.active==0;}else{return 1;}");
            if ($ajaxIsComplete) {
                break;
            }
            sleep(1);
        }

        $tmp = $driver->findElements(WebDriverBy::xpath("//input[@name='data[Article][article_url]']"));

        if (count($tmp) != 0) {
            var_dump($task);
            $driver->takeScreenshot(realpath(dirname(__FILE__)) . "/screenshots/" . $task['miralinks_id'] . ".png");
            $driver->quit();
            return "ERROR. Статья с написанием не размещена(" . $task['miralinks_id'] . ")<br>";
        } else {
            $driver->quit();
            return false;
        }
    }//end ВТОРОЙ ТИП
}

die();
