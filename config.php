<?php
date_default_timezone_set('Europe/Moscow'); 
error_reporting (E_ALL);

define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_BASE', 'iforget');

define('MAIL_ADMIN', 'iforget.ru@gmail.com');
define('MAIL_DEVELOPER', 'abashevav@gmail.com');

define('ETXT_PASS', 'geFbINbs');
define('ETXT_TOKEN', '29aa0eec2c77dd6d06e23b3faaef9eed');

define('MANDRILL', 'zTiNSqPNVH3LpQdk1PgZ8Q');

define('GETBOT_APIKEY', 'd1451b02a44a571bb9d8d6033624649b');
define('TEXTRU_APIKEY', 'd83546e9b977aa6e6c256cf6c9adba81');

define('PATH', dirname(__FILE__).'\\');
define('LOGIN_IN_SAPE', 'iforget');
define('PASS_IN_SAPE', 'iforgetiforget');

/*  OTHER CONSTANTS   */
define("LIMIT_ERROR_FROM_COPYWRITER", 3);
define("COPYWRITER_PRICE_FOR_1000_CHAR", 21);
define("UNIQ_TEXT_FROM_COPYWRITER", 95);

?>
