{include file="$path/includes/postman/templates/fromUser/header.tpl"}
<div style="font-size: 16px; line-height: 20px; font-weight: bold; padding: 20px 0 0 0; text-shadow: 1px 1px 0 #fff;">Добрый день, {$login}!</div>
<div style="font-size: 16px; line-height: normal; padding: 15px 0 0 0; text-shadow: 1px 1px 0 #fff;">
    На Ваш тикет - {$tid} пришел ответ от администрации сайта iForget.<br />
    Для просмотра перейдите по данной <a href='http://iforget.ru/user.php?action=ticket&action2=view&tid={$tid}' style="color: #f9a825;">ссылке</a>.
</div>
{include file="$path/includes/postman/templates/fromUser/footer.tpl"}