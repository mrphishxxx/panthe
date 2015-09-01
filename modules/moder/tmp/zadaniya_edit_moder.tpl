<script language="javascript" src="/modules/admins/tmp/admin/js/index.js" type="text/javascript"></script>
<h1>Задание для сайта [site] <br />[type_task]</h1>
<form action="" method="post" id="admin_form">
    <div class="form">

        <ul>

            <li>
                <span class="title">Анкор:</span>
                <input type="text" name="ankor" id="ankor" value='[ankor]' readonly="readonly" class="full-length">
            </li>
            <li>
                <span class="title">Анкор 2:</span>
                <input type="text" name="ankor2" value='[ankor2]' readonly="readonly" class="full-length">
            </li>
            <li>
                <span class="title">Анкор 3:</span>
                <input type="text" name="ankor3" value='[ankor3]' readonly="readonly" class="full-length">
            </li>
            <li>
                <span class="title">Анкор 4:</span>
                <input type="text" name="ankor4" value='[ankor4]' readonly="readonly" class="full-length">
            </li>
            <li>
                <span class="title">Анкор 5:</span>
                <input type="text" name="ankor5" value='[ankor5]' readonly="readonly" class="full-length">
            </li>
            <li>
                <span class="title">Ссылка, куда ведёт:</span>
                <input type="text" name="url" id="url" value='[url]' readonly="readonly" class="full-length">
            </li>
            <li>
                <span class="title">URL 2:</span>
                <input type="text" name="url2" readonly="readonly"  value='[url2]' class="full-length" >
            </li>
            <li>
                <span class="title">URL 3:</span>
                <input type="text" name="url3" readonly="readonly"  value='[url3]' class="full-length" >
            </li>
            <li>
                <span class="title">URL 4:</span>
                <input type="text" name="url4" readonly="readonly"  value='[url4]' class="full-length" >
            </li>
            <li>
                <span class="title">URL 5:</span>
                <input type="text" name="url5" readonly="readonly"  value='[url5]' class="full-length" >
            </li>
            <li>
                <span class="title">Ключевые слова:</span>
                <textarea name="keywords" id="keywords"  readonly="readonly" class="full-length">[keywords]</textarea>
            </li>
            <li>
                <span class="title">Тема статьи:</span>
                <input type="text" name="tema" id="tema" value='[tema]' readonly="readonly" class="full-length">
            </li>
            <li>
                <span class="title">Комментарии работников:</span>
                <textarea name="admin_comments" id="admin_comments" class="full-length">[admin_comments]</textarea>
            </li>
            <li>
                <span class="title">Статья:</span>
                <textarea name="text" id="text" class="full-length">[text]</textarea>
                [etxt_action]
            </li>
            <li>
                <span class="title">Уникальность текста:</span>
                [uniq]%
            </li>
            <li>
                <span class="title">Картинка:</span>
                <input type="text" name="url_pic" value='[url_pic]' id="url_pic" class="full-length">
            </li>
            <li>
                <span class="title">Ссылка на статью:</span>
                <input type="text" name="url_statyi" value='[url_statyi]' id="url_statyi" class="full-length">
            </li>
            <li>
                <span class="title">Статус:</span>
                <input type="radio" name="task_status" value="[status]" id="[status]" [status_check]> [status_name]
            </li>

        </ul>
    </div>


    <div class="action_bar">
        <input type="hidden" name="send" value="1">
        <input type="hidden" name="uid" value="[uid]">
        <input type="hidden" name="sid" value="[sid]">
        <input type="hidden" name="tid" value="[tid]">
        <input type="hidden" name="error" id="error" value="[error]">
        <input type="submit" value="Сохранить" onclick="saveTask(); return false;" /><br/><br/>
        <input type="button" value="Вернуться" onclick="location.href = '?module=user&action=zadaniya_moder&uid=[uid]&sid=[sid]'" style="width:196px;">

        <br/><br/><a href="/user.php?action=ticket&zid=[tid]">Есть вопрос?</a>
    </div>
</form>
<script>
$( document ).ready(function() {
    if($("#error").val() !== ""){
        alert($("#error").val());
    }
});    

function saveTask()
{
	var error = 0, err_txt="", url=$("#url_statyi").val(), vilojeno=$("#vilojeno").prop("checked");

        if (vilojeno)
	{
		if (!$.trim(url).length)
                {
                        err_txt += "Поле `Ссылка на статью` обязательно для заполнения, если текст выложен!\r\n";
                        error = 1;
                }
	}


	if (error)
            alert(err_txt);
	else
            $("#admin_form").submit();

}

</script>
