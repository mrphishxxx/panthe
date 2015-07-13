<script language="javascript" src="/modules/admins/tmp/admin/js/index.js" type="text/javascript"></script>
<h1>Редактирование сайта</h1>
<form action="" method="post" id="admin_form">
    <div class="form">

        <ul>
            <li>
                <span class="title">Владелец:</span>
                <select class="full-length" name="owner">
                    [owner]
                </select>
            </li>
            <li>
                <span class="title">URL сайта:</span>
                <input type="text" name="url" id="url" value="[url]" class="full-length">
            </li>
            <li>
                <span class="title">Тематика сайта:</span>
                <select name="site_subject" class="full-length">
                    <option value="" ></option>
                    <option value="Авто и Мото" [Авто и Мото] >Авто и Мото</option>
                    <option value="Бизнес/Финансы/Реклама" [Бизнес/Финансы/Реклама]>Бизнес/Финансы/Реклама</option>
                    <option value="Бытовая техника" [Бытовая техника]>Бытовая техника</option>
                    <option value="Дети" [Дети]>Дети</option>
                    <option value="Дом и быт" [Дом и быт]>Дом и быт</option>
                    <option value="Другое" [Другое]>Другое</option>
                    <option value="Животные и растительный мир" [Животные и растительный мир]>Животные и растительный мир</option>
                    <option value="Закон и право" [Закон и право]>Закон и право</option>
                    <option value="Здоровье/Медицина" [Здоровье/Медицина]>Здоровье/Медицина</option>
                    <option value="Интернет" [Интернет]>Интернет</option>
                    <option value="Компьютерная и цифровая техника" [Компьютерная и цифровая техника]>Компьютерная и цифровая техника</option>
                    <option value="Красота/Косметика/Парфюмерия" [Красота/Косметика/Парфюмерия]>Красота/Косметика/Парфюмерия</option>
                    <option value="Кулинария и продукты питания" [Кулинария и продукты питания]>Кулинария и продукты питания</option>
                    <option value="Культура и искусство" [Культура и искусство]>Культура и искусство</option>
                    <option value="Мебель и интерьер" [Мебель и интерьер]>Мебель и интерьер</option>
                    <option value="Мода и стиль" [Мода и стиль]>Мода и стиль</option>
                    <option value="Недвижимость" [Недвижимость]>Недвижимость</option>
                    <option value="Непознанное" [Непознанное]>Непознанное</option>
                    <option value="Новости" [Новости]>Новости</option>
                    <option value="Образование и наука" [Образование и наука]>Образование и наука</option>
                    <option value="Отдых и туризм" [Отдых и туризм]>Отдых и туризм</option>
                    <option value="Под анкор заявки" [Под анкор заявки]>Под анкор заявки</option>
                    <option value="Производство и промышленность" [Производство и промышленность]>Производство и промышленность</option>
                    <option value="Психология" [Психология]>Психология</option>
                    <option value="Работа" [Работа]>Работа</option>
                    <option value="Развлечения/Игры/Юмор/Знакомства" [Развлечения/Игры/Юмор/Знакомства]>Развлечения/Игры/Юмор/Знакомства</option>
                    <option value="Связь и коммуникации" [Связь и коммуникации]>Связь и коммуникации</option>
                    <option value="Семья и отношения" [Семья и отношения]>Семья и отношения</option>
                    <option value="Спорт" [Спорт]>Спорт</option>
                    <option value="Строительство и ремонт" [Строительство и ремонт]>Строительство и ремонт</option>
                    <option value="Товары и Услуги" [Товары и Услуги]>Товары и Услуги</option>
                </select>
            </li>
            <li>
                <span class="title">Уточните тематику:</span>
                <input type="text" name="site_subject_more" id="site_subject_more" value="[site_subject_more]" class="full-length">
            </li>
            <li>
                <span class="title">CMS:</span>
                <select name="cms" class="full-length">
                    <option value="" ></option>
                    <option value="AMIRO" [AMIRO]>AMIRO</option>
                    <option value="Bitrix" [Bitrix]>Bitrix</option>
                    <option value="Diafan" [Diafan]>Diafan</option>
                    <option value="DLE" [DLE]>DLE</option>
                    <option value="Drupal" [Drupal]>Drupal</option>
                    <option value="ExpressionEngine" [ExpressionEngine]>ExpressionEngine</option>
                    <option value="HostCMS" [HostCMS]>HostCMS</option>
                    <option value="Joomla" [Joomla]>Joomla</option>
                    <option value="Joostina" [Joostina]>Joostina</option>
                    <option value="livejournal" [livejournal]>livejournal</option>
                    <option value="MODx" [MODx]>MODx</option>
                    <option value="NetCat" [NetCat]>NetCat</option>
                    <option value="Typo3" [Typo3]>Typo3</option>
                    <option value="Ucoz" [Ucoz]>Ucoz</option>
                    <option value="UMI" [UMI]>UMI</option>
                    <option value="WebAsyst" [WebAsyst]>WebAsyst</option>
                    <option value="Wordpress" [Wordpress]>Wordpress</option>
                    <option value="Другая" [Другая]>Другая</option>
                    <option value="Самопис" [Самопис]>Самопис</option>
                </select>
            </li>
            <li>
                <span class="title">URL админки:</span>
                <input type="text" name="url_admin" id="url_admin" value="[url_admin]" class="full-length">
            </li>
            <li>
                <span class="title">Логин:</span>
                <input type="text" name="login" value="[login]" class="full-length">
            </li>
            <li>
                <span class="title">Пароль:</span>
                <input type="text" name="pass" id="pass" value="[pass]" class="full-length">
            </li>
            <li>
                <span class="title">ID в gogetlinks:</span>
                <input type="text" name="gid" id="pgid" value="[gid]" class="full-length"[readonly]>
            </li>
            <li>
                <span class="title">ID в getgoodlinks:</span>
                <input type="text" name="getgoodlinksId" id="getgoodlinksId" value="[getgoodlinksId]" class="full-length"[readonly]>
            </li>
            <li>
                <span class="title">ID в pr.sape:</span>
                <input type="text" name="sape_id" id="sape_id" value="[sape_id]" class="full-length"[readonly]>
            </li>
            <li>
                <span class="title">ID в miralinks:</span>
                <input type="text" name="miralinks_id" id="miralinks_id" value="[miralinks_id]" class="full-length"[readonly]>
            </li>
            <li>
                <span class="title">ID в webartex:</span>
                <input type="text" name="webartex_id" id="webartex_id" value="[webartex_id]" class="full-length"[readonly]>
            </li>
            <li>
                <span class="title">ID в blogun:</span>
                <input type="text" name="blogun_id" id="blogun_id" value="[blogun_id]" class="full-length"[readonly]>
            </li>
            <li [rights]>
                <span class="title">Стоимость текста:</span>
                <select class="full-length" name="cena">
                    [prices_option]
                </select>
            </li>
            <li>
                <span class="title">Выкладывальщик:</span>
                <select name="viklad" id="viklad"  class="full-length">
                    [str_v]
                </select>
            </li>
            <li>
                <span class="title">Стоимость выкладывания:</span>
                <input type="text" name="price_viklad" id="price_viklad" value="[price_viklad]" class="short" placeholder="">
                <span class="hint">рублей.</span>
            </li>
            <li>
                <span class="title">Комментарий для выкладывальщика:</span>
                <textarea name="comment_viklad" id="comment_viklad" class="full-length">[comment_viklad]</textarea>
            </li>
            <li>
                <span class="title">Комментарий выкладывальщика:</span>
                <textarea name="question_viklad" id="question_viklad" class="full-length">[question_viklad]</textarea>
            </li>
            <li>
                <span class="title">Обзоры: <span class="hint">(размещать заявки с типом обзоров)</span></span>
                <select class="full-length" name="obzor_flag">
                    <option value=""></option>
                    <option value="Да" [obzor_1]>Да</option>
                    <option value="Нет" [obzor_0]>Нет</option>
                </select>
            </li>
            <li>
                <span class="title">Новости: <span class="hint">(размещать заявки с типом новости)</span></span>
                <select class="full-length" name="news_flag">
                    <option value=""></option>
                    <option value="Да" [news_1]>Да</option>
                    <option value="Нет" [news_0]>Нет</option>
                </select>
            </li>
            <li>
                <span class="title">Тематичность:</span>
                <select class="full-length" name="subj_flag">
                    <option value=""></option>
                    <option value="Да" [subj_1]>Да</option>
                    <option value="Нет" [subj_0]>Нет</option>
                </select>
            </li>
            <li>
                <span class="title">Размещать задания: <span class="hint">(порно, казино и т.п)</span></span>
                <select class="full-length" name="bad_flag">
                    <option value=""></option>
                    <option value="Да" [bad_1]>Да</option>
                    <option value="Нет" [bad_0]>Нет</option>
                </select>
            </li>
            <li>
                <span class="title">Размер анонса: <span class="hint">(по умолчанию отсутствует)</span></span>
                <input type="text" class="short" value="[anons_size]" placeholder="Символов" name="anons_size" />
                <span class="hint">Пример: 1000, 1500</span>
            </li>
            <li>
                <span class="title">Публикация фото: <span class="hint">(критерии)</span></span>
                <input type="text" class="one-third" value="[pic_width]" placeholder="Ширина пикс." name="pic_width" />
                <span class="hint">X</span>
                <input type="text" class="one-third" value="[pic_height]" placeholder="Высота пикс." name="pic_height" />
                <select class="one-third" name="pic_position">
                    <option value="Слева" [Слева]>Слева</option>
                    <option value="По-центру" [По-центру]>По-центру</option>
                    <option value="Справа" [Справа]>Справа</option>
                </select>
            </li>
            <li>
                <span class="title">Ваши пожелания по работе с площадкой: <span class="hint">(по умолчанию отсутствует)</span></span>
                <textarea class="full-length" cols="10" rows="4" placeholder="Введите текст" name="site_comments">[site_comments]</textarea>
            </li>
        </ul>
    </div>


    <div class="action_bar">
        <input type="hidden" name="send" value="1">
        <input type="submit" value="Сохранить" /><br/><br/>
        <input type="button" value="Вернуться" onclick="location.href = '?module=admins&action=sayty&uid=[uid]'" style="width:196px;">
    </div>
</form>
