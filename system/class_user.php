<?php

class class_user {

    public $user = array();
    public $search;

    public function __construct() {
        include PATH . 'modules/search/search_class.php';
        if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
            $this->user = $_SESSION['user'];
        }
        $this->search = new search();
    }

    function content($db) {
        //загружаем шаблон
        if (!empty($this->user)) {
            switch ($this->user["type"]) {
                case "user":
                    $content = $this->get_user_templeate($db);
                    break;
                case "moder":
                    $content = $this->get_moder_templeate($db);
                    break;
                default: $content = $this->get_user_templeate($db);
                    break;
            }
        }
        else
            $content = file_get_contents(PATH . 'admin_tmp/register.tpl');

        $sform = $this->search->form();
        $content = str_replace('[search]', $sform, $content);

        $auth_block = '
		<!-- userbar -->
		<div id="userbar">
			<p id="fast_connection">
                            Быстрая связь: 
                            <img src="/images/openid/skype16x16.png" /> <a href="skype:roman.vetes?chat">Roman.vetes</a>
                            <img src="/images/openid/icq16x16.gif" /> 133-215 
                        </p>
			<a id="user_login" href="'.($this->user["type"] == "moder" ? '/user.php' : '/user.php?action=lk').'">' . $this->user['login'] . '</a>
			
			<span class="sep"></span>
			
			<a id="log_off" href="/user.php?action=logout"><span class="ico"></span> <span>Выход</span> </a>
			
		</div>
	';
        $content = str_replace('[auth_block]', $auth_block, $content);

        $new_tick = $db->Execute("SELECT COUNT(id) as newt FROM tickets WHERE (uid='" . $this->user["id"] . "' OR to_uid='" . $this->user["id"] . "') AND status != 0")->FetchRow();
        $content = str_replace('[new_tick]', $new_tick['newt'], $content);

        $all_tick = $db->Execute("SELECT COUNT(id) as allt FROM tickets WHERE uid='" . $this->user["id"] . "' OR to_uid='" . $this->user["id"] . "'")->FetchRow();
        $content = str_replace('[all_tick]', $all_tick['allt'], $content);
        
        $new_wallet = $db->Execute("SELECT COUNT(id) as num FROM change_wallet WHERE uid='" . $this->user["id"] . "' AND status = 0")->FetchRow();
        $content = str_replace('[new_wallet]', $new_wallet['num'], $content);

        $all_wallet = $db->Execute("SELECT COUNT(id) as num FROM change_wallet WHERE uid='" . $this->user["id"] . "'")->FetchRow();
        $content = str_replace('[all_wallet]', $all_wallet['num'], $content);


        return $content;
    }
    
    private function get_user_templeate($db) {
        if($this->user['active'] == 1){
            $content = file_get_contents(PATH . 'admin_tmp/inside_user.tpl');
        } else {
            $content = file_get_contents(PATH . 'admin_tmp/inside_user.tpl');
        }
                
        //Подсчитываем баланс
        $balans = $db->Execute("SELECT SUM(price) as total FROM orders WHERE uid='" . $this->user["id"] . "' AND status=1")->FetchRow();
        if (!is_null($balans['total']) || 1) {
            if (!$balans['total'])
                $balans['total'] = 0;

            $pay_link = "/user.php?action=payments";
        }
        else {
            $balans['total'] = 0;
            $pay_link = "/first-payment/?oid=" . $this->user["id"];
        }

        //Подсчитываем количество выполненных задач
        $compl_tasks = $db->Execute("SELECT * FROM completed_tasks WHERE uid='" . $this->user["id"] . "'");
        $credit = 0;
        while ($row = $compl_tasks->FetchRow()) {
            $credit += $row['price'];
        }
        $balans['total'] -= $credit;
        $_SESSION['user_balans'] = $balans['total'];
        $this->user['user_balans'] = $balans['total'];
        if ($balans['total'] < 0)
            $balans['total'] = "<span style='color:red;'>" . $balans['total'] . "</span>";

        $content = str_replace('[balans]', $_SESSION['user_balans'], $content);
        $content = str_replace('[payment_link]', $pay_link, $content);
        
        if (@$_GET['whyedit'] == 1)
            $main_comment = $db->Execute("SELECT * FROM Message2002 WHERE Sub_Class_ID = 24")->FetchRow();
        else {
            $main_comment = $db->Execute("SELECT * FROM Message2002 WHERE Sub_Class_ID = 22")->FetchRow();
        }
        if($this->user["active"] != 0){
            $content = str_replace('[main_comment]', $main_comment['Text'], $content);
            if ($this->user['hide_notify']) {
                $content = str_replace('[display_comment]', 'style="display:none"', $content);
            } else {
                $content = str_replace('[display_comment]', 'style="display:block;"', $content);
            }
        } else {
            $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
            if(!empty($action) && mb_stristr($action, "postreg_step")) {
                $content = str_replace('[main_comment]', "Вы можете пропустить эти шаги и легко к ним вернуться, нажав на ссылку <b>Управление</b>.", $content);
                $content = str_replace('[display_comment]', (($this->user['hide_notify'] == 1) ? 'style="display:none"' : ""), $content);
            } else {
                $content = str_replace('[main_comment]', '', $content);
                $content = str_replace('[display_comment]', 'style="display:none"', $content);
            }
        }
        
        $content = str_replace('[brcr]', $this->get_brcr(), $content);
        return $content;
    }
    
    private function get_moder_templeate($db) {
        $content = file_get_contents(PATH . 'admin_tmp/inside.tpl');
        $sites_model = $db->Execute("SELECT * FROM sayty WHERE moder_id='" . $this->user["id"] . "' ORDER BY url");
        $sids = array();
        while ($site = $sites_model->FetchRow()) {
            $sids[] = $site['id'];
        }
        if (!empty($sids)) {
            $sids = "(" . implode(",", $sids) . ")";
        } else {
            $sids = "(1)";
        }

        $all_navyklad = $db->Execute("select count(*) from zadaniya where navyklad=1 AND sid IN " . $sids)->FetchRow();
        $all_dorabotka = $db->Execute("SELECT count(*) FROM zadaniya WHERE dorabotka=1 AND sid IN " . $sids)->FetchRow();
        $all_vilojeno = $db->Execute("select count(*) from zadaniya where vilojeno=1 AND sid IN " . $sids)->FetchRow();
        $all_to_remove = $db->Execute("select count(*) from zadaniya where to_remove=1 AND sid IN " . $sids)->FetchRow();
        $all_removed = $db->Execute("select count(*) from zadaniya where removed=1 AND sid IN " . $sids)->FetchRow();

        $content = str_replace('[all_navyklad]', $all_navyklad['count(*)'], $content);
        $content = str_replace('[all_dorabotka]', $all_dorabotka['count(*)'], $content);
        $content = str_replace('[all_vilojeno]', $all_vilojeno['count(*)'], $content);
        $content = str_replace('[all_to_remove]', $all_to_remove['count(*)'], $content);
        $content = str_replace('[all_removed]', $all_removed['count(*)'], $content);
        
        $sum_num_tasks = $db->Execute("SELECT count(id) as num FROM zadaniya WHERE who_posted = '" . $this->user["id"] . "' AND vipolneno = 1")->FetchRow();
        $sum_money = $db->Execute("SELECT SUM(price) as sum FROM moders_money WHERE moder_id = '" . $this->user["id"] . "'")->FetchRow();
        $withdrawal = $db->Execute("SELECT SUM(w.`sum`) as sums FROM withdrawal w WHERE w.uid='" . $this->user["id"] . "' ORDER BY w.date DESC")->FetchRow();
        $withdrawal["sums"] = (!empty($withdrawal["sums"])) ? $withdrawal["sums"] : 0;
        $content = str_replace('[balance]', $sum_money['sum'] - $withdrawal["sums"], $content);
        $content = str_replace('[num]', $sum_num_tasks['num'], $content);
        
        $content = str_replace('[display_comment]', 'style="display:none;"', $content);
        return $content;
    }

    private function get_brcr() {
        // КРОШКИ
        $brcr = array();
        $brcr[] = "<a href='/user.php'>Личный кабинет</a>";
        $ruri = $_SERVER['REQUEST_URI'];

        if (@$_GET['action'] == "birj")
            $brcr[] = "<a href='/user.php?action=birj'>Биржи</a>";

        if (@$_GET['action'] == "sayty")
            $brcr[] = "<a href='/user.php?action=sayty'>Площадки</a>";

        if (@$_GET['action'] == "all_tasks")
            $brcr[] = "<a href='/user.php?action=all_tasks'>Все задания</a>";

        if (@$_GET['action'] == "zadaniya") {
            $brcr[] = "<a href='/user.php?action=sayty'>Площадки</a>";
            if ($_GET['sid'])
                $brcr[] = "<a href='/user.php?module=user&action=zadaniya&uid=" . $_GET['uid'] . "&sid=" . $_GET['sid'] . "'>Задания</a>";
        }

        if (@$_GET['action'] == "ticket")
            $brcr[] = "<a href='/user.php?action=ticket'>Обращения</a>";

        $result = implode(" - ", $brcr);
        return $result;
    }

    public function executeRequest($method, $url, $useragent, $cookie, $query, $body) {
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        if (count($query) > 0) {
            $url = $url . '&' . http_build_query($query);
        }
        if($cookie){
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        }
        if($useragent){
            curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        }
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        $response = curl_exec($ch);
        if (!$response) {
            die(curl_error($ch));
        }
        curl_close($ch);
        return $response;
    }

}

?>