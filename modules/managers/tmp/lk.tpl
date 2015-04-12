<div class="wider">
    <form action="?module=managers&action=lk" method="post" class="form" id="saveManager" autocomplete="off">
        <h1>Данные пользователя</h1>		
        <ul>
            <li>
                <span class="title">Логин:</span>
                <input type="text" value="[login]" disabled="disabled" />
            </li>
            <li>
                <span class="title">E-mail*:</span>
                <input type="text" value="[email]" name="email" id="email" />
            </li>
            <li>
                <span class="title">Полное имя:*</span>
                <input name="fio" value="[fio]" type="text" id="fio" autocomplete="off" />
                <span class="hint"></span>
            </li>
            <li>
                <span class="title">Новый пароль:*</span>
                <input id="password" name="password" value="" type="password" class="textfield" autocomplete="off" />
                <span class="hint"></span>
            </li>
            <li>
                <span class="title">Повторить пароль:*</span>
                <input name="confpass" id="confpass" value="" type="password"  class="textfield"  autocomplete="off" />
                <span class="hint"></span>
            </li>
            <li>
                <span class="title">Выводить деньги на:</span>
                <select style="width:170px" name="wallet_type">
                    <option value="1" [checked_1]>Webmoney</option>
                </select>
            </li>
            <li>
                <span class="title">Кошелек:</span>
                <input type="text"  value="[wallet]" placeholder="" name="wallet" id="wallet" />

            </li>
            <li>
                <span class="title">ICQ:</span>
                <input type="text"  value="[icq]" placeholder="" name="icq" id="icq" />
            </li>
            <li>
                <span class="title">Scype:</span>
                <input type="text"  value="[scype]" placeholder="" name="scype" id="scype" />
            </li>
            <li class="em">
                <span class="title">Откуда вы о нас узнали?</span>
                <textarea style=" height: 50px;" name="knowus" rows="5" cols="55">[knowus]</textarea>
            </li>
            <!--
            <li>
                <span class="title">Отписаться от рассылки:</span>
                <input type="checkbox" name="mail_period" id="mail_period" style="margin-top: 13px" [mail_period] />
            </li>
            -->
        </ul>

        <div class="action-bar">
            <br/>
            <input name="send" type="hidden" value='1'>
            <input type="submit" value="Сохранить" id="send" onclick="return false;" />
        </div>
    </form>
</div>
<script type="text/javascript">
                $(document).ready(function() {
                    function validateEmail(email) {
                        var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                        return re.test(email);
                    }

                    $("#send").click(function() {
                        var email = $("#email").val();
                        var pass = $("#pass").val();
                        var confpass = $("#confpass").val();
                        var fio = $("#fio").val();

                        if (email === "" || fio === "") {
                            alert("Все обязательные поля должны быть заполнены");
                        } else {
                            if(!validateEmail(email)){
                                alert("E-mail некорректный");
                                return false;
                            }
                            
                            if ((pass !== "" && confpass !== "") && pass !== confpass) {
                                alert("Пароли должны совпадать!");
                            } else {
                                $("#saveManager").submit();
                            }
                        }
                    });

                });

</script>