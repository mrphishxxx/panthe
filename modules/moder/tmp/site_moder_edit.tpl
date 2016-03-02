<script language="javascript" src="/modules/admins/tmp/admin/js/index.js" type="text/javascript"></script>
<h1>Комментарий по сайту</h1>

<h2>Пожелания по работе с площадкой:</h2>
<p class="full-length">
    [site_comments]
</p>

<h2>Пожелания публикации фото:</h2>
<p>
    Изображение размером [pic_width] x [pic_height] <br/>
    Расположение - "[pic_position]"
</p>

<form action="" method="post" id="admin_form">
    <div class="form">
        <ul>
            <li>
                <span class="title">Комментарий выкладывальщика:</span>
                <textarea name="question_viklad" id="question_viklad" class="full-length">[question_viklad]</textarea>
            </li>
        </ul>
    </div>


    <div class="action_bar">
        <input type="hidden" name="s_id" id="s_id" value="[sid]">
        <input type="hidden" name="send" value="1">
        <input type="submit" value="Сохранить" /><br/><br/>
        <input type="button" value="Вернуться" onclick="location.href = '/user.php'" style="width:196px;">
    </div>
</form>
