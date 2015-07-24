<html>
    <head>
        <meta charset="utf-8">
        <title>Поздравляем Вас с успешной регистрацией на сайте iforget.ru</title>
    </head>
    <body style="margin: 0">
        <div style="text-align: center; font-family: tahoma, arial; color: #3d413d;">
            <div style="width: 650px; margin: 0 auto; text-align: left; background: #f1f0f0;">
                <div style="width: 650px; height: 89px; background: {if $get_text}url(images/header_bg.jpg){else}url(cid:header_bg){/if}; text-align: center;">
                    <div style="width: 575px; margin: 0 auto; text-align: left;">
                        <a style="float: left; margin: 15px 0 0 0;" target="_blank" href="http://iforget.ru">
                            <img style="border: 0;" src="{if $get_text}images/logo_main.jpg{else}cid:logo_main{/if}" alt=".">
                        </a>
                        <div style="color: #fff; font-size: 22px; float: left; margin: 12px 0 0 0; line-height: 60px;">- работает сутки напролёт</div>
                        <a style="float: right; font-size: 16px; color: #3d413d; margin: 30px 0 0 0;" href="http://iforget.ru/user.php?uid={$id}">Войти</a>
                    </div>
                </div>
                <div style="padding: 0 38px 0 38px;">
                    <div style="font-size: 16px; line-height: 20px; font-weight: bold; padding: 20px 0 0 0; text-shadow: 1px 1px 0 #fff;">Здравствуйте!</div>
                    <div style="font-size: 16px; line-height: normal; padding: 15px 0 0 0; text-shadow: 1px 1px 0 #fff;">
                        Поздравляем! Вы успешно зарегистрировались в статусе копирайтера.
                        <br /><br />
                        <strong>Ваш логин:</strong> {$login}
                        <br /><br />
                        <strong>Ваш пароль:</strong> {$password}
                        <br /><br />
                    </div>
                    <br />
                    Ваша работа будет осуществляться через личный кабинет пользователя. 
                    <br />
                    {if $network}
                        Для входа в личный кабинет используйте аккаунт {$network} или логин и пароль.
                    {else}
                        Для входа в личный кабинет используйте логин и пароль, указанные при регистрации.
                    {/if}
                    <br /><br />
                    Желаем Вам удачи!
                    <br /><br />
                    Оставить и почитать отзывы Вы сможете в нашей ветке на <a href="http://searchengines.guru/showthread.php?p=12378271">серчах</a>
                    <br />

                    С Уважением, Администрация сервиса iforget.ru
                </div>
                <br />
                <div style="font-size: 13px; line-height: normal; text-shadow: 1px 1px 0 #fff; padding: 20px 60px 0 60px;">Вы получили это письма так как зарегистрировались в системе iforget.ru<br> </div>
                <div style="font-size: 13px; background: #fff; border-top: 1px solid #d7d7d7; height: 50px; line-height: 50px; text-align: center; margin: 20px 0 0 0;">© 2014 iForget — система автоматической монетизации</div>
            </div>
        </div>
    </body>
</html>
