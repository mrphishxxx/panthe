<?php

session_start();
include 'config.php';
include_once 'includes/Rotapost.php';
//include 'includes/adodb5/adodb.inc.php';

error_reporting(E_ALL);
/*
  $db = ADONewConnection(DB_TYPE);
  @$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
  $db->Execute('set charset utf8');
  $db->Execute('SET NAMES utf8');
 */
$url_statyi = "ramamama.ru/koktejlnoe-ili-vechernee-plate-opredelyayushhie-vybor-otlichiya/";
$sinfo = "http://ramamama.ru/";

if (!mb_strstr($url_statyi, $sinfo)) { 
    echo "В поле `Ссылка на статью` url не соответствует сайту!";
}
//print_r($New);
die();
?>

