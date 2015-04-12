<h1>Редактирование записи</h1>
<form action="" method="post" id="admin_form">
    <input type="hidden" name="id" value="[id]">
    <p>
        Вывод средств [date]
    </p>
    
    <div class="form">
        <ul>
            <li>
                <span class="title">Копирайтер:</span>
                <select name="uid" id="uid" class="full-length">
                    [copywriters]
                </select>
            </li>
            <li>
                <span class="title">Сумма:</span>
                <input type="text" name="sum" id="sum" value="[sum]" class="full-length">
            </li>
        </ul>
    </div>


    <div class="action_bar">
        <input type="hidden" name="send" value="1">
        <input type="submit" value="Сохранить" /><br/><br/>
        <input type="button" value="Вернуться" onclick="location.href = '?module=admins&action=copywriters&action2=withdrawal'" style="width:196px;">
    </div>

</form>