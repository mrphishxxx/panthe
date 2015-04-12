<?php
class system {
	function content($db){
		$content = file_get_contents(PATH.'modules/system/tmp/admin/index.tpl');
		$query = $db->Execute('select * from system');
		if($res = $query->FetchRow()){
			foreach ($res as $k=>$v){
				$content = str_replace("[$k]", $v, $content);
			}
		}
		
		$send = $_REQUEST['send'];
		if($send){
			if($_SESSION['admin']['type'] != 'admin'){
				$content = file_get_contents(PATH.'modules/admins/tmp/admin/request.tpl');
				$content = str_replace('[alert]', 'Данное действие Вам недоступно', $content);
				$content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
				echo $content;
				exit;
			}
			$mail_mail = $_REQUEST['mail_mail'];
			$mail_server = $_REQUEST['mail_server'];
			$mail_login = $_REQUEST['mail_login'];
			if($_REQUEST['mail_pass']) $mail_pass = $_REQUEST['mail_pass'];
			else $mail_pass = $res['mail_pass'];
			$zaderjka  = $_REQUEST['zaderjka'];
			$limit  = $_REQUEST['mlimit'];
			
			
			$db->Execute("update system set mlimit='$limit', mail_mail='$mail_mail', mail_server='$mail_server', mail_login='$mail_login', mail_pass='$mail_pass', zaderjka='$zaderjka'");
			$content = file_get_contents(PATH.'modules/system/tmp/admin/request.tpl');
			$content = str_replace('[alert]', 'Данные успешно сохранены', $content);
			$content = str_replace('[url]', '?module=system', $content);
			echo $content;
			exit;
		}
		
		
		$GLOBAL['content'] = $content;
		return $GLOBAL;
	}
	
	
}
?>