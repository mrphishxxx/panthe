<h1>Модераторы</h1>
<div [not_access_manager]>
    <div style="float: left;margin-right: 30px;">
        <a href="?module=admins&action=moders&filter=day" class="button">За день</a>
    </div>
    <div style="float: left;margin-right: 30px;">
        <a href="?module=admins&action=moders&filter=weeks" class="button">За неделю</a>
    </div>
    <div style="float: left;margin-right: 30px;">
        <a href="?module=admins&action=moders&filter=month" class="button">За месяц</a>
    </div>
    <div style="float: left">
        <a href="?module=admins&action=moders&filter=all" class="button">За всё время</a>
    </div>
    <div class="clear"></div>
    <p>
        <em>За день - задачи выполненые сегодня.<br />
        За неделю - задачи выполненые с понедельника текущей недели.<br />
        За месяц - задачи выполненые с 1 числа текущего месяца.<br />
        За все время - все выполненые (выложенные) задачи модератором.<br /></em>
    </p>
    <br />
</div>
<h3>[name_stat]</h3>

<div class="form no-shadow no-background">
    <form action="" method="post" id="form">
        <input type="hidden" id="error" value="[error]" />
        <div class="wider">
            <table>
                <thead>
                    <tr style="background:#f0f0ff;">
                        <th width="250px">Копирайтеры</th>
                        <th width="150px">Выполнено задач</th>
                        <!--<th width="100px">Сейчас в работе</th>-->
                    </tr>
                </thead>
                <tbody>
                    [table]
                </tbody>
            </table>
        </div>
    </form>
</div>




