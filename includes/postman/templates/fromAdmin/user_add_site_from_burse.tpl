Добрый день!
<br/><br/>
{if $sites}
    {foreach from=$sites item=site_url name=list key=sid}
        <p>
            В системе появился новый сайт! <a href='http://iforget.ru/admin.php?module=admins&action=sayty&uid={$uid}&action2=edit&id={$sid}'>{$site_url}</a>
        </p>
    {/foreach}
{else}
    <p>Ни одного сайта не добавлено</p>
{/if}
