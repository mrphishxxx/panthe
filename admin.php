<?php

session_start();
error_reporting(E_ALL ^ E_NOTICE);
include('config.php');
include('configs/setup-smarty.php');
include('includes/postman/Postman.php');

require_once 'includes/phpxls/excel_reader2.php';

include 'includes/adodb5/adodb.inc.php';
$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$smarty = new Smarty_Project();
//$db->debug = true;

if (isset($_GET['act']) && $_GET['act'] == "getXLS") {

    $uid = (int) $_REQUEST['uid'];
    $query = $db->Execute("select * from admins where id=$uid");
    $res = $query->FetchRow();

    $sid = (int) $_GET['sid'];
    $query = $db->Execute("select * from sayty where id=$sid");
    $res = $query->FetchRow();

    $zadaniya = '';

    if ($_GET['from'] || $_GET['to']) {
        if ($_GET['from'])
            $from = strtotime($db->escape($_GET['from']));

        if ($_GET['to'])
            $to = strtotime($db->escape($_GET['to']));

        if ($from && $to) {
            $query = $db->Execute("select * from zadaniya where sid=$sid AND date BETWEEN '" . $from . "' AND '" . $to . "' order by date");
        } else {
            if ($from)
                $query = $db->Execute("select * from zadaniya where sid=$sid AND date >= '" . $from . "' order by date");
            else {
                $query = $db->Execute("select * from zadaniya where sid=$sid AND date <= '" . $to . "' order by date");
            }
        }
    }
    else
        $query = $db->Execute("select * from zadaniya where sid=$sid order by date");

    header('Content-Type: text/html; charset=windows-1251');
    header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', FALSE);
    header('Pragma: no-cache');
    header('Content-transfer-encoding: binary');
    header('Content-Disposition: attachment; filename=list.xls');
    header('Content-Type: application/x-unknown');

    echo '
		<table border="1">
		<tr>
			<td>' . @htmlentities("Sistema", ENT_QUOTES, "utf8") . '</td>
			<td>' . @htmlentities("Ankor", ENT_QUOTES, "utf8") . '</td>
			<td>' . @htmlentities("URL", ENT_QUOTES, "utf8") . '</td>
			<td>' . @htmlentities("Keywords", ENT_QUOTES, "utf8") . '</td>
			<td>' . @htmlentities("Tema", ENT_QUOTES, "utf8") . '</td>
			<td>' . @htmlentities("URL statji", ENT_QUOTES, "utf8") . '</td>
			<td>' . @htmlentities("Date", ENT_QUOTES, "utf8") . '</td>
			<td>' . @htmlentities("Text", ENT_QUOTES, "utf8") . '</td>
			<td>' . @htmlentities("Dorabotka", ENT_QUOTES, "utf8") . '</td>
			<td>' . @htmlentities("Vipolneno", ENT_QUOTES, "utf8") . '</td>
		</tr>';

    $n = 0;
    while ($res = $query->FetchRow()) {
        $system = $res['sid'];
        $system = $db->Execute("select * from sayty where id=$system");
        $system = $system->FetchRow();

        echo '
			<table border="1">
			<tr>
				<td>' . @htmlentities(iconv("utf-8", "windows-1251", $res['sistema']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . @htmlentities(iconv("utf-8", "windows-1251", $res['ankor']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . @htmlentities(iconv("utf-8", "windows-1251", $res['url']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . @htmlentities(iconv("utf-8", "windows-1251", $res['keywords']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . @htmlentities(iconv("utf-8", "windows-1251", $res['tema']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . @htmlentities(iconv("utf-8", "windows-1251", $res['url_statyi']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . @htmlentities(iconv("utf-8", "windows-1251", date("d-m-Y", $res['date'])), ENT_QUOTES, "cp1251") . '</td>
				<td>' . @htmlentities(iconv("utf-8", "windows-1251", $res['text']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . @htmlentities(iconv("utf-8", "windows-1251", ($res['dorabotka'] ? "ДА" : "НЕТ")), ENT_QUOTES, "cp1251") . '</td>
				<td>' . @htmlentities(iconv("utf-8", "windows-1251", ($res['vipolneno'] ? "ДА" : "НЕТ")), ENT_QUOTES, "cp1251") . '</td>
			</tr>';
    }
    echo '</table>';

    exit();
}

include 'system/class_admin.php';
$my = new class_index();


if (isset($_SESSION['admin']['id']) && ($_SESSION['admin']['id'] > 0)) {
    $auth_block = '
		<!-- userbar -->
		<div id="userbar">
			
			<a id="user_login" href="#">' . $_SESSION['admin']['login'] . '</a>
			
			<span class="sep"></span>
			
			<a id="log_off" href="/admin.php?module=admins&action=logout"><span class="ico"></span> <span>Выход</span> </a>
			
		</div>
	';
} else {

    $auth_block = '
		<!-- login -->
		<div id="login">
			<form action="/user.php" method="POST">
			
				<input type="text" name="login" value="" placeholder="Логин" />
				<input type="text" name="pass" value="" placeholder="Пароль" />
				<input type="submit" value="Вход" />
				
				<div class="registration-recovery">
					<a href="/register.php" id="registration">Регистрация</a>
				</div>
			
			</form>
		</div>
	';
}

$content = $my->content();
$content = str_replace('[auth_block]', $auth_block, $content);

$admins_managers = $users_type = $new_tick_user = $new_tick_moder = $new_tick_copywriter = $new_tick_manager = $new_wallet = array();
$admins = $db->Execute("SELECT id, type FROM admins")->GetAll();
foreach ($admins as $user) {
    if($user["type"] == "admin" || $user["type"] == "manager") {
        $admins_managers[] = $user['id'];
    } else {
        $users_type[$user["id"]] = $user["type"];
    }
}
$new_tick = $db->Execute("SELECT t.id, t.uid, t.to_uid, t.status FROM tickets t WHERE t.status != 0")->GetAll();
foreach ($new_tick as $ticket) {
    if(in_array($ticket["uid"], $admins_managers)) {
        if(in_array($ticket["to_uid"], $admins_managers)){
            $type = "manager";
        } else {
            $type = $users_type[$ticket["to_uid"]];
        }
    } else if(isset($users_type[$ticket["uid"]])) {
        $type = $users_type[$ticket["uid"]];
    }
    switch ($type) {
        case "user":
            $new_tick_user[] = $ticket;
            break;
        case "moder":
            $new_tick_moder[] = $ticket;
            break;
        case "copywriter":
            $new_tick_copywriter[] = $ticket;
            break;
        case "manager":
            $new_tick_manager[] = $ticket;
            break;

        default:
            break;
    }
}
$content = str_replace('[new_tick]', count($new_tick), $content);
$content = str_replace('[new_tick_user]', count($new_tick_user), $content);
$content = str_replace('[new_tick_moder]', count($new_tick_moder), $content);
$content = str_replace('[new_tick_copywriter]', count($new_tick_copywriter), $content);
$content = str_replace('[new_tick_manager]', count($new_tick_manager), $content);


$old_tickets = $db->Execute("SELECT id FROM tickets WHERE status = 0")->GetAll();
$content = str_replace('[old_tickets]', count($old_tickets), $content);


$all_wallet = $db->Execute("SELECT * FROM change_wallet")->GetAll();
foreach ($all_wallet as $value) {
    if($value['status'] == 0 && $value['confirm'] == '1') {
        $new_wallet[] = $value;
    }
}
$content = str_replace('[new_wallet]', count($new_wallet), $content);
$content = str_replace('[all_wallet]', count($all_wallet), $content);

$main_comment = $db->Execute("SELECT * FROM Message2002 WHERE Sub_Class_ID = 22");
$mc = $main_comment->FetchRow();

$content = str_replace('[main_comment]', $mc['Text'], $content);


echo $content;
?>