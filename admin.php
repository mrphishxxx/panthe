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


if ((@$_SESSION['user']['id'] > 0) || (@$_SESSION['admin']['id'] > 0)) {
    $auth_block = '
		<!-- userbar -->
		<div id="userbar">
			
			<a id="user_login" href="#">' . (isset($_SESSION['user']['login']) ? $_SESSION['user']['login'] : $_SESSION['admin']['login']) . '</a>
			
			<span class="sep"></span>
			
			<a id="log_off" href="/' . (isset($_SESSION['user']['email']) ? 'user.php?action=logout' : 'admin.php?module=admins&action=logout') . '"><span class="ico"></span> <span>Выход</span> </a>
			
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

$admins_managers = array();
$admins_manager = $db->Execute("SELECT id FROM admins WHERE type = 'admin' OR type = 'manager'");
while ($user = $admins_manager->FetchRow()) {
    $admins_managers[] = $user['id'];
}
$admins_managers = "(" . implode(",", $admins_managers) . ")";
/* Тикеты ВСЕГО */
$new_tick = $db->Execute("SELECT COUNT(t.id) as newt FROM tickets t LEFT JOIN admins a ON IF (t.uid = 1, a.id=t.to_uid, a.id=t.uid) WHERE a.id != 0 AND t.status != 0")->FetchRow();
$all_tick = $db->Execute("SELECT COUNT(t.id) as allt FROM tickets t LEFT JOIN admins a ON IF (t.uid = 1, a.id=t.to_uid, a.id=t.uid) WHERE a.id != 0")->FetchRow();
$content = str_replace('[new_tick]', $new_tick['newt'], $content);
$content = str_replace('[all_tick]', $all_tick['allt'], $content);

/* Тикеты ПОЛЬЗОВАТЕЛИ */
$new_tick_user = $db->Execute("SELECT COUNT(t.id) as newt FROM tickets t LEFT JOIN admins a ON IF (t.uid = 1, a.id=t.to_uid, a.id=t.uid) WHERE t.status != 0 AND a.type = 'user'")->FetchRow();
$all_tick_user = $db->Execute("SELECT COUNT(t.id) as newt FROM tickets t LEFT JOIN admins a ON IF (t.uid = 1, a.id=t.to_uid, a.id=t.uid) WHERE a.type = 'user'")->FetchRow();
$content = str_replace('[new_tick_user]', $new_tick_user['newt'], $content);
$content = str_replace('[all_tick_user]', $all_tick_user['newt'], $content);

/* Тикеты МОДЕРАТОРЫ */
$new_tick_moder = $db->Execute("SELECT COUNT(t.id) as newt FROM tickets t LEFT JOIN admins a ON IF (t.uid = 1, a.id=t.to_uid, a.id=t.uid) WHERE t.status != 0 AND a.type = 'moder'")->FetchRow();
$all_tick_moder = $db->Execute("SELECT COUNT(t.id) as newt FROM tickets t LEFT JOIN admins a ON IF (t.uid = 1, a.id=t.to_uid, a.id=t.uid) WHERE a.type = 'moder'")->FetchRow();
$content = str_replace('[new_tick_moder]', $new_tick_moder['newt'], $content);
$content = str_replace('[all_tick_moder]', $all_tick_moder['newt'], $content);

/* Тикеты КОПИРАЙТЕРЫ */
$new_tick_copywriter = $db->Execute("SELECT COUNT(t.id) as newt FROM tickets t LEFT JOIN admins a ON IF (t.uid = 1, a.id=t.to_uid, a.id=t.uid) WHERE t.status != 0 AND a.type = 'copywriter'")->FetchRow();
$all_tick_copywriter = $db->Execute("SELECT COUNT(t.id) as newt FROM tickets t LEFT JOIN admins a ON IF (t.uid = 1, a.id=t.to_uid, a.id=t.uid) WHERE a.type = 'copywriter'")->FetchRow();
$content = str_replace('[new_tick_copywriter]', $new_tick_copywriter['newt'], $content);
$content = str_replace('[all_tick_copywriter]', $all_tick_copywriter['newt'], $content);

/* Тикеты МЕНЕДЖЕРЫ */
$new_tick_manager = $db->Execute("SELECT COUNT(t.id) as newt FROM tickets t LEFT JOIN admins a ON (t.uid IN $admins_managers AND t.to_uid IN $admins_managers) WHERE t.status != 0 AND a.type = 'manager'")->FetchRow();
$all_tick_manager = $db->Execute("SELECT COUNT(t.id) as newt FROM tickets t LEFT JOIN admins a ON (t.uid IN $admins_managers AND t.to_uid IN $admins_managers) WHERE a.type = 'manager'")->FetchRow();
$content = str_replace('[new_tick_manager]', $new_tick_manager['newt'], $content);
$content = str_replace('[all_tick_manager]', $all_tick_manager['newt'], $content);


$main_comment = $db->Execute("SELECT * FROM Message2002 WHERE Sub_Class_ID = 22");
$mc = $main_comment->FetchRow();

$content = str_replace('[main_comment]', $mc['Text'], $content);


echo $content;
?>