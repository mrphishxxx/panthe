<?php
require_once PATH . 'modules/error/ErrorController.php';
class Init {
    public $_db;
    public $_smarty;
    public $_front;
    
    public function __construct() {
        $this->_db = $this->_initDB();
        $this->_smarty = $this->_initSmarty();
        
        if (!isset($_SESSION)) {
            session_start();
            $_SESSION['requested_page'] = (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "/");
        }

        if (!isset($_SESSION["admin"]) || empty($_SESSION["admin"])) {
            $error = new ErrorController($this->_db, $this->_smarty);
            $error->accessErrorAction();
            return;
        }

        $this->run();
    }
    
    private function run() {
        $this->_initFront();
        $this->_loadFile(PATH . $this->_front->getFileController());
        $controller = $this->_front->getControllerName();
        $action = $this->_front->getActionName();
        if (!file_exists($this->_front->getFileController())) {
            $controller = 'ErrorController';
            $action = '_404';
        }
        $class = new $controller($this->_db, $this->_smarty);
        if (!method_exists($class, $action)) {
            $this->_redirect("/error/_404/");
            exit();
        }
        $class->$action();
    }
    
    protected function _redirect($url = "/") {
        header("Location: " . $url);
    }
    
    private function _initFront() {
        require_once PATH . 'modules/Front.php';
        if (empty($this->_front)) {
            $this->_front = new Front();
        }
        return $this->_front;
    }

    private function _initDB() {
        require_once PATH . 'includes/adodb5/adodb.inc.php';
        $this->_db = ADONewConnection(DB_TYPE);
        @$this->_db->Connect(DB_HOST, DB_USER, DB_PASS, DB_BASE) or die('Unable to connect to database');
        $this->_db->Execute('set charset utf8');
        return $this->_db;
    }
    
    private function _initSmarty() {
        require_once PATH . 'configs/setup-smarty.php';
        $this->_smarty = new Smarty_Project();
        $this->_smarty->template_dir = PATH . 'templates/';
        $this->_smarty->compile_dir = PATH . 'templates_c/';
        $this->_smarty->config_dir = PATH . 'configs/';
        $this->_smarty->cache_dir = PATH . 'cache/';

        $this->_smarty->caching = false;
        $this->_smarty->debugging = false;

        return $this->_smarty;
    }
    
    private function _loadFile($file) {
        $suffix = pathinfo($file, PATHINFO_EXTENSION);
        
        switch (strtolower($suffix)) {
            case 'php':
            case 'inc':
                if (file_exists($file)) {
                    $source = include $file;
                } else {
                    return null;
                }
                return $source;

            default:
                return null;
        }
        
        return null;
    }

    public function getRus($n, $forms) {
        if ($n > 0) {
            $n = abs($n) % 100;
            $n1 = $n % 10;
            if ($n > 10 && $n < 20)
                return $forms[2];
            if ($n1 > 1 && $n1 < 5)
                return $forms[1];
            if ($n1 == 1)
                return $forms[0];
        }
        return $forms[2];
    }

}

?>
