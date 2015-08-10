<?php

/**
 * Description of FromUser
 *
 * @author Abashev V. Alexey
 */
class FromUser {

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
        $this->TEMPLATE_PATH = $template_path . "fromUser/";
        
        $f1 = fopen("images/header_bg.jpg", "rb");
        $f2 = fopen("images/logo_main.jpg", "rb");
        $this->message["images"] = array();
        $this->message["images"][] = array("type" => "image/jpg", "name" => "header_bg", "content" => base64_encode(fread($f1, filesize("images/header_bg.jpg"))));
        $this->message["images"][] = array("type" => "image/jpg", "name" => "logo_main", "content" => base64_encode(fread($f2, filesize("images/logo_main.jpg"))));
        
        $this->message["to"] = array();
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
        
        if ($email != "") {
            $this->message["to"][0] = array("email" => $email, "name" => $login);
        }
        $this->message["subject"] = "[Регистрация в iForget.ru!]";
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "registration.tpl");
        return $this->sendEmail();
    }
    
    public function ticketAdd($email = "", $login = "LOGIN", $lastId = 0) {
        $this->smarty->assign('lastId', $lastId);
        $this->smarty->assign('login', $login);
        $this->smarty->assign('get_text', $this->get_text);
        
        if ($email != "") {
            $this->message["to"][0] = array("email" => $email, "name" => $login);
        }
        $this->message["subject"] = "[Новый тикет $lastId в системе iForget!]";
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "ticket_add.tpl");
        return $this->sendEmail();
    }

    public function ticketAnswer($email = "", $login = "LOGIN", $tid = 0) {
        $this->smarty->assign('tid', $tid);
        $this->smarty->assign('login', $login);
        $this->smarty->assign('get_text', $this->get_text);
        
        if ($email != "") {
            $this->message["to"][0] = array("email" => $email, "name" => $login);
        }
        $this->message["subject"] = "[Появился ответ в тикете $tid от админимстрации iForget!]";
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "ticket_answer.tpl");
        return $this->sendEmail();
    }

    public function priceIncrease($users = array()) {
        $this->smarty->assign('get_text', $this->get_text);
        if (!empty($users)) {
            $this->message["to"] = $users;
        }
        $this->message["text"] = "До поднятие цены осталось 2 дня!";
        $this->message["subject"] = "[Внимание: увеличение цен в IForget!]";
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "price_increase.tpl");
        return $this->sendEmail();
    }

    public function getMailsName($name = null) {
        if (!empty($name)) {
            switch ($name) {
                case "registration": return "[Регистрация в iForget.ru!]";
                case "ticketAdd": return "[Новый тикет ID в системе iForget!]";
                case "ticketAnswer": return "[Появился ответ в тикете ID от администрации iForget!]";
                case "priceIncrease": return "[Внимание: увеличение цен в IForget!]";
            }
        } else {
            return array(
                "registration" => "[Регистрация в iForget.ru!]",
                "ticketAdd" => "[Новый тикет ID в системе iForget!]",
                "ticketAnswer" => "[Появился ответ в тикете ID от администрации iForget!]",
                "priceIncrease" => "[Внимание: увеличение цен в IForget!]"
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
                case "priceIncrease": return $this->priceIncrease();
            }
        }
    }

}
