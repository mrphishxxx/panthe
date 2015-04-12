<?php

class CKEditor {

    private $name;

    private $value;

    public function __construct($name = null, $value = null) {
        $this->name = $name;
        $this->value = $value;
    }

    public function CreateHtml() {
        $nc_core = nc_Core::get_object();

        $html = "";

        static $initComplete;

        if (!$initComplete) {
            $initComplete = true;

            $ed_path = $nc_core->SUB_FOLDER . $nc_core->HTTP_ROOT_PATH . 'editors/ckeditor4/';
            $js_path = $ed_path . 'ckeditor.js';

            $html .= "<script type='text/javascript' src='" . $js_path . "'></script>";
        }

        $language = $nc_core->lang->detect_lang(1);
        $language = $language == 'ru' ? 'ru' : 'en';

        $html .= "<textarea class='no_cm' name=\"{$this->name}\" id=\"{$this->name}\">" . htmlspecialchars($this->value) . "</textarea>\n";
        $html .= "<script type=\"text/javascript\">
    setTimeout(function() {
        try {
            CKEDITOR.replace('{$this->name}', {
                language: '{$language}',
                filebrowserBrowseUrl:  '" . $nc_core->SUB_FOLDER . $nc_core->HTTP_ROOT_PATH . 'editors/ckeditor/filemanager/index.php' . "'
            });
        } catch (exception) {
        }
    }, 250);
</script>\n";

        return $html;
    }

    public function getScriptPath() {
        $nc_core = nc_Core::get_object();

        return $nc_core->SUB_FOLDER . $nc_core->HTTP_ROOT_PATH . 'editors/ckeditor4/ckeditor.js';
    }

    public function getWindowFormScript($language = null) {
        $nc_core = nc_Core::get_object();

        if (!$language) {
            $language = $nc_core->lang->detect_lang(1);
            $language = $language == 'ru' ? 'ru' : 'en';
        }

        return "setTimeout(function() {
    try {
        CKEDITOR.replace('nc_editor', {
            language: '{$language}',
            filebrowserBrowseUrl:  '{$nc_core->SUB_FOLDER}{$nc_core->HTTP_ROOT_PATH}editors/ckeditor/filemanager/index.php'
        });
    } catch (exception) {
    }
}, 250);";
    }

    public function getInlineScript($fieldName, $fieldValue, $messageId, $subClassId = null) {
        $nc_core = nc_Core::get_object();

        $language = $nc_core->lang->detect_lang(1);
        $language = $language == 'ru' ? 'ru' : 'en';

        $subClass = $subClassId ? $nc_core->sub_class->get_by_id($subClassId) : $nc_core->sub_class->get_current();
        $classId = $subClass['Class_ID'];

        $html = '';

        static $initComplete;

        if (!$initComplete) {
            $initComplete = true;

            $ed_path = $nc_core->SUB_FOLDER . $nc_core->HTTP_ROOT_PATH . 'editors/ckeditor4/';
            $js_path = $ed_path . 'ckeditor.js';

            $html .= "<script type='text/javascript' src='" . $js_path . "'></script>";
            $html .= "<script type='text/javascript'>
    CKEDITOR.disableAutoInline = true;
</script>";
        }

        $html .= "<div id='{$fieldName}_{$messageId}_{$subClassId}_edit_inline' style='display: inline-block;' contenteditable='true' data-oldvalue='" . str_replace("'", "&#39;", $fieldValue) . "'>";
        $html .= $fieldValue;
        $html .= "</div>";
        $html .= "<script type='text/javascript'>
    \$nc('#{$fieldName}_{$messageId}_{$subClassId}_edit_inline').closest('A').on('click', function(){
        return false;
    });
    try {
        if (typeof(CKEDITOR.nc_active_inline) == 'undefined') {
            CKEDITOR.nc_active_inline = false;
        }
        CKEDITOR.inline('{$fieldName}_{$messageId}_{$subClassId}_edit_inline', {
            filebrowserBrowseUrl: '{$nc_core->SUB_FOLDER}{$nc_core->HTTP_ROOT_PATH}editors/ckeditor/filemanager/index.php',
            language: '{$language}',
            on: {
                blur: function(){
                    var \$element = \$nc('#{$fieldName}_{$messageId}_{$subClassId}_edit_inline');
                    var newValue = CKEDITOR.instances.{$fieldName}_{$messageId}_{$subClassId}_edit_inline.getData();
                    var oldValue = \$element.attr('data-oldvalue');
                    if (CKEDITOR.nc_active_inline) {
                        \$element.html(oldValue);
                        return true;
                    } else {
                        CKEDITOR.nc_active_inline = true;
                    }

                    if (\$nc.trim(newValue) != \$nc.trim(oldValue)) {
                        //\$element.attr('data-oldvalue', newValue);
                    ";
        if ($nc_core->get_settings('InlineEditConfirmation')) {
            $html .= "parent.nc_form('{$nc_core->SUB_FOLDER}{$nc_core->HTTP_ROOT_PATH}editors/nc_edit_in_place.php?dummy=0', '', '', {
                            width: 300,
                            height: 100
                        }, 'post', {
                            messageId: {$messageId},
                            subClassId: {$subClass['Sub_Class_ID']},
                            fieldName: '{$fieldName}',
                            newValue: newValue
                        });";
        } else {
            $html .= "parent.nc_action_message('{$nc_core->SUB_FOLDER}{$nc_core->HTTP_ROOT_PATH}editors/nc_edit_in_place.php?dummy=0', 'post', {
                            messageId: {$messageId},
                            subClassId: {$subClass['Sub_Class_ID']},
                            fieldName: '{$fieldName}',
                            newValue: newValue
                        });
                        CKEDITOR.nc_active_inline = false;
                        ";
        }

        $html .= "}
                    return true;
                }
            }
        });
    } catch (exception) {
    }
</script>";

        return $html;
    }

}