{* Smarty *}
{include file='header/header-user.tpl'}

<h1>Биржи ссылок</h1>
<p class="valign-wrapper">
    <a class="modal-trigger valign" href="#modal1" style="display: block">
        <i class="material-icons left">help</i>Зачем добавлять биржи
    </a>
</p>
<div class="row">
    <div class="col s12">
        <table class="striped">
            <thead>
                <tr>
                    <th data-field="id">Биржа</th>
                    <th data-field="name">Логин</th>
                    <th data-field="password">Пароль</th>
                    <th data-field="status">Статус</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>GoGetLinks</td>
                    <td>ya-seo-guru@yandex.ru</td>
                    <td>treyioesc5324</td>
                    <td><i class="material-icons left">check</i><span class="hide-on-med-and-down">Работает</span></td>
                </tr>
                <tr>
                    <td>GetGoodLinks</td>
                    <td>ya-seo-guru@yandex.ru</td>
                    <td>y0694kfjsw</td>
                    <td><span class="tooltipped hover-tooltip" data-position="top" data-delay="100" data-tooltip="Все сайты заблокированы"><i class="material-icons left">warning</i><span class="hide-on-med-and-down">Не работает</span></td>
                </tr>
                <tr>
                    <td>Rotapost</td>
                    <td>ya-seo-guru@yandex.ru</td>
                    <td>60798gkjf09530s</td>
                    <td><span class="tooltipped hover-tooltip" data-position="top" data-delay="100" data-tooltip="Логин или пароль не подходит"><i class="material-icons left">warning</i><span class="hide-on-med-and-down">Не работает</span></td>
                </tr>
                <tr>
                    <td>Sape</td>
                    <td>yaseoguru</td>
                    <td>qrev24t5g2e</td>
                    <td><i class="material-icons left">check</i><span class="hide-on-med-and-down">Работает</span></td>
                </tr>
                <tr>
                    <td>Miralinks</td>
                    <td>romakson</td>
                    <td>tp6049fkse4</td>
                    <td><i class="material-icons left">check</i><span class="hide-on-med-and-down">Работает</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col s6 m4 l2">
        <a class="btn-large" href="/faq.php?action=userAddBurse"><i class="material-icons left">add</i>Добавить</a>
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