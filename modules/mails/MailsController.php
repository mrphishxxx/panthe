<?php

include_once dirname(__FILE__) . '/../' . 'admins/class_admin_admins.php';

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
        $log = "";
        $reg_date_max = time() - 259200;
        $reg_date_min = time() - 262800;
        $users = $this->_db->Execute("SELECT * FROM admins WHERE reg_date BETWEEN $reg_date_min AND $reg_date_max AND type='user'")->GetAll();
        if (!empty($users)) {
            foreach ($users as $user) {
                $this->_postman->user->gettingStarted($user["email"], $user["login"]);
                $log .= "gettingStarted: ".$user["email"] . PHP_EOL;
                //$log .= $this->_postman->user->getMail("gettingStarted");
            }
        } else {
            $log = "gettingStarted: Not users" . PHP_EOL;
        }
        return $log;
    }

    public function promocodeAction() {
        $log = "";
        $reg_date_max = time() - 864000;
        $reg_date_min = time() - 950400;

        $promos = array();
        $promo_user = $this->_db->Execute("SELECT user_id FROM promo_user")->GetAll();
        foreach ($promo_user as $user) {
            $promos[] = $user["user_id"];
        }

        $users = $this->_db->Execute("SELECT * FROM admins WHERE reg_date BETWEEN $reg_date_min AND $reg_date_max AND mail_promo=0 AND id NOT IN (" . implode(',', $promos) . ") AND type='user'")->GetAll();
        if (!empty($users)) {
            $code = $this->_db->Execute("SELECT Code FROM Message2009 WHERE Used = 0 ORDER BY Message_ID DESC LIMIT 1")->FetchRow();
            foreach ($users as $user) {
                $this->_postman->user->promocode($user["email"], $user["login"], $code["Code"]);
                $log .= "promocodeAction: ".$user["email"] . PHP_EOL;
                //$log .= $this->_postman->user->getMail("promocode");
            }
        } else {
            $log = "promocode: Not users" . PHP_EOL;
        }
        return $log;
    }

    public function endedBalance() {
        $log = "";
        $admin = new admins($this->_db, $this->_smarty);
        $balance_all = $admin->getAllUserBalans($this->_db);
        $users = $this->_db->Execute("SELECT * FROM admins WHERE active=1 AND type='user' AND mail_period > 0 AND id != 20")->GetAll();
        if (!empty($users)) {
            foreach ($users as $user) {
                // если баланс менее 62 рублей, то отправляем письмо
                if (isset($balance_all[$user["id"]]) && $balance_all[$user["id"]] < 62 && $user["mail_balance_ended"] == 0) {
                    $this->_postman->user->promocode($user["email"], $user["login"]);
                    $this->_db->Execute("UPDATE admins SET mail_balance_ended = 1");
                    $log .= "endedBalance: ".$user["id"] . " = " . $balance_all[$user["id"]] . PHP_EOL;
                    //$log .= $this->_postman->user->getMail("endedBalance");
                }
            }
        } else {
            $log = "endedBalance: Not users" . PHP_EOL;
        }
        return $log;
    }

    public function balanceComesToEnd() {
        $log = "";
        $admin = new admins($this->_db, $this->_smarty);
        $balance_all = $admin->getAllUserBalans($this->_db);
        $users = $this->_db->Execute("SELECT * FROM admins WHERE active=1 AND type='user' AND mail_period > 0 AND id != 20")->GetAll();
        if (!empty($users)) {
            foreach ($users as $user) {
                // если баланс менее 200 рулей, но более 62 рублей, то отправляем письмо о скором окончании баланса
                if (isset($balance_all[$user["id"]]) && $balance_all[$user["id"]] < 200 && $balance_all[$user["id"]] > 62 && $user["mail_balance_comes_end"] == 0) {
                    $this->_postman->user->promocode($user["email"], $user["login"]);
                    $this->_db->Execute("UPDATE admins SET mail_balance_comes_end = 1");
                    $log .= "balanceComesToEnd: ".$user["id"] . " = " . $balance_all[$user["id"]] . PHP_EOL;
                    //$log .= $this->_postman->user->getMail("endedBalance");
                }
            }
        } else {
            $log = "balanceComesToEnd: Not users" . PHP_EOL;
        }
        return $log;
    }
    
    public function checkMailBalance(){
        $log = "";
        $admin = new admins($this->_db, $this->_smarty);
        $balance_all = $admin->getAllUserBalans($this->_db);
        
        $users_balance_ended = $this->_db->Execute("SELECT * FROM admins WHERE mail_balance_ended = 1")->GetAll();
        if (!empty($users_balance_ended)) {
            foreach ($users_balance_ended as $user) {
                if (isset($balance_all[$user["id"]]) && $balance_all[$user["id"]] > 62) {
                    $this->_db->Execute("UPDATE admins SET mail_balance_ended = 0");
                    $log .= "checkMailBalance: mail_balance_ended - ".$user["id"] . " = " . $balance_all[$user["id"]] . PHP_EOL;
                }
            }
        } else {
            $log = "checkMailBalance: mail_balance_ended" . PHP_EOL;
        }
        
        $users_balance_comes_end = $this->_db->Execute("SELECT * FROM admins WHERE mail_balance_ended = 1")->GetAll();
        if (!empty($users_balance_comes_end)) {
            foreach ($users_balance_comes_end as $user) {
                if (isset($balance_all[$user["id"]]) && $balance_all[$user["id"]] > 200) {
                    $this->_db->Execute("UPDATE admins SET mail_balance_comes_end = 0");
                    $log .= "checkMailBalance: mail_balance_comes_end - ".$user["id"] . " = " . $balance_all[$user["id"]] . PHP_EOL;
                }
            }
        } else {
            $log = "checkMailBalance: mail_balance_comes_end" . PHP_EOL;
        }
        
        return $log;
    }

}
