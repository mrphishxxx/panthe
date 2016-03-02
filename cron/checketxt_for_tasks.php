<?php

$starttime = time();

include(dirname(__FILE__) . '/../' . 'config.php');
include dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
include dirname(__FILE__) . '/../' . 'includes/IXR_Library.php';
require_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';
error_reporting(E_ALL);
$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$profil = "";
$query = $db->Execute("select * from admins where active=1");
$act_uid = array();
while ($row = $query->FetchRow()) {
    $act_uid[] = $row['id'];
}
$act_uid = "(" . implode(",", $act_uid) . ")";

$query = $db->Execute("select * from sayty where uid in $act_uid");
$site_ids = $task_site = array();
while ($row = $query->FetchRow()) {
    $site_ids[] = $row['id'];
    $task_site[$row['id']] = $row['url'];
}
$site_ids = "(" . implode(",", $site_ids) . ")";

$all = $db->Execute("select * from zadaniya where (vipolneno=0 AND etxt=1)  AND (sid in $site_ids) order by date DESC, id");
$temp = $etxt_list = $end_etxt = array();
$profil .= microtime() . "  - AFTER QUERY 1, 2, 3" . "\r\n";

/*   Вытаскиваем из ЕТХТ ID всех задач со статусом "НА ПРОВЕРКЕ"   */
$pass = ETXT_PASS;
$params = array('method' => 'tasks.listTasks', 'token' => '29aa0eec2c77dd6d06e23b3faaef9eed', 'status' => '3', 'only_id' => 1);
ksort($params);
$data = array();
$data2 = array();
foreach ($params as $k => $v) {
    $data[] = $k . '=' . $v;
    $data2[] = $k . '=' . urlencode($v);
}
$sign = md5(implode('', $data) . md5($pass . 'api-pass'));
$url = 'https://www.etxt.ru/api/json/?' . implode('&', $data2) . '&sign=' . $sign;
$profil .= microtime() . "  - BEFORE 1 curl - listTasks" . "\r\n";
if ($curl = curl_init()) {
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $out = curl_exec($curl);
    curl_close($curl);
}
$temp = json_decode($out);

if (!empty($temp) && is_array($temp)) {
    foreach ($temp as $one) {
        $one = (array) $one;
        $etxt_list[] = $one["id"];
    }
}
$profil .= microtime() . "  - AFTER 1 curl - listTasks" . "\r\n";
/* Вытащили */
//print_r($etxt_list);

/*   Вытаскиваем из ЕТХТ ID всех задач со статусом "ВЫПОЛНЕНО"   */
$end_etxt = $temp = array();
$count = 0;
$profil .= microtime() . "  - BEFORE N curl in etxt (in archive)" . "\r\n";
while (TRUE) {
    $pass = ETXT_PASS;
    $params = array('method' => 'tasks.listTasks', 'token' => '29aa0eec2c77dd6d06e23b3faaef9eed', 'status' => '4', 'from' => $count, 'only_id' => 1);
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
        $out = curl_exec($curl);
        curl_close($curl);
    }
    //$out = file_get_contents($url);
    $end_list = json_decode($out);
    $end_list = (array) $end_list;
    $profil .= microtime() . "  - REQUEST " . $count . "\r\n";
    if (!@$end_list["error"]) {
        $temp[] = $end_list;
    } else {
        break;
    }
    $count += 100;
}
foreach ($temp as $one) {
    foreach ($one as $two) {
        $two = (array) $two;
        $end_etxt[] = $two["id"];
    }
}
$profil .= microtime() . "  - AFTER N curl in etxt (in archive) " . "\r\n";
/* Вытащили */

$body2 = "Выгружены тексты для задач:<br><br>";
$num = 0;
$etxt_users_array = array();
while ($res = $all->FetchRow()) {
    if ((in_array($res['task_id'], $etxt_list) || in_array($res['task_id'], $end_etxt)) && !$res['text']) {
        $params = array('method' => 'tasks.getResults', 'token' => '29aa0eec2c77dd6d06e23b3faaef9eed', 'id' => $res['task_id']);
        ksort($params);
        $data = array();
        $data2 = array();
        foreach ($params as $k => $v) {
            $data[] = $k . '=' . $v;
            $data2[] = $k . '=' . urlencode($v);
        }
        $profil .= microtime() . "  - CURL FOR TASK # " . $res['task_id'] . "\r\n";
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
        $task = (array) $task_stat->$res['task_id'];
        if (empty($task)) {
            $task = json_decode($cur_out);
        }
        $file_href = "";
        $file_path = "";
        $uniq = $ID = 0;
        foreach ($task as $vt) {
            $vt = (array) $vt;
            $file_href = (array) $vt['files'];
            $file_href_parts = (array) @$file_href['file'];
            if (@$file_href_parts['path']) {
                $file_path = $file_href_parts['path'];
                $uniq = $file_href_parts['per_antiplagiat'];
            } else {
                $file_href_parts = (array) $file_href['text'];
                $file_path = $file_href_parts['path'];
                $uniq = @$file_href_parts['per_antiplagiat'];
            }

            // I. Вытаскиваем из ЕТХТ исполнителя работы (в дальнейшем сохраняем его в БД)
            if (isset($vt["id_user"]) && !array_key_exists($vt["id_user"], $etxt_users_array)) {
                $etxt_users = get_etxt_request("users.getUser", $vt["id_user"]);
                foreach ($etxt_users as $etxt_user) {
                    $etxt_users_array[$etxt_user->id_user] = array(
                        "etxt_id" => $etxt_user->id_user,
                        "login" => $etxt_user->login,
                        "fio" => $etxt_user->fio,
                        "description" => $etxt_user->description,
                        "country" => $etxt_user->country,
                        "city" => $etxt_user->city,
                        "regdate" => $etxt_user->regdate,
                        "rate" => $etxt_user->rate,
                        "photo" => $etxt_user->photo,
                        "group" => $etxt_user->group,
                        "categories" => array()
                    );
                    foreach ($etxt_user->categories as $etxt_user_cat) {
                        if ($etxt_user_cat != "object") {
                            $etxt_users_array[$etxt_user->id_user]["categories"][] = $etxt_user_cat;
                        }
                    }
                }
            }
            // Конец I. 

            if ($vt["status"] == 2) {
                $email = null;
                $cur_text = file_get_contents($file_path);
                $cur_text = iconv('cp1251', 'utf-8', $cur_text);
                $cur_text = str_replace('&nbsp;', ' ', $cur_text);
                $db->Execute("UPDATE zadaniya SET vrabote='0', navyklad='1', uniq='$uniq', text='" . $cur_text . "' WHERE id=" . $res['id']);

                $site = $db->Execute("SELECT * FROM sayty WHERE id = " . $res["sid"])->FetchRow();
                $url = explode("/wp-", $site["url_admin"]);

                $url_connect = @$url[0];

                // Выкладываем готовую статью на сайт, если это Wordpress
                //  --> Запрещаем отправление статьи для пользователя "me05"
                /*$users_not_wordpress_autosend = array(330 => 330, 649 => 649, 1463 => 1463);
                if (!empty($url_connect) && !empty($cur_text) && $site["cms"] == "Wordpress" && !array_search($res["uid"], $users_not_wordpress_autosend)) {
                    $client = new IXR_Client($url_connect . '/xmlrpc.php');
                    if (!$client->query('wp.getCategories', '', $site["login"], $site["pass"])) {
                        echo($client->getErrorCode() . ":" . $client->getErrorMessage()) . "<br>";
                    } else {
                        $cats = $client->getResponse();
                        if (!empty($cats)) {
                            $content['title'] = $res['tema'];
                            $content['categories'] = array();
                            $content['description'] = $cur_text;
                            $content['mt_allow_comments'] = 1;
                            if ($res['keywords']) {
                                $keywords = explode(",", $res['keywords']);
                                $content['mt_keywords'] = array();
                                foreach ($keywords as $word) {
                                    $content['mt_keywords'][] = $word;
                                }
                            }

                            if (!$client->query('metaWeblog.newPost', '', $site["login"], $site["pass"], $content, false)) {
                                echo ('Error while creating a new post' . $client->getErrorCode() . " : " . $client->getErrorMessage());
                            }
                            $ID = $client->getResponse();
                        }
                    }
                }*/

                $body2 .= $res['id'] . "<br>";
                $num++;

                $moder = $db->Execute("SELECT a.* FROM sayty s LEFT JOIN admins a ON a.id = s.moder_id WHERE s.id = " . $res["sid"])->FetchRow();
                if ($res['uid'] == 330) { // только для пользователя "palexa" (id = 330)
                    $body = "Добрый день!<br/><br/>
				Ваше задание для сайта " . $task_site[$res['sid']] . " на сайте iForget с номером <a href='http://iforget.ru/user.php?module=user&action=zadaniya&uid=" . $res['uid'] . "&sid=" . $res['sid'] . "&action2=edit&id=" . $res['id'] . "'>" . $res['id'] . "</a> поменяло статус: &laquo;На Выкладку&raquo;!
				";
                    $file_name = $this->createXLSForUser($res['id'], $res['sistema'], $res['ankor'], $res['url'], $res['url_statyi'], $res['tema'], $cur_text);
                    $f1 = fopen($file_name, "rb");
                    $message["attachments"] = array();
                    $message["attachments"][] = array("type" => "text/plain", "name" => "text_file.xlsx", "content" => base64_encode(fread($f1, filesize($file_name))));
                    $email = "ap@al-pa.com";
                    $profil .= microtime() . "  - send mail palexa " . "\r\n";
                } elseif (!empty($moder)) {
                    $profil .= microtime() . "  - send mail moder " . "\r\n";
                    $email = $moder['email'];
                    $body = "Добрый день!<br/><br/>
				Ваше задание для сайта " . $task_site[$res['sid']] . " на сайте iForget с номером <a href='http://iforget.ru/user.php?module=user&action=zadaniya_moder&uid=" . $res['uid'] . "&sid=" . $res['sid'] . "&action2=edit&id=" . $res['id'] . "'>" . $res['id'] . "</a> поменяло статус: &laquo;На Выкладку&raquo;!";
                    /*if (isset($ID) && !empty($ID)) {
                        $body .= "<br />На сайте клиента создан пост под названием '" . $res['tema'] . "'. Текст задачи уже выложен. Проверьте корректность текста, соответствие рубрики и др.";
                    }*/
                }
                if (!empty($email)) {
                    $body .= "<br /><br /> Оставить и почитать отзывы Вы сможете в нашей ветке на <a href='http://searchengines.guru/showthread.php?p=12378271'>серчах</a><br/><br/>С уважением,<br/>Администрация проекта iForget.";
                    $subject = "[на выкладывании]";
                    $message["html"] = $body;
                    $message["text"] = "";
                    $message["subject"] = $subject;
                    $message["from_email"] = "news@iforget.ru";
                    $message["from_name"] = "iforget";
                    $message["to"][0] = array("email" => $email);
                    $message["track_opens"] = null;
                    $message["track_clicks"] = null;
                    $message["auto_text"] = null;

                    $mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
                    try {
                        $mandrill->messages->send($message);
                    } catch (Exception $e) {
                        echo $e;
                    }
                    $profil .= microtime() . "  - send mail " . "\r\n";
                }
            }
        }
        $profil .= microtime() . "  - AFTER CURL FOR TASK " . "\r\n";
    }
}

// II. Сохраняем в БД (таб. users_etxt) Исполнителей работы. (Нужно для оценки уровня текста) 
foreach ($etxt_users_array as $etxt_user_id => $etxt_user) {
    $exists_user = $db->Execute("SELECT * FROM users_etxt WHERE etxt_id=" . $etxt_user_id)->FetchRow();
    if (empty($exists_user)) {
        $db->Execute("INSERT INTO users_etxt (`etxt_id`, `login`, `fio`, `description`, `country`, `city`, `regdate`, `rate`, `photo`, `group`, `categories`) "
                . "VALUES ('" . $etxt_user_id . "', '" . $etxt_user['login'] . "', '" . addslashes($etxt_user['fio']) . "', '" . addslashes($etxt_user['description']) . "', '" . $etxt_user['country'] . "', '" . $etxt_user['city'] . "', '" . $etxt_user['regdate'] . "', '" . $etxt_user['rate'] . "', '" . $etxt_user['photo'] . "', '" . $etxt_user['group'] . "', '" . json_encode($etxt_user['categories']) . "')");
    }
}
// Конец II.

if ($num == 0) {
    $body2 = "Нет ни одного готового текста для задач!<br>";
}
$body2 .= "<br>Date: " . date("d-m-Y H:i:s");
$mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
$message = array();
$message["html"] = $body2;
$message["text"] = "";
$message["subject"] = "$num текстов для Всех задач";
$message["from_email"] = "news@iforget.ru";
$message["from_name"] = "iforget";
$message["to"] = array();
$message["to"][1] = array("email" => MAIL_DEVELOPER);
//$message["to"][0] = array("email" => MAIL_ADMIN);
$message["track_opens"] = null;
$message["track_clicks"] = null;
$message["auto_text"] = null;

try {
    //$mandrill->messages->send($message);
} catch (Exception $e) {
    echo $e;
}
echo $body2;
echo $num . "задач";
$profil .= microtime() . "  - END FUNCTION" . "\r\n";
$endtime = time() - $starttime;
if ($endtime > 6) {
    $profil .= "ALL TIME - " . $endtime;
    $file = dirname(__FILE__) . '/../' . 'temp_file/checketxt_tasks/' . $endtime . '-' . '(' . time() . ').txt';
    file_put_contents($file, $profil);
}
echo "ALL TIME - " . $endtime;
exit();

function createXLSForUser($id = null, $sistema = null, $ankor = null, $url = null, $url_statyi = null, $tema = null, $text = null) {
    require_once dirname(__FILE__) . '/../' . 'includes/PHPExcel.php';
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
    $file_name = dirname(__FILE__) . '/../' . 'files/' . $id . '-' . time() . '.xlsx';
    $objWriter->save($file_name);
    return $file_name;
}

function get_etxt_request($method = "", $user_id = null) {
    $params = array('method' => $method, 'token' => ETXT_TOKEN, 'id' => $user_id);
    ksort($params);
    $data = array();
    $data2 = array();
    foreach ($params as $k => $v) {
        $data[] = $k . '=' . $v;
        $data2[] = $k . '=' . urlencode($v);
    }
    $sign = md5(implode('', $data) . md5(ETXT_PASS . 'api-pass'));
    $url_etxt = 'https://www.etxt.ru/api/json/?' . implode('&', $data2) . '&sign=' . $sign;
    if ($curl = curl_init()) {
        curl_setopt($curl, CURLOPT_URL, $url_etxt);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $cur_out = curl_exec($curl);
        curl_close($curl);
    }
    $out = json_decode($cur_out);
    return $out;
}
?>
