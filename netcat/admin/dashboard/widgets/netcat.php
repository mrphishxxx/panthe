<?php

$NETCAT_FOLDER  = realpath(dirname(__FILE__) . '/../../../../') . DIRECTORY_SEPARATOR;

require_once $NETCAT_FOLDER . 'vars.inc.php';
require_once $ADMIN_FOLDER . 'function.inc.php';
require_once $ADMIN_FOLDER . 'patch/function.inc.php';

CheckForNewPatch();

?>

<div class="nc-widget-link" onclick="return nc.ui.dashboard.fullscreen(this, '/netcat/admin/patch/')">
	<div class="nc-text-center" style="position:absolute; left:0; top:50%; margin-top:-46px; height:82px; width:100%; line-height:90px; font-size:25px; background:url('<?=$ADMIN_PATH ?>dashboard/img/netcat-logo-large.png') no-repeat 50% 50%;">
		<?=$VERSION_ID ?>
	</div>

	<div class="nc-position-tl">
		<span title="NetCat <?=BEGINHTML_VERSION ?> <?=$VERSION_ID ?> <?=$SYSTEM_NAME ?>"><i class="nc-icon nc--info nc--white"></i></span>
	</div>

	<div class="nc-position-b nc-text-center">
		<? if($LAST_PATCH): ?>
				<i class="nc-icon nc--download nc--white"></i> <?=DASHBOARD_UPDATES_EXISTS ?>
		<? else: ?>
			<?=DASHBOARD_UPDATES_DONT_EXISTS ?>
		<? endif ?>
	</div>
</div>