<?php

session_start();
error_reporting(E_ALL);
include('config.php');
include 'includes/adodb5/adodb.inc.php';

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');
//$db->debug = true;
//загружаем шаблон

if ($_SESSION['user']['id'] > 0) {
    if ($_SESSION['user']['type'] == "user")
        $content1 = file_get_contents(PATH . 'admin_tmp/inside_user.tpl');
    else
        $content1 = file_get_contents(PATH . 'admin_tmp/inside.tpl');
}
else
    $content1 = file_get_contents(PATH . 'admin_tmp/register.tpl');


include 'modules/user/user_class.php';
include 'modules/search/search_class.php';
$search = new search();
$ruser = new user();
if (isset($_REQUEST['logout']))
    $ruser->logout();
$content = '';
$sform = '';

if (!$_SESSION['user']) {
    $ruser->login($db);
    exit;
} else {
    $_REQUEST['uid'] = $_SESSION['user']['id'];
    $user_content = file_get_contents(PATH . 'modules/user/tmp/logined.tpl');

    $sform = $search->form();
    $user_content = str_replace('[login]', $_SESSION['user']['login'], $user_content);
    
    if ($_SESSION['user']['type'] == "user") {
        
        switch (@$_REQUEST['action']) {
            case '':
                $user_content .=$ruser->sayty_user($db);
                break;

            case 'logout':
                $user_content .=$ruser->logout($db);
                break;

            case 'sayty':
                switch (@$_REQUEST['action2']) {
                    case '':
                        $user_content .=$ruser->sayty($db);
                        break;

                    case 'add':
                        $user_content .=$ruser->sayty_add($db);
                        break;

                    case 'edit':
                        $user_content .=$ruser->sayty_edit($db);
                        break;

                    case 'del':
                        $user_content .=$ruser->sayty_del($db);
                        break;
                    
                    case 'load_ggl':
                        $user_content .= $ruser->sayty_load_ggl($db);
                        break;
                    case 'load_getgoodlinks':
                        $user_content .= $ruser->sayty_load_getgoodlinks($db);
                        break;
                    case 'load_sape':
                        $user_content .= $ruser->sayty_load_sape($db);
                        break;
                }
                break;

            case 'zadaniya':
                switch (@$_REQUEST['action2']) {
                    case '':
                        $user_content .=$ruser->zadaniya($db);
                        break;

                    case 'add':
                        $user_content .=$ruser->zadaniya_add($db);
                        break;

                    case 'edit':
                        $user_content .=$ruser->zadaniya_edit_user($db);
                        break;

                    case 'del':
                        $user_content .=$ruser->zadaniya_del_user($db);
                        break;
                }
                break;

            case 'birj':
                switch (@$_REQUEST['action2']) {
                    case '':
                        $user_content .=$ruser->birj($db);
                        break;

                    case 'edit':
                        $user_content .= $ruser->birj_edit($db);
                        break;

                    case 'add':
                        $user_content .= $ruser->birj_add($db);
                        break;

                    case 'del':
                        $user_content .= $ruser->birj_delete($db);
                        break;
                }
                break;
            
            case 'all_tasks':
                $user_content .= $ruser->all_tasks($db);
                break;

            case 'ajax':
                $content1 = $ruser->birj($db);
                break;

            case 'changemail':
                $user_content .= $ruser->changemail($db);
                break;

            case 'site_moder_edit':
                $user_content .= $ruser->site_viklad_edit($db);
                break;
            
            case 'ticket':
                switch (@$_REQUEST['action2']) {
                    case '':
                        $user_content .= $ruser->tickets($db);
                        break;

                    case 'view':
                        $user_content .= $ruser->ticket_view($db);
                        break;

                    case 'edit':
                        $user_content .= $ruser->ticket_edit($db);
                        break;

                    case 'add':
                        $user_content .= $ruser->ticket_add($db);
                        break;

                    case 'answer':
                        $user_content .= $ruser->ticket_answer($db);
                        break;

                    case 'close':
                        $user_content .= $ruser->ticket_close($db);
                        break;
                }
                break;

            case 'payments':
                $user_content .= $ruser->go_payment($db);
                break;

            case 'lk':
                $user_content .= $ruser->lk($db);
                break;

            case 'partnership':
                $user_content .= $ruser->partnership($db);
                break;

            case 'output_to_balance':
                $user_content .= $ruser->output_to_balance($db);
                break;

            case 'output_to_purse':
                $user_content .= $ruser->output_to_purse($db);
                break;
            case 'decode_balans':
                $user_content .= $ruser->decode_balans($db);
                break;

            default:
                $content1 = $ruser->birj($db);
                break;
        }
    } else {
        switch (@$_REQUEST['action']) {
            case '': 
                if ($_SESSION['user']['type'] == 'moder') {
                    $user_content .= $ruser->zadaniya_moder($db);
                } else {
                    $user_content .=$ruser->sayty($db);
                }
                break;

            case 'logout':
                $user_content .=$ruser->logout($db);
                break;
            
            case 'lk':
                $user_content .= $ruser->lk($db);
                break;
            
            case 'tasks_moder':
                $user_content .= $ruser->tasks_moder($db);
                break;

            case 'sayty':
                $user_content .= $ruser->sayty($db);
                break;
            
            case 'zadaniya_moder':
                switch (@$_REQUEST['action2']) {
                    case '':
                        $user_content .= $ruser->zadaniya_moder($db);
                        break;

                    case 'edit':
                        $user_content .= $ruser->zadaniya_edit($db);
                        break;
                    
                    case 'csv':
                        $user_content .= $ruser->zadaniya_to_csv($db);
                        break;
                }
                break;

            case 'birj':
                $user_content .=$ruser->birj($db);
                break;

            case 'ajax':
                $content1 = $ruser->birj($db);
                break;

            case 'changemail':
                $user_content .= $ruser->changemail($db);
                break;

            case 'site_moder_edit':
                $user_content .= $ruser->site_viklad_edit($db);
                break;
            
            case 'ticket':
                switch (@$_REQUEST['action2']) {
                    case '':
                        $user_content .= $ruser->tickets($db);
                        break;

                    case 'view':
                        $user_content .= $ruser->ticket_view($db);
                        break;

                    case 'edit':
                        $user_content .= $ruser->ticket_edit($db);
                        break;

                    case 'add':
                        $user_content .= $ruser->ticket_add($db);
                        break;

                    case 'answer':
                        $user_content .= $ruser->ticket_answer($db);
                        break;

                    case 'close':
                        $user_content .= $ruser->ticket_close($db);
                        break;
                }
                break;
            case 'money':
                switch (@$_REQUEST['action2']) {
                    case 'output':
                        $user_content .= $ruser->money_output($db);
                        break;
                }
                break;
            default:
                $content1 = $ruser->birj($db);
                break;
        }
    }
}

if (isset($_GET['act2']) && $_GET['act2'] == "close_notify") {
    $db->Execute("UPDATE admins SET hide_notify=1 WHERE id=" . $_SESSION['user']['id']);
}

if ($_REQUEST['q'])
    $user_content = $search->search1($db, $_SESSION['user']);
$content = str_replace('[search]', $sform, $content1);
$content = str_replace('[content]', $user_content, $content);


if ($_SESSION['user']['id'] > 0) {
    $auth_block = '
		<!-- userbar -->
		<div id="userbar">
			<p id="fast_connection">
                            Быстрая связь: 
                            <img src="/images/openid/skype16x16.png" /> <a href="skype:roman.vetes?chat">Roman.vetes</a>
                            <img src="/images/openid/icq16x16.gif" /> 133-215 
                        </p>
			<a id="user_login" href="' . ($_SESSION['user']["type"] == "moder" ? '/user.php' : '/user.php?action=lk') . '">' . $_SESSION['user']['login'] . '</a>
			
			<span class="sep"></span>
			
			<a id="log_off" href="/user.php?action=logout"><span class="ico"></span> <span>Выход</span> </a>
			
		</div>
	';
} else {
    $auth_block = '
		<!-- login -->
		<div id="login">
			<form action="/user.php" method="POST">
			
				<input type="text" name="login" value="" placeholder="Логин" />
				<input type="text" name="pass" value="" placeholder="Пароль" />
				<input type="submit" value="Вход" />
				
				<div class="registration-recovery">
					<a href="/register.php" id="registration">Регистрация</a>
				</div>
			
			</form>
		</div>
	';
}
$content = str_replace('[auth_block]', $auth_block, $content);


$new_tick = $db->Execute("SELECT COUNT(id) as newt FROM tickets WHERE (uid='" . $_SESSION['user']["id"] . "' OR to_uid='" . $_SESSION['user']["id"] . "') AND status != 0")->FetchRow();
$content = str_replace('[new_tick]', $new_tick['newt'], $content);

$all_tick = $db->Execute("SELECT COUNT(id) as allt FROM tickets WHERE uid='" . $_SESSION['user']["id"] . "' OR to_uid='" . $_SESSION['user']["id"] . "'")->FetchRow();
$content = str_replace('[all_tick]', $all_tick['allt'], $content);


if (isset($_SESSION['user']['type']) && $_SESSION['user']['type'] == "user") {
    $balans = $db->Execute("SELECT SUM(price) as total FROM orders WHERE uid='" . $_SESSION['user']["id"] . "' AND status=1")->FetchRow();
    if (!is_null($balans['total']) || 1) {
        if (!$balans['total'])
            $balans['total'] = 0;

        $pay_link = "/user.php?action=payments";
    }
    else {
        $balans['total'] = 0;
        $pay_link = "/first-payment/?oid=" . $_SESSION['user']["id"];
    }

    //Подсчитываем количество выполненных задач
    $compl_tasks = $db->Execute("SELECT * FROM completed_tasks WHERE uid='" . $_SESSION['user']["id"] . "'");
    $credit = 0;
    while ($row = $compl_tasks->FetchRow()) {
        $credit += $row['price'];
    }
    $balans['total'] -= $credit;
    $_SESSION['user_balans'] = $balans['total'];
    if ($balans['total'] < 0)
        $balans['total'] = "<span style='color:red;'>" . $balans['total'] . "</span>";

    $content = str_replace('[balans]', $_SESSION['user_balans'], $content);
    $content = str_replace('[payment_link]', $pay_link, $content);

    if (@$_GET['whyedit'] == 1)
        $main_comment = $db->Execute("SELECT * FROM Message2002 WHERE Sub_Class_ID = 24")->FetchRow();
    else {
        $main_comment = $db->Execute("SELECT * FROM Message2002 WHERE Sub_Class_ID = 22")->FetchRow();
    }
    $content = str_replace('[main_comment]', $main_comment['Text'], $content);

    if ($_SESSION['user']['hide_notify']) {
        $content = str_replace('[display_comment]', 'none', $content);
    } else {
        $content = str_replace('[display_comment]', 'block;', $content);
    }

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
    $content = str_replace('[brcr]', $result, $content);
} else {
    $sites_model = $db->Execute("SELECT * FROM sayty WHERE moder_id='" . $_SESSION['user']["id"] . "' ORDER BY url");
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

    $content = str_replace('[all_navyklad]', $all_navyklad['count(*)'], $content);
    $content = str_replace('[all_dorabotka]', $all_dorabotka['count(*)'], $content);
    $content = str_replace('[all_vilojeno]', $all_vilojeno['count(*)'], $content);

    $sum_num_tasks = $db->Execute("SELECT count(z.id) as num, SUM(s.price_viklad) as sum FROM zadaniya z LEFT JOIN sayty s ON s.id=z.sid WHERE z.who_posted = '" . $_SESSION['user']["id"] . "' AND z.vipolneno = 1")->FetchRow();
    $withdrawal = $db->Execute("SELECT SUM(w.`sum`) as sums FROM withdrawal w WHERE w.uid='" . $_SESSION['user']['id'] . "' ORDER BY w.date DESC")->FetchRow();
    $withdrawal["sums"] = (!empty($withdrawal["sums"])) ? $withdrawal["sums"] : 0;
    $content = str_replace('[balance]', $sum_num_tasks['sum'] - $withdrawal["sums"], $content);
    $content = str_replace('[num]', $sum_num_tasks['num'], $content);
    
    $content = str_replace('[display_comment]', 'none;', $content);
    
}



echo $content;
?>
