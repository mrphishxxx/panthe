<script language="javascript" src="/modules/admins/tmp/admin/js/index.js" type="text/javascript"></script>
<h1>Новое задание для сайта [url_sayta] пользователя [login]</h1>

<span class="title">Добавлена: [date]</span>
<br />
[nomoney]

[noporno]

[themes]
<form action="" method="post" id="admin_form">

    <div class="form">

        <ul>
            <li style="padding-bottom: 5px;">
                <span class="title">Выкладывание текста:</span>
                <input style="top:8px;" type="checkbox" name="lay_out" id="lay_out" [lay_out_text] />
            </li>
            <li>
                <span class="title" >Система:</span>
                <select id="sistema" name="sistema">
                    [sistema1]
                </select>    
                <!--<input type="text" name="sistema" id="sistema" class="full-length" value='[sistema]'>-->
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
                <input type="text" name="ankor" id="ankor" class="full-length" value='[ankor]'>
            </li>
            <li>
                <span class="title">Анкор 2:</span>
                <input type="text" name="ankor2" class="full-length" value='[ankor2]'>
            </li>
            <li>
                <span class="title">Анкор 3:</span>
                <input type="text" name="ankor3" class="full-length" value='[ankor3]'>
            </li>
            <li>
                <span class="title">Анкор 4:</span>
                <input type="text" name="ankor4" class="full-length" value='[ankor4]'>
            </li>
            <li>
                <span class="title">Анкор 5:</span>
                <input type="text" name="ankor5" class="full-length" value='[ankor5]'>
            </li>
            <li>
                <span class="title">Ссылка, куда ведёт:</span>
                <input type="text" name="url" id="url" class="full-length" value='[url]'>
            </li>
            <li>
                <span class="title">URL 2:</span>
                <input type="text" name="url2" class="full-length" value='[url2]'>
            </li>
            <li>
                <span class="title">URL 3:</span>
                <input type="text" name="url3" class="full-length" value='[url3]'>
            </li>
            <li>
                <span class="title">URL 4:</span>
                <input type="text" name="url4" class="full-length" value='[url4]'>
            </li>
            <li>
                <span class="title">URL 5:</span>
                <input type="text" name="url5" class="full-length" value='[url5]'>
            </li>
            <li>
                <span class="title">Ключевые слова:</span>
                <textarea name="keywords" id="keywords" class="full-length">[keywords]</textarea>
            </li>
            <li>
                <span class="title">Тема статьи:</span>
                <input type="text" name="tema" id="tema" class="full-length" value='[tema]'>
            </li>
            <li>
                <span class="title">Комментарий:</span>
                <textarea name="comments" id="comments" class="full-length">[comments]</textarea>
            </li>
            <li>
                <span class="title">Комментарии работников:</span>
                <textarea name="admin_comments" id="admin_comments" class="full-length">[admin_comments]</textarea>
            </li>
            <li>
                <span class="title">Статья:</span>
                <textarea name="text" id="text" class="full-length" rows="5">[text]</textarea>
                <!--[etxt_action]-->
            </li>
            <li>
                <span class="title">Уникальность текста:</span>
                [uniq]%
            </li>
            <!--<li>
                <span class="title">Перезаписать, если битая кодировка:</span>
                <input type="checkbox" name="overwrite" value="1" [over] />
            </li>-->
            <li>
                <span class="title">Картинка:</span>
                <input type="text" name="url_pic" id="url_pic" class="full-length" value='[url_pic]'>
            </li>
            <li>
                <span class="title">Ссылка на статью:</span>
                <input type="text" name="url_statyi" id="url_statyi" class="full-length" value='[url_statyi]'>
            </li>
            <li>
                <span class="title">Etxt:</span>
                <input type="text" name="etxt" value='[etxt]' id="etxt" class="full-length">
            </li>
            <li>
                <span class="title">GGL_ID:</span>
                <input type="text" name="b_id" value='[b_id]' id="b_id" class="full-length">
            </li>
            <li>
                <span class="title">Sape_ID:</span>
                <input type="text" name="sape_id" value='[sape_id]' id="sape_id" class="full-length">
            </li>
            <li>
                <span class="title">Rotapost_ID:</span>
                <input type="text" name="rotapost_id" value='[rotapost_id]' id="rotapost_id" class="full-length">
            </li>
            <li>
                <span class="title">Miralinks_ID:</span>
                <input type="text" name="miralinks_id" value='[miralinks_id]' id="miralinks_id" class="full-length">
            </li>
            <li>
                <span class="title">Webartex_ID:</span>
                <input type="text" name="webartex_id" value='[webartex_id]' id="webartex_id" class="full-length">
            </li>
            
            <li>
                <span class="title">Статус:</span>
                <input type="radio" name="task_status" value="dorabotka" [dorabotka] [stat_disabled]> На доработке
            </li>
            <li>
                <span class="title"></span>
                <input type="radio" name="task_status" value="vilojeno" [vilojeno] [stat_disabled]> Выложено
            </li>
            <li>
                <span class="title"></span>
                <input type="radio" name="task_status" value="vipolneno" [vipolneno] [stat_disabled]> Выполнено
            </li>
            <li>
                <span class="title"></span>
                <input type="radio" name="task_status" value="vrabote" [vrabote] [stat_disabled]> В работе
            </li>
            <li>
                <span class="title"></span>
                <input type="radio" name="task_status" value="navyklad" [navyklad] [stat_disabled]> На выкладывании
            </li>
        </ul>
    </div>


    <div class="action_bar">
        <input type="hidden" name="send" value="1">
        <input type="submit" value="Сохранить" /><br/><br/>
        <input type="button" value="Вернуться" onclick="location.href = '?module=admins&action=zadaniya&uid=[uid]&sid=[sid]'" style="width:196px;">
        <br/><input type="button" value="Дублировать" id="dulb"  style="width:196px;">
    </div>
</form>
<script>
$( document ).ready(function() {
    $("#dulb").click(function(){
        $("#admin_form").attr("action","?module=admins&action=zadaniya&action2=dubl&zid=[zid]");
        $('#admin_form').submit();
    });
  
   
  
});
</script>