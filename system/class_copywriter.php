<?php

class class_index {

    public $GLOBAL;

    function content($db, $smarty) {
        //загружаем шаблон
        $content = file_get_contents(PATH . 'admin_tmp/inside_copywriter.tpl');

        $auth_block = '
		<!-- userbar -->
		<div id="userbar">
			<p id="fast_connection">
                            Быстрая связь: 
                            <img src="/images/openid/skype16x16.png" /> <a href="skype:roman.vetes?chat">Roman.vetes</a>
                            <img src="/images/openid/icq16x16.gif" /> 133-215 
                            <img src="/images/openid/phone.png" /> 8-926-417 30-30
                        </p>
			<a id="user_login" href="/copywriter.php?action=lk">' . $_SESSION['user']['login'] . '</a>
			
			<span class="sep"></span>
			
			<a id="log_off" href="/copywriter.php?action=logout"><span class="ico"></span> <span>Выход</span> </a>
			
		</div>
	';
        /* Вывод баланса и количества выволненых задач */
        $balance = $vipolneno = 0;
        $tables = array(0 => "zadaniya_new", 1 => "zadaniya");
        foreach ($tables as $table) {
            $tasks = $db->Execute("SELECT * FROM $table WHERE copywriter='" . $_SESSION['user']['id'] . "' AND vipolneno=1");
            if (!empty($tasks)) {
                $vipolneno += $tasks->NumRows();
                while ($res = $tasks->FetchRow()) {
                    $balance += ($res["nof_chars"] / 1000) * COPYWRITER_PRICE_FOR_1000_CHAR;
                }
            }
        }
        /* Минус выведенные средства*/
        $withdrawal = $db->Execute("SELECT * FROM withdrawal WHERE uid='" . $_SESSION['user']['id'] . "'");
        while ($res = $withdrawal->FetchRow()) {
            $balance -= $res["sum"];
        }
        
        $content = str_replace('[balance]', $balance, $content);
        $content = str_replace('[vipolneno]', $vipolneno, $content);
        /* ------- */

        $other_tasks_sape = $db->Execute("SELECT * FROM zadaniya_new WHERE copywriter='" . $_SESSION['user']['id'] . "' AND vipolneno!=1  AND rectificate!=1");
        $other_tasks_burse = $db->Execute("SELECT * FROM zadaniya WHERE copywriter='" . $_SESSION['user']['id'] . "' AND vipolneno!=1  AND rectificate!=1");
        $content = str_replace('[vrabote]', ($other_tasks_sape->NumRows()) + ($other_tasks_burse->NumRows()), $content);

        $content = str_replace('[auth_block]', $auth_block, $content);
        $content = str_replace('[page_title]', "Index", $content);

        $new_tick = $db->Execute("SELECT COUNT(id) as newt FROM tickets WHERE (uid='" . $_SESSION['user']["id"] . "' OR to_uid='" . $_SESSION['user']["id"] . "') AND status != 0")->FetchRow();
        $content = str_replace('[new_tick]', $new_tick['newt'], $content);

        $all_tick = $db->Execute("SELECT COUNT(id) as allt FROM tickets WHERE uid='" . $_SESSION['user']["id"] . "' OR to_uid='" . $_SESSION['user']["id"] . "'")->FetchRow();
        $content = str_replace('[all_tick]', $all_tick['allt'], $content);
        return $content;
    }

}

?>