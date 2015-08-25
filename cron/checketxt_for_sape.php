<?php

$starttime = time();

error_reporting(E_ALL ^ E_NOTICE);
include(dirname(__FILE__) . '/../' . 'config.php');
include dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
require_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$profil = "";
/*   Вытаскиваем из ЕТХТ ID всех задач со статусом "НА ПРОВЕРКЕ"   */
$etxt_list = $checked = array();
$profil .= microtime() . "  - BEFORE curl requests in ETXT" . "\r\n";
$pass = ETXT_PASS;
$params = array('method' => 'tasks.listTasks', 'token' => '29aa0eec2c77dd6d06e23b3faaef9eed', 'status' => '3', 'only_id' => 1);
ksort($params);
$data = array();
$data2 = array();
foreach ($params as $k => $v) {
    $data[] = $k . '=' . $v;
    $data2[] = $k . '=' . urlencode($v);
}
$profil .= microtime() . "  - BEFORE 1 curl in etxt (in work)" . "\r\n";
$sign = md5(implode('', $data) . md5($pass . 'api-pass'));
$url_etxt = 'https://www.etxt.ru/api/json/?' . implode('&', $data2) . '&sign=' . $sign;
if ($curl = curl_init()) {
    curl_setopt($curl, CURLOPT_URL, $url_etxt);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $out = curl_exec($curl);
    curl_close($curl);
}
$list = (array) json_decode($out);
$profil .= microtime() . "  - AFTER 1 curl" . "\r\n";
if (!empty($list) && is_array($list)) {
    foreach ($list as $one) {
        $one = (array) $one;
        if (isset($one["id"]))
            $etxt_list[] = $one["id"];
    }
}
$profil .= microtime() . "  - BEFORE N curl in etxt (in archive)" . "\r\n";
/* Вытащили */
//print_r($etxt_list);

/*   Вытаскиваем из ЕТХТ ID всех задач со статусом "ВЫПОЛНЕНО"   */
$end_etxt = $temp = array();
$count = 0;
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
    $profil .= microtime() . "  - REQUEST -  $count " . "\r\n";
    $sign = md5(implode('', $data) . md5($pass . 'api-pass'));
    $url_etxt = 'https://www.etxt.ru/api/json/?' . implode('&', $data2) . '&sign=' . $sign;
    if ($curl = curl_init()) {
        curl_setopt($curl, CURLOPT_URL, $url_etxt);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $out = curl_exec($curl);
        curl_close($curl);
    }
    $end_list = json_decode($out);
    $end_list = (array) $end_list;

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
$profil .= microtime() . "  - AFTER curl requests in archive" . "\r\n";
/* Вытащили */
//print_r($end_etxt);

$body = "";
$error = false;

$all = $db->Execute("SELECT * FROM zadaniya_new WHERE (sape_id IS NOT NULL AND sape_id != 0) AND etxt=1 AND vrabote = 1 ORDER BY id DESC");
$profil .= microtime() . "  - QUERY 1 get ALL" . "\r\n";
$num = 0;
if (!empty($all)) {
    while ($value = $all->FetchRow()) {
        if (in_array($value['task_id'], $etxt_list) || in_array($value['task_id'], $end_etxt)) {
            $vilojeno = 0;
            $navyklad = 1;
            $params = array('method' => 'tasks.getResults', 'token' => '29aa0eec2c77dd6d06e23b3faaef9eed', 'id' => $value['task_id']);
            ksort($params);
            $data = array();
            $data2 = array();
            foreach ($params as $k => $v) {
                $data[] = $k . '=' . $v;
                $data2[] = $k . '=' . urlencode($v);
            }
            $profil .= microtime() . "  - BEFORE curl FROM ALL TASKS - " . $value['task_id'] . "\r\n";
            $sign = md5(implode('', $data) . md5($pass . 'api-pass'));
            $url_etxt = 'https://www.etxt.ru/api/json/?' . implode('&', $data2) . '&sign=' . $sign;
            if ($curl = curl_init()) {
                curl_setopt($curl, CURLOPT_URL, $url_etxt);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                $cur_out = curl_exec($curl);
                curl_close($curl);
            }
            $task_stat = json_decode($cur_out);
            $task = (array) $task_stat->$value['task_id'];
            $file_href = "";
            $file_path = "";
            if (empty($task)) {
                $task = json_decode($cur_out);
            }
            foreach ($task as $vt) {
                $vt = (array) $vt;
                $file_href = (array) @$vt['files'];
                $file_href_parts = (array) @$file_href['file'];
                if (@$file_href_parts['path']) {
                    $file_path = $file_href_parts['path'];
                } else {
                    $file_href_parts = (array) @$file_href['text'];
                    $file_path = @$file_href_parts['path'];
                }

                if (@$vt["status"] == 2) {
                    $cur_text = file_get_contents($file_path);
                    $cur_text_utf = iconv('cp1251', 'utf-8', $cur_text);
                    $cur_text_utf = str_replace('&nbsp;', ' ', $cur_text_utf);
                    $description = mb_substr($cur_text_utf, 0, mb_strpos($cur_text_utf, ".", 1, "utf-8"), "utf-8");
                    if (mb_strlen($description) < 150) {
                        $first = mb_strpos($cur_text_utf, ".", "utf-8");
                        $next = mb_strpos($cur_text_utf, ".", ((int) $first + 1), "utf-8");
                        $description .= mb_substr($cur_text_utf, $first, ($next - $first), "utf-8");
                    }
                    $description = str_replace("}", "", str_replace("{", "", $description));
                    if(mb_strlen($description) > 255) {
                        $description = mb_substr($description, 0, 254, "utf-8");
                    }

                    $pos_one = $pos_two = 0;
                    $ankor = $url = array();
                    // Собираем массив Анкоров и Ссылок
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i == 1) {
                            $a = $value["ankor"];
                            $u = $value["url"];
                        } else {
                            $a = $value["ankor$i"];
                            $u = $value["url$i"];
                        }
                        if (!empty($a) && !empty($u)) {
                            $url[] = $u;
                            $ankor[] = $a;
                        }
                    }
                    $cur_text_utf = $db->escape($cur_text_utf);
                    // Проходим по массиву Анкоров и заменяем в тексте конструкцию {анкор} на <a href='урл'>анкор</a>
                    for ($i = 0; $i < count($ankor); $i++) {
                        /*                         * * Старый вид поиска - пропускал конструкции {  анкор }
                         *   $pos_one = mb_strpos($cur_text_utf, "{" . $ankor . "}", 0, "utf-8");
                         *   $pos_two = mb_strlen($ankor, "utf-8");
                         * * */
                        //Ищем первое вхождения скобок
                        $pos_one = mb_strpos($cur_text_utf, "{", 0, "utf-8");
                        $pos_two = mb_strpos($cur_text_utf, "}", 0, "utf-8");

                        //Вырезаем то, что внутри них
                        $there = str_replace("{", "", str_replace("}", "", mb_substr($cur_text_utf, $pos_one, $pos_two - $pos_one, "utf-8")));

                        //Удаляем пробелы по краям, и ищем данный текст в массиве Анкоров
                        $key = array_search(trim($there), $ankor);

                        //Если все переменные найдены, производим замену!
                        if ($pos_one !== false && $pos_two !== false && $key !== false) {
                            $cur_text_utf = mb_substr_replace($cur_text_utf, '<a href="' . $url[$key] . '">' . $ankor[$key] . '</a>', $pos_one, $pos_two);
                        }
                    }
                    
                    if (!empty($value['title']) && !empty($value['tema']) && !empty($value['keywords']) && !empty($description) && !empty($cur_text_utf)) {
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

                        $data = xmlrpc_encode_request('performer.orderComplite', array((int) $value["sape_id"], array("title" => $value['title'], "header" => $value['tema'], "keywords" => $value['keywords'], "description" => $description, "text" => $cur_text_utf)), array('encoding' => 'UTF-8', 'escaping' => 'markup'));
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
                        if ($accept == true && !isset($accept["faultString"])) {
                            $vilojeno = 1;
                            $navyklad = 0;
                            //$body .= " Ссылка отправлена в SAPE - " . $value['id'] . "<br><br>";
                        } else {
                            $vilojeno = 0;
                            $navyklad = 1;
                            $request = array();
                            $errors = json_decode($accept["faultString"]);
                            foreach ($errors->items as $err_type => $err_arr) {
                                foreach ($err_arr as $key_err => $err) {
                                    $request[] = $err;
                                }
                            }
                            if (empty($request) && isset($accept["faultString"])) {
                                $request[] = $accept["faultString"];
                            }
                            $body .= "Задача #<a href='http://iforget.ru/admin.php?module=admins&action=articles&action2=edit&id=".$value['id']."'>" . $value['id'] . "</a> не отправлена в Sape (ОШИБКА отправления) - <br>";
                            foreach ($request as $err) {
                                $body .= "error = " . $err . "<br>";
                                if($err == "Failed to parse request") {
                                    //$body .= json_encode(array((int) $value["sape_id"], array("title" => $value['title'], "header" => $value['tema'], "keywords" => $value['keywords'], "description" => $description, "text" => $cur_text_utf)));
                                    $body .= "DESCRIPTION = $description <br>";
                                    $body .= "TEXT = $cur_text_utf <br>";                                    
                                }
                            }
                            $body .= "<br><br>";
                            $error = true;
                        }
                    } else {
                        $vilojeno = 0;
                        $navyklad = 1;

                        $body .= "Задача #<a href='http://iforget.ru/admin.php?module=admins&action=articles&action2=edit&id=".$value['id']."'>" . $value['id'] . "</a> не отправлена в Sape (не хватает данных) - <br>";
                        if (empty($value['title'])) {
                            $body .= "Поле Title пустое<br>";
                        }
                        if (empty($value['tema'])) {
                            $body .= "Поле Tema пустое<br>";
                        }
                        if (empty($value['keywords'])) {
                            $body .= "Поле Keywords пустое<br>";
                        }
                        if (empty($description)) {
                            $body .= "Поле Description пустое<br>";
                        }
                        if (empty($cur_text_utf)) {
                            $body .= "Поле Text пустое<br>";
                        }
                        $body .= "<br><br>";
                        $error = true;
                    }
                    $db->Execute("UPDATE zadaniya_new SET vrabote='0', navyklad='$navyklad', vilojeno='$vilojeno', description='" . $description . "', text='" . ($cur_text_utf) . "' WHERE id=" . $value['id']);
                    $num++;
                }
            }
            $profil .= microtime() . "  - AFTER curl - " . $value['task_id'] . "\r\n";
        }
    }
}
$profil .= microtime() . "  - AFTER OUTPUT ALL TASKS" . "\r\n";

if ($num != 0 && $error == true) {
    $body .= "\r\n<br>Date: " . date("d-m-Y H:i:s") . "<br>\r\n";
    $mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
    $message = array();
    $message["html"] = $body;
    $message["text"] = "";
    $message["subject"] = "Для Sape выгруженны тексты из ETXT. Есть ошибки отправления готовой задачи.";
    $message["from_email"] = "news@iforget.ru";
    $message["from_name"] = "iforget";
    $message["to"] = array();
    //$message["to"][1] = array("email" => MAIL_DEVELOPER);
    $message["to"][0] = array("email" => MAIL_ADMIN);
    $message["track_opens"] = null;
    $message["track_clicks"] = null;
    $message["auto_text"] = null;

    try {
        $mandrill->messages->send($message);
        echo $body;
        echo $num . " задач" . "\r\n";
    } catch (Exception $e) {
        echo $e;
    }
}
$profil .= microtime() . "  - END FUNCTION" . "\r\n";
$endtime = time() - $starttime;
if ($endtime > 6) {
    $profil .= "ALL TIME - " . $endtime;
    $file = dirname(__FILE__) . '/../' . 'temp_file/checketxt_sape/' . $endtime . '-' . '(' . time() . ').txt';
    file_put_contents($file, $profil);
}
echo "ALL TIME - " . $endtime;
echo "\r\n";
exit();

function mb_substr_replace($output, $replace, $posOpen, $posClose) {
    return mb_substr($output, 0, $posOpen, "utf-8") . $replace . mb_substr($output, $posClose + 1, NULL, "utf-8");
}

?>
