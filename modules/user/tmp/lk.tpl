<!-- the table -->
<div class="wider">
    <form action="/user.php?action=lk" method="post" class="form" autocomplete="off">
        <h1>Данные пользователя</h1>
        <span style="color: green">[query]</span>
        <ul>
            <li>
                <span class="title">Полное имя:*</span>
                <input name="fio" value="[fio]" type="text" id="fio">
                <span class="hint"></span>
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
                    <option value="1" [checked_1]>Webmoney</option>
                </select>
            </li>
            <li>
                <span class="title">Кошелек:</span>
                <input type="text"  value="[wallet]" placeholder="" disabled="disabled" id="wallet" /> <!--name="wallet" -->
                <span class="hint edit"><a href="?action=change_wallet" class="ico">Изменить</a></span>
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
                <br/><br/>
                <textarea style="width: 475px; height: 32px;" name="knowus" rows="5" cols="55">[knowus]</textarea>
            </li>
            <li>
                <span class="title">Периодичность системных уведомлений:</span>
                <select name="mail_period" id="mail_period">
                    <option value="43200" [checked_43200]>Два раза в день</option>
                    <option value="86400" [checked_86400]>Раз в день</option>
                    <option value="259200" [checked_259200]>Раз в три дня</option>
                    <option value="604800" [checked_604800]>Раз в неделю</option>
                    <option value="0" [checked_0]>Отписаться от рассылки</option>
                </select>
                <span class="hint"></span>
            </li>
        </ul>

        <div class="action-bar">
            <br/>
            <input name="send" type="hidden" value='1'>
            <input type="submit" value="Сохранить" />

        </div>
    </form>
</div>