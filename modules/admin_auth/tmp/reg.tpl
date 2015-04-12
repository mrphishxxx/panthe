<script language="javascript" src="/modules/auth/tmp/js/index.js" type="text/javascript"></script>
<form action="" id="reg_form" method="POST">
			<table>
			<tr>
			<td>Email: </td><td><input type="text" name="mail" id="mail" style="width:180px"></td>
			</tr>
			<tr>
			<td>Логин: </td><td><input type="text" name="login" id="login" style="width:180px"></td>
			</tr>
			<tr>
			<td>Пароль: </td><td><input type="password" name="pass" id="pass" style="width:180px"></td>
			</tr><tr>
			<tr>
			<td><nobr>Повтор пароля: </nobr> </td><td><input type="password" name="pass2" id="pass2" style="width:180px"></td>
			</tr>
			<tr>
			<td>
			<img id="captcha" src="/includes/captcha/index.php">
			</td>
			<td>
			Код с каптчи<br>
			<input type="text" name="kod" id="kod" style="width:180px"><br>
			<a href="javascript:captcha_change();">Сменить рисунок</a>
			</td>
			</tr>
			<tr>
			<td></td><td><input type="hidden" name="send" value="1">
			<input type="button" onclick="reg();" value="Регистрация" style="width:187px"></td>
			</tr>
			
			</table>
</form>