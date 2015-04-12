<!-- the table -->
<div class="wider">

<h1>Партнёрская страница системы iForget</h1>

<div class="alert">
	<p>Мы готовы оплачивать <b>3 рубля</b> с каждой оплаченной заявки!</p>
	<p>Ваша индивидуальная ссылка для привлечения пользователей:<br/>[partner_link]</p>
</div>

<div>
	<table width="100%" border="1">
		<tr>
			<th>Привлеченных вебмастеров:</th>
			<td>[reged_users]</td>
		</tr>
		<tr>
			<th>Добавили сайтов:</th>
			<td>[new_sites]</td>
		</tr>
		<tr>
			<th>Оплатили заявок:</th>
			<td>[payed_tasks]</td>
		</tr>
		<tr>
			<th>Всего заработано:</th>
			<td>[earned]</td>
		</tr>
                <tr>
			<th>Выведено средств:</th>
			<td>[derived_money]</td>
		</tr>
                <tr>
			<th>Остаток средств:</th>
			<td>[balance_of_money]</td>
		</tr>
	</table>
</div>
<br />
[check_wallet_user]
<a href="/user.php?module=user&amp;action=output_to_balance" class="button" style="float: right;margin-right: 15px;">Вывод в баланс</a>
<br /><br /><br />
<div>
    <table style="width: 500px;">
        <tr>
            <td>
                <b>Размер:480x60. Формат: gif</b>
            </td>
            <td>
                <img src="/images/partners-img/iForget1.gif">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <textarea onclick="select();">&lt;a target="blank" href="[partner_link]"&gt;&lt;img src="http://iforget.ru/images/partners-img/iForget1.gif" width="480" height="60"&gt;&lt;/a&gt;</textarea>
            </td>
        </tr>

         
        <tr>
            <td style="padding-top: 35px">
                <b>Размер:480x60. Формат: gif</b>
            </td>
            <td style="padding-top: 35px;">
                <img src="/images/partners-img/iForget.gif" style="width: 468px">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <textarea onclick="select();">&lt;a target="blank" href="[partner_link]"&gt;&lt;img src="http://iforget.ru/images/partners-img/iForget.gif" width="480" height="60"&gt;&lt;/a&gt;</textarea>
            </td>
        </tr>
        
        
        <tr>
            <td style="padding-top: 35px">
                <b>Размер:240x400. Формат: gif</b>
            </td>
            <td style="padding-top: 35px;">
                <img src="/images/partners-img/240x400-iforget.gif" style="float: right">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <textarea onclick="select();">&lt;a target="blank" href="[partner_link]"&gt;&lt;img src="http://iforget.ru/images/partners-img/240x400-iforget.gif" width="240" height="400"&gt;&lt;/a&gt;</textarea>
            </td>
        </tr>
    </table>
</div>
</div>
<script>
function checkWalletUser()
{
    alert("Для вывода денег заполните поле кошелек в личной карточке!\r\n");
    return false;
}
</script>