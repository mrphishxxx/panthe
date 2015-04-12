<?php

class managers {

    function content($db) {
        $GLOBAL = array();
        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
        //$action2 = isset($_REQUEST['action2']) ? $_REQUEST['action2'] : '';

        switch (@$action) {
            case 'logout':
                $content = $this->logout();
                break;
            case 'login':
                $content = $this->login($db);
                break;
            case 'lk':
                $content = $this->lk($db);
                break;
        }


        $GLOBAL['content'] = $content;
        return $GLOBAL;
    }

    function login($db) {
        $send = @$_REQUEST['send'];
        $error = '';
        
        if ($send and $_REQUEST['login'] and $_REQUEST['pass']) {
            $login = $_REQUEST['login'];
            $pass = md5($_REQUEST['pass']);
            $res = $db->Execute("select * from admins where login='$login' and pass='$pass' and type = 'manager'")->FetchRow();
            
            if (!$res) {
                $error = 'Логин или пароль введены неверно.';
            } else {
                if ($res['active'] != 1){
                    $error = 'Аккаунт не активирован.';
                } else if ($res['type'] != "manager") {
                    $error = "У Вас нет прав!";
                } else {
                    $url = $_SERVER["REQUEST_URI"];
                    $this->GLOBAL['manager'] = $res;
                    $_SESSION['manager'] = $res;
                    setcookie("iforget_manager", $res['id'], time() + 60 * 60 * 24);
                    header('location:'.$url);
                    exit;
                }
            }
        }
        $content = file_get_contents(PATH . 'modules/admin_auth/tmp/login.html');
        $content = str_replace('[error]', $error, $content);
        echo $content;
    }

    function logout() {
        unset($_SESSION['manager']);
        $cur_exp = time() - 3600;
        setcookie("iforget_manager", "0", $cur_exp);
        header('location: /');
        exit;
    }

    function lk($db) {
        
        $uid = (int) $_SESSION['manager']['id'];
        $uinfo = $db->Execute("SELECT * FROM admins WHERE id=$uid")->FetchRow();
        if (@$_REQUEST['send']) {
            $fio = $db->escape($_REQUEST['fio']);
            $knowus = $db->escape($_REQUEST['knowus']);
            $pass = $db->escape($_REQUEST['password']);
            $confpass = $db->escape($_REQUEST['confpass']);
            $wallet = $db->escape($_REQUEST['wallet']);
            $wallet_type = $db->escape($_REQUEST['wallet_type']);
            $icq = $db->escape($_REQUEST['icq']);
            $scype = $db->escape($_REQUEST['scype']);
            $email = $db->escape($_REQUEST['email']);
            $mail_period = $uinfo["mail_period"];
            /*if(isset($_REQUEST['mail_period']) && !empty($_REQUEST['mail_period']))
                $mail_period = 0; 
            else
                $mail_period = 1;*/
            
            
            if ($pass && $confpass && $pass == $confpass) {
                $pass = md5($pass);
                $db->Execute("UPDATE admins SET pass='$pass', email='$email', contacts='$fio', dostupy='$knowus', wallet_type='$wallet_type', wallet='$wallet', mail_period='$mail_period', icq='$icq', scype='$scype' WHERE id=$uid");
            }
            else
                $db->Execute("UPDATE admins SET email='$email', contacts='$fio', dostupy='$knowus', wallet_type='$wallet_type', wallet='$wallet', mail_period='$mail_period', icq='$icq', scype='$scype' WHERE id=$uid");

            header("Location: ?module=managers&action=lk");
            exit();
        } else {
            $content = file_get_contents(PATH . 'modules/managers/tmp/lk.tpl');

            $content = str_replace('[fio]', $uinfo['contacts'], $content);
            $content = str_replace('[knowus]', $uinfo['dostupy'], $content);
            $content = str_replace('[checked_' . $uinfo["wallet_type"] . ']', "selected='selected'", $content);
            $content = str_replace('[wallet]', $uinfo['wallet'], $content);
            //$content = str_replace('[mail_period]', (($uinfo["mail_period"] == 0)?"checked='checked'":""), $content);
            $content = str_replace('[icq]', $uinfo['icq'], $content);
            $content = str_replace('[scype]', $uinfo['scype'], $content);
            $content = str_replace('[login]', $uinfo['login'], $content);
            $content = str_replace('[email]', $uinfo['email'], $content);
        }

        return $content;
    }
    
}

?>