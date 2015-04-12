<h1>Статистика по задачам</h1>

<div style="float: left;margin-right: 30px;">
    <a href="?module=admins&action=statistics&action2=tasks&filter=weeks" class="button">За неделю</a>
</div>
<div style="float: left;margin-right: 30px;">
    <a href="?module=admins&action=statistics&action2=tasks&filter=month" class="button">За месяц</a>
</div>
<div style="float: left;margin-right: 30px;">
    <a href="?module=admins&action=statistics&action2=tasks&filter=year" class="button">За год</a>
</div>
<div style="float: left">
    <a href="?module=admins&action=statistics&action2=tasks&filter=all" class="button">За всё время</a>
</div>
<div class="clear"></div>
<br />
<div id="pChart">
    [chart]
</div>
<br /><br />

<div class="wider">
    <table>
        <thead>
            <tr style="background:#f0f0ff;">
                <th width="150px">[name_col]</td>
                <th width="200px">Выполнено полностью</td>
                <th>Только выложено</td>
            </tr>
        </thead>
        <tbody>
            [stat]
        </tbody>
    </table>
</div>
<div class="wider">
    <table>
        <thead>
            <tr style="background:#f0f0ff">
                <th width="150px" style="font-weight: bold">Всего</th>
                <th width="200px" style="font-weight: bold">[count_work]</th>
                <th style="font-weight: bold">[count_lay_out]</th>
            </tr>
        </thead>
    </table>
</div>




