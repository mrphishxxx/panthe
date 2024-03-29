<?php

class nc_class_editor {
    private $hash = null;
    private $template = null;
    private static $instanse = null;

    public function __construct($path, nc_Db $db, $type = null) {
        self::$instanse = $this;
        $this->template = new nc_tpl($path, $db, $type);
    }

    public function load($id, $path = null, $hash = null) {
        $this->hash = $hash;
        $this->template->load($id, 'Class', $path);
    }

    public function fill_fields() {
        if ($this->hash) {
            nc_tpl_parser::main2parts($this->template, $this->hash);
        }

        foreach ($this->template->fields->standart as $field_name => $tmp) {
            $this->template->fields->standart[$field_name] = $this->get_content($field_name);
        }
    }


    private function get_content($field) { 
        $field_path = $this->template->fields->get_path($field);
        return nc_check_file($field_path) ? nc_get_file($field_path) : false;
    }

    public function get_fields() {
        return $this->template->fields->standart;
    }

    public function save_fields($only_isset_post = false, $template = null) {
        $strip_slashes = false;        
        if (null == $template) {
            $template = $this->template;
            $strip_slashes = true;
        }
        
        foreach ($template->fields->standart as $field => $tmp) {
            if (!$only_isset_post || isset($_POST[$field])) {
                if ($field == 'RecordTemplate') {
                    $_POST['RecordTemplate'] = nc_get_string_service_prefix_for_RecordTemplate()
                            .$_POST['RecordTemplate']
                            .nc_get_string_service_suffix_for_RecordTemplate();
                }
                if ($strip_slashes && get_magic_quotes_gpc()) {
                    $_POST[$field] = stripslashes($_POST[$field]);                    
                }
                nc_save_file($template->fields->get_path($field), $_POST[$field]);
            }            
        }       
        nc_tpl_parser::parts2main($template);
    }

    public function get_parent_class_id($id) {
        return $this->template->get_parent_class_id($id);
    }

    public function save_new_class($id = null) {
        if ($id) {
            $this->template->load_child($id);
            $template = $this->template->child;
        } else {
            $template = $this->template;
        }
        nc_create_folder($template->absolute_path);
        $this->save_fields(false, $template);
        $template->update_file_path_and_mode();
    }

    public function delete_template() {
        $this->template->delete_template_file_and_folder();
    }

    public function get_template_id() {
        return $this->template->id;
    }

    public function get_relative_path() {
        return $this->template->relative_path;
    }

    public function get_absolute_path() {
        return $this->template->absolute_path;
    }
    
    public function get_clear_fields($params_text) {
        return array_diff($params_text, array_keys($this->template->fields->standart));
    }

    public static function get_instanse() {
        return self::$instanse;
    }
}