Добрый день!<br />
Копирайтер {$login} изменил свои данные:<br /><br />

{if isset($old['contacts']) && $old['contacts'] != $new['contacts']}
    <b>Полное имя:</b> {$new['contacts']} (было: {$old['contacts']}) <br />
{/if}
{if isset($old['wallet']) && $old['wallet'] != $new['wallet']}
    <b>Кошелек Webmoney:</b> {$new['wallet']} (было: {$old['wallet']}) <br />
{/if}
{if isset($old['mail_period']) && $old['mail_period'] != $new['mail_period']}
    <b>Периодичность системных уведомлений:</b> {$new['mail_period']} (было: {$old['mail_period']}) <br />
{/if}
{if isset($old['icq']) && $old['icq'] != $new['icq']}
    <b>ICQ:</b> {$new['icq']} (было: {$old['icq']}) <br />
{/if}
{if isset($old['scype']) && $old['scype'] != $new['scype']}
    <b>Scype:</b> {$new['scype']} (было: {$old['scype']}) <br />
{/if}
<br/>
С уважением, Администрация сайта iforget!