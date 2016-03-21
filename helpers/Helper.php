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

}
