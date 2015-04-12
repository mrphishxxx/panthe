<form method="post" action=""  enctype="multipart/form-data">
<table>
			<tr>
			<td>Кем Вы являетесь?  </td><td><select name="type" style="width:186px">
<option value="user">Пользователь</option>
<option value="finexpert">Финансовый эксперт</option>
<option value="bankworker">Банковский служащий</option>
<option value="infopartner">Информационный партнер</option>
<option value="smiworker">Представитель СМИ</option>
<option value="region">Региональный представитель</option>
</select></td>
			</tr>
			<tr>
			<td>Телефон: </td><td><input type="text" name="phone" id="phone" style="width:180px"></td>
			</tr>
			<tr>
			<td>Место работы: </td><td><input type="text" name="work" id="work" style="width:180px"></td>
			</tr>
			<tr>
			<td>Фото: </td><td><input type="file" name="photo" id="photo"></td>
			</tr>
			<tr>
			<td><input type="hidden" name="send" value="1"></td><td><input type="submit" value="Сохранить"  style="width:180px"></td>
			</tr>
</table>
</form>