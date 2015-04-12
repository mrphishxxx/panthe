<!-- the table -->
<div class="wider">
    <form action="remove" method="post">
        <table>
            <thead>

                <tr>
                    <th class="title" style="width: 25px">&nbsp;</th>
                    <th class="title">&nbsp;</th>
                    <th width="150px">Логин</th>
                    <th>Тип</th>
                    <th>В работе</th>
                    <th class="tasks">Сайты</th>
                    <th class="tasks">Баланс</th>
                    <th class="tasks" [not_access_manager]>Стоим.</th>
                    <th class="edit"></th>
                    <th class="edit" [not_access_manager]></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="action-bar" colspan="9">
                        <a href="?module=admins&action=add" class="button">Добавить пользователя</a>
                    </td>
                </tr>
                [admins]
            </tbody>
        </table>
</div>

<br/><br/>
