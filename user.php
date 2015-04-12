<?php

//21232f297a57a5a743894a0e4a801fc3
include('config.php');
include 'includes/adodb5/adodb.inc.php';
include 'system/class_user.php';
include 'modules/user/user_class.php';
include 'modules/moder/moder_class.php';
session_start();
error_reporting(E_ALL);

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$my = new class_user();
$user = new user();
$moder = new moder();

if (!isset($_SESSION['user']['id']) || ($_SESSION['user']['type'] != "user" && $_SESSION['user']['type'] != "moder")) {
    $user->login($db);
    exit();
}

if (isset($_SESSION['user']['id']) && $_SESSION['user']['id'] > 0) {
    $template .= $my->content($db);
}

if (isset($_REQUEST['q']) && !empty($_REQUEST['q'])) {
    $content = $my->search->search1($db, $_SESSION['user']);
} else {
    if ($_SESSION['user']["type"] == "user") {
        $content = $user->content($db);
    } else {
        $content = $moder->content($db);
    }
}

$template = str_replace('[content]', $content, $template);

echo $template;
exit();

?>
