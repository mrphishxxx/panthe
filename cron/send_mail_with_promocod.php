<?php

error_reporting(E_ALL ^ E_NOTICE);
include(dirname(__FILE__).'/../'.'config.php');
include dirname(__FILE__).'/../'.'includes/adodb5/adodb.inc.php';
require_once dirname(__FILE__).'/../'.'includes/mandrill/mandrill.php';

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');


$three_days = (86400 * 3) - 43200;
$interval = time() - $three_days;
$users = $db->Execute("SELECT a.* FROM admins a 
                           LEFT JOIN orders o ON o.uid = a.id
                           LEFT JOIN birjs b ON b.uid = a.id
                           LEFT JOIN sayty s ON s.uid = a.id
                           WHERE (a.reg_date < $interval) AND (o.status = 0 AND o.price=300) AND (b.bid IS NULL) AND (s.id IS NULL) AND (a.mail_promo = 0)
                           GROUP BY a.id
                           ORDER BY a.id");

$code = $db->Execute("SELECT Code FROM Message2009 ORDER BY Message_ID DESC LIMIT 1");
$code = $code->FetchRow();
$code = $code["Code"];

try {
    $mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
} catch (Exception $e) {
    echo 'Error open Mandrill!';
}
while ($row = $users->FetchRow()) {

    $body = '
        <html>
            <head>
                <meta charset="utf-8">
                <title>Используйте промокод в письме. IForget </title>
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
                                <div style="font-size: 16px; line-height: 20px; font-weight: bold; padding: 20px 0 0 0; text-shadow: 1px 1px 0 #fff;">Здравствуйте, ' . $row["login"] . '!</div>
                                <div style="font-size: 16px; line-height: normal; padding: 15px 0 0 0; text-shadow: 1px 1px 0 #fff;">Первые заявки за наш счет! Мы дарим 150 рублей на ваш баланс!<br/>
                                        <br/>
                                        Для этого вам необходимо добавить 1 биржу и 1 сайт в вашем аккаунте и пополнить счет.</div>
                                <div style="background: #3d413d; color: #fff; font-size: 15px; line-height: normal; padding: 8px 100px 10px 100px; margin: 20px 0 0 0;">Используйте этот код, чтобы пополнить баланс:</div>
                                <div style="background: #fff; border: 2px solid #616461; border-top: 0; height: 75px;">
                                        <div style="float: left; font-size: 16px; line-height: 20px; font-weight: bold; padding: 25px 0 0 145px;">Код купона:</div>
                                        <div style="float: left; font-size: 15px; padding: 0 25px 0 25px; margin: 20px 0 0 20px; border: 3px solid #ffcc3d; height: 29px; line-height: 29px;">' . $code . '</div>
                                </div>
                                <div style="font-size: 13px; line-height: 17px; text-align: center; text-shadow: 1px 1px 0 #fff; padding: 10px 0 0 0;">
                                Напоминаем, что комиссия за заявку составляет <span style="font-size: 16px;">всего 13 рублей</span></div>
                                <div style="text-align: center; height: 38px; padding: 15px 0 0 0;">
                                        <a style="float: left; font-size: 15px; color: #3d413d; text-decoration: none; height: 35px; line-height: 35px; padding: 0 15px 0 15px; background: #ffcc3d; margin: 0 0 0 215px; border-bottom: 3px solid #e3b022;" href="http://iforget.ru/user.php?action=payments&promo=' . $code . '">Использовать код</a>
                                </div>
                        </div>
                        <div style="height: 2px; font-size: 0; background: #e5e5e5; border-top: 1px solid #d1d1d1; border-bottom: 1px solid #dadada; margin: 20px 0 0 0;"></div>
                        <div style="text-align: center; font-size: 18px; line-height: 22px; margin: 15px 0 0 0; text-shadow: 1px 1px 0 #fff;">Отзывы наших клиентов:</div>
                        <div style="background: #faf9f9; height: 130px; margin: 20px 0 0 97px;">
                                <img style="display:block;margin-right: 20px;margin-left: -56px;float: left;" src="cid:photo" alt=".">
                                <div style="font-size: 14px; line-height: normal; padding: 35px 80px 0 0px;">Работаю с сервисом уже больше месяца. Всем доволен, поддержка отвечает быстро. Заявки делают и самое главное дешевле, чем в Быстропосте.</div>
                                <div style="font-weight: bold; font-style: italic; font-size: 14px; line-height: 18px; text-align: right; padding: 0 110px 0 0;">Иван, Москва</div>
                        </div>
                        <div style="font-size: 14px; line-height: 18px; padding: 30px 0 0 0;">
                                <a style="color: #3d413d; margin-left: 60px;" href="http://iforget.ru/about/">О системе</a>
                                <a style="color: #3d413d; margin-left: 15px;" href="http://iforget.ru/price/">Цены</a>
                                <a style="color: #3d413d; margin-left: 15px;" href="http://iforget.ru/convention/">Соглашение</a>
                                <a style="color: #3d413d; margin-left: 15px;" href="http://iforget.ru/register.php">Регистрация</a>
                                <a style="color: #3d413d; margin-left: 15px;" href="http://iforget.ru/contacts/">Контакты</a>
                        </div>
                        <div style="font-size: 13px; line-height: normal; text-shadow: 1px 1px 0 #fff; padding: 20px 60px 0 60px;"> Оставить и почитать отзывы Вы сможете в нашей ветке на <a style="color: #3d413d;" href="http://searchengines.guru/showthread.php?p=12378271">серчах</a></div>
                        <div style="font-size: 13px; line-height: normal; text-shadow: 1px 1px 0 #fff; padding: 20px 60px 0 60px;">Вы получили это письма так как зарегистрировались в системе iforget.ru<br> Для того чтобы отписаться пройдите по <a style="color: #3d413d;" href="http://iforget.ru/user.php?action=lk">ссылке</a></div>
                        <div style="font-size: 13px; background: #fff; border-top: 1px solid #d7d7d7; height: 50px; line-height: 50px; text-align: center; margin: 20px 0 0 0;">© 2013 iForget — система автоматической монетизации</div>
                    </div>
                </div>
            </body>
        </html>    
        ';
    $subject = "IForget дарит Вам 150 руб.";

    $message = array();
    $message["html"] = $body;
    $message["text"] = NULL;
    $message["subject"] = $subject;
    $message["from_email"] = "news@iforget.ru";
    $message["from_name"] = "iforget";
    $message["to"] = array();
    //$message["to"][0] = array("email" => "abashevav@gmail.com");
    $message["to"][0] = array("email" => $row['email']);
    $message["track_opens"] = null;
    $message["track_clicks"] = null;
    $message["auto_text"] = null;
    $f1 = fopen("../images/header_bg.jpg", "rb");
    $f2 = fopen("../images/logo_main.jpg", "rb");
    $f3 = fopen("../images/photo.png", "rb");
    $message["images"][] = array("type" => "image/jpg", "name" => "header_bg", "content" => base64_encode(fread($f1, filesize("../images/header_bg.jpg"))));
    $message["images"][] = array("type" => "image/jpg", "name" => "logo_main", "content" => base64_encode(fread($f2, filesize("../images/logo_main.jpg"))));
    $message["images"][] = array("type" => "image/jpg", "name" => "photo", "content" => base64_encode(fread($f3, filesize("../images/photo.png"))));

    try {
        $mandrill->messages->send($message);
        $db->Execute("UPDATE admins SET mail_promo=1 WHERE id=" . $row["id"]);
    } catch (Exception $e) {
        echo '';
    }
}
exit();
?>
