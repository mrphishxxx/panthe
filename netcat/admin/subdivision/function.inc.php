<?php
/* $Id: function.inc.php 8571 2012-12-27 14:51:40Z lemonade $ */
if (!class_exists("nc_System")) die("Unable to load file.");

if (!$systemMessageID) {
    $systemMessageID = $SubdivisionID;
}
$systemTableName = "Subdivision";
$systemTableID = 2;
//ui class
require_once "ui.php";
require_once "favorite.inc.php";


# покажем список разделов в виде таблицы

function ShowSubdivisionList() {
    global $db, $nc_core;
    global $EDIT_DOMAIN, $HTTP_ROOT_PATH, $SUB_FOLDER;
    global $loc, $perm;
    global $DOMAIN_NAME;
    global $ADMIN_PATH, $ADMIN_TEMPLATE, $UI_CONFIG;

    if ($Result = $db->get_results("select a.Subdivision_ID,a.Subdivision_Name,a.Priority,a.Checked,a.Hidden_URL,b.Domain,a.Catalogue_ID,a.ExternalURL from Subdivision AS a,Catalogue AS b where a.Parent_Sub_ID=".$loc->ParentSubID." and a.Catalogue_ID=".$loc->CatalogueID." AND a.Catalogue_ID=b.Catalogue_ID ORDER BY a.Priority", ARRAY_N)) {
        $totrows = $db->num_rows;
        ?>
        <form method=post action=index.php>

            <table border=0 cellpadding=0 cellspacing=0 width=100% class='border-bottom'><tr><td>

                        <table border=0 cellpadding=0 cellspacing=0 width=100%>
                            <tr>
                                <td>ID</td>
                                <td width=60%><?= CONTROL_CONTENT_SUBDIVISION_FUNCS_SECTION ?></td>
                                <td><?= CONTROL_CONTENT_SUBDIVISION_FUNCS_SUBSECTIONS ?></td>
                                <td align=center><div class='icons icon_prior' title='<?=CONTROL_CONTENT_SUBDIVISION_FUNCS_PRIORITY ?>'></div></td>
                                <td align=center><?= CONTROL_CONTENT_SUBDIVISION_FUNCS_GOTO ?></td>
                                <td align=center><div class='icons icon_delete' title='<?= CONTROL_CONTENT_SUBDIVISION_FUNCS_DELETE ?>'></div></td>
                            </tr>
                            <?php
                            foreach ($Result as $Array) {
                                $children = 0;
                                print "<tr><td bgcolor=white><font size=-2".(!$Array[3] ? " color=cccccc" : "")."><b>".$Array[0]."</td>";
                                print "<td><a href=\"index.php?phase=4&SubdivisionID=".$Array[0]."\">".(!$Array[3] ? "<font color=cccccc>" : "").$Array[1]."</a></td>";
                                print "<td><font size=-2><a href=index.php?phase=1&CatalogueID=".$loc->CatalogueID."&ParentSubID=".$Array[0].">".(!$Array[3] ? "<font color=cccccc>" : "").(!ChildrenNumber($Array[0]) ? CONTROL_CONTENT_SUBDIVISION_FUNCS_NONE : CONTROL_CONTENT_SUBDIVISION_FUNCS_LIST." (".($children = ChildrenNumber($Array[0])).")")."</a></td>";
                                print "<td align=center><input type=text name=Priority".$Array[0]." size=3 class=s value='".$Array[2]."'></td>";

                                print "<td align=center>";

                                //setup
                                print "<a href='index.php?view=edit&phase=5&SubdivisionID=".$Array[0]."''><div class='icons icon_settings' title='".CONTROL_CONTENT_SUBDIVISION_FUNCS_TOOPTIONS."'></div></a>";

                                //edit

                                if (GetSubClassCount($Array[0])) {
                                    $cc_id = $db->get_var("SELECT Sub_Class_ID FROM Sub_Class WHERE Subdivision_ID = '".$Array[0]."'");
                                    print "<a href='http://".$EDIT_DOMAIN.$SUB_FOLDER.$HTTP_ROOT_PATH."?inside_admin=1&cc=".$cc_id.(strlen(session_id()) > 0 ? "&".session_name()."=".session_id()."" : "")."'><div class='icons icon_pencil' title='".CONTROL_CONTENT_CATALOUGE_FUNCS_SHOWMENU_A_EDIT."'></div></a>";
                                } else {
                                    print "<img src=".$ADMIN_PATH."images/emp.gif width=16 height=16>";
                                }

                                //browse
                                $show_url_arr = array("Hidden_URL" => $Array[4], "Domain" => $Array[5], "ExternalURL" => $Array[7]);
                                print "<a href='".nc_subdivision_preview_link($show_url_arr)."' target='_blank'><div class='icons icon_preview' title='".CONTROL_CONTENT_SUBDIVISION_FUNCS_TOVIEW."'></div></a>";

                                print"</td>";

                                print "<td bgcolor=white align=center><input type=checkbox name=Delete".$Array[0]." value=".$Array[0]."></td>\n";
                                print "</tr>\n";
                            }
                            ?>
                        </table></td></tr></table>
            <?php
        } else {
            if (!$loc->ParentSubID) {
                nc_print_status(CONTROL_CONTENT_SUBDIVISION_FUNCS_NOSECTIONS, 'info');
            } else {
                nc_print_status(CONTROL_CONTENT_SUBDIVISION_FUNCS_NOSUBSECTIONS, 'info');
            }
        }

        if ($totrows) {
            print "<input type=hidden name=phase VALUE=7>";
            print "<input type=hidden name=CatalogueID VALUE=".$loc->CatalogueID.">";
            print "<input type=hidden name=ParentSubID VALUE=".$loc->ParentSubID.">";
            print "<input type='submit' class='hidden'>";
            print $nc_core->token->get_input();
            print "</form>";
            $UI_CONFIG->actionButtons[] = array(
                "id" => "delete",
                "caption" => CONTROL_CONTENT_SUBDIVISION_FUNCS_SAVE,
                "action" => "mainView.submitIframeForm()",
                "align" => "right"
            );
        }
        $UI_CONFIG->actionButtons[] = array("id" => "submit",
                "caption" => CONTROL_CONTENT_SUBDIVISION_FUNCS_ADDSECTION,
                "location" => "subdivision.add($loc->ParentSubID,".$loc->CatalogueID.")",
                "align" => "left"
        );
    }

###############################################################################
# добавим/изменим раздел

    function ActionSubdivisionCompleted($type) {
        global $HTTP_ROOT_PATH, $HTTP_DOMAIN, $SUB_FOLDER;
        global $loc, $perm, $admin_mode, $nc_core;
        global $db, $ROOT_FOLDER, $FILECHMOD, $DIRCHMOD;
        global $systemTableID, $systemTableName, $systemMessageID;
        global $FILES_FOLDER, $INCLUDE_FOLDER, $MODULE_FOLDER, $ADMIN_FOLDER;

        require_once $INCLUDE_FOLDER."s_files.inc.php";
        $is_there_any_files = getFileCount(0, $systemTableID);
        $lm_type = $nc_core->page->get_field_name('last_modified_type');
        $sm_field = $nc_core->page->get_field_name('sitemap_include');
		$sm_change_field = $nc_core->page->get_field_name('sitemap_changefreq');
		$sm_priority_field = $nc_core->page->get_field_name('sitemap_priority');

        $params = array('Subdivision_Name', 'EnglishName', 'TemplateID', 'ReadAccessID',
                'WriteAccessID', 'EditAccessID', 'SubscribeAccessID',
                'CheckedAccessID', 'DeleteAccessID', 'ModerationID', 'Checked',
                'Priority', 'ExternalURL', 'UseMultiSubClass', 'CacheAccessID', 'CacheLifetime', 'CatalogueID',
                'SubdivisionID', 'ParentSubID', 'UseEditDesignTemplate', 'Title',
                'Keywords', 'Description', 'CommentsEditRules', 'CommentAccessID', 'CommentsDeleteRules',
                'posting', 'last_modified_type', 'language', 'title', 'keywords', 'description',
                'DisallowIndexing', $sm_field, $sm_change_field, $sm_priority_field);

        foreach ($params as $v)
            $$v = $nc_core->input->fetch_get_post($v);

        $st = new nc_Component(0, 2);
        foreach ($st->get_fields() as $v) {
            $name = 'f_'.$v['name'];
            global $$name;
            if ($v['type'] == 6) {
                global ${$name."_old"};
                global ${"f_KILL".$v['id']};
            }
        }

        $Priority += 0;

        if ($type == 1) {
            $action = "add";
        }

        if ($type == 2) {
            $action = "change";
            $message = $loc->SubdivisionID;
        }

        $component = new nc_Component(0, 2);
        $fl = $component->get_fields();

        // prepare template custom settings
        $settings = $db->get_var("SELECT `CustomSettings` FROM `Template` WHERE `Template_ID` = '".(int) $TemplateID."'");
        if ($settings) {
            require_once($ADMIN_FOLDER."array_to_form.inc.php");
            $a2f = new nc_a2f($settings, 'TemplateSettings');
            if (!$a2f->validate($_POST['TemplateSettings'])) {
                $warnText = $a2f->get_validation_errors();
                $posting = 0;
            }
            $a2f->save($_POST['TemplateSettings']);
            $TemplateSettings = $a2f->get_values_as_string();
        } else {
            $TemplateSettings = "";
        }

        require $ROOT_FOLDER."message_fields.php";

        if ($posting == 0) {
            nc_print_status($warnText, 'error');
            SubdivisionForm($phase, "index.php", $type);
            return false;
        }

        if (nc_module_check_by_keyword("comments")) {
            include_once ($MODULE_FOLDER."comments/function.inc.php");
        }

        require $ROOT_FOLDER."message_put.php";

        $db->last_error = '';

        if ($type == 1) {
            $insert = "INSERT INTO `Subdivision` SET ";
            // fields from system table component (2)
            for ($i = 0; $i < $fldCount; $i++) {
                if ($fldTypeOfEdit[$i] == 3)
                        continue; // поле недоступно никому

// quotes added into the message_put.php!
                $insert.= "`".$fld[$i]."` = ".$fldValue[$i].", ";
            }
            if (nc_module_check_by_keyword("cache")) {
                $insert .= "`Cache_Access_ID` = '".$CacheAccessID."', ";
                $insert .= "`Cache_Lifetime` = '".$CacheLifetime."',";
            }


            $insert.= "`Catalogue_ID`  = '".$loc->CatalogueID."',";
            $insert.= "`Parent_Sub_ID` = '".$loc->ParentSubID."',";
            $insert.= "`Subdivision_Name` = '".$Subdivision_Name."',";
            $insert.= "`Template_ID` = '".$TemplateID."',";
            $insert.= "`Read_Access_ID` = '".$ReadAccessID."',";
            $insert.= "`Write_Access_ID` = '".$WriteAccessID."',";
            $insert.= "`Edit_Access_ID` = '".$EditAccessID."',";
            $insert.= "`Checked_Access_ID` = '".$CheckedAccessID."',";
            $insert.= "`Delete_Access_ID` = '".$DeleteAccessID."',";
            $insert.= "`Subscribe_Access_ID` = '".$SubscribeAccessID."',";
            $insert.= "`Moderation_ID` = '".$ModerationID."',";
            $insert.= "`Checked` = '".$Checked."',";
            $insert.= "`ExternalURL` = '".$ExternalURL."',";
            $insert.= "`EnglishName` = '".$EnglishName."',";
            $insert.= "`Favorite` = '".$Favorite."',";
            $insert.= "`Created` = '".date("Y-m-d H:i:s")."',";
            $insert.= "`Priority` = '".$Priority."',";
            $insert.= "`UseMultiSubClass` = '".$UseMultiSubClass."',";
            $insert.= "`UseEditDesignTemplate` = '".$UseEditDesignTemplate."',";
            $insert.= "`".$lm_type."` = '".intval($last_modified_type)."',";
            $insert.= "`TemplateSettings` = '".$db->escape($TemplateSettings)."'";

            // execute core action
            $nc_core->event->execute("addSubdivisionPrep", $loc->CatalogueID, 0);

            $Result = $db->query($insert);
            $systemMessageID = $db->insert_id;

            //sql error
            if ($db->last_error) return false;

            // execute core action
            $nc_core->event->execute("addSubdivision", $loc->CatalogueID, $systemMessageID);

            $message = $systemMessageID;

            // set insert_id in Filetable, becouse Message_ID and File_Path fields are empty
            if (!empty($filetable_lastid)) {
                $db->query("UPDATE `Filetable`
          SET `Message_ID` = '".$message."', `File_Path` = '/".$message."/'
          WHERE `ID`  IN(".join(',', $filetable_lastid).")");
            }
            // default value
            if (!isset($DIRCHMOD)) $DIRCHMOD = 0777;
            // create dir
            @mkdir($FILES_FOLDER.$message."/", $DIRCHMOD);

            // end set insert_id block

            if (nc_module_check_by_keyword("comments")) {
                if ($CommentAccessID > 0) {
                    // add comment relation
                    $CommentRelationID = nc_comments::addRule($db, array($loc->CatalogueID, $message), $CommentAccessID, $CommentsEditRules, $CommentsDeleteRules);
                    // update inserted data
                    $db->query("UPDATE `Subdivision` SET `Comment_Rule_ID` = '".(int) $CommentRelationID."' WHERE `Subdivision_ID` = '".(int) $message."'");
                }
            }
        }
        if ($type == 2) {
            $cur_checked = $db->get_var("SELECT `Checked` FROM `Subdivision` WHERE `Subdivision_ID` = '".$loc->SubdivisionID."'");
            if (nc_module_check_by_keyword("comments")) {
                // get rule id
                $CommentData = nc_comments::getRuleData($db, array($loc->CatalogueID, $loc->SubdivisionID));
                $CommentRelationID = $CommentData['ID'];
                // do something
                switch (true) {
                    case $CommentAccessID > 0 && $CommentRelationID:
                        // update comment rules
                        nc_comments::updateRule($db, array($loc->CatalogueID, $loc->SubdivisionID), $CommentAccessID, $CommentsEditRules, $CommentsDeleteRules);
                        break;
                    case $CommentAccessID > 0 && !$CommentRelationID:
                        // add comment relation
                        $CommentRelationID = nc_comments::addRule($db, array($loc->CatalogueID, $loc->SubdivisionID), $CommentAccessID, $CommentsEditRules, $CommentsDeleteRules);
                        break;
                    case $CommentAccessID <= 0 && $CommentRelationID:
                        // delete comment rules
                        nc_comments::dropRuleSubdivision($db, $loc->SubdivisionID);
                        $CommentRelationID = 0;
                        break;
                }
            }

            $update = "UPDATE `Subdivision` SET ";
            for ($i = 0; $i < $fldCount; $i++) {
                if ($fldTypeOfEdit[$i] == 3) continue; // поле недоступно никому
                $update.= "`".$fld[$i]."` = ".$fldValue[$i].", ";
            }


            if (!empty($fl))
                    foreach ($fl as $field) {
                    if ($field['usage'])
                            $update .="`".$db->escape($field['name'])."` = '".$db->escape($nc_core->input->fetch_get_post($field['name']))."', ";
                }

            $update.= "`Subdivision_Name`= '".$Subdivision_Name."',";
            $update.= "`ExternalURL`= '".$ExternalURL."',";
            $update.= "`EnglishName` = '".$EnglishName."',";
            $update.= "`Template_ID` = ".$TemplateID.",";
            $update.= "`Read_Access_ID` = '".$ReadAccessID."',";
            $update.= "`Write_Access_ID` = '".$WriteAccessID."',";
            $update.= "`Edit_Access_ID` = '".$EditAccessID."',";
            $update.= "`Checked_Access_ID` = '".$CheckedAccessID."',";
            $update.= "`Delete_Access_ID` = '".$DeleteAccessID."',";
            $update.= "`Subscribe_Access_ID` = '".$SubscribeAccessID."',";
            if (nc_module_check_by_keyword("cache")) {
                $update.= "`Cache_Access_ID` = '".$CacheAccessID."',";
                $update.= "`Cache_Lifetime` = '".$CacheLifetime."',";
            }
            if (nc_module_check_by_keyword("comments")) {
                $update.= "`Comment_Rule_ID` = '".$CommentRelationID."',";
            }
            $update.= "`Moderation_ID` = '".$ModerationID."',";
            $update.= "`Checked` = '".$Checked."',";
            $update.= "`Priority`= ".$Priority.",";
            $update.= "`Favorite`= '".$Favorite."',";
            $update.= "`UseMultiSubClass`= '".$UseMultiSubClass."',";
            $update.= "`UseEditDesignTemplate`= '".$UseEditDesignTemplate."',";
            $update.= "`DisallowIndexing`= '".intval($DisallowIndexing)."',";
            $update.= "`".$sm_field."`= '".$nc_core->input->fetch_get_post('sitemap_include')."',";
            $update.= "`".$nc_core->page->get_field_name('language')."` = '".$db->escape($language)."',";
            $update.= "`".$nc_core->page->get_field_name('title')."` = '".$db->escape($title)."',";
            $update.= "`".$nc_core->page->get_field_name('keywords')."` = '".$db->escape($keywords)."',";
            $update.= "`".$nc_core->page->get_field_name('description')."` = '".$db->escape($description)."',";
            $update.= "`".$lm_type."` = '".intval($last_modified_type)."',";
            if ($nc_core->modules->get_by_keyword('search')) {
				$update.= "`" . $sm_field . "` = '" . $nc_core->input->fetch_get_post('sitemap_include') . "',";
				$update.= "`" . $sm_change_field . "` = '" . $nc_core->input->fetch_get_post('sitemap_changefreq') . "',";
				$update.= "`" . $sm_priority_field . "` = '" . str_replace(',', '.', sprintf("%.1f", doubleval($nc_core->input->fetch_get_post('sitemap_priority')))) . "',";
			}
            $update.= "`TemplateSettings` = '".$db->escape($TemplateSettings)."'";
            $update.= " WHERE `Subdivision_ID` = ".$loc->SubdivisionID;
            // execute core action
            $nc_core->event->execute("updateSubdivisionPrep", $loc->CatalogueID, $loc->SubdivisionID);
            $nc_core->event->execute($Checked ? "checkSubdivisionPrep" : "uncheckSubdivisionPrep", $loc->CatalogueID, $loc->SubdivisionID);

            $Result = $db->query($update);

            //sql error
            if ($db->last_error) return false;

            // execute core action
            $nc_core->event->execute("updateSubdivision", $loc->CatalogueID, $loc->SubdivisionID);

            // произошло включение / выключение
            if ($cur_checked != $Checked) {
                $nc_core->event->execute($Checked ? "checkSubdivision" : "uncheckSubdivision", $loc->CatalogueID, $loc->SubdivisionID);
            }

            $changed_cc = array();

            // RSS
            $cc_in_sub = $db->get_results("SELECT `Sub_Class_ID` as `id`, `AllowRSS` as `cur` FROM `Sub_Class` WHERE `Subdivision_ID` = '".$loc->SubdivisionID."' ", ARRAY_A);
            if (!empty($cc_in_sub))
                    foreach ($cc_in_sub as $v) {
                    // значение, пришедшие из формы
                    $allow_rss = intval($nc_core->input->fetch_get_post('AllowRSS'.$v['id']));
                    // в случае, если значение изменилось
                    if ($allow_rss != $v['cur']) {
                        $db->query("UPDATE `Sub_Class` SET `AllowRSS` = '".$allow_rss."' WHERE `Sub_Class_ID` = '".$v['id']."' ");
                        $changed_cc[] = $v['id'];
                    }
                }

            // визуальные настройки
            $CustomSettings = "";
            if ($nc_core->input->fetch_get_post('custom_subclass_id')) {
                $settings = $db->get_var("SELECT `CustomSettingsTemplate` FROM `Class`
                                WHERE `Class_ID` = '".intval($nc_core->input->fetch_get_post('custom_class_id'))."'");
                if ($settings) {
                    require_once($nc_core->ADMIN_FOLDER."array_to_form.inc.php");
                    $a2f = new nc_a2f($settings, 'CustomSettings');
                    if (!$a2f->validate($_POST['CustomSettings'])) {
                        $error = $a2f->get_validation_errors();
                        nc_print_status($error, 'error');
                    } else {
                        $a2f->save($_POST['CustomSettings']);
                        $CustomSettings = $a2f->get_values_as_string();
                        $cur_settings = $db->get_var("SELECT `CustomSettings` FROM `Sub_Class`
                                    WHERE `Sub_Class_ID` = '".intval($nc_core->input->fetch_get_post('custom_subclass_id'))."'");
                        if ($CustomSettings <> $cur_settings) {
                            $nc_core->event->execute("updateSubClassPrep", $loc->CatalogueID, $loc->SubdivisionID, $changed_cc);

                            $db->query("UPDATE `Sub_Class` SET `CustomSettings` = '".$db->escape($CustomSettings)."'
                    WHERE `Sub_Class_ID` = '".intval($nc_core->input->fetch_get_post('custom_subclass_id'))."'");
                            $changed_cc[] = intval($nc_core->input->fetch_get_post('custom_subclass_id'));
                        }
                    }
                }
            }

            // трансляция события для компонент в разделе
            if (!empty($changed_cc)) {
                $changed_cc = array_unique($changed_cc);
                $nc_core->event->execute("updateSubClass", $loc->CatalogueID, $loc->SubdivisionID, $changed_cc);
            }
        }


        if ($type == 1 || $type == 2) {
            $hidden_url = GetHiddenURL($loc->ParentSubID);
            UpdateHiddenURL($hidden_url ? $hidden_url : "/", $loc->ParentSubID, $loc->CatalogueID);
        }


        // поисковая оптимизация, проверка
        if (!empty($fl)) {
            $hidden_url = $db->get_var("SELECT Hidden_URL FROM Subdivision WHERE Subdivision_ID = '".$message."' ");
            $real_value = $nc_core->page->get_meta_tags("http://".$nc_core->DOMAIN_NAME.$hidden_url);

            foreach ($fl as $field) {
                if ($real_value[$field['usage']] && $field['usage'] && $nc_core->input->fetch_get_post($field['name']) && $nc_core->input->fetch_get_post($field['name']) != $real_value[$field['usage']]) {
                    nc_print_status(sprintf(CONTROL_CONTENT_SUBDIVISION_SEO_VALUE_NOT_SETTINGS, $field['usage']), 'info');
                }
            }
        }

        // work with files
        for ($i = 0; $i < count($tmpFile); $i++) {

            eval("\$tmpNewFile[\$i] = \"".$tmpNewFile[$i]."\";");
            eval("\$File_PathNew[\$i] = \"".$File_PathNew[$i]."\";");
            eval("\$File_Path[\$i] = \"".$File_Path[$i]."\";");

            if ($fld_name[$i] /* && $type == 1 */) {
                $db->query("UPDATE `Subdivision`
       SET `".$fld_name[$i]."` = REPLACE(".$fld_name[$i].", '\$message', Subdivision_ID )
       WHERE Subdivision_ID = '".$message."'");
            }

            if ($FileFS[$i] == NC_FS_PROTECTED || $FileFS[$i] == NC_FS_ORIGINAL) {
                @rename($nc_core->FILES_FOLDER.$tmpNewFile[$i], $nc_core->FILES_FOLDER.$File_PathNew[$i].$tmpNewFile[$i]);
            } else {
                @rename($nc_core->FILES_FOLDER.$tmpFile[$i], $nc_core->FILES_FOLDER.$File_Path[$i].$tmpNewFile[$i]);
            }

            @chmod($nc_core->FILES_FOLDER.$File_PathNew[$i].$tmpNewFile[$i], $nc_core->FILECHMOD);
        }

        if (!empty($filetable_lastid)) {
            $db->query("UPDATE `Filetable`
          SET `Message_ID` = '".$message."', `File_Path` = '/".$message."/'
          WHERE `ID`  IN(".join(',', $filetable_lastid).")");
        }

        return ( $type == 1 && $message ? $message : ($type == 2 && $loc->SubdivisionID ? $loc->SubdivisionID : false) );
    }

###############################################################################
# покажем информацию по разделу

    function ShowSubdivisionMenu($SubdivisionID, $phase1, $action1, $phase2, $action2, $phase3, $action3) {
        global $db;
        global $EDIT_DOMAIN, $HTTP_ROOT_PATH, $DOMAIN_NAME, $SUB_FOLDER;
        global $UI_CONFIG;

        $nc_core = nc_Core::get_object();

        $Array = $nc_core->subdivision->get_by_id($SubdivisionID);

        $Domain = $nc_core->catalogue->get_by_id($Array["Catalogue_ID"], 'Domain');

        $Array["Hidden_Host"] = ($Domain ? (strchr($Domain, ".") ? $Domain : $Domain.".".$DOMAIN_NAME) : $DOMAIN_NAME);

        $SubClassCount = $db->get_var("SELECT COUNT(*) FROM `Sub_Class` WHERE `Subdivision_ID` = '".intval($SubdivisionID)."'");

        $info = $nc_core->template->get_by_id($Array['Template_ID']);

        //  In My SQL 4.1, TIMESTAMP display format changes to be the same as DATETIME.
        if ($Array['LastUpdated'][4] != '-') {
            $Array['LastUpdated'] = substr($Array['LastUpdated'], 0, 4)."-".substr($Array['LastUpdated'], 4, 2)."-".substr($Array['LastUpdated'], 6, 2)." ".substr($Array['LastUpdated'], 8, 2).":".substr($Array['LastUpdated'], 10, 2).":".substr($Array['LastUpdated'], 12, 2);
        }
        ?>

        <br />
            <table border=0 cellpadding=0 cellspacing=0 width=100%>
            <tr>
                <td>
                    <table border=0 cellpadding=0 cellspacing=0 width=100% class='border-bottom'>
                        <tr>
                            <td bgcolor=white>
                                <table border=0 cellpadding=0 cellspacing=0 width=100% >
                                    <col width='50%'/><col width='50%'/>
                                    <tr>
                                        <td style='border-bottom: none'><?= CONTROL_CONTENT_SUBDIVISION_FUNCS_INFO_UPDATED ?>:</td>
                                        <td style='border-bottom: none'><?= $Array['LastUpdated'] ?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor=white>
                                <table border=0 cellpadding=0 cellspacing=0 width=100%>
                                    <tr><td width=50%><?= CONTROL_CONTENT_SUBDIVISION_FUNCS_INFO_SUBSECTIONS_COUNT
    ?>:</td><td><?= $children = ChildrenNumber($SubdivisionID)
    ?> (<?= ($children ? "<a href=index.php?phase=1&ParentSubID=".$SubdivisionID.">".CONTROL_CONTENT_SUBDIVISION_FUNCS_LIST."</a>, " : "")
    ?><a href="index.php?phase=2&ParentSubID=<? print $SubdivisionID; ?>"><?= CONTROL_CONTENT_SUBDIVISION_FUNCS_ADD ?></a>)</td></tr>
                                    <tr><td><?= CONTROL_CONTENT_SUBDIVISION_FUNCS_INFO_CLASS_COUNT ?>:</td><td><?= $SubClassCount ?> (<?= $SubClassCount ? "<a href=SubClass.php?SubdivisionID=".$SubdivisionID.">".CONTROL_CONTENT_SUBDIVISION_FUNCS_LIST."</a>, " : "" ?><a href=SubClass.php?phase=1&SubdivisionID=<?= $SubdivisionID ?>><?= CONTROL_CONTENT_SUBDIVISION_FUNCS_ADD ?></a>)</td></tr>
                                    <tr><td><?= CONTROL_CONTENT_SUBDIVISION_FUNCS_INFO_STATUS ?>:</td><td><?= $Array["Checked"] ? CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_TURNON : CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_TURNOFF ?></td></tr>
                                    <tr><td style='border-bottom: none'><?= CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_MULTI_SUB_CLASS ?>:</td><td style='border-bottom: none'><?= $Array["UseMultiSubClass"] ? CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_TURNON : CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_TURNOFF ?></td></tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor=white>
                                <table border=0 cellpadding=0 cellspacing=0 width=100%>
                                    <tr><td width=50%><?= CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_DTEMPLATE ?>:</td><td><?= $info["Template_ID"] ?>. <?= $info['Description'] ?></td></tr>
                                    <tr><td valign='top' style='border-bottom: none; padding-top: 10px;'><?= CONTROL_TEMPLATE_CUSTOM_SETTINGS ?>:</td>
                                        <td style='border-bottom: none'><?
                                        if ($info['CustomSettings']) {
                                            eval($info['CustomSettings']);
                                            echo "<ol>";
                                            foreach ($settings_array as $settings) {
                                                echo "<li>".$settings['caption']."<br>\n";
                                            }
                                            echo "</ol>";
                                        } else {
                                            echo CONTROL_TEMPLATE_CUSTOM_SETTINGS_ISNOTSET;
                                        }
        ?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    <?php
}

###############################################################################

function DeleteSubdivision() {
    global $db;
    global $UI_CONFIG;

    $nc_core = nc_Core::get_object();

    // помним, что функция вызывается рекурсивно
    if (!is_object($UI_CONFIG)) {
        $UI_CONFIG = new ui_config_subdivision_delete(0);
        $UI_CONFIG->headerText = CONTROL_CONTENT_SUBDIVISION_FUNCS_LINEADD_DELETE;
    }
    $cat_fields = $nc_core->catalogue->get_all();
    foreach ($cat_fields as $kc => $vc) {
        foreach ($vc as $ks => $vs) {
            if ($ks == 'Title_Sub_ID' || $ks == 'E404_Sub_ID') {
                $subs_serv[] = $vs;
            }
        }
    }
    $i = 0;
    foreach ($nc_core->input->fetch_get_post() as $key => $val) {
        if (substr($key, 0, 6) == "Delete") {
            $sub_id = substr($key, 6, strlen($key) - 6) + 0;
            if (!in_array($sub_id, $subs_serv)) {
                DeleteSystemTableFiles('Subdivision', $sub_id);
                CascadeDeleteSubdivision($sub_id);
                $UI_CONFIG->treeChanges['deleteNode'][] = "sub-{$sub_id}";
                $i++;
            }
        }
    }
    nc_print_status(CONTROL_CONTENT_SUBDIVISION_FUNCS_LINEADD_DELETE_SUCCESS, 'ok');
}

###############################################################################

function AscIfDeleteSubdivision($phase, $action) {
    global $db, $nc_core;
    global $ParentSubID, $CatalogueID;
    global $UI_CONFIG;

    $ask = false;
    $cat_counter = 0;

    print "<form method=post action=\"".$action."\"><ul>";
    foreach ($nc_core->input->fetch_get_post() as $key => $val) {
        if (substr($key, 0, 6) == "Delete" && $val) {
            $ask = true;
            $sub_id = substr($key, 6, strlen($key) - 6) + 0;
            $sub_array = nc_get_sub_children($sub_id);
            $SelectArray = $db->get_results("SELECT Subdivision_ID, Subdivision_Name FROM Subdivision WHERE Subdivision_ID IN (".join(',', $sub_array).")", ARRAY_A);

            if (!empty($SelectArray))
                    foreach ($SelectArray as $temp) {
                    print "<li>".$temp['Subdivision_Name']."<br>";
                    print "<INPUT TYPE=HIDDEN NAME=Delete".$temp['Subdivision_ID']." VALUE=".$temp['Subdivision_ID'].">";
                    $cat_counter++;
                }
        }
    }

    if (!$ask) return false;

    if ($cat_counter > 1) {
        $post_f1 = CONTROL_CONTENT_SUBDIVISION_FUNCS_LINEADD_WARNING_ONE_MANY;
        $post_f2 = CONTROL_CONTENT_SUBDIVISION_FUNCS_LINEADD_WARNING_TWO_MANY;
    } else {
        $post_f1 = CONTROL_CONTENT_SUBDIVISION_FUNCS_LINEADD_WARNING_ONE_ONE;
        $post_f2 = CONTROL_CONTENT_SUBDIVISION_FUNCS_LINEADD_WARNING_TWO_ONE;
    }
    ?>
    </ul>
        <? nc_print_status(sprintf(CONTROL_CONTENT_SUBDIVISION_FUNCS_LINEADD_WARNING, $post_f1, $post_f2), 'info'); ?>
    <input type=hidden name=phase value=<? print $phase; ?>>
    <input type=hidden name=ParentSubID value=<?= ($ParentSubID + 0) ?>>
    <input type=hidden name=CatalogueID value=<?= ($CatalogueID + 0) ?>>
    <?php
    print $nc_core->token->get_input();
    print "</form>";
    $UI_CONFIG->actionButtons[] = array(

        "caption" => CONTROL_CONTENT_SUBDIVISION_FUNCS_LINEADD_DELETE_CONFIRMATION,
        "action" => "mainView.submitIframeForm()",
        "red_border" => true,
    );
    return true;
}

function ShowFullSubdivisionList() {
    global $db, $nc_core;
    global $HTTP_DOMAIN, $HTTP_HOST, $DOMAIN_NAME, $EDIT_DOMAIN, $HTTP_ROOT_PATH, $SUB_FOLDER, $CatalogueID;
    global $AUTHORIZATION_TYPE, $first, $perm;
    global $UI_CONFIG, $ADMIN_PATH, $ADMIN_TEMPLATE;

    $is_supervisor = ($perm->isSupervisor() || $perm->isCatalogueAdmin($CatalogueID) || $perm->isGuest() );

    $SQL = "SELECT `Catalogue_ID`,
                   `Catalogue_Name`,
                   `Domain`,
                   `Title_Sub_ID`,
                   `Checked`
                FROM `Catalogue`
                    WHERE `Catalogue_ID` = " . $CatalogueID;

    $Array = $db->get_row($SQL, ARRAY_N);

    if (!$db->num_rows) {
        nc_print_status(CONTROL_CONTENT_SUBDIVISION_FUNCS_LINEADD_ERR_NOONESITE, 'error');
        return;
    }

    ?>
    <form name='siteMapSearch' id='siteMapSearch' onsubmit='return false'>
        <div id='nc_sitemap_quicksearch_form'>
            <div class='nc_clear'></div>
            <div id='nc_sitemap_quicksearch'>
                <?= SITE_SITEMAP_SEARCH ?> &nbsp; <input type='text' name='searchKeyword' onsubmit='return false;' disabled>
            </div>
            <div class='nc_clear'></div>
        </div>
    </form>
    <?php
    echo "<form name='siteMapForm' id='siteMapForm' method='get' action='index.php'>";
    echo "<input type='hidden' name='phase' value='7'>";

    echo "<table class='nc-table nc--wide nc--hovered' id='nc_site_map'>";
    echo "<tr>";
    echo "<td id='siteTitle' class='name ".($Array[4] ? "active" : "unactive")."'>
            <span>".$Array[0].". </span>
            <a href='".$ADMIN_PATH."catalogue/index.php?phase=2&type=2&CatalogueID={$Array[0]}'>".$Array[1]."</a>
        </td>";


    // edit
    echo "<td class='nc--compact'>";
    if (GetSubClassCount($Array[3]) && $is_supervisor) {
        $cc_id = $db->get_var("SELECT Sub_Class_ID FROM Sub_Class WHERE Subdivision_ID = '".$Array[3]."'");
        echo "<a href='http://".$EDIT_DOMAIN.$SUB_FOLDER.$HTTP_ROOT_PATH."?inside_admin=1&cc=".$cc_id.(strlen(session_id()) > 0 ? "&".session_name()."=".session_id()."" : "")."'><div class='icons icon_pencil' title='".CONTROL_CONTENT_CATALOUGE_FUNCS_SHOWMENU_A_EDIT."'></div></a>";
    } else {
        echo "<img src='".$ADMIN_TEMPLATE."img/px.gif' alt='' />";
    }
    echo "</td>";

    // view
    echo "<td class='nc--compact'><a href='http://".($Array[2] ? $Array[2] : $DOMAIN_NAME).($SUB_FOLDER ? $SUB_FOLDER."/" : NULL).(strlen(session_id()) > 0 ? "?".session_name()."=".session_id()."" : "")."' target=_blank><i class='nc-icon nc--arrow-right nc--hovered' title='".CONTROL_CONTENT_CATALOUGE_FUNCS_SHOWMENU_A_VIEW."'></i></a></td>";

    // add
    echo "<td class='nc--compact'>".($is_supervisor ? "<a href='index.php?phase=2&CatalogueID=".$Array[0]."&ParentSubID=0'><i class='nc-icon nc--folder-add nc--hovered' title='".CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_A_ADDSUBSECTION."'></i></a>" : "")."</td>";

    // settings
    echo "<td class='nc--compact'>".($is_supervisor ? "<a href='".$ADMIN_PATH."catalogue/index.php?phase=2&CatalogueID=".$Array[0]."&type=2'><i class='nc-icon nc--settings nc--hovered' title='".CONTROL_CONTENT_CATALOUGE_FUNCS_SHOWCATALOGUELIST_TOOPTIONS."'></i></a>" : "")."</td>";

    // remove
    echo "<td class='nc--compact nc-padding-10 nc-text-center'>".($is_supervisor ? "<a href='".$ADMIN_PATH."catalogue/?".$nc_core->token->get_url()."&phase=4&Delete".$Array[0]."=".$Array[0]."'><i class='nc-icon nc--remove nc--hovered' title='".CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_A_REMSITE."'></i></a>" : "")."</td>";

    echo "</tr>";

    $count = 1;
    echo write_sub(0, $Array[0]);

    echo "</table>";
    echo "</form>\n";
    echo "<div id='siteMapNotFound' style='display:none'>".SITE_SITEMAP_SEARCH_NOT_FOUND."</div>\n";

    $UI_CONFIG->actionButtons[] = array(
        "id" => "submit",
        "caption" => CONTROL_CONTENT_SUBDIVISION_FUNCS_ADDSECTION,
        "action" => "parent.nc_form('{$SUB_FOLDER}{$nc_core->HTTP_ROOT_PATH}admin/subdivision/index.php?phase=16&CatalogueID={$CatalogueID}&inside_admin=1', '', '', {width: 300, height: 100})",
        "align" => "left",
    );

    $UI_CONFIG->actionButtons[] = array(
        "id" => "submit",
        "caption" => NETCAT_ADMIN_DELETE_SELECTED,
        "action" => "mainView.submitIframeForm('siteMapForm')",
        "red_border" => true,
    );
}

###############################################################################
# покажем список разделов в виде дерева: каждый элемент

function write_sub($ParentSubID, $CatalogueID, $count = 1) {
    global $db, $nc_core;
    global $DOMAIN_NAME, $HTTP_ROOT_PATH, $EDIT_DOMAIN, $DOMAIN_NAME, $SUB_FOLDER, $ADMIN_PATH, $ADMIN_TEMPLATE;
    global $perm;

    $CatalogueID = intval($CatalogueID);
    $ParentSubID = intval($ParentSubID);

    static $security_limit, $initialized;

    if (!$initialized) {
        $initialized = true;
        $allow_id = $perm->GetAllowSub($CatalogueID, MASK_ADMIN | MASK_MODERATE);
        $security_limit = is_array($allow_id) && !$perm->isGuest() ? " Subdivision_ID IN (".join(', ', (array) $allow_id).")" : " 1";
    }

    $Result = $db->get_results("SELECT a.Subdivision_ID,a.Subdivision_Name,a.Priority,a.Checked,a.Hidden_URL,b.Domain,a.Catalogue_ID,a.ExternalURL FROM Subdivision AS a, Catalogue AS b
    WHERE a.Catalogue_ID=b.Catalogue_ID AND a.Catalogue_ID=".$CatalogueID."
    AND a.Parent_Sub_ID='".$ParentSubID."' AND ".$security_limit." ORDER BY a.Priority", ARRAY_N);

    if (empty($Result)) return "";

    $cat_fields = $nc_core->catalogue->get_all();
    foreach ($cat_fields as $kc => $vc) {
        foreach ($vc as $ks => $vs) {
            if ($ks == 'Title_Sub_ID' || $ks == 'E404_Sub_ID') {
                $subs_serv[] = $vs;
            }
        }
    }

    foreach ($Result as $Array) {
        $result .= "<tr>";

        $result .= "<td class='name ".($Array[3] ? "active" : "unactive")."' style='padding-left: ".($count + 15)."px;'>
            <img src='".$ADMIN_PATH."images/arrow_sec.gif' width='14' height='10' alt='' title=''><span>".$Array[0].". </span>
            <a href='".$ADMIN_PATH."subdivision/index.php?phase=5&SubdivisionID={$Array[0]}&view=edit'>".$Array[1]."</a>
            </td>";

         // edit
        $result .= "<td class='nc--compact'>";
        if (GetSubClassCount($Array[0])) {
            $cc_id = $db->get_var("SELECT Sub_Class_ID FROM Sub_Class WHERE Subdivision_ID = '".$Array[0]."'");
            $result .= "<a href='http://".$EDIT_DOMAIN.$SUB_FOLDER.$HTTP_ROOT_PATH."?inside_admin=1&cc=".$cc_id.(strlen(session_id()) > 0 ? "&".session_name()."=".session_id()."" : "")."'><i class='nc-icon nc--edit nc--hovered' title='".CONTROL_CONTENT_CATALOUGE_FUNCS_SHOWMENU_A_EDIT."'></i></a>";
        }
        $result.= "</td>";

        // view
        $show_url_arr = array("Hidden_URL" => $Array[4], "Domain" => $Array[5], "ExternalURL" => $Array[7]);
        $result .= "<td class='nc--compact'><a href='".nc_subdivision_preview_link($show_url_arr)."' target='_blank'><i class='nc-icon nc--arrow-right nc--hovered' title='".CONTROL_CONTENT_CATALOUGE_FUNCS_SHOWMENU_A_VIEW."'></i></a></td>";

        // add
        $result .= "<td class='nc--compact'><a href='".$ADMIN_PATH."subdivision/index.php?phase=2&ParentSubID=".$Array[0]."'><i class='nc-icon nc--folder-add nc--hovered' title='".CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_A_ADDSUBSECTION."'></i></a></td>";

        // settings
        $result .= "<td class='nc--compact'><a href='".$ADMIN_PATH."subdivision/index.php?view=edit&phase=5&SubdivisionID=".$Array[0]."'><i class='nc-icon nc--settings nc--hovered' title='".CONTROL_CONTENT_SUBDIVISION_FUNCS_TOOPTIONS."'></i></a></td>";

        // checkbox
        $disabled = in_array($Array[0], $subs_serv) ? " disabled='disabled'" : '';
        $result.= "<td class='nc--compact nc-padding-10 nc-text-center'><input name='Delete".$Array[0]."' value='".$Array[0]."' type='checkbox'" . $disabled . "></td>";

        $result.= "</tr>";

        $result.= write_sub($Array[0], $CatalogueID, $count + 20);
    }

    return $result;
}

###############################################################################
# начало класса
# построение хлебных крошек - пути по структуре

class SubdivisionHierarchy {

    var $Link, $NoLink;

    function SubdivisionHierarchy($Delimeter, $CatalogueURL, $SubdivisionURL) {
        global $nc_core, $db;
        global $loc;
        $CurrentSubdivisionID+=0;
        $loc->CatalogueID+=0;
        $loc->ParentSubID+=0;
        $loc->SubdivisionID+=0;
        $Hierarchy = "";
        $Hierarchy = "<a href=\"".$CatalogueURL.$loc->CatalogueID."\">";
        if ($loc->CatalogueID) $Hierarchy .= $nc_core->catalogue->get_by_id($loc->CatalogueID, "Catalogue_Name")."</a>";

        if ($loc->SubdivisionID) $SubdivisionID = $loc->SubdivisionID;
        else $SubdivisionID = $loc->ParentSubID;


        $LastSubdivisionAsLink = 1;

        if ($SubdivisionID) {
            $SubdivisionArray[] = $SubdivisionID;
            $CurrentSubdivisionID = $SubdivisionID;

            while ($CurrentSubdivisionID) {
                $Array = $db->get_var("select Parent_Sub_ID from Subdivision where Subdivision_ID=".$CurrentSubdivisionID);
                $CurrentSubdivisionID = $Array;
                if ($CurrentSubdivisionID) $SubdivisionArray[] = $Array;
            }

            $this->Link = $this->NoLink = $Hierarchy;
            for ($i = sizeof($SubdivisionArray) - 1; $i > -1; $i--) {
                $this->Link .= $Delimeter;
                $this->NoLink .= $Delimeter;
                if ($i || (!$i && $LastSubdivisionAsLink))
                        $this->Link .= "<a href=\"".$SubdivisionURL.$SubdivisionArray[$i]."\">";
                $Hierarchy = $nc_core->subdivision->get_by_id($SubdivisionArray[$i], "Subdivision_Name");
                $this->Link .= $Hierarchy;
                $this->NoLink .= $Hierarchy;
                if ($i || (!$i && $LastSubdivisionAsLink)) $this->Link .= "</a>";
            }
        } else {
            $this->Link = $this->NoLink = $Hierarchy;
        }
    }

    function printVars() {
        print "Link=".$this->Link."<br>\n";
        print "NoLink=".$this->NoLink."<br>\n";
    }

}

#конец класса
###############################################################################

function UpdateSubdivisionPriority() {
    global $nc_core, $db;

    reset($_POST);

    while (list($key, $val) = each($_POST)) {
        if (substr($key, 0, 8) == "Priority") {
            $sub_id = substr($key, 8, strlen($key) - 8) + 0;
            $val += 0;

            $catalogue = $nc_core->subdivision->get_by_id($sub_id, "Catalogue_ID");

            // execute core action
            $nc_core->event->execute("updateSubdivisionPrep", $catalogue, $sub_id);

            $db->query("UPDATE `Subdivision` SET `Priority` = '".$val."', `LastUpdated` = `LastUpdated` WHERE `Subdivision_ID` = '".$sub_id."'");

            // execute core action
            $nc_core->event->execute("updateSubdivision", $catalogue, $sub_id);
        }
    }
}

###############################################################################

function AskForSubClassAddition() {
    global $db, $loc, $systemMessageID;
    echo CONTROL_CONTENT_CATALOUGE_FUNCS_MSG_OK."<br><br>";
    echo "<b><a href=SubClass.php?phase=1&SubdivisionID=".$systemMessageID.">".CONTROL_CONTENT_CATALOUGE_FUNCS_A_ADDCLASSTOSECTION."</a> | <a href=index.php?phase=1&".($loc->ParentSubID ? "ParentSubID=".$loc->ParentSubID : "CatalogueID=".$loc->CatalogueID).">".CONTROL_CONTENT_CATALOUGE_FUNCS_A_BACKTOSECTIONLIST."</a></b>";
}

function nc_show_subdivision_rights($subdivision_id) {
    global $db, $UI_CONFIG, $loc, $perm, $ADMIN_TEMPLATE, $nc_core;

    $is_supervisor = $perm->isAccess(NC_PERM_ITEM_USER, NC_PERM_ACTION_LIST, -1);

    $catalogue_id = $loc->CatalogueID;
    $subdivision_id = intval($subdivision_id);

    $perm_array = $nc_core->subdivision->get_by_id($subdivision_id);

    if ($perm_array['Read_Access_ID'] == 3 || $perm_array['Write_Access_ID'] == 3 || $perm_array['Edit_Access_ID'] == 3 || $perm_array['Subscribe_Access_ID'] == 3) {
        $cub_class_array = $db->get_col("SELECT Sub_Class_ID FROM Sub_Class WHERE Subdivision_ID = '".$subdivision_id."'", 0);

        $sql = "SELECT perm.User_ID, user.Login, perm.PermissionSet
            FROM Permission as perm
            LEFT JOIN User as user
            ON user.User_ID = perm.User_ID
            WHERE (perm.AdminType = '".SUBDIVISION_ADMIN."'
            AND perm.Catalogue_ID = '".$subdivision_id."')
            OR (perm.AdminType = '".CATALOGUE_ADMIN."' AND (perm.Catalogue_ID = '".$catalogue_id."' OR perm.Catalogue_ID = 0))";
        if ($cub_class_array)
                $sql .= " OR (perm.AdminType = '".SUB_CLASS_ADMIN."' AND perm.Catalogue_ID IN (".join(',', $cub_class_array)."))";
        $sql .= " GROUP BY User_ID";

        $users = $db->get_results($sql, ARRAY_A);
    }

    echo "<table class='subPermissions' border='0' cellpadding='0' cellspacing='0'><tr><td>";

    echo "<b>".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_READ_ACCESS.":</b><br>\n<ul>\n";
    switch ($perm_array['Read_Access_ID']) {
        case 1:
            echo "<li><div class='icons icon_usergroups' title='".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_ALL_USERS."'></div>&nbsp&nbsp ".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_ALL_USERS."\n";
            break;
        case 2:
            echo "<li><div class='icons icon_usergroups' title='".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_REGISTERED_USERS."'></div>&nbsp&nbsp ".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_REGISTERED_USERS."\n";
            break;
        case 3:
            $read_access = false;
            if ($users) {
                foreach ($users as $user) {
                    if ($user['PermissionSet'] & MASK_READ) {
                        $read_access = true;
                        echo "<li><div class='icons icon_usergroups' title='".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_ALL_USERS."'></div>".($is_supervisor ? "<a href='".$ADMIN_PATH."user/index.php?phase=4&UserID=".$user['User_ID']."'>".$user['Login']."</a>" : $user['Login'])." \n";
                    }
                }
            }
            if (!$read_access) {
                echo "<li><div class='icons icon_usergroups'  title='".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_PRIVILEGED_USERS."'></div>&nbsp&nbsp".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_PRIVILEGED_USERS."\n";
            }
            break;
    }
    echo "</ul>\n";

    if (nc_module_check_by_keyword("comments")) {
        echo "<b>".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_COMMENT_ACCESS.":</b><br>\n<ul>\n";
        // get comments access from inherited access ID
        $comment_access = $db->get_var("SELECT `Access_ID` FROM `Comments_Rules` WHERE `ID` = '".$perm_array['Comment_Rule_ID']."'");

        switch ($comment_access) {
            case 1:
                echo "<li><div class='icons icon_usergroups' title='".COSUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_ALL_USERSNTROL_."'></div>&nbsp&nbsp".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_ALL_USERS."\n";
                break;
            case 2:
                echo "<li><div class='icons icon_usergroups' title='".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_REGISTERED_USERS."'></div>&nbsp&nbsp".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_REGISTERED_USERS."\n";
                break;
            case 3:
                $read_access = false;
                if ($users) {
                    foreach ($users as $user) {
                        if ($user['PermissionSet'] & MASK_COMMENT) {
                            $read_access = true;
                            echo "<li><div class='icons icon_user' title='".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_ALL_USERS."'></div>&nbsp&nbsp".($is_supervisor ? "<a href='".$ADMIN_PATH."user/index.php?phase=4&UserID=".$user['User_ID']."'>".$user['Login']."</a>" : $user['Login'])." \n";
                        }
                    }
                }
                if (!$read_access) {
                    echo "<li><div class='icons icon_usergroups' title='".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_PRIVILEGED_USERS."'></div>&nbsp&nbsp".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_PRIVILEGED_USERS."\n";
                }
                break;
        }
        echo "</ul>\n";
    }

    echo "<b>".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_WRITE_ACCESS.":</b><br>\n<ul>\n";
    switch ($perm_array['Write_Access_ID']) {
        case 1:
            echo "<li><div class='icons icon_usergroups' title='".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_ALL_USERS."'></div>&nbsp&nbsp".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_ALL_USERS."\n";
            break;
        case 2:
            echo "<li><div class='icons icon_usergroups' title='".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_REGISTERED_USERS."'></div>&nbsp&nbsp".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_REGISTERED_USERS."\n";
            break;
        case 3:
            $write_access = false;
            if ($users) {
                foreach ($users as $user) {
                    if ($user['PermissionSet'] & MASK_ADD) {
                        $write_access = true;
                        echo "<li><div class='icons icon_user' title='".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_ALL_USERS."'></div>&nbsp&nbsp".($is_supervisor ? "<a href='".$ADMIN_PATH."user/index.php?phase=4&UserID=".$user['User_ID']."'>".$user['Login']."</a>" : $user['Login'])." \n";
                    }
                }
            }
            if (!$write_access) {
                echo "<li><div class='icons icon_usergroups' title='".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_PRIVILEGED_USERS."'></div>&nbsp&nbsp".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_PRIVILEGED_USERS."\n";
            }
            break;
    }
    echo "</ul>\n";

    echo "<b>".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_EDIT_ACCESS.":</b><br>\n<ul>\n";
    switch ($perm_array['Edit_Access_ID']) {
        case 1:
            echo "<li><div class='icons icon_usergroups' title='".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_ALL_USERS."'></div>&nbsp&nbsp".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_ALL_USERS."\n";
            break;
        case 2:
            echo "<li><div class='icons icon_usergroups' title='".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_REGISTERED_USERS."'></div>&nbsp&nbsp".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_REGISTERED_USERS."\n";
            break;
        case 3:
            $edit_access = false;
            if ($users) {
                foreach ($users as $user) {
                    if ($user['PermissionSet'] & MASK_EDIT) {
                        $edit_access = true;
                        echo "<li><div class='icons icon_user' title='".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_ALL_USERS."'></div>&nbsp&nbsp".($is_supervisor ? "<a href='".$ADMIN_PATH."user/index.php?phase=4&UserID=".$user['User_ID']."'>".$user['Login']."</a>" : $user['Login'])." \n";
                    }
                }
            }
            if (!$edit_access) {
                echo "<li><div class='icons icon_usergroups' title='".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_PRIVILEGED_USERS."'></div>&nbsp&nbsp".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_PRIVILEGED_USERS."\n";
            }
            break;
    }
    echo "</ul>\n";

    echo "</td><td>";

    echo "<b>".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_SUBSCRIBE_ACCESS.":</b><br>\n<ul>\n";
    switch ($perm_array['Subscribe_Access_ID']) {
        case 1:
            echo "<li><div class='icons icon_usergroups' title='".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_ALL_USERS."'></div>&nbsp&nbsp".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_ALL_USERS."\n";
            break;
        case 2:
            echo "<li><div class='icons icon_usergroups' title='".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_REGISTERED_USERS."'></div>&nbsp&nbsp".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_REGISTERED_USERS."\n";
            break;
        case 3:
            $subscribe_access = false;
            if ($users) {
                foreach ($users as $user) {
                    if ($user['PermissionSet'] & MASK_SUBSCRIBE) {
                        $subscribe_access = true;
                        echo "<li><div class='icons icon_user' title='".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_ALL_USERS."'></div>&nbsp&nbsp".($is_supervisor ? "<a href='".$ADMIN_PATH."user/index.php?phase=4&UserID=".$user['User_ID']."'>".$user['Login']."</a>" : $user['Login'])." \n";
                    }
                }
            }
            if (!$subscribe_access) {
                echo "<li><div class='icons icon_usergroups' title='".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_PRIVILEGED_USERS."'></div>&nbsp&nbsp".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_PRIVILEGED_USERS."\n";
            }
            break;
    }
    echo "</ul>\n";

    echo "<b>".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_MODERATORS.":</b><br>\n<ul>\n";
    $object_moderator = false;
    if ($users) {
        foreach ($users as $user) {
            if ($user['PermissionSet'] & MASK_MODERATE) {
                $object_moderator = true;
                echo "<li><div class='icons icon_userg' title='".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_ALL_USERS."'></div>".($is_supervisor ? "<a href='".$ADMIN_PATH."user/index.php?phase=4&UserID=".$user['User_ID']."'>".$user['Login']."</a>" : $user['Login'])." \n";
            }
        }
    }
    if (!$object_moderator) {
        echo "<li><div class='icons icon_usergroups' title='".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_PRIVILEGED_USERS."'></div>&nbsp&nbsp".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_PRIVILEGED_USERS."\n";
    }
    echo "</ul>\n";

    echo "<b>".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_ADMINS.":</b><br>\n<ul>\n";
    $object_admin = false;
    if ($users) {
        foreach ($users as $user) {
            if ($user['PermissionSet'] & MASK_ADMIN) {
                $object_admin = true;
                echo "<li><div class='icons icon_user' title='".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_."'></div>&nbsp&nbsp".($is_supervisor ? "<a href='".$ADMIN_PATH."user/index.php?phase=4&UserID=".$user['User_ID']."'>".$user['Login']."</a>" : $user['Login'])." \n";
            }
        }
    }
    if (!$object_admin) {
        echo "<li><div class='icons icon_usergroups' title='".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_PRIVILEGED_USERS."'></div>&nbsp&nbsp".SUBDIVISION_TAB_INFO_TOOLBAR_USERLIST_TEXT_PRIVILEGED_USERS."\n";
    }
    echo "</ul>\n";
}

/**
 * Return all sub childrens
 *
 * @param mixed parent sub
 * @return array array with sub id
 */
function GetChildrenSub($sub) {
    global $db;

    $sub = (array) $sub;
    if (empty($sub)) return;

    $ret = $db->get_col("SELECT `Subdivision_ID` FROM `Subdivision` WHERE `Parent_Sub_ID` IN (".join(", ", $sub).")");

    if (empty($ret)) return;

    $ret = array_merge($ret, (array) GetChildrenSub($ret));

    return $ret;
}

function nc_subdivision_show_edit_form($sub_id, $view) {
    $nc_core = nc_Core::get_object();
    $sub_env = $nc_core->subdivision->get_by_id($sub_id);
    if ($view == 'all') {
        echo "<style>#simplemodal-container { background-color: white; width: 900px; height: 70%; margin:0px; padding:0px; font-family:'Segoe UI', SegoeWP, Arial; font-size:12px; color:#444444; line-height:14px; background:#fff; }</style>";
    }

    echo "<form id='adminForm' class='nc-form' name='adminForm' action='{$nc_core->ADMIN_PATH}subdivision/index.php' method='post' enctype='multipart/form-data' >";
    echo "<input type='hidden' name='SubdivisionID' value='".intval($sub_id)."' />";
    echo "<input type='hidden' name='view' value='".htmlspecialchars($view)."' />";
    echo "<input type='hidden' name='phase' value='6' />";
    echo "<input type='hidden' name='posting' value='1' />";
    echo "<input type='submit' class='hidden' />";


    echo $nc_core->token->get_input();

    $views = $view == 'all' ? array('edit', 'design', 'seo', 'system', 'fields') : (array) $view;

    foreach ($views as $view) {
        echo "<div id='nc_subdivision_form_{$view}_div'" . ($_REQUEST['isNaked'] && $view != 'edit' ? " class='nc_class_none'" : "") . ">";
        echo call_user_func('nc_subdivision_form_' . $view, $sub_env);
        echo "</div>";
    }

    echo "</form>";
}

function nc_subdivision_print_modal_prefix($sub_id) {
    $Subdivision_Name = nc_Core::get_object()->subdivision->get_by_id($sub_id, 'Subdivision_Name');
?>

    <div class='nc_admin_form_menu' style='padding-top: 20px;'>
            <h2><?= $Subdivision_Name; ?></h2>
            <div id='nc_object_slider_menu' class='slider_block_2' style='padding-top: 0px; padding-bottom: 15px;'>
                <ul>
                    <li id='nc_subdivision_form_edit' class='button_on'><?=SUBDIVISION_TAB_INFO_TOOLBAR_EDIT_EDIT?></li>
                    <li id='nc_subdivision_form_design'><?=SUBDIVISION_TAB_INFO_TOOLBAR_EDIT_DESIGN?></li>
                    <li id='nc_subdivision_form_seo'><?=SUBDIVISION_TAB_INFO_TOOLBAR_EDIT_SEO?></li>
                    <li id='nc_subdivision_form_system'><?=SUBDIVISION_TAB_INFO_TOOLBAR_EDIT_SYSTEM?></li>
                    <li id='nc_subdivision_form_fields'><?=SUBDIVISION_TAB_INFO_TOOLBAR_EDIT_FIELDS?></li>
                </ul>
            </div>
            <div class='nc_admin_form_menu_hr'></div>
        </div>

        <script>
            var nc_slider_li = $nc('div#nc_object_slider_menu ul li');

            nc_slider_li.click(function() {
                nc_slider_li.removeClass('button_on');
                $nc(this).addClass('button_on');
                $nc('form#adminForm > div > div').addClass('nc_class_none');
                $nc('form#adminForm > div > div#' + this.id + '_div').removeClass('nc_class_none');
            });
        </script>

        <style>
            .nc_class_none, .hidden { display: none; }
        </style>

        <div class='nc_admin_form_body'>
<?php
}

function nc_subdivision_print_modal_suffix() {
?>
    </div>
        <div class='nc_admin_form_buttons'>
            <button type='button' class='nc_admin_metro_button nc-btn nc--blue' disable><?= NETCAT_REMIND_SAVE_SAVE; ?></button>
            <button type='button' class='nc_admin_metro_button_cancel nc-btn nc--red nc--bordered nc--right'><?= CONTROL_BUTTON_CANCEL ?></button>
        </div>

        <style>
            a { color:#1a87c2; }
            a:hover { text-decoration:none; }
            a img { border:none; }
            p { margin:0px; padding:0px 0px 18px 0px; }
            h2 { font-size:20px; font-family:'Segoe UI', SegoeWP, Arial; color:#333333; font-weight:normal; margin:0px; padding:20px 0px 10px 0px; line-height:20px; }
            form { margin:0px; padding:0px; }
            input { outline:none; }
            .clear { margin:0px; padding:0px; font-size:0px; line-height:0px; height:1px; clear:both; float:none; }
            select, input, textarea { border:1px solid #dddddd; }
            :focus { outline:none;}
            .input { outline:none; border:1px solid #dddddd; }
        </style>

        <script>
            var nc_admin_metro_buttons = $nc('.nc_admin_metro_button');

            $nc(function() {
                $nc('#adminForm').html('<div class="nc_admin_form_main">' + $nc('#adminForm').html() + '</div>');
            });

            nc_admin_metro_buttons.click(function() {
                $nc('#adminForm').submit();
            });

            $nc('.nc_admin_metro_button_cancel').click(function() {
				$nc.modal.close();
			});
        </script>
<?php
}

function nc_subdivision_save($view) {
    $result = true;
    $views = $view == 'all' ? array('edit', 'design', 'seo', 'system', 'fields') : (array) $view;

    foreach ($views as $view) {
        try {
            $result = call_user_func('nc_subdivision_form_'.$view.'_save') && $result;
        } catch (Exception $e) {
            nc_print_status($e->getMessage(), 'error');
            return null;
        }
    }

    return $result;
}

function nc_subdivision_show_access($SubEnv, $have_default = true) {
    $nc_core = nc_Core::get_object();

    $AccessType = array(
        0 => array(
            0 => 1,
            1 => CLASSIFICATOR_USERGROUP_ALL
        ),
        1 => array(
            0 => 2,
            1 => CLASSIFICATOR_USERGROUP_REGISTERED
        ),
        2 => array(
            0 => 3,
            1 => CLASSIFICATOR_USERGROUP_AUTHORIZED
        )
    );

    $actions = array(
            'Read',
            'Write',
            'Edit',
            'Checked',
            'Delete');

    if ($nc_core->modules->get_vars('subscriber', 'VERSION') == 1) {
        $actions[] = 'Subscribe';
    }


    $table = $nc_core->ui->table();
    $table->thead = $table->thead();

    $table->thead->th();
    if ($have_default) $table->thead->th(CONTROL_CONTENT_SUBDIVISION_FUNCS_ACCESS_INHERIT);
    $table->thead
        ->th(CLASSIFICATOR_USERGROUP_ALL)->text_center()->style('min-width:80px')
        ->th(CLASSIFICATOR_USERGROUP_REGISTERED)
        ->th(CLASSIFICATOR_USERGROUP_AUTHORIZED);

    foreach ($actions as $v) {


        $trow = $table->add_row();
        $trow->td( constant("CONTROL_CONTENT_SUBDIVISION_FUNCS_ACCESS_".strtoupper($v)) )->text_right();

        if($have_default) {
            $trow->td()
                ->label()->blocked()->text_center()
                    ->input('radio')->name("{$v}_Access_ID")->value(0)
                        ->checked( !$SubEnv['_db_'.$v."_Access_ID"] );
        }

        for ($i = 0; $i < count($AccessType); $i++) {
            $trow->td()->class_name("col_".($i + 3).($AccessType[$i][0] == $SubEnv[$v."_Access_ID"] ? ' nc-bg-lighten' : ''))
                ->label()->blocked()->text_center()
                    ->input('radio')
                        ->name("{$v}_Access_ID")
                        ->value($AccessType[$i][0])
                        ->checked( $AccessType[$i][0] == $SubEnv['_db_'.$v."_Access_ID"] );
        }
    }

    $radio_array = array();
    if($have_default) {
        $radio_array[] = array(
            'attr' => array('value' => '0'),
            'desc' => CONTROL_CONTENT_SUBDIVISION_FUNCS_ACCESS_INHERIT
        );
    }
    $radio_array[] = array(
        'attr' => array('value' => '1'),
        'desc' => CLASSIFICATOR_TYPEOFMODERATION_RIGHTAWAY
    );
    $radio_array[] = array(
        'attr' => array('value' => '2'),
        'desc' => CLASSIFICATOR_TYPEOFMODERATION_MODERATION
    );

    return (string)$table
        . '<br><div>' . CONTROL_CONTENT_SUBDIVISION_FUNCS_ACCESS_PUBLISH . '</div>'
        . nc_get_modal_radio('Moderation_ID', $radio_array, +$SubEnv["_db_Moderation_ID"]);
}

function nc_subclass_show_export($type, $sub_id, $cc_id, $show_name = 0, $from_sub = 0) {
    $nc_core = nc_Core::get_object();
    $db = $nc_core->db;

    $sub_id = +$sub_id;
    $cc_id = +$cc_id;

    if (!in_array($type, array('rss', 'xml'))) return;

    $Array = $db->get_row("SELECT `Allow".strtoupper($type)."`, `Sub_Class_Name`,`Class_ID`, `EnglishName` FROM `Sub_Class` WHERE `Sub_Class_ID` = '".$cc_id."' ", ARRAY_A);
    $class_id = $Array['Class_ID'];

    $name = $show_name ? "<b>".$Array['Sub_Class_Name']."</b> " : "";

    $export_class_id = $db->get_var("SELECT `Class_ID` FROM `Class` WHERE `Type` = '".$type."' AND `ClassTemplate` = '".$class_id."' ");
    $File_Mode = nc_get_file_mode('Class', $class_id);
    $html = "<div>";

    if (!$export_class_id) {
        $html .= "<font>".sprintf(constant("CONTROL_CLASS_COMPONENT_TEMPLATE_FOR_".strtoupper($type)."_DOESNT_EXIST"), $name)."</font>";
        $html .= "<br/>";
        $html .= "<a  onClick='parent.nc_form(this.href); location.reload(); return false;' href='".$nc_core->ADMIN_PATH."class/index.php?fs=$File_Mode&".$nc_core->token->get_url()."&amp;from_sub=".$sub_id.( $from_sub ? "" : "&amp;from_cc=".$cc_id)."&amp;Type=".$type."&amp;base=auto&amp;phase=1411&amp;ClassID=".$class_id."'>".constant("CONTROL_CLASS_COMPONENT_TEMPLATE_CREATE_FOR_".strtoupper($type))."</a>";
    } else {
        $link = $nc_core->SUB_FOLDER.$db->get_var("SELECT `Hidden_URL` FROM `Subdivision` WHERE `Subdivision_ID` = '".$sub_id."' ");
        $link = 'http://'.$_SERVER['HTTP_HOST'].$link.$Array['EnglishName'].".".$type;
        $html .= "<input type='checkbox' id='Allow".strtoupper($type)."".$cc_id."' name='Allow".strtoupper($type)."".$cc_id."' value='1' ".( $Array["Allow".strtoupper($type).""] ? " checked" : "")." /><label for='Allow".strtoupper($type)."".$cc_id."'>".constant("CONTROL_CLASS_COMPONENT_TEMPLATE_TURN_ON_".strtoupper($type))." ".$name."</label>";

        $html .= "  ( ";
        if ($Array["Allow".strtoupper($type).""])
                $html .= "<a target='_blank' href='".$link."'>".CONTROL_CLASS_COMPONENT_TEMPLATE_VIEW."</a>, ";
        $html .= " <a  onClick='parent.nc_form(this.href); return false; ' href='".$nc_core->ADMIN_PATH."class/index.php?fs=".$File_Mode."&phase=4&amp;ClassID=".$export_class_id."'>".CONTROL_CLASS_COMPONENT_TEMPLATE_EDIT."</a> )";
    }

    $html .= "</div>";


    return $html;
}

function nc_subdivision_form_edit($sub_env) {
    $nc_core = nc_Core::get_object();
    $sub_id = $sub_env['Subdivision_ID'];

    $html = nc_subdivision_moderate_form($sub_env);

    $field_main = new nc_admin_fieldset(CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA);
    $field_main->add(nc_admin_input(CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_NAME, 'Subdivision_Name', $sub_env, 50));
    $field_main->add(nc_admin_input(CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_KEYWORD, 'EnglishName', $sub_env, 50));

    $html .= $field_main->result();

    if (!$_REQUEST['isNaked'] && $_POST['view'] != 'all') {
        global $UI_CONFIG;
        $UI_CONFIG->updateTreeSubdivisionNode($sub_id, $sub_env["Subdivision_Name"], $sub_env["Checked"]);
    }
    return $html;
}

function nc_subdivision_form_design($sub_env, $CatalogueID = null, $display_type_settings = true) {
    global $perm, $loc;
    $nc_core = nc_Core::get_object();
    $db = $nc_core->db;
    $html = '';

    $Result = $db->get_results(
            "SELECT `Template_ID` as `value`,
                    CONCAT(`Template_ID`, '. ', `Description`) as `description`,
                    `Parent_Template_ID` as `parent`,
                    `File_Mode`
                 FROM `Template`
                     ORDER BY `Template_ID` ", ARRAY_A);
    if (empty($Result)) {
        $html .= CONTROL_USER_NOONESECSINSITE;
    } else {
        $field_design = new nc_admin_fieldset($CatalogueID ? null : CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_DTEMPLATE);
        // поиск названия используемого макета
        $File_Mode_IDs = array();
        foreach ($Result as $v) {
            if ($v['File_Mode']) {
                $File_Mode_IDs[] = $v['value'];
            }
            if ($v['value'] == $sub_env["_db_inherit_Template_ID"]) {
                $cur_template = CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_DTEMPLATE_N." [".$v['description']."]";
            }
        }

        $html .= "<select name='Template_ID' onchange='loadCustomTplSettings(0, ".($loc->SubdivisionID ? $loc->SubdivisionID : -1).", this.options[this.selectedIndex].value)'>\n";
        $html .= "<option ".($sub_env["_db_Template_ID"] ? "" : "selected='selected' ")."value='{$sub_env["_db_inherit_Template_ID"]}'>".$cur_template."</option>";
        $html .= nc_select_options($Result, $sub_env["_db_Template_ID"]);
        $html .= "</select>\n";
        // edit template in new window
        if ($perm->isSupervisor()) {
            $html .= "<input disabled='disabled' style='margin-left: 0px;' type='button' id='templateEditLink' style='margin-top: -25px; margin-left: 300px;' value='".CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_DTEMPLATE_EDIT."' title='".CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_DTEMPLATE_EDIT."'/>";
            $html .= "
                    <input name='is_parent_template' type='hidden' value='' />
                    <script>


                        var File_Mode_IDs = '|".join('|', $File_Mode_IDs)."|';
                    </script>";
        }
        $html .= nc_admin_checkbox(CONTROL_CONTENT_SUBDIVISION_FUNCS_USEEDITDESIGNTEMPLATE, 'UseEditDesignTemplate', $sub_env['UseEditDesignTemplate']);
        $field_design->add($html);
        $result = $field_design->result();

        $loc_id = $CatalogueID !== null ? $CatalogueID : $loc->SubdivisionID;
        $loc_id = $loc_id ? $loc_id : -1;

        if ($CatalogueID !== null) {
            $js_load_custom_settings = "loadCustomTplSettings($loc_id, 0, ". +$sub_env["Template_ID"].");";
        } else {
            $js_load_custom_settings = "loadCustomTplSettings(0, $loc_id, ". +$sub_env["Template_ID"].");";
        }

        $field_cs = new nc_admin_fieldset(CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_DTEMPLATE_CS);
        $html = "<br/><div id='customTplSettings'></div>";
        $html .= "<br/><div id='loadTplWait'><img src='".$nc_core->ADMIN_TEMPLATE."img/trash-loader.gif' alt='' /></div>";
        $html .= "<script type='text/javascript'>
                      setTimeout(function() {
                          $js_load_custom_settings
                      }, 250);
                  </script>\n";

        $field_cs->add($html);
        $result .= $field_cs->result();
    }

    if ($display_type_settings) {
        $display_type_fieldset = new nc_admin_fieldset(CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_DISPLAYTYPE);

        $displayTypeControls = nc_get_modal_radio('DisplayType', array(
            array(
                'attr' => array('value' => 'inherit'),
                'desc' => CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_DISPLAYTYPE_INHERIT),
            array(
                'attr' => array('value' => 'traditional'),
                'desc' => CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_DISPLAYTYPE_TRADITIONAL),
            array(
                'attr' => array('value' => 'shortpage'),
                'desc' => CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_DISPLAYTYPE_SHORTPAGE),
            array(
                'attr' => array('value' => 'longpage_vertical'),
                'desc' => CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_DISPLAYTYPE_LONGPAGE_VERTICAL)), $sub_env['DisplayType']);

        $display_type_fieldset->add($displayTypeControls);

        $result .= $display_type_fieldset->result();
    }

    return $result;
}

function nc_subdivision_form_seo($sub_env, $have_default = true) {
    $html = '';
    $nc_core = nc_Core::get_object();
    $sub_id = $sub_env['Subdivision_ID'];
    $field_index = new nc_admin_fieldset(CONTROL_CONTENT_SUBDIVISION_SEO_INDEXING);

    $lm = $nc_core->page->get_field_name('last_modified');
    $lm_type = $nc_core->page->get_field_name('last_modified_type');

    $m_search = 0;

    if ($nc_core->modules->get_by_keyword('search')) {
        $m_search = 1;
        $sm_field = $nc_core->page->get_field_name('sitemap_include');
        $sm_change_field = $nc_core->page->get_field_name('sitemap_changefreq');
        $sm_priority_field = $nc_core->page->get_field_name('sitemap_priority');
    }

    $domain = $sub_env['Catalogue_ID'] ? $nc_core->catalogue->get_by_id($sub_env['Catalogue_ID'], 'Domain') : '';
    if (!$domain) {
        $domain = $nc_core->DOMAIN_NAME;
    }

    if ($have_default) {
        $field_meta = new nc_admin_fieldset(CONTROL_CONTENT_SUBDIVISION_SEO_META);
        $mt = $nc_core->page->get_meta_tags("http://".$domain.$sub_env['Hidden_URL']);
        // title
        $title .= "<style> div#nc_subdivision_form_seo_div div.inf_block {padding: 3px; padding-top: 13px;} </style>";
        $title .= nc_admin_input(NETCAT_MODERATION_SEO_TITLE, 'title', $sub_env[$nc_core->page->get_field_name('title')], 50);

        if ($mt['title']) {
            $title .= CONTROL_CONTENT_SUBDIVISION_SEO_CURRENT_VALUE.": <b>".htmlspecialchars($mt['title'])."</b><br/><br />";
        }

        // keywords
        $html .= nc_admin_textarea(NETCAT_MODERATION_SEO_KEYWORDS, 'keywords', $sub_env[$nc_core->page->get_field_name('keywords')]);
        if ($mt['keywords']) {
            $html .= CONTROL_CONTENT_SUBDIVISION_SEO_CURRENT_VALUE.": <b>".htmlspecialchars($mt['keywords'])."</b><br/>";
        }

        // description
        $html .= nc_admin_textarea(NETCAT_MODERATION_SEO_DESCRIPTION, 'description', $sub_env[$nc_core->page->get_field_name('description')]);
        if ($mt['description']) {
            $html .= CONTROL_CONTENT_SUBDIVISION_SEO_CURRENT_VALUE.": <b>".htmlspecialchars($mt['description'])."</b><br/>";
        }

        $field_meta->add($html);
    }

    $html = "<div>" . CONTROL_CONTENT_SUBDIVISION_SEO_LAST_MODIFIED_HEADER . ":</div>";

    $radio_array = array();

    if ($have_default) {
        $radio_array[] =  array(
                'attr' => array(
                        'value' => '0'),
                'desc' => CONTROL_CONTENT_SUBDIVISION_FUNCS_ACCESS_INHERIT);
    }

    $radio_array[] = array(
            'attr' => array(
                    'value' => '1'),
            'desc' => CONTROL_CONTENT_SUBDIVISION_SEO_LAST_MODIFIED_NONE);
    $radio_array[] = array(
            'attr' => array(
                    'value' => '2'),
            'desc' => CONTROL_CONTENT_SUBDIVISION_SEO_LAST_MODIFIED_YESTERDAY);
    $radio_array[] = array(
            'attr' => array(
                    'value' => '3'),
            'desc' => CONTROL_CONTENT_SUBDIVISION_SEO_LAST_MODIFIED_HOUR);
    $radio_array[] = array(
            'attr' => array(
                    'value' => '4'),
            'desc' => CONTROL_CONTENT_SUBDIVISION_SEO_LAST_MODIFIED_CURRENT);
    $radio_array[] = array(
            'attr' => array(
                    'value' => '5'),
            'desc' => CONTROL_CONTENT_SUBDIVISION_SEO_LAST_MODIFIED_ACTUAL);

    $html .= nc_get_modal_radio('last_modified_type', $radio_array, +$sub_env['_db_'.$lm_type]);

    // Запрет индексации
    $html .= "
        <style>
        div.nc_table_indexing > div {
            border-bottom: 1px #cccccc solid;
            display: table;
        }

        div.nc_table_indexing > div > div {
            display: inline-block;
            padding-top: 9px;
            padding-bottom: 11px;
        }

        div.nc_table_indexing div.col_1 {
            width: 210px;
        }

        div.nc_table_indexing div.col_2 {
            text-align: center;
            width: 134px;
        }

        div.nc_table_indexing div.col_3 {
            text-align: center;
            width: 75px;
        }

        div.nc_table_indexing div.col_4 {
            text-align: center;
            width: 81px;
        }

        div.nc_table_indexing > div.row_1 {
            padding-top: 3px;
            padding-bottom: 2px;
        }

        div.nc_table_indexing div.col_checked {
            background-color: #eeeeee;
        }

        </style>

        <div class='nc_table_indexing'>
            <div class='row_1'>
                <div class='col_1'>
                </div>";

    if ($have_default) {
        $html .= "  <div class='col_2'>
                        ".CONTROL_CONTENT_SUBDIVISION_FUNCS_ACCESS_INHERIT."
                    </div>";
    }

    $html .= "  <div class='col_3'>
                    ".CONTROL_CONTENT_SUBDIVISION_SEO_DISALLOW_INDEXING_YES."
                </div>

                <div class='col_4'>
                    ".CONTROL_CONTENT_SUBDIVISION_SEO_DISALLOW_INDEXING_NO."
                </div>
            </div>

            <div>
                <div class='col_1'>
                    ".CONTROL_CONTENT_SUBDIVISION_SEO_DISALLOW_INDEXING."
                </div>";

    if ($have_default) {
        $html .= "  <div class='col_2".($sub_env['_db_DisallowIndexing'] == -1 ? " col_checked" : "")."'>
                        <input name='DisallowIndexing' type='radio' value='-1' ".($sub_env['_db_DisallowIndexing'] == -1 ? " checked='checked'" : "")." />
                    </div>";
    }

    $html .= "  <div class='col_3".($sub_env['_db_DisallowIndexing'] == 0 ? " col_checked" : "")."'>
                    <input name='DisallowIndexing' type='radio' value='0' ".($sub_env['_db_DisallowIndexing'] == 0 ? " checked='checked'" : "")." />
                </div>

                <div class='col_4".($sub_env['_db_DisallowIndexing'] == 1 ? " col_checked" : "")."'>
                    <input name='DisallowIndexing' type='radio' value='1' ".($sub_env['_db_DisallowIndexing'] == 1 ? " checked='checked'" : "")." />
                </div>
            </div>

            <div>
                <div class='col_1'>
                    ".CONTROL_CONTENT_SUBDIVISION_SEO_INCLUDE_IN_SITEMAP."
                </div>";

    if ($have_default) {
        $html .= "  <div class='col_2".($sub_env['_db_'.$sm_field] == -1 ? " col_checked" : "")."'>
                        <input name='sitemap_include' type='radio' value='-1' ".($sub_env['_db_'.$sm_field] == -1 ? " checked='checked'" : "")." />
                    </div>";
    }

    $html .= "  <div class='col_3".($sub_env['_db_'.$sm_field] == 1 ? " col_checked" : "")."'>
                    <input name='sitemap_include' type='radio' value='1' ".($sub_env['_db_'.$sm_field] == 1 ? " checked='checked'" : "")." />
                </div>

                <div class='col_4".($sub_env['_db_'.$sm_field] == 0 ? " col_checked" : "")."'>
                    <input name='sitemap_include' type='radio' value='0' ".($sub_env['_db_'.$sm_field] == 0 ? " checked='checked'" : "")." />
                </div>
            </div>
        </div><br />";

    // sitemap: частота изменения и приоритет страницы
    if ($m_search) {
        $changefreq = '';
        $prioritysel = '';

        if ($have_default && $sub_env['_db_inherit_'.$sm_change_field]) {
            $changefreq .= "<option value='-1'>".CONTROL_CONTENT_SUBDIVISION_FUNCS_ACCESS_INHERIT." [".constant("CONTROL_CONTENT_SUBDIVISION_SEO_SITEMAP_CHANGEFREQ_".strtoupper($sub_env['_db_inherit_'.$sm_change_field]))."]</option>";
            $prioritysel .= "<option name=''>".CONTROL_CONTENT_SUBDIVISION_FUNCS_ACCESS_INHERIT." [".sprintf("%.1f", $sub_env[$sm_priority_field])."]</option>";
        }

        foreach (array('always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never') as $v) {
            $changefreq .= "<option value='".$v."' ".( $sub_env['_db_'.$sm_change_field] == $v ? "selected" : "").">".constant("CONTROL_CONTENT_SUBDIVISION_SEO_SITEMAP_CHANGEFREQ_".strtoupper($v))."</option>";
        }

        for ($i = 1; $i >= 0; $i-= 0.1) {
            $prioritysel .= "<option value='".str_replace(',', '.', sprintf("%.1f", $i))."' ".( $sub_env['_db_'.$sm_priority_field] >= 0 && abs(doubleval($sub_env['_db_'.$sm_priority_field]) - $i) < 0.01 ? "selected" : "").">".sprintf("%.1f", $i)."</option>";
        }

        $html .= "
        <style>
            div.nc_table_sitemap > div {
                display: table;
            }

            div.nc_table_sitemap > div > div {
                display: inline-block;
            }

            div.nc_table_sitemap div.col_1 {
                width: 247px;
            }

            div.nc_table_sitemap div.col_2 {
                text-align: center;
            }
        </style>

        <div class='nc_table_sitemap'>
            <div>
                <div class='col_1'>
                    ".CONTROL_CONTENT_SUBDIVISION_SEO_SITEMAP_CHANGEFREQ."
                </div>

                <div class='col_2'>
                    <select name='sitemap_changefreq'>$changefreq</select>
                </div>
            </div>

            <div>
                <div class='col_1'>
                    ".CONTROL_CONTENT_SUBDIVISION_SEO_SITEMAP_PRIORITY."
                </div>

                <div class='col_2'>
                    <select name='sitemap_priority'>$prioritysel</select>
                </div>
            </div>
        </div>";
    }
    $field_index->add($html);
    $meta = '';
    if ($have_default) {
        $meta = $field_meta->result();
    }

    return $title.$meta.$field_index->result();
}

function nc_subdivision_moderate_form($env) {
    return "
        <div id='nc_seo_edit_info' class='nc_admin_settings_info'>
            <div class='nc_admin_settings_info_actions'>
                " . ($env['Created'] ?
                "<div>
                    <span>" . CLASS_TAB_CUSTOM_ADD . ":</span> {$env['Created']}
                </div>" : "") . "
                " . ($env['LastUpdated'] ?
                "<div>
                    <span>" . CLASS_TAB_CUSTOM_EDIT . ":</span> {$env['LastUpdated']}
                </div>" : "") . "
            </div>
			
            " . ($env['Created'] ?
                "<div class='nc_admin_settings_info_priority'>
                <div>
                    " . CONTROL_CONTENT_CATALOUGE_FUNCS_CATALOGUEFORM_PRIORITY . ":
                </div>

                <div>
                    <input type='text' name='Priority' size='3' value='{$env["Priority"]}'/>
                </div>
            </div>" : "") . "

            <div class='nc_admin_settings_info_checked'>
                <div>
                    <input id='turnon' type='checkbox' name='Checked' value='1' ".($env["Checked"] ? "checked='checked' " : "")." />
                    <label for='turnon'>".CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_TURNON."</label>
                </div>
            </div>

            <div class='nc_admin_settings_info_favorites'>
                <div>
                    <input id='fav' type='checkbox' name='Favorite' value='1' ".($env["Favorite"] == 1 ? "checked='checked' " : "")."/>
                    <label for='fav'>".CONTROL_CONTENT_SUBDIVISION_FUNCS_ADDFAVOTITES."</label>
                </div>
            </div>
        </div>";
}

function nc_subdivision_form_system($sub_env) {
    $nc_core = nc_Core::get_object();
    $sub_id = $sub_env['Subdivision_ID'];
    $lang_field = $nc_core->page->get_field_name('language');
    $sub_env = $nc_core->subdivision->get_by_id($sub_id);

    $field_main = new nc_admin_fieldset(CONTROL_CONTENT_SUBDIVISION_SYSTEM_FIELDS);
    $html = nc_admin_input(CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_EXTURL, 'ExternalURL', $sub_env['ExternalURL'], 50);
    $html .= nc_admin_input(CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_LANG, 'language', $sub_env['_db_'.$lang_field], 50);
    $html .= CONTROL_CONTENT_SUBDIVISION_SEO_CURRENT_VALUE.": <b>".$sub_env[$lang_field]."</b></font><br/><br/>";

    $field_main->add($html);

    // доступ
    $field_access = new nc_admin_fieldset(CONTROL_CONTENT_SUBDIVISION_FUNCS_ACCESS);
    $field_access->add(nc_subdivision_show_access($sub_env));

    $result = $field_main->result().$field_access->result();

    //rss
    global $isNaked;
    if ( !$isNaked ) {
		$all_cc_ids = $nc_core->db->get_col("SELECT `Sub_Class_ID` FROM `Sub_Class` WHERE `Subdivision_ID` = '".intval($sub_id)."' ");
		$count_cc = count($all_cc_ids);
		if ($count_cc) {
			$field_rss = new nc_admin_fieldset('RSS');
			foreach ($all_cc_ids as $cc_id) {
				$field_rss->add(nc_subclass_show_export('rss', $sub_id, $cc_id, ($count_cc > 1), 1));
			}
			$result .= $field_rss->result();
		}
	}

    // cache
    if ($nc_core->modules->get_by_keyword('cache')) {
        $field_cache = new nc_admin_fieldset(CONTROL_CONTENT_SUBCLASS_FUNCS_CACHE);
        $field_cache->add(nc_subdivision_show_cache($sub_env));
        $result .= $field_cache->result();
    }

    // comments
    if ($nc_core->modules->get_by_keyword('comments')) {
        include_once ($nc_core->MODULE_FOLDER."comments/function.inc.php");
        $field_comments = new nc_admin_fieldset(CONTROL_CONTENT_SUBDIVISION_FUNCS_COMMENTS);
        $field_comments->add(nc_subdivision_show_comments($sub_env));
        $result .= $field_comments->result();
    }

    return $result;
}

function nc_subdivision_form_fields($sub_env) {
    $nc_core = nc_Core::get_object();
    $sub_id = $sub_env['Subdivision_ID'];
    global $db;
    global $systemTableID, $systemMessageID, $systemTableName;
    $action = "change";
    $systemMessageID = $message = $sub_id;
    echo "<br/>";
    require $nc_core->ROOT_FOLDER."message_fields.php";

    if ($fldCount) {
        $fieldQuery = join($fld, ",");
        $fldValue = $nc_core->db->get_row("SELECT ".$fieldQuery." FROM `Subdivision` WHERE `Subdivision_ID` = '".intval($sub_id)."'", ARRAY_N);
    }
    else {
		nc_print_status(CONTROL_CONTENT_SUBDIVISION_SYSTEM_FIELDS_NO, 'info');
	}

    require $nc_core->ROOT_FOLDER."message_edit.php";
}

function nc_subdivision_show_cache($env, $have_default = true) {
    $html = "<div>".CONTROL_CONTENT_SUBDIVISION_FUNCS_CACHE_STATUS."</div>";

    $radio_array = array();

    if ($have_default) {
        $radio_array[] = array(
                'attr' => array(
                        'value' => '0'),
                'desc' => CONTROL_CONTENT_SUBDIVISION_FUNCS_ACCESS_INHERIT
            );
    }

    $radio_array['ca'] = array(
            'attr' => array(
                    'value' => '1'
                ),
            'desc' => CONTROL_CONTENT_SUBDIVISION_FUNCS_CACHE_ALLOW);

    $radio_array['cd'] = array(
            'attr' => array(
                    'value' => '2'
                ),
            'desc' => CONTROL_CONTENT_SUBDIVISION_FUNCS_CACHE_DENY);

    $html .= nc_get_modal_radio('Cache_Access_ID', $radio_array, +$env["Cache_Access_ID"]);

    $html .= "<div>
                  ".CONTROL_CONTENT_SUBDIVISION_FUNCS_CACHE_LIFETIME.":
              </div>";
    $html .= "<div class='col_input_text'>
                  <input name='Cache_Lifetime' id='Cache_Lifetime' type='text' value='".($env['Cache_Lifetime'] ? $env['Cache_Lifetime'] : ($env['_db_Cache_Lifetime'] ? $env['_db_Cache_Lifetime'] : 0))."' ".((!$env["_db_Cache_Access_ID"] || ('1' != $env['Cache_Access_ID'])) ? " disabled" : "")." />
              </div>";

    return $html;
}

function nc_subdivision_show_comments($env) {
    require_once nc_Core::get_object()->MODULE_FOLDER . 'comments/function.inc.php';

    $db = nc_Core::get_object()->db;
    $AccessType = array(
            0 => array(
                    0 => 1,
                    1 => CLASSIFICATOR_USERGROUP_ALL),
            1 => array(
                    0 => 2,
                    1 => CLASSIFICATOR_USERGROUP_REGISTERED),
            2 => array(
                    0 => 3,
                    1 => CLASSIFICATOR_USERGROUP_AUTHORIZED),
            3 => array(
                    0 => 4,
                    1 => CLASSIFICATOR_COMMENTS_DISABLE)
    );

    $comments_data = nc_comments::getRuleData($db, array($env['Catalogue_ID'], $env['Subdivision_ID'], $env['Sub_Class_ID']));

    $parent_comment_rule = $env['Comment_Rule_ID'] ? $db->get_row("SELECT * FROM `Comments_Rules` WHERE `ID` = ".$env['Comment_Rule_ID'], ARRAY_A) : array();

    $comments_change_variants = array(
            'disable' => CLASSIFICATOR_COMMENTS_DISABLE,
            'enable' => CLASSIFICATOR_COMMENTS_ENABLE,
            'unreplied' => CLASSIFICATOR_COMMENTS_NOREPLIED);

    $hide_inherit_radio = !isset($env['Parent_Sub_ID']);

    $html = "<script type='text/javascript'>
        \$nc(function() {
         \$nc('input[name=CommentAccessID]').click(function(){
             var radioValue = \$nc('input[name=CommentAccessID]:checked').val();
             var inputs = \$nc('#CommentsEditRules, #CommentsDeleteRules');

             if (0 == radioValue) {
                 inputs.attr('disabled', 'disabled');
             } else {
                 inputs.removeAttr('disabled');
             }

         });

         ";
    if ($comments_data["Access_ID"] == 0) {
        $html .= "
            \$nc('#CommentsEditRules, #CommentsDeleteRules').attr('disabled', 'disabled');
        ";
    }

    $html .= "
     });
    </script>
    <style>
        div.nc_table_comments > div {
            border-bottom: 1px #cccccc solid;
            display: table;
        }

        div.nc_table_comments > div > div {
            display: inline-block;
            padding-top: 9px;
            padding-bottom: 11px;
        }

        div.nc_table_comments div.col_1 {
            width: 214px;
        }

        div.nc_table_comments div.col_2 {
            text-align: center;
            width: 134px;
        }

        div.nc_table_comments div.col_3 {
            text-align: center;
            width: 61px;
        }

        div.nc_table_comments div.col_4 {
            text-align: center;
            width: 180px;
        }

        div.nc_table_comments div.col_5 {
            text-align: center;
            width: 158px;
        }

        div.nc_table_comments div.col_6 {
            text-align: center;
            width: 116px;
        }

        div.nc_table_comments > div.row_1 {
            padding-top: 3px;
            padding-bottom: 2px;
        }

        div.nc_table_comments div.col_checked {
            background-color: #eeeeee;
        }

    </style>";

    $comments_radio = array();
    $comments_radio['inherit'] = array(
                    'attr' => array(
                            'value' => '0'),
                    'desc' => CONTROL_CONTENT_SUBDIVISION_FUNCS_ACCESS_INHERIT);
    $comments_radio += array(
            array(
                    'attr' => array(
                            'value' => '1'),
                    'desc' => CLASSIFICATOR_USERGROUP_ALL),

            array(
                    'attr' => array(
                            'value' => '2'),
                    'desc' => CLASSIFICATOR_USERGROUP_REGISTERED),
            array(
                    'attr' => array(
                            'value' => '3'),
                    'desc' => CLASSIFICATOR_USERGROUP_AUTHORIZED),
            array(
                    'attr' => array(
                            'value' => '4'),
                    'desc' => CLASSIFICATOR_COMMENTS_DISABLE));

    if ($hide_inherit_radio) {
        unset($comments_radio['inherit']);
    }

    $html .= "<div>".CONTROL_CONTENT_SUBDIVISION_FUNCS_COMMENTS_ADD."</div>";
    $html .= nc_get_modal_radio('CommentAccessID', $comments_radio, +$comments_data["Access_ID"]);

    $html .= "<br/>";

    $html .= "
        <style>
        div.nc_table_content > div > div {
            display: inline-block;
        }

        div.nc_table_content div.col_1 {
            width: 244px;
        }
        </style>

        <div class='nc_table_content'>
            <div>
                <div class='col_1'>
                    ".CONTROL_CONTENT_SUBDIVISION_FUNCS_COMMENTS_AUTHOR_EDIT."
                </div>

                <div class='col_2'>";

    $html .= "      <select name='CommentsEditRules' id='CommentsEditRules' ". ($comments_data["Access_ID"] > 0 ? '' : 'disabled ') .">";
    foreach ($comments_change_variants AS $key => $value) {
        switch (true) {
            case empty($comments_data) && empty($parent_comment_rule) && $key == "disable":
                $opt_selected = " selected";
                break;
            case empty($comments_data) && $parent_comment_rule['Edit_Rule'] == $key:
                $opt_selected = " selected";
                break;
            case $comments_data['Edit_Rule'] == $key:
                $opt_selected = " selected";
                break;
            default:
                $opt_selected = "";
        }
        $html .= "      <option value='$key'$opt_selected>$value</option>";
    }

    $html .= "      </select>

                </div>
            </div>

            <div>
                <div class='col_1'>
                    ".CONTROL_CONTENT_SUBDIVISION_FUNCS_COMMENTS_AUTHOR_DELETE."
                </div>

                <div class='col_2'>";
    $html .= "      <select name='CommentsDeleteRules' id='CommentsDeleteRules'". ($comments_data["Access_ID"] > 0 ? '' : 'disabled ') .">";
    foreach ($comments_change_variants AS $key => $value) {
        switch (true) {
            case empty($comments_data) && empty($parent_comment_rule) && $key == "disable":
                $opt_selected = " selected";
                break;
            case empty($comments_data) && $parent_comment_rule['Delete_Rule'] == $key:
                $opt_selected = " selected";
                break;
            case $comments_data['Delete_Rule'] == $key:
                $opt_selected = " selected";
                break;
            default:
                $opt_selected = "";
        }
        $html .= "      <option value='$key'$opt_selected>$value</option>\r\n";
    }
    $html .= "      </select>
                </div>
            </div>
        </div>";
    return $html;
}

function nc_subdivision_form_edit_save() {
    $nc_core = nc_Core::get_object();
    $sub_id = intval($nc_core->input->fetch_get_post('SubdivisionID'));
    // информация о разделе
    $parent = $nc_core->subdivision->get_by_id($sub_id, 'Parent_Sub_ID');
    $cat_id = $nc_core->subdivision->get_by_id($sub_id, 'Catalogue_ID');
    $cur_checked = $nc_core->subdivision->get_by_id($sub_id, 'Checked');

    // проверка названия раздела
    $Subdivision_Name = trim($nc_core->input->fetch_get_post('Subdivision_Name'));
    if (!$Subdivision_Name) {
        throw new Exception(CONTROL_CONTENT_SUBDIVISION_INDEX_ERROR_THREE_NAME);
    }
    // проверка ключевого слова
    $EnglishName = trim($nc_core->input->fetch_get_post('EnglishName'));
    if (!$nc_core->subdivision->validate_english_name($EnglishName)) {
        throw new Exception(CONTROL_CONTENT_SUBDIVISION_SUBCLASS_ERROR_KEYWORD_INVALID);
    }
    // проверка уникальности ключевого слова
    if (!IsAllowedSubdivisionEnglishName($EnglishName, $parent, $sub_id, $cat_id)) {
        throw new Exception(CONTROL_CONTENT_SUBDIVISION_INDEX_ERROR_THREE_KEYWORD);
    }

    // визуальные настройки

    $CustomSettings = "";
    $custom_subclass_id = intval($nc_core->input->fetch_get_post('custom_subclass_id'));
    $custom_class_id = intval($nc_core->input->fetch_get_post('custom_class_id'));

    if ($custom_subclass_id) {
        $SQL = "SELECT `CustomSettingsTemplate`
                    FROM `Class`
                        WHERE `Class_ID` = " . +$custom_class_id;
        $settings = $nc_core->db->get_var($SQL);
        if ($settings) {
            require_once($nc_core->ADMIN_FOLDER."array_to_form.inc.php");
            $a2f = new nc_a2f($settings, 'CustomSettings');

            if (!$a2f->validate($_POST['CustomSettings'])) {
                throw new Exception($a2f->get_validation_errors());
            } else {
                $a2f->save($_POST['CustomSettings']);
                $CustomSettings = $a2f->get_values_as_string();
                $SQL = "SELECT `CustomSettings` FROM `Sub_Class`
                                  WHERE `Sub_Class_ID` = " . +$custom_subclass_id;
                $cur_settings = $nc_core->db->get_var($SQL);

                if ($CustomSettings <> $cur_settings) {
                    $nc_core->event->execute("updateSubClassPrep", $cat_id, $sub_id, $custom_subclass_id);

                    $nc_core->db->query("UPDATE `Sub_Class` SET `CustomSettings` = '".$nc_core->db->escape($CustomSettings)."'
                  WHERE `Sub_Class_ID` = '".$custom_subclass_id."'");
                    $nc_core->event->execute("updateSubClass", $cat_id, $sub_id, $custom_subclass_id);
                }
            }
        }
    }

    // параметры для обновления
    $params = array('Priority', 'Checked', 'Favorite');
    $fields = array('EnglishName' => $EnglishName, 'Subdivision_Name' => $Subdivision_Name);
    foreach ($params as $v) {
        $fields[$v] = $nc_core->input->fetch_get_post($v);
    }

    $Checked = intval($nc_core->input->fetch_get_post('Checked'));

    if ($cur_checked != $Checked) {
        $nc_core->event->execute($Checked ? "checkSubdivisionPrep" : "uncheckSubdivisionPrep", $cat_id, $sub_id);
    }

    $nc_core->subdivision->update($sub_id, $fields);

    // обновим Hidden_URL
    $hidden_url = GetHiddenURL($parent);
    UpdateHiddenURL($hidden_url ? $hidden_url : "/", $parent, $cat_id);

    // произошло включение / выключение
    if ($cur_checked != $Checked) {
        $nc_core->event->execute($Checked ? "checkSubdivision" : "uncheckSubdivision", $cat_id, $sub_id);
    }

    return true;
}

function nc_subdivision_form_design_save() {
    $nc_core = nc_Core::get_object();
    $sub_id = intval($nc_core->input->fetch_get_post('SubdivisionID'));
    $Template_ID = intval($nc_core->input->fetch_get_post('Template_ID'));
    $UseEditDesignTemplate = intval($nc_core->input->fetch_get_post('UseEditDesignTemplate'));

    // визуальные настройки
    $TemplateSettings = "";
    if ($Template_ID) {
        $settings = $nc_core->db->get_var("SELECT `CustomSettings` FROM `Template` WHERE `Template_ID` = '".$Template_ID."'");
        if ($settings && ($_POST['TemplateSettings'] || $_FILES['TemplateSettings'])) {
            require_once($nc_core->ADMIN_FOLDER."array_to_form.inc.php");
            $a2f = new nc_a2f($settings, 'TemplateSettings');
            if (!$a2f->validate($_POST['TemplateSettings'])) {
                throw new Exception($a2f->get_validation_errors());
            }
            $a2f->save($_POST['TemplateSettings']);
            $TemplateSettings = $a2f->get_values_as_string();
        }
    }

    $DisplayType = $nc_core->input->fetch_get_post('DisplayType');

    // обновление раздела
    $is_parent_template = $_POST['is_parent_template'] == 'true' ? true : false;
    $fields = array('Template_ID' => $is_parent_template ? '' : $Template_ID, 'TemplateSettings' => $TemplateSettings, 'UseEditDesignTemplate' => $UseEditDesignTemplate, 'DisplayType' => $DisplayType);
    $nc_core->subdivision->update($sub_id, $fields);

    return true;
}

function nc_subdivision_form_seo_save() {
    $nc_core = nc_Core::get_object();
    $sub_id = intval($nc_core->input->fetch_get_post('SubdivisionID'));

    // метатэги
    $metatags = array('title', 'keywords', 'description');
    foreach ($metatags as $v) {
        $user_value[$v] = $nc_core->input->fetch_get_post($v);
        $fields[$nc_core->page->get_field_name($v)] = $user_value[$v];
    }

    // запрет индексации и настройки sitemap'a
    $fields['DisallowIndexing'] = intval($nc_core->input->fetch_get_post('DisallowIndexing'));
    $fields[$nc_core->page->get_field_name('last_modified_type')] = $nc_core->input->fetch_get_post('last_modified_type');
    if ($nc_core->modules->get_by_keyword('search')) {
        foreach (array('sitemap_include', 'sitemap_changefreq', 'sitemap_priority') as $v) {
            $fields[$nc_core->page->get_field_name($v)] = $nc_core->input->fetch_get_post($v);
        }
    }
    $nc_core->subdivision->update($sub_id, $fields);

    // проверка
    $hidden_url = $nc_core->subdivision->get_by_id($sub_id, 'Hidden_URL');
    $real_value = $nc_core->page->get_meta_tags("http://".$nc_core->DOMAIN_NAME.$hidden_url);
    foreach ($metatags as $v) {
        if ($real_value[$v] && $user_value[$v] && $user_value[$v] != $real_value[$v]) {
            nc_print_status(sprintf(CONTROL_CONTENT_SUBDIVISION_SEO_VALUE_NOT_SETTINGS, $v, $v), 'info');
        }
    }

    return true;
}

function nc_subdivision_form_system_save() {
    $nc_core = nc_Core::get_object();
    $sub_id = intval($nc_core->input->fetch_get_post('SubdivisionID'));
    $cat_id = $nc_core->subdivision->get_by_id($sub_id, 'Catalogue_ID');

    $params = array('ExternalURL', 'Read_Access_ID', 'Write_Access_ID',
            'Edit_Access_ID', 'Checked_Access_ID', 'Delete_Access_ID', 'Moderation_ID');
    if ($nc_core->modules->get_by_keyword('cache')) {
        $params[] = 'Cache_Access_ID';
        $params[] = 'Cache_Lifetime';
    }

    foreach ($params as $v) {
        $fields[$v] = $nc_core->input->fetch_get_post($v);
    }
    // язык
    $fields[$nc_core->page->get_field_name('language')] = $nc_core->input->fetch_get_post('language');

    // комментарии
    if ($nc_core->modules->get_by_keyword("comments")) {
        include_once ($nc_core->MODULE_FOLDER."comments/function.inc.php");
        $CommentAccessID = $nc_core->input->fetch_get_post('CommentAccessID');
        $CommentsEditRules = $nc_core->input->fetch_get_post('CommentsEditRules');
        $CommentsDeleteRules = $nc_core->input->fetch_get_post('CommentsDeleteRules');
        // get rule id
        $CommentData = nc_comments::getRuleData($nc_core->db, array($cat_id, $sub_id));
        $CommentRelationID = $CommentData['ID'];
        // do something
        switch (true) {
            case $CommentAccessID > 0 && $CommentRelationID:
                // update comment rules
                nc_comments::updateRule($nc_core->db, array($cat_id, $sub_id), $CommentAccessID, $CommentsEditRules, $CommentsDeleteRules);
                break;
            case $CommentAccessID > 0 && !$CommentRelationID:
                // add comment relation
                $CommentRelationID = nc_comments::addRule($nc_core->db, array($cat_id, $sub_id), $CommentAccessID, $CommentsEditRules, $CommentsDeleteRules);
                break;
            case $CommentAccessID <= 0 && $CommentRelationID:
                // delete comment rules
                nc_comments::dropRuleSubdivision($nc_core->db, $sub_id);
                $CommentRelationID = 0;
                break;
        }
        $fields['Comment_Rule_ID'] = $CommentRelationID;
    }

    // RSS
    $cc_in_sub = $nc_core->db->get_results("SELECT `Sub_Class_ID` as `id`, `AllowRSS` as `cur` FROM `Sub_Class` WHERE `Subdivision_ID` = '".$sub_id."' ", ARRAY_A);
    if (!empty($cc_in_sub))
            foreach ($cc_in_sub as $v) {
            // значение, пришедшие из формы
            $allow_rss = intval($nc_core->input->fetch_get_post('AllowRSS'.$v['id']));
            // в случае, если значение изменилось
            if ($allow_rss != $v['cur']) {
                $nc_core->event->execute("updateSubClassPrep", $cat_id, $sub_id, $v['id']);
                $nc_core->db->query("UPDATE `Sub_Class` SET `AllowRSS` = '".$allow_rss."' WHERE `Sub_Class_ID` = '".$v['id']."' ");
                $nc_core->event->execute("updateSubClass", $cat_id, $sub_id, $v['id']);
            }
        }

    $nc_core->subdivision->update($sub_id, $fields);
    return true;
}

function nc_subdivision_form_fields_save() {
    // глобальные переменные нужны в файлах message_put, message_fields
    global $systemTableID, $systemTableName, $systemMessageID, $message, $db;
    global $FILES_FOLDER, $HTTP_FILES_PATH, $SUB_FOLDER, $DIRCHMOD;

    $nc_core = nc_Core::get_object();
    $sub_id = intval($nc_core->input->fetch_get_post('SubdivisionID'));
    $posting = 1;
    $systemMessageID = $message = $sub_id;
    $action = "change";

    $st = new nc_Component(0, 2);
    foreach ($st->get_fields() as $v) {
        $name = 'f_'.$v['name'];
        global $$name;
        if ($v['type'] == 6) {
            global ${$name."_old"};
            global ${"f_KILL".$v['id']};
        }
    }

    require $nc_core->ROOT_FOLDER."message_fields.php";

    if (!$posting) {
        echo $warnText;
        return false;
    }

    require $nc_core->ROOT_FOLDER."message_put.php";

    $update = "UPDATE `Subdivision` SET ";
    $update_inherited = array();
    for ($i = 0; $i < $fldCount; $i++) {
        if ($fldTypeOfEdit[$i] == 3) continue; // поле недоступно никому
        if ($fldInheritance[$i] == 1) {
            $update_inherited[$fld[$i]] = $fldValue[$i];
        }
        $update.= "`".$fld[$i]."` = ".$fldValue[$i].", ";
    }
    $update.= " `Checked` = `Checked` WHERE `Subdivision_ID` = '".$sub_id."'";
    $db->query($update);

    if(!empty($update_inherited)) {
        $SQL = "UPDATE `Subdivision` SET ";
        foreach ($update_inherited as $key => $value) {
            $update_inherited[$key] = "`".$key."` = ".$value;
        }
        $SQL .= implode(', ', $update_inherited)." WHERE `Parent_Sub_ID` = '".$sub_id."'";
        $db->query($SQL);
    }

    // default value
    if (!isset($nc_core->DIRCHMOD)) $nc_core->DIRCHMOD = 0777;
    // create dir
    @mkdir($nc_core->FILES_FOLDER.$message."/", $nc_core->DIRCHMOD);

    for ($i = 0; $i < count($tmpFile); $i++) {

        eval("\$tmpNewFile[\$i] = \"".$tmpNewFile[$i]."\";");
        eval("\$File_PathNew[\$i] = \"".$File_PathNew[$i]."\";");
        eval("\$File_Path[\$i] = \"".$File_Path[$i]."\";");

        if ($fld_name[$i] /* && $type == 1 */) {
            $db->query("UPDATE `Subdivision`
       SET `".$fld_name[$i]."` = REPLACE(".$fld_name[$i].", '\$message', Subdivision_ID )
       WHERE Subdivision_ID = '".$message."'");
            if(!empty($update_inherited)) {
                $sql = "UPDATE `Subdivision`
       SET `".$fld_name[$i]."` = REPLACE(".$fld_name[$i].", '\$message', Parent_Sub_ID )
       WHERE Parent_Sub_ID = '".$message."'";
                $db->query($sql);
            }
        }

        if ($FileFS[$i] == NC_FS_PROTECTED || $FileFS[$i] == NC_FS_ORIGINAL) {
            @rename($nc_core->FILES_FOLDER.$tmpNewFile[$i], $nc_core->FILES_FOLDER.$File_PathNew[$i].$tmpNewFile[$i]);
        } else {
            @rename($nc_core->FILES_FOLDER.$tmpFile[$i], $nc_core->FILES_FOLDER.$File_Path[$i].$tmpNewFile[$i]);
        }

        @chmod($nc_core->FILES_FOLDER.$File_PathNew[$i].$tmpNewFile[$i], $nc_core->FILECHMOD);
    }

    if (!empty($filetable_lastid)) {
        $db->query("UPDATE `Filetable`
          SET `Message_ID` = '".$message."', `File_Path` = '/".$message."/'
          WHERE `ID`  IN(".join(',', $filetable_lastid).")");
    }


    $nc_core->subdivision->update($sub_id, array('Subdivision_ID' => $sub_id));

    return true;
}

function nc_subdivision_show_add_form($catalogue_id = 0, $parent_sub_id = 0) {
    $nc_core = nc_Core::get_object();
    $db = $nc_core->db;

    if (($parent_sub_id = intval($parent_sub_id))) {
        $catalogue_id = $nc_core->subdivision->get_by_id($parent_sub_id, 'Catalogue_ID');
    }
    $catalogue_id = intval($catalogue_id);

    echo "<form action='index.php' method='post' enctype='multipart/form-data' >";
    echo "<input type='hidden' name='CatalogueID' value='".intval($catalogue_id)."' />";
    echo "<input type='hidden' name='ParentSubID' value='".intval($parent_sub_id)."' />";
    echo "<input type='hidden' name='phase' value='3' />";
    echo "<input type='hidden' name='posting' value='1' />";
    echo "<input type='submit' class='hidden' />";
    echo nc_Core::get_object()->token->get_input();

    $Priority = $db->get_var("SELECT (Priority+1) FROM Subdivision WHERE Parent_Sub_ID='".$parent_sub_id."' AND Catalogue_ID='".$catalogue_id."' ORDER BY Priority DESC LIMIT 1");
    echo nc_subdivision_moderate_form(array('Checked' => 1, 'Priority' => $Priority + 0));

    $field_main = new nc_admin_fieldset(CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA);
    $field_main->add(nc_admin_input(CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_NAME, 'Subdivision_Name', $nc_core->input->fetch_get_post('Subdivision_Name'), 50));
    $field_main->add(nc_admin_input(CONTROL_CONTENT_SUBDIVISION_FUNCS_MAINDATA_KEYWORD, 'EnglishName', $nc_core->input->fetch_get_post('EnglishName'), 50));
    echo $field_main->result();

	echo nc_subdivision_show_component($catalogue_id);

    $template_id = $parent_sub_id ? $nc_core->subdivision->get_by_id($parent_sub_id, 'Template_ID') : $nc_core->catalogue->get_by_id($catalogue_id, 'Template_ID');
    echo nc_subdivision_form_design(array('_db_inherit_Template_ID' => $template_id));

    echo "</form>";
}

function nc_subdivision_show_component($catalogue_id = 0) {
    $nc_core = nc_Core::get_object();
    $Class_ID = intval($nc_core->input->fetch_get_post('Class_ID'));
    $Class_Template_ID = intval($nc_core->input->fetch_get_post('Class_Template_ID'));
    $CatalogueID = (intval($nc_core->input->fetch_get_post('CatalogueID')) ? intval($nc_core->input->fetch_get_post('CatalogueID')) : $catalogue_id);

    $db = $nc_core->db;

    $sql = "SELECT `Class_ID` as value, " .
        "CONCAT(`Class_ID`, '. ', `Class_Name`) as description, " .
        "`Class_Group` as optgroup " .
        "FROM `Class` " .
        "WHERE `ClassTemplate` = 0 AND File_Mode = 0 " .
        "ORDER BY `Class_Group`, `Class_ID`";
    $classesV4 = (array)$db->get_results($sql, ARRAY_A);

    $sql = "SELECT `Class_ID` as value, " .
        "CONCAT(`Class_ID`, '. ', `Class_Name`) as description, " .
        "`Class_Group` as optgroup " .
        "FROM `Class` " .
        "WHERE `ClassTemplate` = 0 AND File_Mode = 1 " .
        "ORDER BY `Class_Group`, `Class_ID`";
    $classesV5 = (array)$db->get_results($sql, ARRAY_A);

    $field_class = new nc_admin_fieldset(CONTROL_USER_FUNCS_CLASSINSECTION);

    $html = "\t<table border='0' cellpadding='6' cellspacing='0' width='100%'><tr><td>\n";
    $html .= "\t<table border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td>\n";

    if (!empty($classesV4) || !empty($classesV5)) {
        $html .= "<font color=gray>".CONTROL_CLASS_CLASS.":<br>";
        $html .= "<select id='Class_ID' name='Class_ID' onchange='if (this.options[this.selectedIndex].value) {loadClassDescription(this.options[this.selectedIndex].value); loadClassCustomSettings(this.options[this.selectedIndex].value); loadClassTemplates(this.options[this.selectedIndex].value".($CatalogueID ? ", 0, ".$CatalogueID : "").");}'>";
        $html .= "<option value='0'".(!$Class_ID ? " selected" : "").">".NOT_ELSEWHERE_SPECIFIED."</option>\n";
        if (!empty($classesV5)) {
            $html .= "<option disabled='disabled'>" . CONTROL_CLASS . " v5</option>\n";
            $html .= nc_select_options($classesV5, $Class_ID);
        }
        if (!empty($classesV4)) {
            $html .= "<option disabled='disabled'>" . CONTROL_CLASS . " v4</option>\n";
            $html .= nc_select_options($classesV4, $Class_ID);
        }
        $html .= "</select>";
        $html .= "<div id='loadClassDescription'></div>";
        $html .= "<script type='text/javascript'>if (".($Class_ID + 0).") loadClassDescription(".$Class_ID.")</script>";
        $html .= "<div id='loadClassTemplates'></div>";
        $html .= "<script type='text/javascript'>if (".($Class_ID + 0).") {loadClassTemplates(".$Class_ID.($Class_Template_ID ? ", ".$Class_Template_ID : ", 0").($CatalogueID ? ", ".$CatalogueID : "").");}</script>";
    } else {
        $html .= CONTROL_CLASS_NONE;
    }
    $html .= "\t</table>\n";

    $html .= "\t</td></tr></table>\n";
    $html .= "</fieldset><br>\n";

    if (!empty($classesV4) || !empty($classesV5)) {
        $html .= "<div id='loadClassCustomSettings'></div>";
        $html .= "<script type='text/javascript'>if (".($Class_ID + 0).") loadClassCustomSettings(".($Class_Template_ID ? $Class_Template_ID : $Class_ID).")</script>";
    }
    $field_class->add($html);

    return $field_class->result($html);
}

function nc_subdivision_add() {
    $nc_core = nc_Core::get_object();
    $db = $nc_core->db;
    $CatalogueID = intval($nc_core->input->fetch_get_post('CatalogueID'));
    $ParentSubID = intval($nc_core->input->fetch_get_post('ParentSubID'));
    $Template_ID = intval($nc_core->input->fetch_get_post('Template_ID'));

    $input = $nc_core->input->fetch_get_post();

    // проверка названия раздела
    $Subdivision_Name = trim($nc_core->input->fetch_get_post('Subdivision_Name'));
    if (!$Subdivision_Name) {
        throw new Exception(CONTROL_CONTENT_SUBDIVISION_INDEX_ERROR_THREE_NAME);
    }
    // проверка ключевого слова
    $EnglishName = trim($nc_core->input->fetch_get_post('EnglishName'));
    if (!$nc_core->subdivision->validate_english_name($EnglishName)) {
        throw new Exception(CONTROL_CONTENT_SUBDIVISION_SUBCLASS_ERROR_KEYWORD_INVALID);
    }
    // проверка уникальности ключевого слова
    if (!IsAllowedSubdivisionEnglishName($EnglishName, $ParentSubID, 0, $CatalogueID)) {
        throw new Exception(CONTROL_CONTENT_SUBDIVISION_INDEX_ERROR_THREE_KEYWORD);
    }

    // визуальные настройки
    $TemplateSettings = "";

    if ($_POST['is_parent_template'] == 'true') {
        $Template_ID = 0;
    }

    if ($Template_ID) {
        $settings = $nc_core->db->get_var("SELECT `CustomSettings` FROM `Template` WHERE `Template_ID` = '".$Template_ID."'");
        if ($settings) {
            require_once($nc_core->ADMIN_FOLDER."array_to_form.inc.php");
            $a2f = new nc_a2f($settings, 'TemplateSettings');
            if (!$a2f->validate($_POST['TemplateSettings'])) {
                throw new Exception($a2f->get_validation_errors());
            }
            $a2f->save($_POST['TemplateSettings']);
            $TemplateSettings = $a2f->get_values_as_string();
        }
    }

    // execute core action
    $nc_core->event->execute("addSubdivisionPrep", $CatalogueID, 0);

    // добавление раздела
    $db->query("
        INSERT INTO `Subdivision`
            SET `Created` = NOW(),
                `Subdivision_Name` = '".$db->escape($Subdivision_Name)."',
                `EnglishName` = '".$db->escape($EnglishName)."',
                `Parent_Sub_ID` = '".$ParentSubID."',
                `Catalogue_ID` = '".$CatalogueID."',
                `Checked` = '".intval($input['Checked'])."',
                `Priority` = '".intval($input['Priority'])."',
                `Favorite` = '".intval($input['Favorite'])."',
                `UseMultiSubClass` = 1,
                `Template_ID` = '".$Template_ID."',
                `TemplateSettings` = '".$db->escape($TemplateSettings)."',
                `UseEditDesignTemplate` = '".intval($input['UseEditDesignTemplate'])."'");
    if ($db->is_error) {
        throw new nc_Exception_DB_Error($db->last_query, $db->last_error);
    }
    $SubdivisionID = $db->insert_id;

    // обновим Hidden_URL
    $hidden_url = GetHiddenURL($ParentSubID);
    UpdateHiddenURL($hidden_url ? $hidden_url : "/", $ParentSubID, $CatalogueID);

    $nc_core->event->execute("addSubdivision", $CatalogueID, $SubdivisionID);


    // добавление компонента в разделе
    $Class_ID = intval($input['Class_ID']);
    $Class_Template_ID = intval($input['Class_Template_ID']);
    if ($Class_ID) {
        // визуальные настройки
        $CustomSettings = "";
        $settings_array = $db->get_var("SELECT `CustomSettingsTemplate` FROM `Class`
      WHERE `Class_ID` = '".($Class_Template_ID ? $Class_Template_ID : $Class_ID)."'");
        if ($settings_array) {
            require_once($nc_core->ADMIN_FOLDER."array_to_form.inc.php");
            $a2f = new nc_a2f($settings_array, 'CustomSettings');
            if (!$a2f->validate($_POST['CustomSettings'])) {
                $error = $a2f->get_validation_errors();
            } else {
                $a2f->save($_POST['CustomSettings']);
                $CustomSettings = $a2f->get_values_as_string();
            }
        }

        $nc_core->event->execute("addSubClassPrep", $CatalogueID, $SubdivisionID, 0);

        $db->query("INSERT INTO `Sub_Class`
      (`Subdivision_ID`, `Catalogue_ID`, `Class_ID`, `Sub_Class_Name`, `Checked`, `EnglishName`, `Created`, `CustomSettings`, `Class_Template_ID`)
       VALUES
       ('".$SubdivisionID."', '".$CatalogueID."', '".$Class_ID."', '".$Subdivision_Name."', 1, '".$EnglishName."',  '".date("Y-m-d H:i:s")."',  '".addcslashes($CustomSettings, "'")."', '".$Class_Template_ID."')");

        if (($SubClassID = $db->insert_id)) {
            $nc_core->event->execute("addSubClass", $CatalogueID, $SubdivisionID, $SubClassID);
        }
    }

    return $SubdivisionID;
}

function nc_get_modal_radio($name, array $items, $checked_value) {
    static $first_use = true;
    $html = '';

    if ($first_use) {
        $html .= "
            <style>
                div.nc_radio_modal > div > div {
                    display: inline-block;
                    height: 20px;
                }

                div.nc_radio_modal > div {
                    padding-bottom: 8px;
                }

                div.nc_radio_modal_input {
                    padding-right: 15px;
                    vertical-align: middle;
                }

                div.nc_radio_modal {
                    padding-top: 13px;
                }
            </style>";
        $first_use = false;
    }

    $html .= "<div class='nc_radio_modal'>";

    foreach ($items as $item) {
        if (!isset($item['attr']['name'])) {
            $item['attr']['name'] = $name;
        }

        $item['attr']['type'] = 'radio';
        $item['attr']['style'] = $item['attr']['style'] ? $item['attr']['style'] : '';

        if($item['attr']['value'] == $checked_value) {
            $item['attr']['checked'] = 'checked';
        }

        $html .= "
            <div>
                <label>
                    <input ".nc_create_attr_str($item['attr'])." />
                    ".$item['desc']."
                </label>
            </div>";
    }

    $html .= "</div>";
    return $html;
}

function nc_create_attr_str(array $attr) {
    $result = array();
    foreach ($attr as $name => $value) {
        $result[] = $name . '="'.$value.'"';
    }
    return join(' ', $result);
}

function nc_print_root_subdivisions($CatalogueID, $ParentSubID = 0, $level = 0) {
    global $db, $nc_core;
    global $perm;

    $CatalogueID = intval($CatalogueID);
    $ParentSubID = intval($ParentSubID);

    static $security_limit, $initialized;

    if (!$initialized) {
        $initialized = true;
        $allow_id = $perm->GetAllowSub($CatalogueID, MASK_ADMIN | MASK_MODERATE);
        $security_limit = is_array($allow_id) && !$perm->isGuest() ? " Subdivision_ID IN (".join(', ', (array) $allow_id).")" : " 1";
    }

    $result = $db->get_results("SELECT a.Subdivision_ID,a.Subdivision_Name,a.Priority,a.Checked,a.Hidden_URL,b.Domain,a.Catalogue_ID,a.ExternalURL FROM Subdivision AS a, Catalogue AS b
    WHERE a.Catalogue_ID=b.Catalogue_ID AND a.Catalogue_ID = {$CatalogueID} AND a.Parent_Sub_ID = {$ParentSubID} AND {$security_limit} ORDER BY a.Priority", ARRAY_A);

    if (!$result) {
        return false;
    }

    $spacer = '&nbsp;&nbsp;';

    for ($i = 0; $i < $level; $i++) {
        $spacer .= '&nbsp;&nbsp;';
    }

    foreach($result as $row) {
        echo "<option value='{$row['Subdivision_ID']}'>{$spacer}{$row['Subdivision_ID']}. {$row['Subdivision_Name']}</option>";
        nc_print_root_subdivisions($CatalogueID, $row['Subdivision_ID'], $level + 1);
    }

    return true;
}