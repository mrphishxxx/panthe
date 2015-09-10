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


$accs = $db->Execute("select * from birjs where birj=5");
while ($birj = $accs->FetchRow()) 
{
	var_dump($birj);
	$proxy_file = file(dirname(__FILE__) . '/../' . "modules/angry_curl/proxy_list.txt");
	$proxies = array();
	foreach ($proxy_file as $p) {
		$proxies[] = array(
			'domain' => substr($p, 0, strpos($p, ":")),
			'port' => (int) substr($p, strpos($p, ":") + 1),
			'user' => PROXY_LOGIN,
			'pass' => PROXY_PASS
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
		if ($ajaxIsComplete){
			break;
		}
		sleep(1);
	}
	$logins = $driver->findElements(WebDriverBy::xpath("//input[@id='UserLogin']"));
	if(count($logins)==0) {$driver->quit(); echo "Ошибка отправления, поле 'input[@name='UserLogin']' - не найдено";continue;}
	$logins[0]->sendKeys($birj['login']);
	
	$pass = $driver->findElements(WebDriverBy::xpath("//input[@id='UserPassword']"));
	if(count($pass)==0) {$driver->quit(); echo "Ошибка отправления, поле 'input[@name='UserPassword']' - не найдено";continue;}
	$pass[0]->sendKeys($birj['pass']);
	
	echo "MIRA: ".$birj['login']." --- ".$birj['pass']."\r\n";
	
	while (true) { // Handle timeout somewhere
		$ajaxIsComplete = $driver->executeScript("if(window.jQuery){return jQuery.active==0;}else{return 1;}");
		if ($ajaxIsComplete){
			break;
		}
		sleep(1);
	}
	$driver->getKeyboard()->pressKey(WebDriverKeys::ENTER);
	while (true) { // Handle timeout somewhere
		$ajaxIsComplete = $driver->executeScript("if(window.jQuery){return jQuery.active==0;}else{return 1;}");
		if ($ajaxIsComplete){
			break;
		}
		sleep(1);
	}
	if (count($driver->findElements(WebDriverBy::xpath("//div[@data-action='doLogout']"))) === 0) {
		$driver->quit();
		echo "Not logged in. Не залогинилось";
		continue;
	}
	
	
	//*********************************************************END LOGIN
	$driver->get('http://www.miralinks.ru/ground_articles/allCmArticlesList/achtung#/start:0/count:15/sort:6.desc/filter:5.e-.["cm_invited"]');
	echo "Loaded page with articles\r\n";
	while (true) { // Handle timeout somewhere
		$ajaxIsComplete = $driver->executeScript("if(window.jQuery){return jQuery.active==0;}else{return 1;}");
		if ($ajaxIsComplete){
			break;
		}
		sleep(2);
	}
	
	$driver->wait(10);
	sleep(10);
	
	$checkall = $driver->findElements(WebDriverBy::xpath("//div[@class='customCheckbox']"));
	if (count($checkall) === 0) {
	   $driver->quit();  echo "WARNING. Нет checkbox выбрать все(может нет статей для принятия)";  exit();
	}
	if($checkall[0]->isDisplayed())
	{
		$checkall[0]->click();
		
		echo "Clicked checkbox\r\n";
		while (true) { // Handle timeout somewhere
			$ajaxIsComplete = $driver->executeScript("if(window.jQuery){return jQuery.active==0;}else{return 1;}");
			if ($ajaxIsComplete){
				break;
			}
			sleep(2);
		}	
		
			$accept = $driver->findElements(WebDriverBy::xpath("//a[@data-actionid='cm_take']"));
			if (count($accept) === 0) {
			   $driver->quit();  echo "ERROR. Нет кнопки принять"; exit();
			}
			if($accept[0]->isDisplayed())
			{
				$accept[0]->click();
				echo "Clicked accept\r\n";
				while (true) { // Handle timeout somewhere
					$ajaxIsComplete = $driver->executeScript("if(window.jQuery){return jQuery.active==0;}else{return 1;}");
					if ($ajaxIsComplete){
						break;
					}
					sleep(2);
				}
				$driver->wait(10);
				sleep(10);
			} 
	}
	
	$driver->quit();
} 
exit();
?>
