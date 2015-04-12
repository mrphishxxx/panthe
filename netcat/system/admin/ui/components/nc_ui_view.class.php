<?php


class nc_ui_view {

    //--------------------------------------------------------------------------

    protected $path = null;
    protected $view = null;
    protected $data = array();

    //--------------------------------------------------------------------------

    public function __construct($view, $data = array()) {
        // global $NETCAT_FOLDER;
        global $nc_core;

        $this->path = '';
        $this->view = $view;

        if ($data) $this->data = $data;
        $this->data += $GLOBALS;
        $this->data['nc_core'] =& $nc_core;

        return $this;
    }

    //--------------------------------------------------------------------------

    /**
     * Рендренг шаблона
     */
    public function make() {
        if ($this->data) {
            extract($this->data);
        }

        ob_start();

        include $this->path . $this->view . '.php';

        return ob_get_clean();
    }

    //--------------------------------------------------------------------------

    public function __toString() {
        return $this->make();
    }

    //--------------------------------------------------------------------------

    /**
     * Присвоение переменной шаблона
     * @param  string $key  Название переменной
     * @param  mixed $value Значение переменой
     * @return $this
     */
    public function with($key, $value) {
        $this->data[$key] = $value;
        return $this;
    }

    //--------------------------------------------------------------------------

}