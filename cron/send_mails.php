<?php

include_once dirname(__FILE__) . '/../' . 'config.php';
include_once dirname(__FILE__) . '/../' . 'configs/setup-smarty.php';
include_once dirname(__FILE__) . '/../' . 'includes/postman/Postman.php';
include_once dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
include_once dirname(__FILE__) . '/../' . 'modules/mails/MailsController.php';

if ((isset($argv) && isset($argv[1]) && !empty($argv[1])) || isset($_GET["param"])) {
    $db = ADONewConnection(DB_TYPE);
    @$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
    $db->Execute('set charset utf8');
    $db->Execute('SET NAMES utf8');

    $smarty = new Smarty_Project();
    $mails = new MailsController($db, $smarty);

    $body = NULL;
    if (isset($_GET["param"]) && !isset($argv[1])) {
        $argv[1] = $_GET["param"];
    }
    switch ($argv[1]) {
        case "started":
            $body = $mails->gettingStartedAction();
            break;
        case "promocode":
            $body = $mails->promocodeAction();
            break;
        case "endedBalance":
            $body = $mails->endedBalance();
            break;
        case "balanceComesToEnd":
            $body = $mails->balanceComesToEnd();
            break;
        case "checkMailBalance":
            $body = $mails->checkMailBalance();
            break;

        default: $body = "unknown argument";
    }
} else {
    $body = "Not argument!" . implode("; ", $argv);
}
echo $body;
exit();
