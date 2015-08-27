<?php

class moder {

    public $_smarty = null;
    public $_postman = null;
    public $gogetlinks_id = 1;
    public $getgoodlinks_id = 2;
    public $rotapost_id = 3;
    public $sape_id = 4;
    public $webartex_id = 6;
    public $gogetlinks_url = "https://gogetlinks.net/";
    public $getgoodlinks_url = "http://getgoodlinks.ru/";
    public $rotapost_url = "";
    public $sape_url = "http://api.pr.sape.ru/xmlrpc/";
    public $webartex_url = "https://api.webartex.ru";

    function content($db, $smarty) {
        $this->_smarty = $smarty;
        $this->_postman = new Postman($smarty, $db);

        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
        $action2 = isset($_REQUEST['action2']) ? $_REQUEST['action2'] : '';

        switch (@$action) {
            case '':
                $content = $this->zadaniya_moder($db);
                break;

            case 'logout':
                $content = $this->logout($db);
                break;

            case 'tasks_moder':
                $content = $this->tasks_moder($db);
                break;

            case 'sayty':
                $content = $this->sayty($db);
                break;

            case 'zadaniya_moder':
                switch (@$action2) {
                    case '':
                        $content = $this->zadaniya_moder($db);
                        break;

                    case 'edit':
                        $content = $this->zadaniya_edit($db);
                        break;

                    case 'csv':
                        $content = $this->zadaniya_to_csv($db);
                        break;
                }
                break;

            case 'zadaniya':
                switch (@$action2) {
                    case '':
                        $content = $this->zadaniya_moder($db);
                        break;

                    case 'edit':
                        $content = $this->zadaniya_edit($db);
                        break;

                    case 'csv':
                        $content = $this->zadaniya_to_csv($db);
                        break;
                }
                break;

            case 'birj':
                $content = $this->birj($db);
                break;

            case 'ajax':
                $content = $this->birj($db);
                break;

            case 'changemail':
                $content = $this->changemail($db);
                break;

            case 'site_moder_edit':
                $content = $this->site_viklad_edit($db);
                break;

            case 'ticket':
                switch (@$action2) {
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
                }
                break;
            case 'money':
                switch (@$action2) {
                    case 'output':
                        $content = $this->money_output($db);
                        break;
                }
                break;

            default:
                $content = $this->lk($db);
                break;
        }

        return $content;
    }

    function executeRequest($method, $url, $useragent, $cookie, $query, $body, $header) {
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
            curl_close($ch);
            return curl_error($ch);
        }
        curl_close($ch);
        return $response;
    }

    function logout() {
        unset($_SESSION['user']);
        $cur_exp = time() - 3600;
        setcookie("iforget_ok", "0", $cur_exp);
        header('location:/');
        exit;
    }

    function changemail($db) {

        if ($_REQUEST['email']) {
            $email = $_REQUEST['email'];
            $db->Execute("UPDATE admins SET email='" . $email . "' WHERE id=" . $_SESSION['user']['id']);
            $_SESSION['user']['email'] = $email;
            $this->_postman->admin->userChangemail($_SESSION['user']['login'], $email);

            header('location:/user.php');
            exit;
        }

        $content = file_get_contents(PATH . 'modules/moder/tmp/changemail.tpl');
        $user_email = $_SESSION['user']['email'];
        $content = str_replace('[email]', $user_email, $content);

        return $content;
    }

    function zadaniya_moder($db) {
        $uid = (int) $_SESSION['user']['id'];
        $moder = $db->Execute("SELECT * FROM admins WHERE id=$uid")->FetchRow();
        $cur_url = (isset($_GET['domain_f']) ? ("/user.php?domain_f=" . $_GET['domain_f']) : "/user.php?");
        $url = $cur_url . (@$_GET['status_z'] ? (isset($_GET['domain_f']) ? "&" : "") . "status_z=" . $_GET['status_z'] : "");
        $content = "";
        $limit = 25;
        $offset = 1;
        if (isset($_GET['offset']) && !empty($_GET['offset'])) {
            $offset = (int) $_GET['offset'];
        }

        $query = $db->Execute("SELECT * FROM sayty WHERE moder_id=$uid ORDER BY url");
        $sites = "";
        $sids = $uids = array();
        $domain_f = "<option></option>";
        $n = 0;
        while ($res = $query->FetchRow()) {
            $n++;
            $sids[] = $res['id'];
            $domain_f .= '<option value="' . $res['id'] . '" ' . (isset($_GET['domain_f']) == $res['id'] ? 'selected' : '') . '>' . $res['url'] . '</option>';
            if (!in_array($res['uid'], $uids))
                $uids[] = $res['uid'];

            $sites .= file_get_contents(PATH . 'modules/moder/tmp/site_one_moder.tpl');
            $sites = str_replace('[site]', $res['url'], $sites);
            $sites = str_replace('[url]', $res['url_admin'], $sites);
            $sites = str_replace('[login]', $res['login'], $sites);
            $sites = str_replace('[pass]', $res['pass'], $sites);
            $sites = str_replace('[sid]', $res['id'], $sites);
            $sites = str_replace('[comment_viklad]', $res['comment_viklad'], $sites);

            $class = ($n % 2 == 0) ? 'style="background:#f7f7f7"' : 'style="background:white"';
            $sites = str_replace('[bg]', $class, $sites);
        }

        $sort = "ASC";
        $content .= file_get_contents(PATH . 'modules/moder/tmp/zadaniya_view_moder.tpl');
        $content = str_replace('[available_domains]', $domain_f, $content);
        $zadaniya = '';

        $sids = "(" . implode(",", $sids) . ")";
        $uids = "(" . implode(",", $uids) . ")";
        if (isset($_GET['domain_f'])){
            $sids = " (" . $db->escape($_GET["domain_f"]) . ") ";
        }

        if (!@$_GET['status_z']) {
            $query = $db->Execute("SELECT * FROM zadaniya WHERE type_task != 3 AND sid IN $sids AND ((dorabotka = 1) OR (navyklad=1)) order by date DESC, id $sort LIMIT " . (($offset - 1) * $limit) . ",$limit");
            $all = $db->Execute("select * from zadaniya where type_task != 3 AND sid IN $sids AND ((dorabotka = 1) OR (navyklad=1)) order by date DESC, id $sort");
        } elseif (@$_GET['status_z'] && ($_GET['status_z'] != 'all')) {
            $who_posted = "";
            if (@$_GET['status_z'] == "vipolneno") {
                $who_posted = " AND who_posted = $uid ";
            }
            $status_f = $db->escape(@$_GET['status_z']);

            $query = $db->Execute("select * from zadaniya where type_task != 3 AND ($status_f=1) AND (sid IN $sids) AND ((dorabotka = 1) OR (navyklad=1) OR (vilojeno=1) OR (vipolneno=1)) $who_posted order by date DESC, id $sort LIMIT " . (($offset - 1) * $limit) . ",$limit");
            $all = $db->Execute("select * from zadaniya where type_task != 3 AND ($status_f=1) AND (sid IN $sids) AND ((dorabotka = 1) OR (navyklad=1) OR (vilojeno=1) OR (vipolneno=1)) $who_posted order by date DESC, id $sort");
        } else {
            $all = $db->Execute("select * from zadaniya where type_task != 3 AND sid IN $sids AND ((dorabotka = 1) OR (navyklad=1) OR (vilojeno=1) OR (vipolneno=1 AND who_posted = $uid)) order by date DESC, id $sort");
            $query = $db->Execute("select * from zadaniya where type_task != 3 AND sid IN $sids AND ((dorabotka = 1) OR (navyklad=1) OR (vilojeno=1) OR (vipolneno=1 AND who_posted = $uid)) order by date DESC, id $sort LIMIT " . (($offset - 1) * $limit) . ",$limit");
        }
        //print_r($all->GetAll());
        $all_zadanya = (!empty($all)) ? $all->NumRows() : 0;
        $count_pegination = ceil($all_zadanya / $limit);
        if ($count_pegination > 1) {
            $pegination = '<div style="float:right">';
            if ($offset == 1) {
                $pegination .= '<div style="float:left">Пред.</div>';
            } else {
                $pegination .= "<div style='float:left'><a href='$url&offset=" . ($offset - 1) . "'>Пред.</a></div>";
            }
            $pegination .= '<div style="float:left">&nbsp [ стр.&nbsp</div>';
            $pegination .= '<div class="select" style="width:50px;float:left;margin-top:-7px"><select name="pagerMenu" onchange="location=\'' . $url . '&offset=\'+this.options[this.selectedIndex].value+\'\'" ;="">';

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
                $pegination .= "<a href='$url&offset=" . ($offset + 1) . "'>След.</a>";
            }
            $pegination .= '</div>';
            $pegination .= '<br>';
            $content = str_replace('[pegination]', $pegination, $content);
        } else {
            $content = str_replace('[pegination]', "", $content);
        }


        $all_sites = $db->Execute("SELECT * FROM sayty");
        $ast = array();
        while ($row = $all_sites->FetchRow()) {
            $ast[] = $row;
        }

        if ($query) {
            while ($res = $query->FetchRow()) {
                $zadaniya .= file_get_contents(PATH . 'modules/moder/tmp/zadaniya_one_moder.tpl');
                $zadaniya = str_replace('[id]', $res['id'], $zadaniya);
                $system = $res['sid'];
                $zadaniya = str_replace('[sid]', $system, $zadaniya);
                $system = $db->Execute("select * from sayty where id=$system");
                $system = $system->FetchRow();
                $system = $system['url'];
                $zadaniya = str_replace('[sistema]', $system, $zadaniya);
                $zadaniya = str_replace('[sistemaggl]', $res['sistema'], $zadaniya);
                $zadaniya = str_replace('[date]', date('d.m.Y', $res['date']), $zadaniya);
                $zadaniya = str_replace('[ankor]', $res['ankor'], $zadaniya);
                $zadaniya = str_replace('[tema]', mb_substr($res['tema'], 0, 35), $zadaniya);
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
                $zadaniya = str_replace('[status]', $new_s, $zadaniya);

                if ($res['dorabotka'])
                    $bg = 'style="background:#f6b300"';
                else if ($res['vipolneno'])
                    $bg = 'style="background:#83e24a"';
                else if ($res['vrabote'])
                    $bg = 'style="background:#00baff"';
                else if ($res['navyklad'])
                    $bg = 'style="background:#ffde96"';
                else if ($res['vilojeno'])
                    $bg = 'style="background:#b385bf"';
                else
                    $bg = '';
                $zadaniya = str_replace('[bg]', $bg, $zadaniya);

                foreach ($ast as $k => $v) {
                    if ($res['sid'] == $v['id']) {
                        $zadaniya = str_replace('[url]', $v['url'], $zadaniya);
                        break;
                    }
                }
            }
        }
        if ($zadaniya)
            $zadaniya = str_replace('[zadaniya]', $zadaniya, file_get_contents(PATH . 'modules/moder/tmp/zadaniya_top_moder.tpl'));
        else {
            $zadaniya = file_get_contents(PATH . 'modules/moder/tmp/no.tpl');
            $pegination = "";
        }

        $content .= file_get_contents(PATH . 'modules/moder/tmp/site_top_moder.tpl');
        $content = str_replace('[sites]', $sites, $content);

        $content = str_replace('[zadaniya]', $zadaniya, $content);
        $content = str_replace('[uid]', $uid, $content);
        //$content = str_replace('[sid]', $sid ? $sid : null, $content);
        $content = str_replace('[pegination]', @$pegination ? $pegination : null, $content);
        $content = str_replace('[cur_url]', $cur_url, $content);
        $content = str_replace('[viklad_id]', $_SESSION['user']['id'], $content);

        return $content;
    }

    function sayty($db) {
        $content = file_get_contents(PATH . 'modules/moder/tmp/sayty_view.tpl');

        $uid = (int) $_SESSION['user']['id'];
        $query = $db->Execute("select * from admins where id=$uid");
        $res = $query->FetchRow();
        $content = str_replace('[login]', $res['login'], $content);

        $content = str_replace('[uid]', $uid, $content);

        $sayty = '';
        $query = $db->Execute("select * from sayty where uid=$uid order by id asc");
        $n = 0;
        while ($res = $query->FetchRow()) {
            $sayty .= file_get_contents(PATH . 'modules/moder/tmp/sayty_one.tpl');
            $sayty = str_replace('[url]', $res['url'], $sayty);
            $sayty = str_replace('[id]', $res['id'], $sayty);
            $sayty = str_replace('[comment_viklad]', $res['comment_viklad'], $sayty);
            $sid = $res['id'];
            $z1 = $db->Execute("select count(*) from zadaniya where vrabote=1 and sid=$sid");
            $z1 = $z1->FetchRow();
            $z1 = $z1['count(*)'];
            $sayty = str_replace('[z1]', $z1, $sayty);
            $z2 = $db->Execute("select count(*) from zadaniya where dorabotka=1 and sid=$sid");
            $z2 = $z2->FetchRow();
            $z2 = $z2['count(*)'];
            $sayty = str_replace('[z2]', $z2, $sayty);
            $z3 = $db->Execute("select count(*) from zadaniya where vipolneno=1 and sid=$sid");
            $z3 = $z3->FetchRow();
            $z3 = $z3['count(*)'];
            $sayty = str_replace('[z3]', $z3, $sayty);
            $z7 = $db->Execute("select count(*) from zadaniya where navyklad=1 and sid=$sid");
            $z7 = $z7->FetchRow();
            $z7 = $z7['count(*)'];
            $sayty = str_replace('[z7]', $z7, $sayty);
            $z4 = $db->Execute("select count(*) from zadaniya where sid=$sid");
            $z4 = $z4->FetchRow();
            $z4 = $z4['count(*)'] - ($z1 + $z2 + $z3 + $z7);
            $sayty = str_replace('[z4]', $z4, $sayty);
        }
        if ($sayty)
            $sayty = str_replace('[sayty]', $sayty, file_get_contents(PATH . 'modules/moder/tmp/sayty_top.tpl'));
        else
            $sayty = file_get_contents(PATH . 'modules/moder/tmp/no.tpl');

        $content = str_replace('[sayty]', $sayty, $content);
        $content = str_replace('[uid]', $uid, $content);
        return $content;
    }

    function zadaniya_edit($db) {

        $send = @$_REQUEST['send'];
        $id = (int) $_REQUEST['id'];
        //$uid = (int) $_GET['uid'];
        $uid = $_SESSION["user"]["id"];
        $sid = (int) $_GET['sid'];
        $res = $db->Execute("select * from zadaniya LEFT JOIN admins ON admins.id=zadaniya.uid where zadaniya.id=$id")->FetchRow();
        $uinfo = $db->Execute("SELECT * FROM admins WHERE id=" . $uid)->FetchRow();
        $sinfo = $db->Execute("SELECT * FROM sayty WHERE id=" . $sid)->FetchRow();

        if (!$send) {
            $content = file_get_contents(PATH . 'modules/moder/tmp/zadaniya_edit_moder.tpl');
            $error = @$_REQUEST['error'];
            $content = str_replace('[error]', $error, $content);

            if ($res['vipolneno'])
                $res['vipolneno'] = 'checked="checked"';
            else
                $res['vipolneno'] = '';
            if ($res['dorabotka'])
                $res['dorabotka'] = 'checked="checked"';
            else
                $res['dorabotka'] = '';
            if ($res['vrabote'])
                $res['vrabote'] = 'checked="checked"';
            else
                $res['vrabote'] = '';
            if ($res['navyklad'])
                $res['navyklad'] = 'checked="checked"';
            else
                $res['navyklad'] = '';
            if ($res['vilojeno'])
                $res['vilojeno'] = 'checked="checked"';
            else
                $res['vilojeno'] = '';

            $pass = ETXT_PASS;
            $params = array('method' => 'tasks.getResults', 'token' => '29aa0eec2c77dd6d06e23b3faaef9eed', 'id' => $res['task_id']);
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
            $file_href = "";
            $uniq = 0;
            foreach ($task_stat as $kt => $vt) {
                $vt = (array) $vt;

                $file_href = (array) @$vt['files'];
                $file_href_parts = (array) @$file_href['file'];
                if (@$file_href_parts['path']) {
                    $file_path = $file_href_parts['path'];
                    $uniq = $file_href_parts['per_antiplagiat'];
                } else {
                    $file_href_parts = (array) @$file_href['text'];
                    $file_path = @$file_href_parts['path'];
                    $uniq = @$file_href_parts['per_antiplagiat'];
                }
            }
            if ($file_path) {
                $cur_text = file_get_contents($file_path);
                $cur_text = iconv('cp1251', 'utf-8', $cur_text);
                if (!$res['overwrite'])
                    $content = str_replace('[text]', htmlspecialchars_decode($cur_text), $content);
            }

            $params = array('method' => 'tasks.listTasks', 'token' => '29aa0eec2c77dd6d06e23b3faaef9eed', 'id' => $res['task_id']);
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
            $task_info = json_decode($cur_out);

            $etxt_action = "";
            foreach ($task_info as $kl => $vl) {
                $vl = (array) $vl;
                if ($vl['status'] == 3) {
                    $etxt_action = '
					<p>
						<table>
						<tr>
							<td>Принять</td>
							<td><input type="radio" value="0" name="morework" /></td>
						</tr>
						<tr>
							<td>На доработку</td>
							<td><input type="radio" value="1" name="morework" /></td>
						</tr>
						<tr>
							<td>Комментарий<br/>доработки</td>
							<td><textarea name="morework_comment" cols="10" rows="5"></textarea></td>
						</tr>
						</table>
					</p>
					';
                }
            }
            $content = str_replace('[etxt_action]', $etxt_action, $content);

            $content = str_replace('[uniq]', $uniq, $content);
            if ($res['sistema'] != "http://miralinks.ru/" && $res['sistema'] != "http://pr.sape.ru/") {
                $content = str_replace('[display]', "style='display:none'", $content);
            } else {
                $content = str_replace('[display]', "", $content);
            }

            foreach ($res as $k => $v) {
                $content = str_replace("[$k]", htmlspecialchars_decode($v), $content);
            }
            $content = str_replace('[uid]', $uid, $content);
            $content = str_replace('[sid]', $sid, $content);
            $content = str_replace('[site]', $sinfo["url"], $content);
            $content = str_replace('[tid]', $id, $content);
        } else {
            $sistema = @$_REQUEST['sistema'];
            $etxt = @$_REQUEST['etxt'];
            $ankor = @$_REQUEST['ankor'];
            $url = @$_REQUEST['url'];
            $keywords = @$_REQUEST['keywords'];
            $tema = @$_REQUEST['tema'];
            $text = @$_REQUEST['text'];
            $url_statyi = @$_REQUEST['url_statyi'];
            $url_pic = @$_REQUEST['url_pic'];
            $price = @$_REQUEST['price'];
            $comments = (isset($_REQUEST['comments']) && !empty($_REQUEST['comments'])) ? mysql_real_escape_string($_REQUEST['comments']) : '';
            $admin_comments = @$_REQUEST['admin_comments'];
            $error = "";

            $task_id = $res['task_id'];

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


            $task_status = @$_REQUEST['task_status'];
            if ($task_status == "vilojeno")
                $vilojeno = 1;
            else
                $vilojeno = 0;

            $sinfo["url"] = str_replace("/", "", str_replace("http://", "", str_replace("www.", "", $sinfo["url"])));
            $vipolneno = 0;

            if ($vilojeno == 0) {
                /*  Если статус не изменился, сохраняем поля и отправляем админу писомо об изменениии в задаче  */
                $db->Execute($q = "update zadaniya set vilojeno='$vilojeno', url_statyi='$url_statyi', url_pic='$url_pic', admin_comments='$admin_comments' where id=$id");
            } elseif ((empty($url_statyi) || $url_statyi == "" || !mb_strstr($url_statyi, $sinfo["url"]))) {
                /*  ПРОБЛЕМА с ссылкой на статью  */
                if (empty($url_statyi) || $url_statyi == "") { /*  Ссылка пуста или отсутствует  */
                    $error .= ("Поле `Ссылка на статью` обязательно для заполнения, если текст выложен! ");
                }
                if (!mb_strstr($url_statyi, $sinfo["url"])) { /*  Ссылка и URL сайта не совпадают  */
                    $error .= "В поле `Ссылка на статью` url не соответствует сайту!";
                }
                /*  отправляем ошибку МОДЕРАТОРУ об этом  */
                header("Location: /user.php?module=user&action=zadaniya_moder&action2=edit&uid=$uid&sid=$sid&id=$id&error=$error'");
                exit();
            } else {
                $db->Execute($q = "update zadaniya set dorabotka=0, vrabote=0, navyklad=0, vilojeno='$vilojeno', who_posted='$uid', url_statyi='$url_statyi', url_pic='$url_pic', admin_comments='$admin_comments' where id=$id");
            }
            //  Если проблем с ссылкой не было, то изменения были уже сохранены, и редиректим на главную страницу  */
            echo "<script>window.location.href='/user.php';</script>";
            exit();
        }

        return $content;
    }

    function setTaskSape($db, $task) {
        $url = "http://api.pr.sape.ru/xmlrpc/";
        $birj = $db->Execute("select * from birjs where birj=4 AND uid=" . $task["uid"])->FetchRow();

        $data = xmlrpc_encode_request('sape_pr.login', array($birj["login"], $birj["pass"]));
        $header[] = "Content-type: text/xml";
        $header[] = "Content-length: " . strlen($data);
        $cookie_jar = tempnam(PATH . 'temp', "cookie");
        if ($curl = curl_init()) { /*  Логинимся в сапе  */
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            $out = curl_exec($curl);
            curl_close($curl);
        }
        $id_user_sape = xmlrpc_decode($out);
        if (!is_array($id_user_sape)) { /*  Если залогинились, отправляем ссылку  */
            $data = xmlrpc_encode_request('sape_pr.advert.place', array((int) $task["sape_id"], $task['url_statyi']));
            $header[1] = "Content-length: " . strlen($data);
            if ($curl = curl_init()) {
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
                curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                $out = curl_exec($curl);
                curl_close($curl);
            }
            $result = xmlrpc_decode($out);

            if (isset($result['faultString']) && !empty($result['faultString'])) {
                return $result['faultString'];
            } else {
                return false;
            }
        } else {
            /*  Если нет пользователя, отправляем админу письмо с ошибкой (задача все равно переводится в статус Выполнено  */
            return "Не верный логин или пароль для доступа к биржи";
        }



        /* $birj = $db->Execute("select * from birjs where birj='" . $this->sape_id . "' AND uid=" . $task["uid"])->FetchRow();

          $data = xmlrpc_encode_request('sape_pr.login', array($birj["login"], $birj["pass"]));
          $header[] = "Content-type: text/xml";
          $header[] = "Content-length: " . strlen($data);
          $cookie_jar = tempnam(PATH . 'temp', "cookie");

          $auth = $this->executeRequest('POST', $this->sape_url, null, $cookie_jar, array(), $data, $header);
          $id_user_sape = xmlrpc_decode($auth);

          if (!is_array($id_user_sape)) {
          //  Если залогинились, отправляем ссылку
          $data = xmlrpc_encode_request('sape_pr.advert.place', array((int) $task["sape_id"], $task['url_statyi']));
          $header[1] = "Content-length: " . strlen($data);
          $send = $this->executeRequest('POST', $this->sape_url, null, $cookie_jar, array(), $data, $header);
          $send = xmlrpc_decode($send);

          if (isset($send['faultString']) && !empty($send['faultString'])) {
          return $send['faultString'];
          } else {
          return false;
          }
          } else {
          //  Если нет пользователя, отправляем админу письмо с ошибкой (задача все равно переводится в статус Выполнено
          return "Не верный логин или пароль для доступа к биржи";
          } */
    }

    function setTaskRotapost($db, $task) {

        include_once 'includes/Rotapost.php';
        $rotapost = new Rotapost\Client();
        $birj = $db->Execute("select * from birjs where birj=3 AND uid=" . $task["uid"])->FetchRow();
        $auth = $rotapost->loginAuth($birj['login'], md5($birj['login'] . $birj['pass']));

        //$message["to"][1] = array("email" => MAIL_DEVELOPER);

        if (($birj['login'] == null || $birj['pass'] == null) || (isset($auth->Success) && $auth->Success == "false") || !isset($auth->ApiKey)) {
            //  Если нет логина или пароля от биржи, отправляем админу письмо с ошибкой 
            $err = (array) $auth->Error;
            if ($birj['login'] == null || $birj['pass'] == null) {
                return "Отсутствует логин или пароль для доступа к биржи Rotapost";
            } elseif ($auth->Success == "false" && isset($err["Description"])) {
                return $err["Description"];
            }
        } else {
            //  ИНАЧЕ отправляем в ротапост ссылку 
            $result = $rotapost->taskComplete($task["rotapost_id"], $task['url_statyi']);
            if ((isset($result->Success) && $result->Success == "true")) {
                return false;
            } elseif ($result->Success == "false") {
                /*  Иначе отправляем ошибку админу  */
                $err = (array) $result->Error;
                return $err["Description"];
            }
        }


        /* include_once 'includes/Rotapost.php';
          $rotapost = new Rotapost\Client();
          $birj = $db->Execute("SELECT * FROM birjs WHERE birj='" . $this->rotapost_id . "' AND uid=" . $task["uid"])->FetchRow();
          $auth = $rotapost->loginAuth($birj['login'], md5($birj['login'] . $birj['pass']));

          if (($birj['login'] == null || $birj['pass'] == null) || (isset($auth->Success) && $auth->Success == "false") || !isset($auth->ApiKey)) {
          //  Если нет логина или пароля от биржи, отправляем админу письмо с ошибкой
          $err = (array) $auth->Error;
          if ($birj['login'] == null || $birj['pass'] == null) {
          return "Отсутствует логин или пароль для доступа к биржи Rotapost</strong>";
          } elseif ($auth->Success == "false" && isset($err["Description"])) {
          return $err["Description"];
          }
          } else {
          //  ИНАЧЕ отправляем в ротапост ссылку
          $result = $rotapost->taskComplete($task["rotapost_id"], $task['url_statyi']);
          if ((isset($result->Success) && $result->Success == "true")) {
          return false;
          } elseif ($result->Success == "false") {
          //  Иначе отправляем ошибку админу
          $err = (array) $result->Error;
          return $err["Description"];
          }
          } */
    }

    function setTaskGGL($db, $task) {
        $birj = $db->Execute("select * from birjs where birj=1 AND uid=" . $task["uid"])->FetchRow();
        $data = array('e_mail' => $birj['login'],
            'password' => trim($birj['pass']),
            'remember' => "");
        $cookie_jar = tempnam(PATH . 'temp', "cookie");
        if ($curl = curl_init()) { /*  Логинимся  */
            curl_setopt($curl, CURLOPT_URL, 'https://gogetlinks.net/login.php');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
            @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            $page = curl_exec($curl);
            curl_close($curl);
        }
        $page = iconv("windows-1251", "utf-8", $page);
        if ($page == "Некорректный Логин или Пароль" || $page == "Некорректный email или Пароль") {
            /*  Если НЕ залогинились отправляем ошибку админу  */
            return "Некорректный Логин или Пароль";
        } else {
            /*  ИНАЧЕ отправляем ссылку в ГГЛ  */
            $urlg = "https://gogetlinks.net/template/check_exist_view.php";
            $query_p = json_encode(array('curr_id' => $task["b_id"], 'URL' => $task['url_statyi'], 'path' => "Главная -> "));
            if ($curl = curl_init()) {
                curl_setopt($curl, CURLOPT_URL, $urlg);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
                curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
                @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, array('curr_id' => $task["b_id"], 'URL' => $task['url_statyi'], 'path' => "Главная -> "));
                $out = curl_exec($curl);
                $out = iconv("windows-1251", "utf-8", $out);
                curl_close($curl);
            }
            if (strstr($out, "Обзор проверен системой и отправлен на проверку оптимизатору")) {
                /*  Если ответ с ГГЛ "НОРМ", то подтверждаем отправку  */
                return false;
            } else {
                /*  Иначе отправляем ошибку админу  */
                return $out;
            }
        }
        die();


        /* $birj = $db->Execute("select * from birjs where birj='1' AND uid=" . $task["uid"])->FetchRow();
          $data = array('e_mail' => $birj['login'], 'password' => trim($birj['pass']), 'remember' => "");

          $cookie_jar = tempnam(PATH . 'temp', "cookie");
          $auth = $this->executeRequest('POST', 'https://gogetlinks.net/login.php', null, $cookie_jar, array(), $data, null);
          $page = iconv("windows-1251", "utf-8", $auth);

          if ($page == "Некорректный Логин или Пароль" || $page == "Некорректный email или Пароль") {
          //  Если НЕ залогинились отправляем ошибку админу
          return $page;
          } else {
          //  ИНАЧЕ отправляем ссылку в ГГЛ

          $data = array('curr_id' => $task["b_id"], 'URL' => $task['url_statyi'], 'path' => "Главная -> ");
          $send = $this->executeRequest('POST', 'https://gogetlinks.net/template/check_exist_view.php', null, $cookie_jar, array(), $data, null);
          $out = iconv("windows-1251", "utf-8", $send);

          if (strstr($out, "Обзор проверен системой и отправлен на проверку оптимизатору")) {
          //  Если ответ с ГГЛ "НОРМ", то подтверждаем отправку
          return false;
          } else {
          //  Иначе отправляем ошибку админу
          return $out;
          }
          } */
    }

    function setTaskGetGoodLinks($db, $task) {
        $birj = $db->Execute("select * from birjs where birj='" . $this->getgoodlinks_id . "' AND uid=" . $task["uid"])->FetchRow();
        $data = array('e_mail' => $birj['login'], 'password' => trim($birj['pass']), 'remember' => "");

        $cookie_jar = tempnam(PATH . 'temp', "cookie");
        $auth = $this->executeRequest('POST', $this->getgoodlinks_url . 'login.php', null, $cookie_jar, array(), $data, null);
        $page = iconv("windows-1251", "utf-8", $auth);

        $error = strpos($page, "Неверное имя пользователя или пароль. Пожалуйста, попробуйте ещё раз.");
        if ($error !== false && !empty($error) && $error != 0) {
            /*  Если НЕ залогинились отправляем ошибку админу  */
            return $page;
        } else {
            /*  ИНАЧЕ отправляем ссылку в GETGOODLINKS  */
            $data = array('curr_id' => $task["b_id"], 'URL' => $task['url_statyi'], 'path' => "Главная -> ");
            $send = $this->executeRequest('POST', $this->getgoodlinks_url . 'template/check_exist_view.php', null, $cookie_jar, array(), $data, null);
            //$out = iconv("windows-1251", "utf-8", $send);

            if (strstr($send, "Обзор проверен системой и отправлен на проверку оптимизатору")) {
                /*  Если ответ с getgoodlinks "НОРМ", то подтверждаем отправку  */
                return false;
            } else {
                /*  Иначе отправляем ошибку админу  */
                return $send;
            }
        }
    }

    function setTaskWebartex($db, $task) {
        $birj = $db->Execute("select * from birjs where birj='" . $this->webartex_id . "' AND uid=" . $task["uid"])->FetchRow();
        $login = $birj['login'];
        $pass = $birj['pass'];

        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $this->webartex_url . "/api/webmaster/articles/check/" . $task["webartex_id"] . "?url=" . $task['url_statyi']);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_USERPWD, $login . ":" . $pass);
            @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            $out = curl_exec($curl);
            curl_close($curl);
        }
        $send = json_decode($out);
        $object = (array) $send->object;
        $err = @$send->error;
        if (empty($err) && isset($object) && isset($object["status"]) && $object["status"] == "waitindex" && !empty($object["url"])) {
            /*  Если status с webartex "Ожидает индексации", то подтверждаем отправку  */
            return false;
        } else {
            /*  Иначе отправляем ошибку админу  */
            return $err;
        }
    }

    function tasks_moder($db) {
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/tasks.tpl');
        $status = $_REQUEST['status'];
        $uid = $_SESSION["user"]['id'];

        $cur_sites = $db->Execute("SELECT * FROM sayty WHERE moder_id=" . $uid);
        $sids = array();
        while ($vs = $cur_sites->FetchRow()) {
            $sids[] = $vs['id'];
        }
        $sids = "(" . implode(",", $sids) . ")";

        $tasks = NULL;
        switch ($status) {
            case "navyklad":
                $tasks = $db->Execute("SELECT * from zadaniya where navyklad=1 AND sid IN " . $sids . " ORDER BY date ASC");
                $new_s = "ready";
                $bg = '#ffde96';
                $status = "На выкладывании";
                break;
            case "dorabotka":
                $tasks = $db->Execute("SELECT * FROM zadaniya WHERE dorabotka=1 AND sid IN " . $sids . " ORDER BY date ASC");
                $new_s = "in-work";
                $bg = '#f6b300';
                $status = "На доработке";
                break;
            case "vilojeno":
                $tasks = $db->Execute("select * from zadaniya where vilojeno=1 AND sid IN " . $sids . " ORDER BY date ASC");
                $new_s = "vilojeno";
                $bg = '#b385bf';
                $status = "Выложено";
                break;

            default :
                $tasks = $db->Execute("select * from zadaniya where vilojeno=1 AND sid = 0 ORDER BY date ASC");
                $new_s = $bg = $status = "";
        }

        if (!empty($tasks)) {
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
        } else {
            $zadaniya = "<tr><td colspan=6>Нет задач</td></tr>";
        }
        $content = str_replace('[status]', $status, $content);
        $content = str_replace('[zadaniya]', $zadaniya, $content);
        return $content;
    }

    function site_viklad_edit($db) {

        $send = $_REQUEST['send'];
        $sid = ($_GET['sid'] ? $_GET['sid'] : $_REQUEST['s_id']);

        $site = $db->Execute("SELECT * FROM sayty WHERE id=$sid")->FetchRow();
        $uid = $site['uid'];
        $uinfo = $db->Execute("SELECT * FROM admins WHERE id=" . $uid)->FetchRow();
        if (!$send) {
            $content = file_get_contents(PATH . 'modules/moder/tmp/site_moder_edit.tpl');
            $content = str_replace('[question_viklad]', $uinfo['comment_viklad'], $content);
            $content = str_replace('[s_id]', $sid, $content);
        } else {
            $qv = $_REQUEST['question_viklad'];

            $db->Execute("UPDATE admins SET comment_viklad='" . $qv . "' WHERE id=$uid");
            $this->_postman->admin->moderChangeVikladComment($uid, $site['url']);
            header("Location: /user.php");
            exit();
        }

        return $content;
    }

    function zadaniya_to_csv($db) {
        $uid = (int) $_REQUEST['uid'];
        $query = $db->Execute("select * from admins where id=$uid");
        $res = $query->FetchRow();

        $query = $db->Execute("select * from sayty where moder_id=$uid");
        $sids = array();
        while ($res = $query->FetchRow()) {
            $sids[] = $res['id'];
        }
        $sids = "(" . implode(",", $sids) . ")";

        $zadaniya = '';

        $query = $db->Execute("select * from zadaniya where sid IN $sids AND vipolneno=1 order by date");

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
		</tr>';

        $n = 0;
        while ($res = $query->FetchRow()) {
            $system = $res['sid'];
            $system = $db->Execute("select * from sayty where id=$system");
            $system = $system->FetchRow();

            echo '
			<table border="1">
			<tr>
				<td>' . htmlentities(iconv("utf-8", "windows-1251", $res['sistema']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . htmlentities(iconv("utf-8", "windows-1251", $res['ankor']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . htmlentities(iconv("utf-8", "windows-1251", $res['url']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . htmlentities(iconv("utf-8", "windows-1251", $res['keywords']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . htmlentities(iconv("utf-8", "windows-1251", $res['tema']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . htmlentities(iconv("utf-8", "windows-1251", $res['url_statyi']), ENT_QUOTES, "cp1251") . '</td>
				<td>' . htmlentities(iconv("utf-8", "windows-1251", date("d-m-Y", $res['date'])), ENT_QUOTES, "cp1251") . '</td>
				<td>' . htmlentities(iconv("utf-8", "windows-1251", $res['text']), ENT_QUOTES, "cp1251") . '</td>
			</tr>';
        }
        echo '</table>';

        exit();
    }

    function tickets($db) {
        $content = file_get_contents(PATH . 'modules/moder/tmp/tickets_view.tpl');

        $uid = (int) $_SESSION['user']['id'];
        $query = $db->Execute("select * from admins where id=$uid");
        $res = $query->FetchRow();
        $content = str_replace('[login]', $res['login'], $content);
        $content = str_replace('[uid]', $uid, $content);

        $query = $db->Execute("SELECT * FROM tickets WHERE (uid=$uid OR to_uid=$uid) ORDER BY id DESC");

        $ticket_subjects = $db->Execute("SELECT * FROM Message2008");
        $ticket_subjects = $ticket_subjects->FetchRow();
        $content = str_replace('[ticket_subjects]', $ticket_subjects['Name'], $content);

        $ticket = "";
        while ($resw = $query->FetchRow()) {
            $ticket .= file_get_contents(PATH . 'modules/moder/tmp/ticket_one.tpl');
            $ticket = str_replace('[site]', $resw['site'], $ticket);
            $ticket = str_replace('[subject]', $resw['subject'], $ticket);
            $ticket = str_replace('[q_theme]', $resw['q_theme'], $ticket);
            $ticket = str_replace('[tdate]', $resw['date'], $ticket);
            $ticket = str_replace('[tid]', $resw['id'], $ticket);
            if ($resw["to_uid"] != 0) {
                $ticket = str_replace('[display]', 'style="display:none"', $ticket);
            } else {
                $ticket = str_replace('[display]', '', $ticket);
            }
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

        $content = str_replace('[tickets]', $ticket, $content);

        $zid = (int) @$_REQUEST['zid'];
        if ($zid) {
            $zinfo = $db->Execute("SELECT * FROM zadaniya WHERE id=$zid");
            $zinfo = $zinfo->FetchRow();
            $sid = $zinfo['sid'];
            $sinfo = $db->Execute("SELECT * FROM sayty WHERE id=$sid");
            $sinfo = $sinfo->FetchRow();

            $content = str_replace('[site]', $sinfo['url'], $content);
            $content = str_replace('[tid]', $zid, $content);
            $content = str_replace('[subject]', "Вопрос по задаче №$zid", $content);
            $content = str_replace('[Обработкой заявок]', "selected", $content);
        } else {
            $content = str_replace('[site]', "", $content);
            $content = str_replace('[tid]', 0, $content);
            $content = str_replace('[subject]', "", $content);
        }

        return $content;
    }

    function ticket_add($db) {
        $uid = (int) $_SESSION['user']['id'];

        $subject = $_REQUEST['subject'];
        $site = $_REQUEST['site'];
        $theme = $_REQUEST['theme'];
        $msg = substr(nl2br(htmlspecialchars(addslashes(trim($_REQUEST['msg'])))), 0, 2000);
        $cdate = date("Y-m-d");
        $zid = $_REQUEST['tid'];

        $db->Execute("INSERT INTO tickets (uid, subject, q_theme, msg, date, status, site, tid) VALUES ($uid, '$subject', '$theme', '$msg', '$cdate', 1, '$site', $zid)");

        if (!mb_stristr($subject, "Вопрос по задаче")) {
            $this->_postman->admin->ticketAdd();
        } else {
            $this->_postman->admin->ticketAdd($subject);
        }
        header("Location: ?module=user&action=ticket");
        exit();
    }

    function ticket_edit($db) {
        $id = (int) $_REQUEST['tid'];
        $ticket = $db->Execute("select * from tickets where id=$id")->FetchRow();
        
        if (!isset($_REQUEST['send'])) {
            $content = file_get_contents(PATH . 'modules/moder/tmp/ticket_edit.tpl');

            $ticket_subjects = $db->Execute("SELECT * FROM Message2008")->FetchRow();
            $content = str_replace('[ticket_subjects]', $ticket_subjects['Name'], $content);
            $content = str_replace("[tid]", $id, $content);
            
            foreach ($ticket as $k => $v) {
                if ($k == "q_theme") {
                    $content = str_replace("[$v]", "selected", $content);
                }
                $content = str_replace("[$k]", $v, $content);
            }
        } else {
            $subject = $_REQUEST['subject'];
            $site = $_REQUEST['site'];
            $theme = $_REQUEST['theme'];
            $msg = $_REQUEST['msg'];

            //$db->Execute("UPDATE tickets SET subject='$subject', q_theme='$theme', msg='$msg', site='$site' WHERE id=$id");
            
            //$this->_postman->admin->ticketEdit($id, $subject);
            header("Location: ?module=user&action=ticket");
            exit();
        }
        return $content;
    }

    function ticket_view($db) {
        $content = file_get_contents(PATH . 'modules/moder/tmp/ticket_full_view.tpl');

        $uid = (int) $_SESSION['user']['id'];
        $query = $db->Execute("select * from admins where id=$uid");
        $res = $query->FetchRow();
        $content = str_replace('[login]', $res['login'], $content);
        $content = str_replace('[uid]', $uid, $content);

        $tid = (int) $_REQUEST['tid'];

        $q = "SELECT * FROM tickets WHERE (uid=$uid OR to_uid=$uid) AND id=$tid";
        $query = $db->Execute($q);
        $res = $query->FetchRow();

        $view = file_get_contents(PATH . 'modules/moder/tmp/ticket_chat_one.tpl');
        $view = str_replace('[msg]', $res['msg'], $view);
        $view = str_replace('[cdate]', $res['date'], $view);
        if ($res['to_uid'] > 0) {
            $view = str_replace('[from_class]', "support", $view);
            $view = str_replace('[from]', "admin iforget.ru", $view);
            if ($res['status'] == 1) {
                $db->Execute("UPDATE tickets SET status=2 WHERE id=$tid");
            }
        } else {
            $view = str_replace('[from_class]', "you", $view);
            $view = str_replace('[from]', "Вы", $view);
        }
//		$view = str_replace('[from_class]', "you", $view);

        $answers = $db->Execute("SELECT * FROM answers WHERE tid=$tid");


        while ($resw = $answers->FetchRow()) {
            $view .= file_get_contents(PATH . 'modules/moder/tmp/ticket_chat_one.tpl');

            $view = str_replace('[msg]', $resw['msg'], $view);
            $view = str_replace('[cdate]', $resw['date'], $view);
            if ($resw['uid'] == $uid) {
                $view = str_replace('[from]', "Вы", $view);
                $view = str_replace('[from_class]', "you", $view);
            } else {
                $view = str_replace('[from]', "Администрация", $view);
                $view = str_replace('[from_class]', "support", $view);
            }
        }

        if ($res['tid'] > 0) {
            $zid = $res['tid'];
            $zinfo = $db->Execute("SELECT * FROM zadaniya WHERE id=$zid");
            $zinfo = $zinfo->FetchRow();
            $sid = $zinfo['sid'];
            $sinfo = $db->Execute("SELECT * FROM sayty WHERE id=$sid");
            $sinfo = $sinfo->FetchRow();

            $subj = "<a href='/admin.php?module=admins&action=zadaniya&uid=" . $sinfo['uid'] . "&sid=" . $sid . "&action2=edit&id=" . $zid . "' target='_blank'>" . $res['subject'] . "</a>";
        } else {
            $subj = $res['subject'];
        }

        $content = str_replace('[chat]', $view, $content);
        $content = str_replace('[subject]', $subj, $content);
        $content = str_replace('[tid]', $tid, $content);

        return $content;
    }

    function ticket_answer($db) {
        $uid = (int) $_SESSION['user']['id'];
        $tid = (int) $_REQUEST['tid'];
        $msg = substr(nl2br(htmlspecialchars(addslashes(trim($_REQUEST['msg'])))), 0, 2000);
        $adate = date("Y-m-d H:i:s");

        if (!empty($msg)) {
            $db->Execute("UPDATE tickets SET status='1' WHERE id=$tid");
            $db->Execute("INSERT INTO answers (uid, tid, msg, date) VALUES ($uid, $tid, '$msg', '$adate')");
            $this->_postman->admin->ticketAnswer($tid, "user");

            header("Location: ?module=user&action=ticket");
        } else {
            $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
            $content = str_replace('[alert]', "Пустой текст ответа!", $content);
            $content = str_replace('[url]', "?module=user&action=ticket&action2=view&tid=" . $tid, $content);
        }
        return $content;
    }

    function ticket_close($db) {
        $tid = (int) $_REQUEST['tid'];
        $db->Execute("UPDATE tickets SET status=0 WHERE id=$tid");
        $content = file_get_contents(PATH . 'modules/admins/tmp/admin/request.tpl');
        $content = str_replace('[url]', "?module=user&action=ticket", $content);
        return $content;
    }

    function money_output($db) {
        $content = file_get_contents(PATH . 'modules/moder/tmp/money_output.tpl');
        $user = $db->Execute("SELECT * FROM admins WHERE id = '" . $_SESSION['user']['id'] . "'")->FetchRow();
        if (isset($_REQUEST["query"])) {
            $content = str_replace('[query]', $_REQUEST["query"], $content);
        } else {
            $content = str_replace('[query]', "", $content);
        }
        if (isset($_REQUEST["error"])) {
            $content = str_replace('[error]', $_REQUEST["error"], $content);
        } else {
            $content = str_replace('[error]', "", $content);
        }

        $send = isset($_REQUEST["send"]) ? 1 : null;
        if (!$send) {
            $table = $bg = "";
            $num = 1;
            $withdrawal_first = null;
            $sum_tasks = $db->Execute("SELECT SUM(price) as sum FROM moders_money WHERE moder_id = '" . $_SESSION['user']["id"] . "'")->FetchRow();
            $balance = $sum_tasks['sum'];
            $withdrawal = $db->Execute("SELECT * FROM withdrawal WHERE uid='" . $user['id'] . "' ORDER BY date DESC");
            while ($res = $withdrawal->FetchRow()) {
                $balance -= $res["sum"];
                $three_days_last = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d") - 3, date("Y")));
                if ($num == 1 && $res["date"] > $three_days_last) {
                    $withdrawal_first = $res;
                }

                $bg = (($num % 2) == 0) ? "#f7f7f7" : "";
                $table .= '<tr style="background:' . $bg . '">';
                $table .= '<td>' . $res["date"] . '</td>';
                $table .= '<td>' . $res["sum"] . ' руб.</td>';
                $table .= '</tr>';
                $num++;
            }
            if (empty($withdrawal_first)) {
                $last_ticket = $db->Execute("SELECT * FROM tickets WHERE uid='" . $user['id'] . "' AND subject = 'Вывод средств' ORDER BY date DESC LIMIT 1")->FetchRow();
                $three_days_last = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 3, date("Y")));
                if ($last_ticket["date"] > $three_days_last) {
                    $withdrawal_first = $last_ticket;
                }
            }

            if (!empty($withdrawal_first)) {
                $date = date_create($withdrawal_first["date"]);
                date_add($date, date_interval_create_from_date_string('3 days'));
                $three_days_ago = date_format($date, 'Y-m-d H:i:s');
                $str = "<br /><span>Вывод средств возможен не чаще 1 раз в 3 суток</span><br />";
                if (isset($withdrawal_first["subject"]))
                    $str .= "<span>Вы запросили снятие средств <i>" . $withdrawal_first["date"] . "</i><br /> Следующий раз можно будет послать запрос не ранее <i>" . $three_days_ago . "</i>.</span><br />";
                else
                    $str .= "<span>Вы снимали средства <i>" . $withdrawal_first["date"] . "</i><br /> Следующий раз можно будет снять не ранее <i>" . $three_days_ago . "</i>.</span><br />";
                $content = str_replace('[form]', $str, $content);
            } else {
                $content = str_replace('[form]', file_get_contents(PATH . 'modules/moder/tmp/money_output_form.tpl'), $content);
            }

            $content = str_replace('[balance]', $balance, $content);
            $content = str_replace('[webmoney]', (!empty($user["wallet"]) ? $user["wallet"] : "Не указан (<i><a href='user.php?action=lk'>Изменить</a></i>)"), $content);
            $content = str_replace('[table]', $table, $content);
        } else {
            $sum = $_REQUEST["sum"];
            $balance = $_REQUEST["balance"];
            $date = date("Y-m-d");

            if ($sum <= $balance) {
                $msg = "Добрый день! Модератор " . $user["login"] . " просит вывести деньги. <br> Запрашиваемая сумма: $sum руб. <br> Кошелек: " . (!empty($user["wallet"]) ? $user["wallet"] : "Не указан") . "";
                $db->Execute("INSERT INTO tickets (uid, subject, q_theme, msg, date, status, site, tid) 
                                                VALUES (
                                                        " . $user["id"] . ", 
                                                        'Вывод средств', 
                                                        'Общими вопросами', 
                                                        '$msg', 
                                                        '$date', 
                                                        1, 
                                                        '', 
                                                        '')");
                $lastId = $db->Insert_ID();

                $this->_postman->admin->moderOutputMoney($user, $sum, $lastId);
                header('location: /user.php?action=money&action2=output&query=Запрос успешно отправлен');
                exit();
            } else {
                header('location: /user.php?action=money&action2=output&error=Запрашиваемая сумма меньше текущего баланса');
            }
        }

        return $content;
    }

    function lk($db) {
        $uid = (int) $_SESSION['user']['id'];
        $uinfo = $db->Execute("SELECT * FROM admins WHERE id=$uid")->FetchRow();
        if (@$_REQUEST['send']) {
            $fio = $db->escape($_REQUEST['fio']);
            $fio = "fio" . $fio;
            $knowus = $db->escape($_REQUEST['knowus']);
            $pass = $db->escape($_REQUEST['password']);
            $mail_period = $db->escape($_REQUEST['mail_period']);
            $wallet = $db->escape($_REQUEST['wallet']);
            $wallet_type = $db->escape($_REQUEST['wallet_type']);
            $icq = $db->escape($_REQUEST['icq']);
            $scype = $db->escape($_REQUEST['scype']);

            if ($pass) {
                $pass = md5($pass);
                $db->Execute("UPDATE admins SET pass='$pass', contacts='$fio', dostupy='$knowus', wallet_type='$wallet_type', wallet='$wallet', mail_period='$mail_period', icq='$icq', scype='$scype' WHERE id=$uid");
            } else {
                $db->Execute("UPDATE admins SET contacts='$fio', dostupy='$knowus', wallet_type='$wallet_type', wallet='$wallet', mail_period='$mail_period', icq='$icq', scype='$scype' WHERE id=$uid");
            }
            
            switch ($mail_period) {
                case 43200: $period = "Два раза в день";
                    break;
                case 86400: $period = "Раз в день";
                    break;
                case 259200: $period = "Раз в три дня";
                    break;
                case 604800: $period = "Раз в неделю";
                    break;
                default: $period = "Отписался от рассылки";
                    break;
            }

            $this->_postman->admin->userChangeData($uinfo);
            header("Location: /user.php?action=lk");
            exit();
        } else {
            $content = file_get_contents(PATH . 'modules/moder/tmp/lk.tpl');
            
            $content = str_replace('[fio]', substr($uinfo['contacts'], 3), $content);
            $content = str_replace('[knowus]', $uinfo['dostupy'], $content);
            $content = str_replace('[checked_' . $uinfo["wallet_type"] . ']', "selected='selected'", $content);
            $content = str_replace('[wallet]', $uinfo['wallet'], $content);
            $content = str_replace('[checked_' . $uinfo["mail_period"] . ']', "selected='selected'", $content);
            $content = str_replace('[icq]', $uinfo['icq'], $content);
            $content = str_replace('[scype]', $uinfo['scype'], $content);
        }

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

}

?>