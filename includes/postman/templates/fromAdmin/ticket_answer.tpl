<html>
    <head>
        <meta charset="utf-8">
        <title>Новое сообщение в тикете</title>
    </head>
    <body style="margin: 0">
        <p>Добрый день!</p>
        <p>На один из тикетов пришел ответ от {if $type && $type == "user"}пользователя{else}копирайтера{/if}.</p> 
        <p>Для просмотра <a href="http://iforget.ru/admin.php?module=admins&action=ticket&action2=view&tid={$id}">перейдите по данной ссылке</a>.</p> 
        <p>Спасибо!</p>
    </body>
</html>