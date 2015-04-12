<script language="javascript" src="/modules/menu/tmp/admin/js/index.js" type="text/javascript"></script>
<h1>Редактирование меню</h1>
<form action="" method="post" id="menu_form">
<table>
	<tr>
		<td>
			Название:
		</td>
		<td>
			<input type="text" name="name" id="name" value="[name]" style="width:190px;">
		</td>
	</tr>
	<tr>
		<td>
			<input type="hidden" name="send" value="1">
		</td>
		<td>
			<input type="button" value="Сохранить" onclick="menu_add();" style="width:196px;"><br>
			<input type="button" value="Вернуться" onclick="location.href='?module=menu'" style="width:196px;">
		</td>
	</tr>
</table>
</form>