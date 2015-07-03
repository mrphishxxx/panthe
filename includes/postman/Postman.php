<?php

require_once PATH . 'includes/mandrill/mandrill.php';
require_once 'FromAdmin.php';
require_once 'FromUser.php';
require_once 'FromCopywriter.php';
require_once 'FromModerator.php';

// Настройки почтальона
define('EMAIL_SUPPORT', 'abashevav@gmail.com');
define('EMAIL_ADMIN', 'abashevav@gmail.com'); //iforget.ru@gmail.com
define('EMAIL_FROM', 'news@iforget.ru');
define('EMAIL_ROBOT', 'robot@iforget.ru');
define('EMAIL_NOREPLY', 'noreply@iforget.ru');

class Postman {

    public $user;
    public $admin;
    public $moderator;
    public $copywriter;
    
    private $TEMPLATE_PATH;
    private $db;
    private $smarty;
    private $mandrill;
    private $message = array();
    private $admins_mail = array();

    public function __construct($smarty, $db, $template_path = NULL) {
        if (isset($smarty) && isset($db)) {
            $this->db = $db;
            $this->smarty = $smarty;
            $this->mandrill = new Mandrill(MANDRILL);

            if (isset($template_path)) {
                $this->TEMPLATE_PATH = $template_path;
            } else {
                $this->TEMPLATE_PATH = PATH . 'includes/postman/templates/';
            }
            $this->admins_mail[] = array("email" => EMAIL_ADMIN, "name" => "iForget");
            $administrations = $db->Execute("SELECT login, email FROM admins WHERE type = 'admin' OR type = 'manager' AND id != 1")->GetAll();
            foreach ($administrations as $user) {
                if (!empty($user["email"]) && $user["email"] != "") {
                    $this->admins_mail[] = array("email" => $user["email"], "name" => $user["login"]);
                }
            }

            $this->message["to"] = array();
            $this->message["text"] = "";
            $this->message['track_opens'] = NULL;
            $this->message['track_clicks'] = NULL;
            $this->message['auto_text'] = NULL;
            $this->message["from_email"] = EMAIL_FROM;
            $this->message["from_name"] = "iforget";
            
            $this->admin = new FromAdmin($this->TEMPLATE_PATH, $this->smarty, $this->db, $this->mandrill, $this->message, $this->admins_mail);
            $this->user = new FromUser($this->TEMPLATE_PATH, $this->smarty, $this->db, $this->mandrill, $this->message, $this->admins_mail);
            $this->copywriter = new FromCopywriter($this->TEMPLATE_PATH, $this->smarty, $this->db, $this->mandrill, $this->message, $this->admins_mail);
            $this->moderator = new FromModerator($this->TEMPLATE_PATH, $this->smarty, $this->db, $this->mandrill, $this->message, $this->admins_mail);
        } else {
            echo '<pre>';
            var_dump("Smarty and db must be a initialized");
            echo '</pre>';
            return null;
        }
    }
    
    public function getMailsName($name = null) {
        return null;
    }
}
