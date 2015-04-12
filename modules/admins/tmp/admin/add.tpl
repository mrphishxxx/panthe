<script language="javascript" src="/modules/admins/tmp/admin/js/index.js" type="text/javascript"></script>

<h1>Новый пользователь</h1>
<form action="" method="post" id="admin_form">

    <div class="form">

        <ul>

            <li>
                <span class="title">Логин:</span>
                <input type="text" name="login" value="" class="full-length">
            </li>
            <li>
                <span class="title">Пароль:</span>
                <input type="text" name="pass" id="pass" value="" class="full-length">
            </li>
            <li>
                <span class="title">Тип:</span>
                <select name="type" id="type" class="full-length">
                    <option value="">--Выберите--</option>
                    <option value="admin" [rights]>Администратор</option>
                    <option value="manager">Менеджер</option>
                    <option value="moder">Выкладывальщик</option>
                    <option value="user">Пользователь</option>
                    <option value="copywriter">Копирайтер</option>
                </select>
            </li>
            <li>
                <span class="title">Контакты:</span>
                <input type="text" name="contacts" id="contacts" value="" class="full-length">
            </li>
            <li>
                <span class="title">ICQ:</span>
                <input type="text" name="icq" id="icq" value="" class="full-length">
            </li>
            <li>
                <span class="title">Scype:</span>
                <input type="text" name="scype" id="scype" value="" class="full-length">
            </li>
            <li>
                <span class="title">Пришел к нам:</span>
                <input type="text" name="date" id="date" value="" class="full-length">
            </li>
            <!--<li>
                    <span class="title">Доступы к биржам:</span>
                    <textarea name="dostupy" class="full-length"></textarea>
            </li>-->
        </ul>
    </div>


    <div class="action_bar">
        <input type="hidden" name="send" value="1">
        <input type="submit" value="Сохранить" /><br/><br/>
        <input type="button" value="Вернуться" onclick="location.href = '?module=admins'" style="width:196px;">
    </div>

</form>