
<h1>Задания выкладывальщика</h1>

<a href="/user.php?module=user&action=zadaniya_moder&action2=csv&uid=[viklad_id]">Выгрузить готовые заявки в Excel</a> | <a href="[cur_url]&status_z=all">Показать ВСЕ заявки</a>

<div class="form no-background no-shadow">
    <ul>
        <li>
            <span class="title"><b>Фильтр по домену:</b></span>
            <select name="domain_f" id="domain_f" class="full-length">
                [available_domains]
            </select>
        </li>
        <li style="padding-top: 25px;">
            <span class="title" style="float: left"><b>Фильтр по статусу:</b></span>
            <ul class="legend" style="padding: 0">
                <li class="done"><a href="[cur_url]&status_z=vipolneno" style="border:0"><span class="ico"></span>— Выполнено</a></li>
                <li class="in-work"><a href="[cur_url]&status_z=dorabotka" style="border:0"><span class="ico"></span>— На доработке</a></li>
                <li class="ready"><a href="[cur_url]&status_z=navyklad" style="border:0"><span class="ico"></span>— На выкладывании</a></li>
                <li class="vilojeno"><a href="[cur_url]&status_z=vilojeno" style="border:0"><span class="ico"></span>— Выложено</a></li>
                <li class="vilojeno"><a href="[cur_url]&status_z=all" style="border:0">Все</a></li>
            </ul>
        </li>
    </ul>
</div>

[pegination]

[zadaniya]
<br>
