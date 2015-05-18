<?php

class register_class {

    function validate($post, $db) {
        $rez = array();
        foreach ($post as $key => $val) {
            $input_text = trim($val);
            $input_text = htmlspecialchars($input_text);
            $input_text = addslashes($input_text);
            if ($key == "type") {
                if($val == 1){
                    $input_text = "copywriter";
                    if(!empty($post["wallet"])) {
                        $wallet = $db->Execute("SELECT * FROM admins WHERE wallet='" . $post['wallet'] . "'")->FetchRow();
                        if(!empty($wallet)){
                            $rez['error'][] = "Копирайтер с таким кошельком уже существует в системе!";
                        }
                    } else {
                        $rez['error'][] = "Webmoney кошелек - обязателен для заполнения!";
                    }
                } else {
                    $input_text = "user";
                }
                
            }
            $rez[$key] = $input_text;
        }

        if ($rez["confpass"] != $rez["password"])
            $rez['error'][] = "Пароли не совпадают!";

        $login = $db->Execute("SELECT * FROM admins WHERE login='" . $post['login'] . "'")->FetchRow();
        if ($login)
            $rez['error'][] = "Пользователь с таким логином уже существует в системе";

        $email = $db->Execute("SELECT * FROM admins WHERE email='" . $post['email'] . "'")->FetchRow();
        if ($email)
            $rez['error'][] = "Пользователь с такой электронной почтой уже существует в системе";

        return $rez;
    }

    function new_user($db, $data) {
        $login = $data['login'];
        $email = $data['email'];
        $type = $data['type'];
        $wallet = $data['wallet'];
        $active = 0;
        if (empty($data['password']))
            $data['password'] = uniqid();

        $pass = md5($data['password']);
        $date = time();
        $new_user = 1; //если зарегестрировался позже 1 ноября, то пользователь считается новым и цены для него выше
        $contacts = NULL;
        $dostupy = $data['knowus'];

        $res = $db->Execute("select * from admins where login='$login' OR email='$email'")->FetchRow();
        if ($res['id']) {
            $alert = 'Пользователь с такими данными уже имеется в базе';
            $url = '/register.php';
        } else {
            if ($type == "copywriter") {
                $active = 1;
            }
            $confirmation = md5($email . time());
            $db->Execute("SET NAMES 'UTF8'");
            $q = "insert into admins(login, email, pass, type, active, reg_date, contacts, dostupy, confirmation, wallet, new_user) values ('$login', '$email', '$pass', '$type', '$active', $date, '$contacts', '$dostupy', '$confirmation', '$wallet', '$new_user')";
            $db->Execute($q);

            $user = $db->Execute("select * from admins where login='$login'")->FetchRow();
            if (!empty($user)) {
                $alert = 'Пользователь успешно добавлен.';
                $url = '/user.php';
            }

            $cur_dt = date("Y-m-d H:i:s");
            $cur_id = $user['id'];
            if ($type == "user")
                $db->Execute("INSERT INTO orders (uid, price, date, status) VALUES ($cur_id, 1000, '$cur_dt', 0)");
            else
                $active = 1;

            if ($data['promo'] && $type == "user") {
                $Message2009 = $db->Execute("SELECT * FROM Message2009 WHERE Code='" . $db->escape($data['promo']) . "' AND ((Used=0) OR (Used IS NULL))")->FetchRow();
                if ($Message2009['Message_ID']) {
                    $user_used = $db->Execute("SELECT * FROM promo_user WHERE promo_id=" . $Message2009['Message_ID'] . " AND user_id=$cur_id")->FetchRow();

                    if (!$user_used) {
                        $db->Execute("INSERT INTO orders (uid, price, date, status) VALUES ($cur_id, 150, '$cur_dt', 1)");
                        $db->Execute("INSERT INTO promo_user (promo_id, user_id) VALUES (" . $Message2009['Message_ID'] . ", $cur_id)");

                        require_once 'includes/mandrill/mandrill.php';
                        $mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
                        $message = array();
                        $message["html"] = "Добрый день! <br/><br/>
                            Вебмастер <a href='http://iforget.ru/admin.php?module=admins&action=edit&id=" . $user["id"] . "'>" . $user["login"] . "</a>
                                воспользовался промокодом <strong>" . $data['promo'] . "</strong>. На его балан зачислено 150 рублей.<br/>";
                        $message["text"] = "";
                        $message["subject"] = "[Вебмастер воспользовался промокодом]";
                        $message["from_email"] = "news@iforget.ru";
                        $message["from_name"] = "iforget";
                        $message["to"] = array();
                        $message["to"][0] = array("email" => MAIL_ADMIN);
                        //$message["to"][1] = array("email" => MAIL_DEVELOPER);
                        $message["track_opens"] = null;
                        $message["track_clicks"] = null;
                        $message["auto_text"] = null;

                        try {
                            $mandrill->messages->send($message);
                        } catch (Exception $e) {
                            echo 'Сообщение не отправлено!';
                        }
                    }
                }
            }

            if ($data['partner_link']) {
                $plink = $db->escape($data['partner_link']);
                $tmpArr = explode('_', $plink);
                $hash = md5($tmpArr[1] . "iforget");
                if ($tmpArr[0] == $hash) {
                    $db->Execute("INSERT INTO partnership (partner_id, new_user_id) VALUES (" . $tmpArr[1] . ", $cur_id)");
                }
            }

            /* сообщение */
            $body = '
                                <html>
                                    <head>
                                        <meta charset="utf-8">
                                        <title>Поздравляем Вас с успешной регистрацией на сайте iforget.ru</title>
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
			                                <a style="float: right; font-size: 16px; color: #3d413d; margin: 30px 0 0 0;" href="http://iforget.ru/?uid=' . $cur_id . '">Войти</a>
                                                    </div>
		                                </div>
		                                <div style="padding: 0 38px 0 38px;">
			                                <div style="font-size: 16px; line-height: 20px; font-weight: bold; padding: 20px 0 0 0; text-shadow: 1px 1px 0 #fff;">Здравствуйте!</div>
			                                <div style="font-size: 16px; line-height: normal; padding: 15px 0 0 0; text-shadow: 1px 1px 0 #fff;">
                                                            Поздравляем! Вы успешно зарегистрировались в статусе вебмастера.
                                                            <br /><br />
                                                            <strong>Ваш логин:</strong> ' . $data['login'] . '
                                                            <br /><br />
                                                            <strong>Ваш пароль:</strong> ' . $data['password'] . '
                                                            <br /><br />
			                                </div>
                                                        <br />
                                                        Ваша работа будет осуществляться через личный кабинет пользователя. Для входа в личный кабинет используйте логин и пароль, указанные при регистрации.
                                                        <br /><br />
                                                        Желаем Вам удачи!
                                                        <br /><br />
                                                        Оставить и почитать отзывы Вы сможете в нашей ветке на <a href="http://searchengines.guru/showthread.php?p=12378271">серчах</a>
                                                        <br />

                                                        С Уважением, Администрация сервиса iforget.ru
		                                </div>
		                                <br />
		                                <div style="font-size: 13px; line-height: normal; text-shadow: 1px 1px 0 #fff; padding: 20px 60px 0 60px;">Вы получили это письма так как зарегистрировались в системе iforget.ru<br> </div>
		                                <div style="font-size: 13px; background: #fff; border-top: 1px solid #d7d7d7; height: 50px; line-height: 50px; text-align: center; margin: 20px 0 0 0;">© 2014 iForget — система автоматической монетизации</div>
                                            </div>
                                        </div>
                                    </body>
                                </html>
                                ';

            require_once 'includes/mandrill/mandrill.php';
            $mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');

            $message = array();
            $message["html"] = $body;
            $message["text"] = "";
            $message["subject"] = "Поздравляем Вас с успешной регистрацией iforget.ru";
            $message["from_email"] = "admin@iforget.ru";
            $message["from_name"] = "iforget";
            $message["to"] = array();
            $message["to"][0] = array("email" => $email);
            //$message["to"][1] = array("email" => MAIL_DEVELOPER);
            $message["track_opens"] = null;
            $message["track_clicks"] = null;
            $message["auto_text"] = null;
            $message["images"] = array();
            $f1 = fopen("images/header_bg.jpg", "rb");
            $f2 = fopen("images/logo_main.jpg", "rb");
            $message["images"][] = array("type" => "image/jpg", "name" => "header_bg", "content" => base64_encode(fread($f1, filesize("images/header_bg.jpg"))));
            $message["images"][] = array("type" => "image/jpg", "name" => "logo_main", "content" => base64_encode(fread($f2, filesize("images/logo_main.jpg"))));


            try {
                $mandrill->messages->send($message);
            } catch (Exception $e) {
                echo $e;
            }

            $body = "Добрый день!<br/>
                     На сайте зарегестрировался новый пользователь!<br/><br/>
                     Логин: '<a href='http://iforget.ru/admin.php?module=admins&action=sayty&uid=$cur_id'> " . $data['login'] . " </a>'<br/>
                     E-mail: " . $email . "<br/><br/>
                     С уважением, администрация сайта iforget.    
            ";
            $message["html"] = $body;
            $message["text"] = "";
            $message["subject"] = "[Зарегистрировался новый пользователь]";
            $message["to"][0] = array("email" => MAIL_ADMIN);
            $message["images"] = null;
            try {
                $mandrill->messages->send($message);
            } catch (Exception $e) {
                echo '';
            }
        }
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
        $content = str_replace('[alert]', $alert, $content);
        $content = str_replace('[url]', $url, $content);

        return $content;
    }

    function new_user_from_socnet($db, $data) {
        $error = false;
        $login = $data['nickname'];
        $email = $data['email'];
        $cid = $data['uid'];
        $network = $data['network'];
        $type = "user";

        if (empty($data['password'])) {
            $data['password'] = uniqid();
        }
        $pass = md5($data['password']);
        $date = time();
        $new_user = 1; //если зарегестрировался позже `01.11.14 00:00`, то пользователь считается новым и цены для него выше
        $contacts = $data['last_name'] . " " . $data['first_name'];

        $res = $db->Execute("select * from admins where login='$login' OR email='$email'")->FetchRow();
        if ($res['id']) {
            $error = 'Пользователь с такими данными уже имеется в базе';
        } else {
            $confirmation = md5($email . time());
            $db->Execute("SET NAMES 'UTF8'");
            $q = "insert into admins(login, email, pass, type, cid, active, reg_date, contacts, dostupy, confirmation, new_user) values ('$login', '$email', '$pass', '$type', '$cid', 0, $date, '$contacts', '', '$confirmation', '$new_user')";
            $db->Execute($q);

            $res = $db->Execute("select * from admins where login='$login'")->FetchRow();

            $cur_dt = date("Y-m-d H:i:s");
            $cur_id = $res['id'];
            if ($type == "user")
                $db->Execute("INSERT INTO orders (uid, price, date, status) VALUES ($cur_id, 1000, '$cur_dt', 0)");

            /* сообщение */
            $body = '
                                <html>
                                    <head>
                                        <meta charset="utf-8">
                                        <title>Поздравляем Вас с успешной регистрацией на сайте iforget.ru</title>
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
			                                <a style="float: right; font-size: 16px; color: #3d413d; margin: 30px 0 0 0;" href="http://iforget.ru/?uid=' . $cur_id . '">Войти</a>
                                                    </div>
		                                </div>
		                                <div style="padding: 0 38px 0 38px;">
			                                <div style="font-size: 16px; line-height: 20px; font-weight: bold; padding: 20px 0 0 0; text-shadow: 1px 1px 0 #fff;">Здравствуйте!</div>
			                                <div style="font-size: 16px; line-height: normal; padding: 15px 0 0 0; text-shadow: 1px 1px 0 #fff;">
                                                            Поздравляем! Вы успешно зарегистрировались в статусе вебмастера.
                                                            <br /><br />
                                                            <strong>Ваш логин:</strong> ' . $data['nickname'] . '
                                                            <br /><br />
                                                            <strong>Ваш пароль:</strong> ' . $data['password'] . '
                                                            <br /><br />
                                                            Пожалуйста, подтвердите Ваш E-mail адрес:
			                                </div>
			                                <br />
                                                        Ваша работа будет осуществляться через личный кабинет пользователя.
                                                        <br />
                                                        Для входа в личный кабинет используйте аккаунт ' . $network . ' или логин и пароль.
                                                        <br /><br />
                                                        Желаем Вам удачи!
                                                        <br /><br />
                                                        Оставить и почитать отзывы Вы сможете в нашей ветке на <a href="http://searchengines.guru/showthread.php?p=12378271">серчах</a>
                                                        <br />

                                                        С Уважением, Администрация сервиса iforget.ru
		                                </div>
		                                <br />
		                                <div style="font-size: 13px; line-height: normal; text-shadow: 1px 1px 0 #fff; padding: 20px 60px 0 60px;">Вы получили это письма так как зарегистрировались в системе iforget.ru<br> </div>
		                                <div style="font-size: 13px; background: #fff; border-top: 1px solid #d7d7d7; height: 50px; line-height: 50px; text-align: center; margin: 20px 0 0 0;">© 2014 iForget — система автоматической монетизации</div>
                                            </div>
                                        </div>
                                    </body>
                                </html>
                                ';

            require_once 'includes/mandrill/mandrill.php';
            $mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');

            $message = array();
            $message["html"] = $body;
            $message["text"] = "";
            $message["subject"] = "Поздравляем Вас с успешной регистрацией iforget.ru";
            $message["from_email"] = "admin@iforget.ru";
            $message["from_name"] = "iforget";
            $message["to"] = array();
            $message["to"][0] = array("email" => $email, "name" => $data['fio']);
            $message["track_opens"] = null;
            $message["track_clicks"] = null;
            $message["auto_text"] = null;
            $message["images"] = array();
            $f1 = fopen("images/header_bg.jpg", "rb");
            $f2 = fopen("images/logo_main.jpg", "rb");
            $message["images"][] = array("type" => "image/jpg", "name" => "header_bg", "content" => base64_encode(fread($f1, filesize("images/header_bg.jpg"))));
            $message["images"][] = array("type" => "image/jpg", "name" => "logo_main", "content" => base64_encode(fread($f2, filesize("images/logo_main.jpg"))));


            try {
                $result = $mandrill->messages->send($message);
            } catch (Exception $e) {
                echo $body;
            }

            $body = "Добрый день!<br/>
                     На сайте зарегестрировался новый пользователь!<br/><br/>
                     Логин: '<a href='http://iforget.ru/admin.php?module=admins&action=sayty&uid=$cur_id'> " . $data['nickname'] . " </a>'<br/>
                     E-mail: " . $email . "<br/><br/>
                     С уважением, администрация сайта iforget.    
            ";
            $message["html"] = $body;
            $message["text"] = "";
            $message["subject"] = "[Зарегистрировался новый пользователь]";
            $message["to"][0] = array("email" => MAIL_ADMIN);
            $message["images"] = null;
            try {
                $mandrill->messages->send($message);
            } catch (Exception $e) {
                echo $e;
            }
        }

        return $error;
    }

    function user_error($data, $error = array()) {
        $content = file_get_contents(PATH . 'modules/register/tmp/register.tpl');
        foreach ($data as $key => $val) {
            if ($key != "error")
                $content = str_replace('[' . $key . ']', $val, $content);
        }
        
        if (isset($data["type"])) {
            if ($data["type"] == "user") {
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

        $error_text = "";
        if (!empty($error)) {
            foreach ($error as $err) {
                $error_text .= "<div style='color: red; font-weight: bold;'>$err</div>";
            }
            $error_text .= "<br>";
        }

        $content = str_replace('[error]', $error_text, $content);

        return $content;
    }

}

?>