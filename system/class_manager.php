<?php

class class_manager {

    public $GLOBAL;
    public $system;

    function content() {

        global $db;        
        global $smarty;
        $auth_block = "";
        $module = isset($_REQUEST['module']) ? $_REQUEST['module'] : "admins";
        //загружаем шаблон
        $content = file_get_contents(PATH . 'admin_tmp/inside_manager.tpl');

        //Запускаем модули обработки информации
        include PATH . 'modules/search/search_class.php';
        $s = new search();

        //Вытаскиваем задания для меню
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
        $vrabote = $db->Execute("SELECT count(*) FROM zadaniya WHERE vrabote=1 AND etxt=1 AND (vipolneno=0 or vipolneno IS NULL) AND (dorabotka=0 or dorabotka IS NULL) AND (navyklad=0 or navyklad IS NULL) AND (vilojeno=0 or vilojeno IS NULL) AND sid IN " . $sids)->FetchRow();
        $navyklad = $db->Execute("select count(*) from zadaniya where navyklad=1 AND sid IN " . $sids)->FetchRow();
        $neobrabot = $db->Execute("SELECT count(*) FROM zadaniya WHERE (vrabote=0 or vrabote IS NULL) AND (vipolneno=0 or vipolneno IS NULL) AND (dorabotka=0 or dorabotka IS NULL) AND (navyklad=0 or navyklad IS NULL) AND (vilojeno=0 or vilojeno IS NULL) AND (to_remove=0 or to_remove IS NULL) AND (removed=0 or removed IS NULL) AND sid IN " . $sids)->FetchRow();
        $vilojeno = $db->Execute("select count(*) from zadaniya where vilojeno=1 AND sid IN " . $sids)->FetchRow();
        $to_remove = $db->Execute("select count(*) from zadaniya where to_remove=1 AND sid IN " . $sids)->FetchRow();
        $removed = $db->Execute("select count(*) from zadaniya where removed=1 AND sid IN " . $sids)->FetchRow();
        $this->GLOBAL['all_vrabote'] = $vrabote['count(*)'];
        $this->GLOBAL['all_navyklad'] = $navyklad['count(*)'];
        $this->GLOBAL['all_neobrabot'] = $neobrabot['count(*)'];
        $this->GLOBAL['all_vilojeno'] = $vilojeno['count(*)'];
        $this->GLOBAL['all_to_remove'] = $to_remove['count(*)'];
        $this->GLOBAL['all_removed'] = $removed['count(*)'];

        // Проверка авторизации
        if (@$_SESSION['manager']['id'] > 0) {
            $auth_block = '
                        <!-- userbar -->
                        <div id="userbar">
                                <a id="user_login" href="?module=managers&action=lk">' . $_SESSION['manager']['login'] . '</a>
                                <span class="sep"></span>
                                <a id="log_off" href="?module=managers&action=logout"><span class="ico"></span> <span>Выход</span> </a>
                        </div>
                ';
        } else {
            include PATH . 'modules/managers/class_admin_managers.php';
            $class = new managers();
            $class->login($db);
            exit;
        }

        if (isset($_REQUEST['q']) && !empty($_REQUEST['q'])) {
            $rez = $s->search_admin($db);
            $content = str_replace('[content]', $rez, $content);
        } else {
            include PATH . 'modules/' . $module . '/class_admin_' . $module . '.php';
            $class = new $module;
            $GLOBAL = $class->content($db, $smarty);
            $this->GLOBAL['title'] = @$GLOBAL['title'];
            $this->GLOBAL['description'] = @$GLOBAL['description'];
            $this->GLOBAL['keywords'] = @$GLOBAL['keywords'];
            $this->GLOBAL['content'] = @$GLOBAL['content'];
            $this->GLOBAL['page_title'] = @$GLOBAL['page_title'];
        }
        $admins_managers = array();
        $admins_manager = $db->Execute("SELECT id FROM admins WHERE type = 'admin' OR type = 'manager'");
        while ($user = $admins_manager->FetchRow()) {
            $admins_managers[] = $user['id'];
        }
        $admins_managers = "(" . implode(",", $admins_managers) . ")";
        /* Тикеты ВСЕГО */
        $new_tick = $db->Execute("SELECT COUNT(t.id) as newt FROM tickets t LEFT JOIN admins a ON IF (t.uid IN $admins_managers, a.id=t.to_uid, a.id=t.uid) WHERE a.id != 0 AND t.status != 0")->FetchRow();
        $all_tick = $db->Execute("SELECT COUNT(t.id) as allt FROM tickets t LEFT JOIN admins a ON IF (t.uid IN $admins_managers, a.id=t.to_uid, a.id=t.uid) WHERE a.id != 0")->FetchRow();
        $content = str_replace('[new_tick]', $new_tick['newt'], $content);
        $content = str_replace('[all_tick]', $all_tick['allt'], $content);

        /* Тикеты ПОЛЬЗОВАТЕЛИ */
        $new_tick_user = $db->Execute("SELECT COUNT(t.id) as newt FROM tickets t LEFT JOIN admins a ON IF (t.uid IN $admins_managers, a.id=t.to_uid, a.id=t.uid) WHERE t.status != 0 AND a.type = 'user'")->FetchRow();
        $all_tick_user = $db->Execute("SELECT COUNT(t.id) as newt FROM tickets t LEFT JOIN admins a ON IF (t.uid IN $admins_managers, a.id=t.to_uid, a.id=t.uid) WHERE a.type = 'user'")->FetchRow();
        $content = str_replace('[new_tick_user]', $new_tick_user['newt'], $content);
        $content = str_replace('[all_tick_user]', $all_tick_user['newt'], $content);

        /* Тикеты МОДЕРАТОРЫ */
        $new_tick_moder = $db->Execute("SELECT COUNT(t.id) as newt FROM tickets t LEFT JOIN admins a ON IF (t.uid IN $admins_managers, a.id=t.to_uid, a.id=t.uid) WHERE t.status != 0 AND a.type = 'moder'")->FetchRow();
        $all_tick_moder = $db->Execute("SELECT COUNT(t.id) as newt FROM tickets t LEFT JOIN admins a ON IF (t.uid IN $admins_managers, a.id=t.to_uid, a.id=t.uid) WHERE a.type = 'moder'")->FetchRow();
        $content = str_replace('[new_tick_moder]', $new_tick_moder['newt'], $content);
        $content = str_replace('[all_tick_moder]', $all_tick_moder['newt'], $content);

        /* Тикеты КОПИРАЙТЕРЫ */
        $new_tick_copywriter = $db->Execute("SELECT COUNT(t.id) as newt FROM tickets t LEFT JOIN admins a ON IF (t.uid IN $admins_managers, a.id=t.to_uid, a.id=t.uid) WHERE t.status != 0 AND a.type = 'copywriter'")->FetchRow();
        $all_tick_copywriter = $db->Execute("SELECT COUNT(t.id) as newt FROM tickets t LEFT JOIN admins a ON IF (t.uid IN $admins_managers, a.id=t.to_uid, a.id=t.uid) WHERE a.type = 'copywriter'")->FetchRow();
        $content = str_replace('[new_tick_copywriter]', $new_tick_copywriter['newt'], $content);
        $content = str_replace('[all_tick_copywriter]', $all_tick_copywriter['newt'], $content);
        
        /* Тикеты АДМИНИСТРАЦИИ */
        $new_tick_admin = $db->Execute("SELECT COUNT(t.id) as newt FROM tickets t LEFT JOIN admins a ON (t.uid IN $admins_managers AND t.to_uid IN $admins_managers) WHERE t.status != 0 AND a.type = 'admin'")->FetchRow();
        $all_tick_admin = $db->Execute("SELECT COUNT(t.id) as newt FROM tickets t LEFT JOIN admins a ON (t.uid IN $admins_managers AND t.to_uid IN $admins_managers) WHERE a.type = 'admin'")->FetchRow();
        $content = str_replace('[new_tick_admin]', $new_tick_admin['newt'], $content);
        $content = str_replace('[all_tick_admin]', $all_tick_admin['newt'], $content);


        $main_comment = $db->Execute("SELECT * FROM Message2002 WHERE Sub_Class_ID = 22")->FetchRow();
        $content = str_replace('[main_comment]', $main_comment['Text'], $content);

        $content = str_replace('[auth_block]', $auth_block, $content);
        $content = str_replace('[search]', $s->form(), $content);
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
        $content = str_replace('[not_access_manager]', "style='display:none;'", $content);

        return $content;
    }

}

?>