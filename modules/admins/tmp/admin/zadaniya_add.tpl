<script language="javascript" src="/modules/admins/tmp/admin/js/index.js" type="text/javascript"></script>
<h1>Новое задание для сайта [url] пользователя [login]</h1>

[noporno]

[themes]

<form action="" method="post" id="admin_form">

    <div class="form">

        <ul>

            <li style="padding-bottom: 5px;">
                <span class="title">Выкладывание текста:</span>
                <input style="top:8px;" type="checkbox" name="lay_out" id="lay_out">
            </li>
            <li>
                <span class="title">Система:</span>
                <select id="sistema" name="sistema">
                    [sistema1]
                </select>    
                <!--<input type="text" name="sistema" id="sistema" class="full-length"'>-->
            </li>
            <li>
                <span class="title" >Тип задания:</span>
                <select id="type" name="type">
                    <option value="0" [type0]>Статья</option>
                    <option value="1" [type1]>Обзор</option>
                    <option value="2" [type2]>Новость</option>
                </select>
            </li>
            <li>
                <span class="title">Анкор:</span>
                <input type="text" name="ankor" id="ankor" class="full-length">
            </li>
            <li>
                <span class="title">Анкор 2:</span>
                <input type="text" name="ankor2" class="full-length" value=''>
            </li>
            <li>
                <span class="title">Анкор 3:</span>
                <input type="text" name="ankor3" class="full-length" value=''>
            </li>
            <li>
                <span class="title">Анкор 4:</span>
                <input type="text" name="ankor4" class="full-length" value=''>
            </li>
            <li>
                <span class="title">Анкор 5:</span>
                <input type="text" name="ankor5" class="full-length" value=''>
            </li>
            <li>
                <span class="title">Ссылка, куда ведёт:</span>
                <input type="text" name="url" id="url" class="full-length">
            </li>
            <li>
                <span class="title">URL 2:</span>
                <input type="text" name="url2" class="full-length" value=''>
            </li>
            <li>
                <span class="title">URL 3:</span>
                <input type="text" name="url3" class="full-length" value=''>
            </li>
            <li>
                <span class="title">URL 4:</span>
                <input type="text" name="url4" class="full-length" value=''>
            </li>
            <li>
                <span class="title">URL 5:</span>
                <input type="text" name="url5" class="full-length" value=''>
            </li>
            <li>
                <span class="title">Ключевые слова:</span>
                <textarea name="keywords" id="keywords" class="full-length"></textarea>
            </li>
            <li>
                <span class="title">Тема статьи:</span>
                <input type="text" name="tema" id="tema" class="full-length">
            </li>
            <li>
                <span class="title">Комментарий:</span>
                <textarea name="comments" id="comments" class="full-length"></textarea>
            </li>
            <li>
                <span class="title">Комментарии работников:</span>
                <textarea name="admin_comments" id="admin_comments" class="full-length"></textarea>
            </li>
            <li>
                <span class="title">Статья:</span>
                <textarea name="text" id="text" class="full-length"></textarea>
            </li>
            <li>
                <span class="title">Картинка:</span>
                <input type="text" name="url_pic" id="url_pic" class="full-length">
            </li>
            <li>
                <span class="title">Ссылка на статью:</span>
                <input type="text" name="url_statyi" id="url_statyi" class="full-length">
            </li>
            <li>
                <span class="title">Стоимость:</span>
                <input type="text" name="price" id="price" class="full-length">
            </li>
            <li>
                <span class="title">Статус:</span>
                <input type="radio" name="task_status" value="dorabotka"> На доработке
            </li>
            <li>
                <span class="title"></span>
                <input type="radio" name="task_status" value="vilojeno"> Выложено
            </li>
            <li>
                <span class="title"></span>
                <input type="radio" name="task_status" value="vipolneno"> Выполнено
            </li>
            <li>
                <span class="title"></span>
                <input type="radio" name="task_status" value="vrabote"> В работе
            </li>
            <li>
                <span class="title"></span>
                <input type="radio" name="task_status" value="navyklad"> На выкладывании
            </li>
        </ul>
    </div>


    <div class="action_bar">
        <input type="hidden" name="send" value="1">
        <input type="submit" value="Сохранить" /><br/><br/>
        <input type="button" value="Вернуться" onclick="location.href = '?module=admins&action=zadaniya&uid=[uid]&sid=[sid]'" style="width:196px;">
    </div>
</form>

