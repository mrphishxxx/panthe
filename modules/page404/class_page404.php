<?php
class page404 {
	function content($db, $url){
		$GLOBAL = array();
		$GLOBAL['title'] = 'Такой страницы не существует';
		$GLOBAL['description'] = 'Такой страницы не существует';
		$GLOBAL['keywords'] = 'Такой страницы не существует';
		$GLOBAL['content'] = 'Такой страницы не существует';
		$GLOBAL['page_title'] = 'ОШИБКА 404!!!';
		return $GLOBAL;
	}
}
?>