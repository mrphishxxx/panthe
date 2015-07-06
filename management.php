<?php

session_start();
error_reporting(E_ALL ^ E_NOTICE);
include 'config.php';
include 'configs/setup-smarty.php';

include 'includes/adodb5/adodb.inc.php';
include 'includes/postman/Postman.php';
include 'system/class_manager.php';

$smarty = new Smarty_Project();

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$my = new class_manager();
$content = $my->content();



echo $content;
?>