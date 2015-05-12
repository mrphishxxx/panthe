<?php

error_reporting(E_ALL ^ E_NOTICE);
include(dirname(__FILE__) . '/../' . 'config.php');
include dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$buffer = array();
//Задача для крона, проверяет, не прошло ли 5 дней с момента завершения заявки, если прошло - пишет статус = 1, после этого из баланса начинает вычитаться эта сумма
$completed_tasks = $db->Execute("SELECT * FROM moders_money")->GetAll();
foreach ($completed_tasks as $task) {
    $buffer[] = $task["zid"];
}

$tasks = $db->Execute("SELECT z.id, z.who_posted, s.price_viklad FROM zadaniya z LEFT JOIN sayty s ON s.id=z.sid WHERE z.who_posted != 0 AND z.vipolneno = 1 AND s.price_viklad != 0 AND s.price_viklad IS NOT NULL");
foreach ($tasks as $value) {
    if (!in_array($value["id"], $buffer)) {
        if ($value["price_viklad"] == NULL) {
            $value["price_viklad"] = 0;
        }
        $db->Execute("INSERT INTO moders_money (moder_id, zid, price) VALUES ('" . $value["who_posted"] . "', '" . $value["id"] . "', '" . $value["price_viklad"] . "')");
        $buffer[] = $value["id"];
        echo "<br>----> USER " . $value["who_posted"] . " (" . $value["id"] . ")<br>";
    }
}
exit();