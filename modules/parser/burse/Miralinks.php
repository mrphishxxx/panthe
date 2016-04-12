<?php

include_once PATH . '/includes/selenium/lib/__init__.php';

class Miralinks {

    private $_userId;
    private $_login;
    private $_password;
    private $_results = array(0 => array(), 1 => array());
    private $_errors = array();
    private $driver = NULL;
    private $host = 'http://127.0.0.1:4444/wd/hub';
    private $capabilities = array();
    private $url_system = 'http://miralinks.ru/';
    private $proxy = array();
    private $list_sites = array();
    private $task_exists = array();
    private $task_vipolneno = array();
    private $log = "";
    private $count_task_on_page = 15;

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
        $this->log .= "->-> MIRA constructor create - done" . PHP_EOL;
    }

    /*
     * 
     * 
     *  Создание Веб-драйвера (Firefox)
     *  Возвращает:
     *      true - в случае удачи (Bool)
     *      в противном случае - ошибку (String)
     */

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

    /*
     * 
     * 
     *  Уничтожение Веб-драйвера
     */

    public function destroySeleniumWebDriver() {
        if (!empty($this->driver)) {
            $this->driver->quit();
            $this->log .= "-> SeleniumWebDriver destroy - done" . PHP_EOL;
            return "-> SeleniumWebDriver destroy - done" . PHP_EOL;
        }
        return "";
    }

    /*
     * 
     * 
     *  Функция для получения лога извне
     *  Возвращает:
     *      $log (String)
     */

    public function getLog() {
        return $this->log;
    }

    /*
     * 
     * 
     *  Функция для получения извне, массива с простыми ошибками
     *  , которые не вызывали серьёзных проблем 
     *  Возвращает:
     *      $_errors (Array)
     */

    public function getErrors() {
        return $this->_errors;
    }

    /*
     *  Добавление ошибки в список ошибок
     *  Возвращает полученную ошибку, для добавления в лог
     */

    public function setErrors($error) {
        $this->_errors[] = $error;
        return $error;
    }

    /*
     * 
     * 
     *  Функция для получения извне, массива с выгруженными задачами 
     *  Возвращает:
     *      $_results (Array)
     */

    public function getResult() {
        return $this->_results;
    }

    /*
     * 
     * 
     *  Главная функция!
     *  Запускает выгрузку новых задач
     *  Включает в себя: 
     *      залогинивание, 
     *      получение задач вида "Размещение", 
     *      получение задач вида "Написание и размещение"
     *  Возвращает:
     *      true - в случае удачной работы
     *      в противном случае - ошибку (String)
     */

    public function getTasks($sites = array(), $task_exists = array(), $task_vipolneno = array()) {
        $this->task_exists = $task_exists;
        $this->task_vipolneno = $task_vipolneno;

        $sites_ids = array();
        foreach ($sites as $site) {
            $sites_ids[$site["id"]] = $site["miralinks_id"];
        }

        $loginpage = $this->getPage($this->url_system . "users/login");
        if (is_string($loginpage)) {
            return PHP_EOL . "ERROR (getTasks): Unable to enter the site!" . PHP_EOL . $this->log . PHP_EOL;
        }

        $logins = $this->loginInSystem();
        if (is_string($logins)) {
            return PHP_EOL . $logins . PHP_EOL . $this->log . PHP_EOL;
        }
        $this->pause(false);

        //ПЕРВЫЙ ТИП(РАЗМЕЩЕНИЕ)
        $this->getGroundChoosed($sites_ids);
        $this->getSitesPrice();
        if (!empty($this->_results[1])) {
            foreach ($this->_results[1] as $id => $task) {
                if ($task["b_price"] == 0 && $task["lay_out"] == 1 && isset($this->list_sites[$task["site_id"]])) {
                    $this->_results[1][$id]["b_price"] = isset($this->list_sites[$task["site_id"]]["place_price"]) ? $this->list_sites[$task["site_id"]]["place_price"] : 0;
                }
            }
        }

        // ВТОРОЙ ТИП РАЗМЕЩЕНИЕ И НАПИСАНИЕ
        $this->getAccepted($sites_ids);
        $this->pause(false, false);

        $this->logoutInSystem();

        return TRUE;
    }

    /*
     * 
     * 
     *  Функция для получения задач вида "НАПИСАНИЕ И РАЗМЕЩЕНИЕ" (lay_out = 0)
     */

    private function getAccepted($sites_ids) {
        $this->getDividingLine();
        $this->log .= "\t-> *** GET TASKS LAY-OUT = 0 ***" . PHP_EOL;
        $this->getPage($this->url_system . "ground_articles/allCmArticlesList/achtung#/start:0/clearFilters:1");
        $this->pause(true);
        $total_found_count = $this->checkThereNewTasks();
        if ($total_found_count == 0) {
            $this->log .= "\t->-> New tasks not found" . PHP_EOL;
        } else {
            $i = 1;
            do {
                $this->log .= PHP_EOL . "\t->-> Page #" . $i . PHP_EOL;
                $this->parseTaskOnPage($sites_ids, 0);
                $i++;
            } while ($i <= ceil($total_found_count / $this->count_task_on_page));
        }
    }

    /*
     * 
     * 
     *  Функция для получения задач вида "РАЗМЕЩЕНИЕ" (lay_out = 1)
     */

    private function getGroundChoosed($sites_ids) {
        $this->getDividingLine();
        $this->log .= "\t-> *** GET TASKS LAY-OUT = 1 ***" . PHP_EOL;
        $type = 1;
        $this->getPage($this->url_system . "ground_articles/allArticlesList/achtung#/start:0/clearFilters:1");
        $this->pause(true);

        $total_found_count = $this->checkThereNewTasks();
        if ($total_found_count == 0) {
            $this->log .= "\t->-> New tasks not found" . PHP_EOL;
        } else {
            $i = 1;
            do {
                $this->log .= PHP_EOL . "\t->-> Page #" . $i . PHP_EOL;
                $this->parseTaskOnPage($sites_ids, $type);
                $i++;
            } while ($i <= ceil($total_found_count / $this->count_task_on_page));

            $this->log .= "\t-> *** START GET ALL INFORMATION ***" . PHP_EOL;
            foreach ($this->_results[$type] as $id => $task) {
                if (!empty($task["href"])) {
                    $this->getPage($task["href"]);
                    $this->pause();
                } else {
                    continue;
                }
                $this->log .= "\t->-> Task " . $id . PHP_EOL;
                $this->getAllInfoTaskForLayout($id, $type);
                $this->log .= "\t->-> get all information - done" . PHP_EOL . PHP_EOL;
            }
        }
    }

    /*
     * 
     * 
     *  Функция нахождения всех новых задач в таблице
     *  Применяется для обоих видов задач
     */

    private function parseTaskOnPage($sites_ids, $type) {
        // Обрабатываем задачи
        $rows = $this->driver->findElements(WebDriverBy::xpath("//table[starts-with(@class, 'content-table')]/tbody/tr"));
        foreach ($rows as $task) {
            $this->getMainInfoTask($task, $sites_ids, $type);
        }
        $this->log .= PHP_EOL . "\t->-> Added " . count($this->_results[$type]) . " tasks in array" . PHP_EOL . PHP_EOL;
    }

    /*
     * 
     * 
     *  Функция получения основной информации из задач обоих типов
     *  Получение:
     *      ID (int)
     *      STATUS (String)
     *      SITE_ID (int)
     *      URL (String) - только для "Размещение"
     *      PRICE (float) - только для "Написание и размещение"
     *  Для задач вида "Написание и размещение", запускается выгрузка полной информации
     *  Возвращает:
     *      true - в случае удачной работы
     *      false - в противном случае
     */

    private function getMainInfoTask($task, $sites_ids, $type = 0) {
        $price = 0;
        $href_site = $site_id = $href = $status = "";
        $status_class = ($type == 0) ? "cm_accepted" : "ground_choosed";


        $tds = $task->findElements(WebDriverBy::xpath(".//td[@class='lPos']"));
        if (count($tds) != 0 && isset($tds[2]) && isset($tds[3])) {
            //Получаем ID заявки
            $id = (int) $this->getTextFromObject($tds[0]);
            $this->log .= "\t->-> Get ID task = " . $id . PHP_EOL;

            if (in_array($id, $this->task_vipolneno)) {
                $this->log .= $this->setErrors("\t\t->-> ERROR (getMainInfoTask): Task (" . array_search($id, $this->task_vipolneno) . ") already exist, in the status of 'Vipolneno'!" . PHP_EOL);
                return false;
            }
            if (in_array($id, $this->task_exists)) {
                $this->log .= "\t\t->-> Task already exist! Continue..." . PHP_EOL;
                return false;
            }

            //Получаем статус
            $td_status = $tds[3]->findElements(WebDriverBy::xpath(".//span[contains(@class, '" . $status_class . "')]"));
            if (isset($td_status[0])) {
                $status = $this->getTextFromObject($td_status[0]);
                $this->log .= "\t\t->-> Get status - done" . PHP_EOL;
            } else {
                $this->log .= "\t\t->-> Status not $status_class! Continue..." . PHP_EOL;
                return false;
            }

            // Получаем ссылку на сайт, чтобы потом вытащить от туда цены на заявки
            $href_site = $this->getAttributeFromObject($tds[2]->findElement(WebDriverBy::xpath(".//p[starts-with(@class, 'popover-holder')]/a")), "href");
            if (!empty($href_site)) {
                $link_array = explode("/", $href_site);
                $site_id = (int) array_pop($link_array);
                $this->log .= "\t\t->-> Get site_id - done" . PHP_EOL;
            } else {
                $this->log .= $this->setErrors("\t\t->-> ERROR (getMainInfoTask): Not added task!!! Not site url! " . PHP_EOL);
                return false;
            }
        }

        if (!empty($site_id) && in_array($site_id, $sites_ids)) {
            $this->createNewTasksInResults($id, $site_id, array_search($site_id, $sites_ids), $type);
            $this->_results[$type][$id]["href_site"] = $href_site;
            $this->_results[$type][$id]["date"] = time();
        } else {
            if(empty($site_id)) {
                $this->log .= $this->setErrors("\t\t->-> ERROR (getMainInfoTask): Not create task! Not site ID!" . PHP_EOL);
            } else {
                $this->log .= "\t\t->-> ERROR (getMainInfoTask): Not create task! Site is absent in iForget!" . PHP_EOL;
            }
            return false;
        }

        // Для TYPE == Размещение
        if ($type == 1) {
            $href = $this->url_system . "project_articles/view/" . $id;

            if (!isset($this->list_sites[$site_id])) {
                $this->list_sites[$site_id] = array("href" => $href_site);
            }
        } else if ($type == 0 && isset($tds[1])) {
            //Получаем цену заявки
            if (isset($this->list_sites[$site_id]["order_price"]) && $this->list_sites[$site_id]["order_price"] != 0) {
                $price = $this->list_sites[$site_id]["order_price"];
            } else {
                $td_price = $task->findElements(WebDriverBy::xpath(".//td[@class='rPos']//span[@class='WMR']"));
                if(isset($td_price[0]) && !empty($td_price[0])){
                    $price =  $this->getTextFromObject($td_price[0]);
                } else {
                    $td_price = $task->findElements(WebDriverBy::xpath(".//td[@class='rPos']//span[@class='WMZ']"));
                    $price = (isset($td_price[0]) && !empty($td_price[0])) ? $this->getTextFromObject($td_price[0]) : 0;
                }                
            }
            $this->getAllInfoTaskForNotLayout($tds[1], $id, $type);
        }

        $this->_results[$type][$id]["b_price"] = $price;
        $this->_results[$type][$id]["href"] = $href;
        return true;
    }

    /*
     * 
     * 
     *  Функция для получения полной информации
     *  только для задач "Размещение"
     *  Получение:
     *      keywords (String)
     *      tema (String)
     *      title (String)
     *      description (Text)
     *      url_statyi (String)
     *      nof_chars (Int)
     *      text (Text)
     *      ankor (Array)
     *      url (Array)
     */

    private function getAllInfoTaskForLayout($id, $type) {
        $tables = $this->driver->findElements(WebDriverBy::xpath("//div[@class='head-holder']//div[@class='row-holder']"));
        foreach ($tables as $key => $block) {
            $name = $value = NULL;
            $block_name = $block->findElements(WebDriverBy::xpath(".//div[@class='cornered-label']//span"));
            $block_value = $block->findElements(WebDriverBy::xpath(".//div[contains(@class, 'copied-text')]"));            
            if (isset($block_name[0]) && isset($block_value[0])) {
                $name = $this->getTextFromObject($block_name[0]);
                $value = $this->getTextFromObject($block_value[0]);
            } else {
                continue;
            }
            switch ($name) {
                case "Ключевые слова":
                    $this->_results[$type][$id]["keywords"] = $value;
                    break;
                case "Название статьи":
                    $this->_results[$type][$id]["tema"] = $value;
                    break;
                case "TITLE":
                    $this->_results[$type][$id]["title"] = $value;
                    break;
                case "Description":
                    $this->_results[$type][$id]["description"] = $value;
                    break;
                case "Анонс":
                    $this->_results[$type][$id]["description"] = (!empty($this->_results[$type][$id]["description"]) ? $this->_results[$type][$id]["description"] : $value);
                    break;
                case "Адрес url":
                    $this->_results[$type][$id]["url_statyi"] = $value;
                    break;
                case "Количество символов в статье":
                    $this->_results[$type][$id]["nof_chars"] = $value;
                    break;
            }
        }

        $task_text = $this->driver->findElement(WebDriverBy::xpath("//blockquote[@id='aticle-plain']"));
        if (isset($task_text)) {
            $this->_results[$type][$id]["text"] = $this->getAttributeFromObject($task_text, "innerHTML");
        } else {
            $this->log .= $this->setErrors("\t\t->-> ERROR (getAllInfoTaskForLayout): not get TEXT!!" . PHP_EOL);
        }

        $tables_links = $this->driver->findElements(WebDriverBy::xpath("//div[contains(@class, 'widget-pageComponent-ArticleLinksTable')]//div[@class='row-fluid']//div[@class='span8']//table[contains(@class, 'table-grid')]//tbody//tr"));
        foreach ($tables_links as $row) {
            $tds = $row->findElements(WebDriverBy::xpath(".//td"));
            if (isset($tds[1]) && isset($tds[2])) {
                $url_link = $tds[2]->findElement(WebDriverBy::xpath(".//a"));

                $this->_results[$type][$id]["ankor"][] = $this->getTextFromObject($tds[1]);
                $this->_results[$type][$id]["url"][] = (isset($url_link) ? $this->getAttributeFromObject($url_link, "href") : "");
            } else {
                $this->log .= $this->setErrors("\t\t->-> ERROR (getAllInfoTaskForLayout): ankor OR url" . PHP_EOL);
            }
        }

        if (empty($this->_results[$type][$id]["keywords"]) && !empty($this->_results[$type][$id]["title"])) {
            $this->_results[$type][$id]["keywords"] = $this->_results[$type][$id]["title"];
        }
        
        if (empty($this->_results[$type][$id]["tema"]) && !empty($this->_results[$type][$id]["title"])) {
            $this->_results[$type][$id]["tema"] = $this->_results[$type][$id]["title"];
        }
    }

    /*
     * 
     * 
     *  Функция для получения полной информации
     *  только для задач "Написание и размещение"
     *  Получение:
     *      ankor (Array)
     *      url (Array)
     *      comments (Text)
     *      text (Text)
     */

    private function getAllInfoTaskForNotLayout($click, $id, $type) {
        $cols_request = $cols_link = array();
        $urls = $ankors = array();
        $comment_block = null;
        $comment = $tema = "";

        //**Получаем данные из модального окна
        try {
            $click->findElement(WebDriverBy::xpath(".//span[@class='popover-holder']//a[@data-action='open']"))->click();
            $this->pause(false, false);
            $this->driverWait(5);
            sleep(5);
        } catch (Exception $e) {
            $this->log .= $this->setErrors("\t\t->-> ERROR (getAllInfoTaskForNotLayout): Span is not clicked!" . PHP_EOL);
        }
        $rows = $this->driver->findElements(WebDriverBy::xpath("//div[contains(@class, 'widget-modal-cm-RequestInfo')]//div[@class='modal-content']//div[@class='row']"));
        if (count($rows) > 0 && isset($rows[0]) && isset($rows[1])) {
            //анкоры
            $cols_request = $rows[0]->findElements(WebDriverBy::xpath(".//table//td[@class='col-request']"));
            //ссылки
            $cols_link = $rows[0]->findElements(WebDriverBy::xpath(".//table//td[@class='col-link']"));
            //комментарий
            $comment_block = $rows[1]->findElement(WebDriverBy::xpath(".//div[contains(@class, 'scroll-text-content')]"));
            $this->log .= "\t\t->-> Get array (ankor=>url) - done" . PHP_EOL;
        } else {
            $this->log .= $this->setErrors("\t\t->-> ERROR (getAllInfoTaskForNotLayout): not table with ankor=>url OR block with Comment" . PHP_EOL);
            return NULL;
        }
        //** получили
        //Получаем массив анкоров и ссылок
        if (count($cols_request) > 0 && count($cols_link) > 0 && count($cols_request) == count($cols_link)) {
            foreach ($cols_request as $key => $td_ankor) {
                $url = $this->getAttributeFromObject($cols_link[$key]->findElement(WebDriverBy::xpath(".//a")), "href");
                $ankor = $this->getTextFromObject($td_ankor);

                $urls[] = $url;
                $ankors[] = $ankor;
                $this->log .= "\t\t->-> Get ankor => url - done" . PHP_EOL;
            }
        } else {
            $this->log .= $this->setErrors("\t\t->-> ERROR (getAllInfoTaskForNotLayout): " . (count($cols_request) == count($cols_link) ? "COUNT ankors != COUNT urls!" : "ankors OR urls is empty") . PHP_EOL);
        }

        //Получаем комментарий заявки
        if (!empty($comment_block)) {
            $comment = $this->getTextFromObject($comment_block);
            $this->log .= "\t\t->-> Get Comment - done" . PHP_EOL;
        } else {
            $this->log .= $this->setErrors("\t\t->-> ERROR (getAllInfoTaskForNotLayout): Comment = NULL! " . PHP_EOL);
        }

        //Закрываем модальное окно
        try {
            $this->pause(false, false);
            $this->driverWait(10);
            sleep(10);
            $this->driver->findElement(WebDriverBy::xpath("//div[contains(@class, 'widget-modal-cm-RequestInfo')]//div[@class='modal-head']//a[@class='close']"))->click();
        } catch (Exception $e) {
            $this->log .= $this->setErrors("\t\t->-> ERROR (getAllInfoTaskForNotLayout): Button is not clicked! Element visible error! " . PHP_EOL);
        }

        //Получаем тему из первого Анкора
        if (isset($ankors[0])) {
            $first1 = mb_strtoupper(mb_substr($ankors[0], 0, 1, 'UTF-8'), 'UTF-8'); //первая буква
            $first = str_replace("?", "", $first1);
            $last1 = mb_strtolower(mb_substr($ankors[0], 1), 'UTF-8'); //все кроме первой буквы
            $last = (isset($last1[0]) && $last1[0] == "?") ? mb_substr($last1, 1) : $last1;
            $tema = mysql_real_escape_string($first . $last);
        }

        if (!empty($comment)) {
            $this->_results[$type][$id]["comments"] = $comment;
        }
        if (!empty($urls)) {
            $this->_results[$type][$id]["url"] = $urls;
        }
        if (!empty($ankors)) {
            $this->_results[$type][$id]["ankor"] = $ankors;
        }
        if (!empty($tema)) {
            $this->_results[$type][$id]["tema"] = $tema;
        }
    }

    /*
     *  
     * 
     *  Получаем стоимость заявок для сайтов
     *  (тех, у которых есть новые заявки в разделе Размещение)
     */

    private function getSitesPrice() {
        if (empty($this->list_sites)) {
            return;
        }

        $this->log .= "\t-> *** Get sites price ***" . PHP_EOL;
        foreach ($this->list_sites as $id => $site) {
            $this->getPage($this->url_system . "catalog/profileView/" . $id);
            $this->pause(false);

            $order_article = $this->driver->findElement(WebDriverBy::xpath("//div[@id='widgetOrderArticle']/div[starts-with(@class, 'button')]/div[@class='strong-wmr']"));
            if (!empty($order_article)) {
                $price = $this->getTextFromObject($order_article);
                $price_array = explode(" ", $price);
                $this->list_sites[$id]["order_price"] = isset($price_array[0]) ? $price_array[0] : 0;
                $this->log .= "\t\t->-> Lay_out = 0: price = " . $this->list_sites[$id]["order_price"] . PHP_EOL;
            } else {
                $this->log .= $this->setErrors("\t\t->-> ERROR (getSitesPrice): Not price for Lay_out = 0" . PHP_EOL);
            }

            $place_article = $this->driver->findElement(WebDriverBy::xpath("//div[@id='widgetPlaceArticle']/div[starts-with(@class, 'button')]/div[@class='strong-wmr']"));
            if (!empty($place_article)) {
                $price = $this->getTextFromObject($place_article);
                $price_array = explode(" ", $price);
                $this->list_sites[$id]["place_price"] = isset($price_array[0]) ? $price_array[0] : 0;
                $this->log .= "\t\t->-> Lay_out = 1: price = " . $this->list_sites[$id]["place_price"] . PHP_EOL;
            } else {
                $this->log .= $this->setErrors("\t\t->-> ERROR (getSitesPrice): Not price for Lay_out = 1" . PHP_EOL);
            }
        }
        $this->log .= PHP_EOL;
    }

    /*
     *  
     * 
     *  Создание пустой задачи в массиве _results
     *  Часть полей автозаполнены, остальные заполнятся 
     *  при получении данных из других функций
     */

    private function createNewTasksInResults($id, $site_id = NULL, $sid = NULL, $type = 0) {
        $this->_results[$type][$id] = array(
            "site_id" => $site_id,
            "sid" => $sid,
            "href" => "",
            "href_site" => "",
            "b_price" => "",
            "date" => "",
            "type_task" => "0",
            "tema" => "",
            "url" => array(),
            "ankor" => array(),
            "url_statyi" => "",
            "keywords" => "",
            "description" => "",
            "alt" => "",
            "title" => "",
            "navyklad" => $type,
            "nof_chars" => "2000",
            "lay_out" => $type,
            "comments" => "",
            "text" => ""
        );
    }

    /*
     * 
     * 
     *  Функция для поиска в таблице, числа
     *  которое указывает есть ли новые задачи 
     *  Возвращает:
     *     total_found_count (Int) 
     */

    private function checkThereNewTasks() {
        $total_found_count = 0;
        $total_found_count_object = $this->driver->findElements(WebDriverBy::xpath("//div[@class='total-found-count']//a"));
        if (isset($total_found_count_object[0]) && !empty($total_found_count_object[0])) {
            $total_found_count = (int) $this->getTextFromObject($total_found_count_object[0]);
            $this->log .= "\t->-> Finded new tasks - " . $total_found_count . PHP_EOL;
        } else {
            $this->log .= $this->setErrors("\t\t->-> ERROR (checkThereNewTasks): Not total_found_count_object" . PHP_EOL);
        }
        return $total_found_count;
    }

    /*
     *  
     * 
     *  Функция для залогинивания на сайте Miralinks
     *  Возвращает:
     *     NULL - если удачно
     *     ошибку - если нет
     */

    private function loginInSystem() {
        if (!empty($this->_login) || !empty($this->_password)) {
            // находим input для логина и вставляем туда значение _login
            $logins = $this->driver->findElements(WebDriverBy::xpath("//input[@id='UserLogin']"));
            if (count($logins) == 0) {
                $this->destroySeleniumWebDriver();
                return "ERROR (loginInSystem): Field Login - not found!";
            }
            $logins[0]->sendKeys($this->_login);

            // находим input для пароль и вставляем туда значение _password
            $pass = $this->driver->findElements(WebDriverBy::xpath("//input[@id='UserPassword']"));
            if (count($pass) == 0) {
                $this->destroySeleniumWebDriver();
                return "ERROR (loginInSystem): Field Password - not found!";
            }
            $pass[0]->sendKeys($this->_password);

            // нажимаем на ENTER, чтобы сработала отправка формы
            $this->driver->getKeyboard()->pressKey(WebDriverKeys::ENTER);
            $this->pause();
        } else {
            return "ERROR (loginInSystem): Field Login or Password - empty! (login: " . $this->_login . "; password: " . $this->_password . ")";
        }

        if (count($this->driver->findElements(WebDriverBy::xpath("//div[@data-action='doLogout']"))) === 0) {
            return "ERROR (loginInSystem): Login failed!" . PHP_EOL;
        } else {
            $this->log .= "\t->-> Logining - done" . PHP_EOL;
            return null;
        }
    }

    /*
     *  
     * 
     *  Функция для выхода из Miralinks
     */

    private function logoutInSystem() {
        $confirm = NULL;
        $this->getDividingLine();
        $this->getPage($this->url_system);
        $this->pause(false, false);

        $button = $this->driver->findElement(WebDriverBy::xpath("//div[@data-action='doLogout']"));
        $this->driverWait(1);
        sleep(1);
        if (!empty($button) && $button->isDisplayed()) {
            try {
                $button->click();
                $this->pause(false, false);
                $this->driverWait(3);
                sleep(3);
                $confirm = $this->driver->findElement(WebDriverBy::xpath("//div[@type='modal.Confirm']//a[@data-action='confirm']"));
            } catch (Exception $e) {
                $this->log .= $this->setErrors("\t->-> ERROR (logoutInSystem): Failed click on Close-button! " . PHP_EOL . $e->getMessage() . PHP_EOL);
                return FALSE;
            }
        }
        if (!empty($confirm)) {
            $confirm->isDisplayed();
            try {
                $confirm->click();
                $this->log .= "\t->-> Logout - done" . PHP_EOL;
                $this->pause(false, false);
            } catch (Exception $e) {
                $this->log .= $this->setErrors("\t->-> ERROR (logoutInSystem): Failed click on Close-confirm-button! " . PHP_EOL . $e->getMessage() . PHP_EOL);
                return FALSE;
            }
        }
        return TRUE;
    }

    /*
     *  Функция для получения Текста внутри объекта Selenium
     */

    private function getTextFromObject($object = NULL) {
        try {
            if (!empty($object)) {
                return $object->getText();
            } else {
                $this->log .= $this->setErrors("\t->-> ERROR (getTextFromObject): Failed to getText()! An empty object!!!" . PHP_EOL);
                return NULL;
            }
        } catch (Exception $e) {
            $this->log .= $this->setErrors("\t->-> ERROR (getTextFromObject): Failed to getText()! " . PHP_EOL . $e->getMessage() . PHP_EOL);
            return NULL;
        }
    }

    /*
     *  Функция для получения Атрибута внутри объекта Selenium
     */

    private function getAttributeFromObject($object = NULL, $attr = NULL) {
        try {
            if (!empty($object) && !empty($attr)) {
                return $object->getAttribute($attr);
            } else {
                $this->log .= $this->setErrors("\t->-> ERROR (getAttributeFromObject): Failed to getAttribute()! An empty object or attribute" . PHP_EOL);
                return NULL;
            }
        } catch (Exception $e) {
            $this->log .= $this->setErrors("\t->-> ERROR (getAttributeFromObject): Failed to getAttribute()! " . PHP_EOL . $e->getMessage() . PHP_EOL);
            return NULL;
        }
    }

    /*
     *  Возвращает разделительную полосу в $log
     */

    private function getDividingLine() {
        $dividing = "";
        for ($i = 0; $i < 100; $i++) {
            $dividing .= "*";
        }
        $this->log .= PHP_EOL . "\t " . $dividing . PHP_EOL;
        return true;
    }

    /*
     *  Переход на страницу
     */

    private function getPage($link) {
        try {
            $this->log .= "\t->-> GoTo on '$link' - done" . PHP_EOL;
            return $this->driver->get($link);
        } catch (Exception $e) {
            $this->destroySeleniumWebDriver();
            return $this->setErrors("ERROR (getPage): " . $e->getMessage());
        }
    }

    /*
     *  Пауза для Веб-драйвера
     */

    private function driverWait($wait = NULL) {
        if (!empty($wait)) {
            $this->driver->wait($wait);
        }
    }

    /*
     *  Пауза для Миралинкса.
     *  нужно для того, чтобы убедиться, что страница полностью загружена
     */

    private function pause($space = false, $out = true) {
        $seconds = 0;
        while (true) {
            try {
                $ajaxIsComplete = $this->driver->executeScript("if(window.jQuery){return jQuery.active==0;}else{return 1;}");
            } catch (Exception $e) {
                $ajaxIsComplete = false;
            }
            if ($ajaxIsComplete) {
                break;
            }
            $seconds += 1;
            sleep(1);
        }
        if ($out) {
            $this->log .= "\t->-> pause " . $seconds . " sec." . PHP_EOL;
        }
        $this->log .= $space == true ? PHP_EOL : "";
    }

}
