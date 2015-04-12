<div class="canvas">
    <!-- form -->
    <div class="form">
        <form action="" method="post" id="registration">				
            <h1>Регистрация</h1>
            [error]
            <a href="/pages/registration/" target="_blank">Для чего нужна регистрация?</a><br/><br/>

            <script src="//ulogin.ru/js/ulogin.js"></script>
            <h3>Через социальные сети</h3>
            <div id="uLogin" data-ulogin="display=panel;fields=first_name,last_name,email,nickname;providers=vkontakte,odnoklassniki,mailru,facebook,twitter,googleplus;hidden=;redirect_uri=http%3A%2F%2Fiforget.ru%2Fregister.php"></div>
            <br />
            
            <h3>Самостоятельная регистрация</h3>
            <ul>
                <li>
                    <span class="title">Логин:*</span>
                    <input name="login" value="[login]" type="text">
                    <span class="hint"></span>
                </li>
                <li>
                    <span class="title">E-mail:*</span>
                    <input name="email" value="[email]" type="text">
                    <span class="hint"></span>
                </li>
                <li>
                    <span class="title">Тип пользователя:*</span>
                    <select id="type" name="type" style="width: 362px">
                        <option value="0">Вебмастер</option>
                        <option value="1">Копирайтер</option>
                    </select>
                    <span class="hint"></span>
                </li>
                <li>
                    <span class="title">Промо код:</span>
                    <input name="promo" value="[promo]" type="text">
                    <span class="hint"></span>
                </li>
                <li>
                    <input name="sendmail" value="1" class="checkbox"  type="checkbox" checked="checked"> <label for="mail-me">Присылать уведомления и новости</label>
                </li>
            </ul>

            <div class="action-bar">
                <br/>
                <button  OnClick="userRegister1('new_user')">Регистрация</button>
                <span class="note">* Поля необходимые для заполнения</span>

            </div>
            <input name="wmid" id="wmid" type="hidden" value='1'>
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


