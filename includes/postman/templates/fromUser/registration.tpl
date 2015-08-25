{include file="$path/includes/postman/templates/fromUser/header.tpl"}
<div style="font-size: 16px; line-height: 20px; font-weight: bold; padding: 20px 0 0 0; text-shadow: 1px 1px 0 #fff;">Здравствуйте {$login}!</div>
<div style="font-size: 16px; line-height: normal; padding: 15px 0 0 0; text-shadow: 1px 1px 0 #fff;">
    Поздравляем! <br />
    Вы успешно зарегистрировались в статусе Вебмастера.
    <br /><br />
    <strong>Ваш логин:</strong> {$login}
    <br /><br />
    <strong>Ваш пароль:</strong> {$password}
    <br /><br />

    Для начала работы нужно пройти несколько простых шагов:
    <ul>
        <ol>1. <a href="http://iforget.ru/user.php?action=birj" style="color: #f9a825;">Добавить биржу ссылок</a></ol>
        <ol>2. <a href="http://iforget.ru/user.php?action=sayty" style="color: #f9a825;">Добавить площадку (сайт)</a></ol>
        <ol>3. <a href="http://iforget.ru/user.php?action=payments" style="color: #f9a825;">Пополнить баланс</a></ol>
        <ol>4. <a href="http://iforget.ru/user.php?action=lk" style="color: #f9a825;">Управление личными кабинетом</a></ol>
    </ul>
    <br />
    Также можете следовать нашим шагам при первом заходе в личный кабинет.
</div>
{include file="$path/includes/postman/templates/fromUser/footer.tpl"}