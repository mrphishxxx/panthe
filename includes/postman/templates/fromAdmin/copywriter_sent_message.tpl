<html>
    <head>
        <meta charset="utf-8">
        <title>Новое сообщение от копирайтера</title>
    </head>
    <body style="margin: 0">
        <p>Добрый день!</p><br />
        <p>На одну из задач пришел ответ от копирайтера <strong>{$login}</strong>.</p>
        <p>`<em>{$message}</em>`</p>
        <p>
            Для того, чтобы ответить копирайтеру перейдите по данной ссылке: 
            <a href="http://iforget.ru/admin.php?module=admins&action={if !$burse}'articles'{else}'zadaniya'{/if}&action2=edit&id={$id}">Задание № {$id}</a>.
        </p> 
        <p>С уважением, Администрация сайта iforget!</p>
    </body>
</html>
