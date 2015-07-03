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

        $this->message["to"] = array();
        $this->debugging(true);
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

    public function priceIncrease($users = array()) {
        $this->smarty->assign('get_text', $this->get_text);
        if (!empty($users)) {
            $this->message["to"] = $users;
        }
        $this->message["text"] = "До поднятие цены осталось 2 дня!";
        $this->message["subject"] = "[Внимание: увеличение цен в IForget!]";
        $f1 = fopen("images/header_bg.jpg", "rb");
        $f2 = fopen("images/logo_main.jpg", "rb");
        $message["images"][] = array("type" => "image/jpg", "name" => "header_bg", "content" => base64_encode(fread($f1, filesize("images/header_bg.jpg"))));
        $message["images"][] = array("type" => "image/jpg", "name" => "logo_main", "content" => base64_encode(fread($f2, filesize("images/logo_main.jpg"))));
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "price_increase.tpl");
        return $this->sendEmail();
    }

    public function getMailsName($name = null) {
        if (!empty($name)) {
            switch ($name) {
                case "ticketAdd": return "[Новый тикет в системе iforget]";
                case "ticketAnswer": return "[Сообщение в тикете от админимстрации IFORGET]";
                case "priceIncrease": return "[Внимание: увеличение цен в IForget!]";
            }
        } else {
            return array(
                "ticketAdd" => "[Новый тикет в системе iforget]",
                "ticketAnswer" => "[Сообщение в тикете от админимстрации IFORGET]",
                "priceIncrease" => "[Внимание: увеличение цен в IForget!]"
            );
        }
    }

    public function getMail($name) {
        if (!empty($name)) {
            $this->get_text = true;
            switch ($name) {
                case "ticketAdd": return $this->ticketAdd();
                case "ticketAnswer": return $this->ticketAnswer();
                case "priceIncrease": return $this->priceIncrease();
            }
        }
    }

}
