<?php
class menu {
	function content($db){
		$GLOBAL = array();
		
		$page   = $_REQUEST['page'];
		$action = $_REQUEST['action'];
		$menuid = $_REQUEST['menu'];
		$id     = $_REQUEST['id'];
		
		$menu = '';
		if(!$page) {
			if(!$menuid) $menu .= 'Меню'; else $menu .= '<a href="?module=menu">Меню</a>';
		} else $menu .= '<a href="?module=menu">Меню</a>';
		if($page == 'template') $menu .= ' | Шаблоны'; else $menu .= ' | <a href="?module=menu&page=template">Шаблоны</a>';
		
		if(!$page){
			if(!$menuid){
				switch ($action){
					case '':
						$content .= $this->type_view($db);
					break;
					case 'add':
						$content .= $this->type_add($db);
					break;
					case 'edit':
						$content .= $this->type_edit($db);
					break;
					case 'del':
						$content .= $this->type_del($db);
					break;
				}
			} else {
				switch ($action){
					case '':
						$content .= $this->menu_view($db);
					break;
					case 'add':
						$content .= $this->menu_add($db);
					break;
					case 'edit':
						$content .= $this->menu_edit($db);
					break;
					case 'del':
						$content .= $this->menu_del($db);
					break;
					case 'up':
						$this->up('menu', $id, $db, $menuid);
					break;
					case 'down':
						$this->down('menu', $id, $db, $menuid);
					break;
				}
			}
		} else {
				switch ($action){
					case '':
						$content .= $this->template_view($db);
					break;
					case 'add':
						$content .= $this->template_add($db);
					break;
					case 'edit':
						$content .= $this->template_edit($db);
					break;
					case 'del':
						$content .= $this->template_del($db);
					break;
				}
		}
		
		
		
		
		
		$content = str_replace('[mymenu]', $menu, $content);
		
		$GLOBAL['content'] = $content;
		return $GLOBAL;
	}
	
	function menu_add($db){
		if($_SESSION['admin']['type'] != 'admin' and $_SESSION['admin']['type'] != 'moder'){
				$content = file_get_contents(PATH.'modules/admins/tmp/admin/request.tpl');
				$content = str_replace('[alert]', 'Данное действие Вам недоступно', $content);
				$content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
				echo $content;
				exit;
		}
		$send = $_REQUEST['send'];
		$menuid = $_REQUEST['menu'];
		if(!$send){
			$content = file_get_contents(PATH.'modules/menu/tmp/admin/menu_add.tpl');
			$content = str_replace('[menuid]', $menuid, $content);
		} else {
			$url = $_REQUEST['url'];
			$url = htmlspecialchars($url, ENT_QUOTES);
			$text = $_REQUEST['text'];
			$text = htmlspecialchars($text, ENT_QUOTES);
			$blank = $_REQUEST['blank'];
			$blank = (int)htmlspecialchars($blank, ENT_QUOTES);
			$position = $db->Execute("select * from menu where id=$menuid order by position desc");
			$position = $position->FetchRow();
			$position = (int)$position['position']+1;
			
			$db->Execute("insert into menu(url, text, position, id, blank) values('$url', '$text', $position, $menuid, $blank)");
			$content = file_get_contents(PATH.'modules/menu/tmp/admin/reguest.tpl');
			$content = str_replace('[alert]', 'Ссылка успешно создана', $content);
			$content = str_replace('[url]', "?module=menu&menu=$menuid", $content);
			echo $content;
			exit;
		}
		return $content;
	}
	
	function menu_edit($db){
		if($_SESSION['admin']['type'] != 'admin' and $_SESSION['admin']['type'] != 'moder'){
				$content = file_get_contents(PATH.'modules/admins/tmp/admin/request.tpl');
				$content = str_replace('[alert]', 'Данное действие Вам недоступно', $content);
				$content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
				echo $content;
				exit;
		}
		$send = $_REQUEST['send'];
		$menuid = $_REQUEST['menu'];
		$id = $_REQUEST['id'];
		$query = $db->Execute("select * from menu where link_id=$id");
		if(!$res0 = $query->FetchRow()){
			header('location:?module=menu&page=template');
			exit;
		}
		if(!$send){
			$content = file_get_contents(PATH.'modules/menu/tmp/admin/menu_edit.tpl');
			$content = str_replace('[url]', $res0['url'], $content);
			$content = str_replace('[text]', $res0['text'], $content);
			if($res0['blank'] == 1) $blank = 'checked="checked"'; else $blank = '';
			$content = str_replace('[blank]', $blank, $content);
			$content = str_replace('[menuid]', $menuid, $content);
		} else {
			$url = $_REQUEST['url'];
			$url = htmlspecialchars($url, ENT_QUOTES);
			$text = $_REQUEST['text'];
			$text = htmlspecialchars($text, ENT_QUOTES);
			$blank = $_REQUEST['blank'];
			$blank = (int)htmlspecialchars($blank, ENT_QUOTES);
			$db->Execute("update menu set url='$url', text='$text', blank='$blank' where link_id=$id");
			$content = file_get_contents(PATH.'modules/menu/tmp/admin/reguest.tpl');
			$content = str_replace('[alert]', 'Ссылка успешно отредактирована', $content);
			$content = str_replace('[url]', "?module=menu&menu=$menuid", $content);
			echo $content;
			exit;
		}
		return $content;
	}
	
	function menu_del($db){
		if($_SESSION['admin']['type'] != 'admin' and $_SESSION['admin']['type'] != 'moder'){
				$content = file_get_contents(PATH.'modules/admins/tmp/admin/request.tpl');
				$content = str_replace('[alert]', 'Данное действие Вам недоступно', $content);
				$content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
				echo $content;
				exit;
		}
		$send = $_REQUEST['send'];
		$menuid = $_REQUEST['menu'];
		$id = $_REQUEST['id'];
		$query = $db->Execute("select * from menu where link_id=$id");
		if(!$res0 = $query->FetchRow()){
			header('location:?module=menu&page=template');
			exit;
		}
		
		$position = $res0['position'];
		$db->Execute("update menu set position=position-1 where position > $position and id=$menuid");
		
		$db->Execute("delete from menu where link_id=$id");
		$content = file_get_contents(PATH.'modules/menu/tmp/admin/reguest.tpl');
		$content = str_replace('[alert]', 'Ссылка успешно удалена', $content);
		$content = str_replace('[url]', "?module=menu&menu=$menuid", $content);
		echo $content;
		exit;
		
		return $content;
	}
	
	function menu_view($db){
		$menuid = $_REQUEST['menu'];
		$content = file_get_contents(PATH.'modules/menu/tmp/admin/menu_top.tpl');
		$query = $db->Execute("select * from menu_names where id='$menuid'");
		$res0 = $query->FetchRow();
		
		
		$menu = '';
		$query = $db->Execute("select * from menu where id=$menuid order by position asc");
		while ($res = $query->FetchRow()){
			$menu .= file_get_contents(PATH.'modules/menu/tmp/admin/menu_one.tpl');
			$menu = str_replace('[url]', $res['url'], $menu);
			$menu = str_replace('[text]', $res['text'], $menu);
			$menu = str_replace('[id]', $res['link_id'], $menu);
		}
		if($menu) $content = str_replace('[menu]', $menu, $content);
		else $content = file_get_contents(PATH.'modules/menu/tmp/admin/menu_no.tpl');
		$content = str_replace('[menuid]', $menuid, $content);
		$content = str_replace('[menu_name]', $res0['name'], $content);
		return $content;
	}
	
	function template_view($db){
		$content = file_get_contents(PATH.'modules/menu/tmp/admin/template_top.tpl');
		$menu = '';
		$query = $db->Execute("select * from menu_template as a left join menu_names as b on(a.id=b.id) order by b.name");
		while ($res = $query->FetchRow()){
			$menu .= file_get_contents(PATH.'modules/menu/tmp/admin/template_one.tpl');
			$menu = str_replace('[template]', $res['template'], $menu);
			$menu = str_replace('[name]', $res['name'], $menu);
			$menu = str_replace('[id]', $res['tid'], $menu);
		}
		if($menu) $content = str_replace('[menu]', $menu, $content);
		else $content = file_get_contents(PATH.'modules/menu/tmp/admin/template_no.tpl');
		
		return $content;
	}
	
	function template_add($db){
		if($_SESSION['admin']['type'] != 'admin'){
				$content = file_get_contents(PATH.'modules/admins/tmp/admin/request.tpl');
				$content = str_replace('[alert]', 'Данное действие Вам недоступно', $content);
				$content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
				echo $content;
				exit;
		}
		$send = $_REQUEST['send'];
		if(!$send){
			$content = file_get_contents(PATH.'modules/menu/tmp/admin/template_add.tpl');
			$menu = '<option value="0">--Выберите--</option>';
			$query = $db->Execute("select * from menu_names order by name asc");
			while ($res = $query->FetchRow()){
				$menu .= '<option value="'.$res['id'].'">'.$res['name'].'</option>';
			}
			$content = str_replace('[menu]', $menu, $content);
			$content = str_replace('[id]', 0, $content);
		} else {
			$menu = $_REQUEST['menu'];
			$menu = htmlspecialchars($menu, ENT_QUOTES);
			$template = $_REQUEST['template'];
			$template = htmlspecialchars($template, ENT_QUOTES);
			$db->Execute("insert into menu_template(id, template) values($menu, '$template')");
			$content = file_get_contents(PATH.'modules/menu/tmp/admin/reguest.tpl');
			$content = str_replace('[alert]', 'Шаблон меню успешно создан', $content);
			$content = str_replace('[url]', '?module=menu&page=template', $content);
			echo $content;
			exit;
		}
		return $content;
	}
	
	function template_edit($db){
		if($_SESSION['admin']['type'] != 'admin'){
				$content = file_get_contents(PATH.'modules/admins/tmp/admin/request.tpl');
				$content = str_replace('[alert]', 'Данное действие Вам недоступно', $content);
				$content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
				echo $content;
				exit;
		}
		$send = $_REQUEST['send'];
		$id = $_REQUEST['id'];
		$query = $db->Execute("select * from menu_template where tid=$id");
		if(!$res0 = $query->FetchRow()){
			header('location:?module=menu&page=template');
			exit;
		}
		
		if(!$send){
			$content = file_get_contents(PATH.'modules/menu/tmp/admin/template_edit.tpl');
			$menu = '<option value="0">--Выберите--</option>';
			$query = $db->Execute("select * from menu_names order by name asc");
			while ($res = $query->FetchRow()){
				if($res['id'] == $res0['id']) $s = 'selected'; else $s = '';
				$menu .= '<option value="'.$res['id'].'" '.$s.'>'.$res['name'].'</option>';
			}
			$content = str_replace('[menu]', $menu, $content);
			$content = str_replace('[template]', $res0['template'], $content);
			$content = str_replace('[id]', $res0['tid'], $content);
		} else {
			$menu = $_REQUEST['menu'];
			$menu = htmlspecialchars($menu, ENT_QUOTES);
			$template = $_REQUEST['template'];
			$template = htmlspecialchars($template, ENT_QUOTES);
			$db->Execute("update menu_template set id=$menu, template='$template' where tid=$id");
			$content = file_get_contents(PATH.'modules/menu/tmp/admin/reguest.tpl');
			$content = str_replace('[alert]', 'Шаблон меню успешно отредактирован', $content);
			$content = str_replace('[url]', '?module=menu&page=template', $content);
			echo $content;
			exit;
		}
		return $content;
	}
	
	function template_del($db){
		if($_SESSION['admin']['type'] != 'admin'){
				$content = file_get_contents(PATH.'modules/admins/tmp/admin/request.tpl');
				$content = str_replace('[alert]', 'Данное действие Вам недоступно', $content);
				$content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
				echo $content;
				exit;
		}
		$id = $_REQUEST['id'];
		$query = $db->Execute("select * from menu_template where tid=$id");
		if(!$res0 = $query->FetchRow()){
			header('location:?module=menu&page=template');
			exit;
		}
		
		$db->Execute("delete from menu_template where tid='$id'");
		$content = file_get_contents(PATH.'modules/menu/tmp/admin/reguest.tpl');
		$content = str_replace('[alert]', 'Шаблон меню успешно удален', $content);
		$content = str_replace('[url]', '?module=menu&page=template', $content);
		echo $content;
		exit;
	}
	
	function type_add($db){
		if($_SESSION['admin']['type'] != 'admin'){
				$content = file_get_contents(PATH.'modules/admins/tmp/admin/request.tpl');
				$content = str_replace('[alert]', 'Данное действие Вам недоступно', $content);
				$content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
				echo $content;
				exit;
		}
		$send = $_REQUEST['send'];
		if(!$send){
			$content = file_get_contents(PATH.'modules/menu/tmp/admin/type_add.tpl');
		} else {
			$name = $_REQUEST['name'];
			$name = htmlspecialchars($name, ENT_QUOTES);
			$db->Execute("insert into menu_names(name) values('$name')");
			$content = file_get_contents(PATH.'modules/menu/tmp/admin/reguest.tpl');
			$content = str_replace('[alert]', 'Меню успешно создано', $content);
			$content = str_replace('[url]', "?module=menu&menu=$menu", $content);
			echo $content;
			exit;
		}
		return $content;
	}
	
	function type_edit($db){
		if($_SESSION['admin']['type'] != 'admin'){
				$content = file_get_contents(PATH.'modules/admins/tmp/admin/request.tpl');
				$content = str_replace('[alert]', 'Данное действие Вам недоступно', $content);
				$content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
				echo $content;
				exit;
		}
		$send = $_REQUEST['send'];
		$id = $_REQUEST['id'];
		$query = $db->Execute("select * from menu_names where id=$id");
		if(!$res = $query->FetchRow()){
			header('location:?module=menu');
			exit;
		}
		
		if(!$send){
			$content = file_get_contents(PATH.'modules/menu/tmp/admin/type_edit.tpl');
			$content = str_replace('[name]', $res['name'], $content);
		} else {
			$name = $_REQUEST['name'];
			$name = htmlspecialchars($name, ENT_QUOTES);
			$db->Execute("update menu_names set name = '$name' where id=$id");
			$content = file_get_contents(PATH.'modules/menu/tmp/admin/reguest.tpl');
			$content = str_replace('[alert]', 'Меню успешно отредактировано', $content);
			$content = str_replace('[url]', '?module=menu', $content);
			echo $content;
			exit;
		}
		return $content;
	}
	
	function type_del($db){
		if($_SESSION['admin']['type'] != 'admin'){
				$content = file_get_contents(PATH.'modules/admins/tmp/admin/request.tpl');
				$content = str_replace('[alert]', 'Данное действие Вам недоступно', $content);
				$content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
				echo $content;
				exit;
		}
		$id = $_REQUEST['id'];
		$query = $db->Execute("select * from menu_names where id=$id");
		if(!$res = $query->FetchRow()){
			header('location:?module=menu');
			exit;
		}
		
		$db->Execute("delete from menu_names where id='$id'");
		$content = file_get_contents(PATH.'modules/menu/tmp/admin/reguest.tpl');
		$content = str_replace('[alert]', 'Меню успешно удалено', $content);
		$content = str_replace('[url]', '?module=menu', $content);
		echo $content;
		exit;
	}
	
	function type_view($db){
		$content = file_get_contents(PATH.'modules/menu/tmp/admin/type_top.tpl');
		$menu = '';
		$query = $db->Execute("select * from menu_names order by name");
		while ($res = $query->FetchRow()){
			$menu .= file_get_contents(PATH.'modules/menu/tmp/admin/type_one.tpl');
			$menu = str_replace('[name]', $res['name'], $menu);
			$menu = str_replace('[id]', $res['id'], $menu);
		}
		if($menu) $content = str_replace('[menu]', $menu, $content);
		else $content = file_get_contents(PATH.'modules/menu/tmp/admin/type_no.tpl');
		
		return $content;
	}
	
	function up($table, $id, $db, $menuid) {
		if($_SESSION['admin']['type'] != 'admin'){
				$content = file_get_contents(PATH.'modules/admins/tmp/admin/request.tpl');
				$content = str_replace('[alert]', 'Данное действие Вам недоступно', $content);
				$content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
				echo $content;
				exit;
		}
		// Уменьшаем параметр position
		$query = $db->Execute("select position from $table where link_id=$id");
		$res = $query->FetchRow();
		$position = $res ['position'];
		$new_position = $position - 1;
		if ($new_position != 0) {
			$db->Execute("update $table set position=$position where position = $new_position and id = $menuid");
			$db->Execute("update $table set position=$new_position where link_id=$id");
		}
		header ( 'location:' . $_SERVER ['HTTP_REFERER'] );
		exit;
	}
	
	function down($table, $id, $db, $menuid) {
		if($_SESSION['admin']['type'] != 'admin'){
				$content = file_get_contents(PATH.'modules/admins/tmp/admin/request.tpl');
				$content = str_replace('[alert]', 'Данное действие Вам недоступно', $content);
				$content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
				echo $content;
				exit;
		}
		// Уменьшаем параметр position
		$query = $db->Execute("select position from $table where  id = $menuid order by position desc");
		$res = $query->FetchRow();
		$top_position = $res ['position'];
		$query = $db->Execute("select position from $table where link_id=$id");
		$res = $query->FetchRow();
		$position = $res ['position'];
		$new_position = $position + 1;
		if ($new_position <= $top_position) {
			$db->Execute("update $table set position=$position where position = $new_position and id = $menuid");
			$db->Execute("update $table set position=$new_position where link_id=$id");
		}
		header ( 'location:' . $_SERVER ['HTTP_REFERER'] );
		exit;
	}
}
?>