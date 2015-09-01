<?php
include_once 'config.php';
include 'includes/adodb5/adodb.inc.php';
$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$query = $db->Execute("select c.* from completed_tasks c LEFT JOIN zadaniya z ON c.zid=z.id where z.type_task = 3 order by c.date")->GetAll();
print_r($query);
die();

