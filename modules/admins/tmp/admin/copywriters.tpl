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
    <p class="small">
        <em>За день - задачи выполненые сегодня.<br />
            За неделю - задачи выполненые с понедельника текущей недели.<br />
            За месяц - задачи выполненые с 1 числа текущего месяца.<br />
            За все время - все выполненые задачи копирайтером.<br /></em>
    </p>
    <p class="hidden-block">
        Столбец "Доверяем" означает, что у данного копирайтера не будет происходить проверка уникальности текста и данное поле он заполняет руками.
    </p>
    <p class="hidden-block">
        У тех у кого не стоит галка в данном столбце, проверятся текст задачи на кол-во знаков и уникальность текста в сервисе text.ru
    </p>
</div>
<h3>[name_stat]</h3>

<div class="form no-shadow no-background">
    <form action="" method="post" id="form">
        <input type="hidden" id="error" value="[error]" />
        <div class="wider">
            <table>
                <thead>
                    <tr style="background:#f0f0ff;">
                        <th width="100px">Копирайтеры</th>
                        <th width="70px">Выполнено задач</th>
                        <th width="70px">Сейчас в работе</th>
                        <!--<th width="50px">Доверяем</th>-->
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

<script >
    $(document).ready(function() {
        $(".trust").click(function() {
            $.post("/admin.php?module=admins&action=copywriters&action2=trust",
                    {
                        id: $(this).attr("id")
                    }, function(data) {
                alert(data);
            });
        });
    });
</script>