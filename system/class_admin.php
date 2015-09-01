<?php

class class_index {

    public $GLOBAL;
    public $system;

    function content() {
        
        global $db;
        global $smarty;
        
        $_SESSION['no_view_modules'] = array('.', '..', 'concurs', 'write', 'admins', 'admin_fp', 'admin_auth', 'modules', 'system', 'menu', 'page404', '404', 'firstpage', 'online', 'search', 'tags');
        $this->GLOBAL['url'] = @$url;
        
        //загружаем шаблон
        $content = file_get_contents(PATH . 'admin_tmp/index.tpl');

        //Запускаем модули обработки информации
        include PATH . 'modules/admin_auth/class_admin_admin_auth.php';
        include PATH . 'modules/search/search_class.php';
        $s = new search();
        $auth = new admin_auth();
        $this->GLOBAL['admin'] = $auth->start($db, @$url);
        
        $cur_admins = $db->Execute("SELECT * FROM admins WHERE active=1 AND type!='admin'");
        $aids = array();
        while ($vs = $cur_admins->FetchRow()) {
            $aids[] = $vs['id'];
        }
        $aids = "(" . implode(",", $aids) . ")";

        $cur_sites = $db->Execute("SELECT * FROM sayty WHERE uid IN " . $aids);
        $sids = array();
        while ($vs = $cur_sites->FetchRow()) {
            $sids[] = $vs['id'];
        }
        $sids = "(" . implode(",", $sids) . ")";


        $z12 = $db->Execute("SELECT count(*) FROM zadaniya WHERE vrabote=1 AND etxt=1 AND (vipolneno=0 or vipolneno IS NULL) AND (dorabotka=0 or dorabotka IS NULL) AND (navyklad=0 or navyklad IS NULL) AND (vilojeno=0 or vilojeno IS NULL) AND sid IN " . $sids);
        $z12 = $z12->FetchRow();
        $z12 = $z12['count(*)'];
        $z22 = $db->Execute("select count(*) from zadaniya where navyklad=1 AND sid IN " . $sids);
        $z22 = $z22->FetchRow();
        $z22 = $z22['count(*)'];
        $z32 = $db->Execute("SELECT count(*) FROM zadaniya WHERE (vrabote=0 or vrabote IS NULL) AND (vipolneno=0 or vipolneno IS NULL) AND (dorabotka=0 or dorabotka IS NULL) AND (navyklad=0 or navyklad IS NULL) AND (vilojeno=0 or vilojeno IS NULL) AND (to_remove=0 or to_remove IS NULL) AND (removed=0 or removed IS NULL) AND sid IN " . $sids);
        $z32 = $z32->FetchRow();
        $z32 = $z32['count(*)'];
        $z42 = $db->Execute("select count(*) from zadaniya where vilojeno=1 AND sid IN " . $sids);
        $z42 = $z42->FetchRow();
        $z42 = $z42['count(*)'];
        
        $to_remove = $db->Execute("select count(*) from zadaniya where to_remove=1 AND sid IN " . $sids)->FetchRow();
        $removed = $db->Execute("select count(*) from zadaniya where removed=1 AND sid IN " . $sids)->FetchRow();

        $this->GLOBAL['all_vrabote'] = $z12;
        $this->GLOBAL['all_navyklad'] = $z22;
        $this->GLOBAL['all_neobrabot'] = $z32;
        $this->GLOBAL['all_vilojeno'] = $z42;
        $this->GLOBAL['all_to_remove'] = $to_remove['count(*)'];
        $this->GLOBAL['all_removed'] = $removed['count(*)'];
        $this->auth($db, $auth);
        $this->menu($db);

        if (isset($_REQUEST['q']) && !empty($_REQUEST['q'])) {
            $rez = $s->search_admin($db);
            $content = str_replace('[content]', $rez, $content);
        } else {
            $this->page();
        }

        $content = str_replace('[search]', $s->form(), $content);
        $content = str_replace('[admin]', $this->GLOBAL['admin']['login'], $content);
        $content = str_replace('[vrabote]', $this->GLOBAL['all_vrabote'], $content);
        $content = str_replace('[navyklad]', $this->GLOBAL['all_navyklad'], $content);
        $content = str_replace('[neobrabot]', $this->GLOBAL['all_neobrabot'], $content);
        $content = str_replace('[vilojeno]', $this->GLOBAL['all_vilojeno'], $content);
        $content = str_replace('[to_remove]', $this->GLOBAL['all_to_remove'], $content);
        $content = str_replace('[removed]', $this->GLOBAL['all_removed'], $content);
        foreach ($this->GLOBAL as $key => $value) {
            if (!is_array($value))
                $content = str_replace('[' . $key . ']', $value, $content);
        }

        $content = str_replace('[not_access_manager]', "", $content);
        return $content;
    }

    function page() {
        global $db;
        global $smarty;

        $module = $_REQUEST['module'];
        $query = $db->Execute("select * from urls where url='$module'");
        $res = $query->FetchRow();
        $mod = $res['module'];
        if ($module == '')
            $mod = 'admin_fp';
        if ($module == 'admins')
            $mod = 'admins';
        if ($module == 'system')
            $mod = 'system';
        if ($module == 'modules')
            $mod = 'modules';
        if ($module == 'menu')
            $mod = 'menu';
        if ($module == 'admin_auth')
            $mod = 'admin_auth';
        if (!$mod) {
            header('location:/404.html');
            exit;
        }

        if ($mod != 'admin_auth')
            include PATH . 'modules/' . $mod . '/class_admin_' . $mod . '.php';

        $class = new $mod;
        $GLOBAL = $class->content($db, $smarty);

        $this->GLOBAL['title'] = @$GLOBAL['title'];
        $this->GLOBAL['description'] = @$GLOBAL['description'];
        $this->GLOBAL['keywords'] = @$GLOBAL['keywords'];
        $this->GLOBAL['content'] = @$GLOBAL['content'];
        $this->GLOBAL['page_title'] = @$GLOBAL['page_title'];
        //var_dump($this->GLOBAL['content']);
    }

    function auth($db, $auth) {
        if (!$this->GLOBAL['admin']) {
            $auth->login($db);
            exit;
        } else {
            $this->GLOBAL['auth'] = file_get_contents(PATH . 'modules/admin_auth/tmp/logined.tpl');
            $this->GLOBAL['auth'] = str_replace('[login]', $this->GLOBAL['admin']['login'], $this->GLOBAL['auth']);
        }
    }

    function menu($db) {
        $query = $db->Execute("select * from urls order by name asc");
        $menu = '';
        while ($res = $query->FetchRow()) {
            $one = $res['url'];
            if (!in_array($one, $_SESSION['no_view_modules'])) {
                $menu .= '<a href="?module=' . $res['url'] . '">' . $res['name'] . '</a>';
            }
        }
        $this->GLOBAL['modules_menu'] = $menu;
    }

}

?>