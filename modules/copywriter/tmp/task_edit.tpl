<h1>Задание #[id]</h1>
<div class="colapse copywriter-view-description">
    <span class="span-title">Описание задачи</span>
    <div class="content-inside">
        <ol>
            <li>[type] [nof_chars] символов без пробелов, в тексте должн[mn] быть фраз[mn] <br>[ankor_url][ankor2_url2][ankor3_url3][ankor4_url4][ankor5_url5]</li>
            <li>Фраза должна быть употреблена в точности как написана, разрывать другими словами ее нельзя, склонять так же нельзя. Если указано несколько фраз, то нужно их равномерно распределить по тексту</li>
            <li>Текст без воды, строго по теме, без негатива (см. раздел "<a href="/copywriter.php?action=help&action2=work">Как выполнять задачи?</a>")</li>
            <li>Фразу употребить ТОЛЬКО ОДИН раз, в остальном - заменять синонимами </li>
            <li>Вставить готовый заказ просто текстом, в поле Статья</li>
            <li>Вручную проверить уникальность текста по Адвего Плагиатус (выше 95%), в поле "Уникальность текста" проставить % уникальности по данной программе.<br>Без этого пункта автоматически задание отправляется на доработку.</li>
            <li>После того как заказ будет принят и оплачен все авторские права принадлежат аккаунту iforget.ru<br>(то есть статьи могут быть опубликованы на сайтах под различным именем, на выбор владельца текста)</li>
            <li>Вручную проверить уникальность текста по Адвего Плагиатус (выше 95%), в поле "Уникальность текста" проставить % уникальности по данной программе.<br/>Без этого пункта автоматически задание отправляется на доработку.</li>
        </ol>
    </div>
</div>

<div class="notcolapse copywriter-view-description">
    <span class="span-title">Качество работы</span>
    <div class="content-inside">
        <h3>Требуемое качество текста: "[text_quality]".</h3>

    </div>
</div>
<form action="" method="post" id="form">
    <input type="hidden" value="[id]" name="id" />
    <div class="form">
        <ul>
            <li>
                <span class="title" >Тип задания:</span>
                <input type="text" name="type" value='[type]' readonly="readonly" class="full-length">
            </li>
            <li>
                <span class="title">Анкор 1:</span>
                <input type="text" name="ankor" value='[ankor]' readonly="readonly" class="full-length">
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
                <span class="title">URL 1:</span>
                <input type="text" name="url" readonly="readonly" value='[url]' class="full-length">
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
                <span class="title">Заголовок статьи:</span>
                <input type="text" name="tema" id="tema" class="full-length" value='[tema]' [read]>
            </li>

            <li>
                <span class="title">поле Title:</span>
                <input type="text" name="title" id="title" class="full-length" value='[title]' [read]>
            </li>
            <li>
                <span class="title">поле Keywords:</span>
                <input type="text" name="keywords" id="keywords" class="full-length" value='[keywords]' [read]>
            </li>
            <li>
                <span class="title">поле Description:</span>
                <input type="text" name="description" id="description" class="full-length" value='[description]' [read]>
            </li>
            <li>
                <span class="title">Количество символов:</span>
                <input type="text" name="nof_chars" id="nof_chars" readonly="readonly" class="full-length" value='[nof_chars]' />
            </li>
            <li>
                <span class="title">Дата добавления:</span>
                <input type="text" name="date" id="date" readonly="readonly" class="full-length" value='[date]' readonly='readonly' />
            </li>
            <li>
                <span class="title">Статья:</span>
                <textarea name="text" id="text" class="full-length" rows="7" [read]>[text]</textarea>
            </li>
            <li> <!-- [trust] -->
                <span class="title">Уникальность текста, %:</span>
                <input name="uniq" id="uniq" type="text" class="full-length" value='[uniq]' [read] />
            </li>

            <li [display]>
                <span class="title">Статус задачи:</span>
                <input style="margin-top: 7px;" type="radio" name="task_status" value="navyklad" [navyklad]> Готов
            </li>

        </ul>
    </div>
    <div class="form gray">
        <ul>
            <li style="float:left; margin-right: 20px;">
                <span class="title">Написать заказчику:</span>
                <input type="text" class="half-length" id="msg" name="msg" value='' />
            </li>
            <input id="send_message" type="button" class="button" value="Отправить сообщение" onclick="return false;" style="margin-top: -3px;">
            <div class="clear"></div>

            <li>
                <span class="title">История сообщений:</span>
                <textarea class="full-length" readonly='readonly' rows="[message_copywriter_count]">[message]</textarea>
            </li>
        </ul>
    </div>

    <div class="action_bar">
        <input type="hidden" name="send" value="1">
        <input type="hidden" id="error" value="[error]">
        <input [display] type="submit" value="Сохранить" /><br/><br/>
        <input type="button" value="Вернуться" onclick="location.href = '?action=tasks'" style="width:196px;">
    </div>
</form>
<script>
    $(document).ready(function () {
        $("#send_message").click(function () {
            var message = $("#msg").val();
            if (message === "") {
                alert("Поле 'Написать заказчику пустое'\r\n");
            } else {
                $("#form").attr("action", "?action=chat&action2=send_message[burse]");
                $("#form").submit();
            }
        });
        
        $(".colapse .span-title").click(function () {
            var block_inside = $(this).parent().find(".content-inside");
            var span = $(this);
            if (block_inside.is(':hidden')) {
                block_inside.show(400);
                span.css("border-radius", "10px 10px 0px 0");
            } else {
                block_inside.hide(400, function () {
                    span.css("border-radius", "10px");
                });
            }
        });

        var err = $("#error").val();
        if (err !== "") {
            alert(err);
        }
    });
</script>