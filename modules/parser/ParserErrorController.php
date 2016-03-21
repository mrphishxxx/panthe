<?php

define("PARSER_ERROR_OTHER", 0);
define("PARSER_ERROR_NOT_BURSE", 1);
define("PARSER_ERROR_NOT_USERS", 2);
define("PARSER_ERROR_NOT_SITES", 3);

define("SELENIUM_ERROR_NOT_CREATE", 0);

class ParserErrorController {

    public function getErrorText($error_code) {
        if (!empty($error_code)) {
            switch ($error_code) {
                case PARSER_ERROR_OTHER:
                    return 'ERROR: Работа скрипта остановлена!';
                case PARSER_ERROR_NOT_BURSE:
                    return 'ERROR: Не найдено ни одного доступа в биржу!';
                case PARSER_ERROR_NOT_USERS:
                    return 'ERROR: Нет ни одного пользователя, у которого можно выгружать заявки!';
                case PARSER_ERROR_NOT_SITES:
                    return 'ERROR: Нет ни одного сайта, для которого можно выгружать заявки!';
                
                default:
                    break;
            }
        }
    }

}
