<?php

class write {

    public $GLOBAL;

    function in_template($db, $url) {
        $content = file_get_contents(PATH . 'modules/write/tmp/form.tpl');
        return $content;
    }

    function content($db, $url) {
        if (!$url[1]) {
            $fio = trim(strip_tags(htmlspecialchars($_REQUEST['fio'], ENT_QUOTES)));
            $mail = trim(strip_tags(htmlspecialchars($_REQUEST['mail'], ENT_QUOTES)));
            $phone = trim(strip_tags(htmlspecialchars($_REQUEST['phone'], ENT_QUOTES)));
            $text = trim(strip_tags(htmlspecialchars($_REQUEST['message'], ENT_QUOTES)));
            
            $body = "ФИО: $fio<br>E-mail: $mail<br>Телефон: $phone<br>Сообщение: $text";
            
            require_once 'includes/mandrill/mandrill.php';
            $mandrill = new Mandrill('zTiNSqPNVH3LpQdk1PgZ8Q');
            $message = array();
            $message["html"] = $body;
            $message["text"] = "";
            $message["subject"] = 'Новая заявка на http://' . $_SERVER['HTTP_HOST'];
            $message["from_email"] = "robot@" . $_SERVER['HTTP_HOST'];
            $message["from_name"] = "Robot";
            $message["to"] = array();
            $message["to"][0] = array("email" => MAIL, "name" => MAIL);
            $message["to"][0] = array("email" => "abashevav@gmail.com");
            $message["track_opens"] = null;
            $message["track_clicks"] = null;
            $message["auto_text"] = null;

            try {
                $mandrill->messages->send($message);
            } catch (Exception $e) {
                echo '';
            }

            header('location:/write/sended.html');
            exit;
        }
        if ($url[1] == 'sended') {
            $this->GLOBAL['title'] = 'Обратная связь';
            $this->GLOBAL['description'] = 'Обратная связь';
            $this->GLOBAL['keywords'] = 'Обратная связь';
            $this->GLOBAL['page_title'] = 'Обратная связь';
            $content = '<p>Заявка успешно отправлена<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /></p>';
        }
        $this->GLOBAL['content'] = $content;
        return $this->GLOBAL;
    }

}

?>