<?php

class graphs {

    function content($db) {
        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
        $action2 = isset($_REQUEST['action2']) ? $_REQUEST['action2'] : '';

        switch (@$action) {
            case '':
                $content = $this->index();
                break;
        }
        return $content;
    }

    function index() {
        return NULL;
    }

    
    /* Chart 1 (Динамика клиентской базы от месяца) */
    public function getExcelUserRegistration($db, $objPHPExcel = null, $sheet = 0, $date_main = 0) {
        $stat_users_reg = array();
        $objPHPExcel->setActiveSheetIndex($sheet)->setTitle("Зарег-ные пользователи");
        $objPHPExcel->setActiveSheetIndex($sheet)
                ->setCellValue('A1', 'Mounth')
                ->setCellValue('B1', 'Count');

        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()->setRGB('b9cde4');
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getfont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);

        $row = 1;
        $date_start = strtotime($date_main);
        $month = $this->getMounth();
        if (!empty($month) && is_array($month)) {
            foreach ($month as $key => $val) {
                $date_time = explode(" ", $date_main);
                $date = explode("-", $date_time[0]);
                $time = explode(":", $date_time[1]);
                $end_date = mktime($time[0], $time[1], $time[2], $key + 1, $date[2], $date[0]);
                $users = $db->Execute("SELECT * FROM admins WHERE type='user' AND reg_date > '$date_start' AND reg_date < '$end_date'");
                while ($user = $users->FetchRow()) {
                    if (!isset($stat_users_reg[$key])) {
                        $stat_users_reg[$key] = array();
                    }
                    $stat_users_reg[$key][] = $user["id"];
                }
            }
        }
        
        foreach ($stat_users_reg as $month => $arr) {
            $row++;
            $objPHPExcel->getActiveSheet()->getStyle("A$row:B$row")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->setActiveSheetIndex($sheet)
                    ->setCellValue('A' . $row, $this->getMounth($month))
                    ->setCellValue('B' . $row, count($arr));
        }
        $objPHPExcel->getActiveSheet()->getStyle("A1:A$row")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("B1:B$row")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("A2:B$row")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        return $objPHPExcel;
    }

    
    /* Chart 2 (динамика активных ( платящих) и нективных пользователей) */
    public function getExcelDynamicsUsers($db, $objPHPExcel = null, $sheet = 0, $date_main = 0) {
        $users_active = $users_not_active = array();

        if (count($objPHPExcel->getAllSheets()) <= $sheet) {
            $objPHPExcel->createSheet();
        }
        $objPHPExcel->setActiveSheetIndex($sheet)->setTitle("Динамика пользователей");
        $objPHPExcel->setActiveSheetIndex($sheet)
                ->setCellValue('A1', 'Mounth')
                ->setCellValue('B1', 'Active')
                ->setCellValue('C1', 'Not active');

        $objPHPExcel->getActiveSheet()->getStyle("A1:C1")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A1:C1")->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()->setRGB('b9cde4');
        $objPHPExcel->getActiveSheet()->getStyle("A1:C1")->getfont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("A1:C1")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);

        $orders = $db->Execute("SELECT o.* FROM `orders` o LEFT JOIN admins a ON o.uid = a.id WHERE o.STATUS=1 AND o.date > '$date_main'");
        while ($order = $orders->FetchRow()) {
            $date_time = explode(" ", $order["date"]);
            $date = explode("-", $date_time[0]);
            $time = explode(":", $date_time[1]);
            $time_unix = mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]);
            $date_time_array = getdate($time_unix);
            if (!isset($users_active[$date_time_array["mon"]])) {
                $users_active[$date_time_array["mon"]] = array();
            }

            if (!in_array($order["uid"], $users_active[$date_time_array["mon"]])) {
                $users_active[$date_time_array["mon"]][] = $order["uid"];
            }
            unset($date_time_array);
        }


        /* NOT ACTIVE USERS */
        $date_start = strtotime($date_main);
        $month = $this->getMounth();
        if (!empty($month) && is_array($month)) {
            foreach ($month as $key => $val) {
                $date_time = explode(" ", $date_main);
                $date = explode("-", $date_time[0]);
                $time = explode(":", $date_time[1]);
                $end_date = mktime($time[0], $time[1], $time[2], $key + 1, $date[2], $date[0]);
                $users = $db->Execute("SELECT * FROM admins WHERE type='user' AND reg_date > '$date_start' AND reg_date < '$end_date'");
                if (!isset($users_active[$key])) {
                    continue;
                }
                while ($user = $users->FetchRow()) {
                    if (!isset($users_not_active[$key])) {
                        $users_not_active[$key] = array();
                    }
                    if (!in_array($user["id"], $users_active[$key])) {
                        $users_not_active[$key][] = $user["id"];
                    }
                }
            }
        }

        ksort($users_active);
        ksort($users_not_active);
        $row = 1;
        foreach ($users_active as $month => $array_users) {
            $row++;
            $not_active = (isset($users_not_active[$month]) && !empty($users_not_active[$month])) ? $users_not_active[$month] : 0;
            $objPHPExcel->getActiveSheet()->getStyle("A$row:C$row")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->setActiveSheetIndex($sheet)
                    ->setCellValue('A' . $row, $this->getMounth($month))
                    ->setCellValue('B' . $row, count($array_users))
                    ->setCellValue('C' . $row, count($not_active));
        }
        $objPHPExcel->getActiveSheet()->getStyle("A1:A$row")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("B1:B$row")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("C1:C$row")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("A2:C$row")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        return $objPHPExcel;
    }

    
    /* Chart 3 (Кол-во заявок на актиктивного клиета в месяц) */
    public function getExcelNumberTasksForActiveClient($db, $objPHPExcel = null, $sheet = 0, $date = 0) {
        if (count($objPHPExcel->getAllSheets()) <= $sheet) {
            $objPHPExcel->createSheet();
        }
        $objPHPExcel->setActiveSheetIndex($sheet)->setTitle("Заявки активного клиента");
        $objPHPExcel->setActiveSheetIndex($sheet)
                ->setCellValue('A1', 'Mounth')
                ->setCellValue('B1', 'Tasks');

        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()->setRGB('b9cde4');
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getfont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);

        $row = 1;
        $active_users = $users = $statistics = array();
        $date_start = strtotime($date);
        $orders = $db->Execute("SELECT o.* FROM `orders` o LEFT JOIN admins a ON o.uid = a.id WHERE o.STATUS=1 AND o.date > '$date'");
        while ($order = $orders->FetchRow()) {
            $date_time = explode(" ", $order["date"]);
            $date = explode("-", $date_time[0]);
            $time = explode(":", $date_time[1]);
            $time_unix = mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]);
            $date_time_array = getdate($time_unix);
            if (!isset($users[$date_time_array["mon"]])) {
                $users[$date_time_array["mon"]] = array();
            }

            if (!in_array($order["uid"], $users[$date_time_array["mon"]])) {
                $users[$date_time_array["mon"]][] = $order["uid"];
            }

            if (!in_array($order["uid"], $active_users)) {
                $active_users[] = $order["uid"];
            }
            unset($date_time_array);
        }

        $zadaniya = $db->Execute("SELECT * FROM zadaniya WHERE uid IN (" . implode(",", $active_users) . ") AND date > '$date_start'");
        while ($task = $zadaniya->FetchRow()) {
            $date_time_array = getdate($task["date"]);
            if (!isset($statistics[$date_time_array["mon"]])) {
                $statistics[$date_time_array["mon"]] = 0;
            }
            $statistics[$date_time_array["mon"]] += 1;
            unset($date_time_array);
        }
        $zadaniya_new = $db->Execute("SELECT * FROM zadaniya_new WHERE date > '$date_start'");
        while ($task = $zadaniya_new->FetchRow()) {
            $date_time_array = getdate($task["date"]);
            if (!isset($statistics[$date_time_array["mon"]])) {
                $statistics[$date_time_array["mon"]] = 0;
            }
            $statistics[$date_time_array["mon"]] += 1;
            unset($date_time_array);
        }

        ksort($statistics);
        foreach ($statistics as $month => $count) {
            $row++;
            $objPHPExcel->getActiveSheet()->getStyle("A$row:B$row")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->setActiveSheetIndex($sheet)
                    ->setCellValue('A' . $row, $this->getMounth($month))
                    ->setCellValue('B' . $row, ceil($count / count($users[$month])));
        }
        $objPHPExcel->getActiveSheet()->getStyle("A1:A$row")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("B1:B$row")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("A2:B$row")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        return $objPHPExcel;
    }

    
    /* Chart 4 (Средний оборот на 1 сайт в месяц) */
    public function getExcelAverageTurnoverOfOneSitePerMonth($db, $objPHPExcel = null, $sheet = 0, $date = 0) {
        if (count($objPHPExcel->getAllSheets()) <= $sheet) {
            $objPHPExcel->createSheet();
        }
        $objPHPExcel->setActiveSheetIndex($sheet)->setTitle("Средний оборот на сайт");
        $objPHPExcel->setActiveSheetIndex($sheet)
                ->setCellValue('A1', 'Mounth')
                ->setCellValue('B1', 'Sum');

        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()->setRGB('b9cde4');
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getfont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);

        $row = 1;
        $summ = 0;
        $statistics = array();
        $date_start = strtotime($date);
        $orders = $db->Execute("SELECT * FROM `completed_tasks` WHERE STATUS=1 AND date > '$date'");
        while ($order = $orders->FetchRow()) {
            $date_time = explode(" ", $order["date"]);
            $date = explode("-", $date_time[0]);
            $time = explode(":", $date_time[1]);
            $time_unix = mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]);
            $date_time_array = getdate($time_unix);
            if (!isset($statistics[$date_time_array["mon"]])) {
                $statistics[$date_time_array["mon"]] = 0;
            }

            $statistics[$date_time_array["mon"]] += $order["price"];
            $summ += $order["price"];
            unset($date_time_array);
        }

        $date_end = $date_start + 31536000; // + год 60*60*24*365
        $zadaniya_new = $db->Execute("SELECT * FROM `zadaniya_new` WHERE (etxt = 1 OR copywriter != 0) AND date > '$date_start' AND date < '$date_end'");
        while ($zadaniya = $zadaniya_new->FetchRow()) {
            $date_time_array = getdate($zadaniya["date"]);
            if (!isset($statistics[$date_time_array["mon"]])) {
                $statistics[$date_time_array["mon"]] = 0;
            }
            switch ($zadaniya["nof_chars"]) {
                case 1000 :
                    $price = ($zadaniya["price"] == 20) ? 32 : 0;
                    break;
                case 1500 :
                    $price = ($zadaniya["price"] == 30) ? 57 : 0;
                    break;
                case 2000 :
                    $price = ($zadaniya["price"] == 20) ? 58 : 73;
                    break;

                default : $price = 32;
            }

            $statistics[$date_time_array["mon"]] += $price;
            $summ += $price;
            unset($date_time_array);
        }

        $sites = $db->Execute("SELECT s.* FROM `sayty` s LEFT JOIN admins a ON a.id = s.uid WHERE a.id !=0 AND a.type = 'user'");
        $count_sites = $sites->NumRows();

        ksort($statistics);
        foreach ($statistics as $month => $count) {
            $row++;
            $objPHPExcel->getActiveSheet()->getStyle("A$row:B$row")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->setActiveSheetIndex($sheet)
                    ->setCellValue('A' . $row, $this->getMounth($month))
                    ->setCellValue('B' . $row, ceil($count / $count_sites));
        }
        $objPHPExcel->getActiveSheet()->getStyle("A1:A$row")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("B1:B$row")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("A2:B$row")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        return $objPHPExcel;
    }

    
    /* Chart 5 (Оборот - общее кол-во заявок в месяц) Оборот - сумма за заявку! 1 заявка = 45 руб. Оборот = 45 руб) */
    public function getExcelTurnoverTotalNumberTasksPerMonth($db, $objPHPExcel = null, $sheet = 0, $date = 0) {
        if (count($objPHPExcel->getAllSheets()) <= $sheet) {
            $objPHPExcel->createSheet();
        }
        $objPHPExcel->setActiveSheetIndex($sheet)->setTitle("Оборот всех заявок за месяц");
        $objPHPExcel->setActiveSheetIndex($sheet)
                ->setCellValue('A1', 'Mounth')
                ->setCellValue('B1', 'Sum');

        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()->setRGB('b9cde4');
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getfont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);

        $row = 1;
        $summ = 0;
        $statistics = array();
        $date_start = strtotime($date);
        $orders = $db->Execute("SELECT * FROM `completed_tasks` WHERE STATUS=1 AND date > '$date'");
        while ($order = $orders->FetchRow()) {
            $date_time = explode(" ", $order["date"]);
            $date = explode("-", $date_time[0]);
            $time = explode(":", $date_time[1]);
            $time_unix = mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]);
            $date_time_array = getdate($time_unix);
            if (!isset($statistics[$date_time_array["mon"]])) {
                $statistics[$date_time_array["mon"]] = 0;
            }

            $statistics[$date_time_array["mon"]] += $order["price"];
            $summ += $order["price"];
            unset($date_time_array);
        }

        $date_end = $date_start + 31536000; // + год 60*60*24*365
        $zadaniya_new = $db->Execute("SELECT * FROM `zadaniya_new` WHERE (etxt = 1 OR copywriter != 0) AND date > '$date_start' AND date < '$date_end'");
        while ($zadaniya = $zadaniya_new->FetchRow()) {
            $date_time_array = getdate($zadaniya["date"]);
            if (!isset($statistics[$date_time_array["mon"]])) {
                $statistics[$date_time_array["mon"]] = 0;
            }
            switch ($zadaniya["nof_chars"]) {
                case 1000 :
                    $price = ($zadaniya["price"] == 20) ? 32 : 0;
                    break;
                case 1500 :
                    $price = ($zadaniya["price"] == 30) ? 57 : 0;
                    break;
                case 2000 :
                    $price = ($zadaniya["price"] == 20) ? 58 : 73;
                    break;

                default : $price = 32;
            }

            $statistics[$date_time_array["mon"]] += $price;
            $summ += $price;
            unset($date_time_array);
        }

        ksort($statistics);
        foreach ($statistics as $month => $count) {
            $row++;
            $objPHPExcel->getActiveSheet()->getStyle("A$row:B$row")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->setActiveSheetIndex($sheet)
                    ->setCellValue('A' . $row, $this->getMounth($month))
                    ->setCellValue('B' . $row, ceil($count));
        }
        $objPHPExcel->getActiveSheet()->getStyle("A1:A$row")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("B1:B$row")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("A2:B$row")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        return $objPHPExcel;
    }

    
    /* Chart 6 (Чистая прибыль в месяц) */
    public function getExcelNetProfitPerMonth($db, $objPHPExcel = null, $sheet = 0, $date = 0) {
        if (count($objPHPExcel->getAllSheets()) <= $sheet) {
            $objPHPExcel->createSheet();
        }
        $objPHPExcel->setActiveSheetIndex($sheet)->setTitle("Чистая прибыль в месяц");
        $objPHPExcel->setActiveSheetIndex($sheet)
                ->setCellValue('A1', 'Mounth')
                ->setCellValue('B1', 'Sum');

        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()->setRGB('b9cde4');
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getfont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);

        $row = 1;
        $summ = 0;
        $statistics = array();
        $date_start = strtotime($date);
        $orders = $db->Execute("SELECT * FROM `completed_tasks` WHERE STATUS=1 AND date > '$date'");
        while ($order = $orders->FetchRow()) {
            $date_time = explode(" ", $order["date"]);
            $date = explode("-", $date_time[0]);
            $time = explode(":", $date_time[1]);
            $time_unix = mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]);
            $date_time_array = getdate($time_unix);
            if (!isset($statistics[$date_time_array["mon"]])) {
                $statistics[$date_time_array["mon"]] = 0;
            }
            $price = 0;
            switch ($order['price']) {
                case 15 : $price = 15;
                    break;
                case 45 : $price = 13.5;
                    break;
                case 61 : $price = 13.75;
                    break;
                case 93 : $price = 22.12;
                    break;
                case 60 : $price = 18;
                    break;
                case 76 : $price = 13;
                    break;
                case 111 : $price = 16.5;
                    break;
            }
            $price -= 3.5; // Выкладывальщику
            $statistics[$date_time_array["mon"]] += $price;
            $summ += $price;
            unset($date_time_array);
        }

        $date_end = $date_start + 31536000; // + год 60*60*24*365
        $zadaniya_new = $db->Execute("SELECT * FROM `zadaniya_new` WHERE (etxt = 1 OR copywriter != 0) AND date > '$date_start' AND date < '$date_end'");
        while ($zadaniya = $zadaniya_new->FetchRow()) {
            $date_time_array = getdate($zadaniya["date"]);
            if (!isset($statistics[$date_time_array["mon"]])) {
                $statistics[$date_time_array["mon"]] = 0;
            }
            switch ($zadaniya["nof_chars"]) {
                case 1000 :
                    $price = ($zadaniya["price"] == 20) ? 11 : 0;
                    break;
                case 1500 :
                    $price = ($zadaniya["price"] == 30) ? 9.75 : 0;
                    break;
                case 2000 :
                    $price = ($zadaniya["price"] == 20) ? 16 : 10;
                    break;

                default : $price = 11;
            }

            $statistics[$date_time_array["mon"]] += $price;
            $summ += $price;
            unset($date_time_array);
        }

        ksort($statistics);
        foreach ($statistics as $month => $count) {
            $row++;
            $objPHPExcel->getActiveSheet()->getStyle("A$row:B$row")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->setActiveSheetIndex($sheet)
                    ->setCellValue('A' . $row, $this->getMounth($month))
                    ->setCellValue('B' . $row, round($count, 2));
        }
        $objPHPExcel->getActiveSheet()->getStyle("A1:A$row")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("B1:B$row")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("A2:B$row")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        return $objPHPExcel;
    }
    
    
    /* Chart 7 (средняя чистая прибыль на 1 заявку в месяц) */
    public function getExcelAverageNetProfitOfOneApplicationPerMonth($db, $objPHPExcel = null, $sheet = 0, $date_main = 0) {
        if (count($objPHPExcel->getAllSheets()) <= $sheet) {
            $objPHPExcel->createSheet();
        }
        $objPHPExcel->setActiveSheetIndex($sheet)->setTitle("Чистая прибыль на 1 заявку");
        $objPHPExcel->setActiveSheetIndex($sheet)
                ->setCellValue('A1', 'Mounth')
                ->setCellValue('B1', 'Sum');

        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()->setRGB('b9cde4');
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getfont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);

        $row = 1;
        $statistics = $stat_zadaniya = $active_users = array();
        $date_start = strtotime($date_main);
        $orders = $db->Execute("SELECT * FROM `completed_tasks` WHERE STATUS=1 AND date > '$date_main'");
        while ($order = $orders->FetchRow()) {
            $date_time = explode(" ", $order["date"]);
            $date = explode("-", $date_time[0]);
            $time = explode(":", $date_time[1]);
            $time_unix = mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]);
            $date_time_array = getdate($time_unix);
            if (!isset($statistics[$date_time_array["mon"]])) {
                $statistics[$date_time_array["mon"]] = 0;
            }
            $price = 0;
            switch ($order['price']) {
                case 15 : $price = 15;
                    break;
                case 45 : $price = 13.5;
                    break;
                case 61 : $price = 13.75;
                    break;
                case 93 : $price = 22.12;
                    break;
                case 60 : $price = 18;
                    break;
                case 76 : $price = 13;
                    break;
                case 111 : $price = 16.5;
                    break;
            }
            $price -= 3.5; // Выкладывальщику
            $statistics[$date_time_array["mon"]] += $price;
            unset($date_time_array);
        }

        $date_end = $date_start + 31536000; // + год 60*60*24*365
        $zadaniya_new = $db->Execute("SELECT * FROM `zadaniya_new` WHERE (etxt = 1 OR copywriter != 0) AND date > '$date_start' AND date < '$date_end'");
        while ($zadaniya = $zadaniya_new->FetchRow()) {
            $date_time_array = getdate($zadaniya["date"]);
            if (!isset($statistics[$date_time_array["mon"]])) {
                $statistics[$date_time_array["mon"]] = 0;
            }
            if (!isset($stat_zadaniya[$date_time_array["mon"]])) {
                $stat_zadaniya[$date_time_array["mon"]] = 0;
            }
            switch ($zadaniya["nof_chars"]) {
                case 1000 :
                    $price = ($zadaniya["price"] == 20) ? 11 : 0;
                    break;
                case 1500 :
                    $price = ($zadaniya["price"] == 30) ? 9.75 : 0;
                    break;
                case 2000 :
                    $price = ($zadaniya["price"] == 20) ? 16 : 10;
                    break;

                default : $price = 11;
            }

            $statistics[$date_time_array["mon"]] += $price;
            $stat_zadaniya[$date_time_array["mon"]] += 1;
            unset($date_time_array);
        }
        ksort($statistics);
        
        /* СЧИАТЕМ СКОЛЬКО ЗАЯВОК КАЖДЫЙ МЕСЯЦ */
        $admins = $db->Execute("SELECT o.* FROM `orders` o LEFT JOIN admins a ON o.uid = a.id WHERE o.STATUS=1 AND o.date > '$date_main'");
        while ($order = $admins->FetchRow()) {
            if (!in_array($order["uid"], $active_users)) {
                $active_users[] = $order["uid"];
            }
            unset($date_time_array);
        }

        $zadaniya = $db->Execute("SELECT * FROM zadaniya WHERE uid IN (" . implode(",", $active_users) . ") AND date > '$date_start'");
        while ($task = $zadaniya->FetchRow()) {
            $date_time_array = getdate($task["date"]);
            if (!isset($stat_zadaniya[$date_time_array["mon"]])) {
                $stat_zadaniya[$date_time_array["mon"]] = 0;
            }
            $stat_zadaniya[$date_time_array["mon"]] += 1;
            unset($date_time_array);
        }
        ksort($stat_zadaniya);
        
        
        foreach ($statistics as $month => $count) {
            $row++;
            $num_tasks = $stat_zadaniya[$month];
            $objPHPExcel->getActiveSheet()->getStyle("A$row:B$row")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->setActiveSheetIndex($sheet)
                    ->setCellValue('A' . $row, $this->getMounth($month))
                    ->setCellValue('B' . $row, round(($count / $num_tasks), 2));
        }
        $objPHPExcel->getActiveSheet()->getStyle("A1:A$row")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("B1:B$row")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("A2:B$row")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        return $objPHPExcel;
    }
    
    
    /* Chart 8 (средний оборот на 1 клиента в месяц) */
    public function getExcelAverageTurnoverOfOneClientPerMonth($db, $objPHPExcel = null, $sheet = 0, $date_main = 0) {
        if (count($objPHPExcel->getAllSheets()) <= $sheet) {
            $objPHPExcel->createSheet();
        }
        $objPHPExcel->setActiveSheetIndex($sheet)->setTitle("Чистая прибыль на 1 клиента");
        $objPHPExcel->setActiveSheetIndex($sheet)
                ->setCellValue('A1', 'Mounth')
                ->setCellValue('B1', 'Sum');

        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()->setRGB('b9cde4');
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getfont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("A1:B1")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);

        $row = 1;
        $statistics = $stat_users = array();
        $date_start = strtotime($date_main);
        $orders = $db->Execute("SELECT * FROM `completed_tasks` WHERE STATUS=1 AND date > '$date_main'");
        while ($order = $orders->FetchRow()) {
            $date_time = explode(" ", $order["date"]);
            $date = explode("-", $date_time[0]);
            $time = explode(":", $date_time[1]);
            $time_unix = mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]);
            $date_time_array = getdate($time_unix);
            if (!isset($statistics[$date_time_array["mon"]])) {
                $statistics[$date_time_array["mon"]] = 0;
            }
            $price = 0;
            switch ($order['price']) {
                case 15 : $price = 15;
                    break;
                case 45 : $price = 13.5;
                    break;
                case 61 : $price = 13.75;
                    break;
                case 93 : $price = 22.12;
                    break;
                case 60 : $price = 18;
                    break;
                case 76 : $price = 13;
                    break;
                case 111 : $price = 16.5;
                    break;
            }
            $price -= 3.5; // Выкладывальщику
            $statistics[$date_time_array["mon"]] += $price;
            unset($date_time_array);
        }

        $date_end = $date_start + 31536000; // + год 60*60*24*365
        $zadaniya_new = $db->Execute("SELECT * FROM `zadaniya_new` WHERE (etxt = 1 OR copywriter != 0) AND date > '$date_start' AND date < '$date_end'");
        while ($zadaniya = $zadaniya_new->FetchRow()) {
            $date_time_array = getdate($zadaniya["date"]);
            if (!isset($statistics[$date_time_array["mon"]])) {
                $statistics[$date_time_array["mon"]] = 0;
            }
            switch ($zadaniya["nof_chars"]) {
                case 1000 :
                    $price = ($zadaniya["price"] == 20) ? 11 : 0;
                    break;
                case 1500 :
                    $price = ($zadaniya["price"] == 30) ? 9.75 : 0;
                    break;
                case 2000 :
                    $price = ($zadaniya["price"] == 20) ? 16 : 10;
                    break;

                default : $price = 11;
            }

            $statistics[$date_time_array["mon"]] += $price;
            unset($date_time_array);
        }
        ksort($statistics);
        
        /* СЧИАТЕМ СКОЛЬКО ПОЛЬЗОВАТЕЛЕЙ */
        $month = $this->getMounth();
        if (!empty($month) && is_array($month)) {
            foreach ($month as $key => $val) {
                $date_time = explode(" ", $date_main);
                $date = explode("-", $date_time[0]);
                $time = explode(":", $date_time[1]);
                $end_date = mktime($time[0], $time[1], $time[2], $key + 1, $date[2], $date[0]);
                $users = $db->Execute("SELECT * FROM admins WHERE type='user' AND reg_date > '$date_start' AND reg_date < '$end_date'");
                while ($user = $users->FetchRow()) {
                    if (!isset($stat_users[$key])) {
                        $stat_users[$key] = 0;
                    }
                    $stat_users[$key] += 1;
                }
            }
        }
               
        foreach ($statistics as $month => $count) {
            $row++;
            $num_users = $stat_users[$month];
            $objPHPExcel->getActiveSheet()->getStyle("A$row:B$row")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->setActiveSheetIndex($sheet)
                    ->setCellValue('A' . $row, $this->getMounth($month))
                    ->setCellValue('B' . $row, round(($count / $num_users), 2));
        }
        $objPHPExcel->getActiveSheet()->getStyle("A1:A$row")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("B1:B$row")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle("A2:B$row")->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        return $objPHPExcel;
    }

    private function getMounth($month = null) {
        if (!empty($month)) {
            switch ($month) {
                case 1: return "Январь";
                case 2: return "Февраль";
                case 3: return "Март";
                case 4: return "Апрель";
                case 5: return "Май";
                case 6: return "Июнь";
                case 7: return "Июль";
                case 8: return "Август";
                case 9: return "Сентябрь";
                case 10: return "Октябрь";
                case 11: return "Ноябрь";
                case 12: return "Декабрь";
            }
        } else {
            return array(
                1 => "Январь",
                2 => "Февраль",
                3 => "Март",
                4 => "Апрель",
                5 => "Май",
                6 => "Июнь",
                7 => "Июль",
                8 => "Август",
                9 => "Сентябрь",
                10 => "Октябрь",
                11 => "Ноябрь",
                12 => "Декабрь"
            );
        }
    }

}

?>
