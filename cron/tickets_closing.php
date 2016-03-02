<?php

error_reporting(E_ALL ^ E_NOTICE);
include_once dirname(__FILE__) . '/../' . 'config.php';
include_once dirname(__FILE__) . '/../' . 'configs/setup-smarty.php';
include_once dirname(__FILE__) . '/../' . 'includes/postman/Postman.php';
include_once dirname(__FILE__) . '/../' . 'includes/adodb5/adodb.inc.php';
include_once dirname(__FILE__) . '/../' . 'modules/mails/MailsController.php';

$db = ADONewConnection(DB_TYPE);
@$db->PConnect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Не удается подключиться к базе данных');
$db->Execute('set charset utf8');
$db->Execute('SET NAMES utf8');
$smarty = new Smarty_Project();
$mails = new MailsController($db, $smarty);

$date_minus_three_days = time() - 259200; // 'сейчас' - 'минус три дня'
$admins_managers = $answers = $tickets = array();

// Собраем массив с Админами и Менеджерами
$admins_model = $db->Execute("SELECT id FROM admins WHERE type = 'admin' OR type = 'manager'")->GetAll();
foreach ($admins_model as $user) {
    $admins_managers[$user["id"]] = $user["id"];
}

// Берем все открытые тикеты и собираем их в массив
// Затем вытаскиваем последний ответ для каждого тикета
// Складываем в другой массив
$tickets_model = $db->Execute("SELECT * FROM tickets WHERE status != 0 ORDER BY id DESC")->GetAll();
foreach ($tickets_model as $ticket) {
    $tickets[$ticket["id"]] = $ticket;
    $answers[] = $db->Execute("SELECT tid, uid, date FROM answers WHERE tid = " . $ticket["id"] . " ORDER BY date DESC LIMIT 1")->FetchRow();
}


$uids_send_mails = array();
if (!empty($answers)) {
    foreach ($answers as $value) {
        //Переводим даты в time()
        $date_last_answer = strtotime($value["date"]);

        // Если это время меньше, чем время "'сейчас' - 'минус три дня'"
        // А также, если последний ответ был только от Админа/Менеджера
        if ($date_last_answer < $date_minus_three_days && array_search($value["uid"], $admins_managers)) {
            // Закрываем тикет
            $db->Execute("UPDATE tickets SET status = 0 WHERE id = " . $value["tid"]);

            // Берем данный тикет
            $ticket = $tickets[$value["tid"]];

            // Проверяем, "кто создал"
            // Если админ, то проверяем "для кого создан"
            // Помещаем ID пользователя в массив для отправки писем
            if (!array_search($ticket["uid"], $admins_managers)) {
                $uids_send_mails[$value["tid"]] = $ticket["uid"];
            } elseif (!array_search($ticket["to_uid"], $admins_managers)) {
                $uids_send_mails[$value["tid"]] = $ticket["to_uid"];
            }
        }
    }
    // Отправляем письма пользователям, о закрытии тикета
    if (!empty($uids_send_mails)) {
        $body = $mails->ticketClosed($uids_send_mails);
    }
}
echo date("Y-m-d H:i:s") . PHP_EOL . $body . PHP_EOL;
exit();

























