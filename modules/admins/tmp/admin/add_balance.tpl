<h1>Пополнить баланс вручную</h1>
<form action="" method="post" id="admin_form">

    <div class="form">

        <ul>

            <li>
                <span class="title">Пользователь:</span>
                <select id="user" name="user">
                    [users]
                </select> 
            </li>
            <li>
                <span class="title">Сумма:</span>
                <input type="text" name="sum" id="sum" value="0" class="full-length">
            </li>
        </ul>
    </div>


    <div class="action_bar">
        <input type="hidden" name="send" value="1">
        <input type="submit" value="Сохранить" /><br/><br/>
        <input type="button" value="На главную" onclick="location.href = '?module=admins'" style="width:196px;">
    </div>

</form>