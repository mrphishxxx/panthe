<?php
session_start();
$NETCAT_FOLDER = join( strstr(__FILE__, "/") ? "/" : "\\", array_slice( preg_split("/[\/\\\]+/", __FILE__), 0, -1 ) ).( strstr(__FILE__, "/") ? "/" : "\\" );
include_once ($NETCAT_FOLDER."vars.inc.php");
if(!empty($_REQUEST) && isset($_REQUEST["promo"])){
    $_SESSION["promo"] = $_REQUEST["promo"];
}
require ($INCLUDE_FOLDER."e404.php");
?>