<?php

/**
 * Description of FromAdmin
 *
 * @author Abashev V. Alexey
 */
class FromAdmin {
    private $db;
    private $smarty;
    private $mandrill;
    private $message = array();
    private $admins_mail = array();
    private $TEMPLATE_PATH;
    
    protected $get_text = false;
    
    public function __construct($template_path, $smarty, $db, $mandrill, $message, $admins_mail) {
        $this->db = $db;
        $this->smarty = $smarty;
        $this->message = $message;
        $this->mandrill = $mandrill;
        $this->admins_mail = $admins_mail;
        $this->TEMPLATE_PATH = $template_path . "fromAdmin/";
        
        $this->message["to"] = array();
        //$this->debugging(true);
    }
    
    public function sendEmail() {
        if($this->get_text == false) {
            try {
                $this->mandrill->messages->send($this->message);
                return NULL;
            } catch (Exception $e) {
                return "Письмо не отправлено! Возникли проблемы! Error: " . $e;
            }
        } else {
            return $this->message["html"];
        }
    }
    
    public function debugging($debug) {
        if ($debug) {
            $this->admins_mail[] = array("email" => EMAIL_SUPPORT, "name" => "Abashev V. Alexey");
        }
    }
    
    public function Test($name = "Name Test"){
        $this->smarty->assign('user', $name);
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "test.tpl");
        return $this->sendEmail();
    }
    
    public function newUser($id = 0, $login = "LOGIN", $email = "EMAIL"){
        $this->smarty->assign('id', $id);
        $this->smarty->assign('login', $login);
        $this->smarty->assign('email', $email);
        
        $this->message["to"] = $this->admins_mail;
        $this->message["subject"] = "[Зарегистрировался новый пользователь]";
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "new_user.tpl");
        return $this->sendEmail();
    }
    
    public function ticketAdd($subject = null){
        $this->message["to"] = $this->admins_mail;
        $this->message["subject"] = (!empty($subject)? $subject : "[Новый тикет в системе]");
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "ticket_add.tpl");
        return $this->sendEmail();
    }
    
    public function ticketEdit($id = 0, $subject = "'Name Ticket'"){
        $this->smarty->assign('id', $id);
        $this->smarty->assign('subject', $subject);
        
        $this->message["to"] = $this->admins_mail;
        $this->message["subject"] = "[Тикет отредактирован]";
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "ticket_edit.tpl");
        return $this->sendEmail();
    }
    
    public function ticketAnswer($id = 0, $type = "user"){
        $this->smarty->assign('id', $id);
        $this->smarty->assign('type', $type);
        
        $this->message["to"] = $this->admins_mail;
        $this->message["subject"] = "[Новое сообщение от ".(($type == 'user')? 'пользователя' : 'копирайтера')."]";
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "ticket_answer.tpl");
        return $this->sendEmail();
    }
    
    public function taskStatusVilojeno($site = array(), $task = array()) {
        $this->smarty->assign('id', (isset($task["id"]) ? $task["id"] : "'#NUMBER'"));
        $this->smarty->assign('uid', (isset($task["uid"]) ? $task["uid"] : 0));
        $this->smarty->assign('sid', (isset($task["sid"]) ? $task["sid"] : 0));
        $this->smarty->assign('site_url', (isset($site["url"]) ? $site["url"] : "'URL SITE"));
        
        $this->message["subject"] = "[Статус задачи изменился на 'Выложено']";
        $this->message["to"] = $this->admins_mail;
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "task_status_vilojeno.tpl");
        return $this->sendEmail();
    }
    
    public function priceIncreaseUsers($users = array()) {
        $this->smarty->assign('users', $users);
        
        $this->message["subject"] = "[Изменение цен для пользователей]";
        $this->message["to"] = $this->admins_mail;
        $this->message["to"][] = array("email" => "ostin.odept@gmail.com", "name" => "Роман");
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "price_increase_users.tpl");
        return $this->sendEmail();
    }
    
    public function userUsedPromoCode($user = array(), $promocode = "PROMOCODE"){
        $this->smarty->assign('user_id', (isset($user["id"]) ? $user["id"] : 0));
        $this->smarty->assign('user_login', (isset($user["login"]) ? $user["login"] : "LOGIN"));
        $this->smarty->assign('promocode', $promocode);
        
        $this->message["to"] = $this->admins_mail;
        $this->message["subject"] = "[Вебмастер воспользовался промокодом]";
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "user_used_promocode.tpl");
        return $this->sendEmail();
    }
    
    public function userChangemail($user_login = "'LOGIN'", $email = "'EMAIL'") {
        $this->smarty->assign('user_login', $user_login);
        $this->smarty->assign('email', $email);
        
        $this->message["subject"] = "[Пользователь сменил почту]";
        $this->message["to"] = $this->admins_mail;
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "user_changemail.tpl");
        return $this->sendEmail();
    }
    
    public function userAddBurse($user = array(), $burse = "BURSE", $login = "LOGIN", $pass = "PASSWORD") {
        $this->smarty->assign('user_login', (isset($user["login"]) ? $user["login"] : "LOGIN"));
        $this->smarty->assign('uid', (isset($user["id"]) ? $user["id"] : "0"));
        $this->smarty->assign('burse', $burse);
        $this->smarty->assign('login', $login);
        $this->smarty->assign('pass', $pass);
        
        $this->message["subject"] = "[Добавилась биржа]";
        $this->message["to"] = $this->admins_mail;
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "user_add_burse.tpl");
        return $this->sendEmail();
    }
    
    public function userEditBurse($user = array(), $login = "LOGIN", $pass = "PASSWORD") {
        $this->smarty->assign('user_login', (isset($user["login"]) ? $user["login"] : "LOGIN"));
        $this->smarty->assign('uid', (isset($user["id"]) ? $user["id"] : "0"));
        $this->smarty->assign('login', $login);
        $this->smarty->assign('pass', $pass);
        
        $this->message["subject"] = "[Пользователь " . (isset($user["login"]) ? $user["login"] : "") . " изменил данные от биржи]";
        $this->message["to"] = $this->admins_mail;
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "user_edit_burse.tpl");
        return $this->sendEmail();
    }
    
    public function userAddSitesFromBurse($uid = 0, $sites = array(0 => "URL_SITE", 1 => "URL_SITE")) {
        $this->smarty->assign('uid', $uid);
        $this->smarty->assign('sites', $sites);
        
        $this->message["subject"] = "[Новые сайты в системе]";
        $this->message["to"] = $this->admins_mail;
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "user_add_site_from_burse.tpl");
        return $this->sendEmail();
    }
    
    public function userAddSite($uid = 0, $sid = 0, $site_url = "URL_SITE") {
        $this->smarty->assign('uid', $uid);
        $this->smarty->assign('sid', $sid);
        $this->smarty->assign('site_url', $site_url);
        
        $this->message["subject"] = "[Новый сайт в системе]";
        $this->message["to"] = $this->admins_mail;
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "user_add_site.tpl");
        return $this->sendEmail();
    }
    
    public function userEditSite($user = array(), $old = array("id" => 0)) {
        $new = $this->db->Execute("SELECT * FROM sayty WHERE id=" . $old["id"])->FetchRow();
        $this->smarty->assign('new', $new);
        $this->smarty->assign('old', $old);
        
        $this->smarty->assign('user_login', (isset($user["login"]) ? $user["login"] : "LOGIN"));
        $this->smarty->assign('uid', (isset($user["id"]) ? $user["id"] : "0"));
        
        $this->smarty->assign('site_url', (isset($old["url"]) ? $old["url"] : "URL_SITE"));
        $this->smarty->assign('id', (isset($old["id"]) ? $old["id"] : "0"));
        
        $this->message["subject"] = "[Изменения в карточке сайта]";
        $this->message["to"] = $this->admins_mail;
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "user_edit_site.tpl");
        if($new != $old) {
            return $this->sendEmail();
        }
    }
    
    public function userAddTask($uid = 0, $sid = 0, $site_url = "'URL_SITE'") {
        $this->smarty->assign('uid', $uid);
        $this->smarty->assign('sid', $sid);
        $this->smarty->assign('site_url', $site_url);
        
        $this->message["subject"] = "[Добавлено новое задание]";
        $this->message["to"] = $this->admins_mail;
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "user_add_task.tpl");
        return $this->sendEmail();
    }
    
    public function userGoPayment($uid = 0, $user_login = "'LOGIN'", $promo = "'PROMO'") {
        $this->smarty->assign('uid', $uid);
        $this->smarty->assign('user_login', $user_login);
        $this->smarty->assign('promo', $promo);
        
        $this->message["subject"] = "[Вебмастер воспользовался промокодом]";
        $this->message["to"] = $this->admins_mail;
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "user_go_payment.tpl");
        return $this->sendEmail();
    }
    
    public function userChangeData($old = array("id" => 0)) {
        $new = $this->db->Execute("SELECT * FROM admins WHERE id=" . $old["id"])->FetchRow();
        $this->smarty->assign('new', $new);
        $this->smarty->assign('old', $old);
        $this->smarty->assign('login', (isset($old["login"])? $old["login"] : "'LOGIN'"));
        
        $this->message["subject"] = "[Пользователь изменил свои данные]";
        $this->message["to"] = $this->admins_mail;
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "user_change_data.tpl");
        return $this->sendEmail();
    }
    
    public function userOutputMoney($user = array(), $summ = 0) {
        $this->smarty->assign('uid', (isset($user["id"]) ? $user["id"] : 0));
        $this->smarty->assign('login', (isset($user["login"]) ? $user["login"] : "'LOGIN'"));
        $this->smarty->assign('summ', $summ);
        
        $this->message["subject"] = "[Запрос на вывод средств пользователем]";
        $this->message["to"] = $this->admins_mail;
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "user_output_money.tpl");
        return $this->sendEmail();
    }
    
    public function userInsertThemesTask($user = array(), $task_id = 0, $sid = 0) {
        $this->smarty->assign('uid', (isset($user["id"]) ? $user["id"] : 0));
        $this->smarty->assign('sid', $sid);
        $this->smarty->assign('login', (isset($user["login"]) ? $user["login"] : "'LOGIN'"));
        $this->smarty->assign('id', $task_id);
        
        $this->message["subject"] = "[Задача может быть отправлена в работу]";
        $this->message["to"] = array(0 => "profi-sale@yandex.ru");
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "user_insert_themes_task.tpl");
        return $this->sendEmail();
    }
    
    public function moderChangeTask($task = array(), $user = array()) {
        $this->smarty->assign('id', (isset($task["id"]) ? $task["id"] : 0));
        $this->smarty->assign('uid', (isset($task["uid"]) ? $task["uid"] : 0));
        $this->smarty->assign('sid', (isset($task["sid"]) ? $task["sid"] : 0));
        $this->smarty->assign('login', (isset($user["login"]) ? $user["login"] : "'LOGIN'"));
        
        $this->message["subject"] = "[Модератор изменил задание]";
        $this->message["text"] = "отправлена ссылка задачи № ";
        $this->message["to"] = $this->admins_mail;
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "moder_change_task.tpl");
        return $this->sendEmail();
    }
    
    public function moderChangeVikladComment($uid = 0, $url = "'URL_SITE'") {
        $this->smarty->assign('url', $url);
        $this->smarty->assign('uid', $uid);
        
        $this->message["subject"] = "[Выкладывальщик оставил комментарий к сайту]";
        $this->message["to"] = $this->admins_mail;
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "moder_change_viklad_comment.tpl");
        return $this->sendEmail();
    }
    
    public function moderOutputMoney($user = array(), $summ = 0, $lastId = 0) {
        $this->smarty->assign('wallet', (isset($user["wallet"]) ? $user["wallet"] : "Не известно"));
        $this->smarty->assign('login', (isset($user["login"]) ? $user["login"] : "'LOGIN'"));
        $this->smarty->assign('summ', $summ);
        $this->smarty->assign('lastId', $lastId);
        
        $this->message["subject"] = "[Запрос на вывод средств модератором]";
        $this->message["to"] = $this->admins_mail;
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "moder_output_money.tpl");
        return $this->sendEmail();
    }
    
    public function copywriterChangedData($old = array("id" => 0)) {
        $new = $this->db->Execute("SELECT * FROM admins WHERE id=" . $old["id"])->FetchRow();
        $this->smarty->assign('new', $new);
        $this->smarty->assign('old', $old);
        $this->smarty->assign('login', (isset($old["login"])? $old["login"] : "'LOGIN'"));
        
        $this->message["subject"] = "[Копирайтер изменил свои данные]";
        $this->message["to"] = $this->admins_mail;
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "copywriter_change_data.tpl");
        return $this->sendEmail();
    }
    
    public function copywriterAddedTask($id_task = "#", $login = "COPYWRITER") {
        $this->smarty->assign('id', $id_task);
        $this->smarty->assign('login', $login);
        
        $this->message["subject"] = "[Копирайтер взял задачу]";
        $this->message["to"] = $this->admins_mail;
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "copywriter_added_task.tpl");
        return $this->sendEmail();
    }
    
    public function copywriterCanceledTask($id_task = "#", $login = "COPYWRITER", $limit = 3, $banned = false) {
        $this->smarty->assign('id', $id_task);
        $this->smarty->assign('login', $login);
        $this->smarty->assign('banned', $banned);
        $this->smarty->assign('limit', $limit);
        
        $this->message["subject"] = "[Копирайтер отменил задачу]";
        $this->message["to"] = $this->admins_mail;
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "copywriter_canceled_task.tpl");
        return $this->sendEmail();
    }
    
    public function copywriterFinishedTask($id_task = "#", $login = "COPYWRITER", $status = "Готов", $request = array(), $empty_data = FALSE) {
        $this->smarty->assign('id', $id_task);
        $this->smarty->assign('login', $login);
        $this->smarty->assign('status', $status);
        $this->smarty->assign('errors', $request);
        $this->smarty->assign('empty_data', $empty_data);
        
        $this->message["subject"] = ($status = "Выложено") ? "[Задача отправлена в Sape]" : ((!$empty_data && empty($request))? "[Задача доработана копирайтером]" : "[Ошибка отправки в Sape]");
        $this->message["text"] = "Копирайтер отправил текст на выкладывание";
                        
        $this->message["to"] = $this->admins_mail;
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "copywriter_finished_task.tpl");
        return $this->sendEmail();
    }
    
    public function copywriterSentMessage($id_task = "#", $login = "COPYWRITER", $message = "TEXT MESSAGE", $burse = FALSE) {
        $this->smarty->assign('id', $id_task);
        $this->smarty->assign('login', $login);
        $this->smarty->assign('message', $message);
        $this->smarty->assign('burse', $burse);
        
        $this->message["subject"] = "[Новое сообщение от копирайтера]";                        
        $this->message["to"] = $this->admins_mail;
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "copywriter_sent_message.tpl");
        return $this->sendEmail();
    }
    
    public function copywriterOutputMoney($user = array(), $summ = 0, $lastId = 0) {
        $this->smarty->assign('login', (isset($user["login"]) ? $user["login"] : "'LOGIN'"));
        $this->smarty->assign('wallet', (isset($user["wallet"]) ? $user["wallet"] : "Не известно"));
        $this->smarty->assign('summ', $summ);
        $this->smarty->assign('lastId', $lastId);
        
        $this->message["subject"] = "[Запрос на вывод средств копирайтером]";                        
        $this->message["to"] = $this->admins_mail;
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "copywriter_output_money.tpl");
        return $this->sendEmail();
    }
    
    public function userChangeWallet($user = array()) {
        $this->smarty->assign('login', (isset($user["login"]) ? $user["login"] : "'LOGIN'"));
        $this->smarty->assign('wallet', (isset($user["wallet"]) ? $user["wallet"] : ""));
        
        $this->message["subject"] = "[Запрос на смену кошелька]";                        
        $this->message["to"] = $this->admins_mail;
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "user_change_wallet.tpl");
        return $this->sendEmail();
    }
    
    
    public function getMailsName($name = null) {
        if (!empty($name)) {
            switch ($name) {
                case "ticketAdd": return "[Новый тикет в системе]";
                case "ticketEdit": return "[Тикет отредактирован]";
                case "ticketAnswer": return "[Новое сообщение от пользователя]";
                case "taskStatusVilojeno": return "[Статус задачи изменился на Выложено]";
                case "priceIncreaseUsers": return "[Изменение цен для пользователей]";
                case "userUsedPromoCode": return "[Вебмастер воспользовался промокодом]";
                case "userChangemail": return "[Пользователь сменил почту]";
                case "userAddBurse": return "[Добавилась биржа]";
                case "userEditBurse": return "[Пользователь 'Login' изменил данные от биржи]";
                case "userEditSite": return "[Изменения в карточке сайта]";
                case "userAddSite": return "[Новый сайт в системе]";
                case "userAddSitesFromBurse": return "[Новые сайты в системе (из биржи)]";
                case "userAddTask": return "[Добавлено новое задание]";
                case "userGoPayment": return "[Вебмастер воспользовался промокодом]";
                case "userInsertThemesTask": return "[Задача может быть отправлена в работу]";
                case "userChangeData": return "[Пользователь изменил свои данные]";
                case "userOutputMoney": return "[Запрос на вывод средств пользователем]";
                case "moderChangeTask": return "[Модератор изменил задание]";
                case "moderChangeVikladComment": return "[Выкладывальщик оставил комментарий к сайту]";
                case "moderOutputMoney": return "[Запрос на вывод средств модератором]";
                case "copywriterChangedData": return "[Копирайтер изменил свои данные]";
                case "copywriterAddedTask": return "[Копирайтер взял задачу]";
                case "copywriterCanceledTask": return "[Копирайтер отменил задачу]";
                case "copywriterFinishedTask": return "[Копирайтер выполнил задачу]";
                case "copywriterSentMessage": return "[Новое сообщение от копирайтера]";
                case "copywriterOutputMoney": return "[Запрос на вывод средств копирайтером]";
                case "userChangeWallet": return "[Запрос на смену кошелька]";
            }
        } else {
            return array(
                "ticketAdd" => '[Новый тикет в системе]',
                "ticketEdit" => '[Тикет отредактирован]',
                "ticketAnswer" => '[Новое сообщение от пользователя]',
                "taskStatusVilojeno" => '[Статус задачи изменился на "Выложено"]',
                "priceIncreaseUsers" => '[Изменение цен для пользователей]',
                "userUsedPromoCode" => "[Вебмастер воспользовался промокодом]",
                "userChangemail" => "[Пользователь сменил почту]",
                "userAddBurse" => "[Добавилась биржа]",
                "userEditBurse" => "[Пользователь 'Login' изменил данные от биржи]",
                "userAddSitesFromBurse" => "[Новые сайты в системе (из биржи)]",
                "userAddSite" => "[Новый сайт в системе]",
                "userEditSite" => "[Изменения в карточке сайта]",
                "userAddTask" => "[Добавлено новое задание]",
                "userGoPayment" => "[Вебмастер воспользовался промокодом]",
                "userChangeData" => "[Пользователь изменил свои данные]",
                "userOutputMoney" => "[Запрос на вывод средств пользователем]",
                "moderChangeTask" => "[Модератор изменил задание]",
                "moderChangeVikladComment" => "[Выкладывальщик оставил комментарий к сайту]",
                "moderOutputMoney" => "[Запрос на вывод средств модератором]",
                "copywriterChangedData" => "[Копирайтер изменил свои данные]",
                "copywriterAddedTask" => "[Копирайтер взял задачу]",
                "copywriterCanceledTask" => "[Копирайтер отменил задачу]",
                "copywriterFinishedTask" => "[Копирайтер выполнил задачу]",
                "copywriterSentMessage" => "[Новое сообщение от копирайтера]",
                "copywriterOutputMoney" => "[Запрос на вывод средств копирайтером]",
                "userChangeWallet" => "[Запрос на смену кошелька]",
                "userInsertThemesTask" => "[Задача может быть отправлена в работу]"
            );
        }
    }
    public function getMail($name){
        if (!empty($name)) {
            $this->get_text = true;
            switch ($name) {
                case "test": return $this->Test();
                case "ticketAdd": return $this->ticketAdd();
                case "ticketEdit": return $this->ticketEdit();
                case "ticketAnswer": return $this->ticketAnswer();
                case "taskStatusVilojeno": return $this->taskStatusVilojeno();
                case "priceIncreaseUsers": return $this->priceIncreaseUsers();
                case "userUsedPromoCode": return $this->userUsedPromoCode();
                case "userChangemail": return $this->userChangemail();
                case "userAddBurse": return $this->userAddBurse();
                case "userEditBurse": return $this->userEditBurse();
                case "userEditSite": return $this->userEditSite();
                case "userAddSite": return $this->userAddSite();
                case "userAddSitesFromBurse": return $this->userAddSitesFromBurse();
                case "userAddTask": return $this->userAddTask();
                case "userGoPayment": return $this->userGoPayment();
                case "userChangeData": return $this->userChangeData();
                case "userOutputMoney": return $this->userOutputMoney();
                case "userInsertThemesTask": return $this->userInsertThemesTask();
                case "moderChangeTask": return $this->moderChangeTask();
                case "moderChangeVikladComment": return $this->moderChangeVikladComment();
                case "moderOutputMoney": return $this->moderOutputMoney();
                case "copywriterChangedData": return $this->copywriterChangedData();
                case "copywriterAddedTask": return $this->copywriterAddedTask();
                case "copywriterCanceledTask": return $this->copywriterCanceledTask();
                case "copywriterFinishedTask": return $this->copywriterFinishedTask();
                case "copywriterSentMessage": return $this->copywriterSentMessage();
                case "copywriterOutputMoney": return $this->copywriterOutputMoney();
                case "userChangeWallet": return $this->userChangeWallet();
            }
        }
    }
}
