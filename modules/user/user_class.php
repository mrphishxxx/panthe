<?php

class user {

    public $_smarty = null;
    public $_postman = null;
    
    public $_user = null;
    public $_options = array();

    function content($db, $smarty) {
        $this->_smarty = $smarty;
        $this->_postman = new Postman($smarty, $db);
        
        if (isset($_SESSION["user"]['id'])) {
            $this->_user = $_SESSION["user"];
            $options = $db->Execute("SELECT * FROM user_options WHERE user_id = '" . $this->_user["id"] . "'")->GetAll();
            foreach ($options as $opt) {
                $this->_options[$opt["option_id"]] = true;
            }
        }

        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
        $action2 = isset($_REQUEST['action2']) ? $_REQUEST['action2'] : '';

        $uid = (int) $_SESSION['user']['id'];
        $user = $db->Execute("select * from admins where id=$uid")->FetchRow();

        switch (@$action) {
            case '':
                if ($user["active"] != 1) {
                    $birjs = $db->Execute("SELECT * FROM birjs b LEFT JOIN birgi b2 ON b.birj=b2.id WHERE uid = '$uid'")->GetAll();
                    if (count($birjs) == 0) {
                        header("Location: /user.php?action=postreg_step1");
                    } else {
                        $sayty = $db->Execute("SELECT * FROM sayty WHERE uid = '$uid'")->GetAll();
                        if (count($sayty) == 0) {
                            header("Location: /user.php?action=postreg_step2");
                        } else {
                            header("Location: /user.php?action=postreg_step3");
                        }
                    }
                } else {
                    $content = $this->sayty($db);
                }
                break;

            case 'postreg_step1':
                $content = $this->postreg_step1($db);
                break;
            case 'postreg_step2':
                $content = $this->postreg_step2($db);
                break;
            case 'postreg_step3':
                $content = $this->postreg_step3($db);
                break;

            case 'logout':
                $content = $this->logout($db);
                break;
            case 'unsubscribe':
                $content = $this->unsubscribe($db);
                break;
            case 'sayty':
                switch (@$action2) {
                    case '':
                        $content = $this->sayty($db);
                        break;
                    case 'add':
                        $content = $this->sayty_add($db);
                        break;
                    case 'edit':
                        $content = $this->sayty_edit($db);
                        break;
                    case 'del':
                        $content = $this->sayty_del($db);
                        break;
                    case 'load_ggl':
                        $content = $this->sayty_load_ggl($db);
                        break;
                    case 'load_getgoodlinks':
                        $content = $this->sayty_load_getgoodlinks($db);
                        break;
                    case 'load_sape':
                        $content = $this->sayty_load_sape($db);
                        break;
                    case 'load_rotapost':
                        $content = $this->sayty_load_rotapost($db);
                        break;
                    case 'thankspage':
                        $content = $this->sayty_thankspage($db);
                        break;
                }
                break;

            case 'zadaniya':
                switch (@$action2) {
                    case '':
                        $content = $this->zadaniya($db);
                        break;

                    case 'add':
                        $content = $this->zadaniya_add($db);
                        break;

                    case 'edit':
                        $content = $this->zadaniya_edit_user($db);
                        break;

                    case 'del':
                        $content = $this->zadaniya_del_user($db);
                        break;
                }
                break;

            case 'birj':
                switch (@$action2) {
                    case '':
                        $content = $this->birj($db);
                        break;

                    case 'edit':
                        $content = $this->birj_edit($db);
                        break;

                    case 'add':
                        $content = $this->birj_add($db);
                        break;

                    case 'del':
                        $content = $this->birj_delete($db);
                        break;
                }
                break;

            case 'all_tasks':
                $content = $this->all_tasks($db);
                break;

            case 'ajax':
                $content = $this->birj($db);
                break;

            case 'changemail':
                $content = $this->changemail($db);
                break;

            case 'site_moder_edit':
                $content = $this->site_viklad_edit($db);
                break;

            case 'ticket':
                switch (@$action2) {
                    case '':
                        $content = $this->tickets($db);
                        break;

                    case 'view':
                        $content = $this->ticket_view($db);
                        break;

                    case 'edit':
                        $content = $this->ticket_edit($db);
                        break;

                    case 'add':
                        $content = $this->ticket_add($db);
                        break;

                    case 'answer':
                        $content = $this->ticket_answer($db);
                        break;

                    case 'close':
                        $content = $this->ticket_close($db);
                        break;
                }
                break;

            case 'payments':
                $content = $this->go_payment($db);
                break;

            case 'lk':
                $content = $this->lk($db);
                break;

            case 'change_wallet':
                switch (@$action2) {
                    case '':
                        $content = $this->change_wallet($db);
                        break;
                    case 'cancel':
                        $content = $this->change_wallet_cancel($db);
                        break;
                    case 'send_mail':
                        $content = $this->change_wallet_send_mail($db);
                        break;
                    case 'confirm':
                        $content = $this->change_wallet_confirm($db);
                        break;
                }
                break;

            case 'partnership':
                $content = $this->partnership($db);
                break;

            case 'output_to_balance':
                $content = $this->output_to_balance($db);
                break;

            case 'output_to_purse':
                $content = $this->output_to_purse($db);
                break;
            case 'decode_balans':
                $content = $this->decode_balans($db);
                break;
            case 'close_notify':
                $content = $this->hide_notify($db);
                break;

            default:
                $content = $this->lk($db);
                break;
        }


        return $content;
    }

    function hide_notify($db) {
        if (isset($_SESSION["user"]['id'])) {
            $_SESSION["user"]['hide_notify'] = 1;
            $db->Execute("UPDATE admins SET hide_notify=1 WHERE id=" . $_SESSION["user"]['id']);
        }
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }

    function logout() {
        unset($_SESSION['user']);
        $cur_exp = time() - 3600;
        setcookie("iforget_ok", "", $cur_exp);
        header('Location:/');
        exit;
    }

    public function executeRequest($method, $url, $useragent, $cookie, $query, $body, $header) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        if (count($query) > 0) {
            $url = $url . '&' . http_build_query($query);
        }
        if ($cookie) {
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        }
        if ($useragent) {
            curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        }
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        $response = curl_exec($ch);
        if (!$response) {
            die(curl_error($ch));
        }
        curl_close($ch);
        return $response;
    }

    function login($db) {
        $error = '';
        if (@$_REQUEST['login'] and ( @$_REQUEST['pass'] or @ $_REQUEST['password'])) {
            $login = $_REQUEST['login'];
            if (isset($_REQUEST['password']) && !empty($_REQUEST['password'])) {
                $pass = md5($_REQUEST['password']);
            } else {
                $pass = md5($_REQUEST['pass']);
            }
            $res = $db->Execute("SELECT * FROM admins WHERE (email='$login' OR login='$login') AND pass='$pass'")->FetchRow();
            if (!$res) {
                $error = 'Логин или пароль введены неверно.';
            } else {
                /* if ($res['active'] != 1)
                  $error = 'Аккаунт не активирован.';
                  else { */
                if ($res["id"] == 421 && $_SERVER["REMOTE_ADDR"] != "87.229.183.58") {
                    $error = 'Не верный IP адрес!';
                    $content = file_get_contents(PATH . 'modules/user/tmp/login.tpl');
                    $content = str_replace('[error]', $error, $content);
                    echo $content;
                    return;
                }
                $this->saveAuthHistory($db, $res);
                if ($res["type"] == 'manager') {
                    $_SESSION['manager'] = $res;
                    header('location:/management.php');
                    setcookie("iforget_manager", $res['id'], time() + 60 * 60 * 24 * 30);
                } elseif ($res["type"] == 'admin') {
                    $_SESSION['admin'] = $res;
                    header('location:/admin.php');
                    setcookie("iforget_admin", $res['id'], time() + 60 * 60 * 24 * 30);
                } else {
                    $_SESSION['user'] = $res;
                    if ($res["type"] == "copywriter") {
                        header('location:/copywriter.php');
                    } else {
                        header('location:/user.php');
                    }
                    setcookie("iforget_ok", $res['id'], time() + 60 * 60 * 24 * 30);
                }
                exit();
                //}
            }
        } elseif (isset($_COOKIE) && isset($_COOKIE['iforget_ok'])) {
            $query = $db->Execute("SELECT * FROM admins WHERE id='" . $_COOKIE['iforget_ok'] . "' LIMIT 1");
            if (!$res = $query->FetchRow()) {
                $error = 'Пользователь не существует!';
            } else {
                /* if ($res['active'] != 1)
                  $error = 'Аккаунт не активирован.';
                  else { */
                if ($res["id"] == 421 && $_SERVER["REMOTE_ADDR"] != "87.229.183.58") {
                    $error = 'Не верный IP адрес!';
                    $content = file_get_contents(PATH . 'modules/user/tmp/login.tpl');
                    $content = str_replace('[error]', $error, $content);
                    echo $content;
                    return;
                }
                $this->saveAuthHistory($db, $res);
                if ($res["type"] == "copywriter") {
                    $_SESSION['user'] = (array) $res;
                    header('location:/copywriter.php');
                } else if ($res["type"] == "manager") {
                    $_SESSION['manager'] = (array) $res;
                    header('location:/management.php');
                } else if ($res["type"] == "admin") {
                    $_SESSION['admin'] = (array) $res;
                    header('location:/admin.php');
                } else {
                    $_SESSION['user'] = (array) $res;
                    header('location:/user.php');
                }
                exit;
                //}
            }
        } elseif (@$_REQUEST['token']) {
            $error = $this->auth_social_network($db);
        }
        $content = file_get_contents(PATH . 'modules/user/tmp/login.tpl');
        $content = str_replace('[error]', $error, $content);
        echo $content;
    }

    function saveAuthHistory($db, $client) {
        $ip = $_SERVER["REMOTE_ADDR"];
        $time = $_SERVER["REQUEST_TIME"];
        $agent = $_SERVER["HTTP_USER_AGENT"];

        $uid = $client["id"];
        $login = $client["login"];

        $db->Execute("INSERT INTO history_auth (uid, login, date, ip, agent) VALUE ('$uid', '$login', '$time', '$ip', '$agent')");
        return;
    }

    function auth_social_network($db) {
        $s = file_get_contents('http://ulogin.ru/token.php?token=' . $_REQUEST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
        $user = json_decode($s, true);
        $error = '';
        if ($user['email'] and $user['uid']) {
            $login = $user['email'];
            $cid = $user['uid'];
            $query = $db->Execute("SELECT * FROM admins WHERE (email='$login' OR login='$login') AND cid='$cid'");
            if (!$res = $query->FetchRow()) {
                $error = 'Пользователь не существует!';
            } else {
                /* if ($res['active'] != 1)
                  $error = 'Аккаунт не активирован.';
                  else { */
                if ($res["id"] == 421 && $_SERVER["REMOTE_ADDR"] != "87.229.183.58") {
                    $error = 'Не верный IP адрес!';
                    $content = file_get_contents(PATH . 'modules/user/tmp/login.tpl');
                    $content = str_replace('[error]', $error, $content);
                    echo $content;
                    return;
                }
                $_SESSION['user'] = (array) $res;
                setcookie("iforget_ok", $res['id'], time() + 60 * 60 * 24 * 30);
                if ($res["type"] == "copywriter") {
                    header('location:/copywriter.php');
                } else {
                    header('location:/user.php');
                }

                exit;
                //}
            }
        }
        return $error;
    }

    function unsubscribe($db) {
        $uid = (int) $_SESSION['user']['id'];
        $query = "";
        if (!empty($uid)) {
            $uinfo = $db->Execute("SELECT * FROM admins WHERE id=$uid")->FetchRow();
            if (!empty($uinfo) && $uinfo["mail_period"] > 0) {
                $db->Execute("UPDATE admins SET mail_period='0' WHERE id=$uid");
                $query .= "Изменения сохранены";
            }
        }
        header("Location: /user.php?action=lk&query=$query");
    }

    function postreg_step1($db) {
        $content = file_get_contents(PATH . 'modules/user/tmp/postreg_step1.tpl');
        $uid = (int) $_SESSION['user']['id'];
        $user = $db->Execute("SELECT * FROM admins WHERE id = '$uid'")->FetchRow();
        $_SESSION["postreg"] = "step1";
        if (!empty($_POST)) {
            $login = $db->escape($_REQUEST['login']);
            $pass = $db->escape($_REQUEST['password']);
            $uid = intval($_REQUEST['uid2']);
            $birj = intval($_REQUEST['bid']);

            $db->Execute("INSERT INTO birjs (uid, birj, login, pass) VALUES ($uid, $birj, '$login', '$pass')");

            $user = $db->Execute("SELECT * FROM admins WHERE id = $uid")->FetchRow();
            $birg = $db->Execute("SELECT * FROM birgi WHERE id = $birj")->FetchRow();

            if ((int) $birj == 5) {
                $this->_postman->admin->debugging(true);
            }
            $this->_postman->admin->userAddBurse($user, $birg["Name"], $login, $pass);
            header("Location: /user.php?action=postreg_step1");
        } else {
            $birgi = $db->Execute("SELECT * FROM birgi");
            $burse = $birj = "";
            while ($b = $birgi->FetchRow()) {
                $burse .= "<option value='" . $b['id'] . "'>" . $b['Name'] . "</option>";
            }
            $birjs = $db->Execute("SELECT * FROM birjs b LEFT JOIN birgi b2 ON b.birj=b2.id WHERE uid = '$uid'")->GetAll();
            if (!empty($birjs)) {
                foreach ($birjs as $resw) {
                    $birj .= file_get_contents(PATH . 'modules/user/tmp/birj_one.tpl');
                    $birj = str_replace('[sistema]', $resw['Name'], $birj);
                    $birj = str_replace('[login]', $resw['login'], $birj);
                    $birj = str_replace('[pass]', $resw['pass'], $birj);
                    $birj = str_replace('[bid]', $resw['bid'], $birj);
                }
                $content = str_replace('[link]', '<a class="button right" href="/user.php?action=postreg_step2">ПЕРЕЙТИ К ШАГУ 2</a>', $content);
            } else {
                $birj = "<tr><td colspan='5'>Вы не добавили пока ни одной биржи</td></tr>";
                $content = str_replace('[link]', '<a class="button right" href="/user.php?action=postreg_step2">Пропустить этот шаг</a>', $content);
            }

            $content = str_replace('[uid]', $uid, $content);
            $content = str_replace('[burse]', $burse, $content);
            $content = str_replace('[birjs]', $birj, $content);
        }
        return $content;
    }

    function postreg_step2($db) {
        $content = file_get_contents(PATH . 'modules/user/tmp/postreg_step2.tpl');
        $uid = (int) $_SESSION['user']['id'];
        $_SESSION["postreg"] = "step2";

        $sayty = '';
        $query = $db->Execute("select * from sayty where uid=$uid order by id asc");
        while ($res = $query->FetchRow()) {
            $sayty .= "<tr>";
            $sayty .= "<td>" . $res['url'] . "</td>";
            $sayty .= "<td>" . $res['site_subject'] . "</td>";
            $sayty .= '<td class="edit"><a href="?module=user&action=sayty&uid=' . $res['uid'] . '&action2=edit&id=' . $res['id'] . '" class="ico"></a></td>';
            $sayty .= "</tr>";
        }
        if ($sayty) {
            $content = str_replace('[link]', '<span>Пожалуйста, заполните карточки сайтов, перед тем как перейти к Шагу 3. Это можно сделать, если кликнуть на значок редактирования рядом с сайтом.</span><br /><br /><a class="button right" href="/user.php?action=postreg_step3">ПЕРЕЙТИ К ШАГУ 3</a>', $content);
        } else {
            $sayty = "<tr><td colspan='2'>Не добавлено ни одно сайта</td></tr>";
            $content = str_replace('[link]', '<a class="button right" href="/user.php?action=postreg_step3">Пропустить этот шаг</a>', $content);
        }

        $ggl = $db->Execute("SELECT * FROM birjs WHERE birj=1 AND uid = '$uid'")->FetchRow();
        if ($ggl) {
            $content = str_replace('[load_site_ggl]', '<a href="/user.php?action=sayty&uid=[uid]&action2=load_ggl" class="button">GoGetLinks</a>', $content);
        } else
            $content = str_replace('[load_site_ggl]', '', $content);

        $getgl = $db->Execute("SELECT * FROM birjs WHERE birj=2 AND uid = '$uid'")->FetchRow();
        if ($getgl) {
            $content = str_replace('[load_site_getgoodlinks]', '<a href="/user.php?action=sayty&uid=[uid]&action2=load_getgoodlinks" class="button">GetGoodLinks</a>', $content);
        } else
            $content = str_replace('[load_site_getgoodlinks]', '', $content);

        $sape = $db->Execute("SELECT * FROM birjs WHERE birj=4 AND uid = '$uid'")->FetchRow();
        if ($sape) {
            $content = str_replace('[load_site_sape]', '<a href="/user.php?action=sayty&uid=[uid]&action2=load_sape" class="button">Sape</a>', $content);
        } else
            $content = str_replace('[load_site_sape]', '', $content);

        $rotapost = $db->Execute("SELECT * FROM birjs WHERE birj=3 AND uid = '$uid'")->FetchRow();
        if ($rotapost) {
            $content = str_replace('[load_site_rotapost]', '<a href="/user.php?action=sayty&uid=[uid]&action2=load_rotapost" class="button">Rotapost</a>', $content);
        } else
            $content = str_replace('[load_site_rotapost]', '', $content);

        if ($ggl || $getgl || $sape || $rotapost) {
            $content = str_replace('[add_site]', '<br /><h3>Вы можете автоматически выгрузить сайты из бирж</h3>', $content);
        } else {
            $content = str_replace('[add_site]', '', $content);
        }
        $content = str_replace('[sayty]', $sayty, $content);
        $content = str_replace('[uid]', $uid, $content);
        return $content;
    }

    function postreg_step3($db) {
        $content = file_get_contents(PATH . 'modules/user/tmp/postreg_step3.tpl');
        $uid = (int) $_SESSION['user']['id'];
        $_SESSION["postreg"] = "step3";
        $user = $db->Execute("select * from admins where id=$uid")->FetchRow();
        if (@$_REQUEST['promo'] && $user["type"] != "copywriter") {
            $res = $db->Execute("SELECT * FROM Message2009 WHERE Code='" . $db->escape($_REQUEST['promo']) . "' AND ((Used=0) OR (Used IS NULL))")->FetchRow();
            if ($res['Message_ID']) {
                $user_used = $db->Execute("SELECT * FROM promo_user WHERE user_id=$uid")->FetchRow();
                if (!$user_used) {
                    $cur_dt = date("Y-m-d H:i:s");
                    $db->Execute("INSERT INTO orders (uid, price, date, status, is_promo) VALUES ($uid, 150, '$cur_dt', 1, 1)");
                    $db->Execute("INSERT INTO promo_user (promo_id, user_id) VALUES (" . $res['Message_ID'] . ", $uid)");

                    $this->_postman->admin->userGoPayment($user["id"], $user["login"], $_REQUEST['promo']);
                }

                header("Location:/user.php?action=payments");
                exit();
            }
        }

        if (@$_REQUEST['send']) {
            $price = floatval($_REQUEST['sum']);
            $cur_dt = date("Y-m-d H:i:s");
            $db->Execute("INSERT INTO orders (uid, price, date, status) VALUES ($uid, '$price', '$cur_dt', 0)");
            $res = $db->Execute("SELECT * FROM orders WHERE date='$cur_dt' AND uid=$uid AND price='$price' AND status=0")->FetchRow();
            $oid = $res['id'];


            // your registration data
            $mrh_login = "postin";      // your login here
            $mrh_pass1 = "xN567YtGhPoSz3M";   // merchant pass1 here
            // order properties
            $inv_id = intval($oid);        // shop's invoice number 
            // (unique for shop's lifetime)
            $inv_desc = "Пополнение баланса";   // invoice desc
            $inv_desc = urlencode($inv_desc);
            $out_summ = $price;   // invoice summ
            // build CRC value
            $crc = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1");

            // build URL
            $url = "https://merchant.roboxchange.com/Index.aspx?MrchLogin=$mrh_login&" .
                    "OutSum=$out_summ&InvId=$inv_id&Desc=$inv_desc&SignatureValue=$crc";

            header("Location:" . $url);
            exit();
        } else {
            $order = "";
            $orders = $db->Execute("SELECT * FROM orders WHERE uid=$uid");
            $payment_no = 1;
            while ($res = $orders->FetchRow()) {
                $payment_no++;
                $order .= file_get_contents(PATH . 'modules/user/tmp/order_one.tpl');
                $order = str_replace('[price]', $res['price'] . " руб.", $order);
                $order = str_replace('[date]', $res['date'], $order);

                if ($res['status'] == 1) {
                    $status = "<b style='color:green;'>Оплачено</b>";
                } else {
                    $status = "Не оплачено.<br/><a href='http://iforget.ru/gopay/?oid=" . $res['id'] . "&psum=" . $res['price'] . "'>Оплатить!</a>";
                }
                $order = str_replace('[status]', $status, $order);
            }
            $content = str_replace('[payment_no]', $payment_no, $content);
            $content = str_replace('[email]', $user["email"], $content);
            $content = str_replace('[uid]', $uid, $content);
            $content = str_replace('[my_payments]', $order, $content);
            $content = str_replace('[promo]', (@$_GET['promo'] ? $_GET['promo'] : ""), $content);
        }

        return $content;
    }

//##################################################### MODER PART ############################################################

    function changemail($db) {

        if ($_REQUEST['email']) {
            $email = $_REQUEST['email'];
            $db->Execute("UPDATE admins SET email='" . $email . "' WHERE id=" . $_SESSION['user']['id']);
            $_SESSION['user']['email'] = $email;
            $this->_postman->admin->userChangemail($_SESSION['user']['login'], $email);

            header('location:/user.php');
            exit;
        }

        $content = file_get_contents(PATH . 'modules/user/tmp/changemail.tpl');
        $user_email = $_SESSION['user']['email'];
        $content = str_replace('[email]', $user_email, $content);

        return $content;
    }

    function zadaniya_moder($db) {
        $uid = (int) $_SESSION['user']['id'];
        $moder = $db->Execute("SELECT * FROM admins WHERE id=$uid")->FetchRow();
        $cur_url = (@$_GET['domain_f'] ? "/user.php?domain_f=" . $_GET['domain_f'] : "/user.php?");
        $url = $cur_url . (@$_GET['status_z'] ? ($_GET['domain_f'] ? "&" : "") . "status_z=" . $_GET['status_z'] : "");
        $content = "";
        $limit = 25;
        $offset = 1;
        if (isset($_GET['offset']) && !empty($_GET['offset'])) {
            $offset = (int) $_GET['offset'];
        }

        $query = $db->Execute("SELECT * FROM sayty WHERE moder_id=$uid ORDER BY url");
        $sites = "";
        $sids = $uids = array();
        $domain_f = "<option></option>";
        $n = 0;
        while ($res = $query->FetchRow()) {
            $n++;
            $sids[] = $res['id'];
            $domain_f .= '<option value="' . $res['id'] . '" ' . (@$_GET['domain_f'] == $res['id'] ? 'selected' : '') . '>' . $res['url'] . '</option>';
            if (!in_array($res['uid'], $uids))
                $uids[] = $res['uid'];

            $sites .= file_get_contents(PATH . 'modules/user/tmp/site_one_moder.tpl');
            $sites = str_replace('[site]', $res['url'], $sites);
            $sites = str_replace('[url]', $res['url_admin'], $sites);
            $sites = str_replace('[login]', $res['login'], $sites);
            $sites = str_replace('[pass]', $res['pass'], $sites);
            $sites = str_replace('[sid]', $res['id'], $sites);
            $sites = str_replace('[comment_viklad]', $res['comment_viklad'], $sites);

            $class = ($n % 2 == 0) ? 'style="background:#f7f7f7"' : 'style="background:white"';
            $sites = str_replace('[bg]', $class, $sites);
        }

        $sort = "ASC";
        $content .= file_get_contents(PATH . 'modules/user/tmp/zadaniya_view_moder.tpl');
        $content = str_replace('[available_domains]', $domain_f, $content);
        $zadaniya = '';

        $sids = "(" . implode(",", $sids) . ")";
        $uids = "(" . implode(",", $uids) . ")";
        if (@$_GET['domain_f'])
            $sids = " (" . $db->escape($_GET["domain_f"]) . ") ";

        if (!@$_GET['status_z']) {
            $query = $db->Execute("SELECT * FROM zadaniya WHERE sid IN $sids AND ((dorabotka = 1) OR (navyklad=1)) order by date ASC, id $sort LIMIT " . (($offset - 1) * $limit) . ",$limit");
            $all = $db->Execute("select * from zadaniya where sid IN $sids AND ((dorabotka = 1) OR (navyklad=1)) order by date ASC, id $sort");
        } elseif (@$_GET['status_z'] && ($_GET['status_z'] != 'all')) {
            $who_posted = "";
            if (@$_GET['status_z'] == "vipolneno") {
                $who_posted = " AND who_posted = $uid ";
            }
            $status_f = $db->escape(@$_GET['status_z']);

            $query = $db->Execute("select * from zadaniya where ($status_f=1) AND (sid IN $sids) AND ((dorabotka = 1) OR (navyklad=1) OR (vilojeno=1) OR (vipolneno=1)) $who_posted order by date ASC, id $sort LIMIT " . (($offset - 1) * $limit) . ",$limit");
            $all = $db->Execute("select * from zadaniya where ($status_f=1) AND (sid IN $sids) AND ((dorabotka = 1) OR (navyklad=1) OR (vilojeno=1) OR (vipolneno=1)) $who_posted order by date ASC, id $sort");
        } else {
            $all = $db->Execute("select * from zadaniya where sid IN $sids AND ((dorabotka = 1) OR (navyklad=1) OR (vilojeno=1) OR (vipolneno=1 AND who_posted = $uid)) order by date ASC, id $sort");
            $query = $db->Execute("select * from zadaniya where sid IN $sids AND ((dorabotka = 1) OR (navyklad=1) OR (vilojeno=1) OR (vipolneno=1 AND who_posted = $uid)) order by date ASC, id $sort LIMIT " . (($offset - 1) * $limit) . ",$limit");
        }
        //print_r($all->GetAll());
        $all_zadanya = (!empty($all)) ? $all->NumRows() : 0;
        $count_pegination = ceil($all_zadanya / $limit);
        if ($count_pegination > 1) {
            $pegination = '<div style="float:right">';
            if ($offset == 1) {
                $pegination .= '<div style="float:left">Пред.</div>';
            } else {
                $pegination .= "<div style='float:left'><a href='$url&offset=" . ($offset - 1) . "'>Пред.</a></div>";
            }
            $pegination .= '<div style="float:left">&nbsp [ стр.&nbsp</div>';
            $pegination .= '<div class="select" style="width:50px;float:left;margin-top:-7px"><select name="pagerMenu" onchange="location=\'' . $url . '&offset=\'+this.options[this.selectedIndex].value+\'\'" ;="">';

            for ($i = 0; $i < $count_pegination; $i++) {
                if ($i + 1 == $offset) {
                    $pegination .= '<option value="' . ($i + 1) . '" selected="selected">' . ($i + 1) . '</option>';
                } else {
                    $pegination .= '<option value="' . ($i + 1) . '">' . ($i + 1) . '</option>';
                }
            }
            $pegination .= '</select></div>';
            $pegination .= '&nbsp из ' . $count_pegination . ' ] &nbsp';
            if ($query->NumRows() < $limit) {
                $pegination .= "След.";
            } else {
                $pegination .= "<a href='$url&offset=" . ($offset + 1) . "'>След.</a>";
            }
            $pegination .= '</div>';
            $pegination .= '<br>';
            $content = str_replace('[pegination]', $pegination, $content);
        } else {
            $content = str_replace('[pegination]', "", $content);
        }


        $all_sites = $db->Execute("SELECT * FROM sayty");
        $ast = array();
        while ($row = $all_sites->FetchRow()) {
            $ast[] = $row;
        }

        if ($query) {
            while ($res = $query->FetchRow()) {
                $zadaniya .= file_get_contents(PATH . 'modules/user/tmp/zadaniya_one_moder.tpl');
                $zadaniya = str_replace('[id]', $res['id'], $zadaniya);
                $system = $res['sid'];
                $zadaniya = str_replace('[sid]', $system, $zadaniya);
                $system = $db->Execute("select * from sayty where id=$system");
                $system = $system->FetchRow();
                $system = $system['url'];
                $zadaniya = str_replace('[sistema]', $system, $zadaniya);
                $zadaniya = str_replace('[sistemaggl]', $res['sistema'], $zadaniya);
                $zadaniya = str_replace('[date]', date('d.m.Y', $res['date']), $zadaniya);
                $zadaniya = str_replace('[ankor]', $res['ankor'], $zadaniya);
                $zadaniya = str_replace('[tema]', mb_substr($res['tema'], 0, 50), $zadaniya);
                $zadaniya = str_replace('[url_statyi]', $res['url_statyi'], $zadaniya);

                if ($res['dorabotka'])
                    $new_s = "in-work";
                else if ($res['vipolneno'])
                    $new_s = "done";
                else if ($res['vrabote'])
                    $new_s = "working";
                else if ($res['navyklad'])
                    $new_s = "ready";
                else if ($res['vilojeno'])
                    $new_s = "vilojeno";
                else
                    $bg = '';
                $zadaniya = str_replace('[status]', $new_s, $zadaniya);

                if ($res['dorabotka'])
                    $bg = 'style="background:#f6b300"';
                else if ($res['vipolneno'])
                    $bg = 'style="background:#83e24a"';
                else if ($res['vrabote'])
                    $bg = 'style="background:#00baff"';
                else if ($res['navyklad'])
                    $bg = 'style="background:#ffde96"';
                else if ($res['vilojeno'])
                    $bg = 'style="background:#b385bf"';
                else
                    $bg = '';
                $zadaniya = str_replace('[bg]', $bg, $zadaniya);

                foreach ($ast as $k => $v) {
                    if ($res['sid'] == $v['id']) {
                        $zadaniya = str_replace('[url]', $v['url'], $zadaniya);
                        break;
                    }
                }
            }
        }
        if ($zadaniya)
            $zadaniya = str_replace('[zadaniya]', $zadaniya, file_get_contents(PATH . 'modules/user/tmp/zadaniya_top_moder.tpl'));
        else {
            $zadaniya = file_get_contents(PATH . 'modules/user/tmp/no.tpl');
            $pegination = "";
        }

        $content .= file_get_contents(PATH . 'modules/user/tmp/site_top_moder.tpl');
        $content = str_replace('[sites]', $sites, $content);

        $content = str_replace('[zadaniya]', $zadaniya, $content);
        $content = str_replace('[uid]', $uid, $content);
        //$content = str_replace('[sid]', $sid ? $sid : null, $content);
        $content = str_replace('[pegination]', @$pegination ? $pegination : null, $content);
        $content = str_replace('[cur_url]', $cur_url, $content);
        $content = str_replace('[viklad_id]', $_SESSION['user']['id'], $content);

        return $content;
    }

    function zadaniya_edit($db) {

        $send = @$_REQUEST['send'];
        $id = (int) $_REQUEST['id'];
        $uid = (int) $_GET['uid'];
        $sid = (int) $_GET['sid'];
        $res = $db->Execute("SELECT * FROM zadaniya WHERE id=$id")->FetchRow();
        $uinfo = $db->Execute("SELECT * FROM admins WHERE id=" . $_SESSION['user']['id'])->FetchRow();
        $sinfo = $db->Execute("SELECT * FROM sayty WHERE id=" . $sid)->FetchRow();

        if (!$send) {
            $content = file_get_contents(PATH . 'modules/user/tmp/zadaniya_edit_moder.tpl');
            $error = @$_REQUEST['error'];
            $content = str_replace('[error]', $error, $content);

            if ($res['vipolneno'])
                $res['vipolneno'] = 'checked="checked"';
            else
                $res['vipolneno'] = '';
            if ($res['dorabotka'])
                $res['dorabotka'] = 'checked="checked"';
            else
                $res['dorabotka'] = '';
            if ($res['vrabote'])
                $res['vrabote'] = 'checked="checked"';
            else
                $res['vrabote'] = '';
            if ($res['navyklad'])
                $res['navyklad'] = 'checked="checked"';
            else
                $res['navyklad'] = '';
            if ($res['vilojeno'])
                $res['vilojeno'] = 'checked="checked"';
            else
                $res['vilojeno'] = '';

            $pass = ETXT_PASS;
            $params = array('method' => 'tasks.getResults', 'token' => '29aa0eec2c77dd6d06e23b3faaef9eed', 'id' => $res['task_id']);
            ksort($params);
            $data = array();
            $data2 = array();
            foreach ($params as $k => $v) {
                $data[] = $k . '=' . $v;
                $data2[] = $k . '=' . urlencode($v);
            }
            $sign = md5(implode('', $data) . md5($pass . 'api-pass'));
            $url = 'https://www.etxt.ru/api/json/?' . implode('&', $data2) . '&sign=' . $sign;
            if ($curl = curl_init()) {
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                $cur_out = curl_exec($curl);
                curl_close($curl);
            }
            $task_stat = json_decode($cur_out);
            $file_href = "";
            $uniq = 0;
            foreach ($task_stat as $kt => $vt) {
                $vt = (array) $vt;

                $file_href = (array) @$vt['files'];
                $file_href_parts = (array) @$file_href['file'];
                if (@$file_href_parts['path']) {
                    $file_path = $file_href_parts['path'];
                    $uniq = $file_href_parts['per_antiplagiat'];
                } else {
                    $file_href_parts = (array) @$file_href['text'];
                    $file_path = @$file_href_parts['path'];
                    $uniq = @$file_href_parts['per_antiplagiat'];
                }
            }
            if ($file_path) {
                $cur_text = file_get_contents($file_path);
                $cur_text = iconv('cp1251', 'utf-8', $cur_text);
                if (!$res['overwrite'])
                    $content = str_replace('[text]', htmlspecialchars_decode($cur_text), $content);
            }

            $params = array('method' => 'tasks.listTasks', 'token' => '29aa0eec2c77dd6d06e23b3faaef9eed', 'id' => $res['task_id']);
            ksort($params);
            $data = array();
            $data2 = array();
            foreach ($params as $k => $v) {
                $data[] = $k . '=' . $v;
                $data2[] = $k . '=' . urlencode($v);
            }
            $sign = md5(implode('', $data) . md5($pass . 'api-pass'));
            $url = 'https://www.etxt.ru/api/json/?' . implode('&', $data2) . '&sign=' . $sign;
            if ($curl = curl_init()) {
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                $cur_out = curl_exec($curl);
                curl_close($curl);
            }
            $task_info = json_decode($cur_out);

            $etxt_action = "";
            foreach ($task_info as $kl => $vl) {
                $vl = (array) $vl;
                if ($vl['status'] == 3) {
                    $etxt_action = '
					<p>
						<table>
						<tr>
							<td>Принять</td>
							<td><input type="radio" value="0" name="morework" /></td>
						</tr>
						<tr>
							<td>На доработку</td>
							<td><input type="radio" value="1" name="morework" /></td>
						</tr>
						<tr>
							<td>Комментарий<br/>доработки</td>
							<td><textarea name="morework_comment" cols="10" rows="5"></textarea></td>
						</tr>
						</table>
					</p>
					';
                }
            }
            $content = str_replace('[etxt_action]', $etxt_action, $content);

            $content = str_replace('[uniq]', $uniq, $content);
            if ($res['sistema'] != "http://miralinks.ru/" && $res['sistema'] != "http://pr.sape.ru/") {
                $content = str_replace('[display]', "style='display:none'", $content);
            } else {
                $content = str_replace('[display]', "", $content);
            }

            foreach ($res as $k => $v) {
                $content = str_replace("[$k]", htmlspecialchars_decode($v), $content);
            }
            $content = str_replace('[uid]', $uid, $content);
            $content = str_replace('[sid]', $sid, $content);
            $content = str_replace('[site]', $sinfo["url"], $content);
            $content = str_replace('[tid]', $id, $content);
        } else {
            $sistema = @$_REQUEST['sistema'];
            $etxt = @$_REQUEST['etxt'];
            $ankor = @$_REQUEST['ankor'];
            $url = @$_REQUEST['url'];
            $keywords = @$_REQUEST['keywords'];
            $tema = @$_REQUEST['tema'];
            $text = @$_REQUEST['text'];
            $url_statyi = @$_REQUEST['url_statyi'];
            $url_pic = @$_REQUEST['url_pic'];
            $price = @$_REQUEST['price'];
            $comments = (isset($_REQUEST['comments']) && !empty($_REQUEST['comments'])) ? mysql_real_escape_string($_REQUEST['comments']) : '';
            $admin_comments = @$_REQUEST['admin_comments'];
            $error = "";

            $task_id = $res['task_id'];

            if (@$_REQUEST['morework'] && $task_id) {
                $text = $_REQUEST['morework_comment'];

                $pass = ETXT_PASS;
                $query_sign = "method=tasks.cancelTasktoken=29aa0eec2c77dd6d06e23b3faaef9eed";
                $sign = md5($query_sign . md5($pass . 'api-pass'));

                $params = array('id' => array($task_id), 'text' => $text);
                $query_p = http_build_query($params);
                $url_etxt = "https://www.etxt.ru/api/json/?method=tasks.cancelTask&token=29aa0eec2c77dd6d06e23b3faaef9eed&sign=" . $sign;
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url_etxt);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $query_p);
                curl_exec($curl);
                curl_close($curl);
            } else if ((@$_REQUEST['morework'] == 0) && $task_id) {
                if (isset($_REQUEST['morework'])) {
                    $pass = ETXT_PASS;
                    $query_sign = "method=tasks.paidTasktoken=29aa0eec2c77dd6d06e23b3faaef9eed";
                    $sign = md5($query_sign . md5($pass . 'api-pass'));

                    $params = array('id' => array($task_id));
                    $query_p = http_build_query($params);
                    $url_etxt = "https://www.etxt.ru/api/json/?method=tasks.paidTask&token=29aa0eec2c77dd6d06e23b3faaef9eed&sign=" . $sign;
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $url_etxt);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $query_p);
                    curl_exec($curl);
                    curl_close($curl);
                }
            }

            $task_status = @$_REQUEST['task_status'];
            if ($task_status == "vilojeno")
                $vilojeno = 1;
            else
                $vilojeno = 0;

            $sinfo["url"] = str_replace("/", "", str_replace("http://", "", str_replace("www.", "", $sinfo["url"])));
            if ($vilojeno == 0) {
                /*  Если статус не изменился, сохраняем поля и отправляем админу писомо об изменениии в задаче  */
                $db->Execute($q = "update zadaniya set vilojeno='$vilojeno', url_statyi='$url_statyi', url_pic='$url_pic', admin_comments='$admin_comments' where id=$id");
                if (($res['text'] != $text) || ($res['admin_comments'] != $admin_comments)) {
                    $this->_postman->admin->moderChangeTask($res, $uinfo);
                }
            } elseif ((empty($url_statyi) || $url_statyi == "" || !mb_strstr($url_statyi, $sinfo["url"]))) {
                /*  ПРОБЛЕМА с ссылкой на статью  */
                if (empty($url_statyi) || $url_statyi == "") { /*  Ссылка пуста или отсутствует  */
                    $error .= ("Поле `Ссылка на статью` обязательно для заполнения, если текст выложен! ");
                }
                if (!mb_strstr($url_statyi, $sinfo["url"])) { /*  Ссылка и URL сайта не совпадают  */
                    $error .= "В поле `Ссылка на статью` url не соответствует сайту!";
                }
                /*  отправляем ошибку МОДЕРАТОРУ об этом  */
                header("Location: /user.php?module=user&action=zadaniya_moder&action2=edit&uid=$uid&sid=$sid&id=$id&error=$error");
                exit();
            } else {
                $db->Execute($q = "update zadaniya set dorabotka=0, vrabote=0, navyklad=0, vilojeno='$vilojeno', who_posted='$uid', url_statyi='$url_statyi', url_pic='$url_pic', admin_comments='$admin_comments' where id=$id");
            }

            /*  Если проблем с ссылкой не было, то изменения были уже сохранены, и редиректим на главную страницу  */
            echo "<script>window.location.href='/user.php';</script>";
            exit();
        }

        return $content;
    }

    function tasks_moder($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/tasks.tpl');
        $status = $_REQUEST['status'];
        $uid = $_SESSION["user"]['id'];

        $cur_sites = $db->Execute("SELECT * FROM sayty WHERE moder_id=" . $uid);
        $sids = array();
        while ($vs = $cur_sites->FetchRow()) {
            $sids[] = $vs['id'];
        }
        $sids = "(" . implode(",", $sids) . ")";

        $tasks = NULL;
        switch ($status) {
            case "navyklad":
                $tasks = $db->Execute("SELECT * from zadaniya where navyklad=1 AND sid IN " . $sids . " ORDER BY date ASC");
                $new_s = "ready";
                $bg = '#ffde96';
                $status = "На выкладывании";
                break;
            case "dorabotka":
                $tasks = $db->Execute("SELECT * FROM zadaniya WHERE dorabotka=1 AND sid IN " . $sids . " ORDER BY date ASC");
                $new_s = "in-work";
                $bg = '#f6b300';
                $status = "На доработке";
                break;
            case "vilojeno":
                $tasks = $db->Execute("select * from zadaniya where vilojeno=1 AND sid IN " . $sids . " ORDER BY date ASC");
                $new_s = "vilojeno";
                $bg = '#b385bf';
                $status = "Выложено";
                break;

            default :
                $tasks = $db->Execute("select * from zadaniya where vilojeno=1 AND sid = 0 ORDER BY date ASC");
                $new_s = $bg = $status = "";
        }

        if (!empty($tasks)) {
            while ($task = $tasks->FetchRow()) {
                $zadaniya .= file_get_contents(PATH . 'modules/admins/tmp/admin/task_one.tpl');
                $zadaniya = str_replace('[url]', (substr(substr($task['url'], strpos($task['url'], "http")), 0, 30)), $zadaniya);
                $zadaniya = str_replace('[id]', $task['id'], $zadaniya);
                $zadaniya = str_replace('[etxt_id]', $task['task_id'], $zadaniya);
                $zadaniya = str_replace('[date]', date('d.m.Y', $task['date']), $zadaniya);
                $zadaniya = str_replace('[tema]', mb_substr($task['tema'], 0, 50), $zadaniya);
                $zadaniya = str_replace('[uid]', $task['uid'], $zadaniya);
                $zadaniya = str_replace('[sid]', $task['sid'], $zadaniya);
                $zadaniya = str_replace('[status]', $new_s, $zadaniya);
                $zadaniya = str_replace('[bg]', 'style="background:' . $bg . '"', $zadaniya);
            }
        } else {
            $zadaniya = "<tr><td colspan=6>Нет задач</td></tr>";
        }
        $content = str_replace('[status]', $status, $content);
        $content = str_replace('[zadaniya]', $zadaniya, $content);
        return $content;
    }

    function site_viklad_edit($db) {

        $send = $_REQUEST['send'];
        $sid = ($_GET['sid'] ? $_GET['sid'] : $_REQUEST['s_id']);

        $site = $db->Execute("SELECT * FROM sayty WHERE id=$sid")->FetchRow();
        $uid = $site['uid'];
        $uinfo = $db->Execute("SELECT * FROM admins WHERE id=" . $uid)->FetchRow();
        if (!$send) {
            $content = file_get_contents(PATH . 'modules/user/tmp/site_moder_edit.tpl');
            $content = str_replace('[question_viklad]', $uinfo['comment_viklad'], $content);
            $content = str_replace('[s_id]', $sid, $content);
        } else {
            $qv = $_REQUEST['question_viklad'];

            $db->Execute("UPDATE admins SET comment_viklad='" . $qv . "' WHERE id=$uid");
            $this->_postman->admin->moderChangeVikladComment($uid, $site['url']);
            header("Location: /user.php");
            exit();
        }

        return $content;
    }

    function zadaniya_to_csv($db) {
        $uid = (int) $_REQUEST['uid'];
        $query = $db->Execute("select * from admins where id=$uid");
        $res = $query->FetchRow();

        $query = $db->Execute("select * from sayty where moder_id=$uid");
        $sids = array();
        while ($res = $query->FetchRow()) {
            $sids[] = $res['id'];
        }
        $sids = "(" . implode(",", $sids) . ")";

        $zadaniya = '';

        $query = $db->Execute("select * from zadaniya where sid IN $sids AND vipolneno=1 order by date");

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
		</tr>';

        $n = 0;
        while ($res = $query->FetchRow()) {
            $system = $res['sid'];
            $system = $db->Execute("select * from sayty where id=$system");
            $system = $system->FetchRow();

            echo '
			<table border="1">
			<tr>
				<td>' . htmlentities(iconv("utf-8", "windows-1251", $res['sistema']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . htmlentities(iconv("utf-8", "windows-1251", $res['ankor']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . htmlentities(iconv("utf-8", "windows-1251", $res['url']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . htmlentities(iconv("utf-8", "windows-1251", $res['keywords']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . htmlentities(iconv("utf-8", "windows-1251", $res['tema']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . htmlentities(iconv("utf-8", "windows-1251", $res['url_statyi']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . htmlentities(iconv("utf-8", "windows-1251", date("d-m-Y", $res['date'])), ENT_QUOTES, "cp1251") . '</td>
				<td>' . htmlentities(iconv("utf-8", "windows-1251", $res['text']), ENT_QUOTES, "cp1251") . '</td>
			</tr>';
        }
        echo '</table>';

        exit();
    }

//##################################################### USER PART ############################################################

    function sayty($db) {
        $content = file_get_contents(PATH . 'modules/user/tmp/sayty_view.tpl');

        $uid = (int) $_SESSION['user']['id'];
        $user = $db->Execute("select * from admins where id=$uid")->FetchRow();

        $content = str_replace('[login]', $user['login'], $content);
        $content = str_replace('[uid]', $uid, $content);

        $neobrabot = $dorabotka = $vilojeno = $vrabote = $navyklad = $neo = array();
        $zadaniya = $db->Execute("SELECT * FROM zadaniya WHERE vipolneno !=1 AND uid=$uid");
        while ($task = $zadaniya->FetchRow()) {
            if (!isset($navyklad[$task["sid"]])) {
                $navyklad[$task["sid"]] = 0;
            }
            if (!isset($vrabote[$task["sid"]])) {
                $vrabote[$task["sid"]] = 0;
            }
            if (!isset($dorabotka[$task["sid"]])) {
                $dorabotka[$task["sid"]] = 0;
            }
            if (!isset($neobrabot[$task["sid"]])) {
                $neobrabot[$task["sid"]] = 0;
            }


            if ($task["navyklad"] == 1) {
                $navyklad[$task["sid"]] += 1;
            }
            if ($task["vrabote"] == 1 && empty($task["dorabotka"]) && empty($task["vipolneno"]) && empty($task["navyklad"]) && empty($task["vilojeno"])) {
                $vrabote[$task["sid"]] += 1;
            }
            if ($task["dorabotka"] == 1) {
                $dorabotka[$task["sid"]] += 1;
            }
            if (empty($task["vrabote"]) && empty($task["dorabotka"]) && empty($task["vipolneno"]) && empty($task["navyklad"]) && empty($task["vilojeno"])) {
                $neobrabot[$task["sid"]] += 1;
            }
        }

        $sayty = '';
        $query = $db->Execute("select * from sayty where uid=$uid order by id asc");
        while ($site = $query->FetchRow()) {
            $sayty .= file_get_contents(PATH . 'modules/user/tmp/sayty_one.tpl');
            $sayty = str_replace('[url]', $site['url'], $sayty);
            $sayty = str_replace('[id]', $site['id'], $sayty);
            $sayty = str_replace('[comment_viklad]', $site['comment_viklad'], $sayty);

            $sayty = str_replace('[z1]', isset($vrabote[$site['id']]) ? $vrabote[$site['id']] : 0, $sayty);
            $sayty = str_replace('[z2]', isset($dorabotka[$site['id']]) ? $dorabotka[$site['id']] : 0, $sayty);
            $sayty = str_replace('[z4]', isset($neobrabot[$site['id']]) ? $neobrabot[$site['id']] : 0, $sayty);
            $sayty = str_replace('[z7]', isset($navyklad[$site['id']]) ? $navyklad[$site['id']] : 0, $sayty);
        }
        if ($sayty) {
            $sayty = str_replace('[sayty]', $sayty, file_get_contents(PATH . 'modules/user/tmp/sayty_top.tpl'));
        } else {
            $sayty = file_get_contents(PATH . 'modules/user/tmp/no.tpl');
        }
        $content = str_replace('[sayty]', $sayty, $content);
        $content = str_replace('[uid]', $uid, $content);
        return $content;
    }

    function sayty_thankspage($db) {
        $content = file_get_contents(PATH . 'modules/user/tmp/thanks_page.tpl');
        $uid = (int) $_SESSION['user']['id'];
        $content = str_replace('[uid]', $uid, $content);
        return $content;
    }

    function birj($db) {
        $content = file_get_contents(PATH . 'modules/user/tmp/birj_view.tpl');
        $uid = (int) $_SESSION['user']['id'];
        $user = $db->Execute("SELECT * FROM admins WHERE id = '$uid'")->FetchRow();
        $content = str_replace('[login]', $user['login'], $content);


        $birgi = $db->Execute("SELECT * FROM birgi");
        $burse = $birj = "";
        while ($b = $birgi->FetchRow()) {
            $burse .= "<option value='" . $b['id'] . "'>" . $b['Name'] . "</option>";
        }

        $birjs = $db->Execute("SELECT * FROM birjs b LEFT JOIN birgi b2 ON b.birj=b2.id WHERE uid = '$uid'")->GetAll();
        if (!empty($birjs)) {
            $content = str_replace('[add_site]', '<h3>Добавить сайты из биржи</h3>', $content);
            foreach ($birjs as $resw) {
                $birj .= file_get_contents(PATH . 'modules/user/tmp/birj_one.tpl');
                $birj = str_replace('[sistema]', $resw['Name'], $birj);
                $birj = str_replace('[login]', $resw['login'], $birj);
                $birj = str_replace('[pass]', $resw['pass'], $birj);
                $birj = str_replace('[bid]', $resw['bid'], $birj);
                if ($resw['birj'] == "1") {
                    $content = str_replace('[load_site_ggl]', '<a href="/user.php?action=sayty&uid=[uid]&action2=load_ggl" class="button">GoGetLinks</a>', $content);
                }
                if ($resw['birj'] == "2") {
                    $content = str_replace('[load_site_getgoodlinks]', '<a href="/user.php?action=sayty&uid=[uid]&action2=load_getgoodlinks" class="button">GetGoodLinks</a>', $content);
                }
                if ($resw['birj'] == "3") {
                    $content = str_replace('[load_site_rotapost]', '<a href="/user.php?action=sayty&uid=[uid]&action2=load_rotapost" class="button">Rotapost</a>', $content);
                }
                if ($resw['birj'] == "4") {
                    $content = str_replace('[load_site_sape]', '<a href="/user.php?action=sayty&uid=[uid]&action2=load_sape" class="button">Sape</a>', $content);
                }
            }
        } else {
            $birj = "<tr><td colspan='5'>Вы не добавили пока ни одной биржи</td></tr>";
            $content = str_replace('[add_site]', '', $content);
        }
        $content = str_replace('[load_site_ggl]', "", $content);
        $content = str_replace('[load_site_getgoodlinks]', "", $content);
        $content = str_replace('[load_site_sape]', "", $content);
        $content = str_replace('[load_site_rotapost]', "", $content);

        $content = str_replace('[uid]', $uid, $content);
        $content = str_replace('[burse]', $burse, $content);
        $content = str_replace('[birjs]', $birj, $content);

        return $content;
    }

    function birj_add($db) {
        $login = $db->escape($_REQUEST['login']);
        $pass = $db->escape($_REQUEST['password']);
        $uid = intval($_REQUEST['uid2']);
        $birj = intval($_REQUEST['bid']);

        $db->Execute("INSERT INTO birjs (uid, birj, login, pass) VALUES ($uid, $birj, '$login', '$pass')");

        $user = $db->Execute("SELECT * FROM admins WHERE id = $uid")->FetchRow();
        $birg = $db->Execute("SELECT * FROM birgi WHERE id = $birj")->FetchRow();

        if ((int) $birj == 5) {
            $this->_postman->admin->debugging(true);
        }
        $this->_postman->admin->userAddBurse($user, $birg["Name"], $login, $pass);
        header("Location: /user.php?action=birj");
    }

    function birj_edit($db) {

        $send = $_REQUEST['send'];
        $bid = intval($_REQUEST['bid']);

        $res = $db->Execute("SELECT * FROM birjs b LEFT JOIN birgi b2 ON b.birj=b2.id WHERE bid=$bid")->FetchRow();
        if (!$send) {
            $content = file_get_contents(PATH . 'modules/user/tmp/birj_edit.tpl');
            foreach ($res as $k => $v) {
                $content = str_replace("[$k]", $v, $content);
            }

            $birgi = $db->Execute("SELECT * FROM birgi");
            $burse = "";
            while ($b = $birgi->FetchRow()) {
                if ($res['birj'] == $b['id'])
                    $burse .= "<option value='" . $b['id'] . "' selected>" . $b['Name'] . "</option>";
                else
                    $burse .= "<option value='" . $b['id'] . "'>" . $b['Name'] . "</option>";
            }
            $content = str_replace('[burse]', $burse, $content);
        } else {
            $login = $db->escape($_REQUEST['login']);
            $pass = $db->escape($_REQUEST['password']);
            $birj = intval($_REQUEST['birj']);
            $user = $db->Execute("SELECT * FROM admins WHERE id=" . $res['uid'])->FetchRow();

            $db->Execute("update birjs set birj=$birj, login='$login', pass='$pass', active='1' WHERE bid=$bid");
            $this->_postman->admin->userEditBurse($user, $login, $pass);
            header("Location: /user.php?action=birj");
            exit();
        }

        return $content;
    }

    function birj_delete($db) {
        $bid = intval($_REQUEST['bid']);

        $q = "DELETE FROM birjs WHERE bid=" . $bid;
        $db->Execute($q);

        $query = $db->Execute("SELECT * FROM birjs b LEFT JOIN birgi b2 ON b.birj=b2.id WHERE bid=$bid");
        $res = $query->FetchRow();

        $q = "DELETE FROM site_b WHERE uid=" . $res['uid'];
        $db->Execute($q);

        header("Location: /user.php?action=birj");
    }

    function sayty_edit($db) {
        $send = @$_REQUEST['send'];
        $id = (int) $_REQUEST['id'];
        $uid = (int) ($_REQUEST['uid'] ? $_REQUEST['uid'] : $_SESSION['user']['id']);
        $site = $db->Execute("SELECT * FROM sayty WHERE id=$id")->FetchRow();
        $user = $db->Execute("SELECT * FROM admins WHERE id=" . $uid)->FetchRow();

        if (!$send) {
            $content = file_get_contents(PATH . 'modules/user/tmp/sayty_edit.tpl');
            $gid = $site['gid'];
            $colvos = $site['colvos'];

            foreach ($site as $k => $v) {
                $content = str_replace("[$k]", $v, $content);
            }
            $content = str_replace('[uid]', $uid, $content);
            $content = str_replace('[gid]', $gid, $content);
            $content = str_replace('[colvos]', $colvos, $content);

            $content = str_replace('[' . $site["site_subject"] . ']', "selected", $content);
            $content = str_replace('[' . $site["cms"] . ']', "selected", $content);
            $content = str_replace('[' . $site["pic_position"] . ']', "selected", $content);
            $content = str_replace('[taskdel_' . $site["taskdel_flag"] . ']', "selected", $content);
            $content = str_replace('[obzor_' . $site["obzor_flag"] . ']', "selected", $content);
            $content = str_replace('[news_' . $site["news_flag"] . ']', "selected", $content);
            $content = str_replace('[subj_' . $site["subj_flag"] . ']', "selected", $content);
            $content = str_replace('[bad_' . $site["bad_flag"] . ']', "selected", $content);

            $option = '<option value="20-45-1500" [cena_45]>45 руб. - 1500 знаков (econom)</option>
                     <option value="30-61-1500" [cena_61]>61 руб. - 1500 знаков (medium)</option>
                     <option value="50-93-1500" [cena_93]>93 руб. - 1500 знаков (elit)</option>
                     <option value="20-60-2000" [cena_60]>60 руб. - 2000 знаков (econom)</option>
                     <option value="30-76-2000" [cena_76]>76 руб. - 2000 знаков (medium)</option>
                     <option value="45-111-2000" [cena_111]>111 руб. - 2000 знаков (elit)</option>';

            $option_newUser = '<option value="20-45-1500" [cena_45]>62 рубл. - 1500 знаков (econom)</option>
                     <option value="30-61-1500" [cena_61]>78 рубл. - 1500 знаков (medium)</option>
                     <option value="50-93-1500" [cena_93]>110 рубл. - 1500 знаков (elit)</option>
                     <option value="20-60-2000" [cena_60]>77 руб. - 2000 знаков (econom)</option>
                     <option value="30-76-2000" [cena_76]>93 руб. - 2000 знаков (medium)</option>
                     <option value="45-111-2000" [cena_111]>128 руб. - 2000 знаков (elit)</option>';

            if ($user["new_user"]) {
                $content = str_replace('[prices_option]', $option_newUser, $content);
            } else {
                $content = str_replace('[prices_option]', $option, $content);
            }

            $content = str_replace('[cena_' . $site['price'] . ']', "selected", $content);
        } else {
            $login = $_REQUEST['login'];
            $pass = $_REQUEST['pass'];
            $url = $_REQUEST['url'];
            $url_admin = $_REQUEST['url_admin'];

            $site_subject = $_REQUEST['site_subject'];
            $site_subject_more = $_REQUEST['site_subject_more'];
            $cms = $_REQUEST['cms'];
            $subj_flag = ($_REQUEST['subj_flag'] == "Да" ? 1 : 0);
            $obzor_flag = ($_REQUEST['obzor_flag'] == "Да" ? 1 : 0);
            $taskdel_flag = ($_REQUEST['taskdel_flag'] == "Да" ? 1 : 0);
            $news_flag = ($_REQUEST['news_flag'] == "Да" ? 1 : 0);
            $bad_flag = ($_REQUEST['bad_flag'] == "Да" ? 1 : 0);
            $anons_size = $_REQUEST['anons_size'];
            $pic_width = $_REQUEST['pic_width'];
            $pic_height = $_REQUEST['pic_height'];
            $pic_position = $_REQUEST['pic_position'];
            $site_comments = $_REQUEST['site_comments'];
            $gid = $_REQUEST['gid'];
            $getgoodlinks_id = $_REQUEST['getgoodlinks_id'];
            $sape_id = $_REQUEST['sape_id'];
            $miralinks_id = $_REQUEST['miralinks_id'];
            $rotapost_id = $_REQUEST['rotapost_id'];
            $webartex_id = $_REQUEST['webartex_id'];
            $blogun_id = $_REQUEST['blogun_id'];
            $tmp_price = @$_REQUEST['cena'];
            if (!empty($tmp_price)) {
                $tmp_price = explode('-', $tmp_price);
                $price_iforget = $tmp_price[1];
                $price_etxt = $tmp_price[0];
                $colvos = $tmp_price[2];
            } else {
                $price_iforget = 45;
                $price_etxt = 20;
                $colvos = 1500;
            }
            //####################################################################
            //cena - etxt; price - iforget	
            if ($uid == 330 && $id == 8192495) {
                $price_iforget = 40;
            }

            $q = "update sayty set login='$login', pass='$pass',gid='$gid', getgoodlinks_id='$getgoodlinks_id', sape_id='$sape_id', miralinks_id='$miralinks_id', rotapost_id='$rotapost_id', webartex_id='$webartex_id', blogun_id='$blogun_id',
                                        url='$url', url_admin='$url_admin', colvos='$colvos', cena='$price_etxt', price='$price_iforget', site_subject='$site_subject', site_subject_more='$site_subject_more', 
                                        cms='$cms', subj_flag='$subj_flag', obzor_flag='$obzor_flag', taskdel_flag='$taskdel_flag', news_flag='$news_flag', bad_flag='$bad_flag', anons_size='$anons_size', 
                                        pic_width='$pic_width', pic_height='$pic_height', pic_position='$pic_position', site_comments='$site_comments' where id=$id";
            $db->Execute($q);
            $this->_postman->admin->userEditSite($user, $site);

            if ($user["active"] != 1 && isset($_SESSION["postreg"]) && $_SESSION["postreg"] == "step2") {
                header("Location: /user.php?action=postreg_step2");
            } else {
                header("Location: /user.php?action=sayty&uid=$uid");
            }
        }
        return $content;
    }

    function sayty_add($db) {
        $uid = (int) $_SESSION['user']['id'];
        $user = $db->Execute("select * from admins where id=" . $uid)->FetchRow();
        $send = @$_REQUEST['send'];
        if (!$send) {
            $content = file_get_contents(PATH . 'modules/user/tmp/sayty_add.tpl');
            $option = '<option value="20-45">32 руб. - 1500 знаков + 13 руб. комиссия = 45 руб.</option>
                    <option value="30-61">48 руб. - 1500 знаков + 13 руб. комиссия = 61 руб.</option>
                    <option value="50-93">80 руб. - 1500 знаков + 13 руб. комиссия = 93 руб.</option>
                    <option value="20-60">47 руб. - 2000 знаков + 13 руб. комиссия = 60 руб.</option>
                    <option value="30-76">63 руб. - 2000 знаков + 13 руб. комиссия = 76 руб.</option>
                    <option value="45-111">98 руб. - 2000 знаков + 13 руб. комиссия = 111 руб.</option>';

            $option_newUser = '<option value="20-45">32 руб. - 1500 знаков + 30 руб. комиссия = 62 руб.</option>
                    <option value="30-61">48 руб. - 1500 знаков + 30 руб. комиссия = 78 руб.</option>
                    <option value="50-93">80 руб. - 1500 знаков + 30 руб. комиссия = 110 руб.</option>
                    <option value="20-60">47 руб. - 2000 знаков + 30 руб. комиссия = 77 руб.</option>
                    <option value="30-76">63 руб. - 2000 знаков + 30 руб. комиссия = 93 руб.</option>
                    <option value="45-111">98 руб. - 2000 знаков + 30 руб. комиссия = 128 руб.</option>';

            if ($user["new_user"]) {
                $content = str_replace('[prices_option]', $option_newUser, $content);
            } else {
                $content = str_replace('[prices_option]', $option, $content);
            }
            $content = str_replace('[uid]', $uid, $content);
        } else {
            $login = $_REQUEST['login'];
            $pass = $_REQUEST['pass'];
            $url = $_REQUEST['url'];
            $url = str_replace('http://', '', $url);
            $url = str_replace('www.', '', $url);
            $url_admin = $_REQUEST['url_admin'];

            $tmp_price = $_REQUEST['cena'];
            $tmp_price = explode('-', $tmp_price);
            $price_iforget = $tmp_price[1];
            $price_etxt = $tmp_price[0];

            $site_subject = $_REQUEST['site_subject'];
            $site_subject_more = $_REQUEST['site_subject_more'];
            $cms = $_REQUEST['cms'];
            $subj_flag = ($_REQUEST['subj_flag'] == "Да" ? 1 : 0);
            $taskdel_flag = ($_REQUEST['taskdel_flag'] == "Да" ? 1 : 0);
            $obzor_flag = ($_REQUEST['obzor_flag'] == "Да" ? 1 : 0);
            $news_flag = ($_REQUEST['news_flag'] == "Да" ? 1 : 0);
            $bad_flag = ($_REQUEST['bad_flag'] == "Да" ? 1 : 0);
            $anons_size = $_REQUEST['anons_size'];
            $pic_width = $_REQUEST['pic_width'];
            $pic_height = $_REQUEST['pic_height'];
            $pic_position = $_REQUEST['pic_position'];
            $site_comments = $_REQUEST['site_comments'];
            $gid = $_REQUEST['gid'];
            $getgoodlinks_id = $_REQUEST['getgoodlinks_id'];
            $sape_id = $_REQUEST['sape_id'];
            $miralinks_id = $_REQUEST['miralinks_id'];
            $rotapost_id = $_REQUEST['rotapost_id'];
            $webartex_id = $_REQUEST['webartex_id'];
            $blogun_id = $_REQUEST['blogun_id'];

            $db->Execute("insert into sayty(uid, url, url_admin, login, pass, gid, getgoodlinks_id, sape_id, miralinks_id, rotapost_id, webartex_id, blogun_id, price, cena, site_subject, site_subject_more, cms, taskdel_flag, obzor_flag, news_flag, subj_flag, bad_flag, anons_size, pic_width, pic_height, pic_position, site_comments) values 
					($uid, '$url', '$url_admin', '$login', '$pass', '$gid', '$getgoodlinks_id', '$sape_id', '$miralinks_id', '$rotapost_id', '$webartex_id', '$blogun_id', '$price_iforget', '$price_etxt', '$site_subject', '$site_subject_more', '$cms', '$taskdel_flag', '$obzor_flag', '$news_flag', '$subj_flag', '$bad_flag', '$anons_size', '$pic_width', '$pic_height', '$pic_position', '$site_comments')");
            $sid = $db->Insert_ID();
            $this->_postman->admin->userAddSite($uid, $sid, $url);

            if ($user["active"] != 1 && isset($_REQUEST["postreg"]) && $_REQUEST["postreg"] == "step2") {
                $url = "?action=postreg_step2";
            } else {
                $url = "?module=user&action=sayty&action2=thankspage";
            }
            header("Location: $url");
        }

        $content = str_replace('[login]', $user['login'], $content);
        return $content;
    }

    function sayty_del($db) {

        $id = $_REQUEST['id'];
        $uid = $_REQUEST['uid'];

        $db->Execute("delete from sayty where id=$id");
        $alert = 'Сайт успешно удален.';

        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
        $content = str_replace('[alert]', $alert, $content);
        $content = str_replace('[url]', "?module=user&action=sayty", $content);

        return $content;
    }

    function sayty_load_ggl($db) {
        include(PATH . 'includes/simple_html_dom.php');
        $content = file_get_contents(PATH . 'modules/user/tmp/load.tpl');
        $content = str_replace('[birj]', "gogetlinks", $content);
        $uid = (int) $_SESSION['user']['id'];
        $user = $db->Execute("select * from admins where id=$uid")->FetchRow();
        if (empty($user)) {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/no_rights.tpl');
            $content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
            return $content;
            exit;
        }
        $cookie_jar = tempnam(PATH . 'temp', "cookie");
        $birjs = $db->Execute("SELECT * FROM birjs WHERE uid = '$uid' AND birj = 1 ORDER BY bid DESC LIMIT 1")->FetchRow();
        if (!empty($birjs)) {
            $data = array('e_mail' => $birjs['login'],
                'password' => $birjs['pass'],
                'remember' => "");

            $auth = $this->executeRequest('POST', 'https://gogetlinks.net/login.php', null, $cookie_jar, array(), $data, null);
            $out_auth = iconv("windows-1251", "utf-8", $auth);


            if (($out_auth == "Некорректный Логин или Пароль") || ($out_auth == "Некорректный email или пароль.")) {
                echo '<script>alert("Неверный логин или пароль!"); window.location.href="/user.php?action=birj";</script>';
                exit();
            }

            $out = $this->executeRequest('POST', 'https://gogetlinks.net/my_sites.php', null, $cookie_jar, array(), array(), null);
            $page_my_sites = iconv("windows-1251", "utf-8", $out);

            $open = str_get_html($page_my_sites);
            $flag = 0;
            foreach ($open->find('script,link,comment') as $tmp) {
                $flag = 1;
                $tmp->outertext = '';
            }

            if ($open->innertext != '' and ( count($open->find('tr[id^=site_row]')))) {
                foreach ($open->find('tr[id^=site_row]') as $tr) {
                    $ggl_id = "";
                    foreach ($tr->find('td') as $td) {
                        foreach ($td->find('div,img,font,label') as $tmp) {
                            $tmp->outertext = '';
                        }
                        if ($td->find('a[href^=/template/edit_site_info.php?site_id=]')) {
                            foreach ($td->find('a[href^=/template/edit_site_info.php?site_id=]') as $a) {
                                $ggl_id = mb_substr($a->href, 37);
                                $url = "http://" . str_replace("&nbsp;", "", $td->innertext);
                            }
                        }
                        if ($td->find('a[href^=row_col_]') && $ggl_id == "") {
                            foreach ($td->find('a[href^=row_col_]') as $td_id) {
                                $ggl_id = mb_substr($td_id->plaintext, 8);
                            }
                        }
                        $dubl = $db->Execute("SELECT * FROM sayty WHERE (url LIKE '" . trim($url) . "' AND gid='$ggl_id') AND uid='$uid'")->FetchRow();
                        if (!$dubl) {
                            $sites[$ggl_id] = trim($url);
                        }
                        break;
                    }
                }
                $new_sites = array();
                if ($_REQUEST["check"] == 1 && count($sites) > 0) {
                    foreach ($sites as $key => $value) {
                        $dubl = $db->Execute("SELECT * FROM sayty WHERE (url LIKE '" . trim($value) . "' OR gid='$key') AND uid='$uid'")->FetchRow();
                        if (!$dubl) {
                            $db->Execute("INSERT INTO sayty(uid,url,gid,cena,price,colvos) VALUES ('" . $uid . "','" . $value . "','" . $key . "', '20', '60', '2000')");
                            $sid = $db->Insert_ID();
                        } else {
                            if ($dubl["url"] != trim($value)) {
                                $db->Execute("UPDATE sayty SET url = '" . trim($value) . "' WHERE id = '" . $dubl["id"] . "'");
                            } else {
                                $db->Execute("UPDATE sayty SET gid = '" . $key . "' WHERE id = '" . $dubl["id"] . "'");
                            }
                            $sid = $dubl["id"];
                        }
                        $new_sites[$sid] = $value;
                    }
                    $this->_postman->admin->userAddSitesFromBurse($uid, $new_sites);
                    if ($user["active"] != 1 && isset($_SESSION["postreg"]) && $_SESSION["postreg"] == "step2") {
                        header("Location: /user.php?action=postreg_step2");
                    } else {
                        header("Location: /user.php?action=sayty");
                    }
                } else {
                    if (count($sites) > 0) {
                        $table = "";
                        foreach ($sites as $key => $value) {
                            if (empty($value)) {
                                continue;
                            }
                            $dubl = $db->Execute("SELECT * FROM sayty WHERE (url LIKE '" . trim($value) . "' OR gid='$key') AND uid='$uid'")->FetchRow();
                            if (!$dubl) {
                                $table .= "<tr><td style='border-right:1px solid #ccc'>$key</td><td style='border-right:1px solid #ccc'>" . $value . "</td>";
                                $table .= "<td>Добавить сайт</td></tr>";
                            } else {
                                if ($dubl["url"] != trim($value) || $dubl["gid"] != $key) {
                                    $table .= "<tr><td style='border-right:1px solid #ccc'>$key</td><td style='border-right:1px solid #ccc'>" . $value . "</td>";
                                    if ($dubl["url"] != trim($value)) {
                                        $table .= "<td>Изменить URL</td></tr>";
                                    } else {
                                        $table .= "<td>Изменить/добавить ID</td></tr>";
                                    }
                                }
                            }
                        }
                        $table .= "";
                        $button = "<a href='/user.php?action=sayty&uid=$uid&action2=load_ggl&check=1' class='button'>Да</a>&nbsp;<a href='/user.php?action=sayty' class='button'>Нет</a>";
                        $content = str_replace('[sites]', $table, $content);
                        $content = str_replace('[button]', $button, $content);
                    } else {
                        $content = file_get_contents(PATH . 'modules/user/tmp/load_not.tpl');
                    }
                }
            } else {
                $content = file_get_contents(PATH . 'modules/user/tmp/load_not.tpl');
            }
        }

        return $content;
    }

    function sayty_load_getgoodlinks($db) {
        include(PATH . 'includes/simple_html_dom.php');
        $content = file_get_contents(PATH . 'modules/user/tmp/load.tpl');
        $content = str_replace('[birj]', "getgoodlinks", $content);
        $uid = (int) $_SESSION['user']['id'];
        $user = $db->Execute("select * from admins where id=$uid")->FetchRow();
        if (empty($user)) {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/no_rights.tpl');
            $content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
            return $content;
            exit;
        }
        $cookie_jar = tempnam(PATH . 'temp', "cookie");
        $birjs = $db->Execute("SELECT * FROM birjs WHERE uid = '$uid' AND birj = 2 ORDER BY bid DESC LIMIT 1")->FetchRow();
        if (!empty($birjs)) {
            $data = array('e_mail' => $birjs['login'],
                'password' => $birjs['pass'],
                'remember' => "");

            $auth = $this->executeRequest('POST', 'http://www.getgoodlinks.ru/login.php', null, $cookie_jar, array(), $data, null);
            $error = strpos($auth, "Неверное имя пользователя или пароль. Пожалуйста, попробуйте ещё раз.");
            if ($error !== false && !empty($error) && $error != 0) {
                echo '<script>alert("Неверный логин или пароль!"); window.location.href="/user.php?action=birj";</script>';
                exit();
            }

            $page_my_sites = $this->executeRequest('POST', 'http://www.getgoodlinks.ru/my_sites.php', null, $cookie_jar, array(), array(), null);
            $open = str_get_html($page_my_sites);
            $flag = 0;
            foreach ($open->find('script,link,comment') as $tmp) {
                $flag = 1;
                $tmp->outertext = '';
            }

            if ($open->innertext != '' and ( count($open->find('tr[id^=row_body]')))) {
                foreach ($open->find('tr[id^=row_body]') as $tr) {
                    foreach ($tr->find('td[class^=row_]') as $td) {
                        foreach ($td->find('div,img,font,label') as $tmp) {
                            $tmp->outertext = '';
                        }
                        if ($td->find('a[href^=template/edit_site_info.php?site_id=]')) {
                            foreach ($td->find('a[href^=template/edit_site_info.php?site_id=]') as $a) {
                                $url = "http://" . str_replace("&nbsp;", "", $a->plaintext);
                            }
                        } else {
                            $url = "http://" . str_replace("&nbsp;", "", $td->innertext);
                        }
                        $ggl_id = mb_substr("$td->class", 4);
                        $dubl = $db->Execute("SELECT * FROM sayty WHERE (url LIKE '" . trim($url) . "' AND getgoodlinks_id='$ggl_id') AND uid='$uid'")->FetchRow();
                        if (!$dubl) {
                            $sites[$ggl_id] = trim($url);
                        }
                        break;
                    }
                }

                $new_sites = array();
                if ($_REQUEST["check"] == 1 && count($sites) > 0) {
                    foreach ($sites as $key => $value) {
                        $dubl = $db->Execute("SELECT * FROM sayty WHERE (url LIKE '" . trim($value) . "' OR getgoodlinks_id='$key') AND uid='$uid'")->FetchRow();
                        if (!$dubl) {
                            $db->Execute("INSERT INTO sayty(uid,url,getgoodlinks_id,cena,price,colvos) VALUES ('" . $uid . "','" . $value . "','" . $key . "', '20', '60', '2000')");
                            $sid = $db->Insert_ID();
                        } else {
                            if ($dubl["url"] != trim($value)) {
                                $db->Execute("UPDATE sayty SET url = '" . trim($value) . "' WHERE id = '" . $dubl["id"] . "'");
                            } else {
                                $db->Execute("UPDATE sayty SET getgoodlinks_id = '" . $key . "' WHERE id = '" . $dubl["id"] . "'");
                            }
                            $sid = $dubl["id"];
                        }
                        $new_sites[$sid] = $value;
                    }
                    $this->_postman->admin->userAddSitesFromBurse($uid, $new_sites);

                    if ($user["active"] != 1 && isset($_SESSION["postreg"]) && $_SESSION["postreg"] == "step2") {
                        header("Location: /user.php?action=postreg_step2");
                    } else {
                        header("Location: /user.php?action=sayty");
                    }
                } else {
                    if (count($sites) > 0) {
                        $table = "";
                        foreach ($sites as $key => $value) {
                            if (empty($value)) {
                                continue;
                            }
                            $dubl = $db->Execute("SELECT * FROM sayty WHERE (url LIKE '" . trim($value) . "' OR getgoodlinks_id='$key') AND uid='$uid'")->FetchRow();
                            if (!$dubl) {
                                $table .= "<tr><td style='border-right:1px solid #ccc'>$key</td><td style='border-right:1px solid #ccc'>" . $value . "</td>";
                                $table .= "<td>Добавить сайт</td></tr>";
                            } else {
                                if ($dubl["url"] != trim($value) || $dubl["getgoodlinks_id"] != $key) {
                                    $table .= "<tr><td style='border-right:1px solid #ccc'>$key</td><td style='border-right:1px solid #ccc'>" . $value . "</td>";
                                    if ($dubl["url"] != trim($value)) {
                                        $table .= "<td>Изменить URL</td></tr>";
                                    } else {
                                        $table .= "<td>Изменить/добавить ID</td></tr>";
                                    }
                                }
                            }
                        }
                        $button = "<a href='/user.php?action=sayty&uid=$uid&action2=load_getgoodlinks&check=1' class='button'>Да</a>&nbsp;<a href='/user.php?action=sayty' class='button'>Нет</a>";
                        $content = str_replace('[sites]', $table, $content);
                        $content = str_replace('[button]', $button, $content);
                    } else {
                        $content = file_get_contents(PATH . 'modules/user/tmp/load_not.tpl');
                    }
                }
            } else {
                $content = file_get_contents(PATH . 'modules/user/tmp/load_not.tpl');
            }
        }

        return $content;
    }

    function sayty_load_sape($db) {
        $content = file_get_contents(PATH . 'modules/user/tmp/load.tpl');
        $content = str_replace('[birj]', "sape", $content);
        $uid = (int) $_SESSION['user']['id'];
        $user = $db->Execute("select * from admins where id=$uid")->FetchRow();
        if (empty($user)) {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/no_rights.tpl');
            $content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
            return $content;
            exit;
        }
        $cookie_jar = tempnam(PATH . 'temp', "cookie");
        $url = "http://api.pr.sape.ru/xmlrpc/";
        $birj = $db->Execute("SELECT * FROM birjs WHERE birj = 4 AND uid = '$uid' ORDER BY bid DESC LIMIT 1")->FetchRow();
        if ($birj['login'] == null || $birj['pass'] == null)
            return false;

        $data = xmlrpc_encode_request('sape_pr.login', array($birj["login"], $birj["pass"]));
        $header[0] = "Content-type: text/xml";
        $header[1] = "Content-length: " . strlen($data);

        $out = $this->executeRequest('POST', $url, null, $cookie_jar, array(), $data, $header);
        $id_user_sape = xmlrpc_decode($out);

        if (is_numeric($id_user_sape)) {
            $data = xmlrpc_encode_request('sape_pr.site.ownlist', array());
            $header[1] = "Content-length: " . strlen($data);
            $get = $this->executeRequest('POST', $url, null, $cookie_jar, array(), $data, $header);
            $site_ownlist = xmlrpc_decode($get);

            $sites = array();
            $sayty = $db->Execute("SELECT * FROM sayty WHERE uid=$uid AND (sape_id IS NOT NULL AND sape_id != 0)");
            while ($site = $sayty->FetchRow()) {
                $sites[] = $site["sape_id"];
            }
            foreach ($site_ownlist as $key => $site) {
                if (in_array($site["id"], $sites) || $site["is_disabled"] == 1) {
                    unset($site_ownlist[$key]);
                }
            }

            $new_sites = array();
            if ($_REQUEST["check"] == 1 && count($site_ownlist) > 0) {
                foreach ($site_ownlist as $value) {
                    $dubl = $db->Execute("SELECT * FROM sayty WHERE (url LIKE '" . $value["url"] . "' OR sape_id='" . $value["id"] . "') AND uid='$uid'")->FetchRow();
                    if (!$dubl) {
                        $db->Execute("INSERT INTO sayty(uid,url,sape_id,cena,price,colvos) VALUES ('" . $uid . "','" . $value["url"] . "','" . $value["id"] . "', '20', '60', '2000')");
                        $sid = $db->Insert_ID();
                    } else {
                        if ($dubl["url"] != $value["url"]) {
                            $db->Execute("UPDATE sayty SET url = '" . $value["url"] . "' WHERE id = '" . $dubl["id"] . "'");
                        } else {
                            $db->Execute("UPDATE sayty SET sape_id = '" . $value["id"] . "' WHERE id = '" . $dubl["id"] . "'");
                        }
                        $sid = $dubl["id"];
                    }
                    $new_sites[$sid] = $value;
                }
                $this->_postman->admin->userAddSitesFromBurse($uid, $new_sites);

                if ($user["active"] != 1 && isset($_SESSION["postreg"]) && $_SESSION["postreg"] == "step2") {
                    header("Location: /user.php?action=postreg_step2");
                } else {
                    header("Location: /user.php?action=sayty");
                }
            } else {
                if (count($site_ownlist) > 0) {
                    $table = "";
                    foreach ($site_ownlist as $value) {
                        $dubl = $db->Execute("SELECT * FROM sayty WHERE (url LIKE '" . $value["url"] . "' OR sape_id='" . $value["id"] . "') AND uid='$uid'")->FetchRow();
                        if (!$dubl) {
                            $table .= "<tr><td style='border-right:1px solid #ccc'>" . $value["id"] . "</td><td style='border-right:1px solid #ccc'>" . $value["url"] . "</td>";
                            $table .= "<td>Добавить сайт</td></tr>";
                        } else {
                            if ($dubl["url"] != $value["url"] || $dubl["sape_id"] != $value["id"]) {
                                $table .= "<tr><td style='border-right:1px solid #ccc'>" . $value["id"] . "</td><td style='border-right:1px solid #ccc'>" . $value["url"] . "</td>";
                                if ($dubl["url"] != $value["url"]) {
                                    $table .= "<td>Изменить URL</td></tr>";
                                } else {
                                    $table .= "<td>Изменить/добавить ID</td></tr>";
                                }
                            }
                        }
                    }
                    $button = "<a href='/user.php?action=sayty&uid=$uid&action2=load_sape&check=1' class='button'>Да</a>&nbsp;<a href='/user.php?action=sayty' class='button'>Нет</a>";
                    $content = str_replace('[sites]', $table, $content);
                    $content = str_replace('[button]', $button, $content);
                } else {
                    $content = file_get_contents(PATH . 'modules/user/tmp/load_not.tpl');
                }
            }
        }

        return $content;
    }

    function sayty_load_rotapost($db) {
        $content = file_get_contents(PATH . 'modules/user/tmp/load.tpl');
        $content = str_replace('[birj]', "rotapost", $content);
        $uid = (int) $_SESSION['user']['id'];
        $user = $db->Execute("select * from admins where id=$uid")->FetchRow();
        if (empty($user)) {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/no_rights.tpl');
            $content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
            return $content;
            exit;
        }
        $error = $table = "";
        include_once 'includes/Rotapost.php';
        $rotapost = new Rotapost\Client();
        $birj = $db->Execute("select * from birjs where birj=3 AND uid=" . $uid)->FetchRow();
        $auth = $rotapost->loginAuth($birj['login'], md5($birj['login'] . $birj['pass']));
        if (($birj['login'] == null || $birj['pass'] == null) || (isset($auth->Success) && $auth->Success == "false") || !isset($auth->ApiKey)) {
            if ($birj['login'] == null || $birj['pass'] == null) {
                $error .= "Error = 'Отсутствует логин или пароль для доступа к биржи Rotapost'!<br/><br/>";
            } elseif ($auth->Success == "false" && isset($auth->Error->Description)) {
                $error .= "Error = '" . $auth->Error->Description . "'!<br/><br/>";
            }
        } else {
            $result = $rotapost->siteIndex();
            if ($result->Success == "true") {
                if (isset($result->Sites->Site) && !empty($result->Sites->Site)) {
                    if ($_REQUEST["check"] == 1 && count($result->Sites->Site) > 0) {
                        $new_sites = array();
                        foreach ($result->Sites->Site as $site) {
                            if ($site->Status == "Active") {
                                $dubl = $db->Execute("SELECT * FROM sayty WHERE (url LIKE '" . $site->Url . "' OR rotapost_id='" . $site->Id . "') AND uid='$uid'")->FetchRow();
                                if (!$dubl) {
                                    $db->Execute("INSERT INTO sayty(uid,url,rotapost_id,cena,price,colvos) VALUES ('" . $uid . "','" . $site->Url . "','" . $site->Id . "', '20', '60', '2000')");
                                    $sid = $db->Insert_ID();
                                } else {
                                    if ($dubl["url"] != $site->Url) {
                                        $db->Execute("UPDATE sayty SET url = '" . $site->Url . "' WHERE id = '" . $dubl["id"] . "'");
                                    } else {
                                        $db->Execute("UPDATE sayty SET rotapost_id = '" . $site->Id . "' WHERE id = '" . $dubl["id"] . "'");
                                    }
                                    $sid = $dubl["id"];
                                }
                                $new_sites[$sid] = $site->Url;
                            }
                        }
                        $this->_postman->admin->userAddSitesFromBurse($uid, $new_sites);

                        if ($user["active"] != 1 && isset($_SESSION["postreg"]) && $_SESSION["postreg"] == "step2") {
                            header("Location: /user.php?action=postreg_step2");
                        } else {
                            header("Location: /user.php?action=sayty");
                        }
                    } else {
                        $count_new_site = 0;
                        foreach ($result->Sites->Site as $site) {
                            if ($site->Status == "Active") {
                                $dubl = $db->Execute("SELECT * FROM sayty WHERE (url LIKE '" . $site->Url . "' OR rotapost_id='" . $site->Id . "') AND uid='$uid'")->FetchRow();
                                if (!$dubl) {
                                    $table .= "<tr><td style='border-right:1px solid #ccc'><small>" . $site->Id . "</small></td><td style='border-right:1px solid #ccc'>" . $site->Url . "</td>";
                                    $table .= "<td><small>Добавить сайт</small></td></tr>";
                                    $count_new_site++;
                                } else {
                                    if ($dubl["url"] != $site->Url || $dubl["rotapost_id"] != $site->Id) {
                                        $count_new_site++;
                                        $table .= "<tr><td style='border-right:1px solid #ccc'><small>" . $site->Id . "</small></td><td style='border-right:1px solid #ccc'>" . $site->Url . "</td>";
                                        if ($dubl["url"] != $site->Url) {
                                            $table .= "<td><small>Изменить URL</small></td></tr>";
                                        } else {
                                            $table .= "<td><small>Изменить/добавить ID</small></td></tr>";
                                        }
                                    }
                                }
                            }
                        }
                        if ($count_new_site > 0) {
                            $button = "<a href='/user.php?action=sayty&uid=$uid&action2=load_rotapost&check=1' class='button'>Да</a>&nbsp;<a href='/user.php?action=sayty' class='button'>Нет</a>";
                            $content = str_replace('[sites]', $table, $content);
                            $content = str_replace('[button]', $button, $content);
                        } else {
                            $content = str_replace('[sites]', "<tr><td colspan='3'>Новых сайтов не найдено в Rotapost</td></tr>", $content);
                            $content = str_replace('[button]', "<a href='/user.php?action=birj' class='button'>Назад</a>", $content);
                        }
                    }
                }
            } else {
                $error .= "Error = '" . $result->Error->Description . "'!<br/><br/>";
            }
        }

        return $content;
    }

    function zadaniya($db) {
        $content = file_get_contents(PATH . 'modules/user/tmp/zadaniya_view.tpl');

        $uid = (int) $_SESSION['user']['id'];
        $content = str_replace('[uid]', $uid, $content);

        $query = $db->Execute("select * from admins where id=$uid");
        $res = $query->FetchRow();
        $content = str_replace('[login]', $res['login'], $content);

        $sid = (int) $_GET['sid'];
        $query = $db->Execute("select * from sayty where id=$sid");
        $res = $query->FetchRow();
        $content = str_replace('[url]', $res['url'], $content);

        $zadaniya = '';
        if (@$_SESSION['sort'] == 'asc' or ! @$_SESSION['sort']) {
            $symb = '↓';
            $sort = 'asc';
        } else {
            $symb = '↑';
            $sort = 'desc';
        }

        if (@$_POST['date-from'] || @$_POST['date-to']) {
            if ($_POST['date-from'])
                $from = strtotime($db->escape($_POST['date-from']));

            if ($_POST['date-to'])
                $to = strtotime($db->escape($_POST['date-to']));

            if ($from && $to) {
                $query = $db->Execute("select * from zadaniya where sid=$sid AND date BETWEEN '" . $from . "' AND '" . $to . "' order by date DESC, id $sort");
            } else {
                if ($from)
                    $query = $db->Execute("select * from zadaniya where sid=$sid AND date >= '" . $from . "' order by date DESC, id $sort");
                else {
                    $query = $db->Execute("select * from zadaniya where sid=$sid AND date <= '" . $to . "' order by date DESC, id $sort");
                }
            }
        } else {
            $q = "select * from zadaniya where sid=$sid order by date DESC, id $sort";
            $query = $db->Execute($q);
        }

        while ($res = $query->FetchRow()) {

            $task_dt = date("Y-m-d", $res['date']);
            $startTimeStamp = strtotime($task_dt);
            $endTimeStamp = strtotime(date("Y-m-d"));

            $timeDiff = abs($endTimeStamp - $startTimeStamp);

            $numberDays = $timeDiff / 86400;  // 86400 seconds in one day
            $numberDays = intval($numberDays);

            if (($numberDays >= 30) && !@$_GET['showall']) {
                continue;
            }

            $zadaniya .= file_get_contents(PATH . 'modules/user/tmp/zadaniya_one.tpl');
            $zadaniya = str_replace('[url]', substr($res['url'], 0, 30), $zadaniya);
            $zadaniya = str_replace('[id]', $res['id'], $zadaniya);
            $system = $res['sid'];
            $system = $db->Execute("select * from sayty where id=$system");
            $system = $system->FetchRow();
            $system = $system['url'];
            $zadaniya = str_replace('[etxt_id]', $res['task_id'], $zadaniya);
            $zadaniya = str_replace('[sistema]', $system, $zadaniya);
            $zadaniya = str_replace('[sistemaggl]', $res['sistema'], $zadaniya);
            $zadaniya = str_replace('[date]', date('d.m.Y', $res['date']), $zadaniya);
            $zadaniya = str_replace('[ankor]', $res['ankor'], $zadaniya);
            $zadaniya = str_replace('[tema]', $res['tema'], $zadaniya);
            $zadaniya = str_replace('[url_statyi]', $res['url_statyi'], $zadaniya);
            $new_s = "";
            if ($res['dorabotka'])
                $new_s = "in-work";
            else if ($res['vipolneno'])
                $new_s = "done";
            else if ($res['vrabote'])
                $new_s = "working";
            else if ($res['navyklad'])
                $new_s = "ready";
            else if ($res['vilojeno'])
                $new_s = "vilojeno";
            else
                $bg = '';
            $zadaniya = str_replace('[status]', $new_s, $zadaniya);

            if (@$_SESSION['admin']['id'] == 1) {
                if ($res['dorabotka'])
                    $bg = 'style="background:#f6b300"';
                else if ($res['vipolneno'])
                    $bg = 'style="background:#83e24a"';
                else if ($res['vrabote'])
                    $bg = 'style="background:#00baff"';
                else if ($res['navyklad'])
                    $bg = 'style="background:#ffde96"';
                else if ($res['vilojeno'])
                    $bg = 'style="background:#b385bf"';
                else
                    $bg = '';

                $bg = 'style="background:' . $bg . '"';
                $zadaniya = str_replace('[bg]', $bg, $zadaniya);
            }

            $zadaniya = str_replace('[etxt_status]', "", $zadaniya);
        }
        if ($zadaniya)
            $zadaniya = str_replace('[zadaniya]', $zadaniya, file_get_contents(PATH . 'modules/user/tmp/zadaniya_top.tpl'));
        else
            $zadaniya = file_get_contents(PATH . 'modules/user/tmp/no.tpl');



        $content = str_replace('[zadaniya]', $zadaniya, $content);
        $content = str_replace('[uid]', $uid, $content);
        $content = str_replace('[sid]', $sid, $content);
        $content = str_replace('[symb]', $symb, $content);
        $content = str_replace('[dfrom]', @$_POST['date-from'], $content);
        $content = str_replace('[dto]', @$_POST['date-to'], $content);
        return $content;
    }

    function zadaniya_add($db) {
        $uid = (int) $_SESSION['user']['id'];
        $sid = (int) $_GET['sid'];
        $send = $_REQUEST['send'];

        $user = $db->Execute("SELECT * FROM admins WHERE id=$uid")->FetchRow();
        $sinfo = $db->Execute("SELECT * FROM sayty WHERE id=$sid")->FetchRow();
        if (!$send) {
            //####################################################################
            //Смотрим на баланс и проверяем, может ли создать пользователь заявку (хватит ли денег)
            $cur_balans = $_SESSION['user_balans'];
            $task_cost = $sinfo['price'];
            $cur_balans -= $task_cost;
            if ($cur_balans < 0) {
                $content = file_get_contents(PATH . 'modules/user/tmp/nomoney_nohoney.tpl');
                $content = str_replace('[min_pay]', abs($cur_balans), $content);
            } else {
                $content = file_get_contents(PATH . 'modules/user/tmp/zadaniya_add.tpl');
            }

            $content = str_replace('[uid]', $uid, $content);
        } else {
            $sistema = $_REQUEST['sistema'];
            $ankor = $_REQUEST['ankor'];
            $url = $_REQUEST['url'];
            $keywords = $_REQUEST['keywords'];
            $tema = str_replace("\"", "\\\"", $_REQUEST['tema']);
            $text = $_REQUEST['text'];
            $url_statyi = $_REQUEST['url_statyi'];
            $url_pic = $_REQUEST['url_pic'];
            $price = $_REQUEST['price'];
            $comments = mysql_real_escape_string($_REQUEST['comments']);
            $admin_comments = $_REQUEST['admin_comments'];

            $vipolneno = $dorabotka = $vrabote = $navyklad = 0;
            switch ($_REQUEST['task_status']) {
                case "vipolneno":
                    $vipolneno = 1;
                    break;
                case "dorabotka":
                    $dorabotka = 1;
                    break;
                case "vrabote":
                    $vrabote = 1;
                    break;
                case "navyklad":
                    $navyklad = 1;
                    break;
            }

            $date = time();
            $db->Execute("insert into zadaniya(dorabotka, date, uid, sid, sistema, ankor, url, keywords, tema, text, url_statyi, vipolneno, price, vrabote, url_pic, navyklad, comments, admin_comments) values($dorabotka, $date, $uid, $sid, '$sistema', '$ankor', '$url', '$keywords', '$tema', '$text', '$url_statyi', $vipolneno, '$price', $vrabote, '$url_pic', $navyklad, '$comments', '$admin_comments')");
            $this->_postman->admin->userAddTask($uid, $sid, $sinfo["url"]);

            header("Location: ?module=user&action=zadaniya&uid=$uid&sid=$sid");
        }
        $content = str_replace('[login]', $user['login'], $content);
        $content = str_replace('[url]', $sinfo['url'], $content);
        return $content;
    }

    function zadaniya_edit_user($db) {

        $send = isset($_REQUEST['send']) ? $_REQUEST['send'] : NULL;
        $id = (int) $_REQUEST['id'];
        $uid = (int) $_REQUEST['uid'];
        $sid = (int) $_GET['sid'];
        $res = $db->Execute("SELECT * FROM zadaniya LEFT JOIN admins ON admins.id=zadaniya.uid WHERE zadaniya.id=$id")->FetchRow();

        if (!$send) {
            $content = file_get_contents(PATH . 'modules/user/tmp/zadaniya_edit.tpl');

            //####################################################################
            //Смотрим на баланс и проверяем, может ли создать пользователь заявку (хватит ли денег)
            $cur_balans = $_SESSION['user_balans'];
            $sinfo = $db->Execute("SELECT * FROM sayty WHERE id=$sid")->FetchRow();
            $content = str_replace('[url]', $sinfo["url"], $content);
            $task_cost = $sinfo['price'];
            $cur_balans -= $task_cost;
            if ($cur_balans < 0) {
                $nomoney = "<p>Внимание! Баланс Вашего счета недостаточен для того, чтобы задание поступило в работу!</p>
                            <p>Пожалуйста, пополните свой баланс, перейдя по <a href='/user.php?action=payments'>этой ссылке</a>.</p>
                            <p>Для того, чтобы заявка поступила в работу, минимальный платёж составит " . abs($cur_balans) . " руб.</p>";
            } else {
                $nomoney = "";
            }
            $content = str_replace('[nomoney]', $nomoney, $content);

            $res['vipolneno'] = $res['vipolneno'] ? 'checked="checked"' : '';
            $res['dorabotka'] = $res['dorabotka'] ? 'checked="checked"' : '';
            $res['vrabote'] = $res['vrabote'] ? 'checked="checked"' : '';
            $res['navyklad'] = $res['navyklad'] ? 'checked="checked"' : '';
            $res['vilojeno'] = $res['vilojeno'] ? 'checked="checked"' : '';

            $id_sistema = 0;
            if (!empty($res["b_id"])) {
                $id_sistema = $res["b_id"];
            } else if (!empty($res["sape_id"])) {
                $id_sistema = $res["sape_id"];
            } else if (!empty($res["rotapost_id"])) {
                $id_sistema = $res["rotapost_id"];
            } else if (!empty($res["miralinks_id"])) {
                $id_sistema = $res["miralinks_id"];
            } else if (!empty($res["webartex_id"])) {
                $id_sistema = $res["webartex_id"];
            }
            $content = str_replace('[id_sistema]', $id_sistema, $content);

            foreach ($res as $k => $v) {
                $content = str_replace("[$k]", $v, $content);
            }
            $content = str_replace('[uid]', $uid, $content);
            $content = str_replace('[sid]', $sid, $content);
            $content = str_replace('[tid]', $id, $content);
            if(isset($this->_options["1"]) && !empty($this->_options["1"])){
                $content = str_replace('[theme_readonly]', "", $content);
                $content = str_replace('[save_button]', '<input type="submit" class="button" value="Сохранить" />', $content);
            } else {
                $content = str_replace('[theme_readonly]', "readonly='readonly'", $content);
                $content = str_replace('[save_button]', '', $content);
            }
        } else {
            $tema = $_REQUEST['tema'];
            $db->Execute("update zadaniya set tema='$tema' where id=$id");
            $this->_postman->admin->userInsertThemesTask($this->_user, $id, $sid);

            header("Location: ?module=user&action=zadaniya&uid=$uid&sid=$sid");
        }

        return $content;
    }

    function zadaniya_del_user($db) {
        $id = $_REQUEST['id'];
        $uid = $_REQUEST['uid'];
        $sid = $_GET['sid'];

        $db->Execute("DELETE FROM zadaniya WHERE id=$id");
        header("Location: ?module=user&action=zadaniya&uid=$uid&sid=$sid");
    }

    function tickets($db) {
        $content = file_get_contents(PATH . 'modules/user/tmp/tickets_view.tpl');
        $limit = 15;
        if (isset($_GET['offset']) && !empty($_GET['offset'])) {
            $offset = (int) $_GET['offset'];
        } else {
            $offset = 1;
        }

        $uid = (int) $_SESSION['user']['id'];
        $res = $db->Execute("select * from admins where id=$uid")->FetchRow();
        $content = str_replace('[login]', $res['login'], $content);
        $content = str_replace('[uid]', $uid, $content);

        $query = $db->Execute("SELECT * FROM tickets WHERE (uid=$uid OR to_uid=$uid) ORDER BY id DESC LIMIT " . ($offset - 1) * $limit . "," . $limit);
        $all = $db->Execute("SELECT count(id) as num FROM tickets WHERE (uid=$uid OR to_uid=$uid)")->FetchRow();
        $pegination = '<div style="float:right">';
        if ($offset == 1) {
            $pegination .= '<div style="float:left">Пред.</div>';
        } else {
            $pegination .= "<div style='float:left'><a href='?action=ticket" . "&offset=" . ($offset - 1) . "'>Пред.</a></div>";
        }
        $pegination .= '<div style="float:left">&nbsp [ стр.&nbsp</div>';
        $pegination .= '<div class="select" style="width:50px;float:left;margin-top:-7px"><select name="pagerMenu" onchange="location=\'?action=ticket&offset=\'+this.options[this.selectedIndex].value+\'\'" ;="">';

        $all_zadanya = $all["num"];
        $count_pegination = ceil($all_zadanya / $limit);
        for ($i = 1; $i < $count_pegination + 1; $i++) {
            if ($i == $offset) {
                $pegination .= '<option value="' . ($i) . '" selected="selected">' . ($i) . '</option>';
            } else {
                $pegination .= '<option value="' . ($i) . '">' . ($i) . '</option>';
            }
        }
        $pegination .= '</select></div>';
        $pegination .= '&nbsp из ' . $count_pegination . ' ] &nbsp';
        if ($query->NumRows() < $limit) {
            $pegination .= "След.";
        } else {
            $pegination .= "<a href='?action=ticket" . "&offset=" . ($offset + 1) . "'>След.</a>";
        }
        $pegination .= '</div><br /><br />';
        if ($count_pegination == 1) {
            $pegination = "";
        }


        $ticket_subjects = $db->Execute("SELECT * FROM Message2008")->FetchRow();
        $content = str_replace('[ticket_subjects]', $ticket_subjects['Name'], $content);

        $ticket = "";
        while ($resw = $query->FetchRow()) {
            $ticket .= file_get_contents(PATH . 'modules/user/tmp/ticket_one.tpl');
            $ticket = str_replace('[site]', $resw['site'], $ticket);
            $ticket = str_replace('[subject]', $resw['subject'], $ticket);
            $ticket = str_replace('[q_theme]', $resw['q_theme'], $ticket);
            $ticket = str_replace('[tdate]', $resw['date'], $ticket);
            $ticket = str_replace('[tid]', $resw['id'], $ticket);
            if ($resw["to_uid"] != 0) {
                $ticket = str_replace('[display]', 'style="display:none"', $ticket);
            } else {
                $ticket = str_replace('[display]', '', $ticket);
            }
            //0 - закрыто; 1-не прочитан; 2-прочитан; 3-дан ответ;
            if ($resw['status'] == 0) {
                $ticket = str_replace('[status]', "Тема закрыта", $ticket);
                $ticket = str_replace('[status_ico]', "closed", $ticket);
            }
            if ($resw['status'] == 1) {
                $ticket = str_replace('[status]', "Не рассмотрено", $ticket);
                $ticket = str_replace('[status_ico]', "processed", $ticket);
            }
            if ($resw['status'] == 2) {
                $ticket = str_replace('[status]', "Рассматривается", $ticket);
                $ticket = str_replace('[status_ico]', "in-progress", $ticket);
            }
            if ($resw['status'] == 3) {
                $ticket = str_replace('[status]', "Дан ответ", $ticket);
                $ticket = str_replace('[status_ico]', "answered", $ticket);
            }
        }
        $content = str_replace('[pegination]', $pegination, $content);
        $content = str_replace('[tickets]', $ticket, $content);

        $zid = (int) @$_REQUEST['zid'];
        if ($zid) {
            $zinfo = $db->Execute("SELECT * FROM zadaniya WHERE id=$zid");
            $zinfo = $zinfo->FetchRow();
            $sid = $zinfo['sid'];
            $sinfo = $db->Execute("SELECT * FROM sayty WHERE id=$sid");
            $sinfo = $sinfo->FetchRow();

            $content = str_replace('[site]', $sinfo['url'], $content);
            $content = str_replace('[tid]', $zid, $content);
            $content = str_replace('[subject]', "Вопрос по задаче №$zid", $content);
            $content = str_replace('[Обработкой заявок]', "selected", $content);
        } else {
            $content = str_replace('[site]', "", $content);
            $content = str_replace('[tid]', 0, $content);
            $content = str_replace('[subject]', "", $content);
        }

        return $content;
    }

    function ticket_add($db) {
        $uid = (int) $_SESSION['user']['id'];

        $subject = $_REQUEST['subject'];
        $site = $_REQUEST['site'];
        $theme = $_REQUEST['theme'];
        $msg = substr(nl2br(htmlspecialchars(addslashes(trim($_REQUEST['msg'])))), 0, 2000);
        $cdate = date("Y-m-d");
        $zid = $_REQUEST['tid'];

        $db->Execute("INSERT INTO tickets (uid, subject, q_theme, msg, date, status, site, tid) VALUES ($uid, '$subject', '$theme', '$msg', '$cdate', 1, '$site', $zid)");
        $this->_postman->admin->ticketAdd();

        header("Location: ?module=user&action=ticket");
    }

    function ticket_edit($db) {
        $id = (int) $_REQUEST['tid'];
        $ticket = $db->Execute("select * from tickets where id=$id")->FetchRow();

        if (!isset($_REQUEST['send'])) {
            $content = file_get_contents(PATH . 'modules/user/tmp/ticket_edit.tpl');

            $ticket_subjects = $db->Execute("SELECT * FROM Message2008")->FetchRow();
            $content = str_replace('[ticket_subjects]', $ticket_subjects['Name'], $content);
            $content = str_replace("[tid]", $id, $content);

            foreach ($ticket as $k => $v) {
                if ($k == "q_theme") {
                    $content = str_replace("[$v]", "selected", $content);
                }
                $content = str_replace("[$k]", $v, $content);
            }
        } else {
            $subject = $_REQUEST['subject'];
            $site = $_REQUEST['site'];
            $theme = $_REQUEST['theme'];
            $msg = $_REQUEST['msg'];

            //$db->Execute("UPDATE tickets SET subject='$subject', q_theme='$theme', msg='$msg', site='$site' WHERE id=$id");
            //$this->_postman->admin->ticketEdit($id, $subject);

            header("Location: ?module=user&action=ticket");
        }
        return $content;
    }

    function ticket_view($db) {
        $content = file_get_contents(PATH . 'modules/user/tmp/ticket_full_view.tpl');

        $uid = (int) $_SESSION['user']['id'];
        $content = str_replace('[login]', $_SESSION['user']['login'], $content);
        $content = str_replace('[uid]', $uid, $content);

        $tid = (int) $_REQUEST['tid'];
        $res = $db->Execute("SELECT * FROM tickets WHERE (uid=$uid OR to_uid=$uid) AND id=$tid")->FetchRow();
        $last_answer = $db->Execute("SELECT * FROM answers WHERE tid=$tid ORDER BY date DESC LIMIT 1")->FetchRow();

        $view = file_get_contents(PATH . 'modules/user/tmp/ticket_chat_one.tpl');
        $view = str_replace('[msg]', $res['msg'], $view);
        $view = str_replace('[cdate]', $res['date'], $view);
        if ($res['to_uid'] > 0) {
            $view = str_replace('[from_class]', "support", $view);
            $view = str_replace('[from]', "Администрация", $view);
            if ($res['status'] == 1 && (!empty($last_answer) && $last_answer["uid"] != $uid)) {
                $db->Execute("UPDATE tickets SET status=2 WHERE id=$tid");
            }
        } else {
            $view = str_replace('[from_class]', "you", $view);
            $view = str_replace('[from]', "Вы", $view);
        }

        $answers = $db->Execute("SELECT * FROM answers WHERE tid=$tid");
        while ($resw = $answers->FetchRow()) {
            $view .= file_get_contents(PATH . 'modules/user/tmp/ticket_chat_one.tpl');

            $view = str_replace('[msg]', $resw['msg'], $view);
            $view = str_replace('[cdate]', $resw['date'], $view);
            if ($resw['uid'] == $uid) {
                $view = str_replace('[from]', "Вы", $view);
                $view = str_replace('[from_class]', "you", $view);
            } else {
                $view = str_replace('[from]', "Администрация", $view);
                $view = str_replace('[from_class]', "support", $view);
            }
        }

        if ($res['tid'] > 0) {
            $zid = $res['tid'];
            $zinfo = $db->Execute("SELECT * FROM zadaniya WHERE id=$zid")->FetchRow();
            $sid = $zinfo['sid'];
            $sinfo = $db->Execute("SELECT * FROM sayty WHERE id=$sid")->FetchRow();

            $subj = "<a href='/admin.php?module=admins&action=zadaniya&uid=" . $sinfo['uid'] . "&sid=" . $sid . "&action2=edit&id=" . $zid . "' target='_blank'>" . $res['subject'] . "</a>";
        } else {
            $subj = $res['subject'];
        }

        $content = str_replace('[chat]', $view, $content);
        $content = str_replace('[subject]', $subj, $content);
        $content = str_replace('[tid]', $tid, $content);

        return $content;
    }

    function ticket_answer($db) {
        $uid = (int) $_SESSION['user']['id'];
        $tid = (int) $_REQUEST['tid'];
        $msg = substr(nl2br(htmlspecialchars(addslashes(trim($_REQUEST['msg'])))), 0, 2000);
        $adate = date("Y-m-d H:i:s");

        if (!empty($msg)) {
            $db->Execute("UPDATE tickets SET status='1' WHERE id=$tid");
            $db->Execute("INSERT INTO answers (uid, tid, msg, date) VALUES ($uid, $tid, '$msg', '$adate')");
            $this->_postman->admin->ticketAnswer($tid, "user");

            header("Location: ?module=user&action=ticket");
        } else {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
            $content = str_replace('[alert]', "Пустой текст ответа!", $content);
            $content = str_replace('[url]', "?module=user&action=ticket&action2=view&tid=" . $tid, $content);
        }
        return $content;
    }

    function ticket_close($db) {
        $tid = (int) $_REQUEST['tid'];
        $db->Execute("UPDATE tickets SET status=0 WHERE id=$tid");
        header("Location: ?module=user&action=ticket");
    }

    function all_tasks($db) {
        $content = file_get_contents(PATH . 'modules/user/tmp/zadaniya_view_all.tpl');

        $uid = (int) $_SESSION['user']['id'];
        $query = $db->Execute("select * from admins where id=$uid");
        $res = $query->FetchRow();
        $content = str_replace('[login]', $res['login'], $content);

        $query = $db->Execute("select * from sayty where uid=$uid");
        $site_ids = array();
        while ($res = $query->FetchRow()) {
            $site_ids[] = $res['id'];
        }
        $site_ids = "(" . implode(",", $site_ids) . ")";

        $content = str_replace('[url]', $res['url'], $content);

        $limit = 15;
        $offset = 1;
        if (isset($_GET['offset']) && !empty($_GET['offset'])) {
            $offset = (int) $_GET['offset'];
        }
        $zadaniya = $pegination = '';
        if (!isset($_SESSION['sort']) || $_SESSION['sort'] == 'asc') {
            $symb = '↓';
            $sort = 'asc';
        } else {
            $symb = '↑';
            $sort = 'desc';
        }

        if (isset($_POST['date-from']) || isset($_POST['date-to'])) {
            if ($_POST['date-from'])
                $from = strtotime($db->escape($_POST['date-from']));
            else if ($_GET['date-from'])
                $from = $_GET['date-from'];
            if ($_POST['date-to'])
                $to = strtotime($db->escape($_POST['date-to']));
            else if ($_GET['date-to'])
                $to = $_GET['date-to'];

            if ($from && $to) {
                $query = $db->Execute("select * from zadaniya where sid in $site_ids AND date BETWEEN '" . $from . "' AND '" . $to . "' order by date DESC, id $sort LIMIT " . ($offset - 1) * $limit . "," . $limit);
                $all = $db->Execute("select id from zadaniya where sid in $site_ids AND date BETWEEN '" . $from . "' AND '" . $to . "' order by date DESC, id $sort");
            } else {
                if ($from) {
                    $query = $db->Execute("select * from zadaniya where sid in $site_ids AND date >= '" . $from . "' order by date DESC, id $sort LIMIT " . ($offset - 1) * $limit . "," . $limit);
                    $all = $db->Execute("select id from zadaniya where sid in $site_ids AND date >= '" . $from . "' order by date DESC, id $sort");
                } else {
                    $query = $db->Execute("select * from zadaniya where sid in $site_ids AND date <= '" . $to . "' order by date DESC, id $sort LIMIT " . ($offset - 1) * $limit . "," . $limit);
                    $all = $db->Execute("select id from zadaniya where sid in $site_ids AND date <= '" . $to . "' order by date DESC, id $sort");
                }
            }
        } else {
            if ($site_ids == "()") {
                $site_ids = "(-1)";
            }
            $query = $db->Execute("select * from zadaniya where sid in $site_ids order by date DESC, id $sort LIMIT " . ($offset - 1) * $limit . "," . $limit);
            $all = $db->Execute("SELECT count(id) as num FROM zadaniya WHERE sid in $site_ids")->FetchRow();
            $from = $to = NULL;
        }
        $all_zadanya = $all["num"];
        $count_pegination = ceil($all_zadanya / $limit);
        if ($all_zadanya > $limit) {
            $pegination = '<br /><div style="float:right">';
            if ($offset == 1) {
                $pegination .= '<div style="float:left">Пред.</div>';
            } else {
                $pegination .= "<div style='float:left'><a href='/user.php?action=all_tasks&offset=" . ($offset - 1) . ((!$from) ? '' : '&date-from=' . $from) . ((!$to) ? '' : '&date-to=' . $to) . "'>Пред.</a></div>";
            }
            $pegination .= '<div style="float:left">&nbsp [ стр.&nbsp</div>';
            $pegination .= '<div class="select" style="width:50px;float:left;margin-top:-7px"><select name="pagerMenu" onchange="location=\'/user.php?action=all_tasks' . ((!$from) ? '' : '&date-from=' . $from) . ((!$to) ? '' : '&date-to=' . $to) . '&offset=\'+this.options[this.selectedIndex].value+\'\'" ;="">';

            for ($i = 0; $i < $count_pegination; $i++) {
                if ($i + 1 == $offset) {
                    $pegination .= '<option value="' . ($i + 1) . '" selected="selected">' . ($i + 1) . '</option>';
                } else {
                    $pegination .= '<option value="' . ($i + 1) . '">' . ($i + 1) . '</option>';
                }
            }
            $pegination .= '</select></div>';
            $pegination .= '&nbsp из ' . $count_pegination . ' ] &nbsp';
            if ($query->NumRows() < $limit) {
                $pegination .= "След.";
            } else {
                $pegination .= "<a href='/user.php?action=all_tasks&offset=" . ($offset + 1) . ((!$from) ? '' : '&date-from=' . $from) . ((!$to) ? '' : '&date-to=' . $to) . "'>След.</a>";
            }
            $pegination .= '</div><br />';
        }

        while ($res = $query->FetchRow()) {

            $zadaniya .= file_get_contents(PATH . 'modules/user/tmp/zadaniya_one.tpl');
            $zadaniya = str_replace('[url]', substr($res['url'], 0, 30), $zadaniya);
            $zadaniya = str_replace('[id]', $res['id'], $zadaniya);
            $system = $res['sid'];
            $system = $db->Execute("select * from sayty where id=$system");
            $system = $system->FetchRow();
            $system = $system['url'];
            $zadaniya = str_replace('[etxt_id]', $res['task_id'], $zadaniya);
            $zadaniya = str_replace('[sistema]', $system, $zadaniya);
            $zadaniya = str_replace('[sistemaggl]', $res['sistema'], $zadaniya);
            $zadaniya = str_replace('[date]', date('d.m.Y', $res['date']), $zadaniya);
            $zadaniya = str_replace('[ankor]', $res['ankor'], $zadaniya);
            $zadaniya = str_replace('[tema]', mb_substr($res['tema'], 0, 35), $zadaniya);
            $zadaniya = str_replace('[url_statyi]', $res['url_statyi'], $zadaniya);
            $zadaniya = str_replace('[uid]', $res['uid'], $zadaniya);
            $zadaniya = str_replace('[sid]', $res['sid'], $zadaniya);
            $new_s = "";
            if ($res['dorabotka'])
                $new_s = "in-work";
            else if ($res['vipolneno'])
                $new_s = "done";
            else if ($res['vrabote'])
                $new_s = "working";
            else if ($res['navyklad'])
                $new_s = "ready";
            else if ($res['vilojeno'])
                $new_s = "vilojeno";
            else if ($res['to_remove'])
                $new_s = "to_remove";
            else if ($res['removed'])
                $new_s = "removed";
            else
                $new_s = '';
            $zadaniya = str_replace('[status]', $new_s, $zadaniya);

            if ($_SESSION['admin']['id'] == 1) {
                if ($res['dorabotka'])
                    $bg = 'style="background:#f6b300"';
                else if ($res['vipolneno'])
                    $bg = 'style="background:#83e24a"';
                else if ($res['vrabote'])
                    $bg = 'style="background:#00baff"';
                else if ($res['navyklad'])
                    $bg = 'style="background:#ffde96"';
                else if ($res['vilojeno'])
                    $bg = 'style="background:#b385bf"';
                else if ($res['to_remove'])
                    $bg = 'style="background:#FF8C69"';
                else if ($res['removed'])
                    $bg = 'style="background:#8B6969"';
                else
                    $bg = '';

                $bg = 'style="background:' . $bg . '"';
                $zadaniya = str_replace('[bg]', $bg, $zadaniya);
            }
        }
        if ($zadaniya) {
            $zadaniya = str_replace('[zadaniya]', $zadaniya, file_get_contents(PATH . 'modules/user/tmp/zadaniya_top_all.tpl'));
        } else {
            $zadaniya = file_get_contents(PATH . 'modules/user/tmp/no.tpl');
            $pegination = "";
        }


        $content = str_replace('[zadaniya]', $zadaniya, $content);
        $content = str_replace('[uid]', $uid, $content);
        $content = str_replace('[symb]', $symb, $content);
        $content = str_replace('[dfrom]', isset($_POST['date-from']) ? $_POST['date-from'] : NULL, $content);
        $content = str_replace('[dto]', isset($_POST['date-to']) ? $_POST['date-to'] : NULL, $content);
        $content = str_replace('[pegination]', $pegination, $content);
        return $content;
    }

    function go_payment($db) {
        $uid = (int) $_SESSION['user']['id'];
        $user = $db->Execute("SELECT * FROM admins WHERE id=$uid")->FetchRow();
        $limit = 10;
        if (isset($_GET['offset']) && !empty($_GET['offset'])) {
            $offset = (int) $_GET['offset'];
        } else {
            $offset = 1;
        }
        if (@$_REQUEST['promo'] && $user["type"] != "copywriter") {
            $exist = $db->Execute("SELECT * FROM Message2009 WHERE Code='" . $db->escape($_REQUEST['promo']) . "' AND ((Used=0) OR (Used IS NULL))")->FetchRow();
            if ($exist['Message_ID']) {
                $user_used = $db->Execute("SELECT * FROM promo_user WHERE user_id=$uid")->FetchRow();
                if (!$user_used) {
                    $cur_dt = date("Y-m-d H:i:s");
                    $db->Execute("INSERT INTO orders (uid, price, date, status, is_promo) VALUES ($uid, 150, '$cur_dt', 1, 1)");
                    $db->Execute("INSERT INTO promo_user (promo_id, user_id) VALUES (" . $exist['Message_ID'] . ", $uid)");
                    $this->_postman->admin->userGoPayment($uid, $user["login"], $_REQUEST['promo']);
                }
                header("Location:/user.php?action=payments");
                exit();
            }
        }

        if (@$_REQUEST['send']) {
            $price = floatval($_REQUEST['sum']);
            $cur_dt = date("Y-m-d H:i:s");
            $db->Execute("INSERT INTO orders (uid, price, date, status) VALUES ($uid, '$price', '$cur_dt', 0)");
            $res = $db->Execute("SELECT * FROM orders WHERE date='$cur_dt' AND uid=$uid AND price='$price' AND status=0")->FetchRow();
            $oid = $res['id'];


            // your registration data
            $mrh_login = "postin";      // your login here
            $mrh_pass1 = "xN567YtGhPoSz3M";   // merchant pass1 here
            // order properties
            $inv_id = intval($oid);        // shop's invoice number 
            // (unique for shop's lifetime)
            $inv_desc = "Пополнение баланса";   // invoice desc
            $inv_desc = urlencode($inv_desc);
            $out_summ = $price;   // invoice summ
            // build CRC value
            $crc = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1");

            // build URL
            $url = "https://merchant.roboxchange.com/Index.aspx?MrchLogin=$mrh_login&" .
                    "OutSum=$out_summ&InvId=$inv_id&Desc=$inv_desc&SignatureValue=$crc";

            header("Location:" . $url);
            exit();
        } else {
            $content = file_get_contents(PATH . 'modules/user/tmp/gopay.tpl');
            $order = "";
            $orders = $db->Execute("SELECT * FROM orders WHERE uid=$uid ORDER BY date DESC LIMIT " . ($offset - 1) * $limit . "," . $limit);
            $all = $db->Execute("SELECT count(*) as num FROM orders WHERE uid=$uid")->FetchRow();
            $payment_no = 1;
            $pegination = '<div style="float:right">';
            if ($offset == 1) {
                $pegination .= '<div style="float:left">Пред.</div>';
            } else {
                $pegination .= "<div style='float:left'><a href='?action=payments" . "&offset=" . ($offset - 1) . "'>Пред.</a></div>";
            }
            $pegination .= '<div style="float:left">&nbsp [ стр.&nbsp</div>';
            $pegination .= '<div class="select" style="width:50px;float:left;margin-top:-7px"><select name="pagerMenu" onchange="location=\'?action=payments&offset=\'+this.options[this.selectedIndex].value+\'\'" ;="">';

            $all_zadanya = $all["num"];
            $count_pegination = ceil($all_zadanya / $limit);
            for ($i = 1; $i < $count_pegination + 1; $i++) {
                if ($i == $offset) {
                    $pegination .= '<option value="' . ($i) . '" selected="selected">' . ($i) . '</option>';
                } else {
                    $pegination .= '<option value="' . ($i) . '">' . ($i) . '</option>';
                }
            }
            $pegination .= '</select></div>';
            $pegination .= '&nbsp из ' . $count_pegination . ' ] &nbsp';
            if ($orders->NumRows() < $limit) {
                $pegination .= "След.";
            } else {
                $pegination .= "<a href='?action=payments" . "&offset=" . ($offset + 1) . "'>След.</a>";
            }
            $pegination .= '</div><br /><br />';
            if ($count_pegination == 1) {
                $pegination = "";
            }
            while ($res = $orders->FetchRow()) {
                $payment_no++;
                $order .= file_get_contents(PATH . 'modules/user/tmp/order_one.tpl');
                $order = str_replace('[price]', $res['price'] . " руб.", $order);
                $order = str_replace('[date]', $res['date'], $order);

                if ($res['status'] == 1) {
                    $status = "<b style='color:green;'>Оплачено</b>";
                } else {
                    $status = "Не оплачено.<br/><a href='http://iforget.ru/gopay/?oid=" . $res['id'] . "&psum=" . $res['price'] . "'>Оплатить!</a>";
                }
                $order = str_replace('[status]', $status, $order);
            }
            $content = str_replace('[pegination]', $pegination, $content);
            $content = str_replace('[payment_no]', $payment_no, $content);
            $content = str_replace('[email]', $user["email"], $content);
            $content = str_replace('[uid]', $uid, $content);
            $content = str_replace('[my_payments]', $order, $content);
            $content = str_replace('[promo]', (@$_GET['promo'] ? $_GET['promo'] : ""), $content);
        }

        return $content;
    }

    function change_wallet($db) {
        $uid = (int) $_SESSION['user']['id'];
        $this->getAccess($_SESSION['user']);
        $content = file_get_contents(PATH . 'modules/user/tmp/change_wallet.tpl');
        $uinfo = $db->Execute("SELECT * FROM admins WHERE id=$uid")->FetchRow();
        $change_wallet = $db->Execute("SELECT * FROM change_wallet WHERE uid='$uid' AND status=0")->FetchRow();

        $error = $accept = "";
        if (isset($_REQUEST["error"])) {
            $error = $_REQUEST["error"];
        }
        if (isset($_REQUEST["accept"])) {
            $accept = $_REQUEST["accept"];
        }

        if (isset($_REQUEST["send"]) && $_REQUEST["send"] == 1 && isset($_POST["wallet"]) && empty($change_wallet)) {
            if (!empty($_POST["wallet"]) || (empty($_POST["wallet"]) && !empty($uinfo["wallet"]))) {
                $wallet = $_POST["wallet"];
                $exist = $db->Execute("SELECT wallet FROM admins WHERE wallet='$wallet'")->FetchRow();
                if (empty($exist) || empty($_POST["wallet"])) {
                    $date = date("Y-m-d H:i:s");
                    $code = md5($uid . $date);
                    $result = $db->Execute("INSERT INTO change_wallet (uid, wallet, date, confirm, code, status) VALUE ('" . $uid . "', '" . $wallet . "', '" . $date . "', 0, '" . $code . "', 0)");
                    $content = str_replace('[accept]', "Запрос успешно добавлен! На Вашу почту отправлено письмо с подтверждением!", $content);
                    $change_wallet = $result;
                    $this->_postman->user->confirmChangeWallet($uinfo["email"], $uinfo["login"], $wallet, $code);
                    $this->_postman->admin->userChangeWallet($uinfo);
                } else {
                    $content = str_replace('[error]', "Этот кошелек уже есть в системе!", $content);
                }
            }
        }
        if (!empty($change_wallet)) {
            $content = str_replace('[form]', "", $content);
            $content = str_replace('[display_label]', "style='display:block'", $content);
            $content = str_replace('[send]', "0", $content);
        } else {
            $form = file_get_contents(PATH . 'modules/user/tmp/forms/form_change_wallet.tpl');
            $content = str_replace('[form]', $form, $content);
            $content = str_replace('[display_label]', "style='display:none'", $content);
            $content = str_replace('[send]', "1", $content);
        }
        $table = "";
        $history = $db->Execute("SELECT * FROM change_wallet WHERE uid='$uid' ORDER BY id DESC")->GetAll();
        if (!empty($history)) {
            foreach ($history as $value) {
                switch ($value['status']) {
                    case 0: $status = $value['confirm'] == 1 ? 'Рассматривается' : 'Подтвердить по почте';
                        break;
                    case 1: $status = 'Одобрено';
                        break;
                    case 2: $status = 'Отменен';
                        break;
                    default : $status = 'Рассматривается';
                }
                $id = $value['id'];
                $table .= "<tr>"
                        . "<td>" . $value['date'] . "</td>"
                        . "<td>" . $value['wallet'] . "</td>"
                        . "<td>" . $status . "</td>"
                        . ($value['status'] != 0 ? "<td></td>" : "<td class='message'><a href='?action=change_wallet&action2=send_mail&id=$id' class='ico'></a></td>")
                        . ($value['status'] != 0 ? "<td></td>" : "<td class='close'><a onclick=\"if(confirm('Вы действительно желаете отменить заявку?')){ location.href='?action=change_wallet&action2=cancel&id=$id';}; return false;\" href=\"?action=change_wallet&action2=cancel&id=$id\" class='ico'></a></td>")
                        . "</tr>";
            }
        }
        $content = str_replace('[old_wallet]', $uinfo['wallet'], $content);
        $content = str_replace('[history]', $table, $content);
        $content = str_replace('[error]', $error, $content);
        $content = str_replace('[accept]', $accept, $content);
        return $content;
    }

    function change_wallet_cancel($db) {
        $uid = (int) $_SESSION['user']['id'];
        $this->getAccess($_SESSION['user']);

        $id = (int) $_REQUEST["id"];
        if (!empty($id)) {
            $change_wallet = $db->Execute("SELECT id FROM change_wallet WHERE uid='$uid' AND id='$id'")->FetchRow();
            if (!empty($change_wallet)) {
                $db->Execute("UPDATE change_wallet SET status='2' WHERE id='" . $change_wallet['id'] . "'");
                header("Location: /user.php?action=change_wallet&accept=Заявка отменена");
            } else {
                header("Location: /user.php?action=change_wallet&error=Нет такой заявки");
            }
        } else {
            header("Location: /user.php?action=change_wallet&error=Нет такой заявки");
        }
    }

    function change_wallet_send_mail($db) {
        $uid = (int) $_SESSION['user']['id'];
        $this->getAccess($_SESSION['user']);

        $id = (int) $_REQUEST["id"];
        if (!empty($id)) {
            $change_wallet = $db->Execute("SELECT id FROM change_wallet WHERE uid='$uid' AND id='$id'")->FetchRow();
            $uinfo = $db->Execute("SELECT * FROM admins WHERE id='$uid'")->FetchRow();
            if (!empty($change_wallet)) {
                $this->_postman->user->confirmChangeWallet($uinfo["email"], $uinfo["login"], $change_wallet["wallet"], $change_wallet["code"]);
                header("Location: /user.php?action=change_wallet&accept=Письмо отправлено");
            } else {
                header("Location: /user.php?action=change_wallet&error=Нет такой заявки");
            }
        } else {
            header("Location: /user.php?action=change_wallet&error=Нет такой заявки");
        }
    }

    function change_wallet_confirm($db) {
        $uid = (int) $_SESSION['user']['id'];
        $this->getAccess($_SESSION['user']);

        $code = $_REQUEST["code"];
        if (!empty($code)) {
            $change_wallet = $db->Execute("SELECT * FROM change_wallet WHERE uid='$uid' AND code='$code'")->FetchRow();
            if (!empty($change_wallet) && $change_wallet["confirm"] == 0) {
                $db->Execute("UPDATE change_wallet SET confirm='1' WHERE id='" . $change_wallet['id'] . "'");
                header("Location: /user.php?action=change_wallet&accept=Заявка подтверждена Вами по почте и направлена Администрации");
            } else if(empty($change_wallet)) {
                header("Location: /user.php?action=change_wallet&error=Нет такой заявки");
            } else {
                header("Location: /user.php?action=change_wallet");
            }
        } else {
            header("Location: /user.php?action=change_wallet&error=Пустой код");
        }
    }

    function getAccess($user) {
        if (empty($user)) {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/no_rights.tpl');
            $content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
            return $content;
            exit;
        }
    }

    function lk($db) {
        $uid = (int) $_SESSION['user']['id'];
        $uinfo = $db->Execute("SELECT * FROM admins WHERE id=$uid")->FetchRow();

        if (@$_REQUEST['send']) {
            $fio = $db->escape($_REQUEST['fio']);
            $fio = "fio" . $fio;
            $knowus = $db->escape($_REQUEST['knowus']);
            $pass = $db->escape($_REQUEST['password']);
            $mail_period = $db->escape($_REQUEST['mail_period']);
            $wallet = $db->escape($_REQUEST['wallet']);
            $wallet_type = $db->escape($_REQUEST['wallet_type']);
            $icq = $db->escape($_REQUEST['icq']);
            $scype = $db->escape($_REQUEST['scype']);

            if ($pass) { //, wallet='$wallet'
                $pass = md5($pass);
                $db->Execute("UPDATE admins SET pass='$pass', contacts='$fio', dostupy='$knowus', wallet_type='$wallet_type', mail_period='$mail_period', icq='$icq', scype='$scype' WHERE id=$uid");
            } else { // wallet='$wallet',
                $db->Execute("UPDATE admins SET contacts='$fio', dostupy='$knowus', wallet_type='$wallet_type', mail_period='$mail_period', icq='$icq', scype='$scype' WHERE id=$uid");
            }
            switch ($mail_period) {
                case 43200: $period = "Два раза в день";
                    break;
                case 86400: $period = "Раз в день";
                    break;
                case 259200: $period = "Раз в три дня";
                    break;
                case 604800: $period = "Раз в неделю";
                    break;
                default: $period = "Отписался от рассылки";
                    break;
            }

            $this->_postman->admin->userChangeData($uinfo);
            header("Location: /user.php?action=lk&query=Изменения сохранены");
            exit();
        } else {
            $content = file_get_contents(PATH . 'modules/user/tmp/lk.tpl');

            $content = str_replace('[query]', (isset($_REQUEST["query"]) && !empty($_REQUEST["query"]) ? $_REQUEST["query"] : ""), $content);
            $content = str_replace('[fio]', substr($uinfo['contacts'], 3), $content);
            $content = str_replace('[knowus]', $uinfo['dostupy'], $content);
            $content = str_replace('[checked_' . $uinfo["wallet_type"] . ']', "selected='selected'", $content);
            $content = str_replace('[wallet]', $uinfo['wallet'], $content);
            $content = str_replace('[checked_' . $uinfo["mail_period"] . ']', "selected='selected'", $content);
            $content = str_replace('[icq]', $uinfo['icq'], $content);
            $content = str_replace('[scype]', $uinfo['scype'], $content);
        }

        return $content;
    }

    function partnership($db) {
        $uid = (int) $_SESSION['user']['id'];

        $content = file_get_contents(PATH . 'modules/user/tmp/partnership.tpl');

        $partner_id = md5($uid . "iforget") . "_$uid";
        $partner_link = "http://iforget.ru/register.php?partner=$partner_id";
        $content = str_replace('[partner_link]', $partner_link, $content);

        $clients_from_partner = $db->Execute("SELECT * FROM partnership WHERE partner_id=$uid");
        $new_cl = array();
        while ($clients = $clients_from_partner->FetchRow()) {
            $new_cl[] = $clients['new_user_id'];
        }
        $reged_users = count($new_cl);

        if ($new_cl)
            $new_cl = "(" . implode(",", $new_cl) . ")";
        else
            $new_cl = "(0)";

        $sites_from_partner = $db->Execute("SELECT * FROM sayty WHERE uid IN $new_cl");
        $new_sites = array();
        $sids = array();
        while ($site = $sites_from_partner->FetchRow()) {
            $new_sites[] = $site;
            $sids[] = $site['id'];
        }
        $sites_num = count($new_sites);

        if ($sids)
            $sids = "(" . implode(",", $sids) . ")";
        else
            $sids = "(0)";

        $tasks_from_partner = $db->Execute("SELECT * FROM zadaniya WHERE sid IN $sids");
        $payed_tasks = array();
        while ($task = $tasks_from_partner->FetchRow()) {
            if ($task['vipolneno'] == 1)
                $payed_tasks[] = $task['id'];
        }
        $task_num = count($payed_tasks);

        $balance_of_money = $earned = 3 * $task_num;
        $derived_money = 0;
        $partnership_output_money = $db->Execute("SELECT * FROM partnership_output_money WHERE uid = $uid");
        while ($output_money = $partnership_output_money->FetchRow()) {
            if (!empty($output_money['amount']) && $output_money['amount'] != 0) {
                $derived_money += $output_money['amount'];
                $balance_of_money -= $output_money['amount'];
            }
        }
        $query = $db->Execute("SELECT * FROM admins WHERE id = $uid");
        $user = $query->FetchRow();
        $check_wallet_user = '<a href="/user.php?module=user&amp;action=output_to_purse" class="button" style="float: right;">Вывод в кошелек</a>';
        if (empty($user["wallet"]) || $user["wallet"] == "") {
            $check_wallet_user = '<button onclick="checkWalletUser()" style="float: right;">Вывод в кошелек</button>';
        }

        $content = str_replace('[check_wallet_user]', $check_wallet_user, $content);
        $content = str_replace('[reged_users]', $reged_users, $content);
        $content = str_replace('[new_sites]', $sites_num, $content);
        $content = str_replace('[payed_tasks]', $task_num, $content);
        $content = str_replace('[earned]', $earned . " руб.", $content);
        $content = str_replace('[derived_money]', $derived_money . " руб.", $content);
        $content = str_replace('[balance_of_money]', $balance_of_money . " руб.", $content);

        return $content;
    }

    function output_to_balance($db) {
        $uid = (int) $_SESSION['user']['id'];

        $clients_from_partner = $db->Execute("SELECT * FROM partnership WHERE partner_id=$uid");
        $new_cl = array();
        while ($clients = $clients_from_partner->FetchRow()) {
            $new_cl[] = $clients['new_user_id'];
        }

        if ($new_cl)
            $new_cl = "(" . implode(",", $new_cl) . ")";
        else
            $new_cl = "(0)";

        $sites_from_partner = $db->Execute("SELECT * FROM sayty WHERE uid IN $new_cl");
        $sids = array();
        while ($site = $sites_from_partner->FetchRow()) {
            $sids[] = $site['id'];
        }

        if ($sids)
            $sids = "(" . implode(",", $sids) . ")";
        else
            $sids = "(0)";

        $tasks_from_partner = $db->Execute("SELECT * FROM zadaniya WHERE vipolneno = 1 AND sid IN $sids");
        $payed_tasks = array();
        while ($task = $tasks_from_partner->FetchRow()) {
            $payed_tasks[] = $task['id'];
        }
        $task_num = count($payed_tasks);
        $earned = 3 * $task_num;
        $cur_dt = date("Y-m-d H:i:s");

        $partnership_output_money = $db->Execute("SELECT * FROM partnership_output_money WHERE uid = $uid");
        while ($output_money = $partnership_output_money->FetchRow()) {
            if (!empty($output_money['amount']) && $output_money['amount'] != 0) {
                $earned -= $output_money['amount'];
            }
        }
        if (!empty($earned) && $earned != 0) {
            $db->Execute("INSERT INTO orders (uid, price, date, status) VALUES ($uid, $earned, '$cur_dt', 1)");
            $db->Execute("INSERT INTO partnership_output_money (uid, amount, date) VALUES ($uid, $earned, '$cur_dt')");
        }

        header('location:/user.php?action=partnership');
    }

    function output_to_purse($db) {
        $uid = (int) $_SESSION['user']['id'];

        $clients_from_partner = $db->Execute("SELECT * FROM partnership WHERE partner_id=$uid");
        $new_cl = array();
        while ($clients = $clients_from_partner->FetchRow()) {
            $new_cl[] = $clients['new_user_id'];
        }

        if ($new_cl)
            $new_cl = "(" . implode(",", $new_cl) . ")";
        else
            $new_cl = "(0)";

        $sites_from_partner = $db->Execute("SELECT * FROM sayty WHERE uid IN $new_cl");
        $sids = array();
        while ($site = $sites_from_partner->FetchRow()) {
            $sids[] = $site['id'];
        }

        if ($sids)
            $sids = "(" . implode(",", $sids) . ")";
        else
            $sids = "(0)";

        $tasks_from_partner = $db->Execute("SELECT * FROM zadaniya WHERE vipolneno = 1 AND sid IN $sids");
        $payed_tasks = array();
        while ($task = $tasks_from_partner->FetchRow()) {
            $payed_tasks[] = $task['id'];
        }
        $task_num = count($payed_tasks);
        $earned = 3 * $task_num;

        $partnership_output_money = $db->Execute("SELECT * FROM partnership_output_money WHERE uid = $uid");
        while ($output_money = $partnership_output_money->FetchRow()) {
            if (!empty($output_money['amount']) && $output_money['amount'] != 0) {
                $earned -= $output_money['amount'];
            }
        }

        if (!empty($earned) && $earned != 0) {
            $subject = "[Запрос на вывод средств]";
            $theme = "Выводом средств";
            $msg = "Прошу вывести средств из партнерской программы в размере $earned руб.";
            $cdate = date("Y-m-d");

            $client = $db->Execute("SELECT * FROM admins WHERE id=$uid")->FetchRow();
            $db->Execute("INSERT INTO tickets (uid, subject, q_theme, msg, date, status, site, tid) VALUES ($uid, '$subject', '$theme', '$msg', '$cdate', 1, '', 0)");

            $this->_postman->admin->userOutputMoney($client, $earned);
            header("Location: ?module=user&action=ticket");
            exit();
        } else {
            header('location:/user.php?action=partnership');
        }
    }

    function decode_balans($db) {
        $content = file_get_contents(PATH . 'modules/user/tmp/decode_balans.tpl');
        $limit = 30;
        $offset = 1;
        $uid = (int) $_SESSION['user']['id'];
        if (isset($_GET['offset']) && !empty($_GET['offset'])) {
            $offset = (int) $_GET['offset'];
        }

        $money = $this->getUserBalans($uid, $db, 1);
        $content = str_replace('[user_balans]', $money . " руб.", $content);

        $completed = $db->Execute("SELECT * FROM completed_tasks WHERE uid=$uid AND status=0")->GetAll();
        $compl = array();
        if (!empty($completed)) {
            foreach ($completed as $value) {
                $compl[] = $value['zid'];
            }
        }
        $zadaniya = "";
        $condition = " AND (dorabotka!=0 OR vrabote!=0 OR navyklad!=0 OR vilojeno!=0 OR vipolneno!=0 OR to_remove!=0 OR removed!=0) ";
        $tasks = $db->Execute("SELECT * FROM zadaniya WHERE uid=" . $uid . " $condition ORDER BY id DESC LIMIT " . ($offset - 1) * $limit . "," . $limit);
        $all = $db->Execute("SELECT count(id) as num FROM zadaniya WHERE uid=" . $uid . " $condition ORDER BY id DESC")->FetchRow();

        $pegination = '<div style="float:right">';
        if ($offset == 1) {
            $pegination .= '<div style="float:left">Пред.</div>';
        } else {
            $pegination .= "<div style='float:left'><a href='?action=decode_balans" . "&offset=" . ($offset - 1) . "'>Пред.</a></div>";
        }
        $pegination .= '<div style="float:left">&nbsp [ стр.&nbsp</div>';
        $pegination .= '<div class="select" style="width:50px;float:left;margin-top:-7px"><select name="pagerMenu" onchange="location=\'?action=decode_balans&offset=\'+this.options[this.selectedIndex].value+\'\'" ;="">';

        $all_zadanya = $all["num"];
        $count_pegination = ceil($all_zadanya / $limit);
        for ($i = 1; $i < $count_pegination + 1; $i++) {
            if ($i == $offset) {
                $pegination .= '<option value="' . ($i) . '" selected="selected">' . ($i) . '</option>';
            } else {
                $pegination .= '<option value="' . ($i) . '">' . ($i) . '</option>';
            }
        }
        $pegination .= '</select></div>';
        $pegination .= '&nbsp из ' . $count_pegination . ' ] &nbsp';
        if ($tasks->NumRows() < $limit) {
            $pegination .= "След.";
        } else {
            $pegination .= "<a href='?action=decode_balans" . "&offset=" . ($offset + 1) . "'>След.</a>";
        }
        $pegination .= '</div><br /><br />';
        if ($count_pegination == 1) {
            $pegination = "";
        }

        while ($res = $tasks->FetchRow()) {
            $balans_status = "";
            $zadaniya .= file_get_contents(PATH . 'modules/user/tmp/decode_balans_one.tpl');
            $zadaniya = str_replace('[url]', substr($res['url'], 0, 30), $zadaniya);
            $zadaniya = str_replace('[zid]', $res['id'], $zadaniya);
            $zadaniya = str_replace('[sid]', $res['sid'], $zadaniya);
            $zadaniya = str_replace('[uid]', $res['uid'], $zadaniya);

            $system = $db->Execute("select * from sayty where id=" . $res['sid'])->FetchRow();
            $compl = $db->Execute("SELECT * FROM completed_tasks WHERE zid=" . $res['id'])->FetchRow();
            if (isset($compl['price']) && !empty($compl['price']))
                $zad_price = $compl['price'];
            else
                $zad_price = 0;

            $zadaniya = str_replace('[price]', $zad_price, $zadaniya);
            $zadaniya = str_replace('[id]', $res['id'], $zadaniya);
            $zadaniya = str_replace('[date]', date('d.m.Y', $res['date']), $zadaniya);
            $zadaniya = str_replace('[tema]', mb_substr($res['tema'], 0, 36), $zadaniya);

            $new_s = "";
            if ($res['dorabotka'])
                $new_s = "in-work";
            else if ($res['vipolneno'])
                $new_s = "done";
            else if ($res['vrabote'])
                $new_s = "working";
            else if ($res['navyklad'])
                $new_s = "ready";
            else if ($res['vilojeno'])
                $new_s = "vilojeno";
            else if ($res['to_remove'])
                $new_s = "to_remove";
            else if ($res['removed'])
                $new_s = "removed";
            else
                $new_s = '';

            $zadaniya = str_replace('[status]', $new_s, $zadaniya);
            $zadaniya = str_replace('[bg]', "", $zadaniya);
            $zadaniya = str_replace('[balans_status]', $balans_status, $zadaniya);
        }
        $content = str_replace('[pegination]', $pegination, $content);
        $content = str_replace('[zadaniya]', $zadaniya, $content);

        return $content;
    }

    function getUserBalans($uid, $db, $nocur = 0) {
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

    function getUserBalansOld($uid, $db, $nocur = 0, $return_freeze = 0) {
        $q = "SELECT SUM(price) as total FROM orders WHERE uid=$uid AND status=1";
        $balans = $db->Execute($q);
        $balans = $balans->FetchRow();
        if (!is_null($balans['total'])) {
            if (!$balans['total'])
                $balans['total'] = 0;
        }
        else {
            $balans['total'] = 0;
        }

        $sites = $db->Execute("SELECT * FROM sayty WHERE uid=$uid");
        $site_price = array();
        while ($row = $sites->FetchRow()) {
            $site_price[$row['id']] = $row['price'];
        }

        $completed = $db->Execute("SELECT * FROM completed_tasks WHERE uid=$uid AND status=0");
        $compl = array();
        if ($completed) {
            while ($row = $completed->FetchRow()) {
                $compl[] = $row['zid'];
            }
        }

        $freezed = $db->Execute("SELECT * FROM zadaniya WHERE uid=$uid");
        $freez_sum = 0;
        if ($freezed) {
            while ($row = $freezed->FetchRow()) {
                if (($row['dorabotka'] == 1) || ($row['vrabote'] == 1) || ($row['navyklad'] == 1) || ($row['vilojeno'] == 1)) {
                    if ($row['lay_out'] == 1) {
                        $freez_sum += 15;
                    } else {
                        if (!empty($row['price']) && $row['price'] != 0)
                            $freez_sum += $row['price'];
                        else
                            $freez_sum += $site_price[$row['sid']];
                    }
                } else if ($row['vipolneno'] == 1) {
                    if (in_array($row['id'], $compl)) {
                        if ($row['lay_out'] == 1) {
                            $freez_sum += 15;
                        } else {
                            if (!empty($row['price']) && $row['price'] != 0)
                                $freez_sum += $row['price'];
                            else
                                $freez_sum += $site_price[$row['sid']];
                        }
                    }
                }
            }
        }
        $fz = 0;
        if ($freez_sum > 0) {
            $fz = $freez_sum;
        }

        //Подсчитываем количество выполненных задач
        $compl_tasks = $db->Execute("SELECT * FROM completed_tasks WHERE uid=$uid AND status=1");
        $credit = 0;
        while ($row = $compl_tasks->FetchRow()) {
            $credit += $row['price'];
        }
        $balans['total'] -= $credit;

        if ($return_freeze) {
            return array("balans" => $balans['total'], "freeze" => $fz);
        }

        $balans = $balans['total'] - $fz;

        if (!$nocur)
            $balans .= "р.";

        return $balans;
    }

}

?>