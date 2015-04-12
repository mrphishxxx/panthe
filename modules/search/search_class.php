<?php

class search {

    function search1($db, $user) {
        $search = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $_REQUEST['q']);
        $good = trim(preg_replace("/\s(\S{1,2})\s/", " ", ereg_replace(" +", "  ", " $search ")));
        $good = ereg_replace(" +", " ", $good);
        $search = str_replace(" ", "%' OR field LIKE '%", $good);
        $content = file_get_contents(PATH . 'modules/search/tmp/search_view.tpl');
        $query = $db->Execute("select * from zadaniya WHERE uid=" . $user['id'] . " AND  (sistema LIKE '%" . $search . "%' 
	 or ankor LIKE '%" . $search . "%'
	 or tema LIKE '%" . $search . "%'
	 or keywords LIKE '%" . $search . "%'
	 or text LIKE '%" . $search . "%'
	 or url_statyi LIKE '%" . $search . "%'
	 or url_statyi LIKE '%" . $search . "%'
	)");
        $n = 0;
        while ($res = $query->FetchRow()) {
            $zadaniya .= file_get_contents(PATH . 'modules/user/tmp/zadaniya_one.tpl');
            $zadaniya = str_replace('[url]', $res['url'], $zadaniya);
            $zadaniya = str_replace('[id]', $res['id'], $zadaniya);
            $system = $res['sid'];
            $system = $db->Execute("select * from sayty where id=$system");
            $system = $system->FetchRow();
            $system = $system['url'];
            $zadaniya = str_replace('[sistema]', $system, $zadaniya);
            $zadaniya = str_replace('[sistemaggl]', $res['sistema'], $zadaniya);
            $zadaniya = str_replace('[date]', date('d.m.Y', $res['date']), $zadaniya);
            $zadaniya = str_replace('[ankor]', $res['ankor'], $zadaniya);
            $zadaniya = str_replace('[tema]', $res['tema'], $zadaniya);
            $zadaniya = str_replace('[url_statyi]', $res['url_statyi'], $zadaniya);
            if ($res['dorabotka'])
                $new_s = "in-work";
            else if ($res['vipolneno'])
                $new_s = "done";
            else if ($res['vrabote'])
                $new_s = "working";
            else if ($res['navyklad'])
                $new_s = "ready";
            else if ($res['vilojeno'])
                $new_s = "vilojeno";
            else
                $bg = '';
//			$zadaniya = str_replace('[bg]', $bg, $zadaniya);
            $zadaniya = str_replace('[status]', $new_s, $zadaniya);
        }


        if ($zadaniya)
            $zadaniya = str_replace('[zadaniya]', $zadaniya, file_get_contents(PATH . 'modules/user/tmp/zadaniya_top.tpl'));
        else
            $zadaniya = file_get_contents(PATH . 'modules/user/tmp/no.tpl');


        $content = str_replace('[zadaniya]', $zadaniya, $content);
        $content = str_replace('[uid]', $uid, $content);
        $content = str_replace('[sid]', $sid, $content);
        $content = str_replace('[symb]', $symb, $content);

        return $content;
    }

    function search_admin($db) {
        $search = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $_REQUEST['q']);
        $good = trim(preg_replace("/\s(\S{1,2})\s/", " ", ereg_replace(" +", "  ", " $search ")));
        $good = ereg_replace(" +", " ", $good);
        $search = str_replace(" ", "%' OR field LIKE '%", $good);
        $content = file_get_contents(PATH . 'modules/search/tmp/search_view_admin.tpl');

//		$search = iconv("utf-8", "cp1251", $search);
        $q = "select * from zadaniya WHERE (sistema LIKE '%" . $search . "%' 
		 or ankor LIKE '%" . $search . "%'
		 or tema LIKE '%" . $search . "%'
		 or keywords LIKE '%" . $search . "%'
		 or text LIKE '%" . $search . "%'
		 or url_statyi LIKE '%" . $search . "%'
		 or url_statyi LIKE '%" . $search . "%'
		 or task_id LIKE '%" . $search . "%'
		)";
        $query = $db->Execute($q);
        $n = 0;

        if ($query) {
            while ($res = $query->FetchRow()) {
                $zadaniya .= file_get_contents(PATH . 'modules/admins/tmp/admin/zadaniya_one.tpl');
                $zadaniya = str_replace('[url]', $res['url'], $zadaniya);
                $zadaniya = str_replace('[id]', $res['id'], $zadaniya);
                $system = $res['sid'];
                $system = $db->Execute("select * from sayty where id=$system");
                $system = $system->FetchRow();
                $system = $system['url'];
                $zadaniya = str_replace('[etxt_id]', $res['task_id'], $zadaniya);
                $zadaniya = str_replace('[sistema]', $system, $zadaniya);
                $zadaniya = str_replace('[sistemaggl]', $res['sistema'], $zadaniya);
                $zadaniya = str_replace('[date]', date('d.m.Y', $res['date']), $zadaniya);
                $zadaniya = str_replace('[ankor]', $res['ankor'], $zadaniya);
                $zadaniya = str_replace('[tema]', $res['tema'], $zadaniya);
                $zadaniya = str_replace('[url_statyi]', $res['url_statyi'], $zadaniya);
                if ($res['dorabotka'])
                    $bg = '#f6b300';
                else if ($res['vipolneno'])
                    $bg = '#83e24a';
                else if ($res['vrabote'])
                    $bg = '#00baff';
                else if ($res['navyklad'])
                    $bg = '#ffde96';
                else
                    $bg = '';
                $zadaniya = str_replace('[bg]', $bg, $zadaniya);
            }
        }


        if ($zadaniya)
            $zadaniya = str_replace('[zadaniya]', $zadaniya, file_get_contents(PATH . 'modules/admins/tmp/admin/zadaniya_top.tpl'));
        else
            $zadaniya = file_get_contents(PATH . 'modules/admins/tmp/admin/no.tpl');


        $content = str_replace('[zadaniya]', $zadaniya, $content);
        $content = str_replace('[uid]', $uid, $content);
        $content = str_replace('[sid]', $sid, $content);
        $content = str_replace('[symb]', $symb, $content);

        return $content;
    }

    function form() {
        $content = file_get_contents(PATH . 'modules/search/tmp/search_form.tpl');
        $q = ' ';
        if (@$_REQUEST['q'])
            $q = $_REQUEST['q'];
        $content = str_replace('[search]', $q, $content);
        return $content;
    }

}

?>