		<link type="text/css" href="/includes/jquery-ui/css/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
		<script type="text/javascript" src="/includes/jquery-ui/js/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="/includes/jquery-ui/js/jquery-ui-1.8.16.custom.min.js"></script>
		<script type="text/javascript">
			$(function(){

				// Accordion
				$("#accordion").accordion({ header: "h3" });
	
				// Tabs
				$('#tabs').tabs();
	

				// Dialog			
				$('#dialog').dialog({
					autoOpen: false,
					width: 600
					
				});
				
				// Dialog Link
				$('#dialog_link1_0').click(function(){
					$('#dialog').dialog('open');
					return false;
				});
				$('#dialog_link1_1').click(function(){
					$('#dialog').dialog('open');
					return false;
				});
				$('#dialog_link2_0').click(function(){
					$('#dialog').dialog('open');
					return false;
				});
				$('#dialog_link2_1').click(function(){
					$('#dialog').dialog('open');
					return false;
				});
				$('#dialog_link3_0').click(function(){
					$('#dialog').dialog('open');
					return false;
				});
				$('#dialog_link3_1').click(function(){
					$('#dialog').dialog('open');
					return false;
				});
				$('#dialog_link4_0').click(function(){
					$('#dialog').dialog('open');
					return false;
				});
				$('#dialog_link4_1').click(function(){
					$('#dialog').dialog('open');
					return false;
				});
				$('#dialog_link5_0').click(function(){
					$('#dialog').dialog('open');
					return false;
				});
				$('#dialog_link5_1').click(function(){
					$('#dialog').dialog('open');
					return false;
				});
				$('#dialog_link6_0').click(function(){
					$('#dialog').dialog('open');
					return false;
				});
				$('#dialog_link6_1').click(function(){
					$('#dialog').dialog('open');
					return false;
				});
				$('#dialog_link7_0').click(function(){
					$('#dialog').dialog('open');
					return false;
				});
				$('#dialog_link7_1').click(function(){
					$('#dialog').dialog('open');
					return false;
				});
				$('#dialog_link8_0').click(function(){
					$('#dialog').dialog('open');
					return false;
				});
				$('#dialog_link8_1').click(function(){
					$('#dialog').dialog('open');
					return false;
				});
				$('#dialog_link9_0').click(function(){
					$('#dialog').dialog('open');
					return false;
				});
				$('#dialog_link9_1').click(function(){
					$('#dialog').dialog('open');
					return false;
				});
				$('#dialog_link10_0').click(function(){
					$('#dialog').dialog('open');
					return false;
				});
				$('#dialog_link10_1').click(function(){
					$('#dialog').dialog('open');
					return false;
				});

				// Datepicker
				$('#datepicker').datepicker({
					inline: true
				});
				
				// Slider
				$('#slider').slider({
					range: true,
					values: [17, 67]
				});
				
				// Progressbar
				$("#progressbar").progressbar({
					value: 20 
				});
				
				//hover states on the static widgets
				$('#dialog_link, ul#icons li').hover(
					function() { $(this).addClass('ui-state-hover'); }, 
					function() { $(this).removeClass('ui-state-hover'); }
				);
				
			});
		</script>

		<style type="text/css">
			/*demo page css*/
			body{ font: 62.5% "Trebuchet MS", sans-serif; margin: 50px;}
			.demoHeaders { margin-top: 2em; }
			#dialog_link {padding: .4em 1em .4em 20px;text-decoration: none;position: relative;}
			#dialog_link span.ui-icon {margin: 0 5px 0 0;position: absolute;left: .2em;top: 50%;margin-top: -8px;}
			ul#icons {margin: 0; padding: 0;}
			ul#icons li {margin: 2px; position: relative; padding: 4px 0; cursor: pointer; float: left;  list-style: none;}
			ul#icons span.ui-icon {float: left; margin: 0 4px;}
		</style>	


		<p><a href="#" id="dialog_link" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-newwin"></span>Open Dialog</a></p>
		
		
	

		
		<!-- ui-dialog -->
		<div id="dialog" title="Dialog Title">
			

		</div>
			
			
