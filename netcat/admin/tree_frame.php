<?php
/* $Id: tree_frame.php 8290 2012-10-26 13:31:12Z vadim $ */

require("./function.inc.php");

$sources = array(
        'sitemap'   => $ADMIN_PATH."subdivision/tree_json.php?node=",
        'developer' => $ADMIN_PATH."dev_json.php?node=",
        'modules'   => $ADMIN_PATH."modules/modules_json.php?node=",
        'users'     => $ADMIN_PATH."user/user_json.php?node=",
        // выбор -связанных-

        'related_message'     => $ADMIN_PATH."related/tree_json.php?mode=related_message&node=",
        'related_subdivision' => $ADMIN_PATH."related/tree_json.php?mode=related_subdivision&node=",
        'related_subclass'    => $ADMIN_PATH."related/tree_json.php?mode=related_subclass&node=",
        'wizard_subdivision'  => $ADMIN_PATH."wizard/tree_json.php?mode=wizard_subdivision&node=",
        'wizard_parentsub'    => $ADMIN_PATH."wizard/tree_json.php?mode=wizard_parentsub&node=",
        'copy_message'        => $ADMIN_PATH."objects/tree_json.php?mode=copy_message&cc=".$cc."&classID=".$classID."&message=".$message."&node=",

        'classificator' => $ADMIN_PATH . 'dev_json.php?fs=0&node=classificator.list&node_action=',

        'dataclass'    => $ADMIN_PATH . 'dev_json.php?fs=0&node=dataclass.list&node_action=',
        'dataclass_fs' => $ADMIN_PATH . 'dev_json.php?fs=1&node=dataclass.list&node_action=',

        'systemclass'    => $ADMIN_PATH . 'dev_json.php?fs=0&node=systemclass.list&node_action=',
        'systemclass_fs' => $ADMIN_PATH . 'dev_json.php?fs=1&node=systemclass.list&node_action=',

        'template'    => $ADMIN_PATH . 'dev_json.php?fs=0&node=templates&node_action=',
        'template_fs' => $ADMIN_PATH . 'dev_json.php?fs=1&node=templates&node_action=',

        'widgetclass'    => $ADMIN_PATH . 'dev_json.php?fs=0&node=widgetclass.list&node_action=',
        'widgetclass_fs' => $ADMIN_PATH . 'dev_json.php?fs=1&node=widgetclass.list&node_action='
);

$source = $sources[$_GET['mode']];

if (!$source) exit('Wrong params');

$selected_node = isset($_GET['selected_node']) ? $_GET['selected_node'] : '';
$mode = isset($_GET['mode']) ? $_GET['mode'] : '';
if (isset($_GET['fs']) && $_GET['fs'] == 1) {
    $mode = preg_replace('/_fs$/i', '', $mode);
}

$selected_node = $selected_node == $mode . '.list' ? false : htmlspecialchars($selected_node);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tree Frame</title>
    <link rel="stylesheet" rev="stylesheet" type="text/css" href="<?= $ADMIN_TEMPLATE ?>css/font.css?<?= $LAST_LOCAL_PATCH ?>" />
    <script type='text/javascript'>
        var File_Mode = <?php echo +$_REQUEST['fs']; ?>;
    </script>
    <?=nc_js(); ?>
    <script>nc.register_view('tree');</script>
    <script type='text/javascript' src='<?= $ADMIN_PATH ?>js/tree_frame.js'></script>
    <style>
    /* fix ios width */
    body {width: 100%; position: absolute;}
    </style>
</head>
<body>
    <div id='menu_left'>
        <div class='menu_left_block'>
            <ul id='siteTree' style='margin-top: 0px; padding-left: 20px; margin-bottom: 0px;'></ul>
        </div>
    </div>
<script type='text/javascript'>
    new dynamicTree('tree', 'siteTree', '<?= $source ?>');
    <?php echo $selected_node ? "setTimeout( function() {tree.selectNode('" . $selected_node . "'); tree.toggleNode('" . $selected_node . "', false, true);}, 500);" : ""; ?>
</script>
</body>
</html>