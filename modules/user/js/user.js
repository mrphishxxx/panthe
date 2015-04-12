function addUserBirj()
{
    var error = 0, err_txt = "", login = $("#loginp").val(), pass = $("#pass").val(), bid = $("select#bid").val();

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
            if (data === "FALSE") {
                alert("В данную систему не возможно зайти с таким логином и паролем! Проверьте правильность введенных данных!");
                return false;
            } else {
                //return false;
                $("#birj_form").submit();
            }
        });


        return false;

    }
}

