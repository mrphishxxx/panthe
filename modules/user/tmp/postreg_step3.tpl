<script language="javascript" src="/modules/user/js/user.js" type="text/javascript"></script>
<h1>ШАГ 3</h1>

<h3>Осталось пополнить баланс и мы можем начать работать с Вашими заявками</h3>

<div class="progress">
    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">
        75%
    </div>
</div>
<form method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp" id="webm_form">  
    <p>Я хочу пополнить счет на <input class="textfield" type="text" name="LMI_PAYMENT_AMOUNT" value="1000" id="money" style="width: 50px; display:inline;"> рублей</p>
    <input type="hidden" name="LMI_PAYEE_PURSE" value="R340688327144">
    <input type="hidden" name="LMI_PAYMENT_NO" id="payment_no" value="[payment_no]">
    <input type="hidden" name="LMI_PAYMENT_DESC" value="Balance increase iForget">
    <input type="hidden" name="LMI_PAYMER_EMAIL" value="[email]">
    <input type="hidden" id="uid" value="[uid]">
    <input type="submit" value="Пополнить через WebMoney!" id="webm_send" onclick="_gaq.push(['_trackEvent', 'Click', 'link', 'balance_wm']);return false;">
</form>
<br>
<form action="/user.php?action=payments" method="post" id="rob_form">
    <input type="hidden" name="sum" id="sum" value="">
    <input type="hidden" name="send" value="1" />
    <a href="#" id="rob_send" onclick="_gaq.push(['_trackEvent', 'Click', 'link', 'balance']);
            return false;">Пополнить другими способами</a>
</form>
<br>
<form action="/user.php?action=payments" method="post">
    <p>Воспользоваться промокодом <input type="text" name="promo" id="promo" value="[promo]" class="textfield" style="width: 100px; display:inline;"> и получить 150 рублей на свой баланс</p>
    <input type="submit" value="Активировать промокод" onclick="_gaq.push(['_trackEvent', 'Click', 'link', 'promokod']);">
</form>
<p>&nbsp;</p>

<h1>Мои платежи</h1>
<div class="wider">
    <table>
        <thead>

            <tr>
                <th>Сумма платежа</td>
                <th>Дата</td>
                <th>Статус</td>
            </tr>

        </thead>
        <tbody>
            [my_payments]
        </tbody>
    </table>
</div>
<br />
<a class="button right" href="/user.php?action=all_tasks">Пропустить этот шаг</a>
<script>
        $(document).ready(function() {
            $("#rob_send").click(function() {
                $('#sum').val($('#money').val());
                $('#rob_form').submit();
                return false;
            });

            $("#webm_send").click(function() {
                var sum = $('#money').val();
                var uid = $('#uid').val();
                $('#sum').val(sum);
                $.ajax({
                    type: "POST",
                    url: "/payments_webmoney.php",
                    data: { summa: sum, uid: uid }
                }).done(function(msg) {
                    $("#payment_no").val(msg);
                    $("#payment_no").text(msg);
                    $('#webm_form').submit();
                });
                return false;
            });



        });
</script>