{* Smarty *}
{include file='header/header-faq.tpl'}
<div class="row">
    <div class="col s12">
        <h3>Письма{if !empty($type)} для {$type}{/if}</h3>
    </div>
</div>
<div class="row">
    <div class="col s12">
        <!-- <a class="waves-effect waves-light btn-large" href="/faq.php?action=mails"><i class="material-icons right">clear_all</i>Other</a> -->
        <a class="waves-effect waves-light btn-large" href="/faq.php?action=mails&type=admin"><i class="material-icons right">person_pin</i>Admin</a>
        <a class="waves-effect waves-light btn-large" href="/faq.php?action=mails&type=user"><i class="material-icons right">supervisor_account</i>User</a>
        <a class="waves-effect waves-light btn-large" href="/faq.php?action=mails&type=copywriter"><i class="material-icons right">edit</i>Copywriter</a>
        <a class="waves-effect waves-light btn-large" href="/faq.php?action=mails&type=moderator"><i class="material-icons right">visibility</i>moderator</a>
    </div>
    <br />
    {if isset($mails)}
        <div class="col s12 m8 l6">
            <div class="collection">
                {foreach from=$mails item=mail name=list key=mail_name}
                    <a href="/faq.php?action=getMail&type={$type}&view={$mail_name}" class="collection-item">{$mail}<span class="badge"><i class="material-icons">email</i></span></a>
                {foreachelse}
                    <p>&emsp;Нет ни одного письма</p>
                {/foreach}
            </div>
        </div>
    {/if}
</div>
{include file='footer/footer-faq.tpl'}

