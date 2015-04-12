<?php

error_reporting(E_ALL ^ E_NOTICE);
include 'config.php';
include 'includes/adodb5/adodb.inc.php';


$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$price = floatval(@$_REQUEST['summa']);
$uid = (@$_REQUEST['uid']);
$cur_dt = date("Y-m-d H:i:s");
$db->Execute("INSERT INTO orders (uid, price, date, status) VALUES ($uid, '$price', '$cur_dt', 0)");
$res = $db->Execute("SELECT * FROM orders WHERE date='$cur_dt' AND uid=$uid AND price='$price' AND status=0")->FetchRow();
$oid = $res['id'];

echo $oid;
?>
