<?php

session_start();
error_reporting(E_ALL);
include 'config.php';
include 'includes/adodb5/adodb.inc.php';
include 'includes/mandrill/mandrill.php';

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$user_sends = array();
$users = $db->Execute("SELECT * FROM admins WHERE type='user' AND active=1");
while ($user = $users->FetchRow()) {
    $sites = $db->Execute("SELECT * FROM sayty WHERE uid='".$user["id"]."'");
    $count_sites = $sites->NumRows();
    if($count_sites != 0){
        $birjs = $db->Execute("SELECT * FROM birjs WHERE uid='".$user["id"]."'");
        $count_birjs = $birjs->NumRows();
        
        $orders = $db->Execute("SELECT * FROM orders WHERE uid='".$user["id"]."' AND status=1");
        $count_orders = $orders->NumRows();
        
        if($count_birjs == 0 || $count_orders == 0){
            $user_sends[] = $user;
        }
    }
}

$text = 'Добрый день!

            Мы заметили, что Вы добавили сайт к нам в сервис, но так не добавили биржу, где находится сайт и не пополнили баланс.
            Хотел бы поинтересоваться, нет ли каких-то проблем с интерфейсом?
            Возможно, Вам нужна помощь - знайте, мы всегда поможем.

            В верхнем правом углу - находятся мои данные Skype и ICQ - Вы можете без проблем писать.

            PS - мы готовы дарить промокоды для тестирования нашего сервиса.

            С уважением, Роман';


foreach ($user_sends as $user){
    $subject = "Почему не хотите работать с нами?";
    $theme = "Общими вопросами";
    $msg = substr(nl2br(htmlspecialchars(addslashes(trim($text)))), 0, 1000);
    $cdate = date("Y-m-d");

    $db->Execute("INSERT INTO tickets (uid, subject, q_theme, msg, date, status, to_uid) VALUES (1, '$subject', '$theme', '$msg', '$cdate', 1, ".$user['id'].")");
    $lastId = $db->Insert_ID();

    $body = "Добрый день!<br/><br/>
             Вам поступил новый тикет. Для просмотра <a href='http://iforget.ru/user.php?action=ticket&action2=view&tid=" . $lastId . "'>перейдите данной ссылке</a>.<br /><br />
             Оставить и почитать отзывы Вы сможете в нашей ветке на <a href='http://searchengines.guru/showthread.php?p=12378271'>серчах</a><br/><br/>
             С уважением,<br/>
             Администрация проекта iForget.";

    $mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
    $message = array();
    $message["html"] = $body;
    $message["text"] = "";
    $message["subject"] = "[Новый тикет в системе iforget]";
    $message["from_email"] = "news@iforget.ru";
    $message["from_name"] = "iforget";
    $message["to"] = array();
    $message["to"][0] = array("email" => $user['email']);
    $message["track_opens"] = null;
    $message["track_clicks"] = null;
    $message["auto_text"] = null;

    try {
        $mandrill->messages->send($message);
        echo 'Тикет для пользователя '.$user['login'].' успешно добавлен. Оповещение отправлено.<br>';
    } catch (Exception $e) {
        echo $e;
        echo 'Письмо не отправлено! Возникли проблемы! НО Тикет успешно добавлен для пользователя '.$user['login'].'.<br>';
    }
}

exit();

?>
