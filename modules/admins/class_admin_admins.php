<?php

class admins {

    public $_smarty = null;
    public $_postman = null;

    function content($db, $smarty = null) {
        $this->_smarty = $smarty;
        $this->_postman = new Postman($smarty, $db);

        $GLOBAL = array();
        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
        $action2 = isset($_REQUEST['action2']) ? $_REQUEST['action2'] : '';

        switch (@$action) {
            case '':
                $content = $this->allsites($db);
                break;
            case 'logout':
                $content = $this->logout();
                break;
            case 'add':
                $content = $this->add($db);
                break;
            case 'edit':
                $content = $this->edit($db);
                break;
            case 'change_status_on':
                $content = $this->change_status_on($db);
                break;
            case 'change_status_off':
                $content = $this->change_status_off($db);
                break;
            case 'del':
                $content = $this->del($db);
            case 'new_user':
                $content = $this->new_user($db);
                break;
            case 'sayty':
                switch (@$action2) {
                    case '':
                        $content = $this->sayty($db);
                        break;
                    case 'send_email':
                        $content = $this->zadaniya_to_email($db);
                        break;
                    case 'load':
                        $content = $this->sayty_load_from_ggl($db);
                        break;
                    case 'add':
                        $content = $this->sayty_add($db);
                        break;
                    case 'edit':
                        $content = $this->sayty_edit($db);
                        break;
                    case 'del':
                        $content = $this->sayty_del($db);
                        break;
                }
                break;
            case 'zadaniya':
                switch (@$action2) {
                    case '':
                        $content = $this->zadaniya($db);
                        break;
                    case 'add':
                        $content = $this->zadaniya_add($db);
                        break;
                    case 'edit':
                        $content = $this->zadaniya_edit($db);
                        break;
                    case 'del':
                        $content = $this->zadaniya_del($db);
                        break;
                    case 'sort':
                        $content = $this->sort($db);
                        break;
                    case 'csv':
                        $content = $this->zadaniya_to_csv($db);
                        break;
                    case 'etxt':
                        $content = $this->etxt($db);
                        break;
                    case 'copywriters':
                        $content = $this->task_for_copywriters($db);
                        break;
                    case 'moreworkETXT':
                        $content = $this->moreworkETXT($db);
                        break;
                    case 'dubl':
                        $content = $this->zadaniya_dubl($db);
                        break;
                }
                break;
            case 'xls':
                $content = $this->loadXLS($db);
                break;
            case 'monitoring':
                $content = $this->monitoring($db);
                break;
            case 'decode_balans':
                $content = $this->decode_balans($db);
                break;
            case 'ticket':
                switch (@$_REQUEST['action2']) {
                    case '':
                        $content = $this->tickets($db);
                        break;

                    case 'view':
                        $content = $this->ticket_view($db);
                        break;

                    case 'edit':
                        $content = $this->ticket_edit($db);
                        break;

                    case 'add':
                        $content = $this->ticket_add($db);
                        break;

                    case 'answer':
                        $content = $this->ticket_answer($db);
                        break;

                    case 'close':
                        $content = $this->ticket_close($db);
                        break;

                    case 'create_ticket':
                        $content = $this->ticket_add($db);
                        break;
                }
                break;
            case 'allsites':
                $content = $this->allsites($db);
                break;
            case 'viewusers':
                $content = $this->view($db);
                break;
            case 'checketxt':
                $content = $this->checketxt($db);
                break;
            case 'output_to_purse':
                $content = $this->output_to_purse($db);
                break;
            case 'birj':
                switch (@$_REQUEST['action2']) {
                    case '':
                        $content = $this->birj_all($db);
                        break;
                    case 'edit_comment':
                        $content = $this->editComment($db);
                        break;
                }
                break;
            case 'balance':
                $content = $this->balance($db);
                break;
            case 'statistics':
                switch (@$_REQUEST['action2']) {
                    case '':
                        $content = $this->statistics($db);
                        break;
                    case 'tasks':
                        $content = $this->statistics_tasks($db);
                        break;
                    case 'money':
                        $content = $this->statistics_money($db);
                        break;
                    case 'graphs':
                        $content = $this->statistics_graphs($db);
                        break;
                    case 'day_to_day':
                        $content = $this->statistics_day_to_day($db);
                        break;
                }
                break;
            case 'moders':
                switch (@$_REQUEST['action2']) {
                    case '':
                        $content = $this->moders($db);
                        break;
                    case 'balance':
                        $content = $this->moders_balance($db);
                        break;
                    case 'decode_balance':
                        $content = $this->moders_decode_balance($db);
                        break;
                    case 'withdrawal':
                        $content = $this->moders_withdrawal($db);
                        break;
                    case 'money':
                        switch (@$_REQUEST['action3']) {
                            case 'output':
                                $content = $this->moders_money_output($db);
                                break;
                            case 'edit':
                                $content = $this->moders_money_edit($db);
                                break;
                            case 'delete':
                                $content = $this->moders_money_delete($db);
                                break;
                        }
                        break;
                }
                break;
            case 'copywriters':
                switch (@$_REQUEST['action2']) {
                    case '':
                        $content = $this->copywriters($db);
                        break;
                    case 'banned':
                        $content = $this->copywriters_banned($db);
                        break;
                    case 'bannedoff':
                        $content = $this->copywriters_bannedoff($db);
                        break;
                    case 'blacklist':
                        $content = $this->copywriters_blacklist($db);
                        break;
                    case 'statistics':
                        $content = $this->copywriters($db);
                        break;
                    case 'balance':
                        $content = $this->copywriters_balance($db);
                        break;
                    case 'change_task_on':
                        $content = $this->change_task_on($db);
                        break;
                    case 'change_task_off':
                        $content = $this->change_task_off($db);
                        break;
                    case 'change_task_all':
                        $content = $this->change_task_all($db);
                        break;
                    case 'change_status_task':
                        $content = $this->change_status_task($db);
                        break;
                    case 'decode_balance':
                        $content = $this->copywriters_decode_balance($db);
                        break;
                    case 'withdrawal':
                        $content = $this->copywriters_withdrawal($db);
                        break;
                    case 'trust':
                        $content = $this->copywriters_trust($db);
                        break;
                    case 'money':
                        switch (@$_REQUEST['action3']) {
                            case 'output':
                                $content = $this->copywriters_money_output($db);
                                break;
                            case 'edit':
                                $content = $this->copywriters_money_edit($db);
                                break;
                            case 'delete':
                                $content = $this->copywriters_money_delete($db);
                                break;
                        }
                        break;
                }
                break;
            case 'chat':
                switch (@$_REQUEST['action2']) {
                    case "send_message_copywriter":
                        $content = $this->send_message_copywriter($db);
                        break;
                }
                break;
            case 'articles':
                switch (@$_REQUEST['action2']) {
                    case '':
                        $content = $this->articles($db);
                        break;
                    case 'edit':
                        $content = $this->article_edit($db);
                        break;
                    case 'etxt':
                        $content = $this->articles_to_etxt($db);
                        break;
                    case 'send_message':
                        $content = $this->send_message($db);
                        break;
                    case 'order_recomplite':
                        $content = $this->order_recomplite($db);
                        break;
                    case 'statistics':
                        $content = $this->articles_statistics($db);
                        break;
                }
                break;
            case 'tasks':
                $content = $this->tasks($db);
                break;
            case 'users_non_active':
                $content = $this->users_non_active($db);
                break;
            case 'users_non_active_in_excel':
                $content = $this->users_non_active_in_excel($db);
                break;
            case 'send_mail_users_non_active':
                $content = $this->send_mail_users_non_active($db);
                break;
            case 'send_mail_users_bl_etxt':
                $content = $this->send_mail_users_bl_etxt($db);
                break;
            case 'verify_compliance_deadlines_order':
                $content = $this->verify_compliance_deadlines_order($db);
                break;
        }


        $GLOBAL['content'] = $content;
        return $GLOBAL;
    }

    function logout() {
        unset($_SESSION['admin']);
        $cur_exp = time() - 3600;
        setcookie("iforget_admin", "0", $cur_exp);
        header('location:/admin.php');
        exit;
    }

    function monitoring($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/view.tpl');


        $admins = '';
        $query = $db->Execute('select * from admins order by login asc');
        $n = 0;

        if (isset($_POST['sitetypes']))
            $aaa = $_POST['sitetypes'];
        else
            $aaa = "active";
        while ($res = $query->FetchRow()) {

            if (($aaa == "all")) {
                $admins .= file_get_contents(PATH . 'modules/admins/tmp/admin/one.tpl');
                $admins = str_replace('[login]', $res['login'], $admins);
                $admins = str_replace('[type]', $res['type'], $admins);
                $admins = str_replace('[id]', $res['id'], $admins);
                $uid = $res['id'];
                $z1 = $db->Execute("select count(*) from zadaniya where vrabote=1 and (vipolneno=0 or vipolneno IS NULL) and (dorabotka=0 or dorabotka IS NULL) and (navyklad=0 or navyklad IS NULL) and (vilojeno=0 or vilojeno IS NULL) and uid=$uid");
                $z1 = $z1->FetchRow();
                $z1 = $z1['count(*)'];
                $admins = str_replace('[z1]', $z1, $admins);
                $z2 = $db->Execute("select count(*) from zadaniya where dorabotka=1 and uid=$uid");
                $z2 = $z2->FetchRow();
                $z2 = $z2['count(*)'];
                $admins = str_replace('[z2]', $z2, $admins);
                $z3 = $db->Execute("select count(*) from zadaniya where vipolneno=1 and uid=$uid");
                $z3 = $z3->FetchRow();
                $z3 = $z3['count(*)'];
                $admins = str_replace('[z3]', $z3, $admins);
                $total_price = 0;
                $all_tasks = $db->Execute("SELECT * FROM zadaniya WHERE uid=$uid AND vipolneno=1");
                while ($row = $all_tasks->FetchRow()) {
                    if ($row['price'] > 0) {
                        $total_price += $row['price'];
                    } else {
                        $cur_sid = $row['sid'];
                        $site_price = $db->Execute("SELECT price FROM sayty WHERE id=$cur_sid");
                        $site_price = $site_price->FetchRow();
                        $total_price += $site_price['price'];
                    }
                }
                $admins = str_replace('[total_price]', $total_price, $admins);

                if ($res['type'] != "admin") {
                    if ($res['active'] == 1) {
                        $ssi = "<input type=checkbox checked name=u$uid class='chbox-user' value=$uid>";
                        $ssis = "style='background:white'";
                    } else {
                        $ssi = "<input type=checkbox name=u$uid class='chbox-user' value=$uid>";
                        $ssis = "style='background:yellow'";
                    }
                } else {
                    if ($res['active'] == 1) {
                        $ssi = "<input type=checkbox checked name=u$uid class='chbox-user' value=$uid disabled>";
                        $ssis = "style='background:white'";
                    } else {
                        $ssi = "<input type=checkbox name=u$uid class='chbox-user' value=$uid checked  disabled>";
                        $ssis = "style='background:yellow'";
                    }
                }

                $admins = str_replace('[aktstyle]', $ssis, $admins);
                $admins = str_replace('[aktivn]', $ssi, $admins);
            }
        }
        return $content;
    }

    function sort($db) {
        $sort = $_SESSION['sort'];
        if (!$sort or $sort == 'desc')
            $sort = 'asc';
        else
            $sort = 'desc';
        $_SESSION['sort'] = $sort;
        header('location:' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    function sayty($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/sayty_view.tpl');
        $uid = (int) $_REQUEST['uid'];
        $user = $db->Execute("SELECT * FROM admins WHERE id=$uid")->FetchRow();
        $content = str_replace('[login]', $user['login'], $content);
        $sayty = '';
        $sites = $db->Execute("SELECT * FROM sayty WHERE uid=$uid ORDER BY id ASC")->GetAll();
        $neobrabot = $dorabotka = $vilojeno = $vrabote = $navyklad = $neo = array();
        $zadaniya = $db->Execute("SELECT * FROM zadaniya WHERE vipolneno!=1 AND uid=$uid");
        while ($task = $zadaniya->FetchRow()) {
            if (!isset($navyklad[$task["sid"]])) {
                $navyklad[$task["sid"]] = 0;
            }
            if (!isset($vrabote[$task["sid"]])) {
                $vrabote[$task["sid"]] = 0;
            }
            if (!isset($vilojeno[$task["sid"]])) {
                $vilojeno[$task["sid"]] = 0;
            }
            if (!isset($dorabotka[$task["sid"]])) {
                $dorabotka[$task["sid"]] = 0;
            }
            if (!isset($neobrabot[$task["sid"]])) {
                $neobrabot[$task["sid"]] = 0;
            }


            if ($task["navyklad"] == 1) {
                $navyklad[$task["sid"]] += 1;
            }
            if ($task["vrabote"] == 1 && empty($task["dorabotka"]) && empty($task["vipolneno"]) && empty($task["navyklad"]) && empty($task["vilojeno"])) {
                $vrabote[$task["sid"]] += 1;
            }
            if ($task["vilojeno"] == 1) {
                $vilojeno[$task["sid"]] += 1;
            }
            if ($task["dorabotka"] == 1) {
                $dorabotka[$task["sid"]] += 1;
            }
            if (empty($task["vrabote"]) && empty($task["dorabotka"]) && empty($task["vipolneno"]) && empty($task["navyklad"]) && empty($task["vilojeno"])) {
                $neobrabot[$task["sid"]] += 1;
            }
        }
        foreach ($sites as $site) {
            $sayty .= file_get_contents(PATH . 'modules/admins/tmp/admin/sayty_one.tpl');
            $sayty = str_replace('[url]', $site['url'], $sayty);
            $sayty = str_replace('[id]', $site['id'], $sayty);
            $sayty = str_replace('[z1]', isset($vrabote[$site['id']]) ? $vrabote[$site['id']] : 0, $sayty);
            $sayty = str_replace('[z2]', isset($dorabotka[$site['id']]) ? $dorabotka[$site['id']] : 0, $sayty);
            $sayty = str_replace('[z4]', isset($neobrabot[$site['id']]) ? $neobrabot[$site['id']] : 0, $sayty);
            $sayty = str_replace('[z7]', isset($navyklad[$site['id']]) ? $navyklad[$site['id']] : 0, $sayty);
            $sayty = str_replace('[z5]', isset($vilojeno[$site['id']]) ? $vilojeno[$site['id']] : 0, $sayty);
        }

        if ($sayty) {
            $sayty = str_replace('[sayty]', $sayty, file_get_contents(PATH . 'modules/admins/tmp/admin/sayty_top.tpl'));
        } else {
            $sayty = file_get_contents(PATH . 'modules/admins/tmp/admin/no.tpl');
        }
        $content = str_replace('[sayty]', $sayty, $content);

        if (!@$_SESSION["admin"]["id"]) {
            $content = str_replace('[rights]', "style='display:none'", $content);
        } else {
            $content = str_replace('[rights]', "", $content);
        }
        $content = str_replace('[uid]', $uid, $content);
        return $content;
    }

    function etxt($db) {
        $cena = $colvos = 0;
        $site_subject = "";
        $uid = (int) $_REQUEST['uid'];
        $sid = (int) $_GET['sid'];
        $pass = ETXT_PASS;
        $db_res = $db->Execute("SELECT * FROM `zadaniya` WHERE sid='" . $sid . "' AND uid='" . $uid . "' AND tema!=''  AND etxt=0 AND copywriter=0 AND for_copywriter=0 AND lay_out=0");
        $sinfo = $db->Execute("SELECT * FROM `sayty` WHERE id='" . $sid . "'")->FetchRow();
        $user = $db->Execute("SELECT * FROM `admins` WHERE id='" . $uid . "'")->FetchRow();
        if (!empty($sinfo)) {
            $cena = $sinfo['cena'];
            $colvos = $sinfo['colvos'];
            $site_subject = $sinfo['site_subject'];
        }
        if ($colvos == 0 OR $cena == 0) {
            echo "<script>
                    alert(\"Не указано кол-во симовлов или цена\");
                    var delay = 500;
                    setTimeout(\"document.location.href='?module=admins&action=zadaniya&uid=" . $uid . "&sid=" . $sid . "'\", delay);
                  </script>";
            exit();
        }
        while ($db_res2 = $db_res->FetchRow()) {
            //Смотрим на баланс и проверяем, может ли создать пользователь заявку (хватит ли денег)
            if (($uid != 20) && ($uid != 55)) {
                $cur_balans = $this->getUserBalans($uid, $db, 1);
                if ($db_res2['lay_out'] == 1) {
                    $cur_balans -= 15;
                } else if ($db_res2['sistema'] == "http://miralinks.ru/" || $db_res2['sistema'] == "https://gogetlinks.net/" || $db_res2['sistema'] == "http://pr.sape.ru/" || $db_res2['sistema'] == "http://getgoodlinks.ru/" || $db_res2['sistema'] == "http://rotapost.ru/") {
                    $colvos = 2000;
                    switch ($sinfo['cena']) {
                        case 20:$cur_balans -= 60;
                            break;
                        case 30:$cur_balans -= 76;
                            break;
                        case 45:$cur_balans -= 111;
                            break;
                        default:$cur_balans -= 60;
                            break;
                    }
                } else {
                    $cur_balans -= $sinfo['price'];
                }
                if ($user["new_user"] == 1 && $db_res2['lay_out'] != 1) {
                    //увеличение цен для новых пользователей на 30%
                    $cur_balans -= 17;
                }
                if ($cur_balans < 0) {
                    break;
                }
            }
            if ($db_res2['sistema'] == "http://miralinks.ru/" || $db_res2['sistema'] == "https://gogetlinks.net/" || $db_res2['sistema'] == "http://pr.sape.ru/" || $db_res2['sistema'] == "http://getgoodlinks.ru/" || $db_res2['sistema'] == "http://rotapost.ru/") {
                $colvos = 2000;
            }

            $tema = $db_res2['tema'];
            $description = '1)Cтатья [colvos] символов без пробелов, в тексте должн[mn] быть фраз[mn] "[ankor]",[ankor2][ankor3][ankor4][ankor5] заключенная в {} 
                            2)Фраза должна быть употреблена в точности как написана, разрывать другими словами ее нельзя, склонять так же нельзя. Если указано несколько фраз через запятую, то нужно их равномерно распределить по тексту  
                            3)Текст без воды, строго по теме, без негатива (см. прикрепленный файл "Текст заказа") 
                            4)Фразу употребить ТОЛЬКО ОДИН раз, в остальном - заменять синонимами 
                            5)Высылать готовый заказ просто текстом, в формате word не принимаем
                            6)Вручную проверить уникальность текста по Адвего Плагиатус (выше 95%), в Комментариях к заказу проставить % уникальности по данной программе. Без этого пункта автоматически задание отправляется на доработку.
                            7)После того как заказ будет принят и оплачен все авторские права принадлежат аккаунту ifoget.ru (то есть статьи могут быть опубликованы на сайтах под различным именем, на выбор владельца текста).
                            8)Тексты писать только на русском языке.';
            $tittle = $db_res2['ankor'];
            $description = str_replace('[ankor]', $tittle, $description);
            if (!empty($db_res2['ankor2']))
                $description = str_replace('[ankor2]', ' "' . $db_res2['ankor2'] . '",', $description);
            else
                $description = str_replace('[ankor2]', "", $description);
            if (!empty($db_res2['ankor3']))
                $description = str_replace('[ankor3]', ' "' . $db_res2['ankor3'] . '",', $description);
            else
                $description = str_replace('[ankor3]', "", $description);
            if (!empty($db_res2['ankor4']))
                $description = str_replace('[ankor4]', ' "' . $db_res2['ankor4'] . '",', $description);
            else
                $description = str_replace('[ankor4]', "", $description);
            if (!empty($db_res2['ankor5']))
                $description = str_replace('[ankor5]', ' "' . $db_res2['ankor5'] . '",', $description);
            else
                $description = str_replace('[ankor5]', "", $description);

            if (!empty($db_res2['ankor3']) || !empty($db_res2['ankor2']) || !empty($db_res2['ankor4']) || !empty($db_res2['ankor5'])) {
                $description = str_replace('[mn]', "ы", $description);
            } else
                $description = str_replace('[mn]', "а", $description);


            $description = str_replace('[colvos]', $colvos, $description);
            $time = time() + 86400;
            $date = date("d.m.y", $time);
            $id = $db_res2['id'];

            $howto = '
		Здесь представлены основные требования к работе авторов. 

		Общие требования к тексту

		Соответствие тематике (НЕ фразы, а именно тематике), если прямо не указано, что можно писать по теме фразы 
		Максимальное совмещение тематики и темы фразы (например, фраза «украшения» прекрасно впишется в женскую тематику и необходимо писать в этом случае ОБЯЗАТЕЛЬНО о женских украшениях, а не о народном противостоянии феминисток в Палестине).
		Текст должен быть уникальным (минимальная уникальность 95%). 
		Текст должен иметь смысловую нагрузку. То есть несущим какую-то полезную информацию для читателей законченный рассказ, с четким и понятным изложением какого-то факта или события, совет, инструкция или рекомендация.
		Информация ОБЯЗАТЕЛЬНО должна быть правдивой.
		Предложения должны быть связаны между собой по смыслу. 
		Фраза должна входить в текст естественно (по смыслу, числу и падежу),или её нужно употребить, не изменяя ничего.Это прописано рядом с фразой, пример: в тексте должна быть фраза "www.altaystroy.ru (склонять анкор нельзя )"
		В тексте не должно быть грамматических и пунктуационных ошибок.
		Нельзя писать от первого лица и от лица компании (мы изготовим для вас, в нашей компании). Все текста пишутся только от третьего лица.


		Оформление

		Текст необходимо делить на абзацы и выделять подзаголовки.
		Нельзя употреблять в тексте смайлы и множество восклицательных и вопросительных знаков.


		О тематике

		Нужно избегать любых негативных тем - Придаем тексту нейтральную или положительную окраску.
 

		О вписывании фразы в тест

		НЕЛЬЗЯ менять заглавные буквы на строчные и наоборот, нельзя его склонять и изменять. Лучше не переписывать фразу, а скопировать и вставить в текст.
		Фраза должна входить в текст естественно. НЕЛЬЗЯ писать: Ухаживать за лицом [матрасы] нужно ежедневно. Мы не примем такую работу. 
		Если в задании фраза, например, "свадебные платья", а вам нужно написать что-то на тему "Советы хозяйке" НЕЛЬЗЯ писать о туалетных ершиках или борьбе с тараканами и всовывать кое-как фразу. 
		НЕЛЬЗЯ придавать фразе негативную окраску. Пишем о нем либо нейтрально, либо ненавязчиво рекомендуем читателю товар, услугу и т.д.
		Фраза должна иметь тематическое окружение (если фраза "диваны", необходимо употребить рядом слова, например, мебель, интерьер спальня). 


		О несоответствии темы и фразы

		Если тематику невозможно аккуратно совместить с темой фразы и логически она никак в нее не вписывается, следует писать текст по тематике. А в конце текста написать 2-3 полноценных предложения о фразе и вставив фразу. 
		В таких случаях пишем текст, а в конце дописываем 2-3 полноценных предложения, не относящихся к тексту, именно по теме ключа. 
		Фраза должна иметь тематическое окружение. После фразы нужно дописать еще несколько слов или предложение.
		НЕЛЬЗЯ фразой заканчивать текст.


		Текст на широкую тематику

		Даже если вы «в теме» не стоит выдумывать что-то самостоятельно. Основывайтесь на реальных фактах.
		Не используйте избитые фразы и выражения - текст короткий и уникальность сразу упадет. 
		Смотрите информацию о фразе, особенно если это слово вам незнакомо. Если фраза, например, монурал (название лекарства) НЕЛЬЗЯ писать, что это модная прическа или деталь трактора.
	';

            $category_id = 1828;
            switch ($site_subject) {
                case NULL: $category_id = 1828;
                    break;
                case "": $category_id = 1828;
                    break;
                case "Авто и Мото": $category_id = 368;
                    break;
                case "Бизнес/Финансы/Реклама": $category_id = 371;
                    break;
                case "Бытовая техника": $category_id = 1714;
                    break;
                case "Дети": $category_id = 1791;
                    break;
                case "Дом и быт": $category_id = 1790;
                    break;
                case "Другое": $category_id = 1828;
                    break;
                case "Животные и растительный мир": $category_id = 1673;
                    break;
                case "Закон и право": $category_id = 390;
                    break;
                case "Здоровье/Медицина": $category_id = 294;
                    break;
                case "Компьютерная и цифровая техника": $category_id = 1828;
                    break;
                case "Красота/Косметика/Парфюмерия": $category_id = 1880;
                    break;
                case "Кулинария и продукты питания": $category_id = 254;
                    break;
                case "Культура и искусство": $category_id = 1647;
                    break;
                case "Мебель и интерьер": $category_id = 1792;
                    break;
                case "Мода и стиль": $category_id = 115;
                    break;
                case "Недвижимость": $category_id = 1839;
                    break;
                case "Непознанное": $category_id = 513;
                    break;
                case "Новости": $category_id = 1828;
                    break;
                case "Образование и наука": $category_id = 1786;
                    break;
                case "Отдых и туризм": $category_id = 1773;
                    break;
                case "Под анкор заявки": $category_id = 1828;
                    break;
                case "Производство и промышленность": $category_id = 1893;
                    break;
                case "Психология": $category_id = 1832;
                    break;
                case "Развлечения/Игры/Юмор/Знакомства": $category_id = 1821;
                    break;
                case "Связь и коммуникации": $category_id = 1828;
                    break;
                case "Семья и отношения": $category_id = 1793;
                    break;
                case "Спорт": $category_id = 345;
                    break;
                case "Строительство и ремонт": $category_id = 1838;
                    break;
                case "Товары и Услуги": $category_id = 1794;
                    break;

                default : $category_id = 1828;
                    break;
            }

            $pass = ETXT_PASS;
            $query_sign = "method=tasks.saveTasktoken=29aa0eec2c77dd6d06e23b3faaef9eed";
            $sign = md5($query_sign . md5($pass . 'api-pass'));

            $params = array('auto_work' => 1,
                'id_category' => $category_id,
                'deadline' => $date,
                'description' => $description,
                'multitask' => 0,
                'id_type' => 1,
                'only_stars' => 0,
                'price' => $cena,
                'price_type' => 1,
                'public' => 1,
                'target_task' => 1,
                'text' => $howto,
                'timeline' => '17:00',
                'title' => $tema,
                'whitespaces' => 0,
                'auto_level' => 1,
                'auto_rate' => 100,
                'size' => $colvos,
                'uniq' => 95);

            $query_p = $params;

            $url_etxt = "https://www.etxt.ru/api/json/?method=tasks.saveTask&token=29aa0eec2c77dd6d06e23b3faaef9eed&sign=" . $sign;

            if ($curl = curl_init()) {
                curl_setopt($curl, CURLOPT_URL, $url_etxt);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $query_p);
                $new_tid = curl_exec($curl);
                curl_close($curl);
            }
            $new_tid = json_decode($new_tid);
            unset($description);

            $new_tid = (array) $new_tid;
            $id_task = $new_tid['id_task'];
            if (isset($id_task)) {
                $db->Execute("UPDATE zadaniya SET task_id = '" . $id_task . "', etxt = 1, vrabote = 1 WHERE id = '" . $id . "'");

                $price = 0;
                if ($db_res2['lay_out'] == 1) {
                    $price = 15;
                } else if ($db_res2['sistema'] == "http://miralinks.ru/" || $db_res2['sistema'] == "https://gogetlinks.net/" || $db_res2['sistema'] == "http://pr.sape.ru/" || $db_res2['sistema'] == "http://getgoodlinks.ru/" || $db_res2['sistema'] == "http://rotapost.ru/") {
                    switch ($sinfo['cena']) {
                        case 20:$price = 60;
                            break;
                        case 30:$price = 76;
                            break;
                        case 45:$price = 111;
                            break;
                        default:$price = 60;
                            break;
                    }
                } else {
                    $price = $sinfo['price'];
                }
                if ($user["new_user"] == 1 && $db_res2['lay_out'] != 1) {
                    //увеличение цен для новых пользователей на 30%
                    $price = (int) $price + 17;
                }

                $compl = $db->Execute("SELECT * FROM completed_tasks WHERE uid = $uid AND zid=" . $db_res2['id'])->FetchRow();
                if (empty($compl)) {
                    $db->Execute("INSERT INTO completed_tasks (uid, zid, date, price, status) VALUES ('$uid', '" . $db_res2['id'] . "', '" . date("Y-m-d H:i:s") . "', '$price',1)");
                } else {
                    $db->Execute("UPDATE completed_tasks SET price = '" . $price . "' WHERE id = '" . $compl["id"] . "'");
                }

                echo "<script>
                        alert(\"Заявка успешно добавлена. Номер заявки " . $id_task . "\");
                        var delay = 500;
                        setTimeout(\"document.location.href='?module=admins&action=zadaniya&uid=" . $uid . "&sid=" . $sid . "'\", delay);
                      </script>";
            }
        }
    }

    function task_for_copywriters($db) {
        $uid = (int) $_REQUEST['uid'];
        $sid = (int) $_REQUEST['sid'];

        $tasks = $db->Execute("SELECT id FROM `zadaniya` WHERE sid='" . $sid . "' AND uid='" . $uid . "' AND tema!='' AND etxt=0 AND copywriter=0 AND for_copywriter=0 AND lay_out=0");
        while ($task = $tasks->FetchRow()) {
            $db->Execute("UPDATE zadaniya SET for_copywriter = 1 WHERE id = " . $task["id"]);
        }
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }

    function zadaniya($db) {
        $starttime = time();
        $profil = "";
        $profil .= time() . "\r\n";
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/zadaniya_view.tpl');

        $uid = (int) $_REQUEST['uid'];
        $user = $db->Execute("select * from admins where id=$uid")->FetchRow(); // Вытаскиваем юзера
        $content = str_replace('[login]', $user['login'], $content);

        $sid = (int) $_GET['sid'];
        $sayty = $db->Execute("select * from sayty where id=$sid")->FetchRow(); // Вытаскиваем сайт
        $content = str_replace('[url]', $sayty['url'], $content);

        $limit = 25;
        $offset = 1;
        if (isset($_GET['offset']) && !empty($_GET['offset'])) {
            $offset = (int) $_GET['offset'];
        }
        $zadaniya = '';
        $array_zadanya = array();
        $array_copy = array();
        if (@$_SESSION['sort'] == 'asc' or ! @$_SESSION['sort']) {
            $symb = '↓';
            $sort = 'asc';
        } else {
            $symb = '↑';
            $sort = 'desc';
        }

        $from = $to = null;
        if (isset($_GET["status_z"])) {
            switch ($_GET["status_z"]) {
                case "vipolneno":
                    $condition = " AND vipolneno = 1";
                    break;
                case "dorabotka":
                    $condition = " AND (dorabotka = 1 OR rework = 1)";
                    break;
                case "navyklad":
                    $condition = " AND navyklad = 1";
                    break;
                case "vilojeno":
                    $condition = " AND vilojeno = 1";
                    break;
                case "vrabote":
                    $condition = " AND vrabote = 1";
                    break;
                case "new":
                    $condition = " AND rectificate='0' AND (vrabote='0' OR vrabote IS NULL) AND vipolneno='0' AND dorabotka='0' AND navyklad='0' AND vilojeno='0' AND rework='0'";
                    break;
                default: $condition = "";
                    break;
            }
        } else {
            $condition = "";
        }


        if (@$_POST['date-from'] || @$_POST['date-to'] || @$_GET['date-from'] || @$_GET['date-to']) {
            if (@$_POST['date-from'])
                $from = strtotime($db->escape($_POST['date-from']));
            else if (@$_GET['date-from'])
                $from = $_GET['date-from'];
            if (@$_POST['date-to'])
                $to = strtotime($db->escape($_POST['date-to']));
            else if (@$_GET['date-to'])
                $to = $_GET['date-to'];

            if ($from && $to) {
                $query = $db->Execute("select * from zadaniya where sid=$sid AND date BETWEEN '" . $from . "' AND '" . $to . "' $condition order by date DESC, id $sort LIMIT " . ($offset - 1) * $limit . "," . $limit);
                $all = $db->Execute("select * from zadaniya where sid=$sid AND date BETWEEN '" . $from . "' AND '" . $to . "' $condition order by date DESC, id $sort");
            } else {
                if ($from) {
                    $query = $db->Execute("select * from zadaniya where sid=$sid AND date >= '" . $from . "' $condition order by date DESC, id $sort LIMIT " . ($offset - 1) * $limit . "," . $limit);
                    $all = $db->Execute("select * from zadaniya where sid=$sid AND date >= '" . $from . "' $condition order by date DESC, id $sort");
                } else {
                    $query = $db->Execute("select * from zadaniya where sid=$sid AND date <= '" . $to . "' $condition order by date DESC, id $sort LIMIT " . ($offset - 1) * $limit . "," . $limit);
                    $all = $db->Execute("select * from zadaniya where sid=$sid AND date <= '" . $to . "' $condition order by date DESC, id $sort");
                }
            }
        } else {
            if (!isset($_GET['showall'])) {
                $q = "SELECT * FROM zadaniya WHERE sid=$sid AND date >= '" . (strtotime(date("Y-m-d")) - 86400 * 30) . "' $condition ORDER BY date DESC, id $sort LIMIT " . ($offset - 1) * $limit . "," . $limit;
                $all = "SELECT * FROM zadaniya WHERE sid=$sid AND date >= '" . (strtotime(date("Y-m-d")) - 86400 * 30) . "' $condition ORDER BY date DESC, id $sort";
            } else {
                $q = "SELECT * FROM zadaniya WHERE sid=$sid $condition ORDER BY date DESC, id $sort LIMIT " . ($offset - 1) * $limit . "," . $limit;
                $all = "SELECT * FROM zadaniya WHERE sid=$sid $condition ORDER BY date DESC, id $sort";
            }
            $query = $db->Execute($q);
            $all = $db->Execute($all);
        }
        $profil .= microtime() . "  - GET DATA" . "\r\n";
        $pegination = '<div style="float:right">';
        if ($offset == 1) {
            $pegination .= '<div style="float:left">Пред.</div>';
        } else {
            $pegination .= "<div style='float:left'><a href='?module=admins&action=zadaniya&uid=$uid&sid=$sid&offset=" . ($offset - 1) . ((!@$_GET['showall']) ? '' : '&showall=1') . ((!$from) ? '' : '&date-from=' . $from) . ((!$to) ? '' : '&date-to=' . $to) . "'>Пред.</a></div>";
        }
        $pegination .= '<div style="float:left">&nbsp [ стр.&nbsp</div>';
        $pegination .= '<div class="select" style="width:50px;float:left;margin-top:-7px"><select name="pagerMenu" onchange="location=\'?module=admins&action=zadaniya&uid=' . $uid . '&sid=' . $sid . '' . ((!@$_GET['showall']) ? '' : '&showall=1') . ((!$from) ? '' : '&date-from=' . $from) . ((!$to) ? '' : '&date-to=' . $to) . '&offset=\'+this.options[this.selectedIndex].value+\'\'" ;="">';

        $all_zadanya = $all->NumRows();
        $count_pegination = ceil($all_zadanya / $limit);
        for ($i = 0; $i < $count_pegination; $i++) {
            if ($i + 1 == $offset) {
                $pegination .= '<option value="' . ($i + 1) . '" selected="selected">' . ($i + 1) . '</option>';
            } else {
                $pegination .= '<option value="' . ($i + 1) . '">' . ($i + 1) . '</option>';
            }
        }
        $pegination .= '</select></div>';
        $pegination .= '&nbsp из ' . $count_pegination . ' ] &nbsp';
        if ($query->NumRows() < $limit) {
            $pegination .= "След.";
        } else {
            $pegination .= "<a href='?module=admins&action=zadaniya&uid=$uid&sid=$sid&offset=" . ($offset + 1) . ((!@$_GET['showall']) ? '' : '&showall=1') . ((!$from) ? '' : '&date-from=' . $from) . ((!$to) ? '' : '&date-to=' . $to) . "'>След.</a>";
        }
        $pegination .= '</div>';
        $profil .= microtime() . "  - AFTER PEGINATION" . "\r\n";
        while ($res = $query->FetchRow()) {
            $startTimeStamp = ($res['date']);
            $endTimeStamp = strtotime(date("Y-m-d"));
            $timeDiff = abs($endTimeStamp - $startTimeStamp);
            $numberDays = intval($timeDiff / 86400);  // 86400 seconds in one day

            if (($numberDays >= 30) && !$_GET['showall']) {
                //continue;
            }
            $worker = "";

            $zadaniya = file_get_contents(PATH . 'modules/admins/tmp/admin/zadaniya_one.tpl');
            if (empty($res["task_id"]) && (!empty($res["copywriter"]) && $res["copywriter"] != 0)) {
                $copywriter = $db->Execute("SELECT login FROM admins WHERE id = " . $res["copywriter"])->FetchRow();
                $worker = isset($copywriter["login"]) ? $copywriter["login"] : "Не известный Копирайтер!";
            } elseif (!empty($res["task_id"])) {
                $worker = $res["task_id"];
            } elseif ((empty($res["task_id"]) && empty($res["copywriter"])) && $res["for_copywriter"] == 1) {
                $worker = "Отдано копирайтерам";
            }

            $url = (mb_substr(mb_substr($res['url'], mb_strpos($res['url'], "http")), 0, 30));
            $zadaniya = str_replace('[url]', $url, $zadaniya);
            $zadaniya = str_replace('[id]', $res['id'], $zadaniya);
            $zadaniya = str_replace('[etxt_id]', $worker, $zadaniya);
            $zadaniya = str_replace('[date]', date('d.m.Y', $res['date']), $zadaniya);
            $zadaniya = str_replace('[tema]', mb_substr($res['tema'], 0, 50), $zadaniya);

            $new_s = "";
            if ($res['dorabotka']) {
                $new_s = "in-work";
                $bg = '#f6b300';
            } else if ($res['vipolneno']) {
                $new_s = "done";
                $bg = '#83e24a';
            } else if ($res['vrabote']) {
                $new_s = "working";
                $bg = '#00baff';
            } else if ($res['navyklad']) {
                $new_s = "ready";
                $bg = '#ffde96';
            } else if ($res['vilojeno']) {
                $new_s = "vilojeno";
                $bg = '#b385bf';
            } else {
                $bg = '';
            }
            $zadaniya = str_replace('[status]', $new_s, $zadaniya);
            $zadaniya = str_replace('[bg]', 'style="background:' . $bg . '"', $zadaniya);

            if ($res['text']) {
                $etxt_status = "текст";
            } else {
                $etxt_status = "";
            }
            $zadaniya = str_replace('[etxt_status]', $etxt_status, $zadaniya);
            if (empty($res['copy'])) {
                $array_zadanya[$res['id']] = $zadaniya;
            } else {

                if (!empty($array_copy[$res['copy']]))
                    $array_copy[$res['copy']] .= $zadaniya;
                else
                    $array_copy[$res['copy']] = $zadaniya;
            }
        }

        if (!empty($array_copy)) {
            foreach ($array_copy as $id_original => $value) {
                if (isset($array_zadanya[$id_original]) && !empty($array_zadanya[$id_original]))
                    $array_zadanya[$id_original] .= $value;
                else
                    $array_zadanya[$id_original] = $value;
            }
        }
        $zadaniya = implode("", $array_zadanya);

        if ($zadaniya)
            $zadaniya = str_replace('[zadaniya]', $zadaniya, file_get_contents(PATH . 'modules/admins/tmp/admin/zadaniya_top.tpl'));
        else {
            $zadaniya = file_get_contents(PATH . 'modules/admins/tmp/admin/no.tpl');
            $pegination = "";
        }

        $profil .= microtime() . "  - END FUNCTION" . "\r\n";

        $content = str_replace('[zadaniya]', $zadaniya, $content);
        $content = str_replace('[uid]', $uid, $content);
        $content = str_replace('[pegination]', $pegination, $content);
        $content = str_replace('[sid]', $sid, $content);
        $content = str_replace('[symb]', $symb, $content);
        $content = str_replace('[dfrom]', (!empty($from)) ? date("m/d/Y", $from) : "", $content);
        $content = str_replace('[dto]', (!empty($to)) ? date("m/d/Y", $to) : "", $content);
        $content = str_replace('[cur_url]', "?module=admins&action=zadaniya&uid=$uid&sid=$sid&offset=" . $offset . ((!isset($_GET['showall'])) ? '' : '&showall=1') . ((!$from) ? '' : '&date-from=' . $from) . ((!$to) ? '' : '&date-to=' . $to) . "", $content);
        $endtime = time() - $starttime;
        if ($endtime > 3) {
            $profil .= "ALL TIME - " . $endtime;
            $file = 'temp_file/view_tasks/' . $endtime . '-(' . time() . ').txt';
            file_put_contents($file, $profil);
        }
        return $content;
    }

    function zadaniya_to_csv($db) {
        $uid = (int) $_REQUEST['uid'];
        $query = $db->Execute("select * from admins where id=$uid");
        $res = $query->FetchRow();

        $sid = (int) $_GET['sid'];
        $query = $db->Execute("select * from sayty where id=$sid");
        $res = $query->FetchRow();

        $zadaniya = '';

        $query = $db->Execute("select * from zadaniya where sid=$sid order by date");

        header('Content-Type: text/html; charset=windows-1251');
        header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', FALSE);
        header('Pragma: no-cache');
        header('Content-transfer-encoding: binary');
        header('Content-Disposition: attachment; filename=list.xls');
        header('Content-Type: application/x-unknown');

        echo '
		<table border="1">
		<tr>
			<td>' . @htmlentities("Sistema", ENT_QUOTES, "utf8") . '</td>
			<td>' . @htmlentities("Ankor", ENT_QUOTES, "utf8") . '</td>
			<td>' . @htmlentities("URL", ENT_QUOTES, "utf8") . '</td>
			<td>' . @htmlentities("Keywords", ENT_QUOTES, "utf8") . '</td>
			<td>' . @htmlentities("Tema", ENT_QUOTES, "utf8") . '</td>
			<td>' . @htmlentities("URL statji", ENT_QUOTES, "utf8") . '</td>
			<td>' . @htmlentities("Date", ENT_QUOTES, "utf8") . '</td>
			<td>' . @htmlentities("Text", ENT_QUOTES, "utf8") . '</td>
			<td>' . @htmlentities("Dorabotka", ENT_QUOTES, "utf8") . '</td>
			<td>' . @htmlentities("Vipolneno", ENT_QUOTES, "utf8") . '</td>
		</tr>';

        $n = 0;
        while ($res = $query->FetchRow()) {
            $system = $res['sid'];
            $system = $db->Execute("select * from sayty where id=$system");
            $system = $system->FetchRow();

            echo '
			<table border="1">
			<tr>
				<td>' . @htmlentities(iconv("utf-8", "windows-1251", $res['sistema']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . @htmlentities(iconv("utf-8", "windows-1251", $res['ankor']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . @htmlentities(iconv("utf-8", "windows-1251", $res['url']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . @htmlentities(iconv("utf-8", "windows-1251", $res['keywords']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . @htmlentities(iconv("utf-8", "windows-1251", $res['tema']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . @htmlentities(iconv("utf-8", "windows-1251", $res['url_statyi']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . @htmlentities(iconv("utf-8", "windows-1251", date("d-m-Y", $res['date'])), ENT_QUOTES, "cp1251") . '</td>
				<td>' . @htmlentities(iconv("utf-8", "windows-1251", $res['text']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . @htmlentities(iconv("utf-8", "windows-1251", ($res['dorabotka'] ? "ДА" : "НЕТ")), ENT_QUOTES, "cp1251") . '</td>
				<td>' . @htmlentities(iconv("utf-8", "windows-1251", ($res['vipolneno'] ? "ДА" : "НЕТ")), ENT_QUOTES, "cp1251") . '</td>
			</tr>';
        }
        echo '</table>';

        exit();
    }

    function sayty_load_from_ggl($db) {
        include(PATH . 'includes/simple_html_dom.php');
        $uid = (int) $_REQUEST['uid'];
        if (empty($uid)) {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/no_rights.tpl');
            $content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
            return $content;
            exit;
        }
        $cookie_jar = tempnam(PATH . 'temp', "cookie");

        $user = $db->Execute("SELECT * FROM admins WHERE id=$uid")->FetchRow();
        $birjs = $db->Execute("SELECT * FROM birjs WHERE birj = 1 AND uid = '$uid'")->FetchRow();
        if (!empty($birjs)) {
            $data = array('e_mail' => $birjs['login'],
                'password' => $birjs['pass'],
                'remember' => "");
            $query_p = http_build_query($data);

            header('Content-Type: text/html; utf-8; charset=UTF-8');
            if ($curl = curl_init()) {
                curl_setopt($curl, CURLOPT_URL, 'https://gogetlinks.net/login.php');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
                @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $query_p);
                curl_exec($curl);
                curl_close($curl);
            }

            if ($curl = curl_init()) {
                curl_setopt($curl, CURLOPT_URL, 'https://gogetlinks.net/my_sites.php');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
                @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                $out = curl_exec($curl);
                $out = iconv("windows-1251", "utf-8", $out);
                curl_close($curl);
            }


            $query = $db->Execute("Select url from sayty");

            $amount = 0;

            while ($omg = $query->FetchRow()) {
                $buf[] = $omg["url"];
                $amount++;
            }

            $open = str_get_html($out);
            foreach ($open->find('script,link,comment') as $tmp)
                $tmp->outertext = '';
            if ($open->innertext != '' and count($open->find('tr[id^=row_body] a[href^=template/edit_site_info.php?site_id=]'))) {
                foreach ($open->find('tr[id^=row_body] a[href^=template/edit_site_info.php?site_id=]') as $a) {
                    $url = $a->plaintext;

                    $i = 0;

                    while ($i < $amount) {
                        if ((strcmp($buf[$i], 'http://' . $url))) {
                            $buf2[] = $url;
                            break;
                        }
                        $i++;
                    }
                }

                $j = 0;

                if ($_REQUEST["check"] == 1 && count($buf2) > 0) {
                    while ($j < count($buf2)) {
                        $db->Execute("Insert INTO sayty(uid,url,login,pass,cena,price,colvos) values('" . $uid . "','http://" . $buf2[$j] . "','" . $data["e_mail"] . "','" . $data["password"] . "', '20', '60', '2000')");
                        $j++;
                    }
                    echo "Сайты добавлены!  <a href='?module=admins&action=sayty&uid=$uid&whyedit=1'><input type='button' value='Назад'></a>";
                } else {
                    $k = 0;
                    if (count($buf2) > 0) {
                        while ($k < count($buf2)) {
                            echo $buf2[$k];
                            echo "<br>";
                            $k++;
                        }
                        echo "<b>Вы действительно хотите добавить эти сайты?</b><br>";
                        echo "<a href='?module=admins&action=sayty&uid=$uid'><input type='button' value='Нет'></a>";
                        echo "<a href='?module=admins&action=sayty&uid=$uid&action2=load&check=1'><input type='button' value='Да'></a>";
                    } else
                        echo "Новых сайтов не найдено <a href='?module=admins&action=sayty&uid=$uid'><input type='button' value='Назад'></a>";
                }
            }
        }
        exit();
    }

    function zadaniya_to_email($db) {

        $uid = (int) $_REQUEST['uid'];
        $res = $db->Execute("select * from admins where id=$uid")->FetchRow();
        $imya = $res['login'];

        $sid = (int) $_GET['sid'];
        $query2 = $db->Execute("select * from sayty where uid=$uid");
        $zadaniya = '';
        $names = '';
        $summ = 0;
        $colvo = 0;
        $message.='<table border="1" width="100%" ><tr><td>Sistema</td><td>Ankor</td><td>URL</td><td>Keywords</td><td>Tema</td><td>URL statji</td><td>Date</td><td>Site</td></tr>';
        while ($res2 = $query2->FetchRow()) {
            $sid = $res2['id'];
            $names.=$res2['url'] . ', ';
            $price = $res2['price'];
            $site = $res2['url'];
            if ($_POST['date-from'] || $_POST['date-to']) {
                if ($_POST['date-from'])
                    $from = strtotime($db->escape($_POST['date-from']));

                if ($_POST['date-to'])
                    $to = strtotime($db->escape($_POST['date-to']));

                if ($from && $to) {
                    $query = $db->Execute("select * from zadaniya where vipolneno=1 AND sid=$sid AND date BETWEEN '" . $from . "' AND '" . $to . "' order by date");
                } else {
                    if ($from)
                        $query = $db->Execute("select * from zadaniya where vipolneno=1 AND sid=$sid AND date >= '" . $from . "' order by date");
                    else
                        $query = $db->Execute("select * from zadaniya where vipolneno=1 AND sid=$sid AND date <= '" . $to . "' order by date");
                }
            }
            else {
                $query = $db->Execute("select * from zadaniya where vipolneno=1 AND `date`>UNIX_TIMESTAMP(DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)) and sid=$sid order by date");
            }
            while ($res = $query->FetchRow()) {
                if ($res['vipolneno'] == 1) {
                    $summ = $summ + $price;
                    $colvo++;
                    $message.="<tr><td>" . $res['sistema'] . "</td><td>" . $res['ankor'] . "</td><td>" . $res['url'] . "</td><td>" . $res['keywords'] . "</td><td>" . $res['tema'] . "</td><td>" . $res['url_statyi'] . "</td><td>" . date("d-m-Y", $res['date']) . "</td><td>" . $site . "</td></tr>";
                }
            }
        }
        $query = $db->Execute("select email from admins where id=$uid");
        $email = $query->FetchRow();
        $message.='</table><br><br>Всего заданий выполнено: ' . $colvo . '<br>Всего заработано: ' . $summ . 'руб.';

        if ($colvo == 0) {
            echo "<script>
                    alert(\"Нет данных для отчета за данный период!\");
                    var delay = 500;
                    setTimeout(\"document.location.href='?module=admins&action=sayty&uid=" . $uid . "'\", delay);
                  </script>";
            exit();
        }
        $xls = tempnam(PATH . 'temp', "list_");
        $handle = fopen($xls, "w");
        fwrite($handle, $message);
        fclose($handle);
        //fwrite($xls, $message); // Записываем во временный файл
        fseek($xls, 0); // Устанавливаем указатель файла
        rename($xls, $xls.='.xls');
        //copy($xls,  PATH.'temp/list.xls');
        //$email['email'] = MAIL_ADMIN;

        require_once PATH . 'includes/libmail.php';
        $m = new Mail(utf - 8); // начинаем 
        $m->From("admin@iforget.ru"); // от кого отправляется почта 
        $m->Bcc("webstels@yandex.ru");
        $m->To($email['email']); // кому адресованно
        $m->Subject("Отчет");
        $m->Body("
                    Добрый день, " . $imya . "

                    Отчет о проделанной работе над сайтами (" . $names . ").
                    Общая сумма за проделанную работу " . $summ . " руб.

                    Оплата на кошелек R340688327144
                    В комментариях пишите за какой сайт идет оплата");
        $m->Priority(3);    // приоритет письма
        $m->Attach($xls, "", ""); // прикрепленный файл 
        $m->Send();    // а теперь пошла отправка
        //print_r  ($res2);
        echo "<script>
            alert(\"Отчет успешно отправлен!\");
            var delay = 500;
            setTimeout(\"document.location.href='?module=admins&action=sayty&uid=" . $uid . "'\", delay);
          </script>";
        //echo fread($xls, 2048); // выводим содержимое файла
        exit();
    }

    function view($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/view.tpl');
        if (isset($_POST['sitetypes']))
            $status = $_POST['sitetypes'];
        else
            $status = "active";

        $condition = "";
        $siteselform = "<table><tr><td><form method=post action='?module=admins&action=viewusers'><input type=radio name=sitetypes value=active onclick=this.form.submit()> Активные юзеры <input type=radio name=sitetypes value=not onclick=this.form.submit()> Не активные юзеры <input type=radio name=sitetypes value=all checked onclick=this.form.submit()> Все юзеры </form></td></tr></table>";

        switch ($status) {
            case "active":
                $condition = " AND a.active = 1 ";
                $siteselform = "<table><tr><td><form method=post action='?module=admins&action=viewusers'><input type=radio name=sitetypes value=active checked onclick=this.form.submit()> Активные юзеры <input type=radio name=sitetypes value=not onclick=this.form.submit()> Не активные юзеры <input type=radio name=sitetypes value=all onclick=this.form.submit()> Все юзеры </form></td></tr></table>";
                break;
            case "not":
                $siteselform = "<table><tr><td><form method=post action='?module=admins&action=viewusers'><input type=radio name=sitetypes value=active onclick=this.form.submit()> Активные юзеры <input type=radio name=sitetypes value=not  checked onclick=this.form.submit()> Не активные юзеры <input type=radio name=sitetypes value=all onclick=this.form.submit()> Все юзеры </form></td></tr></table>";
                $condition = " AND a.active = 0 ";
                break;
            case "all":
                $siteselform = "<table><tr><td><form method=post action='?module=admins&action=viewusers'><input type=radio name=sitetypes value=active onclick=this.form.submit()> Активные юзеры <input type=radio name=sitetypes value=not onclick=this.form.submit()> Не активные юзеры <input type=radio name=sitetypes value=all checked onclick=this.form.submit()> Все юзеры </form></td></tr></table>";
                $condition = "";
                break;
            default:
                $condition = "";
        }
        $tasks = array();
        $admins = '';
        $query = $db->Execute('SELECT a.*, count(s.uid) as countsayty
                                FROM admins a
                                LEFT JOIN sayty s ON s.uid = a.id
                                WHERE 1=1 ' . $condition . '
                                GROUP BY a.id 
                                ORDER BY countsayty desc, a.login asc');

        $zadaniya = $db->Execute("select * from zadaniya where vrabote=1 and (vipolneno=0 or vipolneno IS NULL) and (dorabotka=0 or dorabotka IS NULL) and (navyklad=0 or navyklad IS NULL) and (vilojeno=0 or vilojeno IS NULL)");
        while ($task = $zadaniya->FetchRow()) {
            if (!isset($tasks[$task["uid"]])) {
                $tasks[$task["uid"]] = 0;
            }
            $tasks[$task["uid"]] += 1;
        }

        $users_balans = $this->getAllUserBalans($db);
        if ($_SESSION['admin']['id']) {
            $total_price = $this->getAllLeftMoney($db);
        }

        while ($res = $query->FetchRow()) {
            $cur_balans = isset($users_balans[$res['id']]) ? $users_balans[$res['id']] : 0;
            if ($status == "active" && $cur_balans <= 0 && $res['id'] != 20) {
                continue;
            }
            $admins .= file_get_contents(PATH . 'modules/admins/tmp/admin/one.tpl');
            $admins = str_replace('[login]', mb_substr($res['login'], 0, 15), $admins);
            $admins = str_replace('[id]', $res['id'], $admins);
            if ($res["type"] == "user") {
                $admins = str_replace('[type]', '<a class="downbirgjs" href="#" onclick="return false;"><img src="/images/interface/downarrow.png" /></a>', $admins);
                $birj = "";
                $birjs_table = file_get_contents(PATH . 'modules/admins/tmp/admin/birj_table_top.tpl');
                $birjs_table = str_replace("[th_comment]", '', $birjs_table);
                $birjs_info = $db->Execute("SELECT b.*, br.url FROM birjs b LEFT JOIN birgi br ON br.id=b.birj WHERE b.uid=" . $res['id']);
                $i = 1;
                while ($birjs = $birjs_info->FetchRow()) {
                    $class = ($i % 2 == 0) ? "style='background:#FFF8DC'" : "style='background:white'";
                    $birj .= file_get_contents(PATH . 'modules/admins/tmp/admin/birja_one.tpl');
                    $birj = str_replace('[class]', $class, $birj);
                    $birj = str_replace('[loginb]', $birjs['login'], $birj);
                    $birj = str_replace('[passb]', $birjs['pass'], $birj);
                    $birj = str_replace('[birja]', $birjs['url'], $birj);
                    $birj = str_replace('[comment_viklad]', "", $birj);
                    $i++;
                }
                if ($birj == "") {
                    $birj = "<tr style='background:#FFF8DC'><td colspan='5'>У пользователя не добавлено бирж</td></tr>";
                }
                $birjs_table = str_replace('[birjs]', $birj, $birjs_table);
                $admins = str_replace('[birjs]', $birjs_table, $admins);
            } else {
                $admins = str_replace('[type]', $res['type'], $admins);
            }
            $uid = $res['id'];

            $admins = str_replace('[z1]', (isset($tasks[$res["id"]]) ? $tasks[$res["id"]] : 0), $admins);
            $admins = str_replace('[z2]', $res['countsayty'], $admins);
            $admins = str_replace('[balans]', ($cur_balans ? $cur_balans : 0) . 'р.', $admins);

            if ($_SESSION['admin']['id']) {
                $total = (isset($total_price[$res['id']]) ? $total_price[$res['id']] : 0) - $cur_balans;
                if ($status == "not") {
                    $total .= "<input type=hidden name=notact value=1>";
                }
                $admins = str_replace('[total_price]', $total, $admins);
            } else {
                $admins = str_replace('[total_price]', "", $admins);
            }

            if ($res['type'] != "admin") {
                if ($res['active'] == 1) {
                    $ssi = "<input class='chbox-user' type=checkbox name=u$uid value=$uid checked>";
                    $ssis = "style='background:white'";
                } else {
                    $ssi = "<input class='chbox-user' type=checkbox name=u$uid value=$uid>";
                    $ssis = "style='background:yellow'";
                }
            } else {
                if ($res['active'] == 1) {
                    $ssi = "<input type=checkbox name=u$uid class='chbox-user' value=$uid checked " . (($status == 'all') ? 'disabled' : '') . ">";
                    $ssis = "style='background:white'";
                } else {
                    $ssi = "<input type=checkbox name=u$uid class='chbox-user' value=$uid checked " . (($status == 'all') ? 'disabled' : '') . ">";
                    $ssis = "style='background:yellow'";
                }
            }

            $admins = str_replace('[aktstyle]', $ssis, $admins);
            $admins = str_replace('[aktivn]', $ssi, $admins);
        }



        if ($admins)
            $admins = str_replace('[admins]', $admins, file_get_contents(PATH . 'modules/admins/tmp/admin/top.tpl'));
        else
            $admins = file_get_contents(PATH . 'modules/admins/tmp/admin/no.tpl');


        $content = str_replace('[siteselform]', $siteselform, $content);
        $content = str_replace('[admins]', $admins, $content);

        return $content;
    }

    function change_status_on($db) {
        $uid = (int) $_POST['uid'];
        $db->Execute("UPDATE admins SET active = 1 WHERE id = $uid");
        exit();
    }

    function change_status_off($db) {
        $uid = (int) $_POST['uid'];
        $db->Execute("UPDATE admins SET active = 0 WHERE id = $uid");
        exit();
    }

    function sayty_add($db) {
        $uid = (int) $_REQUEST['uid'];
        $owner = $db->Execute("select * from admins where active=1 ORDER BY login");
        $owner_str = "";
        while ($res_v = $owner->FetchRow()) {
            $owner_str.='<option value="' . $res_v['id'] . '" ' . ($uid == $res_v['id'] ? 'selected' : '') . '>' . $res_v['login'] . '</option>';
        }

        $send = @$_REQUEST['send'];
        if (!$send) {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/sayty_add.tpl');
            $content = str_replace('[uid]', $uid, $content);
            $content = str_replace('[owner]', $owner_str, $content);
        } else {
            $login = $_REQUEST['login'];
            $pass = $_REQUEST['pass'];
            $url = $_REQUEST['url'];
            $url = str_replace('http://', '', $url);
            $url = str_replace('www.', '', $url);
            $url_admin = $_REQUEST['url_admin'];
            $price = $_REQUEST['price'];

            $site_subject = $_REQUEST['site_subject'];
            $site_subject_more = $_REQUEST['site_subject_more'];
            $cms = $_REQUEST['cms'];
            $gid = $_REQUEST['gid'];
            $getgoodlinksId = $_REQUEST['getgoodlinksId'];
            $sape_id = $_REQUEST['sape_id'];
            $miralinks_id = $_REQUEST['miralinks_id'];
            $webartex_id = $_REQUEST['webartex_id'];
            $blogun_id = $_REQUEST['blogun_id'];
            $subj_flag = ($_REQUEST['subj_flag'] == "Да" ? 1 : 0);
            $obzor_flag = ($_REQUEST['obzor_flag'] == "Да" ? 1 : 0);
            $news_flag = ($_REQUEST['news_flag'] == "Да" ? 1 : 0);
            $bad_flag = ($_REQUEST['bad_flag'] == "Да" ? 1 : 0);
            $anons_size = $_REQUEST['anons_size'];
            $pic_width = $_REQUEST['pic_width'];
            $pic_height = $_REQUEST['pic_height'];
            $pic_position = $_REQUEST['pic_position'];
            $site_comments = $_REQUEST['site_comments'];

            $db->Execute("insert into sayty(uid, url, url_admin, login, pass, price, gid, getgoodlinks_id, sape_id, miralinks_id, webartex_id, blogun_id, site_subject, site_subject_more, cms, obzor_flag, news_flag, subj_flag, bad_flag, anons_size, pic_width, pic_height, pic_position, site_comments) values 
					($uid, '$url', '$url_admin', '$login', '$pass', '$price', '$gid', '$getgoodlinksId', '$sape_id', '$miralinks_id', '$blogun_id', '$webartex_id', '$site_subject', '$site_subject_more', '$cms', '$obzor_flag', '$news_flag', '$subj_flag', '$bad_flag', '$anons_size', '$pic_width', '$pic_height', '$pic_position', '$site_comments')");

            $alert = 'Сайт успешно добавлен.';
            $url = "?module=admins&action=sayty&uid=$uid";

            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
            $content = str_replace('[alert]', $alert, $content);
            $content = str_replace('[url]', $url, $content);
        }

        $query = $db->Execute("select * from admins where id=$uid");
        $res = $query->FetchRow();
        $content = str_replace('[login]', $res['login'], $content);

        return $content;
    }

    function zadaniya_add($db) {
        $starttime = time();
        $profil = "";
        $profil .= microtime() . "  - START FUNCTION" . "\r\n";
        $uid = (int) $_REQUEST['uid'];
        $sid = (int) $_GET['sid'];

        $task_site = $db->Execute("select * from sayty where id=$sid")->FetchRow();
        $user = $db->Execute("select * from admins where id=$uid")->FetchRow();
        $send = @$_REQUEST['send'];
        if (!$send) {
            $profil .= microtime() . "  - NOT SEND" . "\r\n";
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/zadaniya_add.tpl');
            $sistema = "<option value=''></option>";
            $birgi = $db->Execute("SELECT * FROM birgi");
            while ($birga = $birgi->FetchRow()) {
                $sistema .= "<option value='" . $birga['url'] . "'>" . $birga['url'] . "</option>";
            }

            if ($task_site["bad_flag"] == 0) {
                $content = str_replace('[noporno]', "<span class='small red'>Не принимать задания секс, порно и тд.</span><br>", $content);
            } else {
                $content = str_replace('[noporno]', "", $content);
            }
            if ($task_site["subj_flag"] == 1) {
                $content = str_replace('[themes]', "<span class='small red'>Берутся только тематичные задания (" . $task_site["site_subject"] . (!empty($task_site["site_subject_more"]) ? '(' . $task_site["site_subject_more"] . ')' : '') . ")</span>", $content);
            } else {
                $content = str_replace('[themes]', "<span class='small red'>Берутся все задания.</span>", $content);
            }


            $content = str_replace('[sistema1]', $sistema, $content);
            $content = str_replace('[type0]', "", $content);
            $content = str_replace('[type1]', "", $content);
            $content = str_replace('[uid]', $uid, $content);
            $content = str_replace('[sid]', $sid, $content);
        } else {
            $profil .= microtime() . "  - SEND FORM" . "\r\n";
            $sistema = $_REQUEST['sistema'];
            $ankor = $_REQUEST['ankor'];
            $ankor2 = $_REQUEST['ankor2'];
            $ankor3 = $_REQUEST['ankor3'];
            $ankor4 = $_REQUEST['ankor4'];
            $ankor5 = $_REQUEST['ankor5'];
            $url = $_REQUEST['url'];
            $url2 = $_REQUEST['url2'];
            $url3 = $_REQUEST['url3'];
            $url4 = $_REQUEST['url4'];
            $url5 = $_REQUEST['url5'];
            $keywords = $_REQUEST['keywords'];
            $tema = str_replace("\"", "\\\"", $_REQUEST['tema']);
            $lay_out = isset($_REQUEST['lay_out']) ? 1 : 0;
            $text = addslashes($_REQUEST['text']);
            $url_statyi = $_REQUEST['url_statyi'];
            $url_pic = $_REQUEST['url_pic'];
            $price = $_REQUEST['price'];
            $comments = mysql_real_escape_string($_REQUEST['comments']);
            $admin_comments = $_REQUEST['admin_comments'];
            $type_task = @$_REQUEST['type'];
            $task_status = $_REQUEST['task_status'];
            $etxt = 0;
            if ($lay_out == 0) {
                if ($task_status == "vipolneno")
                    $vipolneno = 1;
                else
                    $vipolneno = 0;
                if ($task_status == "dorabotka")
                    $dorabotka = 1;
                else
                    $dorabotka = 0;
                if ($task_status == "vrabote")
                    $vrabote = 1;
                else
                    $vrabote = 0;
                if ($task_status == "navyklad")
                    $navyklad = 1;
                else
                    $navyklad = 0;
                if ($task_status == "vilojeno")
                    $vilojeno = 1;
                else
                    $vilojeno = 0;
            } else {
                $vipolneno = $dorabotka = $vrabote = 0;
                $navyklad = 1;
                $etxt = 1;
            }
            $profil .= microtime() . "  - GET DATA" . "\r\n";
            $date = time();
            $db->Execute("insert into zadaniya(etxt, dorabotka, date, uid, sid, sistema, type_task, ankor, ankor2, ankor3, ankor4, ankor5, url, url2, url3, url4, url5, keywords, tema, text, url_statyi, vipolneno, price, vrabote, url_pic, navyklad, comments, admin_comments, lay_out) values($etxt, $dorabotka, $date, $uid, $sid, '$sistema', '$type_task', '$ankor', '$ankor2', '$ankor3', '$ankor4', '$ankor5', '$url', '$url2', '$url3', '$url4', '$url5', '$keywords', '$tema', '$text', '$url_statyi', $vipolneno, '$price', $vrabote, '$url_pic', $navyklad, '$comments', '$admin_comments', '$lay_out')");
            $lastId = $db->Insert_ID();
            $profil .= microtime() . "  - AFTER QUERY 1 UPDATE" . "\r\n";
            $compl = $db->Execute("SELECT * FROM completed_tasks WHERE uid = $uid AND zid=" . $lastId)->FetchRow();
            if (($navyklad == 1 || $dorabotka == 1 || $vipolneno == 1 || $vilojeno == 1 || $vrabote == 1) && empty($compl)) {
                $price = 0;
                if ($lay_out == 1 || $lay_out == "1") {
                    $price = 15;
                } elseif ($sistema == "http://miralinks.ru/" || $sistema == "http://pr.sape.ru/" || $sistema == "http://getgoodlinks.ru/" || $sistema == "https://gogetlinks.net/") {
                    switch ($task_site['cena']) {
                        case 20:$price = 60;
                            break;
                        case 30:$price = 76;
                            break;
                        case 45:$price = 111;
                            break;
                        default:$price = 60;
                            break;
                    }
                } else {
                    $price = $task_site['price'];
                }
                if ($user["new_user"] == 1 && !$lay_out) {
                    //увеличение цен для новых пользователей на 30%
                    $price = (int) $price + 17;
                }

                $db->Execute("INSERT INTO completed_tasks (uid, zid, date, price, status) VALUES ('$uid', '$lastId', '" . date("Y-m-d H:i:s") . "', '$price', '1')");
                $profil .= microtime() . "  - AFTER QUERY 2 INSERT compl_tasks" . "\r\n";
            }

            $alert = 'Задание успешно добавлено.';
            $url = "?module=admins&action=zadaniya&uid=$uid&sid=$sid";

            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
            $content = str_replace('[alert]', $alert, $content);
            $content = str_replace('[url]', $url, $content);
        }
        $profil .= microtime() . "  - END FUNCTION" . "\r\n";
        $endtime = time() - $starttime;
        if ($endtime > 3) {
            $profil .= "ALL TIME - " . $endtime;
            $file = 'temp_file/add_task/' . $endtime . '-(' . time() . ').txt';
            file_put_contents($file, $profil);
        }
        $admins = $db->Execute("select * from admins where id=$uid")->FetchRow();
        $content = str_replace('[login]', $admins['login'], $content);
        $content = str_replace('[url]', $task_site['url'], $content);

        return $content;
    }

    function add($db) {
        if (@$_SESSION['admin']['type'] == 'admin' || @$_SESSION['manager']['type'] == 'manager') {
            $send = @$_REQUEST['send'];
            if (!$send) {
                $content = file_get_contents(PATH . 'modules/admins/tmp/admin/add.tpl');
                $rights = "";
                if (!@$_SESSION['admin']['id']) {
                    $rights = "style='display:none'";
                }
                $content = str_replace('[rights]', $rights, $content);
            } else {
                $login = $_REQUEST['login'];
                $type = $_REQUEST['type'];
                $pass = md5($_REQUEST['pass']);
                $date = $_REQUEST['date'];
                $icq = $_REQUEST['icq'];
                $scype = $_REQUEST['scype'];
                $contacts = $_REQUEST['contacts'];
                $dostupy = "";

                if (empty($date) || !isset($date)) {
                    $date = time("d.m.Y");
                } else {
                    $date = explode('.', $date);
                    $date = mktime(4, 0, 0, $date[1], $date[0], $date[2]);
                }

                //если зарегестрировался позже 1 ноября 2014,
                //то пользователь считается новым и цены для него выше
                $new_user = 1;

                $query = $db->Execute("select * from admins where login='$login'");
                $res = $query->FetchRow();
                if ($res['id']) {
                    $alert = 'Пользователь с таким логином уже имеется в базе';
                    $url = '?module=admins&action=add';
                } else {
                    $db->Execute("insert into admins(login, pass, type, active, reg_date, contacts, icq, scype, dostupy, new_user) values ('$login', '$pass', '$type', 1, $date, '$contacts', '$icq', '$scype', '$dostupy', '$new_user')");
                    $alert = 'Пользователь успешно добавлен.';
                    $url = '?module=admins&action=viewusers';
                }
                $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
                $content = str_replace('[alert]', $alert, $content);
                $content = str_replace('[url]', $url, $content);
            }
            return $content;
        } else {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/no_rights.tpl');
            $content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
            return $content;
            exit;
        }
    }

    function edit($db) {
        if (!@$_SESSION['admin']['id'] && !@$_SESSION['manager']['id']) {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
            $content = str_replace('[alert]', 'Данное действие Вам недоступно', $content);
            $content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
            echo $content;
            exit;
        }
        $send = @$_REQUEST['send'];
        $id = (int) $_REQUEST['id'];
        $res = $db->Execute("select * from admins where id=$id")->FetchRow();
        $res['reg_date'] = date('d.m.Y', $res['reg_date']);

        if ($res['type'] == 'admin') {
            $res['user'] = $res['manager'] = $res['moder'] = $res['copywriter'] = '';
            $res['admin'] = 'selected';
        } elseif ($res['type'] == 'manager') {
            $res['user'] = $res['moder'] = $res['admin'] = $res['copywriter'] = '';
            $res['manager'] = 'selected';
        } elseif ($res['type'] == 'moder') {
            $res['user'] = $res['manager'] = $res['admin'] = $res['copywriter'] = '';
            $res['moder'] = 'selected';
        } elseif ($res['type'] == 'copywriter') {
            $res['user'] = $res['manager'] = $res['admin'] = $res['moder'] = '';
            $res['copywriter'] = 'selected';
        } else {
            $res['copywriter'] = $res['manager'] = $res['admin'] = $res['moder'] = '';
            $res['user'] = 'selected';
        }

        if (!$send) {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/edit.tpl');
            if (!@$_SESSION['admin']['id']) {
                $content = str_replace("[rights]", "style='display:none'", $content);
            } else {
                $content = str_replace("[rights]", "", $content);
            }
            foreach ($res as $k => $v) {
                $content = str_replace("[$k]", $v, $content);
            }
        } else {
            $login = $_REQUEST['login'];
            $email = $_REQUEST['email'];
            $type = $_REQUEST['type'];
            $icq = $_REQUEST['icq'];
            $scype = $_REQUEST['scype'];
            $contacts = $_REQUEST['contacts'];
            $comment_viklad = $_REQUEST['comment_viklad'];
            if ($_REQUEST['pass'])
                $pass = md5($_REQUEST['pass']);
            else
                $pass = $res['pass'];
            $date = explode('.', @$_REQUEST['date']);
            $time = mktime(12, 0, 0, $date[1], $date[0], $date[2]);

            $user = $db->Execute("select * from admins where login='$login' and id!=$id")->FetchRow();
            if ($user['id']) {
                $alert = 'Пользователь с таким логином уже имеется в базе';
                $url = '?module=admins&action=add';
            } else {
                $db->Execute("update admins set login='$login', email='$email', pass='$pass', type='$type', reg_date='$time', contacts='$contacts', icq='$icq', scype='$scype', comment_viklad='$comment_viklad' where id=$id");
                $alert = 'Пользователь успешно отредактирован.';
                $url = '?module=admins&action=viewusers';
            }
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
            $content = str_replace('[alert]', $alert, $content);
            $content = str_replace('[url]', $url, $content);
        }
        return $content;
    }

    function sayty_edit($db) {

        $send = @$_REQUEST['send'];
        $id = (int) $_REQUEST['id'];
        $uid = (int) $_REQUEST['uid'];

        $res = $db->Execute("select * from sayty where id=$id")->FetchRow();
        $gid = $res['gid'];
        $getgoodlinksId = $res['getgoodlinks_id'];
        $sape_id = $res['sape_id'];
        $miralinks_id = $res["miralinks_id"];
        $cena = $res['cena'];
        $colvos = $res['colvos'];
        $user = $db->Execute("select * from admins where id=" . $uid)->FetchRow();

        $query_v = $db->Execute("select * from admins where type='moder' AND active=1 ORDER BY login");
        $str_v = "<option></option>";
        while ($res_v = $query_v->FetchRow()) {
            $str_v.='<option value="' . $res_v['id'] . '" ' . ($res['moder_id'] == $res_v['id'] ? 'selected' : '') . '>' . $res_v['login'] . '</option>';
        }
        $res['str_v'] = $str_v;

        $owner = $db->Execute("select * from admins where 1=1 ORDER BY login");
        $owner_str = "";
        while ($res_v = $owner->FetchRow()) {
            $owner_str.='<option value="' . $res_v['id'] . '" ' . ($res['uid'] == $res_v['id'] ? 'selected' : '') . '>' . $res_v['login'] . '</option>';
        }

        if (!$send) {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/sayty_edit.tpl');
            foreach ($res as $k => $v) {
                $content = str_replace("[$k]", $v, $content);
            }
            $content = str_replace('[uid]', $uid, $content);
            $content = str_replace('[cena]', $cena, $content);
            $content = str_replace('[gid]', $gid, $content);
            $content = str_replace('[getgoodlinksId]', $getgoodlinksId, $content);
            $content = str_replace('[sape_id]', $sape_id, $content);
            $content = str_replace('[miralinks_id]', $miralinks_id, $content);
            $content = str_replace('[colvos]', $colvos, $content);
            $content = str_replace('[owner]', $owner_str, $content);

            $content = str_replace('[' . $res["site_subject"] . ']', "selected", $content);
            $content = str_replace('[' . $res["cms"] . ']', "selected", $content);
            $content = str_replace('[' . $res["pic_position"] . ']', "selected", $content);
            $content = str_replace('[obzor_' . $res["obzor_flag"] . ']', "selected", $content);
            $content = str_replace('[news_' . $res["news_flag"] . ']', "selected", $content);
            $content = str_replace('[subj_' . $res["subj_flag"] . ']', "selected", $content);
            $content = str_replace('[bad_' . $res["bad_flag"] . ']', "selected", $content);
            if ($uid == 421) {
                $content = str_replace('[readonly]', ' readonly="readonly"', $content);
            } else {
                $content = str_replace('[readonly]', '', $content);
            }

            if (!@$_SESSION['admin']['id']) {
                $content = str_replace('[rights]', 'style="display:none"', $content);
            } else {

                $option = '<option value="20-45-1500" [cena_45]>45 руб. - 1500 знаков (econom)</option>
                        <option value="30-61-1500" [cena_61]>61 руб. - 1500 знаков (medium)</option>
                        <option value="50-93-1500" [cena_93]>93 руб. - 1500 знаков (elit)</option>
                        <option value="20-60-2000" [cena_60]>60 руб. - 2000 знаков (econom)</option>
                        <option value="30-76-2000" [cena_76]>76 руб. - 2000 знаков (medium)</option>
                        <option value="45-111-2000" [cena_111]>111 руб. - 2000 знаков (elit)</option>';

                $option_newUser = '<option value="20-45-1500" [cena_45]>62 рубл. - 1500 знаков (econom)</option>
                        <option value="30-61-1500" [cena_61]>78 рубл. - 1500 знаков (medium)</option>
                        <option value="50-93-1500" [cena_93]>110 рубл. - 1500 знаков (elit)</option>
                        <option value="20-60-2000" [cena_60]>77 руб. - 2000 знаков (econom)</option>
                        <option value="30-76-2000" [cena_76]>93 руб. - 2000 знаков (medium)</option>
                        <option value="45-111-2000" [cena_111]>128 руб. - 2000 знаков (elit)</option>';

                if ($user["new_user"]) {
                    $content = str_replace('[prices_option]', $option_newUser, $content);
                } else {
                    $content = str_replace('[prices_option]', $option, $content);
                }
                $content = str_replace('[cena_' . $res['price'] . ']', "selected", $content);
            }
        } else {
            $login = $_REQUEST['login'];
            $pass = $_REQUEST['pass'];
            $url = $_REQUEST['url'];
            $gid = $_REQUEST['gid'];
            $getgoodlinksId = $_REQUEST['getgoodlinksId'];
            $sape_id = $_REQUEST['sape_id'];
            $miralinks_id = $_REQUEST['miralinks_id'];
            $webartex_id = $_REQUEST['webartex_id'];
            $blogun_id = $_REQUEST['blogun_id'];
            $url_admin = $_REQUEST['url_admin'];
            $moder_id = $_REQUEST['viklad'];
            $owner_id = $_REQUEST['owner'];
            $price_viklad = $_REQUEST['price_viklad'];
            $comment_viklad = $_REQUEST['comment_viklad'];
            $question_viklad = $_REQUEST['question_viklad'];

            $site_subject = $_REQUEST['site_subject'];
            $site_subject_more = $_REQUEST['site_subject_more'];
            $cms = $_REQUEST['cms'];
            $subj_flag = ($_REQUEST['subj_flag'] == "Да" ? 1 : 0);
            $obzor_flag = ($_REQUEST['obzor_flag'] == "Да" ? 1 : 0);
            $news_flag = ($_REQUEST['news_flag'] == "Да" ? 1 : 0);
            $bad_flag = ($_REQUEST['bad_flag'] == "Да" ? 1 : 0);
            $anons_size = $_REQUEST['anons_size'];
            $pic_width = $_REQUEST['pic_width'];
            $pic_height = $_REQUEST['pic_height'];
            $pic_position = $_REQUEST['pic_position'];
            $site_comments = $_REQUEST['site_comments'];
            $tmp_price = @$_REQUEST['cena'];
            if (!empty($tmp_price)) {
                $tmp_price = explode('-', $tmp_price);
                $price_iforget = $tmp_price[1];
                $price_etxt = $tmp_price[0];
                $colvos = $tmp_price[2];
            } else {
                $price_iforget = !empty($res["price"]) ? $res["price"] : 45;
                $price_etxt = !empty($res["cena"]) ? $res["cena"] : 20;
                $colvos = !empty($res["colvos"]) ? $res["colvos"] : 1500;
            }
            if ($owner_id == 330 && $id == 8192495) {
                $price_iforget = 40;
            }

            $db->Execute("update sayty set uid='$owner_id', colvos='$colvos', login='$login', gid='$gid', getgoodlinks_id='$getgoodlinksId', sape_id='$sape_id', miralinks_id='$miralinks_id', webartex_id='$webartex_id', blogun_id='$blogun_id', pass='$pass', url='$url', 
                                        url_admin='$url_admin', price='$price_iforget', cena='$price_etxt', moder_id='$moder_id', price_viklad = '$price_viklad', comment_viklad='$comment_viklad', question_viklad='$question_viklad',
					site_subject='$site_subject', site_subject_more='$site_subject_more', cms='$cms', subj_flag='$subj_flag', obzor_flag='$obzor_flag', news_flag='$news_flag', 
					bad_flag='$bad_flag', anons_size='$anons_size', pic_width='$pic_width', pic_height='$pic_height', pic_position='$pic_position', site_comments='$site_comments' where id=$id");


            header('location: ?module=admins&action=sayty&uid=' . $uid);
        }
        return $content;
    }

    function zadaniya_edit($db) {

        $starttime = time();
        $profil = "";
        $profil .= time() . "\r\n";

        $send = @$_REQUEST['send'];
        $id = (int) $_REQUEST['id'];
        $uid = (int) $_REQUEST['uid'];
        $sid = (int) $_GET['sid'];
        $query = $db->Execute("select * from zadaniya where id=$id");
        $user = $db->Execute("select * from admins where id=$uid")->FetchRow();
        $res = $query->FetchRow();
        $profil .= microtime() . "  - AFTER 1 && 2 QUERY" . "\r\n";
        if (!$send) {
            $profil .= microtime() . "  - NOT SEND" . "\r\n";
            $_SESSION["HTTP_REFERER"] = @$_SERVER["HTTP_REFERER"];
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/zadaniya_edit.tpl');
            //####################################################################
            //Смотрим на баланс и проверяем, может ли создать пользователь заявку (хватит ли денег)
            $cur_balans = $this->getUserBalans($uid, $db, 1);
            $sinfo = $db->Execute("SELECT * FROM sayty WHERE id=$sid")->FetchRow();
            $lay_out = $res['lay_out'];
            if ($res['lay_out'] == 1) {
                $cur_balans -= 15;
            } else if ($res['sistema'] == "http://miralinks.ru/" || $res['sistema'] == "http://pr.sape.ru/" || $res['sistema'] == "http://getgoodlinks.ru/") {
                //$cur_balans -= (2 * $sinfo['cena']) + (round($sinfo['cena'] / 10)) + 13;
                switch ($sinfo['cena']) {
                    case 20:$cur_balans -= 60;
                        break;
                    case 30:$cur_balans -= 76;
                        break;
                    case 45:$cur_balans -= 111;
                        break;
                    default:$cur_balans -= 60;
                        break;
                }
            } else {
                $cur_balans -= $sinfo['price'];
            }
            if ($user["new_user"] == 1 && !$res['lay_out']) {
                $cur_balans -= 17;
            }
            $nomoney = "";
            $stat_disabled = "";
            if ($cur_balans < 0) {
                if (($res['dorabotka'] == 0) && ($res['vrabote'] == 0) && ($res['navyklad'] == 0) && ($res['vilojeno'] == 0) && ($res['vipolneno'] == 0)) {
                    $nomoney = "<p>Внимание! Баланс счета пользователя недостаточен для того, чтобы задание поступило в работу!</p>
								<p>Возможность перевода заявки в статус &laquo;В работе&raquo отключена!</p>";
                    if (($uid != 20) && ($uid != 55))
                        $stat_disabled = "disabled='disabled'";
                }
            }
            $profil .= microtime() . "  - OPERATIONS WITH MONEY" . "\r\n";
            $content = str_replace("[id]", $res["id"], $content);
            $content = str_replace('[zid]', $id, $content);
            $content = str_replace('[nomoney]', $nomoney, $content);
            $content = str_replace('[stat_disabled]', $stat_disabled, $content);

            $res['vipolneno'] = $res['vipolneno'] ? 'checked="checked"' : '';
            $res['dorabotka'] = $res['dorabotka'] ? 'checked="checked"' : '';
            $res['vrabote'] = $res['vrabote'] ? 'checked="checked"' : '';
            $res['navyklad'] = $res['navyklad'] ? 'checked="checked"' : '';
            $res['vilojeno'] = $res['vilojeno'] ? 'checked="checked"' : '';
            $res['rework'] = $res['rework'] ? 'checked="checked"' : '';

            $url_sayta = '<a href="?module=admins&action=sayty&uid=' . $sinfo['uid'] . '&action2=edit&id=' . $sinfo['id'] . '" target="_blank">' . $sinfo['url'] . '</a>';
            $content = str_replace('[url_sayta]', $url_sayta, $content);
            $content = str_replace('[login]', $user["login"], $content);

            $sistema = "<option value=''></option>";
            $birgi = $db->Execute("SELECT * FROM birgi");
            while ($birga = $birgi->FetchRow()) {
                if ($birga['url'] == $res['sistema']) {
                    $sistema .= "<option value='" . $birga['url'] . "' selected='selected'>" . $birga['url'] . "</option>";
                } else
                    $sistema .= "<option value='" . $birga['url'] . "'>" . $birga['url'] . "</option>";
            }
            $content = str_replace('[sistema1]', $sistema, $content);
            $content = str_replace("[burse]", (($res['sistema'] == "https://blogun.ru/") ? "Blogun_id" : "GGL_ID"), $content);
            if ((int) $res['type_task'] == 0) {
                $content = str_replace('[type0]', "selected='selected'", $content);
                $content = str_replace('[type1]', "", $content);
                $content = str_replace('[type2]', "", $content);
            } elseif ((int) $res['type_task'] == 1) {
                $content = str_replace('[type0]', "", $content);
                $content = str_replace('[type1]', "selected='selected'", $content);
                $content = str_replace('[type2]', "", $content);
            } else {
                $content = str_replace('[type0]', "", $content);
                $content = str_replace('[type1]', "", $content);
                $content = str_replace('[type2]', "selected='selected'", $content);
            }
            $content = str_replace('[date]', date("d-m-Y H:i:s", $res["date"]), $content);
            foreach ($res as $k => $v) {
                $content = str_replace("[$k]", htmlspecialchars($v), $content);
            }
            if ($res['copywriter'] != 0) {
                $chat = $db->Execute("SELECT ch.*, a.login FROM chat_admin_copywriter ch LEFT JOIN admins a ON ch.uid=a.id WHERE zid='$id' AND burse=1 ORDER BY ch.date DESC");
                $message_copywriter = "";
                $message_copywriter_count = 2;
                while ($value = $chat->FetchRow()) {
                    if ($message_copywriter_count < 10)
                        $message_copywriter_count += 2;
                    $message_copywriter .= $value['login'] . " (" . $value['date'] . ")" . "\n";
                    $message_copywriter .= $value['msg'] . "\n\n";
                }
                $message_copywriter = trim($message_copywriter, "\n\n");
                $content = str_replace('[message_copywriter]', $message_copywriter, $content);
                $content = str_replace('[message_copywriter_count]', $message_copywriter_count == 0 ? 2 : $message_copywriter_count, $content);
                $content = str_replace('[display_for_copywriter]', "", $content);
                $content = str_replace('[rework_display]', ($res["rework"] == 'checked="checked"' ? "" : "style='display:none'"), $content);
                $db->Execute("UPDATE chat_admin_copywriter SET status=1 WHERE uid!='" . $_SESSION["admin"]["id"] . "' AND zid='$id' AND burse=1");
            } else {
                $content = str_replace('[display_for_copywriter]', " style='display:none' ", $content);
                $content = str_replace('[rework_display]', " style='display:none' ", $content);
            }

            $content = str_replace('[uid]', $uid, $content);
            $content = str_replace('[sid]', $sid, $content);
            $content = str_replace('[lay_out_text]', (($lay_out) ? "checked" : ""), $content);
            $content = str_replace('[noporno]', (($sinfo["bad_flag"] == 0) ? "<span class='small red'>Не принимать задания секс, порно и тд.</span><br>" : ""), $content);
            $content = str_replace('[themes]', (($sinfo["subj_flag"] == 1) ? "<span class='small red'>Берутся только тематичные задания (" . $sinfo["site_subject"] . (!empty($sinfo["site_subject_more"]) ? '(' . $sinfo["site_subject_more"] . ')' : '') . ")</span>" : "<span class='small red'>Берутся все задания.</span>"), $content);
            $profil .= microtime() . "  - AFTER GET DATA" . "\r\n";
        } else {
            $profil .= microtime() . "  - SEND FORM !!" . "\r\n";

            $sistema = $_REQUEST['sistema'];
            $etxt = $_REQUEST['etxt'];
            $ankor = ($_REQUEST['ankor']);
            $ankor2 = ($_REQUEST['ankor2']);
            $ankor3 = ($_REQUEST['ankor3']);
            $ankor4 = ($_REQUEST['ankor4']);
            $ankor5 = ($_REQUEST['ankor5']);
            $url = ($_REQUEST['url']);
            $url2 = ($_REQUEST['url2']);
            $url3 = ($_REQUEST['url3']);
            $url4 = ($_REQUEST['url4']);
            $url5 = ($_REQUEST['url5']);
            $keywords = $_REQUEST['keywords'];
            $tema = $_REQUEST['tema'];
            $text = addslashes($_REQUEST['text']);
            $url_statyi = $_REQUEST['url_statyi'];
            $url_pic = $_REQUEST['url_pic'];
            $price = (int) @$_REQUEST['price'];
            $comments = mysql_real_escape_string($_REQUEST['comments']);
            $admin_comments = $_REQUEST['admin_comments'];
            $lay_out = isset($_REQUEST['lay_out']) ? 1 : 0;
            $b_id = $_REQUEST['b_id'];
            $sape_id = $_REQUEST['sape_id'];
            $rotapost_id = $_REQUEST['rotapost_id'];
            $task_id = $res['task_id'];
            $type_task = (int) $_REQUEST['type'];
            $profil .= microtime() . "  - GET DATA" . "\r\n";

            $task_site = $db->Execute("SELECT * FROM sayty WHERE id=" . $res['sid'])->FetchRow();
            $viklad_info = $db->Execute("SELECT * FROM admins WHERE id=" . $task_site['moder_id'])->FetchRow();
            $viklad_email = $viklad_info['email'];
            $profil .= microtime() . "  - AFTER 3 && 4 QUERY" . "\r\n";
            $task_status = @$_REQUEST['task_status'];

            $vipolneno = ($task_status == "vipolneno") ? 1 : 0;
            $dorabotka = ($task_status == "dorabotka") ? 1 : 0;
            $vrabote = ($task_status == "vrabote") ? 1 : 0;
            $navyklad = ($task_status == "navyklad") ? 1 : 0;
            $vilojeno = ($task_status == "vilojeno") ? 1 : 0;

            $rework = ($vipolneno == 1 || $dorabotka == 1 || $vrabote == 1 || $navyklad == 1 || $vilojeno == 1) ? 0 : $res["rework"];

            if ($lay_out == 1) {
                $vrabote = 0;
                if ($navyklad == 0 && $vilojeno == 0 && $dorabotka == 0 && $vipolneno == 0) {
                    $navyklad = 1;
                }
                $etxt = 1;
            }

            if ($navyklad == 1 || $dorabotka == 1 || $vilojeno == 1) {
                $message = array();

                if ($navyklad == 1) {
                    // Выкладываем текст автоматом в WORDPRESS
                    //  --> Запрещаем отправление статьи для пользователя "me05"(id=649) и "palexa"(id = 330)
                    include 'includes/IXR_Library.php';
                    $url_connect = explode("/wp-", $task_site["url_admin"]);
                    $url_connect = $url_connect[0];
                    if (!empty($url_connect) && !empty($text) && $uid != 330 && $uid != 649 && $res["navyklad"] != 1 && $task_site["cms"] == "Wordpress") {
                        $client = new IXR_Client($url_connect . '/xmlrpc.php');
                        if ($client->query('wp.getCategories', '', $task_site["login"], $task_site["pass"])) {
                            $cats = $client->getResponse();
                            if (!empty($cats)) {
                                $content['title'] = $tema;
                                $content['categories'] = array();
                                $content['description'] = $text;
                                $content['mt_allow_comments'] = 1;
                                if ($keywords) {
                                    $keywords_wp = explode(",", $keywords);
                                    $content['mt_keywords'] = array();
                                    foreach ($keywords_wp as $word) {
                                        $content['mt_keywords'][] = $word;
                                    }
                                }

                                if ($client->query('metaWeblog.newPost', '', $task_site["login"], $task_site["pass"], $content, false)) {
                                    $ID = $client->getResponse();
                                }
                            }
                        }
                    }

                    if ($uid == 330) { // только для пользователя "palexa" (id = 330)
                        $moder = $db->Execute("select * from admins where id=" . $uid)->FetchRow();
                        $file_name = $this->createXLSForUser($id, $sistema, $ankor, $url, $url_statyi, $tema, $text);
                        $this->_postman->moderator->taskStatusNavykladForPalexa($viklad_info, $task_site, $res, $file_name);
                    } else {
                        $wordpress = "";
                        if (isset($ID)) {
                            $wordpress = "<br />На сайте клиента создан пост под названием '" . $tema . "'. Текст задачи уже выложен. Проверьте корректность текста, соответствие рубрики и др.";
                        }
                        $this->_postman->moderator->taskStatusNavyklad($viklad_info, $task_site, $res, $wordpress);
                    }
                } elseif ($dorabotka == 1) {
                    $this->_postman->moderator->taskStatusDorabotka($viklad_info, $task_site, $res, $admin_comments);
                } else {
                    $this->_postman->admin->taskStatusVilojeno($task_site, $res);
                }
                $profil .= microtime() . "  - SEND MAIL" . "\r\n";
            }

            $q = "update zadaniya set b_id='$b_id',sape_id='$sape_id',rotapost_id='$rotapost_id', dorabotka='$dorabotka', rework='$rework', etxt='$etxt', vipolneno='$vipolneno', vrabote='$vrabote', navyklad='$navyklad', vilojeno='$vilojeno', type_task='$type_task', url_statyi='$url_statyi', text='$text', tema='$tema', sistema='$sistema', ankor='$ankor', ankor2='$ankor2', ankor3='$ankor3', ankor4='$ankor4', ankor5='$ankor5', url='$url', url2='$url2', url3='$url3', url4='$url4', url5='$url5', keywords='$keywords', price='$price', url_pic='$url_pic', comments='$comments', admin_comments='$admin_comments', lay_out='$lay_out' where id=$id";
            $db->Execute($q);
            $profil .= microtime() . "  - QUERY #5 UPDATE task" . "\r\n";
            $compl = $db->Execute("SELECT * FROM completed_tasks WHERE uid = $uid AND zid=" . $id)->FetchRow();
            if (($navyklad == 1 || $dorabotka == 1 || $vilojeno == 1 || $vipolneno == 1 || $vrabote == 1)) {
                $price = 0;
                if ($lay_out == 1 || $lay_out == "1") {
                    $price = 15;
                } elseif ($sistema == "http://miralinks.ru/" || $sistema == "http://pr.sape.ru/" || $sistema == "http://getgoodlinks.ru/" || $sistema == "https://gogetlinks.net/") {
                    switch ($task_site['cena']) {
                        case 20:$price = 60;
                            break;
                        case 30:$price = 76;
                            break;
                        case 45:$price = 111;
                            break;
                        default:$price = 60;
                            break;
                    }
                    if ($lay_out == 1) {
                        $price = 15;
                    }
                } else {
                    $price = $task_site['price'];
                }
                if ($user["new_user"] == 1 && !$lay_out) {
                    //увеличение цен для новых пользователей на 30%
                    $price = (int) $price + 17;
                }

                if (empty($compl)) {
                    $db->Execute("INSERT INTO completed_tasks (uid, zid, date, price, status) VALUES ('$uid', '$id', '" . date("Y-m-d H:i:s") . "', '$price',1)");
                } else {
                    $db->Execute("UPDATE completed_tasks SET date='" . date("Y-m-d H:i:s") . "', price = '$price' WHERE uid = '$uid' AND zid = '$id'");
                }
            }
            $profil .= microtime() . "  - QUERY 6 - INSERT/UPDATE completed_tasks" . "\r\n";

            $alert = 'Задание успешно отредактировано.';
            $offset = $showall = "";
            if ($_SESSION["HTTP_REFERER"]) {
                $pos = strripos($_SESSION["HTTP_REFERER"], "offset=");
                if (!empty($pos)) {
                    $pos = $pos + 7;
                    $offset = substr($_SESSION["HTTP_REFERER"], $pos);
                    $offset = "&offset=" . $offset;
                }
                $pos = strripos($_SESSION["HTTP_REFERER"], "&showall=1");
                if (!empty($pos)) {
                    $showall = "&showall=1";
                }
                unset($_SESSION["HTTP_REFERER"]);
            }
            $url = "?module=admins&action=zadaniya&uid=$uid&sid=$sid" . $showall . $offset;

            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
            $content = str_replace('[alert]', $alert, $content);
            $content = str_replace('[url]', $url, $content);
        }
        $profil .= microtime() . "  - END FUNCTIONS" . "\r\n";
        $endtime = time() - $starttime;
        if ($endtime > 3) {
            $profil .= "ALL TIME - " . $endtime;
            $file = 'temp_file/edit_task/' . $endtime . '-(' . time() . ').txt';
            file_put_contents($file, $profil);
        }
        return $content;
    }

    function zadaniya_del($db) {

        $id = $_REQUEST['id'];
        $uid = $_REQUEST['uid'];
        $sid = $_GET['sid'];

        $db->Execute("delete from zadaniya where id=$id");

        $compl = $db->Execute("SELECT * FROM completed_tasks WHERE zid = $id AND uid = $uid")->FetchRow();
        if ($compl) {
            $db->Execute("DELETE FROM completed_tasks WHERE id=" . $compl["id"]);
        }
        $alert = 'Задание успешно удалено.';

        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
        $content = str_replace('[alert]', $alert, $content);
        $content = str_replace('[url]', "?module=admins&action=zadaniya&uid=$uid&sid=$sid", $content);

        return $content;
    }

    function sayty_del($db) {
        if (!@$_SESSION['admin']['id']) {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/no-rights.tpl');
            $content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
            echo $content;
            exit;
        }
        $id = $_REQUEST['id'];
        $uid = $_REQUEST['uid'];

        $db->Execute("delete from sayty where id=$id");
        $alert = 'Сайт успешно удален.';

        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
        $content = str_replace('[alert]', $alert, $content);
        $content = str_replace('[url]', "?module=admins&action=sayty&uid=$uid", $content);

        return $content;
    }

    function del($db) {
        if ($_SESSION['admin']['type'] != 'admin') {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
            $content = str_replace('[alert]', 'Данное действие Вам недоступно', $content);
            $content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
            echo $content;
            exit;
        }
        $id = $_REQUEST['id'];
        $query = $db->Execute("select * from admins where id=$id");
        $res = $query->FetchRow();
        if ($res['gl'] == 1)
            $alert = 'Этого пользователя нельзя удалить.';
        else {
            $db->Execute("delete from admins where id=$id");
            $alert = 'Пользователь успешно удален.';
        }
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
        $content = str_replace('[alert]', $alert, $content);
        $content = str_replace('[url]', '?module=admins', $content);

        return $content;
    }

    function loadXLS($db) {
        setlocale(LC_ALL, 'ru_RU.cp1251');
        //echo("Locale=".setLocale(LC_ALL, NULL));
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/viewXLS.tpl');
        $row = 1;

        $birja = @$_REQUEST['birja'];

        if (@$_FILES['xls']['tmp_name'] && (($handle = fopen($_FILES['xls']['tmp_name'], "r")) !== FALSE)) {
            while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
                $num = count($data);
                if ($row > 1) {
                    $site = iconv("cp1251", "utf8", $data[0]);
                    //step.1. ищем, какому пользователю принадлежит сайт $site
                    $q = "SELECT * FROM sayty WHERE url='" . $site . "'";
                    $query = $db->Execute($q);
                    $res = $query->FetchRow();

                    $cur_dt = time();

                    if ($res) {
                        $uid = $res['uid'];
                        $sid = $res['id'];
                    }

                    if ($birja == "gogetlinks") {
                        $sistema = "https://gogetlinks.net/";
                        $url = iconv("cp1251", "utf8", $data[1]);
                        $ankor = iconv("cp1251", "utf8", $data[2]);
                        $keywords = iconv("cp1251", "utf8", $data[8]);

                        $q = "SELECT * FROM zadaniya WHERE url='" . $url . "' AND sid=" . $sid;

                        $zad_exist = $db->Execute($q);
                        $zexist = $zad_exist->FetchRow();
                        if (!$zexist) {
                            $db->Execute("insert into zadaniya(uid, sid, sistema, ankor, url, keywords, date) values(" . $uid . ", " . $sid . ", '" . $sistema . "', '" . $ankor . "', '" . $url . "', '" . $keywords . "', '" . $cur_dt . "')");
                        }
                    } else if ($birja == "getgoodlinks") {
                        $sistema = "http://getgoodlinks.ru";
                        $url = iconv("cp1251", "utf8", $data[1]);
                        $ankor = iconv("cp1251", "utf8", $data[2]);

                        $zad_exist = $db->Execute("SELECT * FROM zadaniya WHERE url='" . $url . "' AND sid=" . $sid);
                        $zexist = $zad_exist->FetchRow();
                        if (!$zexist) {
                            $db->Execute("insert into zadaniya(uid, sid, sistema, ankor, url, date) values(" . $uid . ", " . $sid . ", '" . $sistema . "', '" . $ankor . "', '" . $url . "', '" . $cur_dt . "')");
                        }
                    }
                }

                $row++;
            }
            fclose($handle);

            $content = "<p>Файл успешно обработан!</p>";
        }

        return $content;
    }

    function tickets($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/tickets_view.tpl');
        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : null;
        $uid = $_SESSION['admin']["id"];
        $limit = 25;
        $offset = 1;
        if (isset($_GET['offset']) && !empty($_GET['offset'])) {
            $offset = (int) $_GET['offset'];
        }
        $condition = $for_pegination = "";
        $title_page = "Все тикеты";
        if (!empty($type)) {
            $condition = " a.type='$type' AND ";
            $title_page = "Тикеты для '$type'";
        }
        $content = str_replace('[title_page]', $title_page, $content);
        $content = str_replace('[type]', "&type=$type", $content);
        $admins_managers = array();
        $admins_manager = $db->Execute("SELECT id FROM admins WHERE type = 'admin' OR type = 'manager'");
        while ($user = $admins_manager->FetchRow()) {
            $admins_managers[] = $user['id'];
        }
        $admins_managers = "(" . implode(",", $admins_managers) . ")";
        if ($type != "manager") {
            $all = $db->Execute("SELECT t.id FROM tickets t LEFT JOIN admins a ON IF (t.uid IN $admins_managers, a.id=t.to_uid, a.id=t.uid) WHERE $condition a.id != 0 ORDER BY t.id DESC");
            $query = $db->Execute("SELECT t.* FROM tickets t LEFT JOIN admins a ON IF (t.uid IN $admins_managers, a.id=t.to_uid, a.id=t.uid) WHERE $condition a.id != 0 ORDER BY t.status DESC, t.id DESC LIMIT " . ($offset - 1) * $limit . "," . $limit);
        } else {
            $all = $db->Execute("SELECT t.id FROM tickets t LEFT JOIN admins a ON (t.uid IN $admins_managers AND t.to_uid IN $admins_managers) WHERE $condition a.id != 0 ORDER BY t.id DESC");
            $query = $db->Execute("SELECT t.* FROM tickets t LEFT JOIN admins a ON (t.uid IN $admins_managers AND t.to_uid IN $admins_managers) WHERE $condition a.id != 0 ORDER BY t.status DESC, t.id DESC LIMIT " . ($offset - 1) * $limit . "," . $limit);
        }
        $pegination = "";
        $tickets_all = $all->NumRows();
        if ($tickets_all > $limit) {
            $pegination = '<div style="float:right">';
            if ($offset == 1) {
                $pegination .= '<div style="float:left">Пред.</div>';
            } else {
                $pegination .= "<div style='float:left'><a href='?module=admins&action=ticket&type=$type&offset=" . ($offset - 1) . "'>Пред.</a></div>";
            }
            $pegination .= '<div style="float:left">&nbsp [ стр.&nbsp</div>';
            $pegination .= '<div class="select" style="width:50px;float:left;margin-top:-7px"><select name="pagerMenu" onchange="location=\'?module=admins&action=ticket&type=' . $type . '&offset=\'+this.options[this.selectedIndex].value+\'\'" ;="">';

            $count_pegination = ceil($tickets_all / $limit);
            for ($i = 0; $i < $count_pegination; $i++) {
                if ($i + 1 == $offset) {
                    $pegination .= '<option value="' . ($i + 1) . '" selected="selected">' . ($i + 1) . '</option>';
                } else {
                    $pegination .= '<option value="' . ($i + 1) . '">' . ($i + 1) . '</option>';
                }
            }
            $pegination .= '</select></div>';
            $pegination .= '&nbsp из ' . $count_pegination . ' ] &nbsp';
            if ($query->NumRows() < $limit) {
                $pegination .= "След.";
            } else {
                $pegination .= "<a href='?module=admins&action=ticket&type=$type&offset=" . ($offset + 1) . "'>След.</a>";
            }
            $pegination .= '</div>';
        }

        $ticket = "";
        if (!empty($query) && $query->NumRows() != 0) {
            while ($resw = $query->FetchRow()) {
                $ticket .= file_get_contents(PATH . 'modules/admins/tmp/admin/ticket_one.tpl');
                $ticket = str_replace('[site]', $resw['site'], $ticket);
                $ticket = str_replace('[subject]', $resw['subject'], $ticket);
                $ticket = str_replace('[q_theme]', $resw['q_theme'], $ticket);
                $ticket = str_replace('[tdate]', $resw['date'], $ticket);
                $ticket = str_replace('[tid]', $resw['id'], $ticket);
                $ticket = str_replace('[module]', 'admins', $ticket);

                //0 - закрыто; 1-не прочитан; 2-прочитан; 3-дан ответ;
                if ($resw['status'] == 0) {
                    $ticket = str_replace('[status]', "Тема закрыта", $ticket);
                    $ticket = str_replace('[status_ico]', "closed", $ticket);
                }
                if ($resw['status'] == 1) {
                    $ticket = str_replace('[status]', "Не рассмотрено", $ticket);
                    $ticket = str_replace('[status_ico]', "processed", $ticket);
                }
                if ($resw['status'] == 2) {
                    $ticket = str_replace('[status]', "Рассматривается", $ticket);
                    $ticket = str_replace('[status_ico]', "in-progress", $ticket);
                }
                if ($resw['status'] == 3) {
                    $ticket = str_replace('[status]', "Дан ответ", $ticket);
                    $ticket = str_replace('[status_ico]', "answered", $ticket);
                }
            }
        } else {
            $ticket = "<tr><td colspan='4'>Нет ни одного тикета</td></tr>";
        }

        $content = str_replace('[tickets]', $ticket, $content);
        $content = str_replace('[pegination]', $pegination, $content);

        return $content;
    }

    function ticket_add($db) {
        if (!@$_SESSION['admin']['id'] && !@$_SESSION['manager']['id']) {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/no-rights.tpl');
            $content = str_replace('[url]', $_SERVER["HTTP_REFERER"], $content);
            echo $content;
            exit();
        }
        if (isset($_SESSION['admin']['id']) && isset($_SESSION['manager']['id'])) {
            switch ($_SERVER["PHP_SELF"]) {
                case "/admin.php" : $uid = $_SESSION['admin']['id'];
                    break;
                case "/management.php" : $uid = $_SESSION['manager']['id'];
                    break;
                default : $uid = $_SESSION['admin']['id'];
            }
        } else {
            $uid = (int) isset($_SESSION['admin']['id']) ? $_SESSION['admin']['id'] : $_SESSION['manager']['id'];
        }

        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/ticket_create.tpl');
        $type = isset($_REQUEST['type']) ? "type='" . $_REQUEST['type'] . "' AND " : "";
        $to_user = (int) (isset($_REQUEST['uid'])) ? $_REQUEST['uid'] : NULL;
        $title_page = "";
        if (!empty($type)) {
            switch ($_REQUEST['type']) {
                case "user" :
                    $title_page = "для Пользователя";
                    break;
                case "moder" :
                    $title_page = "для Модератора";
                    break;
                case "copywriter" :
                    $title_page = "для Копирайтера";
                    break;
                case "admin" :
                    $title_page = "для Администрации";
                    break;
                case "manager" :
                    $title_page = "для Менеджера";
                    break;
                default : $type = "";
            }
        }
        $content = str_replace('[title_page]', $title_page, $content);

        if (isset($_POST) && !empty($_POST)) {
            $to = $_REQUEST['ticket_to'];
            $subject = $_REQUEST['subject'];
            $site = $_REQUEST['site'];
            $theme = $_REQUEST['theme'];
            $msg = substr(nl2br(htmlspecialchars(addslashes(trim($_REQUEST['msg'])))), 0, 1000);
            $cdate = date("Y-m-d");
            $zid = (intval($_REQUEST['tid']) > 0 ? $_REQUEST['tid'] : 0);

            $user = $db->Execute("SELECT * FROM admins WHERE id=$to")->FetchRow();
            $db->Execute("INSERT INTO tickets (uid, subject, q_theme, msg, date, status, site, tid, to_uid) VALUES ($uid, '$subject', '$theme', '$msg', '$cdate', 1, '$site', $zid, $to)");
            $lastId = $db->Insert_ID();

            // SEND Mails from Admins AND User
            //$this->_postman->admin->ticketAdd();
            if ($user["mail_period"] > 0) {
                switch ($user["type"]) {
                    case "user":
                        $this->_postman->user->ticketAdd($user['email'], $user['login'], $lastId);
                        break;
                    case "copywriter":
                        $this->_postman->copywriter->ticketAdd($user['email'], $user['login'], $lastId);
                        break;
                }
            }
            header("Location: ?module=admins&action=ticket");
            exit();
        }

        $res = $db->Execute("select * from admins where id=$uid")->FetchRow();
        $content = str_replace('[login]', $res['login'], $content);
        $content = str_replace('[uid]', $uid, $content);

        $query = $db->Execute("SELECT * FROM admins where $type id<>$uid ORDER BY login, id DESC");

        $ticket_subjects = $db->Execute("SELECT * FROM Message2008")->FetchRow();
        $content = str_replace('[ticket_subjects]', $ticket_subjects['Name'], $content);
        $content = str_replace('[site]', "", $content);
        $content = str_replace('[subject]', "", $content);

        $ticket = "";
        $ticket_to = "";
        while ($resw = $query->FetchRow()) {
            if (!empty($to_user) && $to_user == $resw['id'])
                $ticket_to .= "<option value='" . $resw['id'] . "' selected>" . $resw['login'] . "</option>";
            else
                $ticket_to .= "<option value='" . $resw['id'] . "'>" . $resw['login'] . "</option>";
        }
        $content = str_replace('[ticket_to]', $ticket_to, $content);

        return $content;
    }

    function ticket_edit($db) {

        $send = $_REQUEST['send'];
        $id = (int) $_REQUEST['tid'];
        $uid = (int) $_SESSION['user']['id'];
        $query2 = $db->Execute("select * from tickets where id=$id");
        $res = $query2->FetchRow();

        if (!$send) {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/ticket_edit.tpl');
            foreach ($res as $k => $v) {
                if ($k == "q_theme") {
                    $content = str_replace("[$v]", "selected", $content);
                }
                $content = str_replace("[$k]", $v, $content);
                $content = str_replace("[tid]", $id, $content);
            }
        } else {
            $subject = $_REQUEST['subject'];
            $site = $_REQUEST['site'];
            $theme = $_REQUEST['theme'];
            $msg = $_REQUEST['msg'];

            $db->Execute("UPDATE tickets SET subject='$subject', q_theme='$theme', msg='$msg', site='$site' WHERE id=$id");

            $alert = 'Тикет успешно отредактирован.';
            $url = "?module=user&action=ticket";

            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
            $content = str_replace('[alert]', $alert, $content);
            $content = str_replace('[url]', $url, $content);
        }
        return $content;
    }

    function ticket_view($db) {
        if (!@$_SESSION['admin']['id']) {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/no-rights.tpl');
            $content = str_replace('[alert]', 'Данное действие Вам недоступно', $content);
            $content = str_replace('[url]', $_SERVER["HTTP_REFERER"], $content);
            echo $content;
            exit;
        }
        $uid = (int) $_SESSION['admin']['id'];
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/ticket_full_view.tpl');
        $content = str_replace('[uid]', $uid, $content);
        $admin_and_manager = false;
        $administrations = array();
        $admins = $db->Execute("SELECT * FROM admins WHERE type='admin' OR type='manager'")->GetAll();
        foreach ($admins as $user) {
            $administrations[$user["id"]] = $user["id"];
        }

        $tid = (int) $_REQUEST['tid'];
        $res = $db->Execute("SELECT * FROM tickets WHERE id=$tid")->FetchRow();
        if ($res['status'] == 1 && ($res['to_uid'] == 0 || $res['to_uid'] == $uid)) {
            $db->Execute("UPDATE tickets SET status=2 WHERE id=$tid");
        }

        if ($res['to_uid'] > 0 && $res['to_uid'] != $uid) {
            $uinfo = $db->Execute("SELECT * FROM admins WHERE id=" . $res['to_uid'])->FetchRow();
        } else {
            $uinfo = $db->Execute("SELECT * FROM admins WHERE id=" . $res['uid'])->FetchRow();
        }

        $content = str_replace('[assigned]', "пользователь: " . $uinfo["login"], $content);

        $view = file_get_contents(PATH . 'modules/admins/tmp/admin/ticket_chat_one.tpl');
        $view = str_replace('[msg]', $res['msg'], $view);
        $view = str_replace('[cdate]', $res['date'], $view);
        if (array_search($res['uid'], $administrations) && array_search($res['to_uid'], $administrations)) {
            $admin_and_manager = true;
        }

        if (array_search($res['uid'], $administrations) && ($admin_and_manager == false || $admin_and_manager == true && $res['uid'] == $uid)) {
            $view = str_replace('[from_class]', "support", $view);
            $view = str_replace('[from]', "Администрация", $view);
        } else {
            $view = str_replace('[from_class]', "you", $view);
            $view = str_replace('[from]', $uinfo['login'] . "<br>" . $res['site'], $view);
        }


        $answers = $db->Execute("SELECT * FROM answers WHERE tid=$tid");
        while ($resw = $answers->FetchRow()) {
            $view .= file_get_contents(PATH . 'modules/admins/tmp/admin/ticket_chat_one.tpl');

            $view = str_replace('[msg]', $resw['msg'], $view);
            $view = str_replace('[cdate]', $resw['date'], $view);
            if (array_search($resw['uid'], $administrations) && ($admin_and_manager == false || $admin_and_manager == true && $resw['uid'] == $uid)) {
                $view = str_replace('[from]', "Администрация", $view);
                $view = str_replace('[from_class]', "support", $view);
            } else {
                $view = str_replace('[from]', $uinfo['login'] . "<br>" . $res['site'], $view);
                $view = str_replace('[from_class]', "you", $view);
            }
        }


        if ($res['tid'] > 0) {
            $zid = $res['tid'];
            $zinfo = $db->Execute("SELECT * FROM zadaniya WHERE id=$zid")->FetchRow();
            if (!empty($zinfo)) {
                $sid = $zinfo['sid'];
                $sinfo = $db->Execute("SELECT * FROM sayty WHERE id=$sid")->FetchRow();
                $subj = "<a href='?module=admins&action=zadaniya&uid=" . $sinfo['uid'] . "&sid=" . $sid . "&action2=edit&id=" . $zid . "' target='_blank'>" . $res['subject'] . "</a>";
            }
        } else {
            $subj = $res['subject'];
        }

        $content = str_replace('[chat]', $view, $content);
        $content = str_replace('[subject]', $subj, $content);
        $content = str_replace('[tid]', $tid, $content);

        return $content;
    }

    function ticket_answer($db) {
        $uid = (int) (@$_SESSION['admin']['id'] ? $_SESSION['admin']['id'] : $_SESSION['manager']['id']);
        $tid = (int) $_REQUEST['tid'];
        $msg = substr(nl2br(htmlspecialchars(addslashes(trim($_REQUEST['msg'])))), 0, 2000);
        $adate = date("Y-m-d H:i:s");
        if (!empty($uid)) {
            $db->Execute("INSERT INTO answers (uid, tid, msg, date) VALUES ($uid, $tid, '$msg', '$adate')");
            $db->Execute("UPDATE tickets SET status=3 WHERE id=$tid");

            $res = $db->Execute("SELECT * FROM tickets WHERE id=$tid")->FetchRow();
            $client = $db->Execute("SELECT * FROM admins WHERE id=" . $res['uid'])->FetchRow();
            if ($client["type"] == "admin") {
                $client = $db->Execute("SELECT * FROM admins WHERE id=" . $res['to_uid'])->FetchRow();
            }
            if ($client["mail_period"] > 0) {
                //echo $client["type"];die();
                switch ($client["type"]) {
                    case "user":
                        $result = $this->_postman->user->ticketAnswer($client['email'], $client['login'], $tid);
                        break;
                    case "copywriter":
                        $this->_postman->copywriter->ticketAnswer($client['email'], $client['login'], $tid);
                        break;
                }
            }
            header("Location: ?module=admins&action=ticket&action2=view&tid=$tid");
            exit();
        }
        return $content;
    }

    function ticket_close($db) {
        $tid = (int) $_REQUEST['tid'];
        $db->Execute("UPDATE tickets SET status=0 WHERE id=$tid");
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
        $content = str_replace('[url]', "?module=admins&action=ticket", $content);
        return $content;
    }

    function getUserBalans($uid, $db, $nocur = 0) {
        $balans = $db->Execute("SELECT SUM(price) as total FROM orders WHERE uid=$uid AND status=1")->FetchRow();
        if (!is_null($balans['total'])) {
            if (!$balans['total'])
                $balans['total'] = 0;
        }
        else {
            $balans['total'] = 0;
        }

        //Подсчитываем количество выполненных задач
        $compl_tasks = $db->Execute("SELECT * FROM completed_tasks WHERE uid=$uid");
        $credit = 0;
        while ($row = $compl_tasks->FetchRow()) {
            $credit += $row['price'];
        }
        $balans['total'] -= $credit;
        if (!$nocur)
            $balans['total'] .= "р.";

        return $balans['total'];
    }

    function getAllUserBalans($db) {
        $user_orders = array();
        $balans = $db->Execute("SELECT uid, SUM(price) as total FROM orders WHERE status=1 GROUP BY uid ORDER BY uid ASC")->GetAll();
        foreach ($balans as $user) {
            $user_orders[$user["uid"]] = $user["total"];
        }

        //Вычитаем деньги за выполненные задачи
        $compl_tasks = $db->Execute("SELECT uid, SUM(price) as total FROM completed_tasks GROUP BY uid ORDER BY uid ASC")->GetAll();
        foreach ($compl_tasks as $task) {
            if (!isset($user_orders[$task["uid"]])) {
                $user_orders[$task["uid"]] = 0;
            }
            $user_orders[$task["uid"]] -= $task["total"];
        }

        return $user_orders;
    }

    function getAllLeftMoney($db) {
        $total_price = array();
        $balans = $db->Execute("SELECT uid, SUM(price) as total FROM orders WHERE status=1 GROUP BY uid ORDER BY uid ASC")->GetAll();
        foreach ($balans as $user) {
            $total_price[$user["uid"]] = $user['total'];
        }
        return $total_price;
    }

    function getUserBalansOld($uid, $db, $nocur = 0, $return_freeze = 0) {
        $balans = $db->Execute("SELECT SUM(price) as total FROM orders WHERE uid=$uid AND status=1")->FetchRow();
        if (!is_null($balans['total'])) {
            if (!$balans['total'])
                $balans['total'] = 0;
        }
        else {
            $balans['total'] = 0;
        }

        $sites = $db->Execute("SELECT * FROM sayty WHERE uid=$uid");
        $site_price = array();
        while ($row = $sites->FetchRow()) {
            $site_price[$row['id']] = $row['price'];
        }

        $completed = $db->Execute("SELECT * FROM completed_tasks WHERE uid=$uid AND status=0");
        $compl = array();
        if ($completed) {
            while ($row = $completed->FetchRow()) {
                $compl[] = $row['zid'];
            }
        }

        $freezed = $db->Execute("SELECT * FROM zadaniya WHERE uid=$uid");
        $freez_sum = 0;
        if ($freezed) {
            while ($row = $freezed->FetchRow()) {
                if (($row['dorabotka'] == 1) || ($row['vrabote'] == 1) || ($row['navyklad'] == 1) || ($row['vilojeno'] == 1)) {
                    if ($row['lay_out'] == 1) {
                        $freez_sum += 15;
                    } else {
                        if (!empty($row['price']) && $row['price'] != 0)
                            $freez_sum += $row['price'];
                        else
                            $freez_sum += $site_price[$row['sid']];
                    }
                } elseif ($row['vipolneno'] == 1) {
                    if (in_array($row['id'], $compl)) {
                        if ($row['lay_out'] == 1) {
                            $freez_sum += 15;
                        } else {
                            if (!empty($row['price']) && $row['price'] != 0)
                                $freez_sum += $row['price'];
                            else
                                $freez_sum += $site_price[$row['sid']];
                        }
                    }
                }
            }
        }
        $fz = 0;
        if ($freez_sum > 0) {
            $fz = $freez_sum;
        }

        //Подсчитываем количество выполненных задач
        $compl_tasks = $db->Execute("SELECT * FROM completed_tasks WHERE uid=$uid AND status=1");
        $credit = 0;
        while ($row = $compl_tasks->FetchRow()) {
            $credit += $row['price'];
        }
        $balans['total'] -= $credit;

        if ($return_freeze) {
            return array("balans" => $balans['total'], "freeze" => $fz);
        }

        $balans = $balans['total'] - $fz;

        if (!$nocur)
            $balans .= "р.";

        return $balans;
    }

    function getActiveUsers($db) {
        $admins = $db->Execute("SELECT id FROM admins WHERE active=1 AND type='user'")->GetAll();
        $active_uid = array();
        foreach ($admins as $row) {
            $active_uid[] = $row['id'];
        }
        return $active_uid;
    }

    function allsites($db) {
        $starttime = time();
        $profil = "";
        $profil .= microtime() . "  - START FUNCTION" . "\r\n";
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/sayty_all.tpl');
        $active_uid = "(" . implode(",", $this->getActiveUsers($db)) . ")";
        $profil .= microtime() . "  - AFTER GET ALL ACTIVE USER" . "\r\n";
        $sayty = '';

        $sites = array();
        $sites_model = $db->Execute("SELECT * FROM sayty WHERE uid IN $active_uid GROUP BY id")->GetAll();
        foreach ($sites_model as $site) {
            $sites[$site["id"]] = $site;
        }
        $users_balans = $this->getAllUserBalans($db);
        $profil .= microtime() . "  - AFTER GET ALL USER BALANCE" . "\r\n";

        $neobrabot = $dorabotka = $vilojeno = $vrabote = $navyklad = $neo = array();
        $zadaniya = $db->Execute("SELECT * FROM zadaniya WHERE vipolneno !=1 AND uid IN $active_uid ");
        while ($task = $zadaniya->FetchRow()) {
            if (!isset($navyklad[$task["sid"]])) {
                $navyklad[$task["sid"]] = 0;
            }
            if (!isset($vrabote[$task["sid"]])) {
                $vrabote[$task["sid"]] = 0;
            }
            if (!isset($vilojeno[$task["sid"]])) {
                $vilojeno[$task["sid"]] = 0;
            }
            if (!isset($dorabotka[$task["sid"]])) {
                $dorabotka[$task["sid"]] = 0;
            }
            if (!isset($neobrabot[$task["sid"]])) {
                $neobrabot[$task["sid"]] = 0;
                //$neobrabot[$task["sid"]]["n"] = 0;
            }


            if ($task["navyklad"] == 1) {
                $navyklad[$task["sid"]] += 1;
            }
            if ($task["vrabote"] == 1 && empty($task["dorabotka"]) && empty($task["vipolneno"]) && empty($task["navyklad"]) && empty($task["vilojeno"])) {
                $vrabote[$task["sid"]] += 1;
            }
            if ($task["vilojeno"] == 1) {
                $vilojeno[$task["sid"]] += 1;
            }
            if ($task["dorabotka"] == 1) {
                $dorabotka[$task["sid"]] += 1;
            }
            if (empty($task["vrabote"]) && empty($task["dorabotka"]) && empty($task["vipolneno"]) && empty($task["navyklad"]) && empty($task["vilojeno"])) {
                $neobrabot[$task["sid"]] += 1;
                //$neobrabot[$task["sid"]]["n"] += 1;
            }
        }
        arsort($navyklad);
        $profil .= microtime() . "  - AFTER SELECT zadaniya (AND) SORT all status " . "\r\n";

        foreach ($neobrabot as $key => $value) {
            $neo[$key] = array("n" => ($navyklad[$key] ? $navyklad[$key] : 0), "l" => $value, "id_site" => $key);
        }

        function cmp($a, $b) {
            $orderBy = array('n' => 'desc', 'l' => 'desc');
            $result = 0;
            foreach ($orderBy as $key => $value) {
                if ($a[$key] == $b[$key])
                    continue;
                $result = ($a[$key] < $b[$key]) ? -1 : 1;
                if ($value == 'desc')
                    $result = -$result;
                break;
            }
            return $result;
        }

        usort($neo, 'cmp');
        $profil .= microtime() . "  - AFTER SORTING ARRAY" . "\r\n";
        $n = 0;
        foreach ($neo as $val) {
            if ($val["id_site"] == 0 || !isset($sites[$val["id_site"]])) {
                continue;
            }
            $site = $sites[$val["id_site"]];
            $balans = $users_balans[$site['uid']];

            if (($balans < 45) && $site['uid'] != 20 && ($navyklad[$site['id']] == 0 && $vrabote[$site['id']] == 0 && $vilojeno[$site['id']] == 0 && $dorabotka[$site['id']] == 0 && $neobrabot[$site['id']] == 0)) {
                continue;
            }

            $sayty .= file_get_contents(PATH . 'modules/admins/tmp/admin/sayty_all_one.tpl');
            $class = ($n % 2 == 0) ? "style='background:#f7f7f7'" : "style='background:white'";

            $sayty = str_replace('[balans]', $balans, $sayty);
            $sayty = str_replace('[url]', $site['url'], $sayty);
            $sayty = str_replace('[id]', $site['id'], $sayty);
            $sayty = str_replace('[uid]', $site['uid'], $sayty);
            $sayty = str_replace('[class]', $class, $sayty);

            $class = ($n % 2 == 0) ? "style='display: none;background:#f7f7f7'" : "style='display: none;background:white'";
            $sayty = str_replace('[class_birgs]', $class, $sayty);
            $n++;
            $sid = $site['id'];

            $sayty = str_replace('[z1]', $vrabote[$site['id']] ? $vrabote[$site['id']] : 0, $sayty);
            $sayty = str_replace('[z2]', $dorabotka[$site['id']] ? $dorabotka[$site['id']] : 0, $sayty);
            $sayty = str_replace('[z4]', $neobrabot[$site['id']] ? $neobrabot[$site['id']] : 0, $sayty);
            $sayty = str_replace('[z5]', $vilojeno[$site['id']] ? $vilojeno[$site['id']] : 0, $sayty);
            $sayty = str_replace('[z7]', $navyklad[$site['id']] ? $navyklad[$site['id']] : 0, $sayty);

            if (!@$_SESSION['admin']["id"]) {
                $sayty = str_replace('[rights]', "style='display:none'", $sayty);
            } else {
                $sayty = str_replace('[rights]', "", $sayty);
            }

            $birj = "";
            $birjs_table = file_get_contents(PATH . 'modules/admins/tmp/admin/birj_table_top.tpl');
            $birjs_table = str_replace("[th_comment]", '<th width="132px" style="max-width: 210px;">Комментарий</th><th width="28px"></th>', $birjs_table);
            $birjs_info = $db->Execute("SELECT b.*, br.url FROM birjs b LEFT JOIN birgi br ON br.id=b.birj WHERE b.uid=" . $site['uid']);
            $birjs_count = $birjs_info->NumRows();
            $profil .= microtime() . "  - QUERY 2 && 3  - GET birjs FOR USER - " . $site['uid'] . "\r\n";
            $i = 1;
            while ($birjs = $birjs_info->FetchRow()) {
                $class = ($i % 2 == 0) ? "style='background:#FFF8DC'" : "style='background:white'";
                $birj .= file_get_contents(PATH . 'modules/admins/tmp/admin/birja_one.tpl');

                $birj = str_replace('[class]', $class, $birj);
                $birj = str_replace('[loginb]', $birjs['login'], $birj);
                $birj = str_replace('[passb]', $birjs['pass'], $birj);
                $birj = str_replace('[birja]', $birjs['url'], $birj);
                $comment = '<td class="row_tt comment_viklad" style="font-size: 11px;" rowspan="' . $birjs_count . '">';
                $edit_comment = '<td class="edit row_tt" rowspan="' . $birjs_count . '">
                                    <a href="?module=admins&action=birj&action2=edit_comment&id=' . $site['uid'] . '" class="ico"></a>
                                </td>';
                if ($i == 1) {
                    $user = $db->Execute("select comment_viklad from admins where id=" . $site['uid'])->FetchRow();
                    $birj = str_replace('[comment_viklad]', $comment . $user['comment_viklad'] . "</td>" . $edit_comment, $birj);
                } else {
                    $birj = str_replace('[comment_viklad]', "", $birj);
                }
                $i++;
            }
            $profil .= microtime() . "  - END CONTRUCT BIRGI" . "\r\n";
            if ($birj == "") {
                $birj = "<tr style='background:#FFF8DC'><td colspan='5'>У пользователя не добавлено бирж</td></tr>";
            }
            $birjs_table = str_replace('[birjs]', $birj, $birjs_table); //die();
            $sayty = str_replace('[birjs]', $birjs_table, $sayty);
        }
        if ($sayty)
            $sayty = str_replace('[sayty]', $sayty, file_get_contents(PATH . 'modules/admins/tmp/admin/sayty_all_top.tpl'));
        else
            $sayty = file_get_contents(PATH . 'modules/admins/tmp/admin/no.tpl');

        $content = str_replace('[sayty]', $sayty, $content);
        $content = str_replace('[uid]', @$uid, $content);
        $profil .= microtime() . "  - END FUNCTION" . "\r\n";
        $endtime = time() - $starttime;
        if ($endtime > 7) {
            $profil .= "ALL TIME - " . $endtime;
            $file = 'temp_file/all_site/' . $endtime . '-(' . time() . ').txt';
            file_put_contents($file, $profil);
        }
        return $content;
    }

    function checketxt($db) {
        $starttime = time();
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/zadaniya_view_etxt.tpl');

        $admins = $db->Execute("select * from admins where active=1");
        $act_uid = array();
        while ($row = $admins->FetchRow()) {
            $act_uid[] = $row['id'];
        }
        $act_uid = "(" . implode(",", $act_uid) . ")";

        $sayty = $db->Execute("select * from sayty where uid in $act_uid");
        $site_ids = $task_site = array();
        while ($row = $sayty->FetchRow()) {
            $site_ids[] = $row['id'];
            $task_site[$row['id']] = $row['url'];
        }
        $site_ids = "(" . implode(",", $site_ids) . ")";

        $query = $db->Execute("select * from zadaniya where (vipolneno=0 AND etxt=1)  AND (sid in $site_ids) order by date DESC, id");
        $n = 0;
        $zadaniya = "";
        while ($res = $query->FetchRow()) {
            $n++;
            $end_link = strpos($res['url'], "\">");
            $zadaniya .= file_get_contents(PATH . 'modules/admins/tmp/admin/zadaniya_one.tpl');
            $zadaniya = str_replace('[url]', (substr(substr($res['url'], strpos($res['url'], "http")), 0, (($end_link) ? (20) : 30))), $zadaniya);
            $zadaniya = str_replace('[id]', $res['id'], $zadaniya);
            $zadaniya = str_replace('[etxt_id]', $res['task_id'], $zadaniya);
            $zadaniya = str_replace('[date]', date('d.m.Y', $res['date']), $zadaniya);
            $zadaniya = str_replace('[tema]', mb_substr($res['tema'], 0, 40), $zadaniya);
            $zadaniya = str_replace('[uid]', $res['uid'], $zadaniya);
            $zadaniya = str_replace('[sid]', $res['sid'], $zadaniya);

            $etxt_status = "";
            $new_s = "";
            $bg = "";

            if ($res['text']) {
                $etxt_status = "текст";
            }
            $zadaniya = str_replace('[etxt_status]', $etxt_status, $zadaniya);

            if ($new_s == "") {
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
                    $new_s = '';
            }
            $zadaniya = str_replace('[status]', $new_s, $zadaniya);

            if ($bg == "") {
                if ($res['dorabotka'])
                    $bg = '#f6b300';
                else if ($res['vipolneno'])
                    $bg = '#83e24a';
                else if ($res['vrabote'])
                    $bg = '#00baff';
                else if ($res['navyklad'])
                    $bg = '#ffde96';
                else if ($res['vilojeno'])
                    $bg = '#b385bf';
                else
                    $bg = '';

                $bg = 'style="background:' . $bg . '"';
                $zadaniya = str_replace('[bg]', $bg, $zadaniya);
            }
        }

        if ($zadaniya)
            $zadaniya = str_replace('[zadaniya]', $zadaniya, file_get_contents(PATH . 'modules/admins/tmp/admin/zadaniya_top_etxt.tpl'));
        else
            $zadaniya = file_get_contents(PATH . 'modules/admins/tmp/admin/no.tpl');

        $content = str_replace('[zadaniya]', $zadaniya, $content);
        return $content;
    }

    function decode_balans($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/decode_balans.tpl');
        $zadaniya = $pegination = "";
        $limit = 50;
        $offset = 1;
        $uid = intval($_GET['uid']);
        if (isset($_GET['offset']) && !empty($_GET['offset'])) {
            $offset = (int) $_GET['offset'];
        }

        $money = $this->getUserBalans($uid, $db, 1);
        $content = str_replace('[user_balans]', $money . " руб.", $content);

        $tasks = $db->Execute("SELECT * FROM zadaniya WHERE uid=$uid ORDER BY id DESC LIMIT " . ($offset - 1) * $limit . "," . $limit);
        $all = $db->Execute("SELECT * FROM zadaniya WHERE uid=$uid ORDER BY id DESC");

        $pegination = '<div style="float:right">';
        if ($offset == 1) {
            $pegination .= '<div style="float:left">Пред.</div>';
        } else {
            $pegination .= "<div style='float:left'><a href='?module=admins&action=decode_balans&uid=$uid" . "&offset=" . ($offset - 1) . "'>Пред.</a></div>";
        }
        $pegination .= '<div style="float:left">&nbsp [ стр.&nbsp</div>';
        $pegination .= '<div class="select" style="width:50px;float:left;margin-top:-7px"><select name="pagerMenu" onchange="location=\'?module=admins&action=decode_balans&uid=' . $uid . '&offset=\'+this.options[this.selectedIndex].value+\'\'" ;="">';

        $all_zadanya = $all->NumRows();
        $count_pegination = ceil($all_zadanya / $limit);
        for ($i = 1; $i < $count_pegination + 1; $i++) {
            if ($i == $offset) {
                $pegination .= '<option value="' . ($i) . '" selected="selected">' . ($i) . '</option>';
            } else {
                $pegination .= '<option value="' . ($i) . '">' . ($i) . '</option>';
            }
        }
        $pegination .= '</select></div>';
        $pegination .= '&nbsp из ' . $count_pegination . ' ] &nbsp';
        if ($tasks->NumRows() < $limit) {
            $pegination .= "След.";
        } else {
            $pegination .= "<a href='?module=admins&action=decode_balans&uid=$uid" . "&offset=" . ($offset + 1) . "'>След.</a>";
        }
        $pegination .= '</div><br /><br />';
        if ($count_pegination == 1)
            $pegination = "";

        while ($res = $tasks->FetchRow()) {
            if (($res['dorabotka'] == 0) && ($res['vrabote'] == 0) && ($res['navyklad'] == 0) && ($res['vilojeno'] == 0) && ($res['vipolneno'] == 0)) {
                continue;
            }
            $url = addslashes(mb_substr(str_replace('<a href="', "", str_replace('">', "", str_replace("&lt;a href=&quot;", "", $res['url']))), 0, 25));
            $zadaniya .= file_get_contents(PATH . 'modules/admins/tmp/admin/decode_balans_one.tpl');
            $zadaniya = str_replace('[url]', $url, $zadaniya);
            $zadaniya = str_replace('[zid]', $res['id'], $zadaniya);
            $zadaniya = str_replace('[sid]', $res['sid'], $zadaniya);
            $zadaniya = str_replace('[uid]', $res['uid'], $zadaniya);

            $compl = $db->Execute("SELECT * FROM completed_tasks WHERE zid=" . $res['id'])->FetchRow();
            if (isset($compl['price']) && !empty($compl['price']))
                $zad_price = $compl['price'];
            else
                $zad_price = 0;

            $zadaniya = str_replace('[price]', $zad_price, $zadaniya);
            $zadaniya = str_replace('[id]', $res['id'], $zadaniya);
            $zadaniya = str_replace('[date]', date('d.m.Y', $res['date']), $zadaniya);
            $zadaniya = str_replace('[tema]', $res['tema'], $zadaniya);
            $new_s = "";
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

            $zadaniya = str_replace('[status]', $new_s, $zadaniya);
        }
        $content = str_replace('[pegination]', $pegination, $content);
        $content = str_replace('[zadaniya]', $zadaniya, $content);

        return $content;
    }

    function zadaniya_dubl($db) {
        $id = (int) @$_REQUEST['zid'];
        $sistema = @$_REQUEST['sistema'];
        $etxt = @$_REQUEST['etxt'];
        $ankor = (@$_REQUEST['ankor']);
        $ankor2 = (@$_REQUEST['ankor2']);
        $ankor3 = (@$_REQUEST['ankor3']);
        $ankor4 = (@$_REQUEST['ankor4']);
        $ankor5 = (@$_REQUEST['ankor5']);
        $url = (@$_REQUEST['url']);
        $url2 = (@$_REQUEST['url2']);
        $url3 = (@$_REQUEST['url3']);
        $url4 = (@$_REQUEST['url4']);
        $url5 = (@$_REQUEST['url5']);
        $keywords = @$_REQUEST['keywords'];
        $tema = @$_REQUEST['tema'];
        $text = @$_REQUEST['text'];
        $url_statyi = @$_REQUEST['url_statyi'];
        $url_pic = @$_REQUEST['url_pic'];
        $price = @$_REQUEST['price'];
        $comments = mysql_real_escape_string(@$_REQUEST['comments']);
        $admin_comments = @$_REQUEST['admin_comments'];
        $b_id = @$_REQUEST['b_id'];
        $overwrite = @$_REQUEST['overwrite'];

        $task_old = $db->Execute("SELECT * FROM zadaniya WHERE id=$id")->FetchRow();
        $uid = $task_old['uid'];

        $user = $db->Execute("select * from admins where id=$uid")->FetchRow();
        $sid = $task_old['sid'];
        $task_id = $task_old['task_id'];
        if (@$_REQUEST['morework'] && $task_id) {
            $text = $_REQUEST['morework_comment'];
            $pass = ETXT_PASS;
            $query_sign = "method=tasks.cancelTasktoken=29aa0eec2c77dd6d06e23b3faaef9eed";
            $sign = md5($query_sign . md5($pass . 'api-pass'));

            $params = array('id' => array($task_id), 'text' => $text);
            $query_p = http_build_query($params);
            $url_etxt = "https://www.etxt.ru/api/json/?method=tasks.cancelTask&token=29aa0eec2c77dd6d06e23b3faaef9eed&sign=" . $sign;
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url_etxt);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $query_p);
            curl_exec($curl);
            curl_close($curl);
        } else if ((@$_REQUEST['morework'] == 0) && $task_id) {
            if (isset($_REQUEST['morework'])) {
                $pass = ETXT_PASS;
                $query_sign = "method=tasks.paidTasktoken=29aa0eec2c77dd6d06e23b3faaef9eed";
                $sign = md5($query_sign . md5($pass . 'api-pass'));
                $params = array('id' => array($task_id));
                $query_p = http_build_query($params);
                $url_etxt = "https://www.etxt.ru/api/json/?method=tasks.paidTask&token=29aa0eec2c77dd6d06e23b3faaef9eed&sign=" . $sign;
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url_etxt);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $query_p);
                curl_exec($curl);
                curl_close($curl);
            }
        }

        $site = $db->Execute("SELECT * FROM sayty WHERE id=" . $sid)->FetchRow();
        $moder = $db->Execute("SELECT * FROM admins WHERE id=" . $site['moder_id'])->FetchRow();

        $task_status = isset($_REQUEST['task_status']) ? $_REQUEST['task_status'] : null;
        $vipolneno = $dorabotka = $vrabote = $navyklad = $vilojeno = 0;
        switch ($task_status) {
            case "vipolneno":
                $vipolneno = 1;
                break;
            case "dorabotka":
                $dorabotka = 1;
                break;
            case "vrabote":
                $vrabote = 1;
                break;
            case "navyklad":
                $navyklad = 1;
                break;
            case "vilojeno":
                $vilojeno = 1;
                break;
        }

        if (($navyklad == 1 || $dorabotka == 1 || $vilojeno == 1 || $vipolneno == 1 || $vrabote == 1)) {
            $price = 0;
            if (@$_REQUEST['lay_out'] == 1 || @$_REQUEST['lay_out'] == "1") {
                $price = 15;
            } elseif ($sistema == "http://miralinks.ru/" || $sistema == "http://pr.sape.ru/" || $sistema == "http://getgoodlinks.ru/" || $sistema == "https://gogetlinks.net/") {
                switch ($site['cena']) {
                    case 20:$price = 60;
                        break;
                    case 30:$price = 76;
                        break;
                    case 45:$price = 111;
                        break;
                    default:$price = 60;
                        break;
                }
            } else {
                $price = $site['price'];
            }
            if ($user["new_user"] == 1 && (!isset($_REQUEST['lay_out']) || @$_REQUEST['lay_out'] == 0)) {
                //увеличение цен для новых пользователей на 30%
                $price = (int) $price + 17;
            }
        }

        if ($navyklad == 1) {
            $this->_postman->moderator->taskStatusNavyklad($moder, $site, $task_old, null);
        }

        if ($dorabotka == 1) {
            $this->_postman->moderator->taskStatusDorabotka($moder, $site, $task_old, $comments);
        }

        if ($vilojeno == 1) {
            $this->_postman->admin->taskStatusVilojeno($site, $task_old);
        }

        $q = "update zadaniya set b_id='$b_id', dorabotka='$dorabotka', etxt='$etxt', vipolneno='$vipolneno', vrabote='$vrabote', navyklad='$navyklad', vilojeno='$vilojeno', url_statyi='$url_statyi', text='$text', tema='$tema', sistema='$sistema', ankor='$ankor',ankor2='$ankor2',ankor3='$ankor3',ankor4='$ankor4', ankor5='$ankor5',url='$url',url2='$url2',url3='$url3',url4='$url4',url5='$url5', keywords='$keywords', price='$price', url_pic='$url_pic', comments='$comments', admin_comments='$admin_comments', overwrite='$overwrite' where id=$id";
        $db->Execute($q);
        $date = time();
        $copy = $id;

        $db->Execute("INSERT INTO zadaniya (etxt,uid,sid,sistema,ankor,ankor2,ankor3,ankor4,ankor5,url,url2,url3,url4,url5,keywords,tema,text,url_statyi,date,price,url_pic,comments,admin_comments,copy) VALUES 
						   (0,'$uid','$sid','$sistema','$ankor','$ankor2','$ankor3','$ankor4','$ankor5','$url','$url2','$url3','$url4','$url5','$keywords','$tema','$text','$url_statyi','$date','$price','$url_pic','$comments','$admin_comments','$copy')");

        $lastId = $db->Insert_ID();
        $db->Execute("INSERT INTO completed_tasks (uid, zid, date, price, status) VALUES ('$uid', '$lastId', '" . date("Y-m-d H:i:s") . "', '$price',1)");
        header("Location: ?module=admins&action=zadaniya&uid=" . $uid . "&sid=" . $sid . "&action2=edit&id=" . $lastId);
        exit();
    }

    function output_to_purse($db) {
        $uid = (int) $_GET['uid'];
        $earned = (int) $_GET['summa'];

        $clients_from_partner = $db->Execute("SELECT * FROM partnership WHERE partner_id=$uid");
        $new_cl = array();
        while ($clients = $clients_from_partner->FetchRow()) {
            $new_cl[] = $clients['new_user_id'];
        }

        if ($new_cl)
            $new_cl = "(" . implode(",", $new_cl) . ")";
        else
            $new_cl = "(0)";

        $sites_from_partner = $db->Execute("SELECT * FROM sayty WHERE uid IN $new_cl");
        $sids = array();
        while ($site = $sites_from_partner->FetchRow()) {
            $sids[] = $site['id'];
        }

        if ($sids)
            $sids = "(" . implode(",", $sids) . ")";
        else
            $sids = "(0)";

        $tasks_from_partner = $db->Execute("SELECT * FROM zadaniya WHERE vipolneno = 1 AND sid IN $sids");
        $payed_tasks = array();
        while ($task = $tasks_from_partner->FetchRow()) {
            $payed_tasks[] = $task['id'];
        }
        $task_num = count($payed_tasks);
        $balance = 3 * $task_num;
        $cur_dt = date("Y-m-d H:i:s");

        $partnership_output_money = $db->Execute("SELECT * FROM partnership_output_money WHERE uid = $uid");
        while ($output_money = $partnership_output_money->FetchRow()) {
            if (!empty($output_money['amount']) && $output_money['amount'] != 0) {
                $balance -= $output_money['amount'];
            }
        }
        if (($balance - $earned) >= 0) {
            $db->Execute("INSERT INTO partnership_output_money (uid, amount, date) VALUES ($uid, $earned, '$cur_dt')");
        }

        $url = "?module=admins&action=ticket";
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
        $content = str_replace('[url]', $url, $content);
        return $content;
    }

    function birj_all($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/birj_all.tpl');
        $users = array();
        $admins_model = $db->Execute("SELECT * FROM admins WHERE active=1")->GetAll();
        foreach ($admins_model as $admin) {
            $users[$admin["id"]] = $admin["login"];
        }
        $kluchi = array_keys($users);
        $active_users = implode(",", $kluchi);

        $birj_access = array();
        $birjs_model = $db->Execute("SELECT b.uid, b.login, b.pass, b.active, br.name FROM birjs b LEFT JOIN birgi br ON br.id=b.birj WHERE b.uid IN ($active_users) ORDER BY b.birj, b.uid")->GetAll();
        foreach ($birjs_model as $birj) {
            if (!isset($birj_access[$birj["name"]])) {
                $birj_access[$birj["name"]] = array();
            }
            $birj_access[$birj["name"]][] = $birj;
        }

        $output = "";
        foreach ($birj_access as $name_birj => $birjs) {
            $output .= "<h2>$name_birj</h2><br />";
            $output .= file_get_contents(PATH . 'modules/admins/tmp/admin/birj_table_top.tpl');
            $output = str_replace('Биржа', "Пользователь", $output);
            $birj = "";
            foreach ($birjs as $key => $value) {
                $class = ($key % 2 == 0) ? "style='background:#FFFFF0'" : "style='background:white'";
                $birj .= file_get_contents(PATH . 'modules/admins/tmp/admin/birja_one.tpl');
                if ($value['active'] == 0) {
                    $class = "style='background:#d7d7d7'";
                }
                $birj = str_replace('[class]', $class, $birj);
                $birj = str_replace('[loginb]', $value['login'], $birj);
                $birj = str_replace('[passb]', $value['pass'], $birj);
                $birj = str_replace('[birja]', $users[$value['uid']], $birj);
                $birj = str_replace('[comment_viklad]', "", $birj);
            }
            $output = str_replace('[th_comment]', "", $output);
            $output = str_replace('[birjs]', $birj, $output);
            $output .= "<br /><br />";
        }
        $content = str_replace('[birjs]', $output, $content);
        return $content;
    }

    function editComment($db) { //print_r($_SERVER);
        $id = intval($_REQUEST['id']);
        $send = $_REQUEST['send'];
        $ref = $_REQUEST['REFERER'];

        $query = $db->Execute("select * from admins where id=$id");
        $res = $query->FetchRow();

        $content = '';
        if (!$send) {
            $content .= file_get_contents(PATH . 'modules/admins/tmp/admin/comment_edit.tpl');
            $content = str_replace('[comment_viklad]', $res['comment_viklad'], $content);
            $content = str_replace('[id]', $id, $content);
            $content = str_replace('[HTTP_REFERER]', $_SERVER["HTTP_REFERER"], $content);
        } else {
            $comment = $_REQUEST['comment_viklad'];
            $q = "UPDATE admins SET comment_viklad='$comment' WHERE id=$id";
            $db->Execute($q);

            if (!empty($ref)) {
                header('Location: ' . $ref);
            } else {
                header('Location: ?module=admins');
            }
            exit();
        }


        return $content;
    }

    function balance($db) {
        if ($_SESSION['admin']['type'] != 'admin') {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
            $content = str_replace('[alert]', 'Данное действие Вам недоступно', $content);
            $content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
            echo $content;
            exit;
        }
        $content .= file_get_contents(PATH . 'modules/admins/tmp/admin/add_balance.tpl');
        $send = $_REQUEST['send'];

        if (!$send) {
            $user = "";
            $users = $db->Execute("SELECT * FROM admins WHERE active = 1 ORDER BY login");
            while ($res = $users->FetchRow()) {
                $user .= "<option value='" . $res['id'] . "'>" . $res['login'] . "</option>";
            }
            $content = str_replace('[users]', $user, $content);
        } else {
            $user = intval($_REQUEST['user']);
            $sum = $_REQUEST['sum'];
            $date = date("Y-m-d H:i:s");
            if (!empty($user) && !empty($sum)) {
                $q = "INSERT INTO orders (id, uid, price, date, status) VALUES (NULL, '$user', '$sum', '$date', 1)";
                $db->Execute($q);
            }

            header('Location: ?module=admins');
            exit();
        }

        return $content;
    }

    function createXLSForUser($id = null, $sistema = null, $ankor = null, $url = null, $url_statyi = null, $tema = null, $text = null) {
        require_once 'includes/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("iforget")->setTitle("Text for task:$id");

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Sistema')
                ->setCellValue('B1', 'Ankor1')
                ->setCellValue('C1', 'URL')
                ->setCellValue('D1', 'Tema')
                ->setCellValue('E1', 'Text');

        $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getAlignment()->setWrapText(true);

        $objPHPExcel->getActiveSheet()->getStyle("A1:E2")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("E2")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

        $objPHPExcel->getActiveSheet()->getStyle("A1:E1")->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()->setRGB('b9cde4');
        $objPHPExcel->getActiveSheet()->getStyle("A1:E1")->getfont()->setBold(true);

        $objPHPExcel->getActiveSheet()->getStyle("A1:A2")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("B1:B2")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("C1:C2")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("D1:D2")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("E1:E2")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("A1:E1")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("A2:E2")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A2', $sistema)
                ->setCellValue('B2', $ankor)
                ->setCellValue('C2', $url)
                ->setCellValue('D2', $tema)
                ->setCellValue('E2', $text);


        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $file_name = __DIR__ . '/../../files/' . $id . '-' . time() . '.xlsx';
        $objWriter->save($file_name);
        return $file_name;
    }

    function moders($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/moders.tpl');
        $filter = @$_REQUEST['filter'];

        if (isset($_REQUEST['error'])) {
            $content = str_replace('[error]', $_REQUEST['error'], $content);
        } else {
            $content = str_replace('[error]', "", $content);
        }

        $date_time_array = getdate(time());
        $day = $date_time_array['mday'];
        $day_week = $date_time_array['wday'] - 1;
        $month = $date_time_array['mon'];
        $year = $date_time_array['year'];

        $condition = "";
        switch ($filter) {
            case "day": $filter = "mon";
                $name_chart = "Статистика <b>за день</b>";
                $time = mktime(0, 0, 0, $month, $day, $year);
                $condition = " AND date >= $time";
                break;
            case "weeks": $filter = "mday";
                $name_chart = "Статистика <b>за неделю</b>";
                $time = mktime(0, 0, 0, $month, $day - $day_week, $year);
                $condition = " AND date >= $time";
                break;
            case "month": $filter = "mday";
                $name_chart = "Статистика <b>за месяц</b>";
                $time = mktime(0, 0, 0, $month, 1, $year);
                $condition = " AND date >= $time";
                break;
            case "all": $filter = "year";
                $name_chart = "Статистика <b>за всё время</b>";
                $condition = "";
                break;
            default : $filter = "";
                $name_chart = "Статистика <b>за всё время</b>";
                $time = mktime(0, 0, 0, $month, $day - $day_week, $year);
                $condition = "";
        }
        $content = str_replace('[name_stat]', $name_chart, $content);

        $moders = $db->Execute("SELECT * FROM admins WHERE type = 'moder' AND active = 1 ORDER BY login ASC");
        $table = "";
        while ($moder = $moders->FetchRow()) {
            $tasks = $db->Execute("SELECT count(id) as num FROM zadaniya WHERE who_posted = " . $moder["id"] . " AND vipolneno = 1" . $condition)->FetchRow();
            //$tasks_other = $db->Execute("SELECT count(id) as num FROM zadaniya WHERE who_posted = " . $moder["id"] . " AND (navyklad = 1 OR vilojeno = 1 OR dorabotka = 1)")->FetchRow();

            $tr = "<tr>";
            $tr .= "<td>" . $moder["login"] . "</td>";
            $tr .= "<td>" . $tasks["num"] . "</td>";
            //$tr .= "<td>" . $tasks_other["num"] . "</td>";
            $tr .= "</tr>";
            $table .= $tr;
        }
        $content = str_replace('[table]', $table, $content);


        return $content;
    }

    function moders_balance($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/moders_balance.tpl');
        $filter = @$_REQUEST['filter'];

        if (isset($_REQUEST['error'])) {
            $content = str_replace('[error]', $_REQUEST['error'], $content);
        } else {
            $content = str_replace('[error]', "", $content);
        }

        $moders = $db->Execute("SELECT * FROM admins WHERE type = 'moder' AND active = 1 ORDER BY login ASC");
        $table = "";
        while ($moder = $moders->FetchRow()) {
            $tasks_vipolneno = $db->Execute("SELECT count(z.id) as num FROM zadaniya z WHERE z.who_posted = '" . $moder["id"] . "' AND z.vipolneno = 1")->FetchRow();
            $moder_money = $db->Execute("SELECT SUM(price) as summ FROM moders_money WHERE moder_id = '" . $moder["id"] . "'")->FetchRow();
            $withdrawal = $db->Execute("SELECT sum(sum) as summa FROM withdrawal WHERE uid = " . $moder["id"])->FetchRow();
            $summa = (isset($withdrawal["summa"]) && !empty($withdrawal["summa"])) ? $withdrawal["summa"] : 0;
            $balance = ((int) $moder_money["summ"]) - $summa;

            $tr = "<tr>";
            $tr .= "<td>" . $moder["login"] . "</td>";
            $tr .= "<td>" . $tasks_vipolneno["num"] . "</td>";
            $tr .= "<td>" . (!empty($moder_money["summ"]) ? $moder_money["summ"] : 0) . "</td>";
            $tr .= "<td class='withdrawal'>" . $summa . "</td>";
            $tr .= "<td class='balance'><a href='?module=admins&action=moders&action2=decode_balance&moder=" . $moder["id"] . "'>" . $balance . "</a></td>";
            $tr .= "<td><input type='text' value='' class='mini' id='" . $moder["id"] . "' /></td>";
            $tr .= "<td class='output'><a href='#' class='ico' onclick='return false;'></a></td>";
            $tr .= "</tr>";
            $table .= $tr;
        }
        $content = str_replace('[table]', $table, $content);

        return $content;
    }

    function moders_money_output($db) {
        $moder = @$_REQUEST['moder'];
        $sum = @$_REQUEST['sum'];
        $date = date("Y-m-d H:i:s");

        if (!empty($moder) && !empty($sum)) {
            $db->Execute("INSERT INTO withdrawal (uid, sum, date) VALUES ('$moder', '$sum', '$date')");
            header('location:' . $_SERVER['HTTP_REFERER']);
        } else {
            header('location:' . $_SERVER['HTTP_REFERER'] . "&error=Не верные данные");
        }
    }

    function moders_money_edit($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/moders_money_edit.tpl');
        $id = @$_REQUEST['id'];
        if (!empty($id)) {
            $withdrawal = $db->Execute("SELECT * FROM withdrawal WHERE id=$id")->FetchRow();
            if (!empty($_POST)) {
                $uid = @$_REQUEST['uid'];
                $sum = @$_REQUEST['sum'];
                $withdrawal = $db->Execute("UPDATE withdrawal SET uid=$uid, sum=$sum WHERE id=$id");
                header('location:?module=admins&action=moders&action2=withdrawal');
            }
            $select = "";
            $moders = $db->Execute("SELECT * FROM admins WHERE type = 'moder' AND active = 1 ORDER BY login ASC");
            while ($moder = $moders->FetchRow()) {
                $select .= "<option value='" . $moder["id"] . "' " . (($withdrawal['uid'] == $moder["id"]) ? "selected" : "") . ">" . $moder["login"] . "</option>";
            }
            $content = str_replace('[moders]', $select, $content);
            $content = str_replace('[id]', $withdrawal["id"], $content);
            $content = str_replace('[sum]', $withdrawal["sum"], $content);
            $content = str_replace('[date]', $withdrawal["date"], $content);
        } else {
            header('location:' . $_SERVER['HTTP_REFERER'] . "&error=В базе данных нет такой записи");
        }

        return $content;
    }

    function moders_money_delete($db) {
        $id = @$_REQUEST['id'];
        if (!empty($id)) {
            $db->Execute("DELETE FROM withdrawal WHERE id=$id");
            header('location:' . $_SERVER['HTTP_REFERER']);
        } else {
            header('location:' . $_SERVER['HTTP_REFERER'] . "&error=В базе данных нет такой записи");
        }
    }

    function moders_decode_balance($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/moders_decode_balance.tpl');
        $limit = 50;
        $offset = 1;
        if (isset($_GET['offset']) && !empty($_GET['offset'])) {
            $offset = (int) $_GET['offset'];
        }
        $money = array();
        $moder = @$_REQUEST['moder'];
        if (!empty($moder)) {
            $moder = $db->Execute("SELECT * FROM admins WHERE type = 'moder' AND id = " . $moder)->FetchRow();
            $content = str_replace('[balance_title]', "Баланс модератора: <b>" . $moder['login'] . "</b>", $content);
            $tasks = $db->Execute("SELECT z.* FROM zadaniya z WHERE z.who_posted = '" . $moder["id"] . "' AND z.vipolneno = 1 ORDER BY z.date DESC, z.id DESC LIMIT " . ($offset - 1) * $limit . "," . $limit);
            $all_tasks = $db->Execute("SELECT z.* FROM zadaniya z WHERE z.who_posted = '" . $moder["id"] . "' AND z.vipolneno = 1 ORDER BY z.date DESC, z.id DESC");
            $moders_money = $db->Execute("SELECT * FROM moders_money WHERE moder_id = '" . $moder["id"] . "'")->GetAll();
            foreach ($moders_money as $task) {
                $money[$task["zid"]] = $task["price"];
            }

            //$count_task = $db->Execute("SELECT count(id) as num FROM zadaniya WHERE who_posted = '" . $moder["id"] . "' AND vipolneno = 1")->FetchRow();
            $all_money = $db->Execute("SELECT SUM(price) as sum FROM moders_money WHERE moder_id = '" . $moder["id"] . "'")->FetchRow();
            $withdrawal = $db->Execute("SELECT sum(sum) as summa FROM withdrawal WHERE uid = " . $moder["id"])->FetchRow();
        }

        $pegination = '<div style="float:right">';
        if ($offset == 1) {
            $pegination .= '<div style="float:left">Пред.</div>';
        } else {
            $pegination .= "<div style='float:left'><a href='/admin.php?module=admins&action=moders&action2=decode_balance&moder=" . $moder["id"] . "&offset=" . ($offset - 1) . "'>Пред.</a></div>";
        }
        $pegination .= '<div style="float:left">&nbsp [ стр.&nbsp</div>';
        $pegination .= '<div class="select" style="width:50px;float:left;margin-top:-7px"><select name="pagerMenu" onchange="location=\'/admin.php?module=admins&action=moders&action2=decode_balance&moder=' . $moder["id"] . '&offset=\'+this.options[this.selectedIndex].value+\'\'" ;="">';

        $all_zadanya = $all_tasks->NumRows();
        $count_pegination = ceil($all_zadanya / $limit);
        for ($i = 1; $i < $count_pegination + 1; $i++) {
            if ($i == $offset) {
                $pegination .= '<option value="' . ($i) . '" selected="selected">' . ($i) . '</option>';
            } else {
                $pegination .= '<option value="' . ($i) . '">' . ($i) . '</option>';
            }
        }
        $pegination .= '</select></div>';
        $pegination .= '&nbsp из ' . $count_pegination . ' ] &nbsp';
        if ($tasks->NumRows() < $limit) {
            $pegination .= "След.";
        } else {
            $pegination .= "<a href='/admin.php?module=admins&action=moders&action2=decode_balance&moder=" . $moder["id"] . "&offset=" . ($offset + 1) . "'>След.</a>";
        }
        $pegination .= '</div><br /><br />';
        if ($count_pegination == 1 || $all_zadanya == 0)
            $pegination = "";

        $zadaniya = "";
        $sum = 0;
        while ($task = $tasks->FetchRow()) {
            $bg = '';
            if ($task['dorabotka']) {
                $new_s = "in-work";
                $bg = '#f6b300';
            } else if ($task['vipolneno']) {
                $new_s = "done";
                $bg = '#83e24a';
            } else if ($task['vrabote']) {
                $new_s = "working";
                $bg = '#00baff';
            } else if ($task['navyklad']) {
                $new_s = "ready";
                $bg = '#ffde96';
            } else if ($task['vilojeno']) {
                $new_s = "vilojeno";
                $bg = '#b385bf';
            } else if ($task['rectificate']) {
                $new_s = "vilojeno";
                $bg = '#bbb';
            } else {
                $new_s = '';
            }
            if ($_SESSION['admin']['id'] != 1)
                $bg = '';

            $zadaniya .= '<tr style="background:' . $bg . '">';
            $zadaniya .= '<td>' . (!empty($money[$task["id"]]) ? $money[$task["id"]] : 0) . '</td>';
            $zadaniya .= '<td style="text-align:left"><a href="?module=admins&action=zadaniya&uid=' . $task["uid"] . '&sid=' . $task["sid"] . '&action2=edit&id=' . $task["id"] . '">' . $task["tema"] . '</a></td>';
            $zadaniya .= '<td>' . date("d.m.Y", $task["date"]) . '</td>';
            $zadaniya .= '<td class="state ' . $new_s . '"><span class="ico"></span></td>';
            $zadaniya .= '</tr>';
            if ($task["vipolneno"] == 1) {
                $sum += ($task["price_viklad"]);
            }
        }
        $content = str_replace('[id]', $moder["id"], $content);
        $content = str_replace('[earned]', $all_money["sum"], $content);
        $content = str_replace('[withdrawn]', $withdrawal["summa"] ? $withdrawal["summa"] : 0, $content);
        $content = str_replace('[balance]', ($all_money["sum"] - $withdrawal["summa"]), $content);
        $content = str_replace('[zadaniya]', $zadaniya, $content);
        $content = str_replace('[pegination]', $pegination, $content);
        return $content;
    }

    function moders_withdrawal($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/moders_withdrawal.tpl');
        $table = $bg = "";
        $num = 1;
        $withdrawal = $db->Execute("SELECT w.*,a.login FROM withdrawal w LEFT JOIN admins a ON a.id=w.uid WHERE a.type='moder' ORDER BY w.date");
        while ($value = $withdrawal->FetchRow()) {
            $bg = (($num % 2) == 0) ? "#f7f7f7" : "";
            $table .= '<tr style="background:' . $bg . '">';
            $table .= '<td>' . $value["login"] . '</td>';
            $table .= '<td>' . $value["sum"] . ' руб.</td>';
            $table .= '<td>' . $value["date"] . '</td>';
            $table .= '<td class="edit"><a href="?module=admins&action=moders&action2=money&action3=edit&id=' . $value["id"] . '" class="ico"></a></td>';
            $table .= '<td class="close"><a href="?module=admins&action=moders&action2=money&action3=delete&id=' . $value["id"] . '" class="ico"></a></td>';
            $table .= '</tr>';
            $num++;
        }
        $content = str_replace('[table]', $table, $content);
        return $content;
    }

    function copywriters($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/copywriters.tpl');
        $filter = @$_REQUEST['filter'];

        if (isset($_REQUEST['error'])) {
            $content = str_replace('[error]', $_REQUEST['error'], $content);
        } else {
            $content = str_replace('[error]', "", $content);
        }

        $date_time_array = getdate(time());
        $day = $date_time_array['mday'];
        $day_week = $date_time_array['wday'] - 1;
        $month = $date_time_array['mon'];
        $year = $date_time_array['year'];

        $condition = "";
        switch ($filter) {
            case "day": $filter = "mon";
                $name_chart = "Статистика <b>за день</b>";
                $time = mktime(0, 0, 0, $month, $day, $year);
                $condition = " AND date >= $time";
                break;
            case "weeks": $filter = "mday";
                $name_chart = "Статистика <b>за неделю</b>";
                $time = mktime(0, 0, 0, $month, $day - $day_week, $year);
                $condition = " AND date >= $time";
                break;
            case "month": $filter = "mday";
                $name_chart = "Статистика <b>за месяц</b>";
                $time = mktime(0, 0, 0, $month, 1, $year);
                $condition = " AND date >= $time";
                break;
            case "all": $filter = "year";
                $name_chart = "Статистика <b>за всё время</b>";
                $condition = "";
                break;
            default : $filter = "";
                $name_chart = "Статистика <b>за всё время</b>";
                $time = mktime(0, 0, 0, $month, $day - $day_week, $year);
                $condition = "";
        }
        $content = str_replace('[name_stat]', $name_chart, $content);

        // Задачи которые выполнили копирайтеры
        $tasks_vipolneno = $db->Execute("SELECT COUNT(*) AS cnt, `copywriter` FROM `zadaniya_new` WHERE copywriter != 0 AND vipolneno = 1 $condition GROUP BY `copywriter`")->GetAll();
        $statistics = array();
        foreach ($tasks_vipolneno as $task) {
            if (!isset($statistics[$task["copywriter"]])) {
                $statistics[$task["copywriter"]] = array();
            }
            $statistics[$task["copywriter"]]["vipolneno"] = $task["cnt"];
        }

        //Задачи, которые ещё находятся в работе и не засчитаны копирайтерам
        $tasks_other = $db->Execute("SELECT COUNT(*) AS cnt, `copywriter` FROM `zadaniya_new` WHERE copywriter != 0 AND vipolneno != 1 GROUP BY `copywriter`")->GetAll();
        foreach ($tasks_other as $task) {
            if (!isset($statistics[$task["copywriter"]])) {
                $statistics[$task["copywriter"]] = array();
            }
            $statistics[$task["copywriter"]]["vrabote"] = $task["cnt"];
        }

        $table = "";
        $copywriters = $db->Execute("SELECT id, login, trust FROM admins WHERE type = 'copywriter' AND active = 1 AND banned = 0 ORDER BY login ASC");
        while ($copywriter = $copywriters->FetchRow()) {
            $tr = "<tr>";
            $tr .= "<td style='text-align:left'>" . $copywriter["login"] . "</td>";
            $tr .= "<td>" . ($statistics[$copywriter["id"]]["vipolneno"] ? $statistics[$copywriter["id"]]["vipolneno"] : 0) . "</td>";
            $tr .= "<td>" . ($statistics[$copywriter["id"]]["vrabote"] ? $statistics[$copywriter["id"]]["vrabote"] : 0) . "</td>";
            $tr .= "<td><input class='trust' type='checkbox' id='" . $copywriter["id"] . "' " . (($copywriter["trust"] == 1) ? 'checked="checked"' : '') . " /></td>";
            $tr .= "<td class='lock_ok'><a href='/admin.php?module=admins&action=copywriters&action2=banned&id=" . $copywriter["id"] . "' class='ico'></a></td>";
            $tr .= "</tr>";
            $table .= $tr;
        }
        $content = str_replace('[table]', $table, $content);
        return $content;
    }

    function copywriters_banned($db) {
        $copywriter = @$_REQUEST['id'];
        if (!empty($copywriter)) {
            $db->Execute("UPDATE admins SET banned='1' WHERE type='copywriter' AND id = '$copywriter'");
            header('location:' . $_SERVER['HTTP_REFERER']);
        } else {
            header('location:' . $_SERVER['HTTP_REFERER'] . "&error=Не верные данные");
        }
    }

    function copywriters_bannedoff($db) {
        $copywriter = @$_REQUEST['id'];
        if (!empty($copywriter)) {
            $db->Execute("UPDATE admins SET banned='0' WHERE type='copywriter' AND id = '$copywriter'");
            header('location:' . $_SERVER['HTTP_REFERER']);
        } else {
            header('location:' . $_SERVER['HTTP_REFERER'] . "&error=Не верные данные");
        }
    }

    function copywriters_blacklist($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/copywriters_blacklist.tpl');
        $statistics = array();
        $copywriters = $db->Execute("SELECT id, login FROM admins WHERE type = 'copywriter' AND active = 1 AND banned = 1 ORDER BY login ASC");
        if ($copywriters->NumRows() == 0) {
            $table = "<tr><td colspan='2'>Нет ни одного заблокированного копирайтера!</td></tr>";
        } else {
            // Задачи которые выполнили копирайтеры
            $tasks_vipolneno = $db->Execute("SELECT COUNT(*) AS cnt, `copywriter` FROM `zadaniya_new` WHERE copywriter != 0 AND vipolneno = 1 GROUP BY `copywriter`")->GetAll();
            foreach ($tasks_vipolneno as $task) {
                if (!isset($statistics[$task["copywriter"]])) {
                    $statistics[$task["copywriter"]] = array();
                }
                $statistics[$task["copywriter"]]["vipolneno"] = $task["cnt"];
            }
        }

        $table = "";
        while ($copywriter = $copywriters->FetchRow()) {
            $tr = "<tr>";
            $tr .= "<td style='text-align:left'>" . $copywriter["login"] . "</td>";
            $tr .= "<td>" . ($statistics[$copywriter["id"]]["vipolneno"] ? $statistics[$copywriter["id"]]["vipolneno"] : 0) . "</td>";
            $tr .= "<td class='lock_open'><a href='/admin.php?module=admins&action=copywriters&action2=bannedoff&id=" . $copywriter["id"] . "' class='ico'></a></td>";
            $tr .= "</tr>";
            $table .= $tr;
        }
        $content = str_replace('[table]', $table, $content);
        return $content;
    }

    function copywriters_balance($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/copywriters_balance.tpl');
        $filter = @$_REQUEST['filter'];

        if (isset($_REQUEST['error'])) {
            $content = str_replace('[error]', $_REQUEST['error'], $content);
        } else {
            $content = str_replace('[error]', "", $content);
        }

        $date_time_array = getdate(time());
        $day = $date_time_array['mday'];
        $day_week = $date_time_array['wday'] - 1;
        $month = $date_time_array['mon'];
        $year = $date_time_array['year'];

        $date = date("Y-m-d H:i:s");
        $condition = "";
        switch ($filter) {
            case "day": $filter = "mon";
                $name_chart = "Статистика за день";
                $time = mktime(0, 0, 0, $month, $day, $year);
                $condition = " AND date >= $time";
                break;
            case "weeks": $filter = "mday";
                $name_chart = "Статистика за неделю";
                $time = mktime(0, 0, 0, $month, $day - $day_week, $year);
                $condition = " AND date >= $time";
                break;
            case "month": $filter = "mday";
                $name_chart = "Статистика за месяц";
                $time = mktime(0, 0, 0, $month, 1, $year);
                $condition = " AND date >= $time";
                break;
            case "all": $filter = "year";
                $name_chart = "Статистика за всё время";
                $condition = "";
                break;
            default : $filter = "";
                $name_chart = "Статистика за всё время";
                $time = mktime(0, 0, 0, $month, $day - $day_week, $year);
                $condition = "";
        }
        $content = str_replace('[name_stat]', $name_chart, $content);

        $copywriters = $db->Execute("SELECT * FROM admins WHERE type = 'copywriter' AND active = 1 ORDER BY login ASC");
        $table = "";
        while ($copywriter = $copywriters->FetchRow()) {
            $tasks_vipolneno = $db->Execute("SELECT count(id) as num, sum(nof_chars) as chars FROM zadaniya_new WHERE copywriter = " . $copywriter["id"] . " AND vipolneno = 1" . $condition)->FetchRow();
            $withdrawal = $db->Execute("SELECT sum(sum) as summa FROM withdrawal WHERE uid = " . $copywriter["id"])->FetchRow();
            $summa = (isset($withdrawal["summa"]) ? $withdrawal["summa"] : 0);
            $balance = ((int) $tasks_vipolneno["chars"] / 1000 * 21) - $summa;

            $tr = "<tr>";
            $tr .= "<td>" . $copywriter["login"] . "</td>";
            $tr .= "<td>" . $tasks_vipolneno["num"] . "</td>";
            $tr .= "<td>" . ((int) $tasks_vipolneno["chars"] / 1000 * 21) . "</td>";
            $tr .= "<td class='withdrawal'>" . round($summa, 2) . "</td>";
            $tr .= "<td class='balance'><a href='?module=admins&action=copywriters&action2=decode_balance&copywriter=" . $copywriter["id"] . "'>" . round($balance, 2) . "</a></td>";
            $tr .= "<td><input type='text' value='' class='mini' id='" . $copywriter["id"] . "' /></td>";
            $tr .= "<td class='output'><a href='#' class='ico' onclick='return false;'></a></td>";
            $tr .= "</tr>";
            $table .= $tr;
        }
        $content = str_replace('[table]', $table, $content);

        return $content;
    }

    function copywriters_money_output($db) {
        $copywriter = @$_REQUEST['copywriter'];
        $sum = @$_REQUEST['sum'];
        $date = date("Y-m-d H:i:s");

        if (!empty($copywriter) && !empty($sum)) {
            $db->Execute("INSERT INTO withdrawal (uid, sum, date) VALUES ('$copywriter', '$sum', '$date')");
            header('location:' . $_SERVER['HTTP_REFERER']);
        } else {
            header('location:' . $_SERVER['HTTP_REFERER'] . "&error=Не верные данные");
        }
    }

    function copywriters_money_edit($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/copywriters_money_edit.tpl');
        $id = @$_REQUEST['id'];
        if (!empty($id)) {
            $withdrawal = $db->Execute("SELECT * FROM withdrawal WHERE id=$id")->FetchRow();
            if (!empty($_POST)) {
                $uid = @$_REQUEST['uid'];
                $sum = @$_REQUEST['sum'];
                $withdrawal = $db->Execute("UPDATE withdrawal SET uid=$uid, sum=$sum WHERE id=$id");
                header('location:?module=admins&action=copywriters&action2=withdrawal');
            }
            $select = "";
            $copywriters = $db->Execute("SELECT * FROM admins WHERE type = 'copywriter' AND active = 1 ORDER BY login ASC");
            while ($copywriter = $copywriters->FetchRow()) {
                $select .= "<option value='" . $copywriter["id"] . "' " . (($withdrawal['uid'] == $copywriter["id"]) ? "selected" : "") . ">" . $copywriter["login"] . "</option>";
            }
            $content = str_replace('[copywriters]', $select, $content);
            $content = str_replace('[id]', $withdrawal["id"], $content);
            $content = str_replace('[sum]', $withdrawal["sum"], $content);
            $content = str_replace('[date]', $withdrawal["date"], $content);
        } else {
            header('location:' . $_SERVER['HTTP_REFERER'] . "&error=В базе данных нет такой записи");
        }

        return $content;
    }

    function copywriters_money_delete($db) {
        $id = @$_REQUEST['id'];
        if (!empty($id)) {
            $db->Execute("DELETE FROM withdrawal WHERE id=$id");
            header('location:' . $_SERVER['HTTP_REFERER']);
        } else {
            header('location:' . $_SERVER['HTTP_REFERER'] . "&error=В базе данных нет такой записи");
        }
    }

    function copywriters_decode_balance($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/copywriters_decode_balance.tpl');
        $copywriter = @$_REQUEST['copywriter'];
        if (!empty($copywriter)) {
            $copywriter = $db->Execute("SELECT * FROM admins WHERE type = 'copywriter' AND id = " . $copywriter)->FetchRow();
            $content = str_replace('[balance_title]', "Баланс копирайтера: <b>" . $copywriter['login'] . "</b>", $content);
            $tasks = $db->Execute("SELECT * FROM zadaniya_new WHERE copywriter = " . $copywriter["id"] . " ORDER BY date DESC");
            $withdrawal = $db->Execute("SELECT sum(sum) as summa FROM withdrawal WHERE uid = " . $copywriter["id"])->FetchRow();
        } else {
            $content = str_replace('[balance_title]', "Баланс копирайтера: <b>[user_balans]</b>", $content);
            $tasks = $db->Execute("SELECT * FROM zadaniya_new WHERE copywriter != 0 ORDER BY date DESC");
            $withdrawal = $db->Execute("SELECT sum(sum) as summa FROM withdrawal")->FetchRow();
        }
        $zadaniya = "";
        $sum = 0;
        while ($task = $tasks->FetchRow()) {
            $bg = '';
            if ($task['dorabotka']) {
                $new_s = "in-work";
                $bg = '#f6b300';
            } else if ($task['vipolneno']) {
                $new_s = "done";
                $bg = '#83e24a';
            } else if ($task['vrabote']) {
                $new_s = "working";
                $bg = '#00baff';
            } else if ($task['navyklad']) {
                $new_s = "ready";
                $bg = '#ffde96';
            } else if ($task['vilojeno']) {
                $new_s = "vilojeno";
                $bg = '#b385bf';
            } else if ($task['rectificate']) {
                $new_s = "vilojeno";
                $bg = '#bbb';
            } else {
                $new_s = '';
            }
            if ($_SESSION['admin']['id'] != 1)
                $bg = '';

            $zadaniya .= '<tr style="background:' . $bg . '">';
            $zadaniya .= '<td>' . ($task["nof_chars"] / 1000 * 21) . '</td>';
            $zadaniya .= '<td style="text-align:left"><a href="?module=admins&action=articles&uid=&action2=edit&id=' . $task["id"] . '">' . $task["tema"] . '</a></td>';
            $zadaniya .= '<td>' . date("d.m.Y", $task["date"]) . '</td>';
            $zadaniya .= '<td class="state ' . $new_s . '"><span class="ico"></span></td>';
            $zadaniya .= '</tr>';
            if ($task["vipolneno"] == 1)
                $sum += ($task["nof_chars"] / 1000 * 21);
        }
        $content = str_replace('[id]', $copywriter["id"], $content);
        $content = str_replace('[earned]', $sum, $content);
        $content = str_replace('[withdrawn]', $withdrawal["summa"] ? $withdrawal["summa"] : 0, $content);
        $content = str_replace('[balance]', ($sum - $withdrawal["summa"]), $content);
        $content = str_replace('[zadaniya]', $zadaniya, $content);
        return $content;
    }

    function copywriters_withdrawal($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/copywriters_withdrawal.tpl');
        $table = $bg = "";
        $num = 1;
        $withdrawal = $db->Execute("SELECT w.*,a.login FROM withdrawal w LEFT JOIN admins a ON a.id=w.uid WHERE a.type='copywriter' ORDER BY w.date");
        while ($value = $withdrawal->FetchRow()) {
            $bg = (($num % 2) == 0) ? "#f7f7f7" : "";
            $table .= '<tr style="background:' . $bg . '">';
            $table .= '<td>' . $value["login"] . '</td>';
            $table .= '<td>' . $value["sum"] . ' руб.</td>';
            $table .= '<td>' . $value["date"] . '</td>';
            $table .= '<td class="edit"><a href="?module=admins&action=copywriters&action2=money&action3=edit&id=' . $value["id"] . '" class="ico"></a></td>';
            $table .= '<td class="close"><a href="?module=admins&action=copywriters&action2=money&action3=delete&id=' . $value["id"] . '" class="ico"></a></td>';
            $table .= '</tr>';
            $num++;
        }
        $content = str_replace('[table]', $table, $content);
        return $content;
    }

    function copywriters_trust($db) {
        $id = (int) $_POST['id'];
        $copywriter = $db->Execute("SELECT * FROM admins WHERE id = $id")->FetchRow();
        if (!empty($copywriter)) {
            if ($copywriter["trust"] == 0) {
                $db->Execute("UPDATE admins SET trust = 1 WHERE id = $id");
                echo "Копирайтер теперь в числе доверенных!";
            } else {
                $db->Execute("UPDATE admins SET trust = 0 WHERE id = $id");
                echo "Копирайтер потерял доверие!";
            }
        }
        die();
    }

    function statistics($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/statistics.tpl');
        return $content;
    }

    function change_task_on($db) {
        $zid = (int) $_POST['zid'];
        $task = $db->Execute("SELECT * FROM zadaniya_new WHERE id = $zid")->FetchRow();
        if ($task["etxt"] == 0) {
            $db->Execute("UPDATE zadaniya_new SET for_copywriter = 1 WHERE id = $zid");
            // Пока убрал отправление писем Копирайтерам, когда включают галку у задачи 
            // Слишком много писем высылается, получается спам какой-то
        }
    }

    function change_task_off($db) {
        $zid = (int) $_POST['zid'];
        $task = $db->Execute("SELECT * FROM zadaniya_new WHERE id = $zid")->FetchRow();
        if ($task["copywriter"] == 0) {
            $db->Execute("UPDATE zadaniya_new SET for_copywriter = 0 WHERE id = $zid");
        } else {
            header('location: ?module=admins&action=articles');
            die();
        }
    }

    function change_task_all($db) {
        $status = (int) $_POST['status'];
        $tasks = $db->Execute("SELECT * FROM zadaniya_new z WHERE z.for_copywriter != '$status' AND z.rectificate='0' AND z.vrabote='0' AND z.vipolneno='0' AND z.dorabotka='0' AND z.navyklad='0' AND z.vilojeno='0'");
        if ($tasks->NumRows() > 0) {
            while ($task = $tasks->FetchRow()) {
                $db->Execute("UPDATE zadaniya_new SET for_copywriter = '$status' WHERE id = " . $task["id"]);
            }
        }
        echo $tasks->NumRows();
        die();
    }

    function change_status_task($db) {
        $uid = $_SESSION['admin']['id'];
        $id = $_REQUEST['id'];
        $status = $_REQUEST['status'];
        if (isset($_REQUEST['burse'])) {
            $table = "zadaniya";
        } else {
            $table = "zadaniya_new";
        }

        $task = $db->Execute("SELECT * FROM $table WHERE id = $id")->FetchRow();
        if (!empty($uid) && !empty($task) && !empty($status) && $status == "dorabotka") {
            /* $dorabotka = 0;
              if (isset($_REQUEST['burse']) && $status == "dorabotka") {
              $dorabotka = 0;
              } else {
              $dorabotka = 1;
              } */

            $time = time();
            $db->Execute("UPDATE $table SET rework = 1, vipolneno = 0, navyklad = 0, vilojeno = 0, date_in_work = '$time' WHERE id = $id");
            if ($task['copywriter'] != 0) {
                $copywriter = $db->Execute("SELECT * FROM admins WHERE id=" . $task['copywriter'])->FetchRow();
                $this->_postman->copywriter->articlesChangeStatusDorabotka($id, $copywriter);
            }
        }

        header('location:' . $_SERVER['HTTP_REFERER']);
    }

    function statistics_money($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/statistics_money.tpl');
        $sort = @$_REQUEST['filter'];

        $date_time_array = getdate(time());
        $day = $date_time_array['mday'];
        $day_week = $date_time_array['wday'] - 1;
        $month = $date_time_array['mon'];
        $year = $date_time_array['year'];
        if ($day_week < 0) {
            $day_week = 6;
        }
        $date = date("Y-m-d H:i:s");
        $time = time();
        switch ($sort) {
            case "weeks": $filter = "mday";
                $time = mktime(0, 0, 0, $month, $day - $day_week, $year);
                $date = date("Y-m-d H:i:s", $time);
                $content = str_replace('[name_col]', strftime('%d.%m', mktime(0, 0, 0, $month, $day - $day_week, $year)) . "-" . strftime('%d.%m', time()), $content);
                break;
            case "month": $filter = "mday";
                $time = mktime(0, 0, 0, $month, 1, $year);
                $date = date("Y-m-d H:i:s", $time);
                $content = str_replace('[name_col]', strftime('%B %Y', time()), $content);
                break;
            case "year": $filter = "mon";
                if (($month - 12) < 1) {
                    $year -= 1;
                    $month += 1;
                }
                $time = mktime(0, 0, 0, $month, 1, $year);
                $date = date("Y-m-d H:i:s", $time);
                $content = str_replace('[name_col]', ((($month - 12) < 1) ? $year . " - " : "") . strftime('%Y', time()), $content);
                break;
            case "all": $filter = "year";
                $date = $time = null;
                break;
            default : $filter = "mday";
                $time = mktime(0, 0, 0, $month, $day - $day_week, $year);
                $date = date("Y-m-d H:i:s", $time);
                $content = str_replace('[name_col]', strftime('%d.%m', mktime(0, 0, 0, $month, $day - $day_week, $year)) . "-" . strftime('%d.%m', time()), $content);

                $sort = "weeks";
        }
        $content = str_replace('[name_col]', "#", $content);
        $tasks_burse_for_copywriter = array();
        if (!empty($date)) {
            $tasks = $db->Execute("SELECT ct.* FROM completed_tasks ct  WHERE ct.date >= '$date' ORDER BY ct.date ASC"); // AND date < '2014-10-28 00:00:00'
            $tasks_burse_copywriter = $db->Execute("SELECT id, nof_chars FROM zadaniya WHERE for_copywriter = 1 AND copywriter != 0 AND date >= '$date'");
            $sapes = $db->Execute("SELECT * FROM zadaniya_new WHERE (etxt = 1 OR copywriter != 0) AND date >= '$time' ORDER BY date ASC");
        } else {
            $tasks = $db->Execute("SELECT ct.* FROM completed_tasks ct ORDER BY ct.date ASC");
            $tasks_burse_copywriter = $db->Execute("SELECT id, nof_chars FROM zadaniya WHERE for_copywriter = 1 AND copywriter != 0");
            $sapes = $db->Execute("SELECT * FROM zadaniya_new WHERE (etxt = 1 OR copywriter != 0) ORDER BY date ASC");
        }
        while ($task_copywriter = $tasks_burse_copywriter->FetchRow()) {
            $tasks_burse_for_copywriter[$task_copywriter["id"]] = $task_copywriter["nof_chars"];
        }
        //print_r($tasks->GetAll());die();
        $stat = $money = array(0 => 0);
        $count = $sum = 0;
        while ($task = $tasks->FetchRow()) {
            $price_viklad = $db->Execute("SELECT s.price_viklad as summa FROM zadaniya z LEFT JOIN sayty s ON s.id=z.sid WHERE z.who_posted !=0 AND z.id = " . $task["zid"])->FetchRow();

            $date_time = explode(" ", $task["date"]);
            $date = explode("-", $date_time[0]);
            $time = explode(":", $date_time[1]);
            $time_unix = mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]);
            $date_time_array = getdate($time_unix);
            //print_r($date_time_array);
            if (!isset($money[$date_time_array[$filter]])) {
                $money[$date_time_array[$filter]] = 0;
            }

            if (!isset($stat[$date_time_array[$filter]])) {
                $stat[$date_time_array[$filter]] = array("15" => 0, "62" => 0, "78" => 0, "110" => 0, "77" => 0, "90" => 0, "128" => 0, "45" => 0, "61" => 0, "93" => 0, "60" => 0, "76" => 0, "111" => 0, "0" => 0, "40" => 0, "90" => 0, "100" => 0, "4" => 0);
            }

            if ($task['status'] == 1) {
                if ($task['price'] == 93 && $task['date'] > "2015-02-01 00:00:00") {
                    $stat[$date_time_array[$filter]][90] += 1;
                } else {
                    $stat[$date_time_array[$filter]][$task['price']] += 1;
                }
                $price = 0;
                if (array_key_exists($task["zid"], $tasks_burse_for_copywriter)) {
                    $price = $task['price'] - (COPYWRITER_PRICE_FOR_1000_CHAR * ($tasks_burse_for_copywriter[$task["zid"]] / 1000));
                } else {
                    switch ($task['price']) {
                        case 15 : $price = 15;
                            break;
                        case 45 : $price = 13.5;
                            break;
                        case 61 : $price = 13.75;
                            break;
                        case 93 : $price = 22.12;
                            break;
                        case 60 : $price = 18;
                            break;
                        case 76 : $price = 13;
                            break;
                        case 111 : $price = 16.5;
                            break;
                        case 62 : $price = 30.5;
                            break;
                        case 78 : $price = 30.75;
                            break;
                        case 110 : $price = 39.12;
                            break;
                        case 77 : $price = 35;
                            break;
                        case 128 : $price = 33.5;
                            break;
                    }
                    if ($task['price'] == 93 && $task['date'] > "2015-02-01 00:00:00") {
                        $price += 17;
                    }
                }

                // Вычитаем деньги за выкладывание (Модератору)
                if ($price > 0 && !empty($price_viklad)) {
                    $price_viklad = (!empty($price_viklad["summa"])) ? $price_viklad["summa"] : 0;
                    $price -= $price_viklad;
                }

                // Вычитаем деньги (ВРОДЕ БЫ) за индексацию в ГетБот и за работу в ЕТХТ
                if ($price > 0 && $task['price'] == 15) {
                    $price -= 0.4;
                } elseif ($price > 0) {
                    $price -= 2.9;
                }
                $money[$date_time_array[$filter]] += $price;
            }


            unset($date_time_array);
        }

        $table = "";
        foreach ($money as $key => $value) {
            if ($key == 0)
                continue;
            $tr = "<tr>";
            $tr .= "<td>" . (($sort == "weeks") ? strftime('%A', mktime(0, 0, 0, $month, $key, $year)) : ((($sort == "month") ? strftime('%d.%m', mktime(0, 0, 0, $month, $key, $year)) : (($sort == "year") ? strftime('%B', mktime(0, 0, 0, $key, 1, $year)) : strftime('%Y', mktime(0, 0, 0, 1, 1, $key)))))) . "</td>";
            $tr .= "<td>" . $stat[$key][15] . "</td>";
            if ($sort == "year") {
                $tr .= "<td>" . $stat[$key][45] . "</td>";
                $tr .= "<td>" . $stat[$key][61] . "</td>";
                $tr .= "<td>" . $stat[$key][93] . "</td>";
                $tr .= "<td>" . $stat[$key][60] . "</td>";
                $tr .= "<td>" . $stat[$key][76] . "</td>";
                $tr .= "<td>" . $stat[$key][111] . "</td>";
            }
            $tr .= "<td>" . $stat[$key][62] . "</td>";
            $tr .= "<td>" . $stat[$key][78] . "</td>";
            $tr .= "<td>" . $stat[$key][110] . "</td>";
            $tr .= "<td>" . $stat[$key][77] . "</td>";
            $tr .= "<td>" . $stat[$key][90] . "</td>";
            $tr .= "<td>" . $stat[$key][128] . "</td>";
            $tr .= "<td>" . (round(($stat[$key][62] + $stat[$key][77] + $stat[$key][110] + $stat[$key][78] + $stat[$key][90] + $stat[$key][128] + $stat[$key][93] + $stat[$key][61] + $stat[$key][45] + $stat[$key][15] + $stat[$key][60] + $stat[$key][76] + $stat[$key][111]))) . "</td>";
            $tr .= "<td>" . $value . "</td>";
            $tr .= "</tr>";
            $table .= $tr;
            $sum += $value;
            $count += ($stat[$key][62] + $stat[$key][77] + $stat[$key][110] + $stat[$key][78] + $stat[$key][90] + $stat[$key][128] + $stat[$key][93] + $stat[$key][61] + $stat[$key][45] + $stat[$key][15] + $stat[$key][60] + $stat[$key][76] + $stat[$key][111]);
        }
        if ($sort != "year") {
            $content = str_replace('[display]', "style='display:none'", $content);
            $content = str_replace('[class_table]', "small", $content);
            $content = str_replace('[count_colspan]', "8", $content);
        } else {
            $content = str_replace('[display]', "", $content);
            $content = str_replace('[class_table]', "very_small", $content);
            $content = str_replace('[count_colspan]', "14", $content);
        }
        $content = str_replace('[sum_task]', round($sum), $content);
        $content = str_replace('[count_task]', $count, $content);
        $content = str_replace('[stat_task]', $table, $content);


        /* ПРОВЕРКА ЗАДАЧ ДЛЯ САПЫ */
        unset($stat);
        unset($money);
        $stat = $money = array(0 => 0);
        $count = $sum = 0;
        while ($task = $sapes->FetchRow()) {
            $date_time_array = getdate($task["date"]);

            if (!isset($money[$date_time_array[$filter]])) {
                $money[$date_time_array[$filter]] = 0;
            }

            if (!isset($stat[$date_time_array[$filter]])) {
                $stat[$date_time_array[$filter]] = array("21" => 0, "31" => 0, "47" => 0, "42" => 0, "63" => 0, "0" => 0);
            }

            if ($task["copywriter"] == 0 && $task["etxt"] == 1) {
                $cena = ($task['nof_chars'] / 1000) * $task['price'];
                $comission_etxt = ($cena / 100) * 5;
                $price = $cena + $comission_etxt;
            } else {
                $cena = ($task['nof_chars'] / 1000) * 21;
                $price = $cena;
            }
            if (!isset($stat[$date_time_array[$filter]][floor($price)])) {
                $stat[$date_time_array[$filter]][floor($price)] = 0;
            }
            $stat[$date_time_array[$filter]][floor($price)] += 1;
            $money[$date_time_array[$filter]] += $price;

            unset($date_time_array);
        }
        //print_r($money);die();
        $table = "";
        foreach ($money as $key => $value) {
            if ($key == 0)
                continue;
            $tr = "<tr>";
            $tr .= "<td>" . (($sort == "weeks") ? strftime('%A', mktime(0, 0, 0, $month, $key, $year)) : ((($sort == "month") ? strftime('%d.%m', mktime(0, 0, 0, $month, $key, $year)) : (($sort == "year") ? strftime('%B', mktime(0, 0, 0, $key, 1, $year)) : strftime('%Y', mktime(0, 0, 0, 1, 1, $key)))))) . "</td>";
            $tr .= "<td>" . $stat[$key][21] . "</td>";
            $tr .= "<td>" . $stat[$key][31] . "</td>";
            $tr .= "<td>" . $stat[$key][47] . "</td>";
            $tr .= "<td>" . $stat[$key][42] . "</td>";
            $tr .= "<td>" . $stat[$key][63] . "</td>";
            $tr .= "<td>" . round($stat[$key][21] + $stat[$key][31] + $stat[$key][47] + $stat[$key][42] + $stat[$key][63]) . "</td>";
            $tr .= "<td>" . $value . "</td>";
            $tr .= "</tr>";
            $table .= $tr;
            $sum += $value;
            $count += ($stat[$key][21] + $stat[$key][31] + $stat[$key][47] + $stat[$key][42] + $stat[$key][63]);
        }
        $content = str_replace('[sum_sape]', round($sum), $content);
        $content = str_replace('[count_sape]', $count, $content);
        $content = str_replace('[stat_sape]', $table, $content);

        return $content;
    }

    function statistics_graphs($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/statistics_graphs.tpl');
        if (@$_SESSION['admin']['type'] != 'admin') {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
            $content = str_replace('[alert]', 'Данное действие Вам недоступно', $content);
            $content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
            echo $content;
            exit;
        }
        $get_graphs = @$_REQUEST['get_graphs'];
        require_once 'includes/PHPExcel.php';
        require_once 'modules/graphs/graphs_class.php';
        //echo strtotime("2014-01-01 00:00:00");
        if (isset($get_graphs) && !empty($get_graphs) && $get_graphs != "0") {
            $grahps = new graphs();
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("iforget")->setTitle("Graphs");
            if ($get_graphs != "all") {
                switch ($get_graphs) {
                    case 1: /* Chart 1 (Динамика клиентской базы от месяца) */
                        $objPHPExcel = $grahps->getExcelUserRegistration($db, $objPHPExcel, 0, "2014-01-01 00:00:00");
                        break;
                    case 2: /* Chart 2 (динамика активных ( платящих) и нективных пользователей) */
                        $objPHPExcel = $grahps->getExcelDynamicsUsers($db, $objPHPExcel, 0, "2014-01-01 00:00:00");
                        break;
                    case 3: /* Chart 3 (Кол-во заявок на актиктивного клиета в месяц) */
                        $objPHPExcel = $grahps->getExcelNumberTasksForActiveClient($db, $objPHPExcel, 0, "2014-01-01 00:00:00");
                        break;
                    case 4: /* Chart 4 (Средний оборот на 1 сайт в месяц) */
                        $objPHPExcel = $grahps->getExcelAverageTurnoverOfOneSitePerMonth($db, $objPHPExcel, 0, "2014-01-01 00:00:00");
                        break;
                    case 5: /* Chart 5 (Оборот - общее кол-во заявок в месяц) Оборот - сумма за заявку! 1 заявка = 45 руб. Оборот = 45 руб) */
                        $objPHPExcel = $grahps->getExcelTurnoverTotalNumberTasksPerMonth($db, $objPHPExcel, 0, "2014-01-01 00:00:00");
                        break;
                    case 6: /* Chart 6 (Чистая прибыль в месяц) */
                        $objPHPExcel = $grahps->getExcelNetProfitPerMonth($db, $objPHPExcel, 0, "2014-01-01 00:00:00");
                        break;
                    case 7: /* Chart 7 (средняя чистая прибыль на 1 заявку в месяц) */
                        $objPHPExcel = $grahps->getExcelAverageNetProfitOfOneApplicationPerMonth($db, $objPHPExcel, 0, "2014-01-01 00:00:00");
                        break;
                    case 8: /* Chart 8 (средний оборот на 1 клиента в месяц) */
                        $objPHPExcel = $grahps->getExcelAverageTurnoverOfOneClientPerMonth($db, $objPHPExcel, 0, "2014-01-01 00:00:00");
                        break;
                }
            } else {

                $objPHPExcel = $grahps->getExcelUserRegistration($db, $objPHPExcel, 0, "2014-01-01 00:00:00");
                $objPHPExcel = $grahps->getExcelDynamicsUsers($db, $objPHPExcel, 1, "2014-01-01 00:00:00");
                $objPHPExcel = $grahps->getExcelNumberTasksForActiveClient($db, $objPHPExcel, 2, "2014-01-01 00:00:00");
                $objPHPExcel = $grahps->getExcelAverageTurnoverOfOneSitePerMonth($db, $objPHPExcel, 3, "2014-01-01 00:00:00");
                $objPHPExcel = $grahps->getExcelTurnoverTotalNumberTasksPerMonth($db, $objPHPExcel, 4, "2014-01-01 00:00:00");
                $objPHPExcel = $grahps->getExcelNetProfitPerMonth($db, $objPHPExcel, 5, "2014-01-01 00:00:00");
                $objPHPExcel = $grahps->getExcelAverageNetProfitOfOneApplicationPerMonth($db, $objPHPExcel, 6, "2014-01-01 00:00:00");
                $objPHPExcel = $grahps->getExcelAverageTurnoverOfOneClientPerMonth($db, $objPHPExcel, 7, "2014-01-01 00:00:00");
            }
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $file_name = __DIR__ . '/../../files/graphs_excel/' . time() . '.xlsx';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . basename($file_name) . '"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
        } else {
            return $content;
        }
        exit();
    }

    function statistics_day_to_day($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/statistics_day_to_day.tpl');

        $mn = array(1 => "Январь", 2 => "Февраль", 3 => "Март", 4 => "Апрель", 5 => "Май", 6 => "Июнь", 7 => "Июль", 8 => "Август", 9 => "Сентябрь", 10 => "Октябрь", 11 => "Ноябрь", 12 => "Декабрь");
        $date_time_array = getdate(time());
        $now = $date_time_array['mday'];
        $month = $date_time_array['mon'];
        $now_mouth = $prev_mounth = array(0 => 0);

        $table = "";
        for ($day = 1; $day <= $now; $day++) {
            $year = $date_time_array['year'];
            $table .= "<tr>";
            $table .= "<td>" . strftime('%d', mktime(0, 0, 0, $month, $day, $year)) . "</td>";
            for ($count = 0; $count < 2; $count++) {

                $month = $date_time_array['mon'] - $count;
                if ($month == 0 && $count > 0) {
                    $month = 12;
                    $year = $date_time_array['year'] - 1;
                }
                $time_start = mktime(0, 0, 0, $month, $day, $year);
                $date_start = date("Y-m-d H:i:s", $time_start);

                $time_end = mktime(23, 59, 59, $month, $day, $year);
                $date_end = date("Y-m-d H:i:s", $time_end);

                $tasks = $db->Execute("SELECT ct.*, z.lay_out FROM completed_tasks ct LEFT JOIN zadaniya z ON z.id=ct.zid WHERE ct.date >= '$date_start' AND ct.date <= '$date_end' ORDER BY ct.date ASC"); //vipolneno=1 AND
                $all = $tasks->NumRows();

                if ($count == 0) {
                    $now_mouth[$day] = $all;
                } else {
                    $prev_mounth[$day] = $all;
                }

                $table .= "<td>" . ($all) . "</td>";
                if ($day == 1) {
                    switch ($count) {
                        case 0: $name_line1 = $mn[$month] . " " . $year;
                            break;
                        case 1: $name_line2 = $mn[$month] . " " . $year;
                            break;
                    }
                }
            }
            $table .= "</tr>";
        }
        $content = str_replace('[now]', $name_line1, $content);
        $content = str_replace('[now-1]', $name_line2, $content);
        if ($now_mouth[0] == 0)
            unset($now_mouth[0]);
        if ($prev_mounth[0] == 0)
            unset($prev_mounth[0]);

        $this->createChard(array("$name_line1" => $now_mouth, "$name_line2" => $prev_mounth), "Статистика день ко дню", "day_to_day");
        $content = str_replace('[chart]', "<img src='images/chart/day_to_day.png' />", $content);
        $content = str_replace('[stat]', $table, $content);
        return $content;
    }

    function statistics_tasks($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/statistics_tasks.tpl');
        $sort = @$_REQUEST['filter'];

        $getdate_array = getdate(time());
        $day = $getdate_array['mday'];
        $day_week = $getdate_array['wday'] - 1;
        $month = $getdate_array['mon'];
        $year = $getdate_array['year'];

        $date = date("Y-m-d H:i:s");
        $name_chart = "";
        switch ($sort) {
            case "weeks": $filter = "mday";
                $name_chart = "Статистика за неделю";
                $time = mktime(0, 0, 0, $month, $day - $day_week, $year);
                $date = date("Y-m-d H:i:s", $time);
                $content = str_replace('[name_col]', strftime('%d.%m', mktime(0, 0, 0, $month, $day - $day_week, $year)) . "-" . strftime('%d.%m', time()), $content);
                break;
            case "month": $filter = "mday";
                $name_chart = "Статистика за месяц";
                $time = mktime(0, 0, 0, $month, 1, $year);
                $date = date("Y-m-d H:i:s", $time);
                $content = str_replace('[name_col]', strftime('%B', time()), $content);
                break;
            case "year": $filter = "mon";
                $name_chart = "Статистика за год";
                if (($month - 12) < 1) {
                    $month += 1;
                    $year -= 1;
                }
                $time = mktime(0, 0, 0, $month, 1, $year);
                $date = date("Y-m-d H:i:s", $time);
                $content = str_replace('[name_col]', ((($month - 12) < 1) ? $year . " - " : "") . strftime('%Y', time()), $content);
                break;
            case "all": $filter = "year";
                $name_chart = "Статистика за всё время";
                $date = null;
                break;
            default : $filter = "mday";
                $name_chart = "Статистика за неделю";
                $time = mktime(0, 0, 0, $month, $day - $day_week, $year);
                $date = date("Y-m-d H:i:s", $time);
                $content = str_replace('[name_col]', strftime('%d.%m', mktime(0, 0, 0, $month, $day - $day_week, $year)) . "-" . strftime('%d.%m', time()), $content);

                $sort = "weeks";
        }
        $content = str_replace('[name_col]', "#", $content);

        if (!empty($date)) {
            $tasks = $db->Execute("SELECT ct.*, z.lay_out FROM completed_tasks ct LEFT JOIN zadaniya z ON z.id=ct.zid WHERE ct.date >= '$date' ORDER BY ct.date ASC");
        } else {
            $tasks = $db->Execute("SELECT ct.*, z.lay_out FROM completed_tasks ct LEFT JOIN zadaniya z ON z.id=ct.zid ORDER BY ct.date ASC");
        }

        $stat_in_work = $stat_lay_out = array(0 => 0);
        $count_work = $count_lay_out = 0;
        while ($task = $tasks->FetchRow()) {
            $date_time = explode(" ", $task["date"]);
            $date = explode("-", $date_time[0]);
            $time = explode(":", $date_time[1]);
            $time_unix = mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]);
            $date_time_array = getdate($time_unix);
            if (!isset($stat_in_work[$date_time_array[$filter]])) {
                $stat_in_work[$date_time_array[$filter]] = 0;
            }
            if (!isset($stat_lay_out[$date_time_array[$filter]])) {
                $stat_lay_out[$date_time_array[$filter]] = 0;
            }
            if ($task["lay_out"] == 0) {
                $stat_in_work[$date_time_array[$filter]] += 1;
            } else {
                $stat_lay_out[$date_time_array[$filter]] += 1;
            }
            unset($date_time_array);
        }

        $table = "";
        foreach ($stat_in_work as $key => $value) {
            if ($key == 0)
                continue;
            if ($key <= $getdate_array['mon'] && $sort == "year") {
                $year = $getdate_array['year'];
            }
            $tr = "<tr>";
            $tr .= "<td>" . (($sort == "weeks") ? strftime('%A', mktime(0, 0, 0, $month, $key, $year)) : ((($sort == "month") ? strftime('%d.%m.%Y', mktime(0, 0, 0, $month, $key, $year)) : (($sort == "year") ? strftime('%B %Y', mktime(0, 0, 0, $key, 1, $year)) : strftime('%Y', mktime(0, 0, 0, 1, 1, $key)))))) . "</td>";
            $tr .= "<td>$value</td>";
            $tr .= "<td>" . $stat_lay_out[$key] . "</td>";
            $tr .= "</tr>";
            $table .= $tr;
            $count_work += $value;
            $count_lay_out += $stat_lay_out[$key];
        }

        $content = str_replace('[count_work]', $count_work, $content);
        $content = str_replace('[count_lay_out]', $count_lay_out, $content);
        $content = str_replace('[stat]', $table, $content);
        $this->createChard(array("Выполнено" => $stat_in_work, "Выложено" => $stat_lay_out), $name_chart, "statistics");
        $content = str_replace('[chart]', "<img src='images/chart/statistics.png' />", $content);
        return $content;
    }

    function createChard($lines = array(), $title = null, $name = null) {
        // Standard inclusions  
        require_once 'includes/pChart/pChart/pData.class';
        require_once 'includes/pChart/pChart/pChart.class';

        // Dataset definition 
        $DataSet = new pData;
        $absciseLabel = array();
        $num_line = 0;
        foreach ($lines as $name_line => $points) {
            $num_line++;
            if (empty($points)) {
                $points[0] = 0;
            } elseif (count($points) > 2) {
                $points[0] = isset($points[0]) ? $points[0] : 0;
                if ($points[0] == 0)
                    unset($points[0]);
            }
            //print_r($points);die();
            $DataSet->AddPoint($points, "Serie$num_line");
            foreach ($points as $key => $val) {
                $absciseLabel[$num_line][] = $key;
            }
        }

        $num_line = 0;
        $DataSet->AddAllSeries();
        $DataSet->AddPoint($absciseLabel[1], "Label");
        $DataSet->SetAbsciseLabelSerie("Label");

        foreach ($lines as $name_line => $points) {
            $num_line++;
            $DataSet->SetSerieName($name_line, "Serie$num_line");
        }

        // Initialise the graph
        $Test = new pChart(600, 230);
        $Test->setFontProperties("includes/pChart/Fonts/tahoma.ttf", 8);
        $Test->setGraphArea(50, 30, 485, 200);
        $Test->drawFilledRoundedRectangle(7, 7, 593, 223, 5, 240, 240, 240);
        $Test->drawRoundedRectangle(5, 5, 595, 225, 5, 230, 230, 230);
        $Test->drawGraphArea(255, 255, 255, TRUE);
        $Test->drawScale($DataSet->GetData(), $DataSet->GetDataDescription(), SCALE_NORMAL, 150, 150, 150, TRUE, 0, 2); //print_r("2");die();
        $Test->drawGrid(1000, TRUE, 230, 230, 230, 100);

        // Draw the 0 line
        $Test->setFontProperties("includes/pChart/Fonts/tahoma.ttf", 6);
        $Test->drawTreshold(0, 143, 55, 72, TRUE, TRUE);

        // Draw the line graph
        $Test->drawLineGraph($DataSet->GetData(), $DataSet->GetDataDescription());
        $Test->drawPlotGraph($DataSet->GetData(), $DataSet->GetDataDescription(), 3, 2, 255, 255, 255);

        // Finish the graph
        $Test->setFontProperties("includes/pChart/Fonts/tahoma.ttf", 8);
        $Test->drawLegend(500, 30, $DataSet->GetDataDescription(), 255, 255, 255);
        $Test->setFontProperties("includes/pChart/Fonts/tahoma.ttf", 10);
        $Test->drawTitle(50, 22, $title, 50, 50, 50, 485);
        $Test->Render("images/chart/$name.png");
    }

    function articles($db) {
        $starttime = time();
        $profil = "";
        $profil .= microtime() . "  - START" . "\r\n";
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/articles.tpl');
        $limit = 50;
        $offset = 1;
        $pegination = "";
        $uid = @$_SESSION['admin']['id'] ? @$_SESSION['admin']['id'] : @$_SESSION['manager']['id'];
        if (isset($_GET['offset']) && !empty($_GET['offset'])) {
            $offset = (int) $_GET['offset'];
        }

        if (isset($_GET["status_z"])) {
            if ($_GET["status_z"] == "all") {
                $tasks = $all = $db->Execute("SELECT z.*,a.login FROM zadaniya_new z LEFT JOIN admins a ON z.copywriter=a.id WHERE z.from_sape=1 AND (z.sape_id IS NOT NULL AND z.sape_id != 0) AND z.sistema = 'http://pr.sape.ru/' ORDER BY z.date DESC, z.id DESC");
            } else {
                switch ($_GET["status_z"]) {
                    case "vipolneno":
                        $condition = " AND z.vipolneno = 1";
                        break;
                    case "dorabotka":
                        $condition = " AND (z.dorabotka = 1)";
                        break;
                    case "navyklad":
                        $condition = " AND z.navyklad = 1";
                        break;
                    case "vilojeno":
                        $condition = " AND z.vilojeno = 1";
                        break;
                    case "vrabote":
                        $condition = " AND z.vrabote = 1";
                        break;
                    case "new":
                        $content = str_replace('[check_all]', "", $content);
                        $condition = " AND z.rectificate='0' AND z.vrabote='0' AND z.vipolneno='0' AND z.dorabotka='0' AND z.navyklad='0' AND z.vilojeno='0' AND z.rework='0'";
                        break;
                    default: $condition = "";
                        break;
                }

                $tasks = $db->Execute("SELECT z.*,a.login FROM zadaniya_new z LEFT JOIN admins a ON z.copywriter=a.id WHERE z.from_sape=1 AND (z.sape_id IS NOT NULL AND z.sape_id != 0) AND z.sistema = 'http://pr.sape.ru/' $condition ORDER BY z.date DESC, z.id DESC LIMIT " . ($offset - 1) * $limit . "," . $limit);
                $all = $db->Execute("SELECT z.*,a.login FROM zadaniya_new z LEFT JOIN admins a ON z.copywriter=a.id WHERE z.from_sape=1 AND (z.sape_id IS NOT NULL AND z.sape_id != 0) AND z.sistema = 'http://pr.sape.ru/' $condition ORDER BY z.date DESC, z.id DESC");
            }
        } else {
            $tasks = $db->Execute("SELECT z.*,a.login FROM zadaniya_new z LEFT JOIN admins a ON z.copywriter=a.id WHERE z.from_sape=1 AND (z.sape_id IS NOT NULL AND z.sape_id != 0) AND z.sistema = 'http://pr.sape.ru/' ORDER BY z.date DESC, z.id DESC LIMIT " . ($offset - 1) * $limit . "," . $limit);
            $all = $db->Execute("SELECT z.*,a.login FROM zadaniya_new z LEFT JOIN admins a ON z.copywriter=a.id WHERE z.from_sape=1 AND (z.sape_id IS NOT NULL AND z.sape_id != 0) AND z.sistema = 'http://pr.sape.ru/' ORDER BY z.date DESC, z.id DESC");
        }
        $profil .= microtime() . "  - QUERY 1 get ALL && LImit tasks" . "\r\n";

        if (!isset($_GET["status_z"]) || (isset($_GET["status_z"]) && $_GET["status_z"] != "all")) {
            $pegination = '<div style="float:right">';
            if ($offset == 1) {
                $pegination .= '<div style="float:left">Пред.</div>';
            } else {
                $pegination .= "<div style='float:left'><a href='?module=admins&action=articles" . (isset($_GET["status_z"]) ? '&status_z=' . $_GET["status_z"] : '') . "&offset=" . ($offset - 1) . "'>Пред.</a></div>";
            }
            $pegination .= '<div style="float:left">&nbsp [ стр.&nbsp</div>';
            $pegination .= '<div class="select" style="width:50px;float:left;margin-top:-7px"><select name="pagerMenu" onchange="location=\'?module=admins&action=articles' . (isset($_GET["status_z"]) ? '&status_z=' . $_GET["status_z"] : '') . '&offset=\'+this.options[this.selectedIndex].value+\'\'" ;="">';

            $all_zadanya = $all->NumRows();
            $count_pegination = ceil($all_zadanya / $limit);
            for ($i = 1; $i < $count_pegination + 1; $i++) {
                if ($i == $offset) {
                    $pegination .= '<option value="' . ($i) . '" selected="selected">' . ($i) . '</option>';
                } else {
                    $pegination .= '<option value="' . ($i) . '">' . ($i) . '</option>';
                }
            }
            $pegination .= '</select></div>';
            $pegination .= '&nbsp из ' . $count_pegination . ' ] &nbsp';
            if ($tasks->NumRows() < $limit) {
                $pegination .= "След.";
            } else {
                $pegination .= "<a href='?module=admins&action=articles" . (isset($_GET["status_z"]) ? '&status_z=' . $_GET["status_z"] : '') . "&offset=" . ($offset + 1) . "'>След.</a>";
            }
            $pegination .= '</div><br /><br />';
            if ($count_pegination == 1)
                $pegination = "";
        }
        $profil .= microtime() . "  - AFTER construct pegenation" . "\r\n";
        $table = $bg = "";
        if (!empty($tasks)) {
            foreach ($tasks as $value) {
                switch ($value["type_task"]) {
                    case 0: $type = "Статья";
                        break;
                    case 1: $type = "Обзор";
                        break;
                    case 2: $type = "Новость";
                        break;
                }
                $status = "Активен";
                //if (@$uid == 1) {
                if ($value['vipolneno']) {
                    $bg = '#83e24a';
                    $status = "Завершен";
                } else if ($value['vrabote']) {
                    $bg = '#00baff';
                    $status = "В работе";
                } else if ($value['vilojeno']) {
                    $bg = '#b385bf';
                    $status = "Выложено";
                } else if ($value['navyklad']) {
                    $bg = '#ffde96';
                    $status = "Готов";
                } else if ($value['dorabotka']) {
                    $bg = '#f6b300';
                    $status = "На доработке";
                } else {
                    $bg = '';
                    $status = "Активен";
                }
                if ($value['rectificate']) {
                    $bg = '#999';
                    $status = "Отклонен";
                }
                //}

                $tr = "<tr style='background:$bg'>";
                if (empty($value["task_id"]) && !empty($value["copywriter"])) {
                    $tr .= "<td>" . $value["login"] . "</td>";
                } elseif (!empty($value["task_id"])) {
                    $tr .= "<td>" . $value["task_id"] . "</td>";
                } elseif ((empty($value["task_id"]) && empty($value["copywriter"]))) {
                    if ($value["for_copywriter"] == 1)
                        $tr .= "<td>" . "<input class='chbox-for-copywriter' type=checkbox value='" . $value["id"] . "' checked>" . "</td>";
                    else
                        $tr .= "<td>" . "<input class='chbox-for-copywriter' type=checkbox value='" . $value["id"] . "'>" . "</td>";
                } else {
                    $tr .= "<td></td>";
                }
                $tr .= "<td>" . mb_substr($value["tema"], 0, 50) . "</td>";
                $tr .= "<td>" . $value["nof_chars"] . "</td>";
                $tr .= "<td>" . $type . "</td>";
                $tr .= "<td>" . date("Y-m-d", $value["date"]) . "</td>";
                $tr .= "<td>" . $status . "</td>";
                if ($value["new_comment"] == 1 || (empty($value["task_id"]) && !empty($value["copywriter"]))) {
                    if (empty($value["task_id"]) && !empty($value["copywriter"])) {
                        $chat = $db->Execute("SELECT * FROM chat_admin_copywriter WHERE uid != " . $uid . " AND status=0 AND zid='" . $value["id"] . "' LIMIT 1")->FetchRow();
                        if (!empty($chat))
                            $tr .= "<td class='state processed' style='padding-top:15px'><span class='ico'></span></td>";
                        else
                            $tr .= "<td></td>";
                    } else {
                        $tr .= "<td class='state processed' style='padding-top:15px'><span class='ico'></span></td>";
                    }
                } else {
                    $tr .= "<td></td>";
                }
                $tr .= "<td class='edit'><a href='?module=admins&action=articles&action2=edit&id=" . $value["id"] . "' class='ico'></a></td>";
                $tr .= "</tr>";
                $table .= $tr;
            }
        }
        $profil .= microtime() . "  - AFTER OUTPUT ALL TASKS" . "\r\n";

        $content = str_replace('[pegination]', $pegination, $content);
        $content = str_replace('[cur_url]', "?module=admins&action=articles" . ($offset != 1 ? '&offset' . $offset : ''), $content);
        $content = str_replace('[table]', $table, $content);
        $content = str_replace('[check_all]', "style='display:none'", $content);
        $profil .= microtime() . "  - END FUNCTION" . "\r\n";
        $endtime = time() - $starttime;
        if ($endtime > 3) {
            $profil .= "ALL TIME - " . $endtime;
            $file = 'temp_file/view_sape/' . $endtime . '-' . '(' . time() . ').txt';
            file_put_contents($file, $profil);
        }
        return $content;
    }

    function article_edit($db) {
        $starttime = time();
        $profil = "";
        $profil .= time() . "\r\n";
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/article_edit.tpl');
        $error = isset($_REQUEST['error']) ? $_REQUEST['error'] : null;
        if (!empty($error)) {
            $query_p = implode(' \r\n ', $error);
            $content = str_replace('[error]', $query_p, $content);
        } else
            $content = str_replace('[error]', "", $content);

        $send = @$_REQUEST['send'];
        $send_task = (isset($_REQUEST['send_sape']) && !empty($_REQUEST['send_sape'])) ? true : false;
        $id = (int) $_REQUEST['id'];
        $task = $db->Execute("SELECT * FROM zadaniya_new WHERE id=$id")->FetchRow();

        if (!$send) {
            $profil .= microtime() . "  - NO SEND" . "\r\n";
            $_SESSION["HTTP_REFERER"] = "";
            if (isset($_SERVER["HTTP_REFERER"])) {
                $_SESSION["HTTP_REFERER"] = $_SERVER["HTTP_REFERER"];
                $pos = strripos($_SESSION["HTTP_REFERER"], "status_z=");
                if (!empty($pos)) {
                    $pos = $pos + 9;
                    $status_z = substr($_SESSION["HTTP_REFERER"], $pos);

                    $pos_offset = strripos($status_z, "&");
                    if (!empty($pos)) {
                        $status_z = substr($status_z, 0, -$pos_offset);
                    }
                    $_SESSION["sort"] = $status_z;
                }
            }

            $content = str_replace('[zid]', $id, $content);
            $check_result = true;

            if ($task['rectificate']) {
                $task['rectificate'] = 'checked';
                $content = str_replace('[activen]', 'disabled', $content);
                $content = str_replace('[vrabote]', 'disabled', $content);
                $check_result = false;
            } else {
                $task['rectificate'] = '';
            }

            if ($task['vrabote']) {
                $task['vrabote'] = 'checked';
                $content = str_replace('[activen]', 'disabled', $content);
            } else {
                $task['vrabote'] = '';
            }
            if ($task['dorabotka'] && $task['rework'] == 0) {
                $task['dorabotka'] = 'checked';
                $content = str_replace('[activen]', 'disabled', $content);
            } else {
                $task['dorabotka'] = '';
                $content = str_replace("[display_dorabotka]", 'style="display:none"', $content);
            }
            if ($task['rework']) {
                $task['rework'] = 'checked';
                $content = str_replace('[activen]', 'disabled', $content);
            } else {
                $content = str_replace("[display_rework]", 'style="display:none"', $content);
            }
            if ($task['navyklad']) {
                $task['navyklad'] = 'checked';
                $content = str_replace('[activen]', 'disabled', $content);
            } else {
                $task['navyklad'] = '';
            }
            if ($task['vilojeno']) {
                $task['vilojeno'] = 'checked';
                $content = str_replace('[activen]', 'disabled', $content);
                $content = str_replace("[display_vilojeno]", '', $content);
                //$content = str_replace("[display]", 'style="display:none"', $content);
            } else {
                $content = str_replace("[display_vilojeno]", 'style="display:none"', $content);
            }
            if ($task['vipolneno'] == 0 && $task['dorabotka'] == 0 && $task['vrabote'] == 0 && $task['navyklad'] == 0 && $task['vilojeno'] == 0) {
                $content = str_replace('[activen]', 'checked', $content);
            } else {
                $content = str_replace('[activen]', "", $content);
            }


            $db->Execute("UPDATE zadaniya_new SET new_comment=0 WHERE id=" . $id);
            $content = str_replace('[message]', $task["admin_comments"], $content);
            $content = str_replace('[date]', date("Y-m-d", $task['date']), $content);

            if ((int) $task['type_task'] == 0) {
                $content = str_replace('[type0]', "selected='selected'", $content);
                $content = str_replace('[type1]', "", $content);
                $content = str_replace('[type2]', "", $content);
            } elseif ((int) $task['type_task'] == 1) {
                $content = str_replace('[type0]', "", $content);
                $content = str_replace('[type1]', "selected='selected'", $content);
                $content = str_replace('[type2]', "", $content);
            } else {
                $content = str_replace('[type0]', "", $content);
                $content = str_replace('[type1]', "", $content);
                $content = str_replace('[type2]', "selected='selected'", $content);
            }
            $content = str_replace('[uniq]', ($task['uniq'] != 0) ? $task['uniq'] : 0, $content);


            foreach ($task as $k => $v) {
                $content = str_replace("[$k]", htmlspecialchars($v), $content);
            }

            if (!empty($task["text"]) && ($task['navyklad'] || $task['dorabotka'])) {
                $content = str_replace("[send_task]", '<li><span class="title"></span><input id="send_sape_button" type="button" class="button" value="Отправить в sape" onclick="return false;"></li>', $content);
            } else {
                $content = str_replace("[send_task]", '', $content);
            }
            if ($task['overwrite'])
                $content = str_replace('[over]', "checked", $content);

            if ($task['copywriter'] != 0) {
                $chat = $db->Execute("SELECT ch.*, a.login FROM chat_admin_copywriter ch LEFT JOIN admins a ON ch.uid=a.id WHERE zid='$id' ORDER BY ch.date DESC");
                $message_copywriter = "";
                $message_copywriter_count = 2;
                while ($value = $chat->FetchRow()) {
                    if ($message_copywriter_count < 10)
                        $message_copywriter_count += 2;
                    $message_copywriter .= $value['login'] . " (" . $value['date'] . ")" . "\n";
                    $message_copywriter .= $value['msg'] . "\n\n";
                }
                $message_copywriter = trim($message_copywriter, "\n\n");
                $content = str_replace('[message_copywriter]', $message_copywriter, $content);
                $content = str_replace('[message_copywriter_count]', $message_copywriter_count == 0 ? 2 : $message_copywriter_count, $content);
                $content = str_replace('[display]', "", $content);
                $db->Execute("UPDATE chat_admin_copywriter SET status=1 WHERE uid!='" . $_SESSION["admin"]["id"] . "' AND zid='$id'");
            } else {
                $content = str_replace('[display]', " style='display:none' ", $content);
                $content = str_replace('[display2]', " style='display:none' ", $content);
            }

            $profil .= microtime() . "  - AFTER all str_replace DATA TASK" . "\r\n";
        } else {
            $ankor = mysql_real_escape_string($_REQUEST['ankor']);
            $ankor2 = mysql_real_escape_string($_REQUEST['ankor2']);
            $ankor3 = mysql_real_escape_string($_REQUEST['ankor3']);
            $ankor4 = mysql_real_escape_string($_REQUEST['ankor4']);
            $ankor5 = mysql_real_escape_string($_REQUEST['ankor5']);
            $url = mysql_real_escape_string($_REQUEST['url']);
            $url2 = mysql_real_escape_string($_REQUEST['url2']);
            $url3 = mysql_real_escape_string($_REQUEST['url3']);
            $url4 = mysql_real_escape_string($_REQUEST['url4']);
            $url5 = mysql_real_escape_string($_REQUEST['url5']);
            $tema = ($_REQUEST['tema']);
            $etxt = $_REQUEST['etxt'];
            $task_id = (($_REQUEST['etxt'] == 0) ? "task_id='null'," : "");
            $title = $_REQUEST['title'];
            $keywords = $_REQUEST['keywords'];
            $description = ($_REQUEST['description']);
            $text = ($_REQUEST['text']);
            $comments = mysql_real_escape_string($_REQUEST['comments']);
            $admin_comments = @$_REQUEST['admin_comments'];
            $lay_out = isset($_REQUEST['lay_out']) ? 1 : 0;
            $type_task = (int) $_REQUEST['type'];
            $overwrite = @$_REQUEST['overwrite'];
            $cookie_jar = tempnam(PATH . 'temp', "cookie");
            $result = null;
            $message = mysql_real_escape_string($_REQUEST["message"]);
            $profil .= microtime() . "  - GET data" . "\r\n";
            if (isset($_REQUEST['morework']) && ($_REQUEST['morework'] == 1) && $task['task_id']) {
                $text = $_REQUEST['morework_comment'];
                $profil .= microtime() . "  - BEFORE 1.1 curl" . "\r\n";
                $pass = ETXT_PASS;
                $query_sign = "method=tasks.cancelTasktoken=29aa0eec2c77dd6d06e23b3faaef9eed";
                $sign = md5($query_sign . md5($pass . 'api-pass'));

                $params = array('id' => array($task['task_id']), 'text' => $text);
                $query_p = http_build_query($params);
                $url_etxt = "https://www.etxt.ru/api/json/?method=tasks.cancelTask&token=29aa0eec2c77dd6d06e23b3faaef9eed&sign=" . $sign;
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url_etxt);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $query_p);
                curl_exec($curl);
                curl_close($curl);
                $profil .= microtime() . "  - AFTER 1.1 curl" . "\r\n";
            } else if (isset($_REQUEST['morework']) && ($_REQUEST['morework'] == 0) && $task['task_id']) {
                $pass = ETXT_PASS;
                $query_sign = "method=tasks.paidTasktoken=29aa0eec2c77dd6d06e23b3faaef9eed";
                $sign = md5($query_sign . md5($pass . 'api-pass'));
                $profil .= microtime() . "  - BEFORE 1.2 curl" . "\r\n";
                $params = array('id' => array($task['task_id']));
                $query_p = http_build_query($params);
                $url_etxt = "https://www.etxt.ru/api/json/?method=tasks.paidTask&token=29aa0eec2c77dd6d06e23b3faaef9eed&sign=" . $sign;
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url_etxt);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $query_p);
                curl_exec($curl);
                curl_close($curl);
                $db->Execute("UPDATE zadaniya_new SET vrabote='0', navyklad='1' WHERE id=" . $id);
                $profil .= microtime() . "  - AFTER 1.2 curl AND query UPDATE" . "\r\n";
            }

            $task_status = @$_REQUEST['task_status'];
            $vrabote = $navyklad = $dorabotka = $rework = $rectificate = $vilojeno = 0;
            switch ($task_status) {
                case "vrabote": $vrabote = 1;
                    break;
                case "navyklad": $navyklad = 1;
                    break;
                case "dorabotka": $dorabotka = 1; $rework = $task["rework"];
                    break;
                case "rework": $rework = 1; $dorabotka = $task["dorabotka"];
                    break;
                case "vilojeno": $vilojeno = 1;
                    break;
                case "rectificate": $rectificate = 1;
                    break;
            }

            if ($rectificate == 1 && !empty($message)) {
                $profil .= microtime() . "  - Task = rectificate BEFORE 2 curl - login" . "\r\n";
                if ($curl = curl_init()) {
                    curl_setopt($curl, CURLOPT_URL, "http://api.articles.sape.ru/performer/index/");
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
                    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
                    @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, xmlrpc_encode_request('performer.login', array(LOGIN_IN_SAPE, PASS_IN_SAPE)));
                    $result = curl_exec($curl);
                    curl_close($curl);
                }
                $profil .= microtime() . "  - AFTER 2 curl AND BEFORE 3 curl - orderRectificate" . "\r\n";
                $data = xmlrpc_encode_request('performer.orderRectificate', array(array((int) $task["sape_id"]), $message), array('encoding' => 'UTF-8', 'escaping' => 'markup'));
                if ($curl = curl_init()) {
                    curl_setopt($curl, CURLOPT_URL, "http://api.articles.sape.ru/performer/index/");
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
                    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
                    @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    $ress = curl_exec($curl);
                    curl_close($curl);
                }
                $profil .= microtime() . "  - AFTER 3 curl" . "\r\n";
            }
            $profil .= microtime() . "  - BEFORE UPDATE task" . "\r\n";

            $q = "UPDATE zadaniya_new SET $task_id etxt='$etxt', title='$title', keywords='$keywords', description='" . mysql_real_escape_string($description) . "', rework ='$rework', vrabote='$vrabote', navyklad='$navyklad', rectificate='$rectificate', dorabotka='$dorabotka', type_task='$type_task', text='" . mysql_real_escape_string($text) . "', tema='" . mysql_real_escape_string($tema) . "', ankor='$ankor', ankor2='$ankor2', ankor3='$ankor3', ankor4='$ankor4', ankor5='$ankor5', url='$url', url2='$url2', url3='$url3', url4='$url4', url5='$url5', comments='$comments', admin_comments='$admin_comments', lay_out='$lay_out', overwrite='$overwrite' WHERE id=$id";
            $db->Execute($q);
            $profil .= microtime() . "  - AFTER UPDATE task" . "\r\n";
            if ($send_task) {
                $profil .= microtime() . "  - SEND TASK click!!" . "\r\n";
                if (empty($result)) {
                    $profil .= microtime() . "  - BEFORE 2 curl - login" . "\r\n";
                    if ($curl = curl_init()) {
                        curl_setopt($curl, CURLOPT_URL, "http://api.articles.sape.ru/performer/index/");
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_POST, true);
                        curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
                        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
                        @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, xmlrpc_encode_request('performer.login', array(LOGIN_IN_SAPE, PASS_IN_SAPE)));
                        curl_exec($curl);
                        curl_close($curl);
                    }
                    $profil .= microtime() . "  - AFTER 2 curl" . "\r\n";
                }
                $data = xmlrpc_encode_request('performer.orderComplite', array((int) $task["sape_id"], array("title" => $title, "header" => $tema, "keywords" => $keywords, "description" => $description, "text" => $text)), array('encoding' => 'UTF-8', 'escaping' => 'markup'));
                $profil .= microtime() . "  - BEFORE 3(4) curl - orderComplite - send task in sape" . "\r\n";
                if ($curl = curl_init()) {
                    curl_setopt($curl, CURLOPT_URL, "http://api.articles.sape.ru/performer/index/");
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
                    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
                    @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    $out = curl_exec($curl);
                    curl_close($curl);
                }
                $accept = xmlrpc_decode($out);

                $profil .= microtime() . "  - AFTER 3(4) curl" . "\r\n";
                if ($accept == true && !isset($accept["faultString"])) {
                    $db->Execute("UPDATE zadaniya_new SET vilojeno = '1', navyklad = '0', dorabotka = '0' WHERE id = $id");
                    $profil .= microtime() . "  - TRUE SEND task" . "\r\n";
                } else {
                        
                    $request = $data = array();
                    $errors = json_decode($accept["faultString"]);
                    if(!empty($errors)){
                        foreach ($errors->items as $err_type => $err_arr) {
                            foreach ($err_arr as $key_err => $err) {
                                $request[] = $err;
                            }
                        }
                    }
                    if (empty($request) && isset($accept["faultString"])) {
                        $request[] = $accept["faultString"];
                    }

                    foreach ($request as $err) {
                        if (mb_strpos("В статье не найдены требуемые ссылки", $err)) {
                            $err = "В статье не найдены требуемые ссылки. Добавьте не менее 1 ссылок из списка.";
                        }
                        $data[] = "error[]=" . str_replace('"', "", $err);
                    }
                    $query_p = implode('&', $data);
                    $profil .= microtime() . "  - FALSE SEND TASK!! OUT ERROR" . "\r\n";
                    $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
                    $content = str_replace('[url]', "?module=admins&action=articles&action2=edit&id=" . $id . "&" . $query_p, $content);
                    return $content;
                    die();
                }
            }
            $offset = $status_z = "";
            if (@$_SESSION["HTTP_REFERER"]) {
                $pos = strripos($_SESSION["HTTP_REFERER"], "offset=");
                if (!empty($pos)) {
                    $pos = $pos + 7;
                    $offset = substr($_SESSION["HTTP_REFERER"], $pos);
                    $offset = "&offset=" . $offset;
                }
                unset($_SESSION["HTTP_REFERER"]);
            }
            if (@$_SESSION["sort"]) {
                $status_z = "&status_z=" . @$_SESSION["sort"];
                unset($_SESSION["sort"]);
            }

            $url = "?module=admins&action=articles" . $status_z . $offset;
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
            $content = str_replace('[alert]', 'Задание успешно отредактировано.', $content);
            $content = str_replace('[url]', $url, $content);
            $profil .= microtime() . "  - END SAVE" . "\r\n";
        }
        $profil .= microtime() . "  - END FUNCTION" . "\r\n";

        $endtime = time() - $starttime;
        if ($endtime > 3) {
            $profil .= "ALL TIME - " . $endtime;
            $file = 'temp_file/edit_sape/' . $endtime . '- ' . $id . '(' . time() . ').txt';
            file_put_contents($file, $profil);
        }

        return $content;
    }

    function send_message($db) {
        $_SESSION["HTTP_REFERER"] = @$_SERVER["HTTP_REFERER"];
        $message = $_REQUEST["message"];
        $sape_id = $_REQUEST["sape_id"];
        if (!empty($message) && !empty($sape_id)) {
            $cookie_jar = tempnam(PATH . 'temp', "cookie");
            if ($curl = curl_init()) {
                curl_setopt($curl, CURLOPT_URL, "http://api.articles.sape.ru/performer/index/");
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
                curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
                @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, xmlrpc_encode_request('performer.login', array(LOGIN_IN_SAPE, PASS_IN_SAPE)));
                $id = curl_exec($curl);
                curl_close($curl);
            }
            if (!empty($id)) {
                $data = xmlrpc_encode_request('performer.messageSend', array((int) $sape_id, $message), array('encoding' => 'UTF-8', 'escaping' => 'markup'));
                if ($curl = curl_init()) {
                    curl_setopt($curl, CURLOPT_URL, "http://api.articles.sape.ru/performer/index/");
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
                    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
                    @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    $out = curl_exec($curl);
                    curl_close($curl);
                }
                $res = xmlrpc_decode($out);
            }
        }
        header('location:' . $_SERVER['HTTP_REFERER']);
        die();
    }

    function order_recomplite($db) {
        $_SESSION["HTTP_REFERER"] = @$_SERVER["HTTP_REFERER"];
        $id = $_REQUEST["id"];
        $sape_id = $_REQUEST["sape_id"];
        $error = "";
        if (!empty($sape_id)) {
            $cookie_jar = tempnam(PATH . 'temp', "cookie");
            if ($curl = curl_init()) {
                curl_setopt($curl, CURLOPT_URL, "http://api.articles.sape.ru/performer/index/");
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
                curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
                @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, xmlrpc_encode_request('performer.login', array(LOGIN_IN_SAPE, PASS_IN_SAPE)));
                $user_id = curl_exec($curl);
                curl_close($curl);
            }

            if (!empty($user_id)) {
                $data = xmlrpc_encode_request('performer.orderRecomplite', array((int) $sape_id));
                if ($curl = curl_init()) {
                    curl_setopt($curl, CURLOPT_URL, "http://api.articles.sape.ru/performer/index/");
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
                    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
                    @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    $out = curl_exec($curl);
                    curl_close($curl);
                }
                $res = xmlrpc_decode($out);

                if (isset($res["faultString"]) && !empty($res["faultString"])) {
                    $faultString = json_decode($res["faultString"]);
                    $faultString = (array) $faultString;
                    if (!empty($faultString)) {
                        $text = $faultString['text'];
                        $error = "&error[]=" . $text;
                    }
                }
                if ($error == "") {
                    $db->Execute("UPDATE zadaniya_new SET vipolneno = '0', vilojeno = '1', vrabote = '0', navyklad = '0', dorabotka = '0' WHERE id = $id");
                }
            }
        }
        header('location:' . $_SERVER['HTTP_REFERER'] . $error);
        die();
    }

    function send_message_copywriter($db) {
        if (!@$_SESSION['admin']['id'] && !@$_SESSION['manager']['id']) {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/no-rights.tpl');
            $content = str_replace('[url]', $_SERVER["HTTP_REFERER"], $content);
            echo $content;
            exit();
        }
        $id = $_REQUEST['id'];
        $uid = isset($_SESSION['admin']['id']) ? $_SESSION['admin']['id'] : $_SESSION['manager']['id'];
        if (isset($_REQUEST['id'])) {
            $burse = 1;
        } else {
            $burse = 0;
        }
        $msg = $_REQUEST['message_copywriter'];
        $date = date("Y-m-d H:i:s");
        if (!empty($uid) && !empty($id) && !empty($msg)) {
            $db->Execute("INSERT INTO chat_admin_copywriter (uid, zid, msg, date, status, burse) VALUE ('$uid', '$id', '$msg', '$date', 0, $burse)");
            $copywriter = $db->Execute("SELECT a.* FROM admins a LEFT JOIN zadaniya_new z ON z.copywriter=a.id WHERE a.type = 'copywriter' AND z.id=$id")->FetchRow();
            if ($copywriter["mail_period"] > 0) {
                $this->_postman->copywriter->sendMessage($id, $copywriter, $msg);
            }
        }
        header('Location:' . $_SERVER['HTTP_REFERER']);
    }

    function articles_to_etxt($db) {
        $error = "";
        $num = 0;
        $tasks = $db->Execute("SELECT * FROM zadaniya_new WHERE 
                                        rectificate=0 AND 
                                        vipolneno=0 AND 
                                        from_sape=1 AND 
                                        for_copywriter=0 AND 
                                        (task_id = 0 OR task_id IS NULL) AND 
                                        etxt=0 AND 
                                        copywriter=0");
        $cena = 20;
        $order_accept = array();
        while ($task = $tasks->FetchRow()) {
            $colvos = $task["nof_chars"];
            $tema = $task['tema'];
            if (empty($tema)) {
                continue;
            }

            $description = '1)Cтатья ' . $colvos . ' символов без пробелов, в тексте должн[mn] быть фраз[mn] "[ankor]",[ankor2][ankor3][ankor4][ankor5] заключенная в {} 
                            2)Фраза должна быть употреблена в точности как написана, разрывать другими словами ее нельзя, склонять так же нельзя. Если указано несколько фраз через запятую, то нужно их равномерно распределить по тексту 
                            3)Текст без воды, строго по теме, без негатива (см. прикрепленный файл "Текст заказа") 
                            4)Фразу употребить ТОЛЬКО ОДИН раз, в остальном - заменять синонимами 
                            5)Высылать готовый заказ просто текстом, в формате word не принимаем
                            6)Вручную проверить уникальность текста по Адвего Плагиатус (выше 95%), в Комментариях к заказу проставить % уникальности по данной программе. Без этого пункта автоматически задание отправляется на доработку.
                            7)После того как заказ будет принят и оплачен все авторские права принадлежат аккаунту ifoget.ru (то есть статьи могут быть опубликованы на сайтах под различным именем, на выбор владельца текста).
                            8)Тексты писать только на русском языке.';

            $description = str_replace('[ankor]', $task['ankor'], $description);
            if (!empty($task['ankor2']))
                $description = str_replace('[ankor2]', ' "' . $task['ankor2'] . '",', $description);
            else
                $description = str_replace('[ankor2]', "", $description);
            if (!empty($task['ankor3']))
                $description = str_replace('[ankor3]', ' "' . $task['ankor3'] . '",', $description);
            else
                $description = str_replace('[ankor3]', "", $description);
            if (!empty($task['ankor4']))
                $description = str_replace('[ankor4]', ' "' . $task['ankor4'] . '",', $description);
            else
                $description = str_replace('[ankor4]', "", $description);
            if (!empty($task['ankor5']))
                $description = str_replace('[ankor5]', ' "' . $task['ankor5'] . '",', $description);
            else
                $description = str_replace('[ankor5]', "", $description);

            if (!empty($task['ankor2']) || !empty($task['ankor3']) || !empty($task['ankor4']) || !empty($task['ankor5'])) {
                $description = str_replace('[mn]', "ы", $description);
            } else
                $description = str_replace('[mn]', "а", $description);


            $time = time() + 86400;
            $date = date("d.m.y", $time);
            $id = $task['id'];
            if (!empty($task["price"])) {
                $cena = $task["price"];
            }

            $howto = '
		Здесь представлены основные требования к работе авторов. 

		Общие требования к тексту

		Соответствие тематике (НЕ фразы, а именно тематике), если прямо не указано, что можно писать по теме фразы 
		Максимальное совмещение тематики и темы фразы (например, фраза «украшения» прекрасно впишется в женскую тематику и необходимо писать в этом случае ОБЯЗАТЕЛЬНО о женских украшениях, а не о народном противостоянии феминисток в Палестине).
		Текст должен быть уникальным (минимальная уникальность 95%). 
		Текст должен иметь смысловую нагрузку. То есть несущим какую-то полезную информацию для читателей законченный рассказ, с четким и понятным изложением какого-то факта или события, совет, инструкция или рекомендация.
		Информация ОБЯЗАТЕЛЬНО должна быть правдивой.
		Предложения должны быть связаны между собой по смыслу. 
		Фраза должна входить в текст естественно (по смыслу, числу и падежу),или её нужно употребить, не изменяя ничего.Это прописано рядом с фразой, пример: в тексте должна быть фраза "www.altaystroy.ru (склонять анкор нельзя )"
		В тексте не должно быть грамматических и пунктуационных ошибок.
		Нельзя писать от первого лица и от лица компании (мы изготовим для вас, в нашей компании). Все текста пишутся только от третьего лица.


		Оформление

		Текст необходимо делить на абзацы и выделять подзаголовки.
		Нельзя употреблять в тексте смайлы и множество восклицательных и вопросительных знаков.


		О тематике

		Нужно избегать любых негативных тем - Придаем тексту нейтральную или положительную окраску.
 

		О вписывании фразы в тест

		НЕЛЬЗЯ менять заглавные буквы на строчные и наоборот, нельзя его склонять и изменять. Лучше не переписывать фразу, а скопировать и вставить в текст.
		Фраза должна входить в текст естественно. НЕЛЬЗЯ писать: Ухаживать за лицом [матрасы] нужно ежедневно. Мы не примем такую работу. 
		Если в задании фраза, например, "свадебные платья", а вам нужно написать что-то на тему "Советы хозяйке" НЕЛЬЗЯ писать о туалетных ершиках или борьбе с тараканами и всовывать кое-как фразу. 
		НЕЛЬЗЯ придавать фразе негативную окраску. Пишем о нем либо нейтрально, либо ненавязчиво рекомендуем читателю товар, услугу и т.д.
		Фраза должна иметь тематическое окружение (если фраза "диваны", необходимо употребить рядом слова, например, мебель, интерьер спальня). 


		О несоответствии темы и фразы

		Если тематику невозможно аккуратно совместить с темой фразы и логически она никак в нее не вписывается, следует писать текст по тематике. А в конце текста написать 2-3 полноценных предложения о фразе и вставив фразу. 
		В таких случаях пишем текст, а в конце дописываем 2-3 полноценных предложения, не относящихся к тексту, именно по теме ключа. 
		Фраза должна иметь тематическое окружение. После фразы нужно дописать еще несколько слов или предложение.
		НЕЛЬЗЯ фразой заканчивать текст.


		Текст на широкую тематику

		Даже если вы «в теме» не стоит выдумывать что-то самостоятельно. Основывайтесь на реальных фактах.
		Не используйте избитые фразы и выражения - текст короткий и уникальность сразу упадет. 
		Смотрите информацию о фразе, особенно если это слово вам незнакомо. Если фраза, например, монурал (название лекарства) НЕЛЬЗЯ писать, что это модная прическа или деталь трактора.
	';

            $category_id = 1828;
            $pass = ETXT_PASS;                        //29aa0eec2c77dd6d06e23b3faaef9eed
            $query_sign = "method=tasks.saveTasktoken=29aa0eec2c77dd6d06e23b3faaef9eed";
            $sign = md5($query_sign . md5($pass . 'api-pass'));

            $params = array('auto_work' => 1,
                'id_category' => $category_id,
                'deadline' => $date,
                'description' => $description,
                'multitask' => 0,
                'id_type' => 1,
                'only_stars' => 0,
                'price' => $cena,
                'price_type' => 1,
                'public' => 1,
                'target_task' => 1,
                'text' => $howto,
                'timeline' => '17:00',
                'title' => $tema,
                'whitespaces' => 0,
                'auto_level' => 1,
                'auto_rate' => 100,
                'size' => $colvos,
                'uniq' => 95);

            $query_p = $params;

            $url_etxt = "https://www.etxt.ru/api/json/?method=tasks.saveTask&token=29aa0eec2c77dd6d06e23b3faaef9eed&sign=" . $sign;

            $not_copywriter = $db->Execute("SELECT copywriter FROM zadaniya_new WHERE id = " . $task['id'])->FetchRow();
            if ($not_copywriter['copywriter'] == 0) {
                if ($curl = curl_init()) {
                    curl_setopt($curl, CURLOPT_URL, $url_etxt);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $query_p);
                    $out = curl_exec($curl);
                    curl_close($curl);
                }
                $new_tid = json_decode($out);
                unset($description);

                $new_tid_arr = (array) $new_tid;
                $id_task = isset($new_tid_arr['id_task']) ? $new_tid_arr['id_task'] : null;

                if (isset($id_task)) {
                    $order_accept[] = $task["sape_id"];
                    $db->Execute("UPDATE zadaniya_new SET task_id = '" . $id_task . "', etxt = 1, vrabote = 1, dorabotka = 0 WHERE id = '" . $id . "'");
                    $num++;
                } else {
                    //print_r($query_p);
                    $error .= 'Задание ' . $task["id"] . ' не добавилось в ETXT! Error: ' . $new_tid_arr["error"] . '<br>';
                }
            } else {
                $copywriter = $db->Execute("SELECT * FROM admins WHERE id = " . $not_copywriter['copywriter'])->FetchRow();
                $error .= 'Задание ' . $task["id"] . ' уже взял себе копирайтер ' . $copywriter["login"] . '! Задача уже в статусе `В работе`.<br>';
            }
        }
        $accept = null;
        if (!empty($order_accept)) {
            $cookie_jar = tempnam(PATH . 'temp', "cookie");
            if ($curl = curl_init()) {
                curl_setopt($curl, CURLOPT_URL, "http://api.articles.sape.ru/performer/index/");
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
                curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
                @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, xmlrpc_encode_request('performer.login', array(LOGIN_IN_SAPE, PASS_IN_SAPE)));
                curl_exec($curl);
                curl_close($curl);
            }

            $data = xmlrpc_encode_request('performer.orderAccept', array($order_accept));
            if ($curl = curl_init()) {
                curl_setopt($curl, CURLOPT_URL, "http://api.articles.sape.ru/performer/index/");
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
                curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
                @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                $out = curl_exec($curl);
                curl_close($curl);
            }
            $accept = xmlrpc_decode($out);
        }
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/articles_to_etxt.tpl');
        if (!empty($error) && $error != "") {
            $content = str_replace('[error]', "<p class='error_to_etxt'>" . $error . "</p>", $content);
        } else {
            $content = str_replace('[error]', '', $content);
        }

        $content = str_replace('[num]', count($order_accept), $content);

        return $content;
    }

    function tasks($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/tasks.tpl');
        $status = $_REQUEST['status'];

        $cur_sites = $db->Execute("SELECT s.id FROM sayty s LEFT JOIN admins a ON s.uid = a.id WHERE a.type != 'admin' AND a.active = 1");
        $sids = array();
        while ($vs = $cur_sites->FetchRow()) {
            $sids[] = $vs['id'];
        }
        $sids = "(" . implode(",", $sids) . ")";

        $tasks = NULL;
        if ($status == "vrabote") {
            $tasks = $db->Execute("SELECT * FROM zadaniya WHERE vrabote=1 AND etxt=1 AND (vipolneno=0 or vipolneno IS NULL) AND (dorabotka=0 or dorabotka IS NULL) AND (navyklad=0 or navyklad IS NULL) AND (vilojeno=0 or vilojeno IS NULL) AND sid IN " . $sids);
            $new_s = "working";
            $bg = '#00baff';
            $status = "В работе";
        } else if ($status == "navyklad") {
            $tasks = $db->Execute("SELECT * FROM zadaniya WHERE navyklad=1 AND sid IN " . $sids);
            $new_s = "ready";
            $bg = '#ffde96';
            $status = "На выкладывании";
        } else if ($status == "neobrabot") {
            $tasks = $db->Execute("SELECT * FROM zadaniya WHERE (vrabote=0 or vrabote IS NULL) AND (vipolneno=0 or vipolneno IS NULL) AND (dorabotka=0 or dorabotka IS NULL) AND (navyklad=0 or navyklad IS NULL) AND (vilojeno=0 or vilojeno IS NULL) AND sid IN " . $sids);
            $new_s = "";
            $bg = '#fff';
            $status = "Не обработанно";
        } else if ($status == "vilojeno") {
            $tasks = $db->Execute("SELECT * FROM zadaniya WHERE vilojeno=1 AND sid IN " . $sids);
            $new_s = "vilojeno";
            $bg = '#b385bf';
            $status = "Выложено";
        }
        $zadaniya = "";
        while ($task = $tasks->FetchRow()) {
            $zadaniya .= file_get_contents(PATH . 'modules/admins/tmp/admin/task_one.tpl');
            $zadaniya = str_replace('[url]', (substr(substr($task['url'], strpos($task['url'], "http")), 0, 30)), $zadaniya);
            $zadaniya = str_replace('[id]', $task['id'], $zadaniya);
            $zadaniya = str_replace('[etxt_id]', $task['task_id'], $zadaniya);
            $zadaniya = str_replace('[date]', date('d.m.Y', $task['date']), $zadaniya);
            $zadaniya = str_replace('[tema]', mb_substr($task['tema'], 0, 50), $zadaniya);
            $zadaniya = str_replace('[uid]', $task['uid'], $zadaniya);
            $zadaniya = str_replace('[sid]', $task['sid'], $zadaniya);
            $zadaniya = str_replace('[status]', $new_s, $zadaniya);
            $zadaniya = str_replace('[bg]', 'style="background:' . $bg . '"', $zadaniya);
        }
        $content = str_replace('[status]', $status, $content);
        $content = str_replace('[zadaniya]', $zadaniya, $content);
        return $content;
    }

    function articles_statistics($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/articles_statistics.tpl');

        if (isset($_REQUEST["filter"])) {
            $content = str_replace('[name_col]', "Неделя", $content);
        } else {
            $content = str_replace('[name_col]', "Число", $content);
        }
        $mn = array("Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь");
        $stat_day = $stat_week = array();
        $tasks = $db->Execute("SELECT * FROM zadaniya_new WHERE id > 137 AND etxt=1 ORDER BY date ASC");
        $all = 0;
        while ($task = $tasks->FetchRow()) {
            $date = getdate($task["date"]);
            if (!isset($stat_day[$date["year"]][$date["mon"]][$date["mday"]])) {
                $stat_day[$date["year"]][$date["mon"]][$date["mday"]] = 0;
            }
            if (!isset($stat_week[$date["year"]][$date["mon"]][$date["wday"]])) {
                $stat_week[$date["year"]][$date["mon"]][$date["wday"]] = 0;
            }
            $sum = $task["price"] * ($task["nof_chars"] / 1000 );
            $sum += ($sum / 100) * 5;
            $all += $sum;
            $stat_day[$date["year"]][$date["mon"]][$date["mday"]] += $sum;
            $stat_week[$date["year"]][$date["mon"]][$date["wday"]] += $sum;
        }
        /* if($_SERVER["REMOTE_ADDR"] =="37.144.53.90"){
          print_r($stat_day);
          } */
        $stat = "";
        $statistics = isset($_REQUEST["filter"]) ? $stat_week : $stat_day;
        foreach ($statistics as $name_year => $year) {
            foreach ($year as $mounth_name => $month) {
                $stat .= "<tr style='background:#f0f0ff;'><td colspan='3'>" . $mn[$mounth_name - 1] . " " . $name_year . "</td></tr>";
                $sum_mount = $num = 0;
                $mm = "";
                foreach ($month as $day_week => $summa) {
                    $num++;
                    $mm .= "<tr>";
                    $mm .= "<td>" . $day_week . "</td>";
                    $mm .= "<td>" . $summa . "</td>";
                    if ($num == 1) {
                        $mm .= "<td rowspan='[col_num]' style='background:rgb(247, 247, 247)'>[insert_mounth_summa]</td>";
                    }
                    $mm .= "</tr>";
                    $sum_mount += $summa;
                }
                $mm = str_replace('[insert_mounth_summa]', $sum_mount, $mm);
                $mm = str_replace('[col_num]', $num + 1, $mm);
                $stat .= $mm;
            }
        }
        $content = str_replace('[stat]', $stat, $content);

        return $content;
    }

    function users_non_active($db) {
        if (!@$_SESSION['admin']['id'] && !@$_SESSION['manager']['id']) {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/no-rights.tpl');
            $content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
            echo $content;
            exit;
        }
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/non_active_users.tpl');
        $table = "";
        $admins = $db->Execute("SELECT * FROM `admins` WHERE type = 'user' ORDER BY login ASC");
        while ($user = $admins->FetchRow()) {
            $orders = $db->Execute("SELECT * FROM orders WHERE status=1 AND price!=0 AND uid = " . $user["id"])->FetchRow();
            $birjs = $db->Execute("SELECT * FROM birjs WHERE uid = " . $user["id"])->FetchRow();
            $sayty = $db->Execute("SELECT * FROM orders WHERE uid = " . $user["id"])->FetchRow();
            if (
                    (!isset($orders["id"]) OR empty($orders["id"])) OR ( !isset($birjs["bid"]) OR empty($birjs["bid"])) OR ( !isset($sayty["id"]) OR empty($sayty["id"]))
            ) {
                $table .= "<tr><td><a href='?module=admins&action=edit&uid=" . $user["id"] . "'>" . $user["login"] . "</a></td><td>" . $user["email"] . "</td></tr>";
            }
        }

        if ($table == "") {
            $content = str_replace('[table]', "<tr><td colspan='2'>Нет неактивных пользователей</td></tr>", $content);
        } else {
            $content = str_replace('[table]', $table, $content);
        }

        return $content;
    }

    function send_mail_users_non_active($db) {
        if (@$_SESSION['admin']['type'] != 'admin') {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
            $content = str_replace('[alert]', 'Данное действие Вам недоступно', $content);
            $content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
            echo $content;
            exit;
        }
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/send_mail_users_non_active.tpl');

        $non_active_users = array();
        $to = array();
        $admins = $db->Execute("SELECT * FROM `admins` WHERE type = 'user' ORDER BY login ASC");
        while ($user = $admins->FetchRow()) {
            $orders = $db->Execute("SELECT * FROM orders WHERE status=1 AND price!=0 AND uid = " . $user["id"])->FetchRow();
            $birjs = $db->Execute("SELECT * FROM birjs WHERE uid = " . $user["id"])->FetchRow();
            $sayty = $db->Execute("SELECT * FROM orders WHERE uid = " . $user["id"])->FetchRow();
            if (
                    (!isset($orders["id"]) OR empty($orders["id"])) OR ( !isset($birjs["bid"]) OR empty($birjs["bid"])) OR ( !isset($sayty["id"]) OR empty($sayty["id"]))
            ) {
                $non_active_users[$user["id"]] = $user;
                $to[] = array("email" => $user["email"], "name" => $user["login"]);
            }
        }
        $this->_postman->user->priceIncrease($to);

        $body = "Письмо о поднятии цен отправлено на эти почтовые ящики: <br/><br/>";
        foreach ($non_active_users as $value) {
            $body .= $value["id"] . "<br/>";
        }
        $this->_postman->admin->priceIncreaseUsers($non_active_users);
        $content = str_replace('[body_mail]', $body, $content);
        return $content;
    }

    function users_non_active_in_excel($db) {
        if (@$_SESSION['admin']['type'] != 'admin') {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
            $content = str_replace('[alert]', 'Данное действие Вам недоступно', $content);
            $content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
            echo $content;
            exit;
        }
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/non_active_users.tpl');
        require_once 'includes/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("iforget")->setTitle("Non active users");
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Login')
                ->setCellValue('B1', 'Email');

        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()->setRGB('b9cde4');
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getfont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);

        $admins = $db->Execute("SELECT * FROM `admins` WHERE type = 'user' ORDER BY login ASC");
        $row = 1;
        while ($user = $admins->FetchRow()) {
            $orders = $db->Execute("SELECT * FROM orders WHERE status=1 AND price!=0 AND uid = " . $user["id"])->FetchRow();
            $birjs = $db->Execute("SELECT * FROM birjs WHERE uid = " . $user["id"])->FetchRow();
            $sayty = $db->Execute("SELECT * FROM orders WHERE uid = " . $user["id"])->FetchRow();
            if (
                    (!isset($orders["id"]) OR empty($orders["id"])) OR ( !isset($birjs["bid"]) OR empty($birjs["bid"])) OR ( !isset($sayty["id"]) OR empty($sayty["id"]))
            ) {
                $row++;
                $objPHPExcel->getActiveSheet()->getStyle("A$row:B$row")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $row, $user["login"])
                        ->setCellValue('B' . $row, $user["email"]);
            }
        }
        $objPHPExcel->getActiveSheet()->getStyle("A1:A$row")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("B1:B$row")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("A2:B$row")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);




        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $file_name = __DIR__ . '/../../files/' . time() . '.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . basename($file_name) . '"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
        exit();
    }

    function send_mail_users_bl_etxt($db) {
        if (!@$_SESSION['admin']['id'] && !@$_SESSION['manager']['id']) {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/no-rights.tpl');
            $content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
            echo $content;
            exit;
        }
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/users_bl_etxt.tpl');

        $params = array('method' => 'tasks.listTasks', 'only_id' => 1, 'status' => '1', 'token' => ETXT_TOKEN);
        $data = array();
        $data2 = array();
        foreach ($params as $k => $v) {
            $data[] = $k . '=' . $v;
            $data2[] = $k . '=' . urlencode($v);
        }
        $sign = md5(implode('', $data) . md5(ETXT_PASS . 'api-pass'));
        $url = 'https://www.etxt.ru/api/json/?' . implode('&', $data2) . '&sign=' . $sign;
        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $out = curl_exec($curl);
            curl_close($curl);
        }
        $etxt_list = json_decode($out);
        if (count((array) $etxt_list)) {
            if ($curl = curl_init()) {
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                $out = curl_exec($curl);
                curl_close($curl);
            }
            $etxt_list = json_decode($out);
        }

        if (count((array) $etxt_list) != 0) {
            $text = "Добрый день!\r\n";
            $text .= "У нас есть задачи, которые Вас могут заинтересовать!\r\n";
            $text .= "Вот список ссылок на задачи:\r\n\r\n";
            foreach ($etxt_list as $task) {
                $text .= "https://www.etxt.ru/admin.php?mod=tasks&lib=task&act=view&id_task=" . $task->id;
                $text .= "\r\n";
            }
            $text .= "\r\nСпасибо!";
        } else {
            $text = "Нет задач в ожидании!\r\n";
        }
        $content = str_replace('[message]', $text, $content);
        $content = str_replace('[num_tasks]', count((array) $etxt_list), $content);
        return $content;
    }

    public function verify_compliance_deadlines_order($db) {
        if (!@$_SESSION['admin']['id'] && !@$_SESSION['manager']['id']) {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/no-rights.tpl');
            $content = str_replace('[url]', $_SERVER['HTTP_REFERER'], $content);
            echo $content;
            exit;
        }
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/verify_compliance_deadlines_order.tpl');
        /* $pass = ETXT_PASS;
          $tasks = $db->Execute("SELECT * FROM zadaniya WHERE vrabote = 1 AND vipolneno != 1");
          $table = "";
          while ($row = $tasks->FetchRow()) {
          $params = array('method' => 'tasks.listTasks', 'token' => '29aa0eec2c77dd6d06e23b3faaef9eed', 'id' => $row['task_id']);
          ksort($params);
          $data = array();
          $data2 = array();
          foreach ($params as $k => $v) {
          $data[] = $k . '=' . $v;
          $data2[] = $k . '=' . urlencode($v);
          }
          $sign = md5(implode('', $data) . md5($pass . 'api-pass'));
          $url = 'https://www.etxt.ru/api/json/?' . implode('&', $data2) . '&sign=' . $sign;
          if ($curl = curl_init()) {
          curl_setopt($curl, CURLOPT_URL, $url);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
          $cur_out = curl_exec($curl);
          curl_close($curl);
          }

          $task_stat = json_decode($cur_out);
          if (!empty($task_stat)) {
          foreach ($task_stat as $vl) {
          $vl = (array) $vl;
          if ($vl['status'] == 5)
          {
          $table .= "<tr></tr>";
          }
          }
          }

          } */

        return $content;
    }

    public function executeRequest($method, $url, $useragent, $cookie, $query, $body, $header) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        if (count($query) > 0) {
            $url = $url . '&' . http_build_query($query);
        }
        if ($cookie) {
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        }
        if ($useragent) {
            curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        }
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
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