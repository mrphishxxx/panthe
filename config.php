<?php
date_default_timezone_set('Europe/Moscow'); 
error_reporting (E_ALL);

define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_BASE', 'live.iforget');

define('MAIL_ADMIN', 'iforget.ru@gmail.com');
define('MAIL_DEVELOPER', 'abashevav@gmail.com');

define('ETXT_PASS', 'geFbINbs');
define('ETXT_TOKEN', '29aa0eec2c77dd6d06e23b3faaef9eed');

define('MANDRILL', 'zTiNSqPNVH3LpQdk1PgZ8Q');

define('GETBOT_APIKEY', 'd1451b02a44a571bb9d8d6033624649b');

define('PATH', dirname(__FILE__).'/');
define('LOGIN_IN_SAPE', 'iforget');
define('PASS_IN_SAPE', 'iforgetiforget');

/*  OTHER CONSTANTS   */
define("LIMIT_ERROR_FROM_COPYWRITER", 3);

?>