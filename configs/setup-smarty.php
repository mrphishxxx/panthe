<?php

require_once PATH . 'includes/smarty/Smarty.class.php';

class Smarty_Project extends Smarty {

    function __construct() {

        parent::__construct();

        $this->template_dir = PATH . 'templates/';
        $this->compile_dir = PATH . 'templates_c/';
        $this->config_dir = PATH . '/';
        $this->cache_dir = PATH . 'cache/';

        $this->caching = false;
        $this->assign('app_name', 'iForget');
    }

    protected function assignAllFieldsObject($object, $is_numeric = false) {
        if(!empty($object)) {
            foreach ($object as $key => $value) {
                if ($is_numeric || !is_numeric($key)) {
                    $this->assign(strtolower($key), $value);
                }
            }
        }
    }

}

?>
