<?php

session_start();
error_reporting(E_ALL ^ E_NOTICE);
include('config.php');
require_once 'includes/phpxls/excel_reader2.php';
include 'includes/adodb5/adodb.inc.php';
$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$admins = $db->Execute("SELECT * FROM admins WHERE active=1 AND type='user'");
while ($admin = $admins->FetchRow()) {
    echo "<br>----> USER ".$admin["login"]." (".$admin["id"].")<br>";
    $site = $db->Execute("SELECT * FROM sayty WHERE uid=" . $admin['id']);
    $sites = array();
    while ($row = $site->FetchRow()) {
        $sites[$row['id']] = $row;
    }

    $completed = $db->Execute("SELECT * FROM completed_tasks WHERE uid=" . $admin['id']);
    $compl = array();
    if ($completed) {
        while ($row = $completed->FetchRow()) {
            $compl[$row['zid']] = $row['price'];
        }
    }
    $tasks = array();
    foreach ($sites as $sid => $value) {
        $zadaniya = $db->Execute("SELECT * FROM zadaniya WHERE date>'1414800000' AND sid=$sid AND uid=" . $admin['id']);
        while ($zad = $zadaniya->FetchRow()) {
            if (array_key_exists($zad["id"], $compl)) {

                $price = 0;
                if ($zad['lay_out'] == 1) {
                    $price = 15;
                } else if ($zad["sistema"] == "http://miralinks.ru/" || $zad["sistema"] == "http://pr.sape.ru/" || $zad["sistema"] == "http://getgoodlinks.ru/") {
                    switch ($sites[$sid]['cena']) {
                        case 20:$price = 60;
                            break;
                        case 30:$price = 76;
                            break;
                        case 45:$price = 111;
                            break;
                        default:$price = 60;
                            break;
                    }
                } else {
                    $price = $sites[$sid]['price'];
                }
                if ($admin["new_user"] == 1) {
                    $price = (int) $price + 17;
                }
                
                
                if($price != $compl[$zad['id']]){
                    if($zad['navyklad'] == 1 || $zad['dorabotka'] == 1 || $zad['vilojeno'] == 1 || $zad['vipolneno'] == 1 || $zad['vrabote'] == 1)
                        echo "cena = $price in DB = ".$compl[$zad['id']] ." (zad = ".$zad['id'].")<br>";
                }
            } else {
                if($zad['navyklad'] == 1 || $zad['dorabotka'] == 1 || $zad['vilojeno'] == 1 || $zad['vipolneno'] == 1 || $zad['vrabote'] == 1)
                    echo "Zad " . $zad["id"] . " - есть, а денег не сняли <br>";
            }
            $tasks[$zad['id']] = $zad;
        }
    }




    

}
?>
