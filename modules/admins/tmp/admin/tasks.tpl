<h1>Все задания со статусом "[status]"</h1>
<!-- the table -->
<form action="?module=admins&action=tasks_delete_all" method="POST" id="adminForm">
    <div class="wider">
        <input type="checkbox" id="all" style="float:right;margin: 0px 17px 10px 10px;" />
        <label for="all" style="float: right;font-weight: bold;">Выделить все</label>
        <table class="very_small">
            <thead>
                <tr style="background:#f0f0ff;">
                    <th>ETXT ID</td>
                    <th>Тема</td>
                    <th>URL</td>
                    <th>Дата</td>
                    <th class="edit"></th>
                    <th class="delete"></th>
                    <th class="delete"></th>
                </tr>
            </thead>
            <tbody>
                [zadaniya]
            </tbody>
        </table>

    </div>
    <div class="action_bar" [visible]>
        <br />
        <button type="submit" class="button" onclick="submitFunction();return false;" style="padding: 0 15px;float: right;">Удалить</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        $("#all").change(function () {
            $("input:checkbox").prop('checked', $(this).prop("checked"));
            if ($(this).prop("checked")) {
                $('button[type="submit"]').prop('disabled', false);
            } else {
                $('button[type="submit"]').prop('disabled', true);
            }
        });
        $("input:checkbox").change(function () {
            if ($(this).prop("checked")) {
                $('button[type="submit"]').prop('disabled', false);
            } else if ($("input:checked").length === 0) {
                $('button[type="submit"]').prop('disabled', true);
            }
        });
        if ($("input:checked").length === 0) {
            $('button[type="submit"]').prop('disabled', true);
        }
    });

    function submitFunction()
    {
        if (confirm("Вы уверены, что хотите удалить эти задачи?"))
        {
            $("#adminForm").submit();
        }
        else
        {
            return false;
        }
    }

</script>