<?php


class nc_ui_helper extends nc_ui_common {

    //--------------------------------------------------------------------------

    protected static $obj;

    //--------------------------------------------------------------------------

    public function render() {
        return "";
    }

    //--------------------------------------------------------------------------

    public static function get()
    {
        if (is_null(self::$obj)) {
            self::$obj = new self();
        }
        self::$obj->reset();
        return self::$obj;
    }

    //--------------------------------------------------------------------------

    public function clearfix() {
        return "<div class='nc--clearfix'></div>";
    }

    //--------------------------------------------------------------------------

    public function h1($text) {
        return nc_ui_html::get('h1')->class_name('nc-h1')->text($text);
    }

    //--------------------------------------------------------------------------

}