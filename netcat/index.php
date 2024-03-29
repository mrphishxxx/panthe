<?php

/* $Id: index.php 8342 2012-11-06 12:40:47Z ewind $ */

$NETCAT_FOLDER = join(strstr(__FILE__, "/") ? "/" : "\\", array_slice(preg_split("/[\/\\\]+/", __FILE__), 0, -2)).( strstr(__FILE__, "/") ? "/" : "\\" );
@include_once ($NETCAT_FOLDER."vars.inc.php");

$curPos = isset($curPos) ? intval($curPos) : 0;
if ($curPos < 0) $curPos = 0;

if (!$action) $action = "index";

// подключаем систему и $nc_core
require ($INCLUDE_FOLDER."index.php");

$lsDisplayType = $nc_core->get_display_type();
if (!$nc_core->inside_admin && $lsDisplayType == 'longpage_vertical' && $sub != $current_catalogue['Title_Sub_ID'] && $sub != $current_catalogue['E404_Sub_ID']) {
    $subdivision = $nc_core->subdivision->get_current();
    $parentSubdivisionId = $subdivision['Parent_Sub_ID'];
    $scrollToSubdivision = $sub;

    $template = null;

    if ($parentSubdivisionId) {
        $sub = $parentSubdivisionId;
        $sql = "SELECT `Sub_Class_ID` FROM `Sub_Class` " .
            "WHERE `Subdivision_ID` = {$sub} AND `Checked` = 1 " .
            "ORDER BY `Priority` ASC LIMIT 1";
        $subClass = $nc_core->db->get_row($sql, ARRAY_A);
        $cc = $subClass ? $subClass['Sub_Class_ID'] : 0;

        require ($INCLUDE_FOLDER."index.php");
    } else {
        $sub = $current_catalogue['Title_Sub_ID'];
        $cc = 0;

        require ($INCLUDE_FOLDER."index.php");
        $cc = 0;
        $current_cc = false;
    }
}

if ($nc_core->inside_admin && !$UI_CONFIG) {
    $UI_CONFIG = new ui_config_objects($cc);
}
// подключаем все Settings темплейтов, чтобы шаблоны навигации и пагинации были видны в s_list
if ($File_Mode && !$templatePreview) {
    $template_view = new nc_template_view($nc_core->TEMPLATE_FOLDER, $nc_core->db);
    $template_view->load_template($template, $template_env['File_Path']);
    $array_settings_path = $template_view->get_all_settings_path_in_array();
    foreach ($array_settings_path as $path) {
        include $path;
    }
}
// для админки
if ($inside_admin && $UI_CONFIG) {
    $UI_CONFIG->locationHash = "object.list(".$cc.")";
}

// site online?
if (!$current_catalogue["Checked"] && !( is_object($perm) && ($perm->isInstanceModeratorAdmin('site') || $perm->isInstanceModeratorAdmin('sub') || $perm->isInstanceModeratorAdmin('cc')) )) {
    echo $current_catalogue["ncOfflineText"];
    exit;
}

if ($inside_admin) {
    $use_multi_sub_class = 0;
} else {
    $use_multi_sub_class = $nc_core->subdivision->get_current("UseMultiSubClass");

    if ($use_multi_sub_class == 2) {
        $use_multi_sub_class = 0;
    }
}

$nc_main_content = '';

switch (true) {
    case $use_multi_sub_class && !$cc_keyword:
        foreach ($cc_array as $cc) {
            if ($cc || $user_table_mode) {
                // поскольку компонентов несколько, то current_cc нужно переопределить
                $current_cc = $nc_core->sub_class->set_current_by_id($cc);
                // вывод списка объектов компонента
                $nc_main_content .= nc_objects_list($sub, $cc, $nc_core->url->get_parsed_url('query').( isset($date) ? "&date=".$date : "")."&isMainContent=1&isSubClassArray=1");
            }
        }
        // current_cc нужно вернуть в первоначальное состояние, чтобы использовать в футере макета
        $current_cc = $nc_core->sub_class->set_current_by_id($cc_array[0]);
        break;
    case $cc || $user_table_mode:
        $nc_main_content = nc_objects_list($sub, $cc, $nc_core->url->get_parsed_url('query').( isset($date) ? "&date=".$date : "")."&isMainContent=1");
        break;
}

ob_start();

if ($nc_core->inside_admin && $nc_trash_full) {
    nc_print_status(NETCAT_TRASH_OBJECT_WERE_DELETED_TRASHBIN_FULL, 'info');
}

if ($nc_core->inside_admin && $nc_folder_fail) {
    nc_print_status(sprintf(NETCAT_TRASH_FOLDER_FAIL, $nc_core->HTTP_TRASH_PATH), 'info');
}

if ($nc_core->inside_admin && is_array($nc_trashed_ids) && !empty($nc_trashed_ids)) {
    $url = http_build_query($_GET).'&nc_recovery=1';
    $url = $nc_core->SUB_FOLDER.$nc_core->HTTP_ROOT_PATH.'message.php?'.$url;
    nc_print_status(sprintf(NETCAT_TRASH_OBJECT_IN_TRASHBIN_AND_CANCEL, $nc_core->ADMIN_PATH."trash/", $url), 'info');
    unset($url);
}

$lsDisplayType = $nc_core->get_display_type();

$inputDisplayType = $nc_core->input->fetch_get('lsDisplayType');

if (!$nc_core->inside_admin && !$inputDisplayType && ($lsDisplayType == 'shortpage' || $lsDisplayType == 'longpage_vertical')) {
    echo "<div " . nc_ls_display_container($sub) . ">";
    echo $nc_main_content;
    echo "</div>";
} else {
    $skipTemplate     = $nc_core->input->fetch_get('skipTemplate');

    $subdivisionTemplate = null;

    if ($inputDisplayType == 'shortpage' && (!$skipTemplate))  {
        $catalogue = $nc_core->catalogue->get_current();
        $subdivision = $nc_core->subdivision->get_current();

        $subdivisionTemplate = $nc_core->catalogue->get_current('Template_ID') != $subdivision['Template_ID'] ?
            $nc_core->template->get_by_id($subdivision['Template_ID']) : null;
        // $subdivisionTemplate = $nc_core->template->get_by_id($subdivision['Template_ID']);
    }

    if ($subdivisionTemplate) {
        if ($File_Mode) {
            $shortpageTemplateView = new nc_template_view($nc_core->TEMPLATE_FOLDER, $nc_core->db);
            $shortpageTemplateView->load_template($subdivision['Template_ID'], $subdivisionTemplate['File_Path']);
            foreach ($shortpageTemplateView->get_all_settings_path_in_array() as $path) {
                include $path;
            }
            $shortpageTemplateView->fill_fields();
        }

        if ($File_Mode) {
            echo $shortpageTemplateView->get_header();
        } else {
            eval("echo \"" . $subdivisionTemplate['Header'] . "\";");
        }
    }

    echo $nc_main_content;
    if ($inputDisplayType == 'shortpage') {
        echo nc_include_quickbar_updates();
    }

    if ($subdivisionTemplate) {
        if ($File_Mode) {
            echo $shortpageTemplateView->get_footer();
        } else {
            eval("echo \"" . $subdivisionTemplate['Footer'] . "\";");
        }
    }
}

//longpage display view
if (!$nc_core->inside_admin && $sub != $current_catalogue['E404_Sub_ID'] && $lsDisplayType == 'longpage_vertical') {
    $catalogue = $nc_core->catalogue->get_current();
    $subdivision = $nc_core->subdivision->get_current();

    $catalogueId = (int)$catalogue['Catalogue_ID'];
    $subdivisionId = (int)$subdivision['Subdivision_ID'];

    if ($catalogue && $subdivision && $subdivision['Catalogue_ID'] == $catalogueId) {

        if ($catalogue['Title_Sub_ID'] == $subdivisionId || $catalogue['E404_Sub_ID'] == $subdivisionId) {
            $parentSubId = 0;
        } else {
            $parentSubId = $subdivisionId;
        }

        $sql = "SELECT `Subdivision_ID`, `Template_ID` " .
            "FROM `Subdivision` " .
            "WHERE `Checked` = 1 AND `Catalogue_ID` = {$catalogueId} AND `Parent_Sub_ID` = {$parentSubId} " .
            "AND `DisplayType` IN ('inherit', 'longpage_vertical') " .
            "ORDER BY `Priority`";

        $innerSubdivisions = (array)$db->get_results($sql, ARRAY_A);

        foreach ($innerSubdivisions as $innerSubdivision) {
            $innerSubdivisionId = (int)$innerSubdivision['Subdivision_ID'];

            if ($subdivisionId == $innerSubdivisionId) {
                continue;
            }

            $sql = "SELECT `Sub_Class_ID` FROM `Sub_Class` " .
                "WHERE `Subdivision_ID` = {$innerSubdivisionId} AND `Checked` = 1 " .
                "ORDER BY `Priority` ASC LIMIT 1";

            $subClass = $db->get_row($sql, ARRAY_A);

            if ($subClass) {
                $subdivisionTemplate = $nc_core->catalogue->get_current('Template_ID') != $innerSubdivision['Template_ID'] ?
                    $nc_core->template->get_by_id($innerSubdivision['Template_ID']) : null;

                echo "<div " . nc_ls_display_pointer($innerSubdivisionId, isset($scrollToSubdivision) && $innerSubdivisionId == $scrollToSubdivision) . "></div>";
                if ($subdivisionTemplate) {
                    if ($File_Mode) {
                        $longpageTemplateView = new nc_template_view($nc_core->TEMPLATE_FOLDER, $nc_core->db);
                        $longpageTemplateView->load_template($innerSubdivision['Template_ID'], $subdivisionTemplate['File_Path']);
                        foreach ($longpageTemplateView->get_all_settings_path_in_array() as $path) {
                            include $path;
                        }
                        $longpageTemplateView->fill_fields();
                    }

                    if ($File_Mode) {
                        echo $longpageTemplateView->get_header();
                    } else {
                        eval("echo \"" . $subdivisionTemplate['Header'] . "\";");
                    }
                }

                echo "<div " . nc_ls_display_container($innerSubdivisionId) . ">";
                echo nc_objects_list($innerSubdivisionId, $subClass['Sub_Class_ID']);
                echo "</div>";

                if ($subdivisionTemplate) {
                    if ($File_Mode) {
                        echo $longpageTemplateView->get_footer();
                    } else {
                        eval("echo \"" . $subdivisionTemplate['Footer'] . "\";");
                    }
                }
            }
        }
    }
}

$nc_result_msg = ob_get_clean();

if($_REQUEST['isModal']) {
    $nc_result_msg = nc_prepare_message_form($nc_result_msg, $action, $admin_mode, $user_table_mode, $sys_table_id, $current_cc, $f_Checked, $f_Priority, $f_Keyword, $f_ncTitle, $f_ncKeywords, $f_ncDescription, false);
}

if ($File_Mode) {
    require_once $INCLUDE_FOLDER.'index_fs.inc.php';

    if (!$templatePreview) {
        if ($nc_core->inside_admin && $UI_CONFIG) {
            $js_code = $UI_CONFIG->to_json();
            $template_header = nc_insert_in_head($template_header, $js_code, true);
        }

        echo $template_header;
        echo $nc_result_msg;
        echo $template_footer;
    } else {
        eval('?>'.$template_header);
        echo $nc_result_msg;
        eval('?>'.$template_footer);
    }
} else {
    eval("echo \"".$template_header."\";");
    echo $nc_result_msg;
    eval("echo \"".$template_footer."\";");
}