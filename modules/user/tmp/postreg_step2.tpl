<script language="javascript" src="/modules/user/js/user.js" type="text/javascript"></script>
<h1>ШАГ 2</h1>

<h3>Теперь добавьте Ваши сайты</h3>

<div class="progress">
    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
        50%
    </div>
</div>

[add_site]
[load_site_ggl]
[load_site_getgoodlinks]
[load_site_sape]
[load_site_rotapost]
<div class="clear"></div>
<br />
<br />
<a href="/user.php?module=user&action=sayty&uid=[uid]&action2=add" class="button">Добавить сайт</a>
<br /><br />
<div class="wider">
    <form action="remove" method="post">
        <table>
            <thead>

                <tr>
                    <th>url</th>
                    <th>Тематика</th>
                    <th class="edit"></th>
                </tr>
            </thead>
            <tbody>
                [sayty]
            </tbody>
        </table>
</div>
<br />
[link]
<br />
<br />

