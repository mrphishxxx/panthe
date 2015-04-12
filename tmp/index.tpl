<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>[title]</title>
		<meta name="keywords" content="[keywords]" />
  		<meta name="description" content="[description]" />
		<link rel="stylesheet" href="/tmp/css/style.css" type="text/css">
		<link href="/modules/galery/tmp/css_pirobox/style_1/style.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="/modules/galery/tmp/js/jquery.min.js"></script>
		<script type="text/javascript" src="/modules/galery/tmp/js/jquery-ui-1.8.2.custom.min.js"></script>
		<script type="text/javascript" src="/modules/galery/tmp/js/pirobox_extended.js"></script>
		<script type="text/javascript">
		$(document).ready(function() {
			$().piroBox_ext({
			piro_speed : 700,
				bg_alpha : 0.5,
				piro_scroll : true // pirobox always positioned at the center of the page
			});
		});
		</script>
		
	</head>
	<body>
		<div class="wrap">
			<div class="top-header"></div>
			<div class="header-shadow"></div>
			<div class="header">					
				<div class="balcony"></div>
				<div class="builder"></div>
				<ul class="top-menu">
					[menu1]
				</ul>
				<div class="header-column">
					<a href="/" class="logo" style=" background: url(/files/[logo]) 0 0 no-repeat;"></a>
					<div class="contacts">
						<span class="time">
							Офис работает [vr]
						</span>
						<span class="phone">
							[phones]
						</span>
					</div>
					<span class="slogan">От простого до сложного - нет ничего невозможного</span>
					<div class="header-menu">
						<span class="helmet"></span>
						<ul class="left">
							[menu2]
						</ul>
						<ul class="right">
							[menu3]
						</ul>
					</div>
					<div class="sale">
						<em class="windows">На <strong>второе</strong> окно</em>
						<span class="windows"><span class="font1">Скидка</span> <span class="font2">1000р.</span></span>
						<em class="windows">На <strong>Третье</strong> окно</em>
						<span class="windows"><span class="font1">Скидка</span> <span class="font2">2000р.</span></span>
					</div>
				</div>
				<div class="header-right">
					<a href="/galery.html" class="a1">Наши работы</a>
					<a href="/pages/write.html" class="a2">Заказать</a>
				</div>
			</div><!--/header-->
			
			<div class="main">
				<div class="content">
						<h1>[page_title]</h1>
						[content]
				</div>
			</div><!--/main-->

		</div><!--/wrap-->
		<a name="top"></a>
			<div class="footer">
				<div class="in-footer">
					<div class="footer-contacts">
						<span class="phone">[phones]</span>
						<span class="copy">[footer] | <a href="http://smolsayt.ru" target="_blank">Сайт создан в Web-студии "СмолСайт"</a></span>
					</div>
					<div class="counters">
						[counters]
					</div>
				</div>
			</div><!--/footer-->
		<script>
			if(document.location.href != 'http://'+document.domain+'/') document.location.href='#top';
		</script>
	</body>
	
</html>