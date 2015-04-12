<h1>Баланс у копирайтеров</h1>

<div class="form" style="background: white">
    <form action="" method="post" id="form">
        <input type="hidden" id="error" value="[error]" />
        <div class="wider">
            <table>
                <thead>
                    <tr style="background:#f0f0ff;">
                        <th width="150px">Копирайтеры</th>
                        <th width="150px">Выполнено</th>
                        <th width="100px">Заработано</th>
                        <th width="100px">Выведено</th>
                        <th width="100px">Баланс</th>                        
                        <th width="150px">Вывести</th>
                        <th width="50px">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    [table]
                </tbody>
            </table>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() {
        $(".ico").click(function() {
            var tr = $(this).parent().parent();
            var input = $('input', tr);
            var sum = parseInt(input.val());
            var copywriter = input.attr('id');
            var balance = parseInt($('.balance', tr).text());
            
            if (sum !== 0 && sum !== "") {
                if ($.isNumeric(sum)) {
                    if(sum <= balance){
                        $("#form").attr("action", "admin.php?module=admins&action=copywriters&action2=money&action3=output&copywriter="+copywriter+"&sum="+sum);
                        $("#form").submit();
                    } else {
                        alert("Не достаточно денег для вывода");
                    }
                } else
                    alert("Сумма для вывода должна быть числом!");
            }
        });

    });
</script>
