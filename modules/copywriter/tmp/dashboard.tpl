<h1>Новые задачи для написания</h1>
[error]
<div class="wider">
    <table class="very_small">
        <thead>
            <tr style="background:#f0f0ff;">
                <th style="width: 250px;">Заголовок</th>
                <th style="width: 30px;">Кол. симв.</th>
                <th style="width: 30px;">Тип</th>
                <th style="width: 50px;">Качество</th>
                <th style="width: 50px;">Цена статьи</th>
                <th style="width: 20px;">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            [table]
        </tbody>
    </table>
</div>

<input type="hidden" id="count_vrabote" value="[count_vrabote]" />
<script type="text/javascript">
    $(document).ready(function () {
        if ($("#count_vrabote").val() === "1") {
            $(".add").each(function () {
                $(this).find("a").css("opacity", 0.3);
            });

            $(".add a").click(function () {
                if (confirm("Вы уже подтвердили 5 задач. Чтобы взять в работу ещё, выполните какую-нибудь из уже подтверждённых")) {
                    window.location.href = "/copywriter.php?action=tasks";
                    return false;
                } else {
                    return false;
                }
            });
        }
    });
</script>
