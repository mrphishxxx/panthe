<script language="javascript" src="/modules/admins/tmp/admin/js/articles.js" type="text/javascript"></script>

<h1>Тексты для написания</h1>
<a href="?module=admins&action=articles&action2=etxt" target="_blank">Выгрузить в Etxt</a>
&emsp; | &emsp;
<a href="?module=admins&action=articles&action2=statistics">Статистика по Etxt</a>
<br /><br />

<ul class="legend legend-article" style="padding: 0">
    <li class="done"><a href="[cur_url]&status_z=vipolneno" style="border:0"><span class="ico"></span>— Выполнено</a></li>
    <li class="in-work"><a href="[cur_url]&status_z=dorabotka" style="border:0"><span class="ico"></span>— На доработке ([num_dorabotka])</a></li>
    <li class="ready"><a href="[cur_url]&status_z=navyklad" style="border:0"><span class="ico"></span>— Готов ([num_navyklad])</a></li>
    <li class="vilojeno"><a href="[cur_url]&status_z=vilojeno" style="border:0"><span class="ico"></span>— Выложено ([num_vilojeno])</a></li>
    <li class="working"><a href="[cur_url]&status_z=vrabote" style="border:0"><span class="ico"></span>— В работе ([num_vrabote])</a></li>
    <li class=""><a href="[cur_url]" style="border:0"><span class="ico"></span>Все задачи</a></li>
    <li class="not_applications"><a href="[cur_url]&status_z=new" style="border:0"><span class="ico"></span>— Не обработанные ([num_neobrabot])</a></li>
</ul>
[pegination]
<br />
<div [check_all]>
    <br />
    <input type="checkbox" class="checkAll" id="task_all_off" checked="checked" /> 
    <label class="task_all_off_label" for="task_all_off"> Снять все задачи с копирайтеров</label>
    <br />
</div>
<br />
<div class="wider">
    <table class="very_small">
        <thead>
            <tr style="background:#f0f0ff;">
                <th>etxt id<br>Копирайт.</th>
                <th>Заголовок</th>
                <th>Кол. симв.</th>
                <th>Тип</th>
                <th>Дата</th>
                <th>Статус</th>
                <th>Комм.</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            [table]
        </tbody>
    </table>
</div>
<br /><br />
[pegination]

<script>
    function confirmDelete() {
        if (confirm("Уверены что хотите удалить задачу?")) {
            return true;
        } else {
            return false;
        }
    }
</script>