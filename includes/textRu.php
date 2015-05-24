<?php

/*
  2 функции для взаимодействия с API Text.ru посредством POST-запросов.
  Ответы с сервера приходят в формате JSON.
 */

//-----------------------------------------------------------------------
class APITextRu {
    
    public $API_KEY = '';
    
    public function __construct($api_key) {
        $this->API_KEY = $api_key;
    }

    /**
     * Добавление текста на проверку
     *
     * @param string $text - проверяемый текст
     * @param string $user_key - пользовательский ключ
     * @param string $exceptdomain - исключаемые домены
     *
     * @return string $text_uid - uid добавленного текста 
     * @return int $error_code - код ошибки
     * @return string $error_desc - описание ошибки
     */
    public function addPost($text = "") {
        if($text == ""){
            return NULL; 
        }
        
        $post = array();
        $post['text'] = $text;
        $post['userkey'] = $this->API_KEY;
        $post['exceptdomain'] = "";
        $postQuery = http_build_query($post, '', '&');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.text.ru/post');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postQuery);
        $json = curl_exec($ch);
        $errno = curl_errno($ch);

        // если произошла ошибка
        if (!$errno) {
            $result = json_decode($json);
            if (isset($result->text_uid)) {
                $text_uid = $result->text_uid;
            } else {
                $errmsg = array("code" => $result->error_code, "description" => $result->error_desc);
            }
        } else {
            $errmsg = curl_error($ch);
        }

        curl_close($ch);
        
        if(!empty($errmsg)){
            return $errmsg;
        } else {
            return $text_uid;
        }
    }

    /**
     * Получение статуса и результатов проверки текста в формате json
     */
    public function getResultPost($id = NULL, $detail = NULL) {
        if(empty($id)){
            return NULL; 
        }
        $post = array();
        $post['uid'] = $id;
        $post['userkey'] = $this->API_KEY;
        if(!empty($detail)){
            $post["jsonvisible"] = "detail";
        }
        $postQuery = http_build_query($post, '', '&');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.text.ru/post');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postQuery);
        $json = curl_exec($ch);
        $errno = curl_errno($ch);

        if (!$errno) {
            $result = json_decode($json);
            if (!isset($result->text_unique)) {
                $errmsg = array("code" => $result->error_code, "description" => $result->error_desc);
            }
        } else {
            $errmsg = curl_error($ch);
        }

        curl_close($ch);
        if(!empty($errmsg)){
            return $errmsg;
        } else {
            return $result;
        }
    }

}