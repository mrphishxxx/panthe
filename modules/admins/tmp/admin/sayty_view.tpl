<h1>Сайты пользователя [login]</h1>

<form action="?module=admins&action=sayty&action2=send_email&uid=[uid]" method="POST" class="form">
    C: <div class="input-append date datepicker" id="dp3" data-date="" data-date-format="dd-mm-yyyy">
        <input class="span2 full-length" size="16" type="text" value="" name="date-from">
        <span class="add-on"><i class="icon-th"></i></span>
    </div>

    По: <div class="input-append date datepicker" id="dp4" data-date="" data-date-format="dd-mm-yyyy">
        <input class="span2 full-length" size="16" type="text" value="" name="date-to">
        <span class="add-on"><i class="icon-th"></i></span>
    </div>

    <div>
        <input type="submit" value="Отправить отчёт" class="btn" />
    </div>
    <a href="?module=admins&action=sayty&uid=[uid]&action2=load" [rights]>Выгрузить сайты из биржи</a>
</form>

<br>
[sayty]
<br/><br/>
<a href="?module=admins&action=sayty&uid=[uid]&action2=add" class="button">Добавить сайт</a>
<br/>
<a href="?module=admins&action=viewusers">вернуться в список пользователей</a>