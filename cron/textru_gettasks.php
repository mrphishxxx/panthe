<?php

include_once dirname(__FILE__) . '/../' . 'config.php';
include_once dirname(__FILE__) . '/../' . 'includes/mandrill/mandrill.php';
include_once dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
include_once dirname(__FILE__) . '/../' . 'includes/textRu.php';

error_reporting(E_ALL);

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');

$mandrill = new Mandrill(MANDRILL);
$message = $message_for_admin = array();
$message["text"] = $message_for_admin["text"] = "";
$message["from_email"] = $message_for_admin["from_email"] = "news@iforget.ru";
$message["from_name"] = $message_for_admin["from_name"] = "iforget";
$message["to"] = $message_for_admin["to"] = array();
$message["track_opens"] = $message_for_admin["track_opens"] = null;
$message["track_clicks"] = $message_for_admin["track_clicks"] = null;
$message["auto_text"] = $message_for_admin["auto_text"] = null;

$API = new APITextRu(TEXTRU_APIKEY);
$users = array();
$body = "";
$copywriters = $db->Execute('SELECT id, email FROM admins WHERE type = "copywriter" AND active = "1"')->GetAll();
foreach ($copywriters as $copywriter) {
    $users[$copywriter["id"]] = $copywriter;
}
$check_tasks = $db->Execute('SELECT * FROM zadaniya_new WHERE copywriter != 0 AND navyklad=1 AND textru_id IS NOT NULL')->GetAll();
if (!empty($check_tasks)) {
    foreach ($check_tasks as $task) {
        $errors = array();
        $error_for_copywriter = null;
        $result = $API->getResultPost($task["textru_id"], true);
        if (!is_string($result)) {
            $db->Execute('UPDATE zadaniya_new SET uniq = "' . $result->text_unique . '" WHERE id = ' . $task["id"]);
            if ($result->text_unique >= UNIQ_TEXT_FROM_COPYWRITER) {
                $seo_check = json_decode($result->seo_check);
                if ($seo_check->count_chars_without_space >= $task["nof_chars"]) {
                    //ВСЕ ОТЛИЧНО, ОТПРАВЛЯЕМ ЗАДАЧУ В САПУ
                    //если нет поля description, или оно больше 255 символов, то генерируем новое
                    if (empty($task["description"]) || mb_strlen($task["description"]) > 255) {
                        $task["description"] = mb_substr(substr($task["text"], 0, strpos($task["text"], ".")), 0, 225);
                    }
                    //проверяем все поля, которые отправятся в САПУ, 
                    if (!empty($task["title"]) && !empty($task["tema"]) && !empty($task["keywords"]) && !empty($task["description"]) && !empty($task["text"])) {
                        // SAPE 
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
                        
                        $data = xmlrpc_encode_request('performer.orderComplite', array((int) $task["sape_id"], array("title" => $task["title"], "header" => $task["tema"], "keywords" => $task["keywords"], "description" => $task["description"], "text" => $task["text"])), array('encoding' => 'UTF-8', 'escaping' => 'markup'));
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
                            // ВСЕ ВООБЩЕ СУПЕР. ЗАДАЧА ОТПРАВИЛАСЬ В САПУ, МЕНЯЕМ СТАТУС НА ВЫЛОЖЕНО
                            $db->Execute('UPDATE zadaniya_new SET vilojeno = "1", navyklad="0" WHERE id = ' . $task["id"]);
                        } else {
                            $request = json_decode($accept["faultString"]);
                            $data = xmlrpc_encode_request('performer.orderDetails', array((int) $task["sape_id"]));
                            if ($curl = curl_init()) {
                                curl_setopt($curl, CURLOPT_URL, "http://api.articles.sape.ru/performer/index/");
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl, CURLOPT_POST, true);
                                curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
                                curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
                                @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                                $detail = curl_exec($curl);
                                curl_close($curl);
                            }
                            $sape = xmlrpc_decode($detail);
                            if (isset($sape["status"]) && ($sape["status"] == 0 || $sape["status"] == 40 || $sape["status"] == 50)) {
                                $db->Execute('UPDATE zadaniya_new SET vilojeno = "1", navyklad="0" WHERE id = ' . $task["id"]);
                                continue;
                            }
                            if(isset($request->items)){
                                foreach ($request->items as $err_arr) {
                                    foreach ($err_arr as $err) {
                                        $errors[] = $err;
                                    }
                                }
                            }
                            if (empty($request) && isset($accept["faultString"])) {
                                $errors[] = $accept["faultString"];
                            }
                        }
                    } else {
                        //если проблема, отправляем Копирайтеру напоминание заполните поля
                        $error_for_copywriter = "Не все обязательные поля заполнены, проверьте такие поля как (Title, Keywords, Description, Заголовок статьи)";
                        $textru = $task["textru_id"];
                    }
                } else {
                    // МАЛО СИМВОЛОВ, ОТПРАВИМ ОШИБКУ КОПИРАЙТЕРУ
                    $error_for_copywriter = "Недостаточно символов - " . $seo_check->count_chars_without_space . ", а должно быть - " . $task["nof_chars"];
                    $textru = "NULL";
                }
            } else {
                // МАЛЕНЬКАЯ УНИКАЛЬНОСТЬ ТЕКСТА, ОТПРАВИМ ОШИБКУ КОПИРАЙТЕРУ
                $error_for_copywriter = "Недостаточная уникальность текста - " . $result->text_unique . "%, а должно быть - " . UNIQ_TEXT_FROM_COPYWRITER ."%";
                $textru = "NULL";
            }
        } else {
            $body .= "<br>Ошибка проверки ушикальности в TEXT.RU. Описание: " . $result . "<br>";
        }

        if (!empty($error_for_copywriter)) {
            $message["subject"] = "[Доработать задачу]";
            $message["html"] = $error_for_copywriter . "<br><a href='http://iforget.ru/copywriter.php?action=tasks&action2=edit&id=" . $task["id"] . "'>Задача - " . $task["id"] . "</a> вернулась на доработку!<br><br>";
            $message["to"][0] = array("email" => $users[$task["copywriter"]]["email"]);
            try {
                $mandrill->messages->send($message);
                echo $message["html"];
            } catch (Exception $e) {
                echo $e;
                echo 'ERROR: Сообщение не отправлено!';
                echo $message["html"];
            }
            $db->Execute('UPDATE zadaniya_new SET textru_id='.$textru.', rework="1", navyklad="0" WHERE id = ' . $task["id"]);
        }

        if (!empty($errors)) {
            $body .= "<br>Задание <a href='http://iforget.ru/admin.php?module=admins&action=articles&action2=edit&id=" . $task["id"] . "'>" . $task["id"] . "</a> поменяло статус на 'Готов'!<br/>
                     <br>Во время автоматической загрузке задачи в Sape произошкли ошибки:<br>";
            foreach ($errors as $err) {
                $body .= "error = " . $err . "<br>";
            }
        }
    }
}

if ($body != "") {
    $message_for_admin["subject"] = "[Ошибки отправления задач в Sape]";
    $message_for_admin["html"] = $body;
    $message_for_admin["to"][0] = array("email" => MAIL_ADMIN);
    $message_for_admin["to"][1] = array("email" => MAIL_DEVELOPER);
    try {
        $mandrill->messages->send($message_for_admin);
        echo $body;
    } catch (Exception $e) {
        echo 'ERROR: Сообщение не отправлено!';
        echo $body;
    }
}

die("\r\nend\r\n");
?>
