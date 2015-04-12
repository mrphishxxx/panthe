<!-- add exchange -->
<div class="add-exchange" style="margin-top:0; padding-top:0;">
	<form action="/user.php?action=birj&action2=edit" method="post" id="birj_form">
		
		<h3>Редактирование биржи</h3>
		<div class="form">
			
			<ul>
				<li>
					<span class="title">Биржа:</span>
					<select class="wide" name="birj" id="bid">
						[burse]
					</select>
				</li>
				<li>
					<span class="title">Логин:</span>
					<input type="text" class="full-length" value="[login]" placeholder="" name="login" id="loginp" />
				</li>
				<li>
					<span class="title">Пароль:</span>
					<input type="password" class="full-length textfield" value="[pass]" placeholder="" name="password" id="pass" />
				</li>
			</ul>
			
		</div>
		
		<div class="action_bar">
			<input type="hidden" value="[bid]" name="bid" />
			<input type="hidden" value="1" name="send" />
			<input type="submit" value="Сохранить" onclick="addUserBirj(); return false;" />
		</div>
	
	</form>
</div>

<script>

function addUserBirj()
{
	var error = 0, err_txt="", login=$("#loginp").val(), pass=$("#pass").val(), bid = $("select#bid").val();

	if (!$.trim(login).length)
	{
		err_txt += "Поле Логин обязательно для заполнения!\r\n";
		error = 1;
	}

	if (!$.trim(pass).length)
	{
		err_txt += "Поле Пароль обязательно для заполнения!\r\n";
		error = 1;
	}

	if (error)
		alert(err_txt);
	else {
            $.post(
                    "/modules/user/php/ajax.php",
                    {
                        login: login,
                        pass: pass,
                        bid: bid
                    }
            ).done(function(data) {
                if(data === "FALSE"){
                    alert("В данную систему не возможно зайти с таким логином и паролем! Проверьте правильность введенных данных!");
                    return false;
                } else {
                     $("#birj_form").submit();
                }
            });


            return false;

        }
		//$("#birj_form").submit();

}

</script>