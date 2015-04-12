<script language="javascript" src="/modules/user/js/user.js" type="text/javascript"></script>
<h1>Биржи пользователя [login]</h1>

<!-- the table -->
<div class="wider">
    <form action="remove" method="post">
        <table style="border-bottom: 1px solid #ccc; padding-bottom: 20px;">
            <thead>

                <tr>
                    <th class="title">Название</th>
                    <th class="login">Логин</th>
                    <th class="password">Пароль</th>
                    <th class="edit"></th>
                    <th class="close right-corner"><div></div></th>
            </tr>

            </thead>
            <tbody>

                <!-- .*.*.*.*.*.*.*.*.*.*.*.*.*.*.*.*.*.*.*.*.*.*.*.*.* -->
                [birjs]
                <!-- .*.*.*.*.*.*.*.*.*.*.*.*.*.*.*.*.*.*.*.*.*.*.*.*.* -->

            </tbody>
        </table>
    </form>
</div>

<br/><br/>
[add_site]
[load_site_ggl]
[load_site_getgoodlinks]
[load_site_sape]
[load_site_rotapost]

<br/>
<!-- add exchange -->
<div class="add-exchange">
    <form action="/user.php?action=birj&action2=add" method="post" id="birj_form" autocomplete="off">

        <h3>Добавление биржи</h3>
        <div class="form">

            <ul>
                <li>
                    <span class="title">Биржа:</span>
                    <select class="wide" name="bid" id="bid">
                        [burse]
                    </select>
                </li>
                <li>
                    <span class="title">Логин:</span>
                    <input type="text" class="full-length" value="" placeholder="" name="login" id="loginp" />
                </li>
                <li>
                    <span class="title">Пароль:</span>
                    <input type="text" class="full-length" value="" placeholder="" name="password" id="pass" />
                </li>
            </ul>
            <div id="help_rotapost" style="display: none">
                <img src="/images/interface/system-attention.png" style="float: left;margin-right: 15px;" />
                <p>Если Вы дадите доступ в главный Ваш аккаунт, скорость обработки будет выше, чем при аккаунте сотрудника.</p>
            </div>
        </div>

        <div class="action_bar">
            <input type="hidden" value="[uid]" name="uid2" />
            <input type="submit" value="Добавить" onclick="addUserBirj();
                    return false;" />
        </div>

    </form>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $("#bid").change(function (){
        if($("#bid :selected").val() === "3"){
            $("#help_rotapost").show();
        } else {
            $("#help_rotapost").hide();
        }
    });

});

</script>