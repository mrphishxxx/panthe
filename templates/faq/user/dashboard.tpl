{* Smarty *}
{include file='header/header-user.tpl'}


<div class="row">
    <div class="col s12 m12 l4">
        <div class="card-panel">
            <table>
                <tbody>
                    <tr>
                        <td><i class="material-icons help-menu-icon">trending_up</i></td>
                        <td>Кол-во бирж</td>
                        <td>5</td>
                    </tr>
                    <tr>
                        <td><i class="material-icons help-menu-icon">web</i></td>
                        <td>Кол-во сайтов</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td><i class="material-icons help-menu-icon">subject</i></td>
                        <td>Кол-во заданий</td>
                        <td>623</td>
                    </tr>
                    <tr>
                        <td><i class="material-icons help-menu-icon">payment</i></td>
                        <td>Баланс</td>
                        <td>250 р.</td>
                    </tr>
                    <tr>
                        <td><i class="material-icons help-menu-icon">speaker_notes</i></td>
                        <td>Кол-во тикетов</td>
                        <td>2/48</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card-panel ">
            <div class="card-content">
                <h5 class="center-align">Задачи</h5>
            </div>
            <ul class="collection">
                <li class="collection-item avatar">
                    <i class="material-icons circle blue">build</i>
                    <span class="title"><strong>В работе</strong></span>
                    <p>кол-во задач <br />которые выполняются</p>
                    <a href="#!" class="secondary-content">15</a>
                </li>
                <li class="collection-item avatar">
                    <i class="material-icons circle yellow">assignment_turned_in</i>
                    <span class="title"><strong>Текст готов</strong></span>
                    <p>кол-во задач <br />с написанным текстом</p>
                    <a href="#!" class="secondary-content">6</a>
                </li>
                <li class="collection-item avatar">
                    <i class="material-icons circle deep-purple">system_update_alt</i>
                    <span class="title"><strong>Текст выложен</strong></span>
                    <p>кол-во задач <br />выложенных на сайт</p>
                    <a href="#!" class="secondary-content">12</a>
                </li>
                <li class="collection-item avatar">
                    <i class="material-icons circle orange">layers_clear</i>
                    <span class="title"><strong>На доработке</strong></span>
                    <p>кол-во задач <br />дорабатывают</p>
                    <a href="#!" class="secondary-content">1</a>
                </li>
            </ul>

        </div>
    </div>
    <div class="col s12 m12 l8">
        <h4 class="center-align">Статистика выполненых задач</h4>
        <p>Данный график показывает количество выполненых задач из всех бирж, которые Вы добавили на странице <a href="/faq.php?action=userBurse">Биржи</a>.</p>
        <div id="chart_all">
            <div class="preloader-wrapper preloader-all big active">
                <div class="spinner-layer spinner-blue-only">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
        <br /><br /><br />
        <h4 class="center-align">Выполнено задач за месяц</h4>
        <div id="chart_month">
            <div class="preloader-wrapper preloader-month big active">
                <div class="spinner-layer spinner-blue-only">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="/modules/faq/js/chart_month.js"></script>
<script type="text/javascript" src="/modules/faq/js/chart_all.js"></script>
{include file='footer/footer-faq.tpl'}
