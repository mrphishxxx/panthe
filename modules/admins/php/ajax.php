<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
include('../../../config.php');

include PATH.'includes/adodb5/adodb.inc.php';
$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
	
$action = $_REQUEST['action'];
if($action == 'login_check'){
	$login = $_REQUEST['login'];
	$query = $db->Execute("select * from admins where login='$login'");
	$res = $query->FetchRow();
	if(!$res['id']) echo 1;
}


?>