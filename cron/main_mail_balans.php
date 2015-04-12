<?php

//session_start();
error_reporting(E_ALL ^ E_NOTICE);
include(dirname(__FILE__) . '/../' . 'config.php');
include dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
require_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');


$users = $db->Execute("SELECT * FROM admins WHERE active=1 AND type='user' ORDER BY id");
$uids = array();
$no_send_ids = array();
$orders = array();

$orders_true = $db->Execute("SELECT uid FROM orders WHERE status=1 GROUP BY uid");
while ($order = $orders_true->FetchRow()) {
    $orders[] = $order["uid"];
}

while ($row = $users->FetchRow()) {
    /* Mail Period */
    $mail_period = $row['mail_period']; // период в секундах
    if ($mail_period == 0) {//Пользователь отписался от рассылки
        $no_send_ids[] = $row['id'];
    } else {
        $last_send = $db->Execute("SELECT * FROM mailstat WHERE user_id=" . $row['id'])->FetchRow();
        if ($last_send) {
            $last_time = strtotime($last_send['last_time_send']);
            $now_time = time();
            $diff_time = $now_time - $last_time; //время в секундах прошедшее после последней отправки письма пользователю

            if ($diff_time < $mail_period) {
                $no_send_ids[] = $row['id'];
            }
        }
    }
    /* ########### */
    $uids[$row['email']] = $row['id'];
}

$body_admin = "Добрый день!<br/><br/>";
//Формируем тело письма в зависимости от причин по которым надо слать что-то пользователю

foreach ($uids as $email => $uid) {
    if(in_array($uid, $orders)){
        $balans['total'] = getUserBalansCron($uid, $db, 1);
        //#######################################################################################
        $body_admin .= "Баланс пользователя <a href='http://iforget.ru/admin.php?module=admins&action=edit&id=$uid'>$email</a> в системе iForget составляет " . $balans['total'] . " рублей.<br/><br/><br/>";


        $body = '
                <html>
                    <head>
                        <meta charset="utf-8">
                        <title>Баланс на сайте iForget!</title>
                    </head>
                    <body style="margin: 0">
                        <div style="text-align: center; font-family: tahoma, arial; color: #3d413d;">
                            <div style="width: 650px; margin: 0 auto; text-align: left; background: #f1f0f0;">
                                <div style="width: 650px; height: 89px; background: url(cid:header_bg); text-align: center;">
                                    <div style="width: 575px; margin: 0 auto; text-align: left;">
                                        <a style="float: left; margin: 15px 0 0 0;" target="_blank" href="">
                                            <img style="border: 0;" src="cid:logo_main" alt=".">
                                        </a>
                                        <div style="color: #fff; font-size: 22px; float: left; margin: 12px 0 0 0; line-height: 60px;">- работает сутки напролёт</div>
                                        <a style="float: right; font-size: 16px; color: #3d413d; margin: 30px 0 0 0;" href="http://iforget.ru/user.php?action=lk">Войти</a>
                                    </div>
                                </div>
                                <div style="padding: 0 38px 0 38px;">
                                    <div style="font-size: 16px; line-height: 20px; font-weight: bold; padding: 20px 0 0 0; text-shadow: 1px 1px 0 #fff;">Здравствуйте!</div>
                                    <div style="font-size: 16px; line-height: normal; padding: 15px 0 0 0; text-shadow: 1px 1px 0 #fff;">
                                        Ваш баланс в системе iForget составляет ' . $balans["total"] . ' рублей.<br>
                                        Настроить уведомления iForget можно на <a href="http://iforget.ru/user.php?action=lk">странице профиля </a>
                                    </div>
                                    <div style="text-align: center; height: 38px; padding: 15px 0 0 0;">
                                        <a style="float: left; font-size: 15px; color: #3d413d; text-decoration: none; height: 35px; line-height: 35px; padding: 0 15px 0 15px; background: #ffcc3d; margin: 0 0 0 215px; border-bottom: 3px solid #e3b022;" href="http://iforget.ru/user.php?action=payments">Пополнить баланс</a>
                                    </div>
                                </div>
                                <div style="height: 2px; font-size: 0; background: #e5e5e5; border-top: 1px solid #d1d1d1; border-bottom: 1px solid #dadada; margin: 20px 0 0 0;"></div>

                                <div style="font-size: 14px; line-height: 18px; padding: 30px 0 0 0;">
                                        <a style="color: #3d413d; margin-left: 60px;" href="http://iforget.ru/about/">О системе</a>
                                        <a style="color: #3d413d; margin-left: 15px;" href="http://iforget.ru/price/">Цены</a>
                                        <a style="color: #3d413d; margin-left: 15px;" href="http://iforget.ru/convention/">Соглашение</a>
                                        <a style="color: #3d413d; margin-left: 15px;" href="http://iforget.ru/register.php">Регистрация</a>
                                        <a style="color: #3d413d; margin-left: 15px;" href="http://iforget.ru/contacts/">Контакты</a>
                                </div>
                                <div style="font-size: 13px; line-height: normal; text-shadow: 1px 1px 0 #fff; padding: 20px 60px 0 60px;"> Оставить и почитать отзывы Вы сможете в нашей ветке на <a style="color: #3d413d;" href="http://searchengines.guru/showthread.php?p=12378271">серчах</a></div>
                                <div style="font-size: 13px; line-height: normal; text-shadow: 1px 1px 0 #fff; padding: 5px 60px 0 60px;">Вы получили это письмо, так как зарегистрировались в системе iforget.ru<br> Для того чтобы отписаться пройдите по <a style="color: #3d413d;" href="http://iforget.ru/user.php?action=lk">ссылке</a></div>
                                <div style="font-size: 13px; background: #fff; border-top: 1px solid #d7d7d7; height: 50px; line-height: 50px; text-align: center; margin: 20px 0 0 0;">© 2013 iForget — система автоматической монетизации</div>
                            </div>
                       </div>
                    </body>
                </html>
            ';



        //Шлем письмo пользователю (если его нет в стоп листе по периоду временному) и админу (всегда)
        $subject = "Ваш баланс в системе iForget составляет ". $balans["total"] . " рублей!";

        if (!in_array($uid, $no_send_ids)) {

            $mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');

            $message = array();
            $message["html"] = $body;
            $message["text"] = "";
            $message["subject"] = $subject;
            $message["from_email"] = "news@iforget.ru";
            $message["from_name"] = "iforget";
            $message["to"] = array();
            $message["to"][0] = array("email" => $email);
            $message["track_opens"] = null;
            $message["track_clicks"] = null;
            $message["auto_text"] = null;
            $message["images"] = array();
            $f1 = fopen("../images/header_bg.jpg", "rb");
            $f2 = fopen("../images/logo_main.jpg", "rb");
            $message["images"][] = array("type" => "image/jpg", "name" => "header_bg", "content" => base64_encode(fread($f1, filesize("../images/header_bg.jpg"))));
            $message["images"][] = array("type" => "image/jpg", "name" => "logo_main", "content" => base64_encode(fread($f2, filesize("../images/logo_main.jpg"))));


            try {
                $mandrill->messages->send($message);
                $send_time = date("Y-m-d H:i:s");
                $mse = $db->Execute("SELECT * FROM mailstat WHERE user_id=$uid")->FetchRow();
                if (!$mse)
                    $db->query("INSERT INTO mailstat (user_id, last_time_send) VALUES ('$uid', '$send_time')");
                else
                    $db->query("UPDATE mailstat SET last_time_send='$send_time' WHERE user_id=$uid");
            } catch (Exception $e) {
                echo '';
            }
        }
    }
}
$body_admin .= "<br/><br/>С Уважением, Администрация сервиса iforget.ru";
$subject_admin = "Баланс активных пользователей на сайте iForget!";

$mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
$message = array();
$message["html"] = $body_admin;
$message["text"] = "";
$message["subject"] = $subject_admin;
$message["from_email"] = "news@iforget.ru";
$message["from_name"] = "iforget";
$message["to"] = array();
$message["to"][0] = array("email" => "admin@iforget.ru");
//$message["to"][1] = array("email" => "abashevav@gmail.com");
$message["track_opens"] = null;
$message["track_clicks"] = null;
$message["auto_text"] = null;

try {
    $mandrill->messages->send($message);
} catch (Exception $e) {
    echo '';
}

exit();

function getUserBalansCron($uid, $db, $nocur = 0) {
    $balans = $db->Execute("SELECT SUM(price) as total FROM orders WHERE uid=$uid AND status=1")->FetchRow();
    if (!is_null($balans['total'])) {
        if (!$balans['total'])
            $balans['total'] = 0;
    }
    else {
        $balans['total'] = 0;
    }

    //Подсчитываем количество выполненных задач
    $compl_tasks = $db->Execute("SELECT * FROM completed_tasks WHERE uid=$uid");
    $credit = 0;
    while ($row = $compl_tasks->FetchRow()) {
        $credit += $row['price'];
    }
    $balans['total'] -= $credit;
    if (!$nocur)
        $balans['total'] .= "р.";

    return $balans['total'];
}

?>
