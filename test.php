<?php
include_once 'config.php';
include_once 'includes/mandrill/mandrill.php';

$body = implode(",", $argv);

$mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
$message = array();
$message["html"] = $body;
$message["text"] = "";
$message["subject"] = "TEST";
$message["from_email"] = "news@iforget.ru";
$message["from_name"] = "iforget";
$message["to"] = array();
$message["to"][0] = array("email" => MAIL_DEVELOPER);
$message["track_opens"] = null;
$message["track_clicks"] = null;
$message["auto_text"] = null;

try {
    $mandrill->messages->send($message);
    echo $body;
} catch (Exception $e) {
    echo $e;
    echo $body;
}
die();

