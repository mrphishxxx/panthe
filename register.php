<?php

session_start();
error_reporting(E_ALL ^ E_NOTICE);
include 'config.php';
include 'configs/setup-smarty.php';
include 'includes/postman/Postman.php';
include 'includes/adodb5/adodb.inc.php';

$smarty = new Smarty_Project();

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
//$db->debug = true;
//загружаем шаблон
$content1 = file_get_contents(PATH . 'admin_tmp/register.tpl');
include 'system/class_index.php';
include 'modules/register/register_class.php';
include 'modules/user/user_class.php';
$ruser = new register_class($db, $smarty);
//$user = new user();
$content = '';
if (@$_POST['wmid'] == 1) {
    $rezult = $ruser->validate($_POST, $db);
    if (isset($rezult['error']) && !empty($rezult['error'])) {
        $content = $ruser->user_error($rezult, $rezult['error']);
    } else {
        $content = $ruser->new_user($db, $rezult);
        //$user->login($db);
        $login = $_REQUEST['login'];
        if (isset($_REQUEST['password']) && !empty($_REQUEST['password']))
            $pass = md5($_REQUEST['password']);
        else
            $pass = md5($_REQUEST['pass']);
        $res = $db->Execute("SELECT * FROM admins WHERE (email='$login' OR login='$login') AND pass='$pass'")->FetchRow();
        $_SESSION['user'] = $res;
        setcookie("iforget_ok", $res['id'], time() + 60 * 60 * 24 * 30);
        header("Location:/confirm.php");
        exit();
    }
} elseif (isset($_POST['token'])) {
    $s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
    $user = json_decode($s, true);
    $error = $ruser->new_user_from_socnet($db, $user);
    if ($error) {
        $content = $ruser->user_error(array(), array($error));
        $content = str_replace('[login]', "", $content);
        $content = str_replace('[email]', "", $content);
        $content = str_replace('[promo]', "", $content);
        $content = str_replace('[wallet]', "", $content);
    } else {
        header("Location:/confirm.php");
        exit();
    }
} else {
    $content = file_get_contents(PATH . 'modules/register/tmp/register.tpl');

    if (isset($_REQUEST["type"])) {
        if ($_REQUEST["type"] == 0) {
            $content = str_replace('[type0]', "selected", $content);
            $content = str_replace('[type1]', "", $content);
            $content = str_replace('[display]', "style='display: none'", $content);
            $content = str_replace('[socseti]', "style='display: block'", $content);
        } else {
            $content = str_replace('[type0]', "", $content);
            $content = str_replace('[type1]', "selected", $content);
            $content = str_replace('[display]', "style='display: block'", $content);
            $content = str_replace('[socseti]', "style='display: none'", $content);
        }
    } else {
        $content = str_replace('[type0]', "", $content);
        $content = str_replace('[type1]', "", $content);
        $content = str_replace('[display]', "style='display: none'", $content);
        $content = str_replace('[socseti]', "style='display: block'", $content);
    }
    $content = str_replace('[login]', "", $content);
    $content = str_replace('[email]', "", $content);
    $content = str_replace('[wallet]', "", $content);
    
    $content = str_replace('[promo]', (@$_GET['promo'] ? $_GET['promo'] : ""), $content);
    $content = str_replace('[partner_link]', (@$_GET['partner'] ? $_GET['partner'] : ""), $content);
    $content = str_replace('[error]', "", $content);
}

$content = str_replace('[content]', $content, $content1);

if (@$_SESSION['user']['id'] > 0) {
    $auth_block = '
		<!-- userbar -->
		<div id="userbar">
			
			<a id="user_login" href="#">' . $_SESSION['user']['email'] . '</a>
			
			<span class="sep"></span>
			
			<a id="log_off" href="/user.php?action=logout"><span class="ico"></span> <span>Выход</span> </a>
			
		</div>
	';
} else {

    $auth_block = '
		<!-- login -->
		<div id="login">
                    <p id="fast_connection">
                        Быстрая связь: 
                        <img src="/images/openid/skype16x16.png" /> <a href="skype:roman.vetes?chat">Roman.vetes</a>
                        <img src="/images/openid/icq16x16.gif" /> 133-215 
                    </p>
                    <form action="/user.php" method="POST">
                        <input type="text" name="login" value="" placeholder="Логин" />
                        <div class="textfield">
                            <input type="password" name="pass" class="" value="" placeholder="Пароль" />
                        </div>
                        <input type="submit" value="Вход" />	
                        <div class="registration-recovery">
                            <a href="/forgot/" id="registration">Забыли пароль?</a>
			</div>
                    </form>
		</div>
	';
}
$content = str_replace('[auth_block]', $auth_block, $content);


$main_comment = $db->Execute("SELECT * FROM Message2002 WHERE Sub_Class_ID = 23");
$mc = $main_comment->FetchRow();

$content = str_replace('[register_comment]', $mc['Text'], $content);


echo $content;
?>
