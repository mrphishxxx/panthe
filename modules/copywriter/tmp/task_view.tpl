<h1>Новое задание</h1>

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
        </ol>
    </div>
</div>

<div class="notcolapse copywriter-view-description">
    <span class="span-title">Качество работы</span>
    <div class="content-inside">
        <h3>Требуемое качество текста: "[text_quality]".</h3>
        
    </div>
</div>

<div class="form">
    <ul>
        <li>
            <span class="title" >Тип задания:</span>
            <input type="text" value='[type]' readonly="readonly" class="full-length">
        </li>
        <li>
            <span class="title">Заголовок статьи:</span>
            <input type="text" id="tema" class="full-length" value='[tema]' readonly='readonly'>
        </li>

        <li>
            <span class="title">Поле Title:</span>
            <input type="text" id="title" class="full-length" value='[title]' readonly='readonly'>
        </li>
        <li>
            <span class="title">Поле Keywords:</span>
            <input type="text" id="keywords" class="full-length" value='[keywords]' readonly='readonly'>
        </li>
        <li>
            <span class="title">Поле Description:</span>
            <textarea id="description" class="full-length" rows="3" readonly='readonly'>[description]</textarea>
        </li>
        <li>
            <span class="title">Количество символов:</span>
            <input type="text" id="nof_chars" readonly="readonly" class="full-length" value='[nof_chars]' />
        </li>
        <li>
            <span class="title">Дата добавления:</span>
            <input type="text" id="date" readonly="readonly" class="full-length" value='[date]' readonly='readonly' />
        </li>
    </ul>
</div>

<div class="action_bar">
    <input type="hidden" id="error" value="[error]">
    <input type="button" class="button" value="Вернуться" onclick="location.href = '/copywriter.php';" style="width:100px;">
    <input type="button" class="button" id="add_task_button" value="Взять задание" onclick="" style="width:150px;">
</div>


<input type="hidden" id="count_vrabote" value="[count_vrabote]" />
<script type="text/javascript">
    $(document).ready(function () {
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
        
        if ($("#count_vrabote").val() === "1") {
            $("#add_task_button").css("opacity", 0.3);
            
            $("#add_task_button").click(function () {
                if (confirm("Вы уже подтвердили 5 задач. Чтобы взять в работу ещё, выполните какую-нибудь из уже подтверждённых")) {
                    window.location.href = "/copywriter.php?action=tasks";
                    return false;
                } else {
                    return false;
                }
            });
        } else {
            $("#add_task_button").click(function () {
                window.location.href = '/copywriter.php?action=tasks&action2=add&id=[id][burse]';
            });
        }
    });
</script>