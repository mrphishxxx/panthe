<?php

$pass = ETXT_PASS;
$params = array ('method' => 'tasks.getResults', 'token' => '29aa0eec2c77dd6d06e23b3faaef9eed', 'id'=>'2966627');
ksort($params);
$data = array(); 
$data2 = array(); 
foreach ($params as $k => $v){
$data[] = $k.'='.$v; 
	$data2[] = $k.'='.urlencode($v); 
}
$sign = md5(implode('', $data).md5($pass.'api-pass'));
$url = 'https://www.etxt.ru/api/json/?'.implode('&', $data2).'&sign='.$sign;
	//echo $url . "<br>";
$out=file_get_contents($url);
$out=json_decode($out);
print_r($out);
exit();
?>