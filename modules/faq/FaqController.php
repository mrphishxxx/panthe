<?php

/**
 * Description of FaqController
 *
 * @author Abashev A.V.
 */
class FaqController {

    public $_db;
    public $_front;
    public $_smarty;
    public $_postman;

    public function __construct($db, $smarty) {
        $this->_db = $db;
        $this->_smarty = $smarty;
        $this->_postman = new Postman($smarty, $db);
        $this->_smarty->assign('Title', 'FAQ iForget');
    }

    public function indexAction() {
        $this->_smarty->display("faq/index.tpl");
    }

    public function mailsAction() {
        $type = "admin";
        if (isset($_GET["type"])) {
            $type = $_GET["type"];
        }
        $mails = array();
        switch ($type) {
            case "admin":
                $mails = $this->_postman->admin->getMailsName();
                break;
            case "user":
                $mails = $this->_postman->user->getMailsName();
                break;
            case "copywriter":
                $mails = $this->_postman->copywriter->getMailsName();
                break;
            case "moderator":
                $mails = $this->_postman->moderator->getMailsName();
                break;
            default : $mails = $this->_postman->getMailsName();
        }
        $this->_smarty->assign('mails', $mails);
        $this->_smarty->assign('type', $type);
        $this->_smarty->display("faq/mails.tpl");
    }

    public function getMailAction() {
        $type = $view = null;
        if (isset($_GET["type"])) {
            $type = $_GET["type"];
        }
        if (isset($_GET["view"])) {
            $view = $_GET["view"];
        }

        if (!empty($type) && !empty($view)) {
            switch ($type) {
                case "admin":
                    $mail_name = $this->_postman->admin->getMailsName($view);
                    break;
                case "user":
                    $mail_name = $this->_postman->user->getMailsName($view);
                    break;
                case "copywriter":
                    $mail_name = $this->_postman->copywriter->getMailsName($view);
                    break;
                case "moderator":
                    $mail_name = $this->_postman->moderator->getMailsName($view);
                    break;
                default : $mail_name = $this->_postman->getMailsName($view);
            }
        }
        $mail = $this->_postman->$type->getMail($view);
        
        $this->_smarty->assign('mail', $mail);
        $this->_smarty->assign('type', $type);
        $this->_smarty->assign('mail_name', $mail_name);
        $this->_smarty->display("faq/get_mail.tpl");
    }

}
