<script language="javascript" src="/modules/user/js/user.js" type="text/javascript"></script>
<h1>ШАГ 1</h1>

<h3>Для начала работы предоставьте доступ для биржи ссылок</h3>

<div class="progress">
    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%">
        25%
    </div>
</div>


<!-- add exchange -->
<div class="add-exchange">
    <h3>Добавить биржу</h3>
    <form action="/user.php?action=postreg_step1" method="post" id="birj_form" autocomplete="off">
        <div class="form">
            <ul>
                <li>
                    <span class="title">Биржа:</span>
                    <select class="wide" name="bid" id="bid">
                        [burse]
                    </select>
                </li>
                <li>
                    <span class="title">Логин:</span>
                    <input type="text" class="full-length" value="" placeholder="" name="login" id="loginp" />
                </li>
                <li>
                    <span class="title">Пароль:</span>
                    <input type="text" class="full-length" value="" placeholder="" name="password" id="pass" />
                </li>
            </ul>
        </div>
        <div class="action_bar">
            <input type="hidden" value="[uid]" name="uid2" />
            <input type="submit" value="Добавить" onclick="addUserBirj();return false;" />
        </div>
    </form>
</div>



<br/>

<div class="wider">
    <form action="remove" method="post">
        <table style="border-bottom: 1px solid #ccc; padding-bottom: 20px;">
            <thead>

                <tr>
                    <th class="title">Название</th>
                    <th class="login">Логин</th>
                    <th class="password">Пароль</th>
                    <th class="edit"></th>
                    <th class="close right-corner"><div></div></th>
            </tr>

            </thead>
            <tbody>
                [birjs]
            </tbody>
        </table>
    </form>
</div>
<br />
[link]
<br />
<br />

