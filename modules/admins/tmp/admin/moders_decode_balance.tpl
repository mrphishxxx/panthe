<h1>Расшифровка баланса</h1>
<p>[balance_title]</p>
<p><i>Заработано</i>: [earned] руб.</p>
<p><i>Выведено</i>: [withdrawn] руб.</p>
<p><i>Баланс</i>: <b>[balance] руб.</b></p>

<div class="wider">
    <form action="?module=admins&action=moders&action2=money&action3=output&moder=[id]" method="post" id="form">
        <input type='hidden' value='[earned]' id='earned' />
        <input type='hidden' value='[withdrawn]' id='withdrawn' />
        <input type='hidden' value='[balance]' id='balance' />
        
        <div class="form">
            <h3 class="title">Вывести средства</h3>
            <input type='text' value='' class='short' name='sum' id='sum' />
            <button type="submit" class="button" style="margin: -3px 0 0 10px;" id="button" onclick="return false;">Вывести</button>
        </div>
        <br />
        [pegination]
        <table class="very_small">
            <thead>

                <tr style="background:#f0f0ff;">
                    <th width="50px">Цена</th>
                    <th width="250px">Тема</th>
                    <th width="150px">Дата</th>
                    <th width="50px">Статус</th>
                </tr>

            </thead>
            <tbody>
                [zadaniya]
                <tr>
                    <td class="action-bar" colspan="8">

                        <!-- legend -->
                        <ul class="legend">
                            <li class="in-work"><span class="ico"></span>— На доработке</li>
                            <li class="done"><span class="ico"></span>— Выполнено</li>
                            <li class="ready"><span class="ico"></span>— На выкладывании</li>
                            <li class="working"><span class="ico"></span>— В работе</li>
                            <li class="vilojeno"><span class="ico"></span>— Выложено</li>
                        </ul>

                    </td>
                </tr>
            </tbody>
        </table>
        [pegination]
    </form>
</div>
<script>
    $(document).ready(function() {
        $("#button").click(function() {
            $(this).attr("disabled","disabled");
            var sum = parseInt($('#sum').val());
            var balance = parseInt($('#balance').val());
            
            if (sum !== 0 && sum !== "" && $.isNumeric(balance) && balance !== 0 && balance !== "") { 
                if ($.isNumeric(sum)) {
                    if(sum <= balance){
                        $("#form").submit();
                    } else {
                        alert("Не достаточно денег для вывода");
                    }
                } else
                    alert("Сумма для вывода должна быть числом!");
            }
            $(this).removeAttr("disabled");
        });

    });
</script>