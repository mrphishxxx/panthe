<?php

include PATH . '/helpers/Helper.php';

class copywriter {

    public $_smarty = null;
    public $_postman = null;

    function content($db, $smarty) {
        $this->_smarty = $smarty;
        $this->_postman = new Postman($smarty, $db);

        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
        $action2 = isset($_REQUEST['action2']) ? $_REQUEST['action2'] : '';

        switch (@$action) {
            case '':
                $content = $this->dashboard($db);
                break;
            case 'lk':
                $content = $this->lk($db);
                break;
            case 'unsubscribe':
                $content = $this->unsubscribe($db);
                break;
            case 'dashboard':
                $content = $this->dashboard($db);
                break;
            case 'login':
                $content = $this->login($db);
                break;
            case 'logout':
                $content = $this->logout($db);
                break;
            case 'tasks':
                switch (@$action2) {
                    case '':
                        $content = $this->tasks($db);
                        break;
                    case 'edit':
                        $content = $this->task_edit($db);
                        break;
                    case 'add':
                        $content = $this->task_add($db);
                        break;
                    case 'delete':
                        $content = $this->task_delete($db);
                        break;
                    case 'view':
                        $content = $this->task_view($db);
                        break;
                }
                break;
            case 'chat':
                switch (@$action2) {
                    case 'send_message':
                        $content = $this->chat_send_message($db);
                        break;
                }
                break;
            case 'statistics':
                switch (@$action2) {
                    case 'done_tasks':
                        $content = $this->statistics_done_tasks($db);
                        break;
                }
                break;
            case 'help':
                switch (@$action2) {
                    case 'work':
                        $content = $this->help_work();
                        break;
                }
                break;
            case 'money':
                switch (@$action2) {
                    case 'output':
                        $content = $this->money_output($db);
                        break;
                }
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
        }

        return $content;
    }

    function login($db) {
        $error = '';
        if (@$_REQUEST['login'] && @$_REQUEST['pass']) {
            $login = $_REQUEST['login'];
            $pass = md5($_REQUEST['pass']);
            $query = $db->Execute("select * from admins where (email='$login' OR login='$login') and pass='$pass' and type='copywriter'");
            if (!$res = $query->FetchRow()) {
                $error = 'Логин или пароль введены неверно. <br>Либо данный аккаунт не принадлежит копирайтеру.';
            } else {
                if ($res['active'] != 1)
                    $error = 'Аккаунт не активирован.';
                else {
                    $this->saveAuthHistory($db, $res);
                    $_SESSION['user'] = $res;
                    setcookie("iforget_ok", $res['id'], time() + 60 * 60 * 24 * 30);
                    header('location:/copywriter.php');
                    exit;
                }
            }
        } elseif (isset($_COOKIE) && isset($_COOKIE['iforget_ok'])) {
            $query = $db->Execute("select * from admins where type='copywriter' AND id='" . $_COOKIE['iforget_ok'] . "' LIMIT 1");

            if (!$res = $query->FetchRow()) {
                $error = 'Пользователь не существует!';
            } else {
                if ($res['active'] != 1)
                    $error = 'Аккаунт не активирован.';
                else {
                    $this->saveAuthHistory($db, $res);
                    $_SESSION['user'] = $res;
                    header('location:/copywriter.php');
                    exit;
                }
            }
        }
        $content = file_get_contents(PATH . 'modules/copywriter/tmp/login.tpl');
        if (empty($error))
            $content = str_replace('[error]', "", $content);
        else {
            $content = str_replace('[error]', $error, $content);
        }
        echo $content;
    }

    function logout() {
        unset($_SESSION['user']);
        $cur_exp = time() - 3600;
        setcookie("iforget_ok", "0", $cur_exp);
        header('location:/');
        exit;
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
        header("Location: /copywriter.php?action=lk&query=$query");
    }

    function lk($db) {
        $uid = (int) $_SESSION['user']['id'];
        $uinfo = $db->Execute("SELECT * FROM admins WHERE id=$uid")->FetchRow();

        if (@$_REQUEST['send']) {
            $fio = $db->escape($_REQUEST['fio']);
            $pass = $db->escape($_REQUEST['password']);
            $confpass = $db->escape($_REQUEST['confpass']);
            $wallet = $db->escape($_REQUEST['wallet']);
            $wallet_type = $db->escape($_REQUEST['wallet_type']);
            $icq = $db->escape($_REQUEST['icq']);
            $scype = $db->escape($_REQUEST['scype']);
            $mail_period = (isset($_REQUEST['mail_period']) && !empty($_REQUEST['mail_period'])) ? 0 : 1;

            /* if (!empty($wallet)) {
              $wallet_model = $db->Execute("SELECT * FROM admins WHERE wallet='" . $wallet . "' AND id != $uid")->FetchRow();
              if (!empty($wallet_model)) {
              $error = "Не возможно сохранить профиль. Копирайтер с таким кошельком уже существует в системе!";
              header("Location: /copywriter.php?action=lk&error=$error");
              exit();
              }
              } else {
              $error = "Поле Кошелек обязателен для заполнения!";
              header("Location: /copywriter.php?action=lk&error=$error");
              exit();
              } */

            if ($pass) {
                $pass = md5($pass);
                $confpass = md5($confpass);
                if ($pass === $confpass) { //wallet='$wallet',
                    $db->Execute("UPDATE admins SET pass='$pass', contacts='$fio', wallet_type='$wallet_type',  icq='$icq', scype='$scype', mail_period='$mail_period' WHERE id=$uid");
                } else {
                    header("Location: /copywriter.php?action=lk&error=Пароли не совпадают");
                    exit();
                }
            } else { //wallet='$wallet', 
                $db->Execute("UPDATE admins SET contacts='$fio', wallet_type='$wallet_type', icq='$icq', scype='$scype', mail_period='$mail_period' WHERE id=$uid");
            }

            $this->_postman->admin->copywriterChangeData($uinfo);
            header("Location: /copywriter.php?action=lk&query=Изменения сохранены");
            exit();
        } else {
            $content = file_get_contents(PATH . 'modules/copywriter/tmp/lk.tpl');
            $query = @$_REQUEST['query'];
            $error = @$_REQUEST['error'];

            $content = str_replace('[login]', $uinfo['login'], $content);
            $content = str_replace('[email]', $uinfo['email'], $content);
            $content = str_replace('[fio]', $uinfo['contacts'], $content);
            $content = str_replace('[checked_' . $uinfo["wallet_type"] . ']', "selected='selected'", $content);
            $content = str_replace('[wallet]', $uinfo['wallet'], $content);
            $content = str_replace('[icq]', $uinfo['icq'], $content);
            $content = str_replace('[scype]', $uinfo['scype'], $content);
            $content = str_replace('[query]', (!empty($query) ? $query : ""), $content);
            $content = str_replace('[error]', (!empty($error) ? $error : ""), $content);
            $content = str_replace('[mail_period]', (($uinfo["mail_period"] == 0) ? "checked='checked'" : ""), $content);
        }
        return $content;
    }

    function dashboard($db) {
        $content = file_get_contents(PATH . 'modules/copywriter/tmp/dashboard.tpl');
        $condition = " AND rectificate='0' AND vrabote='0' AND vipolneno='0' AND dorabotka='0' AND navyklad='0' AND vilojeno='0'";
        $uid = $_SESSION['user']['id'];
        $user = $db->Execute("SELECT * FROM admins WHERE id=$uid")->FetchRow();
        $for_sape = $db->Execute("SELECT * FROM zadaniya_new WHERE 
            tema != '' AND  
            for_copywriter=1 AND 
            etxt=0 AND 
            copywriter=0 AND 
            (sape_id IS NOT NULL AND sape_id != 0) AND 
            sistema = 'http://pr.sape.ru/' 
            $condition ORDER BY date ASC, id DESC")->GetAll();

        $for_burse = $db->Execute("SELECT * FROM zadaniya WHERE 
            tema != '' AND  
            for_copywriter=1 AND 
            etxt=0 AND 
            copywriter=0 
            $condition ORDER BY date ASC, id DESC")->GetAll();
        $all = array_merge($for_sape, $for_burse);

        $sites = array();
        $sites_model = $db->Execute("SELECT * FROM sayty")->GetAll();
        foreach ($sites_model as $site) {
            $sites[$site["id"]] = $site;
        }

        $prohibition_tasks = array();
        $prohibition_taking_tasks = $db->Execute("SELECT * FROM prohibition_taking_tasks WHERE user_id=$uid");
        while ($res = $prohibition_taking_tasks->FetchRow()) {
            $prohibition_tasks[] = $res["task_id"];
        }

        $table = "";
        $num_task = 0;
        if ($user["banned"] == 0) {
            foreach ($all as $res) {
                if (!in_array($res["id"], $prohibition_tasks)) {
                    $num_task++;
                    switch ($res["type_task"]) {
                        case 0: $type = "Статья";
                            break;
                        case 1: $type = "Обзор";
                            break;
                        case 2: $type = "Новость";
                            break;
                        default : $type = "Статья";
                    }
                    $price = (($res["sid"] != 1 && isset($sites[$res["sid"]]["cena"]) && !empty($sites[$res["sid"]]["cena"])) ? $sites[$res["sid"]]["cena"] : $res["price"]);
                    $tr = "<tr>";
                    $tr .= "<td><a href='/copywriter.php?action=tasks&action2=view&id=" . $res["id"] . (!isset($res["from_sape"]) ? "&burse=1" : "") . "'>" . mb_substr($res["tema"], 0, 35) . "</a></td>";
                    $tr .= "<td>" . $res["nof_chars"] . "</td>";
                    $tr .= "<td>" . $type . "</td>";
                    $tr .= "<td>" . Helper::textQuality($price) . "</td>";
                    $tr .= "<td>" . $this->getPriceCopywriter($price, $res["nof_chars"]) . " руб.</td>";
                    $tr .= "<td class='add'><a href='/copywriter.php?action=tasks&action2=add&id=" . $res["id"] . (!isset($res["from_sape"]) ? "&burse=1" : "") . "' class='ico'></a></td>";
                    $tr .= "</tr>";
                    $table .= $tr;
                }
            }
        }

        if ($table == "") {
            $table = "<tr><td colspan='5'>Нет новых задач</td></tr>";
        }
        
        if($_SESSION['user']['trust'] == 0) {
            $other_tasks_sape = $db->Execute("SELECT * FROM zadaniya_new WHERE copywriter='" . $_SESSION['user']['id'] . "' AND vrabote=1");
            $other_tasks_burse = $db->Execute("SELECT * FROM zadaniya WHERE copywriter='" . $_SESSION['user']['id'] . "' AND vrabote=1");
            $count_tasks = ($other_tasks_sape->NumRows()) + ($other_tasks_burse->NumRows());

            if($count_tasks >= 5) {
                $content = str_replace('[count_vrabote]', "1", $content);
            } else {
                $content = str_replace('[count_vrabote]', "0", $content);
            }
        } else {
            $content = str_replace('[count_vrabote]', "0", $content);
        }

        if (isset($_REQUEST['error'])) {
            $content = str_replace('[error]', "<p class='error_to_copywrite'>" . $_REQUEST['error'] . "</p>", $content);
        } else {
            $content = str_replace('[error]', "", $content);
        }
        $content = str_replace('[table]', $table, $content);
        return $content;
    }

    function tasks($db) {
        $content = file_get_contents(PATH . 'modules/copywriter/tmp/tasks.tpl');
        $uid = $_SESSION['user']['id'];
        $limit = 25;
        $offset = 1;
        $pegination = "";
        if (isset($_GET['offset']) && !empty($_GET['offset'])) {
            $offset = (int) $_GET['offset'];
        }

        if (isset($_GET["status_z"])) {
            if ($_GET["status_z"] == "all") {
                $tasks_sape = $db->Execute("SELECT * FROM zadaniya_new WHERE copywriter=$uid ORDER BY date DESC, id DESC")->GetAll();
                $tasks_burse = $db->Execute("SELECT * FROM zadaniya WHERE copywriter=$uid ORDER BY date DESC, id DESC")->GetAll();
                $all = $tasks = array_merge($tasks_sape, $tasks_burse);
            } else {
                switch ($_GET["status_z"]) {
                    case "vipolneno":
                        $condition = " AND vipolneno = 1";
                        break;
                    case "dorabotka":
                        $condition = " AND rework = 1";
                        break;
                    case "vilojeno":
                        $condition = " AND (vilojeno = 1 OR (dorabotka = 1 AND rework = 0))";
                        break;
                    case "navyklad":
                        $condition = " AND navyklad = 1";
                        break;
                    case "vrabote":
                        $condition = " AND vrabote = 1";
                        break;
                    default: $condition = "";
                        break;
                }

                $tasks_sape = $db->Execute("SELECT * FROM zadaniya_new WHERE copywriter='$uid' $condition ORDER BY date DESC, id DESC LIMIT " . ($offset - 1) * $limit . "," . $limit)->GetAll();
                $tasks_burse = $db->Execute("SELECT * FROM zadaniya WHERE copywriter='$uid' $condition ORDER BY date DESC, id DESC LIMIT " . ($offset - 1) * $limit . "," . $limit)->GetAll();
                $tasks = array_merge($tasks_sape, $tasks_burse);

                $all_sape = $db->Execute("SELECT * FROM zadaniya_new WHERE copywriter='$uid' $condition ORDER BY date DESC, id DESC")->GetAll();
                $all_burse = $db->Execute("SELECT * FROM zadaniya WHERE copywriter='$uid' $condition ORDER BY date DESC, id DESC")->GetAll();
                $all = array_merge($all_sape, $all_burse);
            }
        } else {
            $tasks_sape = $db->Execute("SELECT * FROM zadaniya_new WHERE copywriter='$uid' ORDER BY date DESC, id DESC LIMIT " . ($offset - 1) * $limit . "," . $limit)->GetAll();
            $tasks_burse = $db->Execute("SELECT * FROM zadaniya WHERE copywriter='$uid' ORDER BY date DESC, id DESC LIMIT " . ($offset - 1) * $limit . "," . $limit)->GetAll();
            $tasks = array_merge($tasks_sape, $tasks_burse);

            $all_sape = $db->Execute("SELECT * FROM zadaniya_new WHERE copywriter='$uid' ORDER BY date DESC, id DESC")->GetAll();
            $all_burse = $db->Execute("SELECT * FROM zadaniya WHERE copywriter='$uid' ORDER BY date DESC, id DESC")->GetAll();
            $all = array_merge($all_sape, $all_burse);
        }

        $sites = array();
        $sites_model = $db->Execute("SELECT * FROM sayty")->GetAll();
        foreach ($sites_model as $site) {
            $sites[$site["id"]] = $site;
        }

        if (!isset($_GET["status_z"]) || (isset($_GET["status_z"]) && $_GET["status_z"] != "all")) {
            $pegination = '<div style="float:right">';
            if ($offset == 1) {
                $pegination .= '<div style="float:left">Пред.</div>';
            } else {
                $pegination .= "<div style='float:left'><a href='/copywriter.php?action=tasks" . (isset($_GET["status_z"]) ? '&status_z=' . $_GET["status_z"] : '') . "&offset=" . ($offset - 1) . "'>Пред.</a></div>";
            }
            $pegination .= '<div style="float:left">&nbsp [ стр.&nbsp</div>';
            $pegination .= '<div class="select" style="width:50px;float:left;margin-top:-7px"><select name="pagerMenu" onchange="location=\'/copywriter.php?action=tasks' . (isset($_GET["status_z"]) ? '&status_z=' . $_GET["status_z"] : '') . '&offset=\'+this.options[this.selectedIndex].value+\'\'" ;="">';

            $all_zadanya = count($all);
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
            if (count($tasks) < $limit) {
                $pegination .= "След.";
            } else {
                $pegination .= "<a href='/copywriter.php?action=tasks" . (isset($_GET["status_z"]) ? '&status_z=' . $_GET["status_z"] : '') . "&offset=" . ($offset + 1) . "'>След.</a>";
            }
            $pegination .= '</div><br /><br />';
            if ($count_pegination == 1 || $all_zadanya == 0)
                $pegination = "";
        }

        function compare($v1, $v2) {
            if ($v1["date"] == $v2["date"]) {
                return 0;
            }
            return ($v1["date"] > $v2["date"]) ? -1 : 1;
        }

        usort($tasks, "compare");

        $table = "";
        foreach ($tasks as $res) {
            switch ($res["type_task"]) {
                case 0: $type = "Статья";
                    break;
                case 1: $type = "Обзор";
                    break;
                case 2: $type = "Новость";
                    break;
                default : $type = "Статья";
            }

            if ($res['vipolneno']) {
                $bg = '#83e24a';
                $status = "Завершен";
            } else if ($res['vrabote']) {
                $bg = '#00baff';
                $status = "В работе";
            } else if ($res['vilojeno']) {
                $bg = '#b385bf';
                $status = "Выложено";
            } else if ($res['navyklad']) {
                $bg = '#ffde96';
                $status = "Готов";
            } else if ($res['dorabotka'] && !$res['rework']) {
                $bg = '#b385bf';
                $status = "Выложено";
            } else if ($res['rework']) {
                $bg = '#f6b300';
                $status = "Доработать";
            } else {
                $bg = '';
                $status = "Активен";
            }
            if ($res['rectificate']) {
                $bg = '#999';
                $status = "Отклонен";
            }
            $chat = $db->Execute("SELECT * FROM chat_admin_copywriter WHERE status=0 AND uid!=$uid AND zid='" . $res["id"] . "' AND burse='" . (!isset($res["from_sape"]) ? "1" : "0") . "' LIMIT 1")->FetchRow();

            $price = (($res["sid"] != 1 && isset($sites[$res["sid"]]["cena"]) && !empty($sites[$res["sid"]]["cena"])) ? $sites[$res["sid"]]["cena"] : $res["price"]);
            $tr = "<tr style='background:$bg'>";
            $tr .= "<td>" . mb_substr($res["tema"], 0, 35) . "</td>";
            $tr .= "<td>" . $res["nof_chars"] . "</td>";
            $tr .= "<td>" . $type . "</td>";
            $tr .= "<td>" . $status . "</td>";
            $tr .= "<td>" . Helper::textQuality($price) . "</td>";
            $tr .= "<td>" . (($res["date"] >= "1456779600") ? $this->getPriceCopywriter($price, $res["nof_chars"]) : ($res["nof_chars"] / 1000 * COPYWRITER_PRICE_FOR_1000_CHAR)) . " руб.</td>";
            if (!empty($chat)) {
                $tr .= "<td class='state processed' style='padding-top:15px'><span class='ico'></span></td>";
            } else {
                $tr .= "<td></td>";
            }
            $tr .= "<td class='edit' style='padding:0 7px;'><a href='?action=tasks&action2=edit&id=" . $res["id"] . (!isset($res["from_sape"]) ? "&burse=1" : "") . "' class='ico'></a></td>";
            $tr .= ($res['vipolneno'] ? "<td></td>" : "<td class='close'><a href='?action=tasks&action2=delete&id=" . $res["id"] . (!isset($res["from_sape"]) ? "&burse=1" : "") . "' class='ico'></a></td>");
            $tr .= "</tr>";
            $table .= $tr;
        }

        if ($table == "") {
            $table = "<tr><td colspan='6'>Нет задач</td></tr>";
        }
        $content = str_replace('[pegination]', $pegination, $content);
        $content = str_replace('[cur_url]', "/copywriter.php?action=tasks", $content);
        $content = str_replace('[table]', $table, $content);
        return $content;
    }

    function task_add($db) {
        $id = $_REQUEST['id'];
        $uid = $_SESSION['user']['id'];
        if (isset($_REQUEST['burse'])) {
            $table = "zadaniya";
        } else {
            $table = "zadaniya_new";
        }

        if (!empty($id) && !empty($uid)) {
            $task = $db->Execute("SELECT * FROM $table WHERE id = $id")->FetchRow();
            $sinfo = $db->Execute("SELECT * FROM sayty WHERE id = " . $task["sid"])->FetchRow();
            
            if($_SESSION['user']['trust'] == 0) {
                $other_tasks_sape = $db->Execute("SELECT * FROM zadaniya_new WHERE copywriter='" . $_SESSION['user']['id'] . "' AND vrabote=1");
                $other_tasks_burse = $db->Execute("SELECT * FROM zadaniya WHERE copywriter='" . $_SESSION['user']['id'] . "' AND vrabote=1");
                $count_tasks = ($other_tasks_sape->NumRows()) + ($other_tasks_burse->NumRows());

                if($count_tasks >= 5) {
                    header('location: /copywriter.php?error=Вы уже подтвердили 5 задач. Чтобы взять в работу ещё, выполните какую-нибудь из уже подтверждённых');
                    die();
                }
            }
            
            if (!empty($task) && $task["etxt"] != 1 && ($task["task_id"] == 0 || $task["task_id"] == NULL) && $task["for_copywriter"] == 1 && $task["copywriter"] == 0) {
                $date = time();

                if ($table == "zadaniya_new" && !empty($task["sape_id"])) {
                    // Если задача из САПЫ, то отправляем подтверждение о ПРИНЯТИИ задачи
                    $cookie_jar = tempnam(PATH . 'temp', "cookie");
                    if ($curl = curl_init()) {
                        curl_setopt($curl, CURLOPT_URL, "http://api.articles.sape.ru/performer/index/");
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_POST, true);
                        curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
                        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
                        @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, xmlrpc_encode_request('performer.login', array(LOGIN_IN_SAPE, PASS_IN_SAPE)));
                        $user = curl_exec($curl);
                        curl_close($curl);
                    }

                    $data = xmlrpc_encode_request('performer.orderAccept', array(array($task["sape_id"])));
                    if ($curl = curl_init()) {
                        curl_setopt($curl, CURLOPT_URL, "http://api.articles.sape.ru/performer/index/");
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_POST, true);
                        curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
                        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
                        @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                        $out = curl_exec($curl);
                        curl_close($curl);
                    }
                    $accept = xmlrpc_decode($out);
                    
                    if (!empty($accept) && in_array($task["sape_id"], $accept)) {
                        $task = $db->Execute("UPDATE zadaniya_new SET copywriter = '$uid', vrabote='1', date_in_work='$date' WHERE id = '$id'");
                        $this->_postman->admin->copywriterAddedTask($id, $_SESSION['user']['login']);
                    } else {
                        $task = $db->Execute("UPDATE zadaniya_new SET copywriter = '0', from_copywriter='0' WHERE id = '$id'");
                        header('location: /copywriter.php?error=Ошибка подтверждения задачи. <br />Пожалуйста создайте тикет и сообщите об этом администрации iForget.');
                        die();
                    }
                } elseif ($table == "zadaniya") {
                    // Если задача из БИРЖИ, то снимаем деньги со счета Вебмастера
                    $price = $this->getTaskPrice(0, $task["nof_chars"], $sinfo);

                    // Проверка: Возможно уже снимали деньги за эту задачу, если так, то просто обновляем цену, иначе добавляем снятие
                    $compl = $db->Execute("SELECT * FROM completed_tasks WHERE uid = '" . $task['uid'] . "' AND zid=" . $task['id'])->FetchRow();
                    if (empty($compl)) {
                        $db->Execute("INSERT INTO completed_tasks (uid, zid, date, price, status) VALUES ('" . $task['uid'] . "', '" . $task['id'] . "', '" . date("Y-m-d H:i:s") . "', '$price',1)");
                    } else {
                        $db->Execute("UPDATE completed_tasks SET price = '" . $price . "' WHERE id = '" . $compl["id"] . "'");
                    }
                    $task = $db->Execute("UPDATE zadaniya SET copywriter = '$uid', vrabote='1', date_in_work='$date' WHERE id = '$id'");
                    $this->_postman->admin->copywriterAddedTask($id, $_SESSION['user']['login']);
                }


                header('location: /copywriter.php?action=tasks');
                die();
            } else {
                if ($task["etxt"] == 1) {
                    $error = "Задача уже занята и отправлена в ETXT, выберете другую задачу.";
                } elseif ($task["for_copywriter"] != 1) {
                    $error = "Задача больше не активна, выберете другую задачу.";
                } elseif ($task["copywriter"] != 0) {
                    $error = "Задача уже занята другим копирайтером, выберете другую задачу.";
                } else {
                    $error = "Ошибка принятия данной задачи, выберете другую задачу пожалуйста.";
                }
            }
        }
        if (!empty($error)) {
            header('location: /copywriter.php?error=' . $error);
        }
    }

    function task_delete($db) {
        $id = $_REQUEST['id'];
        if (isset($_REQUEST['burse'])) {
            $table = "zadaniya";
            $burse = 1;
        } else {
            $table = "zadaniya_new";
            $burse = 0;
        }
        if (!empty($id)) {
            $task = $db->Execute("SELECT * FROM $table WHERE id = $id")->FetchRow();
            if (!empty($task)) {
                $banned = false;
                $db->Execute("UPDATE $table SET copywriter = '0', 
                                                      vrabote='0',
                                                      vipolneno='0',
                                                      dorabotka='0',
                                                      vilojeno='0',
                                                      navyklad='0',
                                                      text='',
                                                      uniq='0',
                                                      for_copywriter='0',
                                                      date_in_work='NULL' WHERE id = $id");

                $db->Execute("INSERT INTO prohibition_taking_tasks (user_id, task_id, burse) VALUE ('" . $task['copywriter'] . "', '" . $id . "', $burse)");
                $prohibition = $db->Execute("SELECT COUNT(*) AS cnt, `user_id` FROM `prohibition_taking_tasks` WHERE `user_id` = '" . $task['copywriter'] . "' GROUP BY `user_id`")->FetchRow();
                if ($prohibition["cnt"] == LIMIT_ERROR_FROM_COPYWRITER) {
                    $db->Execute("UPDATE admins SET banned = '1' WHERE id = '" . $task['copywriter'] . "' AND type='copywriter'");
                    $banned = true;
                }

                $this->_postman->admin->copywriterCanceledTask($id, $_SESSION['user']['login'], LIMIT_ERROR_FROM_COPYWRITER, $banned);
                header('location: /copywriter.php?action=tasks');
                die();
            } else {
                $error = "Задачи не существует или она уже снята с Вас.";
            }
        }
        if (!empty($error)) {
            header('location: /copywriter.php?error=' . $error);
        }
    }

    function task_edit($db) {
        $content = file_get_contents(PATH . 'modules/copywriter/tmp/task_edit.tpl');
        $id = $_REQUEST['id'];
        $uid = $_SESSION['user']['id'];
        $send = isset($_REQUEST['send']) ? $_REQUEST['send'] : null;
        if (isset($_REQUEST['burse'])) {
            $table = "zadaniya";
            $burse = 1;
        } else {
            $table = "zadaniya_new";
            $burse = 0;
        }
        $display = $read = "";
        $task = $db->Execute("SELECT * FROM $table WHERE copywriter='$uid' AND id='$id'")->FetchRow();
        $site = $db->Execute("SELECT * FROM sayty WHERE id='" . $task["sid"] . "'")->FetchRow();

        if (empty($id) || empty($task)) {
            $error = "Не верный ID задачи!";
            header('Location: /copywriter.php?action=tasks');
        }
        if (!$send) {
            if ($task['navyklad']) {
                $task['navyklad'] = 'checked';
            } else {
                $task['navyklad'] = '';
            }
            if ($task['vilojeno'] || $task['vipolneno'] || ($task['dorabotka'] && !$task['rework'])) {
                $display = "style='display:none'";
                $read = "readonly='readonly'";
            }
            //$additional_text = "";
            switch ($task["type_task"]) {
                case 0: $type = "Статья";
                    break;
                case 1: $type = "Обзор";
                    break;
                case 2: $type = "Новость";
                    //$additional_text = "<br>Новость будет размещена на сайте " . $task["url"] . ". Писать только по тематике сайта";
                    break;
                default : $type = "Статья";
            }
            $content = str_replace('[read]', $read, $content);
            $content = str_replace('[display]', $display, $content);
            $content = str_replace('[type]', $type, $content);
            //$content = str_replace('[additional_text]', $additional_text, $content);
            $content = str_replace('[date]', date("Y-m-d", $task['date']), $content);
            foreach ($task as $k => $v) {
                $content = str_replace("[$k]", htmlspecialchars($v), $content);
            }
            $chat = $db->Execute("SELECT ch.*, a.login FROM chat_admin_copywriter ch LEFT JOIN admins a ON ch.uid=a.id WHERE zid='$id' AND burse='$burse' ORDER BY ch.date DESC");
            $message = "";
            $message_copywriter_count = 2;
            while ($value = $chat->FetchRow()) {
                if ($message_copywriter_count < 10)
                    $message_copywriter_count += 2;
                $message .= $value['login'] . " (" . $value['date'] . ")" . "\n";
                $message .= $value['msg'] . "\n\n";
            }
            $message = trim($message, "\n\n");
            $content = str_replace('[message]', $message, $content);
            $content = str_replace('[message_copywriter_count]', $message_copywriter_count == 0 ? 2 : $message_copywriter_count, $content);
            $db->Execute("UPDATE chat_admin_copywriter SET status=1 WHERE uid != '" . $_SESSION["user"]["id"] . "' AND zid='$id'");
            if (!empty($task['ankor3']) || !empty($task['ankor2']) || !empty($task['ankor4']) || !empty($task['ankor5'])) {
                $content = str_replace('[mn]', "ы", $content);
            } else {
                $content = str_replace('[mn]', "а", $content);
            }

            /* if ($copywriter["trust"] == 0 && $burse == 0) {
              $content = str_replace('[trust]', "style='display:none'", $content);
              } else {
              $content = str_replace('[trust]', "", $content);
              } */
            $content = str_replace('[id]', $task['id'], $content);
            $content = str_replace('[ankor_url]', (!empty($task['ankor'])) ? htmlspecialchars(' <a href="' . $task['url'] . '">' . $task['ankor'] . '</a>') : '', $content);
            $content = str_replace('[ankor2_url2]', (!empty($task['ankor2'])) ? "<br>" . htmlspecialchars('<a href="' . $task['url2'] . '">' . $task['ankor2'] . '</a>') : '', $content);
            $content = str_replace('[ankor3_url3]', (!empty($task['ankor3'])) ? "<br>" . htmlspecialchars('<a href="' . $task['url3'] . '">' . $task['ankor3'] . '</a>') : '', $content);
            $content = str_replace('[ankor4_url4]', (!empty($task['ankor4'])) ? "<br>" . htmlspecialchars('<a href="' . $task['url4'] . '">' . $task['ankor4'] . '</a>') : '', $content);
            $content = str_replace('[ankor5_url5]', (!empty($task['ankor5'])) ? "<br>" . htmlspecialchars('<a href="' . $task['url5'] . '">' . $task['ankor5'] . '</a>') : '', $content);
            $content = str_replace('[text_quality]', Helper::textQuality(($site["id"] != 1) ? $site["id"] : $task['price']), $content);

            $content = str_replace('[burse]', (($burse == 1) ? "&burse=1" : ""), $content);
            $content = str_replace('[error]', ((isset($_REQUEST['error']) && !empty($_REQUEST['error'])) ? $_REQUEST['error'] : ""), $content);
        } else {
            $tema = ($_REQUEST['tema']);
            $title = ($_REQUEST['title']);
            $keywords = ($_REQUEST['keywords']);
            $description = ($_REQUEST['description']);
            $text = ($_REQUEST['text']);
            $uniq = isset($_REQUEST['uniq']) ? $_REQUEST['uniq'] : $task['uniq'];
            $url_pic = @$_REQUEST['url_pic'];
            $navyklad = isset($_REQUEST['task_status']) ? 1 : 0;
            $rework = isset($_REQUEST['task_status']) ? 0 : $task['rework'];
            $dorabotka = (isset($_REQUEST['task_status']) && $burse == 0) ? 0 : $task['dorabotka'];
            $vilojeno = isset($_REQUEST['task_status']) ? 0 : $task["vilojeno"];
            $vrabote = isset($_REQUEST['task_status']) ? 0 : $task["vrabote"];

            $text_without_links = preg_replace('~<a\b[^>]*+>|</a\b[^>]*+>~', '', $text);
            $num_symbol_without_space = mb_strlen(str_replace(" ", "", str_replace("\r", "", str_replace("\n", "", $text_without_links))), "UTF-8");
            $message = array();

            if ($navyklad == 1) { // && $copywriter['trust'] == 1
                if ($num_symbol_without_space < (int) $task["nof_chars"] || (empty($uniq) || $uniq < 95)) {
                    if ($uniq < 95) {
                        $error = "Уникальность статьи должна быть больше 95%! Читайте Описание задачи!";
                    } else {
                        $error = "Количество символов ($num_symbol_without_space) в статье меньше, чем требуется в задании! Количество символов считается без учета ссылок!";
                    }
                    $db->Execute("UPDATE $table SET title='" . mysql_real_escape_string($title) . "', 
                                              keywords='" . mysql_real_escape_string($keywords) . "', 
                                              description='" . mysql_real_escape_string($description) . "', 
                                              text='" . mysql_real_escape_string($text) . "', 
                                              tema='" . mysql_real_escape_string($tema) . "', 
                                              url_pic='" . mysql_real_escape_string($url_pic) . "', 
                                              uniq='$uniq'
                                              WHERE id=$id");

                    header('Location: /copywriter.php?action=tasks&action2=edit&id=' . $id . ($burse == 1 ? "&burse=1" : "") . '&error=' . $error);
                    exit();
                } else {
                    $dorabotka = 0;
                    if (empty($description) || mb_strlen($description) > 255) {
                        $description = mb_substr(substr($text, 0, strpos($text, ".")), 0, 225);
                    }
                    if ($task["navyklad"] != 1 && $task["vipolneno"] != 1 && $task["vilojeno"] != 1 && $task["dorabotka"] != 1 && $task["rework"] != 1) {
                        $request = array();
                        $empty_data = FALSE;
                        $status = "Готов";
                        if (!empty($title) && !empty($tema) && !empty($keywords) && !empty($description) && !empty($text) && $burse == 0) {
                            $cookie_jar = tempnam(PATH . 'temp', "cookie");
                            if ($curl = curl_init()) {
                                curl_setopt($curl, CURLOPT_URL, "http://api.articles.sape.ru/performer/index/");
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl, CURLOPT_POST, true);
                                curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
                                curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
                                @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                                curl_setopt($curl, CURLOPT_POSTFIELDS, xmlrpc_encode_request('performer.login', array(LOGIN_IN_SAPE, PASS_IN_SAPE)));
                                $login_sape = curl_exec($curl);
                                curl_close($curl);
                            }

                            $data = xmlrpc_encode_request('performer.orderComplite', array((int) $task["sape_id"], array("title" => $title, "header" => $tema, "keywords" => $keywords, "description" => $description, "text" => $text)), array('encoding' => 'UTF-8', 'escaping' => 'markup'));
                            if ($curl = curl_init()) {
                                curl_setopt($curl, CURLOPT_URL, "http://api.articles.sape.ru/performer/index/");
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl, CURLOPT_POST, true);
                                curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
                                curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
                                @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                                $out = curl_exec($curl);
                                curl_close($curl);
                            }
                            $accept = xmlrpc_decode($out);
                            if ($accept == true && !isset($accept["faultString"])) {
                                $vilojeno = 1;
                                $navyklad = 0;
                                $status = "Выложено";
                            } else {
                                $errors = json_decode($accept["faultString"]);
                                if (isset($errors->items) && !empty($errors->items)) {
                                    foreach ($errors->items as $err_arr) {
                                        foreach ($err_arr as $err) {
                                            $request[] = $err;
                                        }
                                    }
                                }
                                if (empty($request) && isset($accept["faultString"])) {
                                    $request[] = $accept["faultString"];
                                }
                            }
                        } else {
                            $empty_data = $burse == 0 ? TRUE : FALSE;
                        }
                        $this->_postman->admin->copywriterFinishedTask($id, $_SESSION['user']['login'], $status, $request, $empty_data);
                    }
                }
            }

            if ($navyklad == 1 && $burse == 1 && ($num_symbol_without_space < (int) $task["nof_chars"] || $uniq < 95)) {
                if ($uniq < 95) {
                    $error = "Уникальность статьи должна быть больше 95%! Читайте Описание задачи!";
                } else {
                    $error = "Количество символов ($num_symbol_without_space) в статье меньше, чем требуется в задании! Количество символов считается без учета ссылок!";
                }
                header('Location: /copywriter.php?action=tasks&action2=edit&id=' . $id . "&burse=1" . '&error=' . $error);
                die();
            } elseif ($navyklad == 1 && $burse == 1) {
                $dorabotka = 0;
            }

            $q = "UPDATE $table SET title='" . mysql_real_escape_string($title) . "', 
                                    keywords='" . mysql_real_escape_string($keywords) . "', 
                                    description='" . mysql_real_escape_string($description) . "', 
                                    text='" . mysql_real_escape_string($text) . "', 
                                    tema='" . mysql_real_escape_string($tema) . "', 
                                    url_pic='" . mysql_real_escape_string($url_pic) . "', 
                                    uniq='$uniq',   
                                    navyklad='$navyklad',
                                    vilojeno='$vilojeno',
                                    rework='$rework',
                                    dorabotka='$dorabotka',
                                    vrabote='$vrabote'
                                    WHERE id=$id";
            $db->Execute($q);

            header('location: /copywriter.php?action=tasks');
        }

        return $content;
    }

    function task_view($db) {
        $content = file_get_contents(PATH . 'modules/copywriter/tmp/task_view.tpl');
        $id = $_REQUEST['id'];
        if (isset($_REQUEST['burse'])) {
            $table = "zadaniya";
            $burse = 1;
        } else {
            $table = "zadaniya_new";
            $burse = 0;
        }
        $task = $db->Execute("SELECT * FROM $table WHERE id='$id'")->FetchRow();
        $site = $db->Execute("SELECT * FROM sayty WHERE id='" . $task["sid"] . "'")->FetchRow();

        if (!empty($id)) {
            //$additional_text = "";
            switch ($task["type_task"]) {
                case 0: $type = "Статья";
                    break;
                case 1: $type = "Обзор";
                    break;
                case 2: $type = "Новость";
                    //$additional_text = "<br>Новость будет размещена на сайте " . $task["url"] . ". Писать только по тематике сайта";
                    break;
                default : $type = "Статья";
            }

            $content = str_replace('[type]', $type, $content);
            //$content = str_replace('[additional_text]', $additional_text, $content);
            $content = str_replace('[date]', date("Y-m-d", $task['date']), $content);
            foreach ($task as $k => $v) {
                $content = str_replace("[$k]", htmlspecialchars($v), $content);
            }

            if (!empty($task['ankor3']) || !empty($task['ankor2']) || !empty($task['ankor4']) || !empty($task['ankor5'])) {
                $content = str_replace('[mn]', "ы", $content);
            } else
                $content = str_replace('[mn]', "а", $content);

            $content = str_replace('[ankor_url]', (!empty($task['ankor'])) ? htmlspecialchars(' "<a href=\'' . $task['url'] . '\'>' . $task['ankor'] . '</a>"') : '', $content);
            $content = str_replace('[ankor2_url2]', (!empty($task['ankor2'])) ? "<br>" . htmlspecialchars('"<a href=\'' . $task['url2'] . '\'>' . $task['ankor2'] . '</a>"') : '', $content);
            $content = str_replace('[ankor3_url3]', (!empty($task['ankor3'])) ? "<br>" . htmlspecialchars('"<a href=\'' . $task['url3'] . '\'>' . $task['ankor3'] . '</a>"') : '', $content);
            $content = str_replace('[ankor4_url4]', (!empty($task['ankor4'])) ? "<br>" . htmlspecialchars('"<a href=\'' . $task['url4'] . '\'>' . $task['ankor4'] . '</a>"') : '', $content);
            $content = str_replace('[ankor5_url5]', (!empty($task['ankor5'])) ? "<br>" . htmlspecialchars('"<a href=\'' . $task['url5'] . '\'>' . $task['ankor5'] . '</a>"') : '', $content);

            $content = str_replace('[text_quality]', Helper::textQuality(($site["id"] != 1) ? $site["id"] : $task['price']), $content);
            
            if($_SESSION['user']['trust'] == 0) {
                $other_tasks_sape = $db->Execute("SELECT * FROM zadaniya_new WHERE copywriter='" . $_SESSION['user']['id'] . "' AND vrabote=1");
                $other_tasks_burse = $db->Execute("SELECT * FROM zadaniya WHERE copywriter='" . $_SESSION['user']['id'] . "' AND vrabote=1");
                $count_tasks = ($other_tasks_sape->NumRows()) + ($other_tasks_burse->NumRows());

                if($count_tasks >= 5) {
                    $content = str_replace('[count_vrabote]', "1", $content);
                } else {
                    $content = str_replace('[count_vrabote]', "0", $content);
                }
            } else {
                $content = str_replace('[count_vrabote]', "0", $content);
            }

            $content = str_replace('[burse]', (($burse == 1) ? "&burse=1" : ""), $content);
            $content = str_replace('[error]', ((isset($_REQUEST['error']) && !empty($_REQUEST['error'])) ? $_REQUEST['error'] : ""), $content);
        }

        return $content;
    }

    function chat_send_message($db) {
        $uid = $_SESSION['user']['id'];
        $id = $_REQUEST['id'];
        $msg = $_REQUEST['msg'];
        $date = date("Y-m-d H:i:s");
        if (isset($_REQUEST['burse'])) {
            $burse = 1;
        } else {
            $burse = 0;
        }
        if (!empty($uid) && !empty($id)) {
            $db->Execute("INSERT INTO chat_admin_copywriter (uid, zid, msg, date, status, burse) VALUE ('$uid', '$id', '$msg', '$date', 0, '$burse')");
            $this->_postman->admin->copywriterSentMessage($id, $_SESSION['user']['login'], $msg, $burse);
        }
        header('location: /copywriter.php?action=tasks&action2=edit&id=' . $id . ($burse == 1 ? '&burse=1' : ''));
    }

    function statistics_done_tasks($db) {
        $content = file_get_contents(PATH . 'modules/copywriter/tmp/statistics_done_tasks.tpl');
        $uid = $_SESSION['user']['id'];
        $date_from = $date_to = NULL;
        if ($_POST) {
            $content = str_replace('[dfrom]', $_POST["date-from"], $content);
            $content = str_replace('[dto]', $_POST["date-to"], $content);

            if (!empty($_POST["date-from"])) {
                $exp = explode("/", $_POST["date-from"]);
                $date_from = mktime(0, 0, 0, $exp[0], $exp[1], $exp[2]);
            }
            if (!empty($_POST["date-to"])) {
                $exp = explode("/", $_POST["date-to"]);
                $date_to = mktime(0, 0, 0, $exp[0], $exp[1], $exp[2]);
            }
        }
        $condition = "";
        if (!empty($date_from) && !empty($date_to)) {
            $condition = " AND date BETWEEN '$date_from' AND '$date_to'";
            $content = str_replace('[time]', "с " . $_POST["date-from"] . " по " . $_POST["date-to"], $content);
        } elseif (!empty($date_from)) {
            $condition = " AND date > '$date_from'";
            $content = str_replace('[time]', "с " . $_POST["date-from"], $content);
        } elseif (!empty($date_to)) {
            $condition = " AND date < '$date_to'";
            $content = str_replace('[time]', "до " . $_POST["date-to"], $content);
        } else {
            $content = str_replace('[time]', "за всё время", $content);
            $content = str_replace('[dfrom]', "", $content);
            $content = str_replace('[dto]', "", $content);
        }

        $tasks_sape = $db->Execute("SELECT count(id) as num FROM zadaniya_new WHERE copywriter = " . $uid . " AND vipolneno = 1" . $condition)->FetchRow();
        $tasks_burse = $db->Execute("SELECT count(id) as num FROM zadaniya WHERE copywriter = " . $uid . " AND vipolneno = 1" . $condition)->FetchRow();
        $all = $tasks_sape["num"] + $tasks_burse["num"];
        $content = str_replace('[num]', $all, $content);
        return $content;
    }

    function help_work() {
        $content = file_get_contents(PATH . 'modules/copywriter/tmp/help_work.tpl');
        return $content;
    }

    function money_output($db) {
        $content = file_get_contents(PATH . 'modules/copywriter/tmp/money_output.tpl');
        $user = $db->Execute("SELECT * FROM admins WHERE id = '" . $_SESSION['user']['id'] . "'")->FetchRow();
        if (isset($_REQUEST["query"])) {
            $content = str_replace('[query]', $_REQUEST["query"], $content);
        } else {
            $content = str_replace('[query]', "", $content);
        }
        if (isset($_REQUEST["error"])) {
            $content = str_replace('[error]', $_REQUEST["error"], $content);
        } else {
            $content = str_replace('[error]', "", $content);
        }

        $send = isset($_REQUEST["send"]) ? 1 : null;
        if (!$send) {
            $table = $bg = "";
            $num = 1;
            $balance = 0;
            $withdrawal_first = null;
            $tasks = $db->Execute("SELECT * FROM zadaniya_new WHERE copywriter='" . $user['id'] . "' AND vipolneno=1");
            while ($res = $tasks->FetchRow()) {
                $balance += (($res["date"] >= "1456779600") ? $this->getPriceCopywriter($res["price"], $res["nof_chars"]) : ($res["nof_chars"] / 1000 * COPYWRITER_PRICE_FOR_1000_CHAR));
            }
            $sites = array();
            $sites_model = $db->Execute("SELECT * FROM sayty")->GetAll();
            foreach ($sites_model as $site) {
                $sites[$site["id"]] = $site;
            }

            $tasks_burse = $db->Execute("SELECT * FROM zadaniya WHERE copywriter='" . $user['id'] . "' AND vipolneno=1");
            while ($res = $tasks_burse->FetchRow()) {
                $price = (($res["sid"] != 1 && isset($sites[$res["sid"]]["cena"]) && !empty($sites[$res["sid"]]["cena"])) ? $sites[$res["sid"]]["cena"] : $res["price"]);
                $balance += (($res["date"] >= "1456779600") ? $this->getPriceCopywriter($price, $res["nof_chars"]) : ($res["nof_chars"] / 1000 * COPYWRITER_PRICE_FOR_1000_CHAR));
            }

            $withdrawal = $db->Execute("SELECT * FROM withdrawal WHERE visible = 1 AND uid='" . $user['id'] . "' ORDER BY date DESC");
            while ($res = $withdrawal->FetchRow()) {
                $balance -= $res["sum"];
                $three_days_last = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d") - 3, date("Y")));
                if ($num == 1 && $res["date"] > $three_days_last) {
                    $withdrawal_first = $res;
                }

                $bg = (($num % 2) == 0) ? "#f7f7f7" : "";
                $table .= '<tr style="background:' . $bg . '">';
                $table .= '<td>' . $res["date"] . '</td>';
                $table .= '<td>' . $res["sum"] . ' руб.</td>';
                $table .= '</tr>';
                $num++;
            }
            if (empty($withdrawal_first)) {
                $last_ticket = $db->Execute("SELECT * FROM tickets WHERE uid='" . $user['id'] . "' AND subject = 'Вывод средств' ORDER BY date DESC LIMIT 1")->FetchRow();
                $three_days_last = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 3, date("Y")));
                if ($last_ticket["date"] > $three_days_last) {
                    $withdrawal_first = $last_ticket;
                }
            }

            if (!empty($withdrawal_first)) {
                $date = date_create($withdrawal_first["date"]);
                date_add($date, date_interval_create_from_date_string('3 days'));
                $three_days_ago = date_format($date, 'Y-m-d H:i:s');
                $str = "<br /><span>Вывод средств возможен не чаще 1 раз в 3 суток</span><br />";
                if (isset($withdrawal_first["subject"]))
                    $str .= "<span>Вы запросили снятие средств <i>" . $withdrawal_first["date"] . "</i><br /> Следующий раз можно будет послать запрос не ранее <i>" . $three_days_ago . "</i>.</span><br />";
                else
                    $str .= "<span>Вы снимали средства <i>" . $withdrawal_first["date"] . "</i><br /> Следующий раз можно будет снять не ранее <i>" . $three_days_ago . "</i>.</span><br />";
                $content = str_replace('[form]', $str, $content);
            } else {
                $content = str_replace('[form]', file_get_contents(PATH . 'modules/copywriter/tmp/money_output_form.tpl'), $content);
            }

            $content = str_replace('[balance]', $balance, $content);
            $content = str_replace('[webmoney]', (!empty($user["wallet"]) ? $user["wallet"] : "Не указан (<i><a href='copywriter.php?action=lk'>Изменить</a></i>)"), $content);
            $content = str_replace('[table]', $table, $content);
        } else {
            $sum = $_REQUEST["sum"];
            $balance = $_REQUEST["balance"];
            $date = date("Y-m-d");

            if ($sum <= $balance) {
                $msg = "Добрый день! Копирайтер " . $user["login"] . " просит вывести деньги. <br> Запрашиваемая сумма: $sum руб. <br> Кошелек: " . (!empty($user["wallet"]) ? $user["wallet"] : "Не указан") . "";
                $db->Execute("INSERT INTO tickets (uid, subject, q_theme, msg, date, status, site, tid) 
                                                VALUES (
                                                        " . $user["id"] . ", 
                                                        'Вывод средств', 
                                                        'Общими вопросами', 
                                                        '$msg', 
                                                        '$date', 
                                                        1, 
                                                        '', 
                                                        '')");
                $lastId = $db->Insert_ID();
                $this->_postman->admin->copywriterOutputMoney($user, $sum, $lastId);
                header('location: /copywriter.php?action=money&action2=output&query=Запрос успешно отправлен');
                exit();
            } else {
                header('location: /copywriter.php?action=money&action2=output&error=Запрашиваемая сумма меньше текущего баланса');
            }
        }


        return $content;
    }

    function tickets($db) {
        $content = file_get_contents(PATH . 'modules/copywriter/tmp/tickets.tpl');

        $uid = (int) $_SESSION['user']['id'];
        $res = $db->Execute("select * from admins where id=$uid")->FetchRow();
        $content = str_replace('[login]', $res['login'], $content);
        $content = str_replace('[uid]', $uid, $content);

        $query = $db->Execute("SELECT * FROM tickets WHERE (uid=$uid OR to_uid=$uid) ORDER BY id DESC");

        $ticket_subjects = $db->Execute("SELECT * FROM Message2008");
        $ticket_subjects = $ticket_subjects->FetchRow();
        $content = str_replace('[ticket_subjects]', $ticket_subjects['Name'], $content);

        $ticket = "";
        while ($resw = $query->FetchRow()) {
            $ticket .= file_get_contents(PATH . 'modules/copywriter/tmp/ticket_one.tpl');
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
        header('location: /copywriter.php?action=ticket');
    }

    function ticket_view($db) {
        $content = file_get_contents(PATH . 'modules/copywriter/tmp/ticket_view.tpl');
        $uid = (int) $_SESSION['user']['id'];
        $user = $db->Execute("select * from admins where id=$uid")->FetchRow();

        $content = str_replace('[login]', $user['login'], $content);
        $content = str_replace('[uid]', $uid, $content);

        $tid = (int) $_REQUEST['tid'];

        $res = $db->Execute("SELECT * FROM tickets WHERE (uid=$uid OR to_uid=$uid) AND id=$tid")->FetchRow();

        $view = file_get_contents(PATH . 'modules/user/tmp/ticket_chat_one.tpl');
        $view = str_replace('[msg]', $res['msg'], $view);
        $view = str_replace('[cdate]', $res['date'], $view);
        if ($res['to_uid'] > 0) {
            $view = str_replace('[from_class]', "support", $view);
            $view = str_replace('[from]', "admin iforget.ru", $view);
            if ($res['status'] == 1) {
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

        $content = str_replace('[chat]', $view, $content);
        $content = str_replace('[subject]', $res['subject'], $content);
        $content = str_replace('[tid]', $tid, $content);
        return $content;
    }

    function ticket_edit($db) {

        $send = $_REQUEST['send'];
        $id = (int) $_REQUEST['tid'];
        $res = $db->Execute("select * from tickets where id=$id")->FetchRow();

        if (!$send) {
            $content = file_get_contents(PATH . 'modules/copywriter/tmp/ticket_edit.tpl');

            $ticket_subjects = $db->Execute("SELECT * FROM Message2008")->FetchRow();
            $content = str_replace('[ticket_subjects]', $ticket_subjects['Name'], $content);

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

            //$db->Execute("UPDATE tickets SET subject='$subject', q_theme='$theme', msg='$msg', site='$site' WHERE id=$id");
            //$this->_postman->admin->ticketEdit($id, $subject);
            header('location: /copywriter.php?action=ticket&action2=view&tid=' . $id);
        }
        return $content;
    }

    function ticket_answer($db) {
        $uid = (int) $_SESSION['user']['id'];
        $tid = (int) $_REQUEST['tid'];
        $msg = substr(nl2br(htmlspecialchars(addslashes(trim($_REQUEST['msg'])))), 0, 2000);
        $date = date("Y-m-d H:i:s");

        if (!empty($msg)) {
            $db->Execute("INSERT INTO answers (uid, tid, msg, date) VALUES ($uid, $tid, '$msg', '$date')");
            $db->Execute("UPDATE tickets SET status=1 WHERE id=$tid");

            $this->_postman->admin->ticketAnswer($tid, "copywriter");
            header('location: /copywriter.php?action=ticket&action2=view&tid=' . $tid);
        } else {
            header('location: /copywriter.php?action=ticket&action2=view&error=Пустой текст ответа!&tid=' . $tid);
        }
    }

    function ticket_close($db) {
        $tid = (int) $_REQUEST['tid'];
        $db->Execute("UPDATE tickets SET status=0 WHERE id=$tid");
        header('location: /copywriter.php?action=ticket');
    }

    function getTaskPrice($lay_out = 0, $nof_chars = 0, $site = array()) {
        $price = 0;
        if ($lay_out == 1) {
            $price = 15;
        } else {
            if ((int) $nof_chars == 2000) {
                $price = $this->getPrice($site['cena'], $nof_chars);
            } else {
                $price = $this->getPrice($site['cena']);
            }
        }
        return $price;
    }

    function getPrice($tarif, $type = 1500) {
        $price = 0;
        if ($type == 1500) {
            switch ($tarif) {
                case 20: $price = 62;
                    break;
                case 30: $price = 78;
                    break;
                case 45: $price = 110;
                    break;
                default: $price = 62;
                    break;
            }
        } else {
            switch ($tarif) {
                case 20: $price = 77;
                    break;
                case 30: $price = 93;
                    break;
                case 45: $price = 128;
                    break;
                default: $price = 77;
                    break;
            }
        }
        return $price;
    }

    function getPriceCopywriter($tarif = 20, $type = 1000) {
        $price = 0;
        switch ($tarif) {
            case 20: $price = 21;
                break;
            case 30: $price = 31.5;
                break;
            case 45:
            case 50:$price = 47.25;
                break;

            case 78:
            case 93: $price = 31.5; //Для задач из биржи (тариф medium).
                break;
            case 110:
            case 128: $price = 47.25; //Для задач из биржи (тариф expert).
                break;

            default: $price = 21;
                break;
        }

        return $price * ($type / 1000);
    }

}

?>
