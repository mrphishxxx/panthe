<?php

session_start();
error_reporting(E_ALL);
include 'config.php';
include 'system/class_copywriter.php';
include 'includes/adodb5/adodb.inc.php';
include 'modules/copywriter/copywriter_class.php';

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$my = new class_index();
$copy_user = new copywriter();
$template = $content = "";

if (isset($_SESSION['user']['id']) && $_SESSION['user']['id'] > 0) {
    if ($_SESSION['user']['type'] == "copywriter"){
        $template .= $my->content($db);
    }
}


if(!isset($_SESSION['user']['id']) || $_SESSION['user']['type'] != "copywriter"){
    $copy_user->login($db);
    exit();
}
$content .= $copy_user->content($db);

       

$template = str_replace('[content]', $content, $template);
echo $template;
?>
