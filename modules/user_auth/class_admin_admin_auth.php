<?php
class admin_auth {
	public $GLOBAL;
	function content($db){
		$action = $_REQUEST['action'];
		switch ($action){
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
	
	function start($db, $url){
		$no_user = 0;
		$admin = $_SESSION['admin']['login'];
		if(!$admin) $no_user = 1;
		else {
			$query = $db->Execute("select * from admins where login='$admin' and type!='user'");
			if(!$res = $query->FetchRow()) {
				$no_user = 1;
			} else {
				return $res;
			}
		}
	}
	
	function login($db){ print_r("sd");die();
		$send = $_REQUEST['send'];
		$error = '';
		if($send and $_REQUEST['login'] and $_REQUEST['pass']){
			$login = $_REQUEST['login'];
			$pass = md5($_REQUEST['pass']);
			$query = $db->Execute("select * from admins where login='$login' and pass='$pass' and type!='user'");
			if(!$res = $query->FetchRow()){
				$error = 'Логин или пароль введены неверно.';
			} else {
				if($res['active'] != 1) $error = 'Аккаунт не активирован.';
				else if ($res['type'] != "admin") $error = "У Вас нет прав!";
				else {
					$_SESSION['admin'] = $res;
                                        setcookie("iforget_ok", $res['id'],time()+60*60*24*30);
					header('location:/admin.php');
					exit;
				}
			}
		} elseif(isset($_COOKIE) && isset($_COOKIE['iforget_ok'])) {
                        $query = $db->Execute("select * from admins where id='".$_COOKIE['iforget_ok']."' LIMIT 1");
                        if(!$res = $query->FetchRow()){
				$error = 'Ошибка при входе в систему!';
			} else {
				if($res['active'] != 1) $error = 'Аккаунт не активирован.';
				else {
					$_SESSION['admin'] = $res;
					header('location:/user.php');
					exit;
				}
			}
                }
                
		
		echo file_get_contents(PATH.'modules/admin_auth/tmp/login.html');
	}
	
	function logout(){
		unset($_SESSION['admin']);
		header('Location:/');
		exit;
	}
}
?>