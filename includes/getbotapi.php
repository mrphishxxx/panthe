<?php
/**
 * @version 0.4
 * История изменений:
 * версия 0.4:
 * 1. добавлен новый тариф GetbotApi::MODE_ABSOLUTE_UPDATE
 * версия 0.3:
 * 1. добавлен новый тариф GetbotApi::MODE_EXPRESS_PRIORITY
 * 2. добавлен новый тариф GetbotApi::MODE_CHECK_INDEX
 * 3. удален старый тариф GetbotApi::MODE_EXPRESS_LIGHT

 * версия 0.2:
 * 1. добавлен новый тариф GetbotApi::MODE_ABSOLUTE_PRIORITY
 * 2. объявление функций API как public
 */

class GetbotApi {
    /**
     * Любой тариф проекта, актуален для метода tasksList
     */
    const MODE_ANY = -1;
    /**
     * тариф АБСОЛЮТ
     */
    const MODE_ABSOLUTE = 0;
    /**
     * тариф Экспресс
     */
    const MODE_EXPRESS = 1;

    /**
     * тариф АБСОЛЮТ Приоритет
     */
    const MODE_ABSOLUTE_PRIORITY = 3;

    /**
     * тариф Экспресс Приоритет
     */
    const MODE_EXPRESS_PRIORITY = 4;

    /**
     * тариф Проверка на индексацию в Яндекс
     */
    const MODE_CHECK_INDEX = 5;

    /**
     * тариф АБСОЛЮТ Апдейт
     */
    const MODE_ABSOLUTE_UPDATE = 6;

    /** Проект в любой стадии выполнения, актуален для метода tasksList */
    const STATUS_ANY = -1;

    /** Проект проект создан пользователем, для проектов этого состояния нужно вызвать метод taskLaunch для запуска проекта на выполенение */
    const STATUS_CREATED = 0;

    /** Проект был запущен пользователем и будет выполнен системой Getbot.guru после того как до проекта дойдет очередь */
    const STATUS_WAITED = 1;

    /** Проект выполняется системой Getbot.guru */
    const STATUS_RUNNING = 2;

    /** Финальное состояние проекта, проект выполнен */
    const STATUS_READY = 3;

    /** Финальное состояние проекта, проект был отклонен администратором в соответствии с правилами сервиса */
    const STATUS_REJECTED = 4;


    private $noCurl;
    private $apiKey;
    private $apiEndpoint = 'http://getbot.guru/api';

    private $apiVersion = '1';

    function __construct($apiKey, $noCurl = false){
        $this->apiKey = $apiKey;
        $this->noCurl = $noCurl;
    }

    private function get($resource, $data = array()) {
        $data['api_key'] = $this->apiKey;

		$url = $this->apiEndpoint . 'v' . $this->apiVersion . '.php?task=' . $resource;
        $url .= '&' . http_build_query($data);
        if(!$this->noCurl) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            $response = curl_exec($ch);
            curl_close($ch);
        } else {
            $response = file_get_contents($url);
        }
        
        return json_decode($response);
    }

    private function post($resource, $data = array()) {
        $data['api_key'] = $this->apiKey;

		$url = $this->apiEndpoint . 'v' . $this->apiVersion . '.php?task=' . $resource;
        if(!$this->noCurl) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
            $response = curl_exec($ch);
            curl_close($ch);
        } else {
            $opts = array('http' =>
                array(
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/json',
                    'content' => json_encode($data)
                )
            );

            $context = stream_context_create($opts);
            $response = file_get_contents($url, false, $context);
        }

        return json_decode($response);
    }

    /**
     * Get first 1000 tasks by task status.
     * @param int $status
     * @param int $mode
     * @return mixed array of StdClass for task
     */
    public function tasksList($status = GetbotApi::STATUS_ANY, $mode = GetbotApi::MODE_ANY) {
        return $this->get("tasksList", array(
            "status" => $status,
            "mode" => $mode,
        ));
    }

    public function taskCreate($name, $urls, $mode = GetbotApi::MODE_EXPRESS_LIGHT, $description = '') {
        return $this->post("taskCreate", array(
            "name" => $name,
            "urls" => $urls,
            "mode" => $mode,
            "description" => $description
        ));
    }

    public function taskDetails($taskId) {
        return $this->get("taskDetails", array(
            "id" => $taskId));
    }

    public function taskLaunch($taskId) {
        return $this->get("taskLaunch", array(
            "id" => $taskId));
    }

    public function userBalance() {
        return $this->get("balance");
    }

    public function queueList() {
        return $this->get("queueList");
    }
}