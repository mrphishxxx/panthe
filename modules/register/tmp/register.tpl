<div class="canvas">
    <!-- form -->
    <div class="form">
        <form action="" method="post" id="registration">				
            <h1>Регистрация</h1>
            [error]
            <a href="/pages/registration/" target="_blank">Для чего нужна регистрация?</a><br/><br/>

            <h3>Регистрация</h3>
            <ul>
                <li>
                    <span class="title">Логин:*</span>
                    <input name="login" id="" value="[login]" type="text" class="necessarily" />
                    <span class="hint"></span>
                </li>
                <li>
                    <span class="title">E-mail:*</span>
                    <input name="email" id="email" value="[email]" type="text" class="necessarily" />
                    <span class="hint"></span>
                </li>
                <li>
                    <span class="title">Пароль:*</span>
                    <input name="password" id="password" value="" type="password" class="textfield necessarily" />
                    <span class="hint"></span>
                </li>
                <li>
                    <span class="title">Повторить пароль:*</span>
                    <input name="confpass" id="confpass" value="" type="password"  class="textfield necessarily" />
                    <span class="hint"></span>
                </li>
                <li>
                    <span class="title">Тип пользователя:*</span>
                    <select id="type" name="type" style="width: 362px">
                        <option value="0" [type0]>Вебмастер</option>
                        <option value="1" [type1]>Копирайтер</option>
                    </select>
                    <span class="hint"></span>
                </li>
                <li id="wallet_block" [display]>
                    <span class="title">Webmoney кошелек:*</span>
                    <input name="wallet" value="[wallet]" id="wallet" type="text" class="" />
                    <span class="hint"></span>
                </li>
                <li>
                    <span class="title">Промо код:</span>
                    <input name="promo" value="[promo]" type="text" />
                    <span class="hint"></span>
                </li>
                <li>
                    <input name="sendmail" value="1" class="checkbox"  type="checkbox" checked="checked"> <label for="mail-me">Присылать уведомления и новости</label>
                </li>

            </ul>

            <div class="action-bar">
                <br/>
                <button onclick="regNewUser();return false;" id="submit">Регистрация</button>
                <span class="note">* Поля, необходимые для заполнения</span>

            </div>
            <input name="wmid" id="wmid" type="hidden" value='1'>
            <input name="partner_link" type="hidden" value="[partner_link]">

        </form>
    </div>

    <!-- notifications -->
    <div class="notifications">
        <div class="notification important">
            [register_comment]
            <div class="pointer"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function (){
    $("#type").change(function () {
        if($(this).val() === "0"){
            $("#wallet_block").hide();
            $("#wallet").val("");
            $("#wallet").removeClass("necessarily");
        } else {
            $("#wallet_block").show();
            $("#wallet").addClass("necessarily");
        }
    });
});
</script>
