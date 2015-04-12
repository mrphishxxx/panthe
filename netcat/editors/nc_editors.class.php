<?php

class nc_Editors {
    protected $core, $editor_id;
    protected $editor, $html;
    // имя textarea и значение
    protected $name, $value;

    public function  __construct($editor_id, $name, $value = '') {
        $this->core = nc_Core::get_object();
        $this->name = $name;
        $this->value = $value;

        $editors = array(2 => 'fckeditor', 3 => 'ckeditor4', 4 => 'tinymce');

        call_user_func(array($this, "_make_" . $editors[$editor_id]));
    }


    public function get_html() {
        return $this->html;
    }


    protected function _make_fckeditor() {
        $lang = $this->core->lang->detect_lang(1);
        if ($lang == 'ru') $lang = $this->core->NC_UNICODE ? "ru_utf8" : "ru_cp1251";

        if (!class_exists("FCKeditor")) include_once($this->core->ROOT_FOLDER . "editors/FCKeditor/fckeditor.php");
        $this->editor = new FCKeditor($this->name);
        $this->editor->BasePath = $this->core->SUB_FOLDER . $this->core->HTTP_ROOT_PATH . "editors/FCKeditor/";
        $this->editor->Config["SmileyPath"] = $this->core->SUB_FOLDER . "/images/smiles/";
        $this->editor->ToolbarSet = "NetCat1";
        $this->editor->Width = "100%";
        $this->editor->Height = "320";
        $this->editor->Value = $this->value;
        $this->editor->Config['DefaultLanguage'] = $lang;
        if ($nc_core->AUTHORIZATION_TYPE == 'session') $this->editor->Config['sid'] = session_id();
        $this->html = $this->editor->CreateHtml();
    }


    protected function _make_ckeditor() {
        global $nc_core;
        if (!class_exists("CKEditor")) {
            include_once($this->core->ROOT_FOLDER . "editors/ckeditor/ckeditor_php5.php");
        }

        // подключение ckfinder'a ( если есть )
        $ckfinder_path = $this->core->ROOT_FOLDER . "editors/ckeditor/ckfinder/ckfinder.php";
        if (!class_exists('CKFinder') && file_exists($ckfinder_path)) {
            include_once($ckfinder_path);
        }

        // загружаем тему
        $skin = $nc_core->get_settings('CKEditorSkin');
        if (!$skin) $skin = 'kama';

        $lang = $this->core->lang->detect_lang(1);
        if ($lang == 'ru') $lang = $this->core->NC_UNICODE ? "ru_utf8" : "ru_cp1251";

        $config = array(
            'filebrowserBrowseUrl' => $this->core->SUB_FOLDER . $this->core->HTTP_ROOT_PATH . 'editors/ckeditor/filemanager/index.php',
            'width' => '100%',
            'height' => 320,
            'language' => $lang,
            'smiley_path' => $this->core->SUB_FOLDER . "/images/smiles/",
            'skin' => $skin,
            'removePlugins' => 'save'
        );

        $this->editor = new CKEditor();
        $this->editor->textareaAttributes['class'] = 'ckeditor_area';
        $this->editor->basePath = $this->core->SUB_FOLDER . $this->core->HTTP_ROOT_PATH . "editors/ckeditor/";
        $this->editor->returnOutput = true;

        if (class_exists('CKFinder')) CKFinder::SetupCKEditor($this->editor, $this->core->SUB_FOLDER . $this->core->HTTP_ROOT_PATH . 'editors/ckeditor/ckfinder/');

        $this->html = $this->editor->editor($this->name, $this->value, $config);
    }

    protected function _make_ckeditor4() {
        if (!class_exists("CKEditor")) {
            include_once($this->core->ROOT_FOLDER . "editors/ckeditor4/ckeditor.php");
        }

        $this->editor = new CKEditor($this->name, $this->value);
        $this->html = $this->editor->CreateHtml();
    }

    protected function _make_tinymce() {
        if (!class_exists("TinyMCE")) {
            include_once($this->core->ROOT_FOLDER . "editors/tinymce/tinymce.php");
        }

        $language = $this->core->lang->detect_lang(1);
        $language = $language == 'ru' ? 'ru' : '';

        $this->editor = new TinyMCE($this->name, $this->value, $language);
        $this->html = $this->editor->CreateHtml();
    }
}
