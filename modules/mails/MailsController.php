<?php

/**
 * @author Abashev A.V.
 */
class MailsController {

    public $_db;
    public $_smarty;
    public $_postman;

    public function __construct($db, $smarty) {
        $this->_db = $db;
        $this->_smarty = $smarty;
        $this->_postman = new Postman($smarty, $db);
    }

    public function gettingStartedAction() {
        $body = "";
        $reg_date_max = time() - 259200;
        $reg_date_min = time() - 262800;
        $users = $this->_db->Execute("SELECT * FROM admins WHERE reg_date BETWEEN $reg_date_min AND $reg_date_max AND type='user'")->GetAll();
        if(!empty($users)) {
            foreach ($users as $user) {
                $this->_postman->user->gettingStarted($user["email"], $user["login"]);
                //$body .= $this->_postman->user->getMail("gettingStarted");
            }
        }
        return $body;
    }

}
