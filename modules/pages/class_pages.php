<?php
class pages{
	function content($db, $url){
		$GLOBAL = array();
		$u = $url[1];
		$query = $db->Execute("select * from pages where url='$u'");
		$res = $query->FetchRow();
		if(!$res['id']) {
			header('location:/404.html');
			exit;
		}
		$GLOBAL['title'] = $res['title'];
		$GLOBAL['description'] = $res['description'];
		$GLOBAL['keywords'] = $res['keywords'];
		$GLOBAL['page_title'] = $res['page_title'];
		
		$content = file_get_contents(PATH.'modules/pages/tmp/view.tpl');
		$content = str_replace('[content]', $res['content'], $content);
		
		
		
		$GLOBAL['content'] = $content;
		return $GLOBAL;
	}
	
	function in_template($db, $url){
		
	}
}
?>