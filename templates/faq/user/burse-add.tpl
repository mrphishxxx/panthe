{* Smarty *}
{include file='header/header-user.tpl'}

<h1>Добавить биржу</h1>
<p class="valign-wrapper">
    <a class="modal-trigger valign" href="#modal1" style="display: block">
        <i class="material-icons left">help</i>Зачем добавлять биржи
    </a>
</p>

<div class="row">
    <form class="col s6" autocomplete="off">
        <div class="row">
            <div class="col s12">
                <label data-error="wrong">Биржи ссылок</label>
                <select class="browser-default">
                    <option value="" disabled selected>Выберите биржу</option>
                    <option value="1">GoGetLinks</option>
                    <option value="2">GetGoodLinks</option>
                    <option value="3">Rotapost</option>
                    <option value="4">Sape</option>
                    <option value="5">Miralinks</option>
                    <option value="6">Webartex</option>
                </select>
            </div>
            <div class="input-field col s12">
                <i class="material-icons prefix">account_circle</i>
                <input id="icon_prefix" type="text" class="validate">
                <label for="icon_prefix" data-error="wrong">Логин</label>
            </div>
            <div class="input-field col s12">
                <i class="material-icons prefix">lock</i>
                <input id="icon_password" type="text" class="validate">
                <label for="icon_password" data-error="wrong">Пароль</label>
            </div>
            
            
        </div>
    </form>
</div>

<div class="row">
    <div class="col s12">
        <a class="btn-large" href="/faq.php?action=userAddBurse"><i class="material-icons left">add</i>Добавить</a>
    </div>
</div>
<div class="row">
    <div class="col s12">
        <a class="btn-large blue-grey lighten-3" href="/faq.php?action=userBurse"><i class="material-icons left">arrow_back</i>Отменить</a>
    </div>
</div>

<div id="modal1" class="modal">
    <div class="modal-content">
        <h4><i class="material-icons left help-menu-icon">help</i>Зачем добавлять<span class="hide-on-small-only"> биржи</span>?</h4>
        <p>
            Добавлять биржи нужно для того, что мы могли брать ваши задания в работу и выполнять их.
        </p>
        <p>
            Чем больше доступов к биржам Вы добавите, тем больше задач мы выгрузим, тем больше Вы заработаете денег.
        </p>
        <p>
            Проверка новых задач в биржах проходит автоматически три раза в день.
        </p>
    </div>
    <div class="modal-footer">
        <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Закрыть</a>
    </div>
</div>
{include file='footer/footer-faq.tpl'}