<?php

class managers {

    function content($db) {
        $GLOBAL = array();
        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
        $action2 = isset($_REQUEST['action2']) ? $_REQUEST['action2'] : '';

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
                    case 'create_ticket':
                        $content = $this->ticket_add($db);
                        break;
                }
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
                if ($res['active'] != 1) {
                    $error = 'Аккаунт не активирован.';
                } else if ($res['type'] != "manager") {
                    $error = "У Вас нет прав!";
                } else {
                    $url = $_SERVER["REQUEST_URI"];
                    $this->GLOBAL['manager'] = $res;
                    $_SESSION['manager'] = $res;
                    setcookie("iforget_manager", $res['id'], time() + 60 * 60 * 24);
                    header('location:' . $url);
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
            /* if(isset($_REQUEST['mail_period']) && !empty($_REQUEST['mail_period']))
              $mail_period = 0;
              else
              $mail_period = 1; */


            if ($pass && $confpass && $pass == $confpass) {
                $pass = md5($pass);
                $db->Execute("UPDATE admins SET pass='$pass', email='$email', contacts='$fio', dostupy='$knowus', wallet_type='$wallet_type', wallet='$wallet', mail_period='$mail_period', icq='$icq', scype='$scype' WHERE id=$uid");
            } else
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

    function ticket_view($db) {
        if (!@$_SESSION['manager']['id']) {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/no-rights.tpl');
            $content = str_replace('[alert]', 'Данное действие Вам недоступно', $content);
            $content = str_replace('[url]', $_SERVER["HTTP_REFERER"], $content);
            echo $content;
            exit;
        }
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/ticket_full_view.tpl');
        
        $uid = (int) @$_SESSION['manager']['id'];
        $admin = $db->Execute("select * from admins where id=$uid")->FetchRow();
        $content = str_replace('[login]', $admin['login'], $content);
        $content = str_replace('[uid]', $uid, $content);

        $tid = (int) $_REQUEST['tid'];
        $res = $db->Execute("SELECT * FROM tickets WHERE id=$tid")->FetchRow();

        if ($res['status'] == 1 && $res['uid'] == $uid) {
            $db->Execute("UPDATE tickets SET status=2 WHERE id=$tid");
        }

        if ($res['uid'] == $uid && $res['to_uid'] > 0) {
            $uinfo = $db->Execute("select * from admins where id=" . $res['to_uid'])->FetchRow();
        } else {
            $uinfo = $db->Execute("select * from admins where id=" . $res['uid'])->FetchRow();
        }
        $content = str_replace('[assigned]', "Кому: " . $uinfo["login"], $content);

        $view = file_get_contents(PATH . 'modules/admins/tmp/admin/ticket_chat_one.tpl');
        $view = str_replace('[msg]', $res['msg'], $view);
        $view = str_replace('[cdate]', $res['date'], $view);

        if ($res['uid'] == $uid && $res['to_uid'] > 0) {
            $view = str_replace('[from_class]', "support", $view);
            $view = str_replace('[from]', "Я", $view);
        } else if($res['to_uid'] == $uid){
            $view = str_replace('[from_class]', "you", $view);
            $view = str_replace('[from]', "Администрация", $view);
        } else {
            $view = str_replace('[from_class]', "you", $view);
            $view = str_replace('[from]', $uinfo['login'] . "<br>" . $res['site'], $view);
        }
        $admins = array();
        $admins_admin = $db->Execute("SELECT id FROM admins WHERE type = 'admin'");
        while ($user = $admins_admin->FetchRow()) {
            $admins[] = $user['id'];
        }
        
        $answers = $db->Execute("SELECT * FROM answers WHERE tid=$tid");
        while ($resw = $answers->FetchRow()) {
            $view .= file_get_contents(PATH . 'modules/admins/tmp/admin/ticket_chat_one.tpl');

            $view = str_replace('[msg]', $resw['msg'], $view);
            $view = str_replace('[cdate]', $resw['date'], $view);
            if ($resw['uid'] == $uid) {
                $view = str_replace('[from]', "Я", $view);
                $view = str_replace('[from_class]', "support", $view);
            } else if(in_array ($resw['uid'], $admins)){
                $view = str_replace('[from_class]', (($res['to_uid'] == $uid || $res['uid'] == $uid) ? "you" : "support"), $view);
                $view = str_replace('[from]', "Администрация", $view);
            } else {
                $view = str_replace('[from]', $uinfo['login'] . "<br>" . $res['site'], $view);
                $view = str_replace('[from_class]', "you", $view);
            }
        }


        if ($res['tid'] > 0) {
            $zid = $res['tid'];
            $zinfo = $db->Execute("SELECT * FROM zadaniya WHERE id=$zid")->FetchRow();
            if (!empty($zinfo)) {
                $sid = $zinfo['sid'];
                $sinfo = $db->Execute("SELECT * FROM sayty WHERE id=$sid")->FetchRow();
                $subj = "<a href='?module=admins&action=zadaniya&uid=" . $sinfo['uid'] . "&sid=" . $sid . "&action2=edit&id=" . $zid . "' target='_blank'>" . $res['subject'] . "</a>";
            }
        } else {
            $subj = $res['subject'];
        }

        $content = str_replace('[chat]', $view, $content);
        $content = str_replace('[subject]', $subj, $content);
        $content = str_replace('[tid]', $tid, $content);

        return $content;
    }

}

?>