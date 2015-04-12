<h1>Задания сайта [url] пользователя [login]</h1>

<div class="well">
    <form action="?module=admins&action=zadaniya&uid=[uid]&sid=[sid]" method="POST" class="form">
        <div><b>Фильтр</b></div>
        C: <div class="input-append date datepicker" id="dp3" data-date="[dfrom]" data-date-format="dd-mm-yyyy">
            <input class="span2 full-length" size="16" type="text" value="[dfrom]" name="date-from">
            <span class="add-on"><i class="icon-th"></i></span>
        </div>

        По: <div class="input-append date datepicker" id="dp4" data-date="[dto]" data-date-format="dd-mm-yyyy">
            <input class="span2 full-length" size="16" type="text" value="[dto]" name="date-to">
            <span class="add-on"><i class="icon-th"></i></span>
        </div>

        <div>
            <input type="submit" value="Применить" class="btn" />
        </div>
    </form>
</div>

<a href="?act=getXLS&uid=[uid]&sid=[sid]&from=[dfrom]&to=[dto]" target="_blank">Выгрузить в Эксель</a> | <a href="?module=admins&action=zadaniya&action2=etxt&uid=[uid]&sid=[sid]" target="_blank">Выгрузить в Etxt</a> | <a href="?module=admins&action=zadaniya&uid=[uid]&sid=[sid]&showall=1">Показать ВСЕ задания</a>
<br/><br/>
<a href="?module=admins&action=sayty&uid=[uid]">вернуться в список сайтов пользователя [login]</a><br /><br />
<a href="?module=admins&action=zadaniya&uid=[uid]&sid=[sid]&action2=add" class="button">Добавить задание</a>
<br/><br/>
[pegination]

<br/><br/>
[zadaniya]
<br><br/>
<a href="?module=admins&action=sayty&uid=[uid]">вернуться в список сайтов пользователя [login]</a>
