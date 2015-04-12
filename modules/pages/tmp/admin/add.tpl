<h1>Новая страница</h1>
<form action="" method="post" id="menu_form" enctype="multipart/form-data">
<table>
	<tr>
		<td>
			Название:
		</td>
		<td>
			<input type="text" name="name" id="name" style="width:600px;">
		</td>
	</tr>
	<tr>
		<td>
			title:
		</td>
		<td>
			<input type="text" name="title" id="title" style="width:600px;">
		</td>
	</tr>
	<tr>
		<td>
			description:
		</td>
		<td>
			<input type="text" name="description" id="description" style="width:600px;">
		</td>
	</tr>
	<tr>
		<td>
			keywords:
		</td>
		<td>
			<input type="text" name="keywords" id="keywords" style="width:600px;">
		</td>
	</tr>
	<tr>
		<td>
			url:
		</td>
		<td>
			<input type="text" name="url" id="url" style="width:600px;"> - только англ. буквы, точки и тире
		</td>
	</tr>
	<tr>
		<td style="vertical-align:top;">
			Контент:
		</td>
		<td>
			[content]
		</td>
	</tr>
	<tr>
		<td>
			<input type="hidden" name="send" value="1">
		</td>
		<td>
			<input type="submit" value="Сохранить" style="width:196px;"><br>
			<input type="button" value="Вернуться" onclick="location.href='?module=pages'" style="width:196px;">
		</td>
	</tr>
</table>
<input type="hidden" id="tid" value="[id]">
</form>