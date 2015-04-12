<h1>Новое задание</h1>
<span class="span-title">Описание задачи</span>
<p>
    1) Cтатья [nof_chars] символов без пробелов, в тексте должн[mn] быть фраз[mn] <br>[ankor_url][ankor2_url2][ankor3_url3][ankor4_url4][ankor5_url5]
</p>
<p>
    2) Фраза должна быть употреблена в точности как написана, разрывать другими словами ее нельзя, склонять так же нельзя. Если указано несколько фраз, то нужно их равномерно распределить по тексту  
</p>
<p>
    3) Текст без воды, строго по теме, без негатива (см. раздел "<a href="/copywriter.php?action=help&action2=work">Как выполнять задачи?</a>") 
</p>
<p>
    4) Фразу употребить ТОЛЬКО ОДИН раз, в остальном - заменять синонимами 
</p>
<p>
    5) Вставить готовый заказ просто текстом, в поле Статья
</p>
<p>
    6) Вручную проверить уникальность текста по Адвего Плагиатус (выше 95%), в поле "Уникальность текста" проставить % уникальности по данной программе.
    Без этого пункта автоматически задание отправляется на доработку.
</p>
<p>
    7) После того как заказ будет принят и оплачен все авторские права принадлежат аккаунту ifoget.ru 
    (то есть статьи могут быть опубликованы на сайтах под различным именем, на выбор владельца текста)
</p>
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
            <span class="title">поле Title:</span>
            <input type="text" id="title" class="full-length" value='[title]' readonly='readonly'>
        </li>
        <li>
            <span class="title">поле Keywords:</span>
            <input type="text" id="keywords" class="full-length" value='[keywords]' readonly='readonly'>
        </li>
        <li>
            <span class="title">поле Description:</span>
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
    <input type="button" class="button" value="Взять задание" onclick="location.href = '/copywriter.php?action=tasks&action2=add&id=[id]';" style="width:150px;">
</div>
