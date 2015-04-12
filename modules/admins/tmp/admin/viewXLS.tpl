<h1>Загрузка задач из Excel</h1>

<form action="admin.php?module=admins&action=xls" method="POST" enctype="multipart/form-data">

    <div class="form">

        <ul>

            <li>
                <span class="title">Биржа:</span>
                <select name="birja" id="birja"><option value=""></option><option value="gogetlinks">https://gogetlinks.net/</option><option value="getgoodlinks">http://getgoodlinks.ru</option></select>
            </li>
            <li>
                <span class="title">Файл:</span>
                <input type="file" name="xls" id="xls" />
            </li>
        </ul>
    </div>


    <div class="action_bar">
        <input type="submit" value="Загрузить" class="btn" onclick="checkBirja();" />
    </div>
</form>
