<?php

class admin_auth {

    public $GLOBAL;

    function content($db) {
        $action = $_REQUEST['action'];
        switch ($action) {
            case 'reg':
                $this->reg($db);
                break;
            case 'reged':
                $this->reged();
                break;
            case 'login':
                $this->login($db);
                break;
            case 'logout':
                $this->logout();
                break;
            case 'activate':
                $this->activate($db, $url);
                break;
            case 'select_type':
                $this->select_type($db);
                break;
        }

        return $this->GLOBAL;
    }

    function start($db, $url) {
        $no_user = 0;
        $admin = @$_SESSION['admin']['login'];
        if (!$admin)
            $no_user = 1;
        else {
            $query = $db->Execute("select * from admins where login='$admin' and type!='user'");
            if (!$res = $query->FetchRow()) {
                $no_user = 1;
            } else {
                return $res;
            }
        }
    }

    function login($db) {
        $send = @$_REQUEST['send'];
        $error = '';
        
        if ($send and $_REQUEST['login'] and $_REQUEST['pass']) {
            $login = $_REQUEST['login'];
            $pass = md5($_REQUEST['pass']);
            $res = $db->Execute("select * from admins where login='$login' and pass='$pass' and type!='user'")->FetchRow();
            
            if (!$res) {;
                $error = 'Логин или пароль введены неверно.';
            } else {
                if ($res['active'] != 1){
                    $error = 'Аккаунт не активирован.';
                } else if ($res['type'] != "admin" && $res['type'] != "manager") {
                    $error = "У Вас нет прав!";
                } else {
                    $this->saveAuthHistory($db, $res);
                    $url = $_SERVER["REQUEST_URI"];
                    if($res['type'] == "manager"){
                        $_SESSION['manager'] = $res;
                    } else {
                        $_SESSION['admin'] = $res;
                    }
                    setcookie("iforget_admin", $res['id'], time() + 60 * 60 * 24);
                    header('location:'.$url);
                    exit;
                }
            }
        }
        echo file_get_contents(PATH . 'modules/admin_auth/tmp/login.html');
    }

    function logout() {
        unset($_SESSION['admin']);
        header('location:/admin.php');
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

}

?>