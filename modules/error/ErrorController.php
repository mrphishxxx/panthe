<?php

/**
 * Description of FaqController
 *
 * @author Abashev A.V.
 */
class ErrorController {

    public $_db;
    public $_front;
    public $_smarty;

    public function __construct($db, $smarty) {
        $this->_db = $db;
        $this->_smarty = $smarty;
        $this->_postman = new Postman($smarty, $db);
        $this->_smarty->assign('Title', 'FAQ iForget');
    }

    public function accessErrorAction() {
        $this->_smarty->display("error/access_error.tpl");
    }

    

}
