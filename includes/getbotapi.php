<?php

class GetbotApi {
    const MODE_ANY           = -1;
    const MODE_ABSOLUTE           =  0;
    const MODE_EXPRESS           =  1;
    const MODE_EXPRESS_LIGHT         = 2;

    /** New project */
    const STATUS_ANY = -1;

    /** New project */
    const STATUS_CREATED = 0;

    /** Project in idle */
    const STATUS_WAITED = 1;

    /** Project is running */
    const STATUS_RUNNING = 2;

    /** Project is ready */
    const STATUS_READY = 3;

    /** Project is rejected by admin */
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
            $response = file_get_contents($$url);
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
    function tasksList($status = GetbotApi::STATUS_ANY, $mode = GetbotApi::MODE_ANY) {
        return $this->get("tasksList", array(
            "status" => $status,
            "mode" => $mode,
        ));
    }


    function taskCreate($name, $urls, $mode = GetbotApi::MODE_EXPRESS_LIGHT, $description = '') {
        return $this->post("taskCreate", array(
            "name" => $name,
            "urls" => $urls,
            "mode" => $mode,
            "description" => $description
        ));
    }

    function taskDetails($taskId) {
        return $this->get("taskDetails", array(
            "id" => $taskId));
    }

    function taskLaunch($taskId) {
        return $this->get("taskLaunch", array(
            "id" => $taskId));
    }

    function userBalance() {
        return $this->get("balance");
    }

    function queueList() {
        return $this->get("queueList");
    }
}