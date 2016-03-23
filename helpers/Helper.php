<?php

class Helper {

    public static function textQuality($quality = null) {
        if (!empty($quality)) {
            switch ($quality) {
                case 20:
                    return "Обычное";
                case 30:
                    return "Высокое";
                case 45:
                    return "Эксперт";
                case 50:
                    return "Эксперт";

                default: return "Обычное";
            }
        } else {
            return array(
                20 => "Обычное",
                30 => "Высокое",
                45 => "Эксперт",
                50 => "Эксперт"
            );
        }
    }
    
    public static function parserFunctionName($function = null) {
        if (!empty($function)) {
            switch ($function) {
                case "get_tasks_getgoodlinks":
                    return "Выгрузка задач из GetGoodLinks";
                case "get_tasks_gogetlinks":
                    return "Выгрузка задач из Gogetlinks";
                case "get_tasks_miralinks":
                    return "Выгрузка задач из Miralinks";

                default: return NULL;
            }
        } else {
            return array(
                "get_tasks_getgoodlinks" => "Выгрузка задач из GetGoodLinks",
                "get_tasks_gogetlinks" => "Выгрузка задач из Gogetlinks",
                "get_tasks_miralinks" => "Выгрузка задач из Miralinks",
            );
        }
    }

}
