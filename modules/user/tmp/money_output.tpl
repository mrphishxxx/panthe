<h1>Вывод средств</h1>
<span style="color: green">[query]</span>
<span style="color: red">[error]</span>
<p>
    <span><strong>Ваши кошельки для вывода средств</strong></span>
    <br>
    <span><strong>WMR</strong> : [webmoney]</span>
</p>
<p>
    <span><strong>Заявка на вывод средств</strong></span>
    <br><br>
    <span>На вашем счете [balance] WMR</span>
</p>
[form]
<br>
<h2>История вывода средств</h2>
<div class="form no-shadow no-background">
    <div class="wider">
        <table style="width: 350px;">
            <thead>
                <tr style="background:#f0f0ff;">
                    <th width="150px">Дата</th>
                    <th width="100px">Выведено</th>                       
                </tr>
            </thead>
            <tbody>
                [table]
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
            $(document).ready(function() {
                $("#but").click(function() {
                    var sum = parseInt($("#sum").val());
                    var balance = parseInt($("#balance").val());

                    if (sum !== 0 && sum !== "" && balance !== 0 && $.isNumeric(balance)) {
                        if ($.isNumeric(sum)) {
                            if (sum <= balance) {
                                $("#form").submit();
                            } else {
                                alert("Не достаточно денег для вывода");
                            }
                        } else {
                            if (isNaN($("#sum").val()))
                                alert("Сумма для вывода должна быть числом!");
                        }
                    }
                });

            });
</script>
