<h1>Статистика по деньгам</h1>

<div style="float: left;margin-right: 30px;">
    <a href="?module=admins&action=statistics&action2=money&filter=weeks" class="button">За неделю</a>
</div>
<div style="float: left;margin-right: 30px;">
    <a href="?module=admins&action=statistics&action2=money&filter=month" class="button">За месяц</a>
</div>
<div style="float: left;margin-right: 30px;">
    <a href="?module=admins&action=statistics&action2=money&filter=year" class="button">За год</a>
</div>
<div style="float: left">
    <a href="?module=admins&action=statistics&action2=money&filter=all" class="button">За всё время</a>
</div>
<div class="clear"></div>
<br /><br />

<h2>Прибыль с задач из бирж</h2>
<br>
<div class="wider">
    <table class="[class_table]">
        <thead>
            <tr style="background:#f0f0ff;">
                <th width="50px">[name_col]</th>
                <th width="20px">15</th>
                <th width="20px" [display]>45</th>
                <th width="20px" [display]>61</th>
                <th width="20px" [display]>93</th>
                <th width="20px" [display]>60</th>
                <th width="20px" [display]>76</th>
                <th width="20px" [display]>111</th>
                <th width="20px">62</th>
                <th width="20px">78</th>
                <th width="20px">110</th>
                <th width="20px">77</th>
                <th width="20px">93</th>
                <th width="20px">128</th>
                <th width="50px">кол-во</th>
                <th width="50px">руб</th>
            </tr>
        </thead>
        <tbody>
            [stat_task]
        </tbody>
        <tfoot>
            <tr style="border-top: 1px solid #ccc;">
                <th colspan="[count_colspan]" style="font-weight: bold">Всего</th>
                <th style="font-weight: bold">[count_task]</th>
                <th style="font-weight: bold">[sum_task]</th>
            </tr>
        </tfoot>
    </table>
</div>

<br /><br />
<h2>Потрачено на задачи для Sape</h2>
<br>
<div class="wider">
    <table class="small">
        <thead>
            <tr style="background:#f0f0ff;">
                <th width="50px">[name_col]</th>
                <th width="50px">21 р.</th>
                <th width="50px">31,5 р.</th>
                <th width="50px">47,25 р.</th>
                <th width="50px">42 р.</th>
                <th width="50px">63 р.</th>
                <th width="50px">кол-во</th>
                <th width="50px">руб</th>
            </tr>
        </thead>
        <tbody>
            [stat_sape]
        </tbody>
        <tfoot>
            <tr style="border-top: 1px solid #ccc;">
                <th colspan="6" style="font-weight: bold">Всего</th>
                <th style="font-weight: bold">[count_sape]</th>
                <th style="font-weight: bold">[sum_sape]</th>
            </tr>
        </tfoot>
    </table>
</div>




