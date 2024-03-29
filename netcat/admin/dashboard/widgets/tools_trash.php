<?php

$NETCAT_FOLDER = realpath(dirname(__FILE__) . '/../../../../') . DIRECTORY_SEPARATOR;

require_once $NETCAT_FOLDER . 'vars.inc.php';
require_once $ADMIN_FOLDER . 'function.inc.php';
require_once $ADMIN_FOLDER . "trash/function.inc.php";

$in_trash      = 0;
$trash_enabled = (bool)$nc_core->get_settings('TrashUse');

if ( $trash_enabled ) {
    $in_trash = (int)$db->get_var("SELECT COUNT(*) AS total FROM `Trash_Data`");
}
?>
<div class="nc-widget-link" onclick="return nc.ui.dashboard.fullscreen(this, '/netcat/admin/trash/')">
	<table class="nc-widget-grid">
		<tr>
			<td class="nc-text-center">
				<div class="<?=$in_trash ? '': 'nc--disabled' ?>">
					<i class="nc-icon-x nc--tools-trash nc--white"></i>
				</div>
			</td>
		</tr>
	</table>
	<div class="nc-position-t nc-text-center nc-text-small">
		<?=$trash_enabled ? TOOLS_TRASH : NETCAT_TRASH_TRASHBIN_DISABLED ?>
	</div>
	<? if($trash_enabled): ?>
		<? if($in_trash): ?>
			<dl class="nc-bg-dark nc-position-b nc-text-center nc-info nc--mini">
				<dt><?=$in_trash ?></dt>
				<dd><?=nc_numeral_inclination($in_trash, array(NETCAT_TRASH_MESSAGES_SK1, NETCAT_TRASH_MESSAGES_SK2, NETCAT_TRASH_MESSAGES_SK3)) ?></dd>
			</dl>
		<? else: ?>
			<div class="nc-bg-dark nc-position-b nc-text-center">
				<?=NETCAT_TRASH_NOMESSAGES ?>
			</div>
		<? endif ?>
	<? endif ?>
</div>