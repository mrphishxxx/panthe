<?php

class Front {

    /**
     * Directory where controller are stored
     *
     * @var string
     */
    protected $_controllerDir = 'modules';

    /**
     * Request
     * @var array
     */
    protected $_request = null;

    /**
     * Default action
     * @var string
     */
    protected $_defaultAction = 'index';

    /**
     * Default controller
     * @var string
     */
    protected $_defaultController = 'index';

    /**
     * Action
     * @var string
     */
    protected $_action;

    /**
     * Controller
     * @var string
     */
    protected $_controller;

    /**
     * Path delimiter character
     * @var string
     */
    protected $_pathDelimiter = '/';

    /**
     * Controller postfix
     * @var string
     */
    protected $_controllerPostfix = '.php';

    /**
     * Action postfix
     * @var string
     */
    protected $_actionPostfix = 'Action';

    /**
     * Front Controller instance
     * @var Zend_Controller_Front
     */
    protected $_frontController = 'front';

    public function __construct() {
        $this->route();
        $this->loadHelpers();
        $this->setRequest($_REQUEST);
    }
    
    private function route() {
        $action = $controller = null;
        $query_string = $_SERVER["QUERY_STRING"];
        $script_name = $_SERVER["SCRIPT_NAME"];
        if(!empty($script_name)){
            $controller = str_replace("/", "", str_replace(".php", "", $script_name));
        }
        if(!empty($query_string) && isset($_GET["action"])){
            $action = $_GET["action"];
        }
        $this->setController($controller);
        $this->setAction($action);
    }
    
    public function loadHelpers(){
        
    }

    public function setControllerDir($controller_dir = null) {
        $this->_controllerDir = $controller_dir;
    }

    public function getControllerDir() {
        return $this->_controllerDir;
    }

    public function setDefaultAction($action = null) {
        $this->_defaultAction = $action;
    }

    public function getDefaultAction() {
        return $this->_defaultAction;
    }

    public function setDefaultController($controller = null) {
        $this->_defaultController = $controller;
    }

    public function getDefaultController() {
        return $this->_defaultController;
    }

    public function setAction($action = null) {
        if (empty($action)) {
            $action = $this->getDefaultAction();
        }
        $this->_action = $action;
    }

    public function getAction() {
        return $this->_action;
    }

    public function setController($controller = null) {
        if (empty($controller)) {
            $controller = $this->getDefaultController();
        }
        $this->_controller = $controller;
    }

    public function getController() {
        return $this->_controller;
    }

    public function setRequest($request = null) {
        if (isset($request["first"])) {
            unset($request["first"]);
        }
        if (isset($request["second"])) {
            unset($request["second"]);
        }
        $this->_request = $request;
    }

    public function _getAllParams() {
        return $this->_request;
    }

    public function getFileController() {
        return $this->getControllerDir() . $this->_pathDelimiter . $this->getController() . $this->_pathDelimiter . ucfirst($this->getController()). "Controller" . $this->_controllerPostfix;
    }

    public function getControllerName() {
        return ucfirst($this->getController()) . "Controller";
    }
    
    public function getActionName() {
        return $this->getAction() . $this->_actionPostfix;
    }

}

?>
