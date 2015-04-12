<?php


/**
 * @property nc_ui_navbar $navbar
 * @method static nc_ui get_instance()
 * @method nc_ui_navbar navbar()
 */
class nc_ui {

    //--------------------------------------------------------------------------

    protected static $obj;
    protected $components = array(
        'html',
        'helper',
        'alert',
        'btn',
        'label',
        'icon',
        'toolbar',
        'navbar',
        'tabs',
        'table',
        'form',
        'view',
    );

    //--------------------------------------------------------------------------

    private function __construct() {
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
        $ext = '.class.php';
        require_once $dir . 'nc_ui_common' . $ext;

        foreach ($this->components as $com) {
            require_once $dir . 'components/nc_ui_' . $com . $ext;
        }
    }

    //--------------------------------------------------------------------------

    private function __clone() {}
    private function __wakeup() {}

    //--------------------------------------------------------------------------

    /**
     * [get_instance description]
     * @return nc_ui [description]
     */
    public static function get_instance() {
        if (is_null(self::$obj)) {
            self::$obj = new self();
        }

        return self::$obj;
    }

    //--------------------------------------------------------------------------

    public function __get($name) {
        return call_user_func_array(array($this, $name), array());
    }

    //--------------------------------------------------------------------------

    public function __call($name, $args) {
        return call_user_func_array(array('nc_ui_' . $name, 'get'), $args);
    }

    //--------------------------------------------------------------------------

    public function view($view, $data = array()) {
        return new nc_ui_view($view, $data);
    }

    //--------------------------------------------------------------------------

}