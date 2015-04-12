<script language="javascript" src="/modules/menu/tmp/admin/js/index.js" type="text/javascript"></script>
<h1>Редактирование шаблона</h1>
<form action="" method="post" id="menu_form">
<table>
	<tr>
		<td>
			Название(англ. буквы и цифры):
		</td>
		<td>
			<input type="text" name="template" id="template" value="[template]" style="width:190px;">
		</td>
	</tr>
	<tr>
		<td>
			Меню:
		</td>
		<td>
			<select name="menu" id="menu" style="width:196px;">[menu]</select>
		</td>
	</tr>
	<tr>
		<td>
			<input type="hidden" name="send" value="1">
		</td>
		<td>
			<input type="button" value="Сохранить" onclick="template_add();" style="width:196px;"><br>
			<input type="button" value="Вернуться" onclick="location.href='?module=menu&page=template'" style="width:196px;">
		</td>
	</tr>
</table>
<input type="hidden"  id="tid" value="[id]">
</form>