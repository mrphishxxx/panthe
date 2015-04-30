<?php

@session_start();
error_reporting(E_ALL ^ E_NOTICE);
include('config.php');
include 'includes/adodb5/adodb.inc.php';
$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
//$db->debug = true;
//загружаем шаблон
$content = file_get_contents(PATH . 'admin_tmp/confirm.tpl');
include 'system/class_index.php';

$text = '
<!-- CANVAS ( WHITE SHEET ) --> 
<div class="canvas">
		<h1>Благодарим за регистрацию на сайте iForget!</h1>
		<p>Теперь Вы можете <a href="/user.php">перейти в личный кабинет</a> и пройти 3 шага для начала работы с нашим сервисом.</p>
		<p>Если у вас будут вопросы, контакты для связи в правом верхнем углу.</p>
                <p><small><a href="/user.php?action=unsubscribe">Отписаться от рассылки</a></small></p>
</div>
';

$ok = '<!-- CANVAS ( WHITE SHEET ) --> 
<div class="canvas">
		<h1>E-mail подтверждение</h1>
		<p>Благодарим за регистрацию на сайте iForget!</p>
		<p>Ваша учетная запись успешно активирована!</p>
		<p><a href="/user.php?action=birj">Перейти в личный кабинет.</a></p>
</div>
';

$not_ok = '<!-- CANVAS ( WHITE SHEET ) --> 
<div class="canvas">
		<h1>E-mail подтверждение</h1>
		<p>Неверный код подтверждения!</p>
</div>
';

if (@$_GET['uid'] && $_GET['code']) {
    $content = str_replace('[text]', "", $content);
    $uid = $db->escape($_GET['uid']);
    $code = $db->escape($_GET['code']);

    $q = "SELECT * FROM admins WHERE confirmation='$code' AND id=$uid";
    $allok = $db->Execute($q);
    $allok = $allok->FetchRow();
    if ($allok) {
        $q = "UPDATE admins SET active=1 WHERE id=$uid";
        $db->Execute($q);
        if ($allok['type'] == "copywriter") {
            $ok = str_replace('user.php?action=birj', "copywriter.php", $ok);
        }
        $_SESSION['user'] = $allok;
        @setcookie("iforget_ok", $allok['id'], time() + 60 * 60 * 24 * 30);
        $content = str_replace('[status]', $ok, $content);
    } else
        $content = str_replace('[status]', $not_ok, $content);
}
else {
    $content = str_replace('[status]', "", $content);
    $content = str_replace('[text]', $text, $content);
}



if (@$_SESSION['user']['id'] > 0) {
    $auth_block = '
		<!-- userbar -->
		<div id="userbar">
			
			<a id="user_login" href="#">' . $_SESSION['user']['login'] . '</a>
			
			<span class="sep"></span>
			
			<a id="log_off" href="/user.php?action=logout"><span class="ico"></span> <span>Выход</span> </a>
			
		</div>
	';
} else {
    $auth_block = '
		<!-- login -->
		<div id="login">
                    <script src="//ulogin.ru/js/ulogin.js"></script>
                    <div style="float:right;" id="uLogin1" data-ulogin="display=small;fields=first_name,last_name,email,nickname;providers=vkontakte,odnoklassniki,mailru,facebook,twitter,googleplus;hidden=;redirect_uri=http%3A%2F%2Fiforget.ru%2Fuser.php"></div>
                    <div style="float:right;margin:0 10px 10px 0">Войти через</div><div class="clear"></div>
			<form action="/user.php" method="POST">
			
				<input type="text" name="login" value="" placeholder="Логин" />
				<input type="text" name="pass" value="" placeholder="Пароль" />
				<input type="submit" value="Вход" />
				
				<div class="registration-recovery">
					<a href="/register.php" id="registration">Регистрация</a>
				</div>
			
			</form>
		</div>

				<!-- notifications -->

	';
}
$content = str_replace('[auth_block]', $auth_block, $content);


echo $content;
?>