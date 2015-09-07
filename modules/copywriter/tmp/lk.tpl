<h1>Данные пользователя</h1>
<span style="color: green">[query]</span>
<span style="color: red">[error]</span>

<div class="wider ">
    <form action="/copywriter.php?action=lk" method="post" class="form" autocomplete="off">		
        <ul>
            <li>
                <span class="title">Логин:</span>
                <input value="[login]" type="text" disabled="disabled" />
            </li>
            <li>
                <span class="title">E-mail:</span>
                <input value="[email]" type="text" disabled="disabled" />
            </li>
            <li>
                <span class="title">ФИО:</span>
                <input name="fio" value="[fio]" type="text" id="fio" />
            </li>
            <li>
                <span class="title">Новый пароль:*</span>
                <input id="password" name="password" value="" type="password" class="textfield">
                <span class="hint"></span>
            </li>
            <li>
                <span class="title">Повторить пароль:*</span>
                <input name="confpass" id="confpass" value="" type="password"  class="textfield">
                <span class="hint"></span>
            </li>
            <li>
                <span class="title">Выводить деньги на:</span>
                <select style="width:156px" name="wallet_type">
                    <option value="1" checked="checked">Webmoney</option>
                </select>
            </li>
            <li>
                <span class="title">Кошелек:*</span>
                <input type="text"  value="[wallet]" placeholder=""  disabled="disabled" id="wallet" /> <!--name="wallet"-->
            </li>
            <li>
                <span class="title">ICQ:</span>
                <input type="text"  value="[icq]" placeholder="" name="icq" id="icq" />
            </li>
            <li>
                <span class="title">Scype:</span>
                <input type="text"  value="[scype]" placeholder="" name="scype" id="scype" />
            </li>
            <li>
                <span class="title">Отписаться от рассылки:</span>
                <input type="checkbox" name="mail_period" id="mail_period" style="margin-top: 13px" [mail_period] />
            </li>
        </ul>

        <div class="action-bar">
            <br/>
            <input name="send" type="hidden" value='1'>
            <input id="send" onclick="return false;" type="submit" value="Сохранить" />
        </div>
    </form>
</div>
<script type="text/javascript">
$(document).ready(function (){
    $("#send").click(function () {
        if($("#wallet").val() === ""){
            alert("Поле Кошелек обязателен для заполнения!");
        } else {
            $(".form").submit();
        }
    });
});
</script>
