<?php

include_once PATH . '/includes/selenium/lib/__init__.php';

class GetGoodLinks {

    private $_userId;
    private $_login;
    private $_password;
    private $_results = array();
    private $driver = NULL;
    private $host = 'http://127.0.0.1:4444/wd/hub';
    private $capabilities = array();
    private $url_system = 'http://getgoodlinks.ru/';
    private $proxy = array();
    private $log = "";

    public function __construct($id, $login, $password, $proxies = array()) {
        $this->_userId = $id;
        $this->_login = $login;
        $this->_password = $password;

        $this->capabilities = array(WebDriverCapabilityType::BROWSER_NAME => "firefox");

        if (!empty($proxies)) {
            $this->proxy = $proxies[rand(0, count($proxies) - 1)];
        }
        if (!empty($this->proxy)) {
            $proxy_capabilities = array(WebDriverCapabilityType::PROXY =>
                array('proxyType' => 'manual',
                    'httpProxy' => '' . $this->proxy['proxy_host'] . ':' . $this->proxy['proxy_port'] . '',
                    'sslProxy' => '' . $this->proxy['proxy_host'] . ':' . $this->proxy['proxy_port'] . '',
                    'socksUsername' => '' . $this->proxy['proxy_user'] . '',
                    'socksPassword' => '' . $this->proxy['proxy_pass'] . ''
                )
            );

            array_push($this->capabilities, $proxy_capabilities);
        }

        $this->log .= "->-> GGL constructor create - done" . PHP_EOL;
    }

    public function createSeleniumWebDriver() {
        try {
            $this->driver = RemoteWebDriver::create($this->host, $this->capabilities, 300000);
            $this->driver->manage()->window()->maximize();
            $this->driver->manage()->timeouts()->implicitlyWait(20);
            $this->driver->manage()->deleteAllCookies();
            $this->log .= "->-> SeleniumWebDriver create - done" . PHP_EOL;
            return true;
        } catch (Exception $e) {
            $this->driver = false;
            return $e->getMessage();
        }
    }

    public function destroySeleniumWebDriver() {
        if (!empty($this->driver)) {
            $this->driver->quit();
            $this->log .= "->-> SeleniumWebDriver destroy - done" . PHP_EOL;
        }
    }

    private function getPage($link) {
        try {
            $this->log .= "\t->-> GoTo on '$link' - done" . PHP_EOL;
            return $this->driver->get($link);
        } catch (Exception $e) {
            $this->destroySeleniumWebDriver();
            return "ERROR: " . $e->getMessage();
        }
    }

    private function driverWait($wait = NULL) {
        if (!empty($wait)) {
            $this->driver->wait($wait);
        }
    }
    
    public function getLog() {
        return $this->log;
    }
    
    public function getResult() {
        return $this->_results;
    }

    public function getTasks($sites = array(), $task_exists = array(), $task_vipolneno = array()) {

        $loginpage = $this->getPage($this->url_system);
        if (is_string($loginpage)) {
            return PHP_EOL . "ERROR: Не возможно зайти на сайт!" . PHP_EOL . $this->log . PHP_EOL;
        }

        $logins = $this->loginInSystem();
        if (is_string($logins)) {
            return PHP_EOL . $logins . PHP_EOL . $this->log . PHP_EOL;
        }

        foreach ($sites as $site) {
            $this->log .= "\t->-> Site ID - " . $site["id"] . PHP_EOL;
            $this->getPage($this->url_system . "web_task.php?in_site_id=" . $site["getgoodlinks_id"]);

            // Проверяем есть ли новые задачи для этого сайта
            $find_new_task = $this->checkThereNewTasks($this->driver->findElements(WebDriverBy::xpath("//div[@class='top_menu_class2_selected']/a")));

            $this->log .= "\t->-> Поиск новых задач - done." . PHP_EOL;
            if ($find_new_task !== TRUE && $find_new_task !== FALSE) {
                return PHP_EOL . $find_new_task . PHP_EOL . $this->log . PHP_EOL;
            } else if ($find_new_task !== TRUE) {
                $this->log .= "\t->-> Новых задач не найдено" . PHP_EOL;
                continue;
            }

            $this->parseTaskForSite($site["id"]);
        }
        


        return TRUE;
    }

    private function parseTaskForSite($site_id) {
        // Обрабатываем задачи
        $new_task = $this->driver->findElements(WebDriverBy::xpath("//tr[starts-with(@class, 'table_content_rows')]"));
        $this->log .= "\t->-> finded " . count($new_task) . " tasks" . PHP_EOL;

        foreach ($new_task as $task) {
            $id = (int) mb_substr($task->getAttribute('id'), 8);
            $this->createNewTasksInResults($id, $site_id);
            
            $this->getMainInfoTask($id, $task);
        }

        foreach ($this->_results as $id => $task) {
            if (!empty($task["href"])) {
                $this->getPage($task["href"]);
            }
            
            $this->getAllInfoTask($id);
            $this->log .= "\t->-> added all information in this task" . PHP_EOL;
        }
    }
    
    private function createNewTasksInResults($id, $site_id = NULL) {
        $this->_results[$id] = array(
            "sid" => $site_id,
            "href" => "",
            "b_price" => "",
            "date" => "",
            "type" => "",
            "tema" => "",
            "url" => array(),
            "ankor" => array(),
            "keywords" => "",
            "alt" => "",
            "title" => "",
            "text" => ""
        );
        
    }

    private function getMainInfoTask($id, $task) {
        $price = 0;
        $href = "";
        foreach ($task->findElements(WebDriverBy::xpath(".//td")) as $column_index => $td) {
            if ($column_index == 3) {
                $hs = $td->findElements(WebDriverBy::xpath(".//a[@class='tipa_url_getgood']"));
                $href = isset($hs[0]) ? $hs[0]->getAttribute('href') : "";
            }
            if ($column_index == 5) {
                $price = $td->getText();
            }
        }
        $this->_results[$id]["b_price"] = $price;
        $this->_results[$id]["href"] = $href;

        return null;
    }

    private function getAllInfoTask($id) {
        $this->_results[$id]["date"] = time();
        
        $tables = $this->driver->findElements(WebDriverBy::xpath("//div[@class='params']/div[contains(@class, 'param')]"));
        foreach ($tables as $block) {
            $name = $value = NULL;
            $block_name = $block->findElements(WebDriverBy::xpath(".//div[@class='block_name']"));
            $block_value = $block->findElements(WebDriverBy::xpath(".//div[@class='block_value']"));
            if (isset($block_name[0]) && $block_value[0]) {
                $name = $block_name[0]->getText();
                $value = $block_value[0]->getText();
            }
            if (empty($name)) {
                continue;
            }
            switch ($name) {
                case "Тип обзора:":
                    $this->_results[$id]["type"] = $value;
                    break;
                case "Адрес, куда ссылаться":
                    $this->_results[$id]["url"][] = $value;
                    break;
                case "Текст ссылки (анкор)":
                    $this->_results[$id]["ankor"][] = $value;
                    break;

                case "Ключевые слова":
                    $this->_results[$id]["keywords"] = $value;
                    break;
                case "Атрибут alt":
                    $this->_results[$id]["alt"] = $value;
                    break;
                case "Атрибут title":
                    $this->_results[$id]["title"] = $value;
                    break;
            }
        }
        $task_text = $this->driver->findElements(WebDriverBy::xpath("//div[@class='params']/div[@id='layer']"));
        if (isset($task_text[0])) {
            $this->_results[$id]["text"] = $task_text[0]->getText();
        }

        if (!empty($this->_results[$id]["ankor"][0])) {
            $first1 = mb_strtoupper(mb_substr($this->_results[$id]["ankor"][0], 0, 1, 'UTF-8'), 'UTF-8'); //первая буква
            $first = str_replace("?", "", $first1);
            $last1 = mb_strtolower(mb_substr($this->_results[$id]["ankor"][0], 1), 'UTF-8'); //все кроме первой буквы
            $last = ($last1[0] == "?") ? mb_substr($last1, 1) : $last1;
            $this->_results[$id]["tema"] = mysql_real_escape_string($first . $last);

            $cut_end = mb_strpos($this->_results[$id]["tema"], "(", 0, "UTF-8");
            $this->_results[$id]["tema"] = trim(mb_substr($this->_results[$id]["tema"], 0, $cut_end, 'UTF-8'));
        } else if (!empty($this->_results[$id]["alt"])) {
            $this->_results[$id]["ankor"][0] = $this->_results[$id]["alt"];
        }

        if ($this->_results[$id]["type"] == "Ссылка-картинка") {
            $this->_results[$id]["ankor"][0] .= " (!ссылка-картинка!)";
        }

        if (empty($this->_results[$id]["keywords"]) && !empty($this->_results[$id]["title"])) {
            $this->_results[$id]["keywords"] = $this->_results[$id]["title"];
        }
    }

    private function loginInSystem() {
        if (!empty($this->_login) || !empty($this->_password)) {
            // находим input для логина и вставляем туда значение _login
            $logins = $this->driver->findElements(WebDriverBy::xpath("//input[@name='e_mail']"));
            if (count($logins) == 0) {
                $this->destroySeleniumWebDriver();
                return "ERROR: Поле Login - не найдено!";
            }
            $logins[0]->sendKeys($this->_login);

            // находим input для пароль и вставляем туда значение _password
            $pass = $this->driver->findElements(WebDriverBy::xpath("//input[@name='password']"));
            if (count($pass) == 0) {
                $this->destroySeleniumWebDriver();
                return "ERROR: Поле Password - не найдено!";
            }
            $pass[0]->sendKeys($this->_password);

            // находим кнопку "Войти" и нажимаем на неё
            $btns = $this->driver->findElements(WebDriverBy::xpath("//input[@type='submit']"));
            if (count($btns) == 0) {
                $this->destroySeleniumWebDriver();
                return "ERROR: Кнопка Войти -  не найдено!";
            }
            $btns[0]->click();
        } else {
            return "ERROR: Поле Login или Password - пустое! (login: " . $this->_login . "; password: " . $this->_password . ")";
        }

        if (count($this->driver->findElements(WebDriverBy::xpath("//a[@class='top_menu_label2']/img"))) === 0) {
            $error = $this->driver->findElements(WebDriverBy::xpath("//div[@id='error_message']"));
            return "ERROR: Ошибка входа!" . $error[0]->getText() . PHP_EOL;
        } else {
            $this->log .= "->-> Logining - done" . PHP_EOL;
            return null;
        }
    }

    private function checkThereNewTasks($objects = array()) {
        if (count($objects) > 0) {
            foreach ($objects as $object) {
                if (strripos($object->getText(), "Новые") !== FALSE) {
                    return TRUE;
                }
            }
        } else {
            return "Ошибка поиска новых задач в таблице. Нет ни одной выделенной ячейки таблицы!";
        }
        return FALSE;
    }

}
