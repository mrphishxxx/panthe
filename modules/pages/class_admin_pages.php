<?php
class pages {
	function content($db){
		$GLOBAL = array();
		
		$action = $_REQUEST['action'];
		$id = $_REQUEST['id'];
		
		switch ($action){
			case '':
				$content .= $this->view($db);
			break;
			case 'add':
				$content .= $this->add($db);
			break;
			case 'edit':
				$content .= $this->edit($db);
			break;
			case 'del':
				$content .= $this->del($db);
			break;
		}
		$GLOBAL['content'] = $content;
		return $GLOBAL;
	}
	

	function add($db){
		$send = $_REQUEST['send'];
		if(!$send){
			$content = file_get_contents(PATH.'modules/pages/tmp/admin/add.tpl');
			$content = str_replace('[content]', file_get_contents(PATH.'includes/ckeditor/editor.tpl'), $content);
			$content = str_replace('[name]', 'text', $content);
			$content = str_replace('[value]', '', $content);
		} else {
			$name = $_REQUEST['name'];
			$name = htmlspecialchars($name, ENT_QUOTES);
			$title = $_REQUEST['title'];
			$title = htmlspecialchars($title, ENT_QUOTES);
			$description = $_REQUEST['description'];
			$description = htmlspecialchars($description, ENT_QUOTES);
			$keywords = $_REQUEST['keywords'];
			$keywords = htmlspecialchars($keywords, ENT_QUOTES);
			$url = $_REQUEST['url'];
			$url = htmlspecialchars($url, ENT_QUOTES);
			$text = $_REQUEST['text'];
			$text = htmlspecialchars($text, ENT_QUOTES);
			$date = time();
						
			$query = $db->Execute("select * from pages where url = '$url'");
			while ($res = $query->FetchRow()){
				$url .= '1';
				$query = $db->Execute("select * from pages where url = '$url'");
			}
			
			$db->Execute("insert into pages(name, title, description, keywords, page_title, content, url) values('$name', '$title', '$description', '$keywords', '$name', '$text', '$url')");
			$content = file_get_contents(PATH.'modules/pages/tmp/admin/reguest.tpl');
			$content = str_replace('[alert]', 'Страница успешно создана', $content);
			$content = str_replace('[url]', '?module=pages', $content);
			echo $content;
			exit;
		}
		return $content;
	}
	
	function edit($db){
		$send = $_REQUEST['send'];
		$id = $_REQUEST['id'];
		$query = $db->Execute("select * from pages where id=$id");
		if(!$res = $query->FetchRow()){
			header('location:?module=pages');
			exit;
		}
		
		if(!$send){
			$content = file_get_contents(PATH.'modules/pages/tmp/admin/edit.tpl');
			$editor = file_get_contents(PATH.'includes/ckeditor/editor.tpl');
			$editor = str_replace('[name]', 'text', $editor);
			$editor = str_replace('[value]', $res['content'], $editor);
			$content = str_replace('[content]', $editor, $content);
			foreach ($res as $k=>$v){
				$content = str_replace('['.$k.']', $v, $content);
			}
		} else {
			$name = $_REQUEST['name'];
			$name = htmlspecialchars($name, ENT_QUOTES);
			$title = $_REQUEST['title'];
			$title = htmlspecialchars($title, ENT_QUOTES);
			$description = $_REQUEST['description'];
			$description = htmlspecialchars($description, ENT_QUOTES);
			$keywords = $_REQUEST['keywords'];
			$keywords = htmlspecialchars($keywords, ENT_QUOTES);
			$url = $_REQUEST['url'];
			$url = htmlspecialchars($url, ENT_QUOTES);
			$text = $_REQUEST['text'];
			$text = htmlspecialchars($text, ENT_QUOTES);
			
			$query = $db->Execute("select * from pages where url = '$url' and id!=$id");
			while ($res = $query->FetchRow()){
				$url .= '1';
				$query = $db->Execute("select * from pages where url = '$url' and id!=$id");
			}
			
			$db->Execute("update pages set name='$name', title='$title', description='$description', keywords='$description', page_title='$name', content='$text', url='$url' where id=$id");
			$content = file_get_contents(PATH.'modules/pages/tmp/admin/reguest.tpl');
			$content = str_replace('[alert]', 'Страница успешно отредактирована', $content);
			$content = str_replace('[url]', '?module=pages', $content);
			echo $content;
			exit;
		}
		return $content;
	}
	
	function del($db){
		$id = $_REQUEST['id'];
		$query = $db->Execute("select * from pages where id=$id");
		if(!$res = $query->FetchRow()){
			header('location:?module=pages');
			exit;
		}
		
		$db->Execute("delete from pages where id='$id'");
		$content = file_get_contents(PATH.'modules/pages/tmp/admin/reguest.tpl');
		$content = str_replace('[alert]', 'Страница успешно удалена', $content);
		$content = str_replace('[url]', '?module=pages', $content);
		echo $content;
		exit;
	}
	
	function view($db){
		$content = file_get_contents(PATH.'modules/pages/tmp/admin/top.tpl');
		$pages = '';
		$query = $db->Execute("select * from pages where system != 1 order by title asc");
		while ($res = $query->FetchRow()){
			$pages .= file_get_contents(PATH.'modules/pages/tmp/admin/one.tpl');
			$pages = str_replace('[title]', $res['name'], $pages);
			$pages = str_replace('[url]', $res['url'], $pages);
			$pages = str_replace('[id]', $res['id'], $pages);
			if($res['uid'] == 0) $autor = 'Администрация';
			else {
				$uid = $res['uid'];
				$query2 = $db->Execute("select * from users where id=$uid");
				if($res2 = $query2->FetchRow()) $autor = $res2['login'];
				else $autor = 'Не определен';
			}
			$pages = str_replace('[autor]', $autor, $pages);
		}
		if($pages) $content = str_replace('[pages]', $pages, $content);
		else $content = file_get_contents(PATH.'modules/pages/tmp/admin/no.tpl');
		
		return $content;
	}
	
	function to_url($text){
		$array = array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z',
		'и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t',
		'у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'sch','ъ'=>'','ы'=>'i','ь'=>'',
		'э'=>'e','ю'=>'yu','я'=>'ya','А'=>'a','Б'=>'b','В'=>'v','Г'=>'g','Д'=>'d','Е'=>'e','Ё'=>'e',
		'Ж'=>'j','З'=>'z','И'=>'i','Й'=>'y','К'=>'k','Л'=>'l','М'=>'m','Н'=>'n','О'=>'o','П'=>'p','Р'=>'r',
		'С'=>'s','Т'=>'t','У'=>'u','Ф'=>'f','Х'=>'h','Ц'=>'c','Ч'=>'ch','Ш'=>'sh','Щ'=>'sch','Ъ'=>'',
		'Ы'=>'i','Ь'=>'','Э'=>'e','Ю'=>'yu','Я'=>'ya','~'=>'','!'=>'','@'=>'','#'=>'','$'=>'','%'=>'',
		'^'=>'','&'=>'','*'=>'','('=>'',')'=>'',' '=>'_','-'=>'','='=>'','+'=>'','['=>'',']'=>'','{'=>'',
		'}'=>'','\\'=>'','|'=>'','\''=>'','"'=>'',';'=>'',':'=>'',','=>'','<'=>'','.'=>'','>'=>'','/'=>'',
		'?'=>'','!'=>'','"'=>'','`'=>'','№'=>'','*'=>'');
		
		foreach ($array as $k => $v){
			$text = str_replace($k, $v, $text);
		}
		return $text;
	}
}
?>