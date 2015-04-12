<h1>Меню: [menu_name]</h1>
<div style="margin:10px 0;">[mymenu]</div>
<table><tr><td><a style="text-decoration:none" href="?module=menu&menu=[menuid]&action=add"><img style="margin-top:3px;" src="/modules/admins/tmp/admin/img/add.gif"></a></td><td><a style="text-decoration:none" href="?module=menu&menu=[menuid]&action=add">Добавить</a></td></tr></table>
<br>
<table cellpadding=0 cellspacing=0 border=0 style="border:solid 1px #d8d8ff; border-bottom:none;" >
<tr style="background:#f0f0ff;">
	<td style="width:300px;border-right:solid 1px #d8d8ff; border-bottom:solid 1px #d8d8ff; padding:0 5px;">
		Текст ссылки
	</td>
	<td style="width:300px;border-right:solid 1px #d8d8ff; border-bottom:solid 1px #d8d8ff; padding:0 5px;">
		Ссылка
	</td>
	<td style="width:70px;border-right:solid 1px #d8d8ff; border-bottom:solid 1px #d8d8ff; padding:0 5px;">
		Позиция
	</td>
	<td style="width:70px;border-right:solid 1px #d8d8ff; border-bottom:solid 1px #d8d8ff; padding:0 5px; text-align:center">
		Ред-ть
	</td>
	<td style="padding:0 5px; width:70px; border-bottom:solid 1px #d8d8ff; text-align:center">
		Удалить
	</td>
</tr>
[menu]
</table>
<br>
<a href="?module=menu">Вернуться</a>
<div style="width:600px; margin-top:20px;">
Заходим а подраздел "Меню", жмем добавить, вводим название для меню. Название нигде не отображается, 
просто формальность. Далее щелкаем по названию, и попадаем в список ссылок. Создаем нужное кол-во ссылок.
Блок меню готов. Но его надо отобразить на сайте. Заходим в подраздел шаблоны, жмем добавить. Выбираем 
созданное меню, и вводим название шаблона(англ. буквы и цифры), к примеру menu1. Готово. На сайте, 
в шаблоне, в нужном месте вписываем переменную [menu1], на ее месте будет отображаться наше меню.
Не забудьте для меню создать шаблон - в папке tmp создаем папку с названием шаблона, в нашем случае menu1.
</div>