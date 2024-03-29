<?php

/* $Id: quickbar.inc.php 8373 2012-11-08 12:59:04Z ewind $ */

function nc_quickbar_in_template_header($buffer, $File_Mode = false, $return_shortpage_update_array = false) {
    global $MODULE_VARS, $AUTH_USER_ID, $ADMIN_TEMPLATE, $HTTP_ROOT_PATH, $ADMIN_PATH, $perm;
    global $SUB_FOLDER, $REQUEST_URI, $REQUEST_METHOD, $ADMIN_AUTHTIME;
    global $current_catalogue, $current_sub, $current_cc, $current_user, $AUTHORIZE_BY;
    global $inside_admin, $admin_mode, $user_table_mode, $action, $message;

    $nc_core = nc_Core::get_object();

    if (($inside_admin || !nc_quickbar_permission())) {
        return $return_shortpage_update_array ? null : $buffer;
    }

    if (!$return_shortpage_update_array) {
        // reversive direction!
        $buffer = nc_insert_in_head( $buffer, nc_js(), $nc_core->get_variable("admin_mode") );
    }

    if ($nc_core->modules->get_by_keyword('auth')) {
        $profile_url = nc_auth_profile_url($AUTH_USER_ID);
    }

    $view_link = ($current_sub['Hidden_URL'] != "/index/" ? $current_sub['Hidden_URL'] : "/") . ($message && $current_cc['EnglishName'] ? $current_cc['EnglishName'] . "_" . $message . ".html" : "");

    if (!$user_table_mode) {
        $edit_link = $HTTP_ROOT_PATH . ($action == "change" ? "message" : $action) . ".php?catalogue=" . $current_catalogue['Catalogue_ID'] . ($current_sub['Subdivision_ID'] ? "&amp;sub=" . $current_sub['Subdivision_ID'] : "") . ($current_cc['Sub_Class_ID'] ? "&amp;cc=" . $current_cc['Sub_Class_ID'] : "") . ($message ? "&amp;message=" . $message : "");
    } else {
        $edit_link = $HTTP_ROOT_PATH . "?catalogue=" . $current_catalogue['Catalogue_ID'] . ($current_sub['Subdivision_ID'] ? "&amp;sub=" . $current_sub['Subdivision_ID'] : "") . ($current_cc['Sub_Class_ID'] ? "&amp;cc=" . $current_cc['Sub_Class_ID'] : "") . ($message ? "&amp;message=" . $message : "");
    }

    $admin_link = "";

    switch (true) {
        case $current_cc['System_Table_ID'] == 3 && $message:
            $admin_link = "#user.edit(" . $message . ")";
            break;
        case $current_cc['Sub_Class_ID'] && $message:
            $admin_link = "#object.view(" . $current_cc['Sub_Class_ID'] . "," . $message . ")";
            break;
        case $current_cc['Sub_Class_ID']:
            $admin_link = "#object.list(" . $current_cc['Sub_Class_ID'] . ")";
            break;
        case $current_sub['Subdivision_ID']:
            $admin_link = "#subclass.list(" . $current_sub['Subdivision_ID'] . ")";
            break;
        case $current_catalogue['Catalogue_ID']:
            $admin_link = "#site.map(" . $current_catalogue['Catalogue_ID'] . ")";
    }

    $admin_link           = $ADMIN_PATH . $admin_link;
    $sub_admin_limk       = $ADMIN_PATH . "subdivision/index.php?phase=5&SubdivisionID={$current_sub['Subdivision_ID']}&view=all";
    $template_admin_limk  = $ADMIN_PATH . 'template/index.php?phase=4&TemplateID=' . $nc_core->template->get_current('Template_ID');
    $sub_class_admin_link = $ADMIN_PATH . "subdivision/SubClass.php?SubdivisionID=" . $current_sub['Subdivision_ID'];
    $msg_img              = $ADMIN_PATH . 'skins/default/img/msg.png';
    $pass_admin_link      = $ADMIN_PATH . 'user/index.php';
    $lock_img             = $ADMIN_PATH . 'skins/default/img/lock.png';
    $right_img            = $ADMIN_PATH . 'skins/default/img/right.png';

    if ($return_shortpage_update_array) {
        return array(
            'view_link' => $view_link,
            'edit_link' => $edit_link,
            'sub_admin_link' => $sub_admin_limk,
            'template_admin_link' => $template_admin_limk,
            'admin_link' => $admin_link,
        );
    }

    $ANY_SYSTEM_MESSAGE = $nc_core->db->get_var("SELECT COUNT(*) FROM `SystemMessage` WHERE `Checked` = 0");

    $lang = $nc_core->lang->detect_lang(1);
    if ($lang == 'ru')
        $lang = $nc_core->NC_UNICODE ? "ru_utf8" : "ru_cp1251";

    if ($nc_core->modules->get_by_keyword('cache'))
        $cache_link = $ADMIN_PATH . "#module.cache";

    $PermissionGroup_Name = $nc_core->db->get_col("SELECT PermissionGroup_Name FROM PermissionGroup WHERE PermissionGroup_ID IN (" . join(', ', (array) $current_user['Permission_Group']) . ")");
	/*<script type='text/javascript' src='" . $SUB_FOLDER . $ADMIN_PATH . "js/sitemap.js'></script>
	<script type='text/javascript' src='" . $SUB_FOLDER . $ADMIN_PATH . "js/remind_save.js'></script>*/


    //--------------------------------------------------------------------------
    // Генерация панели инструментов (navbar) для front-end
    //--------------------------------------------------------------------------

    $navbar = $nc_core->ui->navbar();

    $lsDisplayType = $nc_core->get_display_type();

    //--------------------------------------------------------------------------

    // Просмотр
    $navbar->quickmenu->add_btn($SUB_FOLDER . $view_link, NETCAT_QUICKBAR_BUTTON_VIEWMODE)->active(! $admin_mode);
    if ($lsDisplayType != 'longpage_vertical') {
        // Редактирование
        $navbar->quickmenu->add_btn($SUB_FOLDER . $edit_link, NETCAT_QUICKBAR_BUTTON_EDITMODE)->active($admin_mode);


        //--------------------------------------------------------------------------

        // Пункт меню "Ещё"
        $navbar->more = $navbar->menu->add_btn('#', NETCAT_QUICKBAR_BUTTON_MORE)->submenu();
        $navbar->more->add_btn($sub_admin_limk, NETCAT_QUICKBAR_BUTTON_SUBDIVISION_SETTINGS)->icon('settings')->click('nc_form(this.href); return false');
        $navbar->more->add_btn($template_admin_limk, NETCAT_QUICKBAR_BUTTON_TEMPLATE_SETTINGS)->icon('dev-templates')->click('nc_form(this.href); return false');
        $navbar->more->add_btn($admin_link, NETCAT_QUICKBAR_BUTTON_ADMIN)->icon('mod-default');

        //--------------------------------------------------------------------------
    } else {
        $navbar->quickmenu->add_btn('#', NETCAT_QUICKBAR_BUTTON_EDITMODE)->disabled()->title(NETCAT_QUICKBAR_BUTTON_EDITMODE_UNAVAILABLE_FOR_LONGPAGE)->click('return false')->modificator('default-cursor');
    }

    // AJAX Loader
    $navbar->tray->add_btn('#')->compact()->icon_large('navbar-loader')->id('nc-navbar-loader')->style('display:none');

    // Иконка с сообщениями
    $navbar->tray->add_btn($ADMIN_PATH . '#tools.systemmessages')->compact()
        ->title($ANY_SYSTEM_MESSAGE ? BEGINHTML_ALARMON : BEGINHTML_ALARMOFF)
        ->icon_large('system-message')
        ->id('trayMessagesIcon')
        ->disabled(!$ANY_SYSTEM_MESSAGE);

    // Меню пользователя
    $logout_link = $MODULE_VARS['auth'] ? $SUB_FOLDER . $HTTP_ROOT_PATH . "modules/auth/?logoff=1&amp;REQUESTED_FROM=" . $REQUEST_URI . "&amp;REQUESTED_BY=" . $REQUEST_METHOD : $ADMIN_PATH . "unauth.php" ;

    $navbar->tray->add_btn('#', $perm->getLogin())->click('return false')->dropdown()->div(
        // Права пользователя (список)
        NETCAT_ADMIN_AUTH_PERM . " <span class='nc-text-grey'>" . str_replace('"', '\"', join(', ', Permission::get_all_permission_names_by_id($AUTH_USER_ID))) . "</span><hr class='nc-hr'>"
        // Кнопка: Изменить пароль
        . $nc_core->ui->btn('#', NETCAT_ADMIN_AUTH_CHANGE_PASS)->click('nc_password_change(); return false')->light()->text_darken()->left()
        // Кнопка: Выйти
        . $nc_core->ui->btn($logout_link, NETCAT_ADMIN_AUTH_LOGOUT)->red()->right()
    )->class_name('nc-padding-10');

    //--------------------------------------------------------------------------


    // $navbar
    $navbar_html = (string)$navbar->fixed();
    $navbar_html .= "
<script type='text/javascript'>
jQuery().ready(function(){
    var padding = parseInt(jQuery('body').css('padding-top')) + parseInt(jQuery('body').css('margin-top')) + jQuery('div.nc-navbar').height();
    jQuery('body').css({marginTop: padding + 'px'});
});
jQuery('div.nc-navbar li.nc--dropdown > a').click(function(){
    jQuery(this.parentNode).addClass('nc--clicked');
    return false;
}).parent().mouseleave(function(){
    var ob = this
    jQuery._navbar_menu_timeout = setTimeout(function(){
       jQuery(ob).removeClass('nc--clicked');
    }, 500);
}).mouseover(function(){
    if (jQuery(this).hasClass('nc--clicked')) {
        try {clearTimeout(jQuery._navbar_menu_timeout)} catch(e) {};
    }
}).find('li a').click(function(){
    jQuery('div.nc-navbar li.nc--dropdown').removeClass('nc--clicked');
    jQuery('div.nc-navbar li.nc--clicked').removeClass('nc--clicked');
});
jQuery('body').click(function(){
    jQuery('div.nc-navbar>ul>li.nc--clicked').removeClass('nc--clicked')
})
</script>";


// Содержание модального окна быстрого изменения пароля
//TODO: Сделать загрузку содержимого окна через ajax
$navbar_html .= "
<div id='nc_password_change' class='nc-shadow-large nc--hide nc-admin'>
    <form style='width:350px; line-height:20px' class='nc-form' method='post' action='" . $ADMIN_PATH . "user/index.php'>
        <div class='nc-padding-15'>
            <h2 class='nc-h2'>" . NETCAT_ADMIN_AUTH_CHANGE_PASS . "</h2>
            <hr class='nc-hr' style='margin:5px -15px 15px'>
            <div>
                <label>" . CONTROL_USER_NEWPASSWORD . "</label><br>
                <input class='nc--wide' type='password' name='Password1' maxlength='32' />
            </div>
            <div>
                <label>" . CONTROL_USER_NEWPASSWORDAGAIN . "</label><br>
                <input class='nc--wide' type='password' name='Password2' maxlength='32' />
            </div>
            <input type='hidden' name='UserID' value='" . $AUTH_USER_ID . "' />
            <input type='hidden' name='phase' value='7' />
            " . $nc_core->token->get_input() . "
        </div>
    </form>
    <div class='nc-form-actions'>
        <button class='nc-btn nc--bordered nc--red nc--right' onclick='jQuery.modal.close()' type='button'>" . CONTROL_BUTTON_CANCEL . "</button>
        <button class='nc_admin_metro_button nc-btn nc--blue nc--right' onclick='jQuery(\'#nc_password_change form\').submit()'>" . NETCAT_REMIND_SAVE_SAVE . "</button>
    </div>
</div>
<!-- /#nc_password_change -->";

    $addon = "<!-- Netcat QuickBar -->\n" .
            "<script type='text/javascript' src='" . $ADMIN_PATH . "js/classes/nc_cookies.class.js'></script>\n" .
            "<script type='text/javascript' src='" . $ADMIN_PATH . "js/classes/nc_drag.class.js'></script>\n" .
            "<script type='text/javascript' src='" . $ADMIN_PATH . "js/lang/" . $lang . ".js?" . $LAST_LOCAL_PATCH . "' charset='" . $nc_core->NC_CHARSET . "'></script>
                <link rel='stylesheet' href='" . $ADMIN_PATH . "/js/codemirror/lib/codemirror.css'>
                <script src='" . $ADMIN_PATH . "js/codemirror/lib/codemirror.js'></script>
                <script src='" . $ADMIN_PATH . "js/codemirror/mode/xml.js'></script>
                <script src='" . $ADMIN_PATH . "js/codemirror/mode/mysql.js'></script>
                <script src='" . $ADMIN_PATH . "js/codemirror/mode/javascript.js'></script>
                <script src='" . $ADMIN_PATH . "js/codemirror/mode/css.js'></script>
                <script src='" . $ADMIN_PATH . "js/codemirror/mode/clike.js'></script>
                <script src='" . $ADMIN_PATH . "js/codemirror/mode/php.js'></script>
                <script type='text/javascript'>
                    var nc_token = '".$nc_core->token->get(+$AUTH_USER_ID)."';
                </script>
                <script type='text/javascript'>
                    jQuery(function () {

                        function getEditorTypeById(id) {
                            if(id == 'Query') {
                                return 'text/x-mysql';
                            }
                            return 'text/x-php';
                        }

                        if(true){

                            window.CMEditors = [];

                            function createCMEditor(ind, el) {
                                var init = true;
                                return function () {
                                    if(init) {
                                        var h = jQuery(el).height();
                                        window.CMEditors[ind] = CodeMirror.fromTextArea(el,{
                                            lineNumbers: true,
                                            mode: getEditorTypeById(jQuery(el).attr('id')),
                                            indentUnit: 4
                                        });
                                        window.CMEditors[ind].id = jQuery(el).attr('id');
                                        var scrollEl = jQuery(window.CMEditors[ind].getScrollerElement());
                                        scrollEl.height(h);
                                    }
                                    else {
                                        var h = jQuery(window.CMEditors[ind].getScrollerElement()).height();
                                        window.CMEditors[ind].toTextArea();
                                        jQuery(el).height(h);
                                    }
                                    init = !init;
                                }
                            }

                            jQuery('textarea').each(function (ind, el) {
                                return null;
                                var prev0 =  jQuery(el).prev(), prev = prev0.prev(), prevPrev = prev.prev(),
                                prev0F = prev0.filter('div.resize_block').children(), prevF = prev.filter('div.resize_block').children(), prevPrevF = prevPrev.filter('div.resize_block').children();
                                prevF.add(prev0F).add(prevPrevF).each(function (i, e) {
                                    jQuery(e).bind('click', function () {
                                        var idd = jQuery(this).attr('href').substr(1);
                                        for(var k in window.CMEditors) {
                                            if(window.CMEditors[k].id == idd) {
                                                var scrollEl = jQuery(window.CMEditors[k].getScrollerElement());
                                                if(jQuery(this).hasClass('textarea_shrink')) {
                                                    scrollEl.height(scrollEl.height() + 20);
                                                }
                                                else if(scrollEl.height() > 120) {
                                                    scrollEl.height(scrollEl.height() - 20);
                                                }
                                                break;
                                            }
                                        }
                                    });
                                });
                                jQuery(el).after(jQuery('<input>').attr({type: 'checkbox', id: 'cmtext'+ind})
                                .click(createCMEditor(ind, el))
                                .after(jQuery('<label>').attr('for', 'cmtext'+ind).html(' " . NETCAT_SETTINGS_CODEMIRROR_SWITCH . "')));
                            });
                        }
                    });
                    jQuery('body').attr('style', 'overflow-y: auto;');
                </script>
                <!-- для диалога генерации альтернативных форм -->
                <script type='text/javascript'>
                    var SUB_FOLDER = '" . $SUB_FOLDER . "';
                    var NETCAT_PATH = '" . $SUB_FOLDER . $HTTP_ROOT_PATH . "';
                    var ADMIN_PATH = '" . $ADMIN_PATH . "';
                    var ADMIN_LANG = '" . MAIN_LANG . "';
                    var NC_CHARSET = '" . $nc_core->NC_CHARSET . "';
                    var ICON_PATH = '" . $ADMIN_TEMPLATE . " + img/';
                </script>" .
            "<script>
                    function showhide(val, val2) {
                        var obj=document.getElementById(val)
                        var obj2=document.getElementById(val2)
                        obj.className=(obj.className=='show_add')? 'hide_add': 'show_add'
                        obj2.className=(obj2.className=='blue')? 'white': 'blue'
                }
                </script>";

    $addon .= $navbar_html;

    if ($File_Mode) {
        $addon = str_replace("\\\"", "\"", $addon);
    }

    switch (true) {
        case nc_preg_match("/\<\s*?frameset.*?\>/im", $buffer):
            break;
        case nc_preg_match("/\<\s*?body.*?\>/im", $buffer):
            $preg_pattern = "/(\<\s*?body.*?\>){1}/im";
            $preg_replacement = "\$1\n" . $addon;
            break;
        case nc_preg_match("/\<\s*?html\s*?\>/im", $buffer):
            $preg_pattern = "/(\<\s*?html\s*?\>){1}/im";
            $preg_replacement = "\$1\n<body>" . $addon . "</body>";
            break;
    }

    if ($preg_pattern && $preg_replacement) {
        $buffer = nc_preg_replace($preg_pattern, $preg_replacement, $buffer);
    }

    return $buffer;
}


?>
