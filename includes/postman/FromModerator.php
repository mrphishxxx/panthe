<?php

/**
 * Description of FromModerator
 *
 * @author Abashev V. Alexey
 */
class FromModerator {

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
        $this->TEMPLATE_PATH = $template_path . "fromModerator/";

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
            $this->message["to"][] = array("email" => EMAIL_SUPPORT, "name" => "Abashev V. Alexey");
        }
    }

    public function taskStatusNavyklad($user = array(), $site = array(), $task = array(), $wordpress_result = "WORDPRESS_ID") {
        $login = (isset($user["login"]) ? $user["login"] : "");
        $email = (isset($user["email"]) ? $user["email"] : "");
        
        $this->smarty->assign('id', (isset($task["id"]) ? $task["id"] : 0));
        $this->smarty->assign('uid', (isset($task["uid"]) ? $task["uid"] : 0));
        $this->smarty->assign('sid', (isset($task["sid"]) ? $task["sid"] : 0));
        $this->smarty->assign('tema', (isset($task["tema"]) ? $task["tema"] : "THEMA_TASK"));
        $this->smarty->assign('site_url', (isset($site["url"]) ? $task["sid"] : "URL_SITE"));
        $this->smarty->assign('wordpress_result', $wordpress_result);
        
        $this->message["to"][] = array("email" => $email, "name" => $login);
        $this->message["subject"] = "[На выкладывание]";
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "task_status_navyklad.tpl");
        return $this->sendEmail();
    }

    public function taskStatusNavykladForPalexa($user = array(), $site = array(), $task = array(), $XLS_name = "") {
        $login = (isset($user["login"]) ? $user["login"] : "");
        $email = (isset($user["email"]) ? $user["email"] : "");
        
        $this->smarty->assign('id', (isset($task["id"]) ? $task["id"] : 0));
        $this->smarty->assign('uid', (isset($task["uid"]) ? $task["uid"] : 0));
        $this->smarty->assign('sid', (isset($task["sid"]) ? $task["sid"] : 0));
        $this->smarty->assign('site_url', (isset($site["url"]) ? $task["sid"] : "URL_SITE"));
        $this->smarty->assign('wordpress_result', "");
        
        if($XLS_name != ""){
            $file = fopen($XLS_name, "rb");
            $this->message["attachments"] = array();
            $this->message["attachments"][] = array("type" => "text/plain", "name" => "text_file.xlsx", "content" => base64_encode(fread($file, filesize($XLS_name))));
        }
        
        $this->message["to"][] = array("email" => $email, "name" => $login);
        $this->message["subject"] = "[На выкладывание]";
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "task_status_navyklad.tpl");
        return $this->sendEmail();
    }

    public function taskStatusDorabotka($user = array(), $site = array(), $task = array(), $comment = "COMMENT") {
        $login = (isset($user["login"]) ? $user["login"] : "");
        $email = (isset($user["email"]) ? $user["email"] : "");
        
        $this->smarty->assign('id', (isset($task["id"]) ? $task["id"] : 0));
        $this->smarty->assign('uid', (isset($task["uid"]) ? $task["uid"] : 0));
        $this->smarty->assign('sid', (isset($task["sid"]) ? $task["sid"] : 0));
        $this->smarty->assign('site_url', (isset($site["url"]) ? $task["sid"] : "URL_SITE"));
        $this->smarty->assign('comment', $comment);
        
        $this->message["to"][] = array("email" => $email, "name" => $login);
        $this->message["subject"] = "[На доработку]";
        $this->message["html"] = $this->smarty->fetch($this->TEMPLATE_PATH . "task_status_dorabotka.tpl");
        return $this->sendEmail();
    }
    
    
    
    public function getMailsName($name = null) {
        if (!empty($name)) {
            switch ($name) {
                case "taskStatusNavyklad": return "[На выкладывание]";
                case "taskStatusNavykladForPalexa": return "[На выкладывание]";
                case "taskStatusDorabotka": return "[На доработку]";
            }
        } else {
            return array(
                "taskStatusNavyklad" => "[На выкладывание]",
                "taskStatusNavykladForPalexa" => "[На выкладывание] (только для Palexa)",
                "taskStatusDorabotka" => "[На доработку]",
            );
        }
    }
    public function getMail($name){
        if (!empty($name)) {
            $this->get_text = true;
            switch ($name) {
                case "taskStatusNavyklad": return $this->taskStatusNavyklad();
                case "taskStatusNavykladForPalexa": return $this->taskStatusNavykladForPalexa();
                case "taskStatusDorabotka": return $this->taskStatusDorabotka();
            }
        }
    }
}
