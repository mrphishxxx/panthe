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
define("WEBARTEX", 6);
define("BLOGUN", 8);

define("GOGETLINKS_URL", "https://gogetlinks.net/");
define("GETGOODLINKS_URL", "http://getgoodlinks.ru/");
define("ROTAPOST_URL", "");
define("SAPE_URL", "http://api.pr.sape.ru/xmlrpc/");
define("WEBARTEX_URL", "https://api.webartex.ru");

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
$date = time()-432000; // - 5 DAYs
$tasks = $db->Execute("SELECT * FROM zadaniya WHERE "
        . "sistema IN ("
            . "'https://gogetlinks.net/', "
            . "'http://pr.sape.ru/', "
            . "'http://rotapost.ru/',"
            . "'http://webartex.ru/'"
        . ") AND (rotapost_id != 0 OR webartex_id != 0 OR sape_id != 0 OR b_id != 0) AND vipolneno = 1 AND date > '$date'");
$count = $tasks->NumRows();
$yes = $no = "";

if ($count > 0) {
    while ($task = $tasks->FetchRow()) {
        $sinfo = $db->Execute("SELECT * FROM sayty WHERE id=" . $task["sid"])->FetchRow();
        $sinfo["url"] = str_replace("/", "", str_replace("http://", "", str_replace("www.", "", $sinfo["url"])));
        if ((!empty($task["url_statyi"]) && $task["url_statyi"] != "" && strstr($task["url_statyi"], $sinfo["url"]))) {
            $err = false;
            switch ($task["sistema"]) {
                case 'https://gogetlinks.net/':
                    if (!empty($task["b_id"]) && $task["b_id"] != 0) {
                        $err = setTaskGGL($db, $task);
                    } else {
                        $err = "Отсутствует ID задачи. Скорей всего задача заведена руками!";
                        continue;
                    }
                    break;
                case 'http://getgoodlinks.ru/':
                    if (!empty($task["b_id"]) && $task["b_id"] != 0) {
                        $err = setTaskGetGoodLinks($db, $task);
                    } else {
                        $err = "Отсутствует ID задачи. Скорей всего задача заведена руками!";
                        continue;
                    }
                    break;
                case 'http://pr.sape.ru/':
                    if (!empty($task["sape_id"]) && $task["sape_id"] != 0) {
                        $err = setTaskSape($db, $task);
                    } else {
                        $err = "Отсутствует ID задачи. Скорей всего задача заведена руками!";
                        continue;
                    }
                    break;
                case 'http://rotapost.ru/':
                    if (!empty($task["rotapost_id"]) && $task["rotapost_id"] != 0) {
                        $err = setTaskRotapost($db, $task);
                    } else {
                        $err = "Отсутствует ID задачи. Скорей всего задача заведена руками!";
                        continue;
                    }
                    break;
                case 'http://webartex.ru/':
                    if (!empty($task["webartex_id"]) && $task["webartex_id"] != 0) {
                        $err = setTaskWebartex($db, $task);
                    } else {
                        $err = "Отсутствует ID задачи. Скорей всего задача заведена руками!";
                        continue;
                    }
                    break;
                case 'https://blogun.ru/':
                    if (!empty($task["b_id"]) && $task["b_id"] != 0) {
                        $err = setTaskBlogun($db, $task);
                    } else {
                        $err = "Отсутствует ID задачи. Скорей всего задача заведена руками!";
                        continue;
                    }
                    break;
                default : $err = 'В данную биржу (' . $task["sistema"] . ') задания отправить не возможно!';
            }
            $main = "Задача <a href='http://iforget.ru/admin.php?module=admins&action=zadaniya&uid=" . $task['uid'] . "&sid=" . $task['sid'] . "&action2=edit&id=" . $task['id'] . "'>" . $task['id'] . "</a> - ";
            if (!$err) {
                $yes .= $main . "Отправлена в биржу [" . $task["sistema"] . "]!<br/><br/>";
            } else {
                $no .= $main . "Ошибка отправления в биржу [" . $task["sistema"] . "]<br/>'<strong>$err</strong>'!<br/><br/>";
            }
        }
    }
    $body = "<h3>Задачи, которые были отправлены:</h3> " . $yes;
    $body .= "<br /><br /> <h3>Задачи, которые НЕ отправлены:</h3> " . $no;
} else {
    $body = NULL;
}

$mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
$message = array();
$message["html"] = $body;
$message["text"] = "";
$message["subject"] = "Отправка ссылок задач в статусе Выполнено";
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

function setTaskGGL($db, $task) {
    $birj = $db->Execute("select * from birjs where birj='" . GOGETLINKS . "' AND uid=" . $task["uid"])->FetchRow();
    $data = array('e_mail' => $birj['login'], 'password' => trim($birj['pass']), 'remember' => "");

    $cookie_jar = tempnam(PATH . 'temp', "cookie");
    $auth = executeRequest('POST', GOGETLINKS_URL . 'login.php', null, $cookie_jar, array(), $data, null);
    $page = iconv("windows-1251", "utf-8", $auth);
    if ($page == "Некорректный Логин или Пароль" || $page == "Некорректный email или Пароль") {
        /*  Если НЕ залогинились отправляем ошибку админу  */
        return $page;
    } else {
        /*  ИНАЧЕ отправляем ссылку в ГГЛ  */
        $data = array('curr_id' => $task["b_id"], 'URL' => $task['url_statyi'], 'path' => "Главная -> ");
        $send = executeRequest('POST', GOGETLINKS_URL . 'template/check_exist_view.php', null, $cookie_jar, array(), $data, null);
        $out = iconv("windows-1251", "utf-8", $send);
        if (strstr($out, "Обзор проверен системой и отправлен на проверку оптимизатору")) {
            /*  Если ответ с ГГЛ "НОРМ", то подтверждаем отправку  */
            return false;
        } else {
            /*  Иначе отправляем ошибку админу  */
            return $out;
        }
    }
}

function setTaskGetGoodLinks($db, $task) {
    $birj = $db->Execute("select * from birjs where birj='" . GETGOODLINKS . "' AND uid=" . $task["uid"])->FetchRow();
    $data = array('e_mail' => $birj['login'], 'password' => trim($birj['pass']), 'remember' => "");

    $cookie_jar = tempnam(PATH . 'temp', "cookie");
    $auth = executeRequest('POST', GETGOODLINKS_URL . 'login.php', null, $cookie_jar, array(), $data, null);
    $page = iconv("windows-1251", "utf-8", $auth);

    $error = strpos($page, "Неверное имя пользователя или пароль. Пожалуйста, попробуйте ещё раз.");
    if ($error !== false && !empty($error) && $error != 0) {
        /*  Если НЕ залогинились отправляем ошибку админу  */
        return $page;
    } else {
        /*  ИНАЧЕ отправляем ссылку в GETGOODLINKS  */
        $data = array('curr_id' => $task["b_id"], 'URL' => $task['url_statyi'], 'path' => "Главная -> ");
        $send = executeRequest('POST', GETGOODLINKS_URL . 'template/check_exist_view.php', null, $cookie_jar, array(), $data, null);
        //$out = iconv("windows-1251", "utf-8", $send);

        if (strstr($send, "Обзор проверен системой и отправлен на проверку оптимизатору")) {
            /*  Если ответ с getgoodlinks "НОРМ", то подтверждаем отправку  */
            return false;
        } else {
            /*  Иначе отправляем ошибку админу  */
            return $send;
        }
    }
}

function setTaskSape($db, $task) {
    $birj = $db->Execute("select * from birjs where birj='" . SAPE . "' AND uid=" . $task["uid"])->FetchRow();

    $data = xmlrpc_encode_request('sape_pr.login', array($birj["login"], $birj["pass"]));
    $header[] = "Content-type: text/xml";
    $header[] = "Content-length: " . strlen($data);
    $cookie_jar = tempnam(PATH . 'temp', "cookie");

    $auth = executeRequest('POST', SAPE_URL, null, $cookie_jar, array(), $data, $header);
    $id_user_sape = xmlrpc_decode($auth);
    if (!is_array($id_user_sape)) {
        /*  Если залогинились, отправляем ссылку  */
        $data = xmlrpc_encode_request('sape_pr.advert.place', array((int) $task["sape_id"], $task['url_statyi']));
        $header[1] = "Content-length: " . strlen($data);
        $send = executeRequest('POST', SAPE_URL, null, $cookie_jar, array(), $data, $header);
        $err = xmlrpc_decode($send);
        //(isset($send['faultString']) && !empty($send['faultString'])) || 
        if ((isset($err['faultString']) && !empty($err['faultString']))) {
            return $err['faultString'];
        } else {
            return false;
        }
    } else {
        /*  Если нет пользователя, отправляем админу письмо с ошибкой (задача все равно переводится в статус Выполнено  */
        return "Не верный логин или пароль для доступа к биржи";
    }
}

function setTaskRotapost($db, $task) {
    $rotapost = new Rotapost\Client();
    $birj = $db->Execute("SELECT * FROM birjs WHERE birj='" . ROTAPOST . "' AND uid=" . $task["uid"])->FetchRow();
    $auth = $rotapost->loginAuth($birj['login'], md5($birj['login'] . $birj['pass']));

    if (($birj['login'] == null || $birj['pass'] == null) || (isset($auth->Success) && $auth->Success == "false") || !isset($auth->ApiKey)) {
        //  Если нет логина или пароля от биржи, отправляем админу письмо с ошибкой 
        $err = (array) $auth->Error;
        if ($birj['login'] == null || $birj['pass'] == null) {
            return "Отсутствует логин или пароль для доступа к биржи Rotapost</strong>";
        } elseif ($auth->Success == "false" && isset($err["Description"])) {
            return $err["Description"];
        }
    } else {
        //  ИНАЧЕ отправляем в ротапост ссылку 
        $result = $rotapost->taskComplete($task["rotapost_id"], $task['url_statyi']);
        if ((isset($result->Success) && $result->Success == "true")) {
            return false;
        } elseif ($result->Success == "false") {
            /*  Иначе отправляем ошибку админу  */
            $err = (array) $result->Error;
            return $err["Description"];
        }
    }
}

function setTaskWebartex($db, $task) {
    $birj = $db->Execute("select * from birjs where birj='" . WEBARTEX . "' AND uid=" . $task["uid"])->FetchRow();
    $login = $birj['login'];
    $pass = $birj['pass'];

    if ($curl = curl_init()) {
        curl_setopt($curl, CURLOPT_URL, WEBARTEX_URL . "/api/webmaster/articles/check/" . $task["webartex_id"] . "?url=" . $task['url_statyi']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERPWD, $login . ":" . $pass);
        @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        $out = curl_exec($curl);
        curl_close($curl);
    }
    $send = json_decode($out);
    //print_r($send);die();
    $object = (array) $send->object;
    $err = @$send->error;
    if (empty($err) && isset($object) && isset($object["status"]) && $object["status"] == "waitindex" && !empty($object["url"])) {
        /*  Если status с webartex "Ожидает индексации", то подтверждаем отправку  */
        return false;
    } else {
        /*  Иначе отправляем ошибку админу  */
        return $err;
    }
}

function setTaskBlogun($db, $task) {
    $birj = $db->Execute("select * from birjs where birj='" . BLOGUN . "' AND uid=" . $task["uid"])->FetchRow();
    $proxy_file = file(dirname(__FILE__) . '/../' . "modules/angry_curl/proxy_list.txt");
    $proxies = array();
    foreach ($proxy_file as $p) {
        $proxies[] = array(
            'proxy_host' => substr($p, 0, strpos($p, ":")),
            'proxy_port' => (int) substr($p, strpos($p, ":") + 1),
            'proxy_user' => PROXY_PASS,
            'proxy_pass' => PROXY_PASS
        );
    }

    $proxy = $proxies[rand(0, count($proxies) - 1)];
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

    $driver->get('https://blogun.ru/');
    $driver->wait(15);
    $login = $driver->findElement(WebDriverBy::xpath("//input[@name='login']"));
    $login->sendKeys($birj['login']);
    $pass = $driver->findElement(WebDriverBy::xpath("//input[@name='password']"));
    $pass->sendKeys($birj['pass']);
    $btn = $driver->findElement(WebDriverBy::xpath("//button[@type='submit']"));
    $btn->click();

    if (count($driver->findElements(WebDriverBy::xpath("//a[@class='amount']"))) === 0) {
        $driver->close();
        return "Ошибка отправления, поле 'a[@class='amount']' - не найдено";
    }

    $sayty = $db->Execute("SELECT * FROM sayty WHERE uid='" . $task['uid'] . "' AND id='" . $task['sid'] . "'")->FetchRow();
    $driver->get('https://blogun.ru/getcode.php?id=' . $task['b_id'] . '&idblog=' . $sayty['blogun_id'] . '&submenu=2&menu=tsk');
    $url = $driver->findElement(WebDriverBy::xpath("//input[@id='url']"));
    $url->sendKeys($task['url_statyi']);
    $btn = $driver->findElement(WebDriverBy::xpath("//input[@name='submit']"));
    $btn->click();

    $driver->close();
    return FALSE;
}

function executeRequest($method, $url, $useragent, $cookie, $query, $body, $header) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    if (count($query) > 0) {
        $url = $url . '&' . http_build_query($query);
    }
    if ($cookie) {
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
    }
    if ($useragent) {
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    }
    if ($method == 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }
    if ($header) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    $response = curl_exec($ch);
    if (!$response) {
        curl_close($ch);
        return curl_error($ch);
    }
    curl_close($ch);
    return $response;
}

die();
?>
