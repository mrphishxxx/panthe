<script language="javascript" src="/modules/admins/tmp/admin/js/index.js" type="text/javascript"></script>
<h1>Новое задание для сайта [url] пользователя [login]</h1>

<p>[nomoney]</p>

<form action="" method="post" id="admin_form">

    <div class="form">

        <ul>

            <li>
                <span class="title">Система:</span>
                <input type="text" name="sistema" id="sistema" class="full-length" value='[sistema]' readonly='readonly'>
            </li>
            <li>
                <span class="title">Анкор:</span>
                <input type="text" name="ankor" id="ankor" class="full-length" value='[ankor]' readonly='readonly'>
            </li>
            <li>
                <span class="title">Ссылка, куда ведёт:</span>
                <input type="text" name="url" id="url" class="full-length" value='[url]' readonly='readonly'>
            </li>
            <li>
                <span class="title">Ключевые слова:</span>
                <textarea name="keywords" id="keywords" class="full-length" readonly='readonly'>[keywords]</textarea>
            </li>
            <li>
                <span class="title">Тема статьи:</span>
                <input type="text" name="tema" id="tema" class="full-length" value='[tema]' readonly='readonly'>
            </li>
            <li>
                <span class="title">Комментарий:</span>
                <textarea name="comments" id="comments" class="full-length" readonly='readonly'>[comments]</textarea>
            </li>
            <li>
                <span class="title">Статья:</span>
                <textarea name="text" id="text" class="full-length" readonly='readonly'>[text]</textarea>
                [etxt_action]
            </li>
            <li>
                <span class="title">Уникальность текста:</span>
                [uniq]%
            </li>
            <!--<li style="display:none">
                    <span class="title">Картинка:</span>
                    <input type="text" name="url_pic" id="url_pic" class="full-length" value='[url_pic]' readonly='readonly'>
            </li>-->
            <li>
                <span class="title">Ссылка на статью:</span>
                <input type="text" name="url_statyi" id="url_statyi" class="full-length" value='[url_statyi]' readonly='readonly'>
            </li>
            <!--
                                                                            <li>
                                                                                    <span class="title">Etxt:</span>
                                                                                    <input type="text" name="etxt" value='[etxt]' id="etxt" class="full-length">
                                                                            </li>
                                                                            <li>
                                                                                    <span class="title">Стоимость:</span>
                                                                                    <input type="text" name="price" id="price" class="full-length" value='[price]'>
                                                                            </li>
                                                                            <li>
                                                                                    <span class="title">Статус:</span>
                                                                                    <input type="radio" name="task_status" value="dorabotka" [dorabotka]> На доработке
                                                                            </li>
                                                                            <li>
                                                                                    <span class="title"></span>
                                                                                    <input type="radio" name="task_status" value="vilojeno" [vilojeno]> Выложено
                                                                            </li>
                                                                            <li>
                                                                                    <span class="title"></span>
                                                                                    <input type="radio" name="task_status" value="vipolneno" [vipolneno]> Выполнено
                                                                            </li>
                                                                            <li>
                                                                                    <span class="title"></span>
                                                                                    <input type="radio" name="task_status" value="vrabote" [vrabote]> В работе
                                                                            </li>
                                                                            <li>
                                                                                    <span class="title"></span>
                                                                                    <input type="radio" name="task_status" value="navyklad" [navyklad]> На выкладывании
                                                                            </li>
            !-->
        </ul>
    </div>


    <div class="action_bar">
        <input type="hidden" name="send" value="1">
        <input type="button" class="button" value="Вернуться" onclick="history.back();" /><br/><br/>

        <br/><br/><a href="/user.php?action=ticket&zid=[tid]">Есть вопрос?</a>
    </div>
</form>
