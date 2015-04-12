<?php
class modules {
	function content($db){
		$GLOBAL = array();
		
		$action = $_REQUEST['action'];
		if($action == 'del'){
			if($_SESSION['admin']['type'] != 'admin'){
				$content = file_get_contents(PATH.'modules/admins/tmp/admin/request.tpl');
				$content = str_replace('[alert]', 'Данное действие Вам недоступно', $content);
				$content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
				echo $content;
				exit;
			}
			$name = $_REQUEST['name'];
			$db->Execute("delete from urls where module='$name'");
			$content = file_get_contents(PATH.'modules/modules/tmp/admin/request.tpl');
			$content = str_replace('[alert]', 'Модуль успешно отключен', $content);
			$content = str_replace('[url]', '?module=modules', $content);
			echo $content;
			exit;
		}
		if($action == 'add'){
			if($_SESSION['admin']['type'] != 'admin'){
				$content = file_get_contents(PATH.'modules/admins/tmp/admin/request.tpl');
				$content = str_replace('[alert]', 'Данное действие Вам недоступно', $content);
				$content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
				echo $content;
				exit;
			}
			$name = $_REQUEST['name'];
			if(is_file(PATH."modules/$name/in_template.txt")) $in_template = 1; else $in_template = 0;
			$mname = file(PATH.'modules/'.$name.'/name.txt');
			$mname = $mname[0];
			$db->Execute("insert into urls(module, url, in_template, name) values('$name', '$name', $in_template, '$mname')");
			$content = file_get_contents(PATH.'modules/modules/tmp/admin/request.tpl');
			$content = str_replace('[alert]', 'Модуль успешно включен', $content);
			$content = str_replace('[url]', '?module=modules', $content);
			echo $content;
			exit;
		}
		
		
		$content = file_get_contents(PATH.'modules/modules/tmp/admin/index.tpl');

		$modules = '';
		$dir = PATH.'modules';
		$data = opendir ($dir);
		while ($one = readdir ($data)) { 
			if(!in_array($one, $_SESSION['no_view_modules'])) {
				$modules .= file_get_contents(PATH.'modules/modules/tmp/admin/one.tpl');
				$mname = file(PATH.'modules/'.$one.'/name.txt');
				$mname = $mname[0];
				$modules = str_replace('[mname]', $mname, $modules);
				$modules = str_replace('[name]', $one, $modules);
				$query = $db->Execute("select * from urls where module='$one'");
				if($res = $query->FetchRow()){
					if(!$url = $res['url']) $url = $one;
					$vkl = 'yes';
					$action = 'del';
					$alt = "Отключить";
				} else {
					$url = '';
					$vkl = 'no';
					$action = 'add';
					$alt = "Включить";
				}
				$modules = str_replace('[url]', $url, $modules);
				$modules = str_replace('[vkl]', $vkl, $modules);
				$modules = str_replace('[action]', $action, $modules);
				$modules = str_replace('[alt]', $alt, $modules);
			}
		}
		closedir ($data);
		$content = str_replace('[modules]', $modules, $content);
		
		$GLOBAL['content'] = $content;
		return $GLOBAL;
	}
}
?>