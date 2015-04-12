<?php
class MultiThreading
{
    /**
     * Имя сервера
     *
     * @var string
     * @access private
     */
    private $server;
    
    /**
     * Максимальное количество потоков
     *
     * @var int
     * @access private
     */
    private $maxthreads;
    
    /**
     * Имя скрипта, который выполняет нужную нам задачу
     *
     * @var string
     * @access private
     */
    private $scriptname;
    
    /**
     * Параметры, которые мы будем передавать скрипту
     *
     * @var array
     * @access private
     */
    private $params = array();
    
    /**
     * Массив, в котором хранятся потоки
     *
     * @var array
     * @access private
     */
    private $threads = array();
    
    /**
     * Массив, в котором хранятся результаты
     *
     * @var array
     * @access private
     */
    private $results = array();
    
    /**
     * Конструктор класса. В нем мы указываем максимальное количество потоков и имя сервера. Оба аргумента необязательны.
     *
     * @param int $maxthreads максимальное количество потоков, по умолчанию 10
     * @param string $server имя сервера, по умолчанию имя сервера, на котором запущено приложение
     * @access public
     */
    public function __construct($maxthreads = 10, $server = '')
    {
    	
        if ($server)
            $this->server = $server;
        else
            $this->server = $_SERVER['SERVER_NAME'];
        
        $this->maxthreads = $maxthreads;
        //echo $this->maxthreads;
    }
    
    /**
     * Указываем имя скрипта, который выполняет нужную нам задачу
     *
     * @param string $scriptname имя скрипта, включая путь к нему
     * @access public
     */
    public function setScriptName($scriptname)
    {	

        $this->scriptname = $scriptname;
    }
    
    /**
     * Задаем параметры, которые мы будем передавать скрипту
     *
     * @param array $params массив параметров
     * @access public
     */
    public function setParams($params = array())
    {
        $this->params = $params;
    }
    
    /**
     * Выполняем задачу, комментарии в коде
     *
     * @access public
     */
    public function execute()
    {
    	
        // Запускаем механизм, и он работает, пока не выполнятся все потоки
        do {
        	//echo count($this->maxthreads); exit;
        	$maxthreads = 10;
            // Если не превысили лимит потоков
            if (count($this->threads) < $maxthreads) {
                // Если удается получить следующий набор параметров
                
                if ($item = current($this->params)) {
                	
                    // Формируем запрос методом GET
                    
                    $query_string = '';
                
                    foreach ($item as $key=>$value)
                        $query_string .= '&'.urlencode($key).'='.urlencode($value);
                    
                    $query = "http://".$this->server."/".$this->scriptname."?".$query_string;
                    
                    // Открыватем соединение
                    
                    $res = file_get_contents($query);
                    
                    /*
                    if (!$fsock = fsockopen($this->server, 80))
                        throw new Exception('Cant open socket connection');
                
                    fputs($fsock, $query);
                    fputs($fsock, "Host: $server\r\n");
                    fputs($fsock, "\r\n");
                
                    stream_set_blocking($fsock, 0);
                    stream_set_timeout($fsock, 3600);
                    */
                    // Записываем поток
                
                    $this->threads[] = $query;
                    
                    // Переходим к следующему элементу
                
                    next($this->params);
                }
            }
            
            // Перебираем потоки
            foreach ($this->threads as $key=>$value) {
                // Если поток отработал, закрываем и удаляем
                if (feof($value)) {
                    fclose($value);
                    unset($this->threads[$key]);
                } else {
                    // Иначе считываем результаты
                    $this->results[] = fgets($value);
                }
            }
            
            // Можно поставить задержку, чтобы не повесить сервер
            sleep(1);
            
        // ... пока не выполнятся все потоки    
        } while (count($this->threads) > 0);
    
        return $this->results;
    }
}
?>