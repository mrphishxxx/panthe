Добрый день! <br/><br/>
Копирайтер '{$login}' выполнил задание {$id}.<br />
Данное задание <a href='http://iforget.ru/admin.php?module=admins&action=articles&action2=edit&id={$id}'>{$id}</a> поменяло статус на '{$status}'!<br />
{if $status == "Выложено"}
    Готовый текст отправлен в Sape на проверку.<br />
{/if}
{if $errors}
    <br />Во время автоматической загрузке задачи в Sape произошли ошибки:<br />
    <ul>
        {foreach from=$errors item=error name=list key=error_id}
            <li>
                error = {$error}
            </li>
        {/foreach}
    </ul>
{/if}
{if $empty_data}
    <br />
    Задача <strong>НЕ ОТПРАВЛЕНА в Sape</strong> из-за не полных данных.<br />
    Какое то из полей пустое (title, tema, keywords, description, text)!<br />
{/if}
<br />
С уважением, Администрация сайта iforget!