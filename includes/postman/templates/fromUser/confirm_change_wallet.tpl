{include file="$path/includes/postman/templates/fromUser/header.tpl"}
<div style="font-size: 16px; line-height: 20px; font-weight: bold; padding: 20px 0 0 0; text-shadow: 1px 1px 0 #fff;">Добрый день, {$login}!</div>
<div style="font-size: 16px; line-height: normal; padding: 15px 0 0 0; text-shadow: 1px 1px 0 #fff;">
    Вы запросили изменение кошелька. <br />
    Новый кошелек - {$wallet}!
</div>
<div style="font-size: 16px; line-height: normal; padding: 10px 0 0 0; text-shadow: 1px 1px 0 #fff;">
    Подтвердите данный запрос, перейдя по этой <a href='http://iforget.ru/user.php?action=change_wallet&action2=confirm&code={$code}' style="color: #f9a825;">ссылки</a>.
</div>
{include file="$path/includes/postman/templates/fromUser/footer.tpl"}