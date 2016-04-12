<?php

header("Content-Type: text/html; charset=utf-8");
mb_internal_encoding("UTF-8");
set_time_limit(0);
ini_set("memory_limit", "1024M");
ini_set("max_execution_time", "0");
error_reporting (E_ALL);

include_once dirname(__FILE__) . '/../' . 'config.php';
include_once dirname(__FILE__) . '/../' . 'configs/setup-smarty.php';
include_once dirname(__FILE__) . '/../' . 'includes/postman/Postman.php';
include_once dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
include_once dirname(__FILE__) . '/../' . 'modules/parser/ParserController.php';

echo date("Y-m-d H:i:s") . PHP_EOL;
if ((isset($argv) && isset($argv[1]) && !empty($argv[1])) || isset($_GET["param"])) {
    $db = ADONewConnection(DB_TYPE);
    @$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
    $db->Execute('set charset utf8');
    $db->Execute('SET NAMES utf8');

    $smarty = new Smarty_Project();
    $parser = new ParserController($db, $smarty);

    $body = NULL;
    if (isset($_GET["param"]) && !isset($argv[1])) {
        $argv[1] = $_GET["param"];
    }
    switch ($argv[1]) {
        case "getgoodlinks":
            $body = $parser->getTasksGetgoodlinksAction();
            break;
        
        case "miralinks":
            $body = $parser->getTasksMiralinksAction();
            break;
        
        default: $body = "unknown argument";
    }
    $parser->saveLogs($argv[1]);
} else {
    $body = "Not argument!" . (isset($argv) ? implode("; ", $argv) : "");
}
echo PHP_EOL . date("Y-m-d H:i:s") . PHP_EOL . $body . PHP_EOL;
exit();
