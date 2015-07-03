{* Smarty *}
{include file='header/header-faq.tpl'}
<div class="row">
    <div class="col s12">
        <h3>Письмо для {$type}</h3>
    </div>
    <div class="col s12">
        <h4>{$mail_name}</h4>
    </div>
</div>
<div class="row">
    {if isset($mail)}
        <div class="col s7">
            <div class="card">
                <div class="card-content">
                    <p>
                        {$mail}
                    </p>
                </div>
            </div>
        </div>
    {/if}
</div>
{include file='footer/footer-faq.tpl'}

