<h1>Пополнение баланса</h1>

<form method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp" id="webm_form">  
    <p>Я хочу пополнить счет на <input class="textfield" type="text" name="LMI_PAYMENT_AMOUNT" value="300" id="money" style="width: 50px; display:inline;"> рублей</p>
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
    <!--<p>Я хочу пополнить счет на <input class="textfield" type="text" name="sum" value="300" id="sum" style="width: 50px; display:inline;"> рублей</p>-->
    <input type="hidden" name="send" value="1" />
    <!--<input type="submit" value="Пополнить через Робокассу" onclick="_gaq.push(['_trackEvent', 'Click', 'link', 'balance']);">-->
    <a href="#" id="rob_send" onclick="_gaq.push(['_trackEvent', 'Click', 'link', 'balance']);
            return false;">Пополнить другими способами</a>
</form>
<br>
<form action="/user.php?action=payments" method="post">
    <p>У меня есть промокод <input type="text" name="promo" id="promo" value="[promo]" class="textfield" style="width: 100px; display:inline;"></p>
    <input type="submit" value="Активировать промокод" onclick="_gaq.push(['_trackEvent', 'Click', 'link', 'promokod']);">
</form>



<p>&nbsp;</p>

<h1>Мои платежи</h1>
[pegination]
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