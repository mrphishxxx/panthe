<h3>Письмо о поднятии цен отправлено на эти почтовые ящики</h3>
<br />
<ul>
    {if $users}
        {foreach from=$users item=user name=list key=user_id}
            <li>
                {$user['login']} ({$user['email']})
            </li>
        {/foreach}
    {else}
        <li>Массив пользователей пуст</li>
    {/if}
</ul>