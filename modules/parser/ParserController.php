<?php

include_once dirname(__FILE__) . '/../' . 'admins/class_admin_admins.php';

include_once 'burse/GetGoodLinks.php';
include_once 'ParserErrorController.php';

define("GETGOODLINKS_URL", "http://getgoodlinks.ru/");
define("GETGOODLINKS_ID", 2);

/**
 * @author Abashev A.V.
 */
class ParserController {

    private $_db;
    private $_smarty;
    private $_postman;
    private $_balances;
    private $_class_admin;
    private $_class_error;
    protected $proxies = array();
    protected $min_balance = 60;
    protected $exclude_users_check_balance = array(20, 55);
    protected $log = "";
    protected $errors = array();
    protected $is_error = false;
    protected $new_tasks = array();

    public function __construct($db, $smarty) {
        $this->_db = $db;
        $this->_smarty = $smarty;
        $this->_postman = new Postman($smarty, $db);

        $this->_class_admin = new admins($db, $smarty);
        $this->_class_error = new ParserErrorController();

        $this->_balances = $this->_class_admin->getAllUserBalans($db);

        $proxy_file_path = PATH . "modules/angry_curl/proxy_list.txt";
        $proxy_files = file($proxy_file_path);
        if (!empty($proxy_files)) {
            foreach ($proxy_files as $p) {
                $this->proxies[] = array(
                    'proxy_host' => substr($p, 0, strpos($p, ":")),
                    'proxy_port' => (int) substr($p, strpos($p, ":") + 1),
                    'proxy_user' => PROXY_LOGIN,
                    'proxy_pass' => PROXY_PASS
                );
            }
        }
    }

    public function getTasksGetgoodlinksAction() {
        $task_exists = $task_vipolneno = array();
        $this->log .= PHP_EOL . ">>START FUNCTION getTasksGetgoodlinksAction => " . PHP_EOL;
        // ID последнего задания
        $last_id = $this->getLastTaskId(GETGOODLINKS_URL);

        // получаем все активные доступы к бирже
        $uid_burse_login_pass = $this->getBurse(GETGOODLINKS_ID);
        if (!empty($uid_burse_login_pass)) {
            $ggl_to_uid = array_keys($uid_burse_login_pass);
        } else {
            return $this->_class_error->getErrorText(PARSER_ERROR_NOT_BURSE);
        }
        $this->log .= ">>get all burse " . PHP_EOL;

        // получаем всех активные юзеров из тех, что добавили доступы
        // проверяем баланс, и добавляем в список кого парсить, только если БАЛАНС больше Минимального
        $uids_get_tasks = $this->getUsersIdsCheckedBalance($ggl_to_uid);
        if ($uids_get_tasks === false || empty($uids_get_tasks)) {
            return $this->_class_error->getErrorText(PARSER_ERROR_NOT_USERS);
        }
        $this->log .= ">>get all users " . PHP_EOL;

        // получаем все сайты этих пользователей
        $uid_sites_ids = $this->getSitesUsers($uids_get_tasks, "getgoodlinks_id");
        if ($uid_sites_ids === false || empty($uid_sites_ids)) {
            return $this->_class_error->getErrorText(PARSER_ERROR_NOT_SITES);
        }
        $this->log .= ">>get all sites " . PHP_EOL;

        // получаем все задачи у всех нужных пользователя (для исключения дубликатов)
        $exists = $this->getExistingTasks($uids_get_tasks, GETGOODLINKS_URL);
        if ($exists === false) {
            return "ERROR: Ошибка получения задач из iforget!" . PHP_EOL . $this->_class_error->getErrorText(PARSER_ERROR_OTHER);
        } elseif (!empty($exists)) {
            $task_exists = $exists[0];
            $task_vipolneno = $exists[1];
        }
        $this->log .= ">>get all task buffer " . PHP_EOL;

        // проходим по каждому пользователю и запускаем выгрузку задач
        $this->log .= PHP_EOL . ">>START PARSING " . PHP_EOL;
        foreach ($uid_sites_ids as $uid => $sites) {
            $this->log .= PHP_EOL . "UID => " . $uid . PHP_EOL;

            if (!isset($task_exists[$uid])) {
                $task_exists[$uid] = array();
            }
            if (!isset($task_vipolneno[$uid])) {
                $task_vipolneno[$uid] = array();
            }
            $user_login = $uid_burse_login_pass[$uid]["login"];
            $user_pass = $uid_burse_login_pass[$uid]["pass"];

            $ggl_parser = new GetGoodLinks($uid, $user_login, $user_pass, $this->proxies);
            $this->log .= "-> created object GetGoodLinks" . PHP_EOL;

            $create_web_driver = $ggl_parser->createSeleniumWebDriver();
            $this->log .= "-> created selenium WebDriver" . PHP_EOL;

            if ($create_web_driver !== true) {
                $this->log .= "-> ERROR create selenium WebDriver: " . PHP_EOL . $create_web_driver . PHP_EOL;
                $this->errors[$uid] = "ERROR create selenium WebDriver: " . PHP_EOL . $create_web_driver . PHP_EOL;
                $this->is_error = true;
                continue;
            }

            $response = $ggl_parser->getTasks($sites, $task_exists[$uid], $task_vipolneno[$uid]);
            if ($response === TRUE) {
                $this->log .= $ggl_parser->getLog();
                $tasks = $ggl_parser->getResult();
                $this->log .= "-> get tasks - done!" . PHP_EOL;
            } else {
                $this->log .= "-> << RESPONSE ERROR >>";
                $this->log .= $ggl_parser->getLog();
                $this->log .= $response . PHP_EOL;
                $this->errors[$uid] = $response;
                $this->is_error = true;
            }
            $ggl_parser->destroySeleniumWebDriver();
            if (!empty($tasks)) {
                $this->new_tasks[$uid] = $tasks;
            }
            $this->log .= "-> the end for this user" . PHP_EOL;
        }
        $this->log .= ">>THE END PARSING for all users" . PHP_EOL;

        // Сохраняем новые задачи в базу данных
        if (!empty($this->new_tasks)) {
            $this->log .= PHP_EOL . ">>SAVE NEW TASKS" . PHP_EOL;
            $this->saveNewTasks($task_exists, $task_vipolneno, GETGOODLINKS_URL);
        } else {
            $this->log .= PHP_EOL . ">>NEW TASKS IS EMPTY!" . PHP_EOL;
        }

        $this->log .= PHP_EOL . ">>THE END PARSER GETGOODLINKS!" . PHP_EOL;
        //print_r($this->new_tasks);
        //print_r($this->log);
        //print_r($this->errors);
        return NULL;
    }

    /*
     *  ID последнего задания
     */

    private function getLastTaskId($system) {
        $last_task = $this->_db->Execute("SELECT id FROM zadaniya WHERE sistema='" . $system . "' ORDER BY id DESC LIMIT 1")->FetchRow();
        $last_id = !empty($last_task['id']) ? $last_task['id'] : null;
        return $last_id;
    }

    /*
     *  получаем все активные доступы к бирже
     */

    private function getBurse($system) {
        $uid_burse_login_pass = array();
        $birjs_model = $this->_db->Execute("SELECT * FROM birjs WHERE birj = '" . $system . "' AND active = 1 ORDER BY uid")->GetAll();
        foreach ($birjs_model as $burse) {
            if (!empty($burse['login']) && !empty($burse['pass'])) {
                $uid_burse_login_pass[$burse['uid']] = array("login" => $burse['login'], "pass" => $burse['pass']);
            }
        }
        return $uid_burse_login_pass;
    }

    /*
     *  получаем всех активных юзеров из тех, что добавили доступы
     *  проверяем баланс, и добавляем в список кого парсить, только если БАЛАНС больше Минимального
     */

    private function getUsersIdsCheckedBalance($users = array()) {
        $uids_get_tasks = array();
        if (!empty($users)) {
            $admins_model = $this->_db->Execute("SELECT * FROM admins WHERE active=1 AND type='user' AND id IN (" . (implode(",", $users)) . ") ORDER BY id")->GetAll();
            foreach ($admins_model as $user) {
                $balance = isset($this->_balances[$user['id']]) ? $this->_balances[$user['id']] : 0;
                if ($balance >= $this->min_balance || in_array($user['id'], $this->exclude_users_check_balance)) {
                    $uids_get_tasks[] = $user['id'];
                }
            }
            return $uids_get_tasks;
        } else {
            return false;
        }
    }

    /*
     * получаем все сайты определенных пользователей
     */

    private function getSitesUsers($users = array(), $burse_field = null) {
        $uid_sites = array();
        if (!empty($users) && !empty($burse_field)) {
            $sayty_model = $this->_db->Execute("SELECT * FROM sayty WHERE uid IN (" . implode(",", $users) . ")")->GetAll();
            foreach ($sayty_model as $site) {
                if (!isset($uid_sites[$site['uid']])) {
                    $uid_sites[$site['uid']] = array();
                }
                if (!empty($site[$burse_field]) && $site[$burse_field] != 0) {
                    $uid_sites[$site['uid']][] = $site;
                }
            }
            return $uid_sites;
        } else {
            return false;
        }
    }

    /*
     *  получаем все задачи у всех нужных пользователя (для исключения дубликатов)
     */

    private function getExistingTasks($users = array(), $system = null) {
        $exists = $vipolneno = array();
        if (!empty($users) && !empty($system)) {
            $zadaniya_model = $this->_db->Execute("SELECT id, uid, b_id, vipolneno FROM zadaniya WHERE uid IN (" . implode(",", $users) . ") AND (b_id IS NOT NULL AND b_id != 0) AND sistema = '" . $system . "'")->GetAll();
            foreach ($zadaniya_model as $task) {
                if (!isset($exists[$task["uid"]])) {
                    $exists[$task["uid"]] = array();
                }
                if (!isset($vipolneno[$task["uid"]])) {
                    $vipolneno[$task["uid"]] = array();
                }

                // уже существующая задача
                $exists[$task["uid"]][$task["id"]] = $task["b_id"];

                if ($task["vipolneno"] == 1) {
                    // уже существующая задача в статусе "Выполнено"
                    $vipolneno[$task["uid"]][$task["id"]] = $task["b_id"];
                }
            }
            return array($exists, $vipolneno);
        } else {
            return false;
        }
    }

    /*
     *  Сохраняем новые задачи в базу данных
     */

    private function saveNewTasks($task_exists, $task_vipolneno, $system) {

        foreach ($this->new_tasks as $user_id => $user_tasks) {

            foreach ($user_tasks as $task_id => $task) {
                if (!in_array($task_id, $task_exists[$user_id])) {
                    $this->insertRowInZadaniya($user_id, $task_id, $task, $system);
                } else {
                    $row_id = array_search($task_id, $task_exists[$user_id]);
                    $this->log .= "-> task exist (ID = $row_id) " . PHP_EOL;
                }
            }
        }
    }

    private function insertRowInZadaniya($user_id, $task_id, $task, $system) {
        if ($this->isAbsent($task["ankor"][0]) && $this->isAbsent($task["url"][0])) {
            $this->_db->Execute("INSERT INTO zadaniya("
                    . "sid, "
                    . "b_id, "
                    . "uid, "
                    . "sistema, "
                    . "ankor, "
                    . "ankor2, "
                    . "ankor3, "
                    . "ankor4, "
                    . "ankor5, "
                    . "url, "
                    . "url2, "
                    . "url3, "
                    . "url4, "
                    . "url5, "
                    . "tema, "
                    . "comments, "
                    . "vipolneno, "
                    . "date, "
                    . "keywords, "
                    . "b_price, "
                    . "nof_chars) "
                    . "VALUES ("
                    . "'" . $task["sid"] . "', "
                    . "'" . $task_id . "',"
                    . "'" . $user_id . "', "
                    . "'" . $system . "', "
                    . "'" . $this->_db->escape(trim($task["ankor"][0])) . "', "
                    . "'" . (isset($task["ankor"][1]) ? $this->_db->escape(trim($task["ankor"][1])) : NULL) . "', "
                    . "'" . (isset($task["ankor"][2]) ? $this->_db->escape(trim($task["ankor"][2])) : NULL) . "', "
                    . "'" . (isset($task["ankor"][3]) ? $this->_db->escape(trim($task["ankor"][3])) : NULL) . "', "
                    . "'" . (isset($task["ankor"][4]) ? $this->_db->escape(trim($task["ankor"][4])) : NULL) . "', "
                    . "'" . $this->_db->escape($task["url"][0]) . "', "
                    . "'" . (isset($task["url"][1]) ? $this->_db->escape($task["url"][1]) : NULL) . "', "
                    . "'" . (isset($task["url"][2]) ? $this->_db->escape($task["url"][2]) : NULL) . "', "
                    . "'" . (isset($task["url"][3]) ? $this->_db->escape($task["url"][3]) : NULL) . "', "
                    . "'" . (isset($task["url"][4]) ? $this->_db->escape($task["url"][4]) : NULL) . "', "
                    . "'" . $task["tema"] . "', "
                    . "'" . $this->_db->escape($task["text"]) . "', "
                    . "'0', '" . $task["date"] . "', "
                    . "'" . $this->_db->escape($task["keywords"]) . "', "
                    . "'" . $task["b_price"] . "', "
                    . "'2000')");

            $insert = $this->_db->Insert_ID();
            if ($insert) {
                $this->log .= "-> Add new task (ID = " . $insert . ") for User - $user_id" . PHP_EOL;
            } else {
                $this->log .= "-> ERROR!!! Not insert row (Burse ID = $task_id) for User - " . $user_id . ", Site Id - " . $task["sid"] . PHP_EOL;
            }
        }
    }

    public function saveLogs($param) {
        $function_name = $this->getFunctionName($param);
        $this->_db->Execute("INSERT INTO cron (function, date, logs, errors, fixed)"
                . " VALUES ("
                . "'" . $function_name . "', "
                . "'" . time() . "', "
                . "'" . $this->_db->escape(json_encode($this->log)) . "', "
                . "'" . (!empty($this->errors) ? $this->_db->escape(json_encode($this->errors)) : NULL) . "',"
                . "'" . (!empty($this->errors) ? "0" : "1") . "'"
                . ")");
    }

    private function getFunctionName($param) {
        switch ($param) {
            case "getgoodlinks":
                return "get_tasks_getgoodlinks";
        }
    }

    private function isAbsent($value) {
        if (isset($value) && !empty($value)) {
            return true;
        } else {
            return false;
        }
    }

}
