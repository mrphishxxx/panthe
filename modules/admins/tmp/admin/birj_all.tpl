<h1>Доступы к биржам</h1>
<div class="clear"></div>
<br />

[birjs]
<br />
<br />

<table>
    <tr style="background: white">
        <td width="30px">
            <div style="width:30px;height:30px;background:#d7d7d7;">&nbsp</div>
        </td>
        <td style="text-align: left"> - доступ не подходит</td>
    </tr>
</table>


<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $(".burse-checkbox-active").click(function () {
            var input = $(this);
            var tr = $(input).parent().parent();
            var color = "";


            $.post("/admin.php?module=admins&action=birj&action2=activating", {
                id: $(this).attr("id")
            }, function (data) {
                if (data === "Биржа активирована!") {
                    if ($(tr).hasClass("odd")) {
                        color = "#FFF";
                    } else {
                        color = "#FFFFF0";
                    }
                    $(tr).css("background", color);
                } else {
                    $(tr).css("background", "#d7d7d7");
                }
            });
        });
    });
</script>
