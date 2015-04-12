<?php

error_reporting(E_ALL);
include(dirname(__FILE__).'/../'.'config.php');

if ($curl = curl_init()) {
    curl_setopt($curl, CURLOPT_URL, 'http://account.fineproxy.org/api/getproxy/?format=txt&type=httpauth&login=RUS79476&password=H987tURQLo');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    $rez = curl_exec($curl);
    curl_close($curl);
}
file_put_contents(PATH . 'modules/angry_curl/proxy_list.txt', trim($rez));
exit();
?>
