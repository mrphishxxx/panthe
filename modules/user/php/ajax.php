<?php

$login = $_REQUEST['login'];
$pass = $_REQUEST['pass'];
$bid = $_REQUEST['bid'];
if (!empty($login) && !empty($pass) && !empty($bid)) {
    $cookie_jar = tempnam(PATH . 'temp', "cookie");
    $data = array('e_mail' => $login,
        'password' => $pass,
        'remember' => "");
    
    $url_login = $auth = null;
    $header = array();
    switch ($bid) {
        case 1: $url_login = 'https://gogetlinks.net/login.php';
            break;
        case 2: $url_login = 'http://getgoodlinks.net/login.php';
            break;
        case 3: /* ROTAPOST */
            echo 'TRUE';
            exit();
            //$url_login = 'https://api.rotapost.ru/Login/Auth?login='. $login . '&authToken='.(md5($login . $pass));
            break;
        case 4: /* PR.SAPE */
            $url_login = "http://api.pr.sape.ru/xmlrpc/";
            $data = xmlrpc_encode_request('sape_pr.login', array($login, $pass));
            $header[] = "Content-type: text/xml";
            $header[] = "Content-length: " . strlen($data);
            break;
        case 5: /* MIRALINKS */
            /* unset($data["remember"]);
              unset($data["e_mail"]);
              unset($data["password"]);
              $url_login = 'http://www.miralinks.ru/users/login';
              $data['_method'] = "POST";
              $data['data[User][login]'] = $login;
              $data['data[User][password]'] = $pass;
              $data['data[User][remember]'] = 0; */
            echo 'TRUE';
            exit();
            break;
        case 6: /* WEBARTEX */
            $url_login = "https://api.webartex.ru/api/webmaster/sites/list";
            unset($data["e_mail"]);
            $data['email'] = $login;
            break;
        case 7: /* RODINALINKON */
            $url_login = 'http://www.rodinalinkov.ru/login/';
            $url_login = '';
            unset($data["e_mail"]);
            unset($data["remember"]);
            $data['username'] = $login;
            $data['csrfmiddlewaretoken'] = "HlbRp2MuXqjD1nEgssLWwavjRM3UMUNe";
            break;
        case 8: /* BLOGUN */
            $url_login = 'https://blogun.ru/login.php';
            unset($data["e_mail"]);
            unset($data["remember"]);
            $data['login'] = $login;
            $data['cpt'] = "";
            break;
    }
    //print_r($url_login);

        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $url_login);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
            @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            if ($bid == 4){
                curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            }
            if($bid == 3) {
                curl_setopt($curl, CURLOPT_USERAGENT, 'RotapostClient/1.0.0.0 (PHP)');
            } elseif($bid != 6) {
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            } else {
                curl_setopt($curl, CURLOPT_USERPWD, $login . ":" . $pass);
            }
            
            $out_auth = curl_exec($curl);
            curl_close($curl);
        }

        if ($bid == 3) { // Special for ROTAPOST
            $auth = simplexml_load_string($out_auth);
            if((isset($auth->Success) && $auth->Success == "false")){
                //echo $auth->Error->Description;
                echo 'FALSE';
                exit();
            }
        }


    if ($bid == 1 || $bid == 2) {
        $out_auth = iconv("windows-1251", "utf-8", $out_auth);
    }
    if ($bid == 6) {
        $out = json_decode($out_auth);
        $out_auth = $out->status;
    }

    /*if($_SERVER["REMOTE_ADDR"] == "128.69.89.113") {
        print_r($out_auth);die();
    }*/
    $auth = true;
    $auth_error = array();
    $auth_error[] = "Некорректный email или пароль."; //ggl
    $auth_error[] = "Некорректный Логин или Пароль"; //ggl
    $auth_error[] = "Invalid e-mail or password";
    $auth_error[] = "Неверный Email или пароль. Попробуйте снова.";
    $auth_error[] = "Вход не удался"; //mira
    $auth_error[] = "Неверно введен проверочный код"; //mira
    $auth_error[] = "Вход по e-mail временно недоступен. Используйте для входа в систему ваш логин"; //mira
    $auth_error[] = "JavaScript is turned off!"; //getgoodlinks
    $auth_error[] = "Login failed!"; // sape
    $auth_error[] = "Unknown error"; // sape
    $auth_error[] = "Неверный пользователь или пароль"; // webartex
    $auth_error[] = "fail"; // webartex
    $auth_error[] = "Ошибка авторизации"; // webartex
    $auth_error[] = "Пожалуйста, введите верные имя пользователя и пароль."; // rodina linkov
    $auth_error[] = "Неверный логин или пароль."; // blogun
    $auth_error[] = "Не удалось выполнить вход"; // rotapost

    foreach ($auth_error as $value) {
        if (stristr($out_auth, $value)) {
            $auth = false;
        }
        if ($out_auth == $value) {
            $auth = false;
        }
    }

    if (!$auth) {
        echo 'FALSE';
        exit();
    }


    echo $out_auth;
} else {
    echo "NO";
}
?>
