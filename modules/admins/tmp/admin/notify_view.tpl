<h1><a href="/admin.php?module=admins&action=notify&action2=parser">Оповещения</a> - <small>[function_name]</small></h1>
<h3>Действие произошло - [date]</h3>
<div class="clear"></div>

<div class="notify-view">
    <p>
        Посмотреть весь лог выгрузки: <a href="/admin.php?module=admins&action=notify&action2=parserviewlog&id=[id]" target="_blank">log</a>
    </p>
    <p>
        Статус: [fixed]
    </p>
    <a class="button" href="/admin.php?module=admins&action=notify&action2=parserlogfixed&id=[id]" [display] id="fixed_button" onclick="return false;">Подтвердить исправление</a>
    <div class="clear"><br /></div>

    <div class="output_error form" [output_errors]>
        <h3>Лог ошибок</h3>
        <textarea style="color:#767676;height:220px;" spellcheck="false">[errors]</textarea>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#fixed_button").click(function () {
            if (confirm("Подтвердить исправление ошибок?")) {
                window.location.href = $(this).attr("href");
            } else {
                return false;
            }
        });
    });
</script>