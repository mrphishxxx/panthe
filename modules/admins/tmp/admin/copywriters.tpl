<h1>Копирайтеры</h1>
<div [not_access_manager]>
    <div style="float: left;margin-right: 30px;">
        <a href="?module=admins&action=copywriters&filter=day" class="button">За день</a>
    </div>
    <div style="float: left;margin-right: 30px;">
        <a href="?module=admins&action=copywriters&filter=weeks" class="button">За неделю</a>
    </div>
    <div style="float: left;margin-right: 30px;">
        <a href="?module=admins&action=copywriters&filter=month" class="button">За месяц</a>
    </div>
    <div style="float: left">
        <a href="?module=admins&action=copywriters&filter=all" class="button">За всё время</a>
    </div>
    <div class="clear"></div>
    <p>
        <em>За день - задачи выполненые сегодня.<br />
        За неделю - задачи выполненые с понедельника текущей недели.<br />
        За месяц - задачи выполненые с 1 числа текущего месяца.<br />
        За все время - все выполненые задачи копирайтером.<br /></em>
    </p>
    <br />
</div>
<h3>[name_stat]</h3>

<div class="form" style="background: white">
    <form action="" method="post" id="form">
        <input type="hidden" id="error" value="[error]" />
        <div class="wider">
            <table>
                <thead>
                    <tr style="background:#f0f0ff;">
                        <th width="100px">Копирайтеры</th>
                        <th width="70px">Выполнено задач</th>
                        <th width="70px">Сейчас в работе</th>
                        <th width="50px">Забанить</th>
                    </tr>
                </thead>
                <tbody>
                    [table]
                </tbody>
            </table>
        </div>
    </form>
</div>

