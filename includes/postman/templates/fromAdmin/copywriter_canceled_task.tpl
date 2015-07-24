Добрый день! <br/>
Копирайтер <strong>{$login}</strong> отказался от 
задания <a href='http://iforget.ru/admin.php?module=admins&action=articles&action2=edit&id={$id}'>{$id}</a>.<br/>
Задание переведено в статус Активен. Поле текст очищено.<br/>
{if $banned}
    <br />
    <em>
        Данный копирайтер отказался от задачи уже <strong>{$limit} раза</strong>! 
        Он переведён в статус <strong>Забанен</strong>. Больше ему не показываются новые задачи!
    </em>
    <br />
    <br />
{/if}
С уважением, Администрация сайта iforget!