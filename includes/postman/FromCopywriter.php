<?php

/**
 * Description of FromCopywriter
 *
 * @author Abashev V. Alexey
 */
class FromCopywriter {
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
        $this->TEMPLATE_PATH = $template_path . "fromCopywriter/";
        
        $this->debugging(true);
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
    
    public function ticketAdd($email = "", $login = "", $lastId = 0){
        $this->smarty->assign('lastId', $lastId);
        
        $this->message["to"][0] = array("email" => $email, "name" => $login);
        $this->message["subject"] = "[Новый тикет в системе iforget]";
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "ticket_add.tpl");
        return $this->sendEmail();
    }
    
    public function ticketAnswer($email = "", $login = "", $tid = 0) {
        $this->smarty->assign('tid', $tid);
        
        $this->message["to"][0] = array("email" => $email, "name" => $login);
        $this->message["subject"] = "[Сообщение в тикете от админимстрации IFORGET]";
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "ticket_answer.tpl");
        return $this->sendEmail();
    }
    
    public function sendMessage($id = null, $user = array(), $msg = "MESSAGE") {
        $login = (isset($user["login"]) ? $user["login"] : "");
        $email = (isset($user["email"]) ? $user["email"] : "");
        
        $this->smarty->assign('id', $id);
        $this->smarty->assign('msg', $msg);
        $this->smarty->assign('login', (isset($user["login"]) ? $user["login"] : "'LOGIN'"));
        
        $this->message["to"][0] = array("email" => $email, "name" => $login);
        $this->message["text"] = "Новый комментарий от администрации iForget в задачи под номером $id";
        $this->message["subject"] = "[Новый комментарий]";
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "send_message.tpl");
        return $this->sendEmail();
    }
    
    public function articlesChangeStatusDorabotka($id = 0, $user = array()) {
        $this->smarty->assign('id', $id);
        $login = (isset($user["login"]) ? $user["login"] : "");
        $email = (isset($user["email"]) ? $user["email"] : "");
        
        $this->message["to"][0] = array("email" => $email, "name" => $login);
        $this->message["subject"] = "[Задание вернулось на доработку]";
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "articles_change_status_dorabotka.tpl");
        return $this->sendEmail();
    }
    
    public function getMailsName($name = null) {
        if (!empty($name)) {
            switch ($name) {
                case "ticketAdd": return "[Новый тикет в системе iforget]";
                case "ticketAnswer": return "[Сообщение в тикете от админимстрации IFORGET]";
                case "sendMessage": return "[Новый комментарий]";
                case "articlesChangeStatusDorabotka": return "[Задание вернулось на доработку]";
            }
        } else {
            return array(
                "ticketAdd" => "[Новый тикет в системе iforget]",
                "ticketAnswer" => "[Сообщение в тикете от админимстрации IFORGET]",
                "sendMessage" => "[Новый комментарий]",
                "articlesChangeStatusDorabotka" => "[Задание вернулось на доработку]",
            );
        }
    }
    public function getMail($name){
        if (!empty($name)) {
            $this->get_text = true;
            switch ($name) {
                case "ticketAdd": return $this->ticketAdd();
                case "ticketAnswer": return $this->ticketAnswer();
                case "sendMessage": return $this->sendMessage();
                case "articlesChangeStatusDorabotka": return $this->articlesChangeStatusDorabotka();
            }
        }
    }
}
