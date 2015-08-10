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

        $this->message["to"] = array();
        $this->message["images"] = array();
        //$this->debugging(true);
    }

    public function sendEmail() {
        if ($this->get_text == false) {
            try {
                $this->send();
                return NULL;
            } catch (Exception $e) {
                return "Письмо не отправлено! Возникли проблемы! Error: " . $e;
            }
        } else {
            return $this->message["html"];
        }
    }

    private function send() {
        if (!empty($this->message["to"])) {
            $this->mandrill->messages->send($this->message);
        }
    }

    public function debugging($debug) {
        if ($debug) {
            $this->message["to"][] = array("email" => EMAIL_SUPPORT, "name" => "Abashev V. Alexey");
        }
    }
    
    public function registration($id = 0, $email = "", $login = "LOGIN", $password = "PASSWORD", $network = NULL) {
        $this->smarty->assign('id', $id);
        $this->smarty->assign('login', $login);
        $this->smarty->assign('password', $password);
        $this->smarty->assign('network', $network);
        $this->smarty->assign('get_text', $this->get_text);
        
        $f1 = fopen("images/header_bg.jpg", "rb");
        $f2 = fopen("images/logo_main.jpg", "rb");
        if ($email != "") {
            $this->message["to"][0] = array("email" => $email, "name" => $login);
        }
        $this->message["subject"] = "Поздравляем Вас с успешной регистрацией iforget.ru";
        $this->message["images"][] = array("type" => "image/jpg", "name" => "header_bg", "content" => base64_encode(fread($f1, filesize("images/header_bg.jpg"))));
        $this->message["images"][] = array("type" => "image/jpg", "name" => "logo_main", "content" => base64_encode(fread($f2, filesize("images/logo_main.jpg"))));
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "registration.tpl");
        return $this->sendEmail();
    }

    public function ticketAdd($email = "", $login = "", $lastId = 0) {
        $this->smarty->assign('lastId', $lastId);
        if ($email != "") {
            $this->message["to"][0] = array("email" => $email, "name" => $login);
        }
        $this->message["subject"] = "[Новый тикет в системе iforget]";
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "ticket_add.tpl");
        return $this->sendEmail();
    }

    public function ticketAnswer($email = "", $login = "", $tid = 0) {
        $this->smarty->assign('tid', $tid);
        if ($email != "") {
            $this->message["to"][0] = array("email" => $email, "name" => $login);
        }
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
        if ($email != "") {
            $this->message["to"][0] = array("email" => $email, "name" => $login);
        }
        $this->message["text"] = "Новый комментарий от администрации iForget в задачи под номером $id";
        $this->message["subject"] = "[Новый комментарий]";
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "send_message.tpl");
        return $this->sendEmail();
    }

    public function articlesChangeStatusDorabotka($id = 0, $user = array()) {
        $this->smarty->assign('id', $id);
        $login = (isset($user["login"]) ? $user["login"] : "");
        $email = (isset($user["email"]) ? $user["email"] : "");
        if ($email != "") {
            $this->message["to"][0] = array("email" => $email, "name" => $login);
        }
        $this->message["subject"] = "[Задание вернулось на доработку]";
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "articles_change_status_dorabotka.tpl");
        return $this->sendEmail();
    }

    public function getMailsName($name = null) {
        if (!empty($name)) {
            switch ($name) {
                case "registration": return "[Регистрация в iforget.ru!]";
                case "ticketAdd": return "[Новый тикет в системе iforget]";
                case "ticketAnswer": return "[Сообщение в тикете от админимстрации IFORGET]";
                case "sendMessage": return "[Новый комментарий]";
                case "articlesChangeStatusDorabotka": return "[Задание вернулось на доработку]";
            }
        } else {
            return array(
                "registration" => "[Регистрация в iforget.ru!]",
                "ticketAdd" => "[Новый тикет в системе iforget]",
                "ticketAnswer" => "[Сообщение в тикете от админимстрации IFORGET]",
                "sendMessage" => "[Новый комментарий]",
                "articlesChangeStatusDorabotka" => "[Задание вернулось на доработку]",
            );
        }
    }

    public function getMail($name) {
        if (!empty($name)) {
            $this->get_text = true;
            switch ($name) {
                case "registration": return $this->registration();
                case "ticketAdd": return $this->ticketAdd();
                case "ticketAnswer": return $this->ticketAnswer();
                case "sendMessage": return $this->sendMessage();
                case "articlesChangeStatusDorabotka": return $this->articlesChangeStatusDorabotka();
            }
        }
    }

}
