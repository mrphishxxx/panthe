<?php

// Настрйоки почтальона
define('EMAIL_SUPPORT' , 'abashevav@gmail.com');
define('EMAIL_ADMIN'   , 'iforget.ru@gmail.com');
define('EMAIL_ROBOT'   , 'robot@iforget.ru');
define('EMAIL_NOREPLY' , 'noreply@iforget.ru');

class Postman{

    private $TEMPLATE_PATH;

    private $smarty;
    private $db;

    public function __construct($smarty, $db, $template_path = NULL) {
        if(isset($smarty) && isset($db)) {
            $this->smarty = $smarty;
            $this->db = $db;
            if(isset($template_path)) {
                $this->TEMPLATE_PATH = $template_path;
            } else {
                $this->TEMPLATE_PATH = PATH.'../includes/postman/templates/';
            }
        } else {
            echo '<pre>';
            var_dump("Smarty and db must be a initialized");
            echo '</pre>';
            return null;
        }
    }

    public function sendEmail($from, $to, $subject, $message, $additional_headers=null, $additional_parameters=null){
        // TODO: отсылаем пока что только себе. После релиза почтальона, удалить
        if($to == 'abashevav@gmail.com')
            mail($to, $subject, $message, $additional_headers, $additional_parameters);
    }

    public function sendPM($from, $to, $body, $message){

    }

    public function sendSMS($from, $to, $body, $message){

    }

    public function sendResetPassword($userData){
        $this->smarty->assign('userLogin', $userData['login']);
        if(isset($userData['Name']) && !empty($userData['name'])) {
            $this->smarty->assign('userName', $userData['name']);
        } else {
            $this->smarty->assign('userName', $userData['login']);
        }
        $this->smarty->assign('userPassword', $userData['passOriginal']);
        $this->smarty->assign('template', $this->TEMPLATE_PATH.'resetpassword.tpl');
        $message = $this->smarty->fetch($this->TEMPLATE_PATH.'email.tpl');
        $this->sendEmail(EMAIL_NOREPLY, $userData['email'], "Фотоконкурсы - Сброс пароля", $message);
    }

    public function sendTaskReminder($userEmail, $userName, $gameTitle, $taskDescription, $taskId){
        $this->smarty->assign('userName', $userName);
        $this->smarty->assign('gameTitle', $gameTitle);
        $this->smarty->assign('taskDescription', $taskDescription);
        $this->smarty->assign('taskLink', 'http://photo.rbyte.ru/task.php?action=upload&task='.$taskId);
        $this->smarty->assign('template', $this->TEMPLATE_PATH.'taskreminder.tpl');
        $message = $this->smarty->fetch($this->TEMPLATE_PATH.'email.tpl');
        $this->sendEmail(EMAIL_NOREPLY, $userEmail, "Photobyte - напоминание о задании", $message);
    }



}
