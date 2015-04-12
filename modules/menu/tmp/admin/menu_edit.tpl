<script language="javascript" src="/modules/menu/tmp/admin/js/index.js" type="text/javascript"></script>
<h1>Редактирование ссылки</h1>
<form action="" method="post" id="menu_form">
<table>
	<tr>
		<td>
			url:
		</td>
		<td>
			<input type="text" name="url" value="[url]" id="url" style="width:190px;">
		</td>
	</tr>
	<tr>
		<td>
			текст:
		</td>
		<td>
			<input type="text" name="text" value="[text]" id="text" style="width:190px;">
		</td>
	</tr>
	<tr>
		<td>
			
		</td>
		<td>
			<input type="checkbox" name="blank" value=1" [blank]> Открывать в новом окне
		</td>
	</tr>
	<tr>
		<td>
			<input type="hidden" name="send" value="1">
		</td>
		<td>
			<input type="button" value="Сохранить" onclick="url_add();" style="width:196px;"><br>
			<input type="button" value="Вернуться" onclick="location.href='?module=menu&menu=[menuid]'" style="width:196px;">
		</td>
	</tr>
</table>
</form>