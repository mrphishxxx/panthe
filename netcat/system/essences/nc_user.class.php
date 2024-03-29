<?php

/* $Id: nc_user.class.php 5960 2012-01-17 17:25:34Z denis $ */
if (!class_exists("nc_System")) die("Unable to load file.");

class nc_User extends nc_Essence {

    protected $core, $db;

    /**
     * Constructor function
     */
    public function __construct() {
        // load parent constructor
        parent::__construct();

        $this->core = nc_Core::get_object();
        $this->db = $this->core->db;
    }

    /*
     * Работает только с $current_user
     */

    public function get_by_id($id, $item = "", $reset = false) {
        //global $current_user; // <<-- depricated
        $id = intval($id);
        if ($this->data[$id]['_nc_final'])
                return $item ? $this->data[$id][$item] : $this->data[$id];

        if (!$this->data[$id])
                $this->data[$id] = $this->db->get_row("SELECT * FROM `User`
                                             WHERE `User_ID` = '".$id."'", ARRAY_A);
        $this->data[$id] = $this->convert_system_vars($this->data[$id]);
        //$this->data[$id] = $current_user;
        $this->data[$id]['_nc_final'] = 1;

        // if item requested return item value
        if ($item && is_array($this->data[$id])) {
            return array_key_exists($item, $this->data[$id]) ? $this->data[$id][$item] : "";
        }

        return $this->data[$id];
    }

    public function add($fields, $groups, $password, $add_fields = array()) {
        $user_table = new nc_Component(0, 3);

        if (!is_array($groups)) $groups = explode(',', $groups);
        $groups = array_unique(array_map('intval', $groups));

        $checked = 1;
        if (isset($add_fields['Checked'])) {
            $checked = (bool) $add_fields['Checked'];
            $checked = (int) $checked;
        }

        $add_fields['UserType'] = $this->db->escape($add_fields['UserType']);

        $insert_fields = array('`Password`', '`Created`', '`Checked`', '`PermissionGroup_ID`', 'UserType');
        $insert_values = array($this->core->MYSQL_ENCRYPT.'("'.$password.'")', "'".date("Y-m-d H:i:s")."'", $checked, min($groups), "'".$add_fields['UserType']."'");

        $user_fields = $user_table->get_fields();
        if (!empty($user_fields))
                foreach ($user_fields as $v) {
                if (isset($fields[$v['name']]) && $v['type'] != NC_FIELDTYPE_FILE) {
                    $insert_fields[] = "`".$this->db->escape($v['name'])."`";
                    $insert_values[] = "'".$this->db->escape($fields[$v['name']])."'";
                }
                if (isset($fields[$v['name']]) && $v['type'] == NC_FIELDTYPE_FILE) {
                    $user_file[$v['id']] = array('path' => $fields[$v['name']]);
                }
            }



        $this->db->query("INSERT INTO `User`(".join(',', $insert_fields).") VALUES (".join(',', $insert_values).") ");
        if ($this->db->is_error)
                throw new nc_Exception_DB_Error($this->db->last_query, $this->db->last_error);

        $user_id = $this->db->insert_id;
        foreach ($groups as $group_id) {
            $this->db->query("INSERT INTO `User_Group` (`User_ID`, `PermissionGroup_ID`) VALUES ('".$user_id."','".$group_id."') ");
        }

        if (!empty($user_file))
                foreach ($user_file as $field_id => $v) {
                $v['name'] = substr($v['path'], strrpos($v['path'], '/') + 1);
                $this->core->files->save_file('User', $field_id, $user_id, $v);
            }
        return $user_id;
    }

    public function authorize_by_token($login, $sign, $text) {
        $user_info = $this->db->get_row("SELECT `User_ID`, `PublicKey` FROM `Auth_Token`
                                     WHERE `Login` = '".$this->db->escape($login)."'", ARRAY_A);

        if (!$user_info) return 0;

        // публичный ключ, текст, компоненты ключа и эцп
        $pk = $user_info['PublicKey'];
        $Hash = strtoupper($text);
        $Qx = strtoupper(substr($pk, 0, 64));
        $Qy = strtoupper(substr($pk, 64));
        $R = strtoupper(substr($sign, 0, 64));
        $S = strtoupper(substr($sign, 64));

        $nc_auth_token = new nc_auth_token();
        if ($nc_auth_token->verify($Hash, $Qx, $Qy, $R, $S)) {
            return $this->authorize_by_id($user_info['User_ID'], NC_AUTHTYPE_TOKEN);
        }

        return 0;
    }

    public function change_password($user_id, $password, $delete_reg_code = 0) {
        $this->db->query("UPDATE `User`
                 SET `Password`=".$this->core->MYSQL_ENCRYPT."('".$this->db->escape($password)."')
                 ".( $delete_reg_code ? ", `RegistrationCode` = ''" : "")."
                 WHERE `User_ID` = '".intval($user_id)."'");
        return $this->db->rows_affected;
    }

    /**
     * Проверка логина
     * @param string логин
     * @param int номер пользователя ( 0 - регистрация ), нужна для проверки совпадения логина
     * @return int константы: NC_AUTH_LOGIN_OK, NC_AUTH_LOGIN_INCORRECT, NC_AUTH_LOGIN_EXISTS
     */
    public function check_login($login, $user_id = 0) {
        $allow_cyrylic = $allow_specialchars = 1;
        if ($this->core->modules->get_by_keyword('auth')) {
            $allow_cyrylic = $this->core->get_settings('allow_cyrillic', 'auth');
            $allow_specialchars = $this->core->get_settings('allow_specialchars', 'auth');
        }

        $auth_by = $this->db->escape($this->core->AUTHORIZE_BY);
        $format = $this->db->get_var("SELECT `Format` FROM `Field` WHERE `Field_Name` = '".$auth_by."' AND `System_Table_ID` = 3");

        // в качестве логина выступает Email
        if ($format == 'email') {
            if (!nc_check_email($login)) return NC_AUTH_LOGIN_INCORRECT;
        }
        else {
            // русские символы запрещены
            if (!$allow_cyrylic && nc_preg_match("/[".NETCAT_RUALPHABET."]/", $login)) {
                return NC_AUTH_LOGIN_INCORRECT;
            }
            // спеецсимволы запрещены
            if (!$allow_specialchars && !nc_preg_match("/^[a-z0-9".NETCAT_RUALPHABET."_-]+$/i", $login)) {
                return NC_AUTH_LOGIN_INCORRECT;
            }
        }
        // проверка существования логина
        $ex = $this->db->get_var("SELECT `User_ID` FROM `User` WHERE `".$auth_by."` = '".$this->db->escape($login)."' ".($user_id ? " AND `User_ID` <> '".intval($user_id)."' " : "")." ");
        if ($ex) return NC_AUTH_LOGIN_EXISTS;

        return NC_AUTH_LOGIN_OK;
    }

    public function delete_by_id($id) {
        if (!is_array($id)) $id = array($id);
        $id = array_map('intval', $id);

        // генерируем событие
        $this->core->event->execute("dropUserPrep", $id);

        require_once $this->core->INCLUDE_FOLDER."s_files.inc.php";
        foreach ($id as $v) {
            DeleteSystemTableFiles('User', $id);
        }

        $ids_str = join(',', $id);

        $this->db->query("DELETE FROM `User` WHERE `User_ID` IN (".$ids_str.") ");
        $this->db->query("DELETE FROM `User_Group`  WHERE `User_ID` IN (".$ids_str.") ");

        if ($this->core->modules->get_by_keyword('auth')) {
            $this->db->query("DELETE FROM `Auth_ExternalAuth` WHERE `User_ID` IN (".$ids_str.") ");
        }

        // генерируем событие
        $this->core->event->execute("dropUser", $id);
    }

    public function authorize_by_pass($login, $password) {


        $PHP_AUTH_USER = $login;
        $PHP_AUTH_PW = $password;

        $user_result = $this->db->get_results("SELECT `u`.User_ID AS `User_ID`, `Password`,
      ug.`PermissionGroup_ID` AS PermissionGroups_ID,
      `u`.PermissionGroup_ID, `Language`, `u`.`".$this->core->AUTHORIZE_BY."`, `InsideAdminAccess`, `Catalogue_ID`
      FROM `User` as `u`, `User_Group` as ug
      WHERE
      `u`.`".$this->core->AUTHORIZE_BY."` = '".$this->db->escape($PHP_AUTH_USER)."'
      AND `Password` = ".$this->core->MYSQL_ENCRYPT."('".$this->db->escape($PHP_AUTH_PW)."')
      AND `Checked` = 1
      AND `u`.User_ID = ug.`User_ID`".$this->get_cond(1)."
      ORDER BY ug.ID", ARRAY_A);

        if (!$user_result) return false;

        $this->core->event->execute("authorizeUserPrep", $user_result[0]['User_ID']);

        $this->create_session($user_result[0]['User_ID']);
        $this->init_user($user_result);

        $this->core->event->execute("authorizeUser", $user_result[0]['User_ID']);

        return $user_result[0]['User_ID'];
    }

    public function authorize_by_id($user_id, $auth_variant = NC_AUTHTYPE_LOGIN, $isInsideAdmin = 0, $create_session = 1) {
        $db = $this->db;
        $user_id = intval($user_id);


        $user_result = $db->get_results("SELECT `User`.User_ID AS `User_ID`, `Password`,
      ug.`PermissionGroup_ID` AS PermissionGroups_ID,
      `User`.PermissionGroup_ID, `Language`, `User`.`".$this->core->AUTHORIZE_BY."`, `InsideAdminAccess`, `Catalogue_ID`
      FROM `User`, `User_Group` as ug
      WHERE
      `User`.`User_ID` = '".$user_id."'
      AND `Checked` = 1
      AND `User`.User_ID = ug.`User_ID`
      ORDER BY ug.ID", ARRAY_A);

        // пользователь не найден
        if (!$user_result) return false;

        // Авторизованные пользователи
        $AUTH_USER_ID = $user_result[0]['User_ID'];

        $this->core->event->execute("authorizeUserPrep", $AUTH_USER_ID);

        $this->create_session($user_id, 'authorize', 0, $auth_variant);
        $this->init_user($user_result);

        $this->core->event->execute("authorizeUser", $AUTH_USER_ID);

        return $AUTH_USER_ID;
    }

    public function attempt_to_authorize() {
        global $perm;
        global $PHP_AUTH_USER, $PHP_AUTH_PW, $PHP_AUTH_LANG;
        global $MODULE_VARS, $HTTP_HOST;
        global $AUTH_USER_ID, $AUTH_USER_GROUP;
        global $current_user;

        if (is_object($perm)) return $AUTH_USER_ID;

        $nc_core = $this->core;
        $db = $this->db;

        $AUTH_USER_ID = 0;
        $AUTH_USER_GROUP = 0;

        $SessionTime = time() + ($nc_core->ADMIN_AUTHTIME ? $nc_core->ADMIN_AUTHTIME : 24 * 3600);

        if ($nc_core->AUTHORIZATION_TYPE == 'session') {
            if (isset($_SESSION['User']['IsLogin'])) {
                if ($_SESSION['User']['IP'] != getenv("REMOTE_ADDR")) {
                    header("Location: ".$nc_core->SUB_FOLDER);
                    exit;
                }
                if ((time() - $_SESSION['User']['datetime']) > ini_get('session.gc_maxlifetime'))
                        unset($_SESSION['User']);
            }
            $_SESSION['User']['datetime'] = time();
        }

        if ($nc_core->AUTHORIZATION_TYPE == 'http') {
            $user_result = $db->get_results("SELECT u.*, ug.`PermissionGroup_ID` AS PermissionGroups_ID
                               FROM `User` as u, `User_Group` as ug
                               WHERE u.`".$nc_core->AUTHORIZE_BY."` = '".$PHP_AUTH_USER."'
                               AND Password=".$nc_core->MYSQL_ENCRYPT."('".$PHP_AUTH_PW."')
                               AND u.`User_ID` = ug.`User_ID`
                               ", ARRAY_A);
        } else {
            $s = $this->get_session_id();
            $user_result = $db->get_results("SELECT u.*, ug.`PermissionGroup_ID` AS PermissionGroups_ID,  s.`LoginSave`, s.`AuthVariant`, s.SessionTime
                                    FROM (`User` as u, `User_Group` as ug)
                                    RIGHT JOIN Session AS s
                                    ON u.User_ID = s.User_ID
                                    WHERE u.Checked = 1 AND u.`User_ID` = ug.`User_ID`
                                    AND s.Session_ID = '".$db->escape($s)."' AND s.SessionTime > ".time().$this->get_cond(), ARRAY_A);
        }


        if ($user_result[0]['AuthVariant'] == 'hash') {
            $nc_auth = nc_auth::get_object();
            if (!$nc_auth->hash->check(0, 0)) {
                unset($user_result); // проверка не прошла
                return false;
            }
        }

        // Гости
        if (!$user_result) {

            $session_id = session_id();
            $PHP_AUTH_USER = '';
            $PHP_AUTH_PW = '';

            if ($nc_core->modules->get_by_keyword('auth')) {
                $update_res = $db->query("UPDATE Session SET SessionTime = IF (SessionTime=".$SessionTime.", SessionTime+1,".$SessionTime.") WHERE Session_ID = '".$db->escape($session_id)."'");
                if (!$update_res) {
                    $db->query("INSERT INTO Session (Session_ID, User_ID, SessionStart, SessionTime, UserIP, Catalogue_ID) VALUES ('".$db->escape($session_id)."', 0, ".time().", ".$SessionTime.", ".sprintf("%u", ip2long($_SERVER['REMOTE_ADDR'])).", ".($catalogue + 0).")");
                    // чистим гостевые сессии
                    if (!rand(0, 50))
                            $db->query("DELETE FROM Session WHERE User_ID = 0 AND SessionTime < ".($SessionTime - 300));
                }
            }

            return false;
        }


        // Авторизованные пользователи
        $AUTH_USER_ID = $user_result[0]['User_ID'];

        $this->create_session($AUTH_USER_ID, 'attempt', $user_result[0]['LoginSave']);
        $this->init_user($user_result);


        return $AUTH_USER_ID;
    }

    public function init_user($user_result) {
        global $AUTH_USER_ID, $AUTH_USER_GROUP, $PHP_AUTH_USER;
        global $current_user, $perm, $nc_core;

        $AUTH_USER_ID = $user_result[0]['User_ID'];
        $this->data[$AUTH_USER_ID] = $user_result[0];
        $this->data[$AUTH_USER_ID]['_nc_final'] = 0;
        unset($this->data[$AUTH_USER_ID]['PermissionGroups_ID']);
        foreach ($user_result as $row) {
            $this->data[$AUTH_USER_ID]["Permission_Group"][] = $row['PermissionGroups_ID'];
        }

        $AUTH_USER_GROUP = $this->data[$AUTH_USER_ID]['PermissionGroup_ID'];
        $PHP_AUTH_USER = $this->data[$AUTH_USER_ID][$nc_core->AUTHORIZE_BY];

        $current_user = $this->get_by_id($AUTH_USER_ID);
        $perm = new Permission($AUTH_USER_ID, 0, $user_result);

        return $AUTH_USER_ID;
    }

    public function create_session($user_id, $auth_phase = 'authorize', $login_save = 0, $auth_variant = NC_AUTHTYPE_LOGIN) {

        global $PHP_AUTH_LANG;
        $db = $this->db;
        $PHP_AUTH_LANG = $this->core->lang->detect_lang();

        // сохранять авторизацию ( перенести проверку поста в вызывающий метод )
        $LoginSave = ( ($login_save || $_POST['loginsave'] || $this->core->ADMIN_AUTHTYPE == "always") ? 1 : 0);

        // авторизировать на поддоменах
        $cookies_with_subdomain = 0;
        if ($this->core->modules->get_by_keyword('auth') && $this->core->get_settings('with_subdomain', 'auth')) {
            $cookies_with_subdomain = 1;
        }

        $UserIP = sprintf("%u", ip2long($_SERVER['REMOTE_ADDR']));
        $session_id = $this->get_session_id();
        if (!$session_id) $session_id = md5(rand(0, 1000).$user_id.$UserIP);

        $SessionStart = time();
        $SessionTime = $SessionStart + ($this->core->ADMIN_AUTHTIME ? $this->core->ADMIN_AUTHTIME : 30 * 24 * 3600);

        if ($auth_phase == 'authorize') {
            $db->query("DELETE FROM `Session` WHERE `SessionTime` < '".$SessionStart."'".($db->escape($session_id) ? " OR `Session_ID` = '".$db->escape($session_id)."'" : ""));
            $db->query("INSERT INTO `Session` (`Session_ID`, `User_ID`, `SessionStart`, `SessionTime`, `UserIP`, `LoginSave`, `Catalogue_ID`, `AuthVariant`)
            VALUES ('".$db->escape($session_id)."', '".$user_id."', '".$SessionStart."', '".$SessionTime."', '".$UserIP."', '".$LoginSave."', '".$Catalogue_ID."', '".intval($auth_variant)."')");
        } else {
            $db->query("UPDATE `Session` SET `SessionTime` = '".$SessionTime."', `UserIP` = '".$UserIP."' WHERE `Session_ID` = '".$db->escape($session_id)."'");
        }

        switch (true) {
            // SESSION авторизация/валидация
            case $this->core->AUTHORIZATION_TYPE == 'session':
                $_SESSION['User']['ID'] = $UserID;
                $_SESSION['User']['PHP_AUTH_USER'] = $PHP_AUTH_USER;
                $_SESSION['User']['PHP_AUTH_PW'] = $Password;
                $_SESSION['User']['PHP_AUTH_LANG'] = $PHP_AUTH_LANG;
                $_SESSION['User']['datetime'] = $SessionStart;
                $_SESSION['User']['IsLogin'] = "1";
                $_SESSION['User']['IP'] = $_SERVER['REMOTE_ADDR'];
                break;

            // COOKIE авторизация/валидация
            case $this->core->AUTHORIZATION_TYPE == 'cookie':
                if (!$LoginSave) $SessionTime = 0;
                $cookie_domain = ($cookies_with_subdomain && nc_strpos($_SERVER['HTTP_HOST'], '.') !== false ? str_replace("www.", "", $_SERVER['HTTP_HOST']) : NULL);
                setcookie('PHP_AUTH_SID', $session_id, $SessionTime, "/", $cookie_domain);
                setcookie('PHP_AUTH_LANG', $PHP_AUTH_LANG, $SessionTime, "/", $cookie_domain);
                break;
        }

        return $session_id;
    }

    protected function get_session_id() {
        return $this->core->AUTHORIZATION_TYPE == 'session' ?
                session_id() :
                $this->core->input->fetch_cookie('PHP_AUTH_SID');
    }

    protected function get_cond($notall=0) {
        $nc_core = nc_Core::get_object();
        $current_catalogue = $nc_core->catalogue->get_by_host_name($_SERVER['HTTP_HOST']);
        $catalogue = $current_catalogue['Catalogue_ID'];

        if ($nc_core->modules->get_by_keyword('auth')) {
            $nc_auth = nc_auth::get_object();
            $SqlCheckIp = $nc_auth->get_sql_check_ip();
            $query_where_cat = $nc_core->get_settings('bind_to_catalogue', 'auth') ? " AND `u`.Catalogue_ID IN(0,".($catalogue + 0).")" : "";
        } else {
            $SqlCheckIp = '';
            $query_where_cat = '';
        }

        return ($notall ? "" : $SqlCheckIp).$query_where_cat;
    }

}
?>
