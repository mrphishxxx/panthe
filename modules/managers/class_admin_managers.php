<?php

class managers {
    public $_smarty = null;
    public $_postman = null;
    
    function content($db, $smarty) {
        $this->_smarty = $smarty;
        $this->_postman = new Postman($smarty, $db);
        
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
                    $this->saveAuthHistory($db, $res);
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
    
    function saveAuthHistory($db, $client){
        $ip = $_SERVER["REMOTE_ADDR"];
        $time = $_SERVER["REQUEST_TIME"];
        $agent = $_SERVER["HTTP_USER_AGENT"];
        
        $uid = $client["id"];
        $login = $client["login"];
        
        $db->Execute("INSERT INTO history_auth (uid, login, date, ip, agent) VALUE ('$uid', '$login', '$time', '$ip', '$agent')");
        return;
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

    function tickets($db) {
        if (!@$_SESSION['manager']['id']) {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/no-rights.tpl');
            $content = str_replace('[alert]', 'Данное действие Вам недоступно', $content);
            $content = str_replace('[url]', $_SERVER["HTTP_REFERER"], $content);
            echo $content;
            exit;
        }
        $content = file_get_contents(PATH . 'modules/managers/tmp/tickets_view.tpl');
        $type = (isset($_REQUEST['type']) && $_REQUEST['type'] != "") ? $_REQUEST['type'] : null;
        $limit = 25;
        $offset = 1;
        if (isset($_GET['offset']) && !empty($_GET['offset'])) {
            $offset = (int) $_GET['offset'];
        }
        $condition = $for_pegination = "";
        $title_page = "Все тикеты";
        if (!empty($type)) {
            $title_page = "Тикеты для '$type'";
        }
        $content = str_replace('[title_page]', $title_page, $content);
        $content = str_replace('[type]', "&type=$type", $content);
        $admins_managers = array();
        $admins = $db->Execute("SELECT id, type FROM admins")->GetAll();
        foreach ($admins as $user) {
            if($user["type"] == "admin" || $user["type"] == "manager") {
                $admins_managers[] = $user['id'];
            } else {
                $users_type[$user["id"]] = $user["type"];
            }
        }
        if($type != "archive"){
            $new_tick = $db->Execute("SELECT * FROM tickets t WHERE t.status != 0")->GetAll();
        } else {
            $new_tick = $db->Execute("SELECT * FROM tickets t WHERE t.status = 0 ORDER BY id DESC")->GetAll();
        }
        $tickets = array();
        foreach ($new_tick as $ticket) {
            if(in_array($ticket["uid"], $admins_managers) && in_array($ticket["to_uid"], $admins_managers)) {
                $type_ticket = "manager";
            } elseif(in_array($ticket["uid"], $admins_managers) && isset($users_type[$ticket["to_uid"]])) {
                $type_ticket = $users_type[$ticket["to_uid"]];
            } else if(isset($users_type[$ticket["uid"]])) {
                $type_ticket = $users_type[$ticket["uid"]];
            } else {
                $type_ticket = "user";
            }
            
            if(!empty($type) && $type != "archive") {
                if($type_ticket == $type) {
                    $tickets[] = $ticket;
                }
            } else {
                $tickets[] = $ticket;
            }
            
        }
        $pegination = "";
        $tickets_all = count($tickets);
        if ($tickets_all > $limit) {
            $pegination = '<div style="float:right">';
            if ($offset == 1) {
                $pegination .= '<div style="float:left">Пред.</div>';
            } else {
                $pegination .= "<div style='float:left'><a href='?module=managers&action=ticket&type=$type&offset=" . ($offset - 1) . "'>Пред.</a></div>";
            }
            $pegination .= '<div style="float:left">&nbsp [ стр.&nbsp</div>';
            $pegination .= '<div class="select" style="width:50px;float:left;margin-top:-7px"><select name="pagerMenu" onchange="location=\'?module=managers&action=ticket&type=' . $type . '&offset=\'+this.options[this.selectedIndex].value+\'\'" ;="">';

            $count_pegination = ceil($tickets_all / $limit);
            for ($i = 0; $i < $count_pegination; $i++) {
                if ($i + 1 == $offset) {
                    $pegination .= '<option value="' . ($i + 1) . '" selected="selected">' . ($i + 1) . '</option>';
                } else {
                    $pegination .= '<option value="' . ($i + 1) . '">' . ($i + 1) . '</option>';
                }
            }
            $pegination .= '</select></div>';
            $pegination .= '&nbsp из ' . $count_pegination . ' ] &nbsp';
            if ($offset == $count_pegination) {
                $pegination .= "След.";
            } else {
                $pegination .= "<a href='?module=managers&action=ticket&type=$type&offset=" . ($offset + 1) . "'>След.</a>";
            }
            $pegination .= '</div>';
        }

        $ticket = "";
        if (!empty($tickets)) {
            $ticket_one = file_get_contents(PATH . 'modules/managers/tmp/ticket_one.tpl');
            foreach ($tickets as $key=>$resw) {
                if($offset > 1 && ($key < ($offset - 1) * $limit)) {
                    continue;
                }
                if($key == $offset * $limit) {
                    break;
                }
                
                $ticket .= $ticket_one;
                $ticket = str_replace('[site]', $resw['site'], $ticket);
                $ticket = str_replace('[subject]', $resw['subject'], $ticket);
                $ticket = str_replace('[q_theme]', $resw['q_theme'], $ticket);
                $ticket = str_replace('[tdate]', $resw['date'], $ticket);
                $ticket = str_replace('[tid]', $resw['id'], $ticket);
                $ticket = str_replace('[module]', 'managers', $ticket);

                //0 - закрыто; 1-не прочитан; 2-прочитан; 3-дан ответ;
                $status = $status_ico = "";
                switch ($resw['status']) {
                    case 0:
                        $status = "Тема закрыта";
                        $status_ico = "closed";
                        break;
                    case 1:
                        $status = "Не рассмотрено";
                        $status_ico = "processed";
                        break;
                    case 2:
                        $status = "Рассматривается";
                        $status_ico = "in-progress";
                        break;
                    case 3:
                        $status = "Дан ответ";
                        $status_ico = "answered";
                        break;
                }
                $ticket = str_replace('[status]', $status, $ticket);
                $ticket = str_replace('[status_ico]', $status_ico, $ticket);
            }
        } else {
            $ticket = "<tr><td colspan='4'>Нет ни одного тикета</td></tr>";
        }

        $content = str_replace('[tickets]', $ticket, $content);
        $content = str_replace('[pegination]', $pegination, $content);

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
        $content = file_get_contents(PATH . 'modules/managers/tmp/ticket_full_view.tpl');

        $uid = (int) @$_SESSION['manager']['id'];
        $admin = $db->Execute("select * from admins where id=$uid")->FetchRow();
        $content = str_replace('[login]', $admin['login'], $content);
        $content = str_replace('[uid]', $uid, $content);

        $tid = (int) $_REQUEST['tid'];
        $res = $db->Execute("SELECT * FROM tickets WHERE id=$tid")->FetchRow();

        $admin_and_manager = false;
        $administrations = array();
        $admins = $db->Execute("SELECT * FROM admins WHERE type='admin' OR type='manager'")->GetAll();
        foreach ($admins as $user) {
            $administrations[$user["id"]] = $user["id"];
        }

        if ($res['status'] == 1 && ($res['to_uid'] == 0 || in_array($res['to_uid'], $administrations))) {
            $db->Execute("UPDATE tickets SET status=2 WHERE id=$tid");
        }
        $last_answer = $db->Execute("SELECT * FROM answers WHERE tid=$tid ORDER BY date LIMIT 1")->FetchRow();
        if($res['status'] == 1 && !in_array($last_answer["uid"], $administrations)) {
            $db->Execute("UPDATE tickets SET status=2 WHERE id=$tid");
        }        

        if ($res['uid'] == $uid && $res['to_uid'] > 0) {
            $uinfo = $db->Execute("SELECT * FROM admins WHERE id=" . $res['to_uid'])->FetchRow();
        } else {
            $uinfo = $db->Execute("SELECT * FROM admins WHERE id=" . $res['uid'])->FetchRow();
        }
        $content = str_replace('[assigned]', "Кому: " . $uinfo["login"], $content);

        $view = file_get_contents(PATH . 'modules/managers/tmp/ticket_chat_one.tpl');
        $view = str_replace('[msg]', $res['msg'], $view);
        $view = str_replace('[cdate]', $res['date'], $view);
        if (array_search($res['uid'], $administrations) && array_search($res['to_uid'], $administrations)) {
            $admin_and_manager = true;
        }
        if (array_search($res['uid'], $administrations) && ($admin_and_manager == false || $admin_and_manager == true && $res['uid'] == $uid)) {
            $view = str_replace('[from_class]', "support", $view);
            $view = str_replace('[from]', "Администрация", $view);
        } else {
            $view = str_replace('[from_class]', "you", $view);
            $view = str_replace('[from]', $uinfo['login'] . "<br>" . $res['site'], $view);
        }


        $answers = $db->Execute("SELECT * FROM answers WHERE tid=$tid");
        while ($resw = $answers->FetchRow()) {
            $view .= file_get_contents(PATH . 'modules/managers/tmp/ticket_chat_one.tpl');

            $view = str_replace('[msg]', $resw['msg'], $view);
            $view = str_replace('[cdate]', $resw['date'], $view);
            if (array_search($resw['uid'], $administrations) && ($admin_and_manager == false || $admin_and_manager == true && $resw['uid'] == $uid)) {
                $view = str_replace('[from]', "Администрация", $view);
                $view = str_replace('[from_class]', "support", $view);
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

    function ticket_add($db) {
        if (!@$_SESSION['manager']['id']) {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/no-rights.tpl');
            $content = str_replace('[url]', $_SERVER["HTTP_REFERER"], $content);
            echo $content;
            exit();
        }

        $uid = (int) $_SESSION['manager']['id'];
        $content = file_get_contents(PATH . 'modules/managers/tmp/ticket_create.tpl');
        $type = isset($_REQUEST['type']) ? "type='" . $_REQUEST['type'] . "' AND " : "";
        $to_user = (int) (isset($_REQUEST['uid'])) ? $_REQUEST['uid'] : NULL;
        $title_page = "";
        if (!empty($type)) {
            switch ($_REQUEST['type']) {
                case "user" :
                    $title_page = "для Пользователя";
                    break;
                case "moder" :
                    $title_page = "для Модератора";
                    break;
                case "copywriter" :
                    $title_page = "для Копирайтера";
                    break;
                case "admin" :
                    $title_page = "для Администрации";
                    break;
                case "manager" :
                    $title_page = "для Менеджера";
                    break;
                default : $type = "";
            }
        }
        $content = str_replace('[title_page]', $title_page, $content);

        if (isset($_POST) && !empty($_POST)) {
            $to = $_REQUEST['ticket_to'];
            $subject = $_REQUEST['subject'];
            $site = $_REQUEST['site'];
            $theme = $_REQUEST['theme'];
            $msg = substr(nl2br(htmlspecialchars(addslashes(trim($_REQUEST['msg'])))), 0, 1000);
            $cdate = date("Y-m-d");
            $zid = (intval($_REQUEST['tid']) > 0 ? $_REQUEST['tid'] : 0);

            $user = $db->Execute("SELECT * FROM admins WHERE id=$to")->FetchRow();
            $db->Execute("INSERT INTO tickets (uid, subject, q_theme, msg, date, status, site, tid, to_uid) VALUES ($uid, '$subject', '$theme', '$msg', '$cdate', 1, '$site', $zid, $to)");
            $lastId = $db->Insert_ID();

            if ($user["type"] == "copywriter") {
                $this->_postman->copywriter->ticketAdd($user['email'], $user['login'], $lastId);
            } elseif($user["mail_period"] > 0) {
                $this->_postman->user->ticketAdd($user['email'], $user['login'], $lastId);
            }

            $this->_postman->admin->ticketAdd();
            header("Location: ?module=managers&action=ticket");
            exit();
        }

        $res = $db->Execute("select * from admins where id=$uid")->FetchRow();
        $content = str_replace('[login]', $res['login'], $content);
        $content = str_replace('[uid]', $uid, $content);

        $query = $db->Execute("SELECT * FROM admins where $type id<>$uid ORDER BY login, id DESC");

        $ticket_subjects = $db->Execute("SELECT * FROM Message2008")->FetchRow();
        $content = str_replace('[ticket_subjects]', $ticket_subjects['Name'], $content);
        $content = str_replace('[site]', "", $content);
        $content = str_replace('[subject]', "", $content);

        $ticket = "";
        $ticket_to = "";
        while ($resw = $query->FetchRow()) {
            if (!empty($to_user) && $to_user == $resw['id'])
                $ticket_to .= "<option value='" . $resw['id'] . "' selected>" . $resw['login'] . "</option>";
            else
                $ticket_to .= "<option value='" . $resw['id'] . "'>" . $resw['login'] . "</option>";
        }
        $content = str_replace('[ticket_to]', $ticket_to, $content);

        return $content;
    }

    function ticket_edit($db) {

        $send = $_REQUEST['send'];
        $id = (int) $_REQUEST['tid'];
        $uid = (int) $_SESSION['manager']['id'];
        $query2 = $db->Execute("select * from tickets where id=$id");
        $res = $query2->FetchRow();

        if (!$send) {
            $content = file_get_contents(PATH . 'modules/managers/tmp/ticket_edit.tpl');
            foreach ($res as $k => $v) {
                if ($k == "q_theme") {
                    $content = str_replace("[$v]", "selected", $content);
                }
                $content = str_replace("[$k]", $v, $content);
                $content = str_replace("[tid]", $id, $content);
            }
        } else {
            $subject = $_REQUEST['subject'];
            $site = $_REQUEST['site'];
            $theme = $_REQUEST['theme'];
            $msg = $_REQUEST['msg'];

            $db->Execute("UPDATE tickets SET subject='$subject', q_theme='$theme', msg='$msg', site='$site' WHERE id=$id");

            $alert = 'Тикет успешно отредактирован.';
            $url = "?module=managers&action=ticket";

            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
            $content = str_replace('[alert]', $alert, $content);
            $content = str_replace('[url]', $url, $content);
        }
        return $content;
    }

    function ticket_answer($db) {
        $uid = (int) $_SESSION['manager']['id'];
        $tid = (int) $_REQUEST['tid'];
        $msg = substr(nl2br(htmlspecialchars(addslashes(trim($_REQUEST['msg'])))), 0, 2000);
        $adate = date("Y-m-d H:i:s");
        
        if (!empty($uid)) {
            $db->Execute("INSERT INTO answers (uid, tid, msg, date) VALUES ($uid, $tid, '$msg', '$adate')");
            $db->Execute("UPDATE tickets SET status=3 WHERE id=$tid");

            $res = $db->Execute("SELECT * FROM tickets WHERE id=$tid")->FetchRow();
            $client = $db->Execute("SELECT * FROM admins WHERE id=" . $res['uid'])->FetchRow();
            if ($client["mail_period"] > 0 && $client["type"] == "copywriter") {
                $this->_postman->copywriter->ticketAnswer($client['email'], $client['login'], $tid);
            } elseif($client["mail_period"] > 0) {
                $this->_postman->user->ticketAnswer($client['email'], $client['login'], $tid);
            }
            header("Location: ?module=managers&action=ticket&action2=view&tid=$tid");
            exit();
        } else {
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
    }

    function ticket_close($db) {
        $tid = (int) $_REQUEST['tid'];
        $db->Execute("UPDATE tickets SET status=0 WHERE id=$tid");
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
        $content = str_replace('[url]', "?module=managers&action=ticket", $content);
        return $content;
    }
}