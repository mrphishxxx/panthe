<?php

error_reporting(E_ALL ^ E_NOTICE);
include('config.php');
include 'includes/adodb5/adodb.inc.php';
$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$admins = $db->Execute("SELECT * FROM admins WHERE type='moder' ORDER BY login");
while ($moder = $admins->FetchRow()) {
    $buffer = array();
    echo "<br>----> USER ".$moder["login"]." (".$moder["id"].")<br>";
    $moders_money = $db->Execute("SELECT * FROM moders_money WHERE moder_id = '".$moder["id"]."'")->GetAll();
    foreach ($moders_money as $task) {
        $buffer[] = $task["zid"];
    }
    
    $sum_num_tasks = $db->Execute("SELECT count(z.id) as num, SUM(s.price_viklad) as sum FROM zadaniya z LEFT JOIN sayty s ON s.id=z.sid WHERE z.who_posted = '" . $moder["id"] . "' AND z.vipolneno = 1")->FetchRow();
    print_r($sum_num_tasks);
    
    $tasks = $db->Execute("SELECT z.id, s.price_viklad FROM zadaniya z LEFT JOIN sayty s ON s.id=z.sid WHERE z.who_posted = '" . $moder["id"] . "' AND z.vipolneno = 1")->GetAll();
    foreach ($tasks as $value) {
        if(empty($buffer) || !in_array($value["id"], $buffer)){
            $db->Execute("INSERT INTO moders_money (moder_id, zid, price) VALUES ('".$moder["id"]."', '".$value["id"]."', '".$value["price_viklad"]."')");
            $buffer[] = $value["id"];
        }
    }
}

