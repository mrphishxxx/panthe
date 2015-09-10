<?php

error_reporting(E_ALL);
include_once dirname(__FILE__) . '/../' . 'config.php';
include_once dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
include_once dirname(__FILE__) . '/../' . 'includes/phpQuery/phpQuery.php';
//include_once dirname(__FILE__) . '/../' . 'includes/gt_functions.php';
include_once dirname(__FILE__) . '/../' . 'modules/admins/class_admin_admins.php';

define("MIRALINKS_URL", "http://www.miralinks.ru/");

global $db;
$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

//$miralink = new Miralinks();


$access = $db->Execute("SELECT * FROM birjs WHERE birj=5 AND uid=649")->FetchRow();
construct();
login($access["login"], $access["pass"]);



//class Miralinks {

    global $proxy_counter;
    global $proxy_data;
    global $login;
    global $pass;

    function construct() {
        global $proxy_counter;
        global $proxy_data;
        phpQuery::$debug = true;
        
        $proxy = file(PATH . "modules/angry_curl/proxy_list.txt");
        foreach ($proxy as $p) {
            $proxy_data[] = array(
                'proxy_host' => substr($p, 0, strpos($p, ":")),
                'proxy_port' => (int) substr($p, strpos($p, ":") + 1),
                'proxy_user' => PROXY_PASS,
                'proxy_pass' => PROXY_PASS
            );
        }
        $proxy_counter = mt_rand(0, 69);

        phpQuery::$proxy_host = $proxy_data[$proxy_counter]['proxy_host'];
        phpQuery::$proxy_port = $proxy_data[$proxy_counter]['proxy_port'];
        phpQuery::$proxy_user = $proxy_data[$proxy_counter]['proxy_user'];
        phpQuery::$proxy_pass = $proxy_data[$proxy_counter]['proxy_pass'];
    }

    function login($l, $p) {
        global $login;
        global $pass;
        if (!empty($l) && !empty($p)) {
            $login = $l;
            $pass = $p;
            phpQuery::browserGet(MIRALINKS_URL . 'users/login', "loginInSystem");
        } else {
            die("Логин или пароль отсутствует!");
        }
    }

    function loginInSystem($browser) {
        global $login;
        global $pass;
        $browser->WebBrowser("loginDone")
                ->find("input[id=UserLogin]")
                ->val($login)->end()
                ->find("input[id=UserPassword]")
                ->val($pass)
                ->parents('form')
                ->submit();
        var_dump($this->proxy_counter);
        
    }
    
    function loginDone($browser) {
        $browser
            ->WebBrowser("sendTask")
            ->location(MIRALINKS_URL . "ground_articles/articlePlacement/6034395");
        echo "asdasd";
    }
    
    function sendTask($browser) {
        global $db;
        $task = $db->Execute("SELECT * FROM zadaniya WHERE miralinks_id=6034395")->FetchRow();
        $text = str_replace(array("\r", "\n"), '', $task['url_statyi']);
        $browser->find("input[id=htmlSource]")
                ->val($text)
                ->parents('form')
                ->submit();
        
        $browser->WebBrowser("done")
                ->find("input[name$='[header]']")
                ->val($task["tema"])->end()
                ->find("input[name$='[article_url]']")
                ->val($task["url_statyi"])
                ->parents('form')
                ->submit();
        echo "qwe123qwe";
    }
    
    function done($browser) {
        echo "function DONE";
    }



//}

exit();
