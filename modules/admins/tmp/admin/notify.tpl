<h1><a href="/admin.php?module=admins&action=notify&action2=parser">Оповещения</a> - <small>Выгрузка</small></h1>
<div class="clear"></div>
<br />

<div class="notify-filters">
    <h3>Фильтрация</h3>
    <ul class="legend legend-notify">
        <li class="done"><span class="ico"></span><a href="[cur_url]&function=get_tasks_getgoodlinks">GetGoodLinks</a></li>
        <li class="none"><span class="ico"></span><a href="[cur_url]">Все</a></li>
    </ul>
</div>
<div class="clear"></div>
<div class="form no-background no-shadow no-padding">
    <span class="title" >Выводить задания:</span>
    <select id="type" name="type" class="right full-length" onchange="location = '[cur_url]&type=' + this.options[this.selectedIndex].value;">
        <option value="all" [type_all]>Все</option>
        <option value="with_errors" [type_with_errors]>С ошибками</option>
        <option value="without_errors" [type_without_errors]>Без ошибок</option>
    </select>
</div>
<br />
<table class="notify-table">
    <thead>
        <th>Дата</th>
        <th>Время</th>
        <th>Парсер</th>
        <th>Ошибки</th>
    </thead>
    <tbody>
        [table]
    </tbody>
</table>
