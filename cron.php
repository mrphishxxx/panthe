<?php
session_start();
$_SESSION["admin"] = true;
require_once 'cron/index.php';

