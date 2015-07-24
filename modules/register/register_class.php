<?php

class register_class {
    public $_smarty = null;
    public $_postman = null;
    
    public function __construct($db, $smarty) {
        $this->_smarty = $smarty;
        $this->_postman = new Postman($smarty, $db);
    }
    
    function validate($post, $db) {
        $rez = array();
        foreach ($post as $key => $val) {
            $input_text = trim($val);
            $input_text = htmlspecialchars($input_text);
            $input_text = addslashes($input_text);
            if ($key == "type") {
                if ($val == 1) {
                    $input_text = "copywriter";
                    if (!empty($post["wallet"])) {
                        $wallet = $db->Execute("SELECT * FROM admins WHERE wallet='" . $post['wallet'] . "'")->FetchRow();
                        if (!empty($wallet)) {
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
        if (empty($data['password'])) {
            $data['password'] = uniqid();
        }

        $pass = md5($data['password']);
        $date = time();
        $contacts = NULL;
        $dostupy = isset($data['knowus']) ? $data['knowus'] : "";

        $res = $db->Execute("SELECT * FROM admins WHERE login='$login' OR email='$email'")->FetchRow();
        if ($res['id']) {
            $alert = 'Пользователь с такими данными уже имеется в базе';
            $url = '/register.php';
        } else {
            if ($type == "copywriter") {
                $active = 1;
            }
            $confirmation = md5($email . time());
            $db->Execute("SET NAMES 'UTF8'");
            $db->Execute("INSERT INTO admins(login, email, pass, type, active, reg_date, contacts, dostupy, confirmation, wallet, new_user) values ('$login', '$email', '$pass', '$type', '$active', $date, '$contacts', '$dostupy', '$confirmation', '$wallet', '1')");

            $user = $db->Execute("SELECT * FROM admins WHERE login='$login'")->FetchRow();
            if (!empty($user)) {
                $alert = 'Пользователь успешно добавлен.';
                $url = '/user.php';
            }

            $cur_dt = date("Y-m-d H:i:s");
            $cur_id = $user['id'];
            if ($type == "user") {
                $db->Execute("INSERT INTO orders (uid, price, date, status) VALUES ($cur_id, 1000, '$cur_dt', 0)");

                if ($data['promo']) {
                    $Message2009 = $db->Execute("SELECT * FROM Message2009 WHERE Code='" . $db->escape($data['promo']) . "' AND ((Used=0) OR (Used IS NULL))")->FetchRow();
                    if ($Message2009['Message_ID']) {
                        $user_used = $db->Execute("SELECT * FROM promo_user WHERE promo_id=" . $Message2009['Message_ID'] . " AND user_id=$cur_id")->FetchRow();

                        if (!$user_used) {
                            $db->Execute("INSERT INTO orders (uid, price, date, status) VALUES ($cur_id, 150, '$cur_dt', 1)");
                            $db->Execute("INSERT INTO promo_user (promo_id, user_id) VALUES (" . $Message2009['Message_ID'] . ", $cur_id)");

                            $this->_postman->admin->userUsedPromoCode($user, $data['promo']);
                        }
                    }
                }

                if ($data['partner_link']) {
                    $plink = $db->escape($data['partner_link']);
                    $tmpArr = explode('_', $plink);
                    $hash = md5($tmpArr[1] . "iforget");
                    if (isset($tmpArr[0]) && $tmpArr[0] == $hash) {
                        $db->Execute("INSERT INTO partnership (partner_id, new_user_id) VALUES (" . $tmpArr[1] . ", $cur_id)");
                    }
                }
                // Отправляем письма в зависимости от типа пользователя
                $this->_postman->user->registration($cur_id, $email, $data['login'], $data['password']);
            } else {
                $this->_postman->copywriter->registration($cur_id, $email, $data['login'], $data['password']);
            }

            // Отправляем письмо админу о новом пользователе
            $this->_postman->admin->newUser($cur_id, $data['login'], $email);
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

        $res = $db->Execute("SELECT * FROM admins WHERE login='$login' OR email='$email'")->FetchRow();
        if ($res['id']) {
            $error = 'Пользователь с такими данными уже имеется в базе';
        } else {
            $confirmation = md5($email . time());
            $db->Execute("SET NAMES 'UTF8'");
            $db->Execute("insert into admins(login, email, pass, type, cid, active, reg_date, contacts, dostupy, confirmation, new_user) values ('$login', '$email', '$pass', '$type', '$cid', 0, $date, '$contacts', '', '$confirmation', '$new_user')");

            $res = $db->Execute("SELECT * FROM admins WHERE login='$login'")->FetchRow();

            $cur_dt = date("Y-m-d H:i:s");
            $cur_id = $res['id'];
            if ($type == "user"){
                $db->Execute("INSERT INTO orders (uid, price, date, status) VALUES ($cur_id, 1000, '$cur_dt', 0)");
                
                // Отправляем письма в зависимости от типа пользователя
                $this->_postman->user->registration($cur_id, $email, $data['nickname'], $data['password'], $network);
            } else {
                $this->_postman->copywriter->registration($cur_id, $email, $data['nickname'], $data['password'], $network);
            }
            
            // Отправляем письмо админу о новом пользователе
            $this->_postman->admin->newUser($cur_id, $data['nickname'], $email);
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