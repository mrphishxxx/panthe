<?php
class admin_fp {
	function content($db){
		header('location:?module=admins');
		exit;
		
		$GLOBAL = array();
		
		$content = '<h1>Разделы</h1>';
		$content .= '<a href="?module=admins"><img alt="Администраторы" title="Администраторы" src="/admin_tmp/img/admins.gif" style="border:solid 1px #666; margin:10px;"></a>';
		$content .= '<a href="?module=system"><img alt="Системное" title="Системное" src="/admin_tmp/img/admins.gif" style="border:solid 1px #666; margin:10px;"></a>';
		
		
		$content .= '<h1>Модули</h1>';
		
		$query = $db->Execute("select * from urls order by module asc");
		$menu = '';
		while ($res = $query->FetchRow()) {
			$one = $res['url'];
			$mod = $res['module'];
			if(!in_array($one, $_SESSION['no_view_modules'])) {
				$content .= '<a href="?module='.$mod.'"><img alt="'.$res['name'].'" title="'.$res['name'].'" src="/modules/'.$mod.'/tmp/admin/img/admin_icon.gif" style="border:solid 1px #666; margin:10px;"></a>';
			}
		}
		
		
		$GLOBAL['content'] = $content;
		return $GLOBAL;
	}
}
?>