<script language="javascript" src="/modules/admins/tmp/admin/js/index.js" type="text/javascript"></script>
<link href="/includes/datepicker/datepicker.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="/includes/datepicker/jquery-1.2.1.js" type="text/javascript"></script>
<script language="javascript" src="/includes/datepicker/datepicker.js" type="text/javascript"></script>
<script>

    $(document).ready(function() {
        $("#date").attachDatepicker();
    });
</script>
<h1>Редактирование пользователя</h1>
<form action="" method="post" id="admin_form">

    <div class="form">

        <ul>

            <li>
                <span class="title">Логин:</span>
                <input type="text" name="login" value="[login]" class="full-length">
            </li>
            <li>
                <span class="title">Email:</span>
                <input type="text" name="email" value="[email]" class="full-length">
            </li>
            <li>
                <span class="title">Новый пароль:</span>
                <input type="text" name="pass" id="pass" value="" class="full-length">
            </li>
            <li>
                <span class="title">Тип:</span>
                <select name="type" id="type" class="full-length">
                    <option value="admin" [rights] [admin]>Администратор</option>
                    <option value="manager" [manager]>Менеджер</option>
                    <option value="moder" [moder]>Выкладывальщик</option>
                    <option value="user" [user]>Пользователь</option>
                    <option value="copywriter" [copywriter]>Копирайтер</option>
                </select>
            </li>
            <li>
                <span class="title">Контакты:</span>
                <input type="text" name="contacts" id="contacts" value="[contacts]" class="full-length">
            </li>
            <li>
                <span class="title">ICQ:</span>
                <input type="text" name="icq" id="icq" value="[icq]" class="full-length">
            </li>
            <li>
                <span class="title">Scype:</span>
                <input type="text" name="scype" id="scype" value="[scype]" class="full-length">
            </li>
            <li>
                <span class="title">Пришел к нам:</span>
                <input type="text" name="date" id="date" value="[reg_date]" class="full-length">
            </li>
            <li>
                <span class="title">Комментарий выкладывальщику:</span>
                <textarea name="comment_viklad" class="full-length">[comment_viklad]</textarea>
            </li>
        </ul>
    </div>


    <div class="action_bar">
        <input type="hidden" name="send" value="1">
        <input type="submit" value="Сохранить" /><br/><br/>
        <input type="button" value="Вернуться" onclick="location.href = '?module=admins'" style="width:196px;">
    </div>

</form>