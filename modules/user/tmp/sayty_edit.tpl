<script language="javascript" src="/modules/admins/tmp/admin/js/index.js" type="text/javascript"></script>
<style type="text/css">
            .tooltip {
                border-bottom: 1px dotted #000000;
                color: #000000; outline: none;
                cursor: help; text-decoration: none;
                position: relative;
                background-image: url("/images/interface/help.png") ;
                background-repeat: no-repeat;
                background-position: right;
                width: 90%;
                display: block
            }
            .tooltip span {
                margin-left: -999em;
                position: absolute;
            }
            .tooltip:hover span {
                font-family: Calibri, Tahoma, Geneva, sans-serif;
                position: absolute;
                left: 1em;
                top: 2em;
                z-index: 99;
                margin-left: 0;
                width: 250px;
                border-radius: 5px 5px;
                -moz-border-radius: 5px;
                -webkit-border-radius: 5px;
                box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.1);
                -webkit-box-shadow: 5px 5px rgba(0, 0, 0, 0.1);
                -moz-box-shadow: 5px 5px rgba(0, 0, 0, 0.1);
            }
            .tooltip:hover img {
                border: 0;
                margin: -10px 0 0 -35px;
                float: left;
                position: absolute;
            }
            .tooltip:hover em {
                font-family: Candara, Tahoma, Geneva, sans-serif;
                font-size: 1.2em;
                font-weight: bold;
                display: block;
                padding-left: 25px;
            }
            .classic { padding: 0em 0em; }
            .custom { padding: 0em 1em 1em 1em; }
            .classic { background: #FFFFAA; border: 1px solid #FFAD33; }
            .critical { background: #FFCCAA; border: 1px solid #FF3334; }
            .help { background: #9FDAEE; border: 1px solid #2BB0D7; }
            .info { background: #9FDAEE; border: 1px solid #2BB0D7; }
            .warning { background: #FFFFAA; border: 1px solid #FFAD33; }
            a:hover { background: transparent; }
            
        </style>

<h1>Редактирование сайта</h1>
<form action="" method="post" id="admin_form">
    <div class="form">

        <ul>

            <li>
                <span class="title">URL сайта: (*)</span>
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
                <span class="title">CMS: (*)</span>
                <select name="cms" id="cms" class="full-length">
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
                <span class="title">URL админки: (*)</span>
                <input type="text" name="url_admin" id="url_admin" value="[url_admin]" class="full-length">
            </li>
            <li>
                <span class="title">Логин: (*)</span>
                <input type="text" name="login" id="loginp" value="[login]" class="full-length">
            </li>
            <li>
                <span class="title">Пароль: (*)</span>
                <input type="text" name="pass" id="pass" value="[pass]" class="full-length">
            </li>
            <li>
                <span class="title">Стоимость текста: (*)<br/><a href="#text_example" style="font-size: 12px;" class="fancybox">примеры текстов</a></span>
                <select class="full-length" name="cena" id="cena">
                    [prices_option]
                </select>
            </li>
            <li>
                <span class="title">
                    <a class="tooltip" href="#" onclick="return false;">
                        ID в gogetlinks: 
                        <span class="custom help">
                            <img src="images/interface/system-help.png" alt="Помощь" height="48" width="48" />
                            <em>Помощь</em>
                            Заходим в систему.<br /><br />
                            Страница "Вебмастер" -> "Мои площадки"<br /><br />
                            Наводим указатель мыши на значение в поле "Размещено".<br /><br />
                            <ib>Внизу браузера высвечивается ссылка</i>.<br /><br />
                            В конце ссылки есть параметр "<b>site_id=</b>".<br /><br />
                            Запоминаем данное число и вводим его в это поле.
                        </span>
                    </a>
                </span>
                <input type="text" name="gid" id="pgid" value="[gid]" class="full-length">
            </li>
            <li>
                <span class="title">
                    <a class="tooltip" href="#" onclick="return false;">
                        ID в getgoodlinks:
                        <span class="custom help">
                            <img src="images/interface/system-help.png" alt="Помощь" height="48" width="48" />
                            <em>Помощь</em>
                            Заходим в систему.<br /><br />
                            Страница "Вебмастер" -> "Мои площадки" <br /><br />
                            Наводим указатель мыши на URL площадки.<br /><br />
                            <i>Внизу браузера высвечивается ссылка</i>.<br /><br />
                            В конце ссылки есть параметр "<b>site_id=</b>".<br /><br />
                            Запоминаем данное число и вводим его в это поле.
                        </span>
                    </a>
                </span>
                <input type="text" name="getgoodlinks_id" id="getgoodlinks_id" value="[getgoodlinks_id]" class="full-length">
            </li>
            <li>
                <span class="title">
                    <a class="tooltip" href="#" onclick="return false;">
                        ID в pr.sape:
                        <span class="custom help">
                            <img src="images/interface/system-help.png" alt="Помощь" height="48" width="48" />
                            <em>Помощь</em>
                            Заходим в систему.<br /><br />
                            Страница "Вебмастеру" -> Переходим к списку площадок.<br /><br />
                            Нажимаем значок гаечного ключа для данной площадки.<br /><br />
                            Происходит переход на страницу "Редактирование площадки".<br /><br />
                            Сразу под заголовком страницы выводится "<b>ID площадки:</b>".<br /><br />
                            Копируем и вставляем данное число в это поле.
                        </span>
                    </a>
                </span>
                <input type="text" name="sape_id" id="sape_id" value="[sape_id]" class="full-length">
            </li>
            <li>
                <span class="title">
                    <a class="tooltip" href="#" onclick="return false;">
                        ID в miralinks:
                        <span class="custom help">
                            <img src="images/interface/system-help.png" alt="Помощь" height="48" width="48" />
                            <em>Помощь</em>
                            Заходим в систему.<br /><br />
                            Страница "Площадки" -> "Все".<br /><br />
                            При наведении на URL площадки, выводится окно с информацией о ней.<br /><br />
                            Слева вверху окна, число после символа "<b>#</b>" и есть ID площадки.<br /><br />
                            Копируем и вставляем данное число в это поле.
                        </span>
                    </a>
                </span>
                <input type="text" name="miralinks_id" id="miralinks_id" value="[miralinks_id]" class="full-length">
            </li>
            <li>
                <span class="title">
                    <a class="tooltip" href="#" onclick="return false;">
                        ID в rotapost:
                        <span class="custom help">
                            <img src="images/interface/system-help.png" alt="Помощь" height="48" width="48" />
                            <em>Помощь</em>
                            Заходим в систему.<br /><br />
                            Страница "Вебмастер" -> "Мои сайты".<br /><br />
                            Нажимаем на значок "Редактировать сайт" (карандаш).<br /><br />
                            Нужно скопировать последнюю часть URL, ту что после "?id=".<br /><br />
                            Вставляем данное значение в это поле.<br /><br />
                            (Пример : 4e4c33d1-74fe-482b-80c3-1d0fbaad006d)
                        </span>
                    </a>
                </span>
                <input type="text" name="rotapost_id" id="rotapost_id" value="[rotapost_id]" class="full-length">
            </li>
            <li>
                <span class="title">
                    <a class="tooltip" href="#" onclick="return false;">
                        ID в webartex:
                        <span class="custom help">
                            <img src="images/interface/system-help.png" alt="Помощь" height="48" width="48" />
                            <em>Помощь</em>
                            Заходим в систему. Страница "Площадки".<br /><br />
                            Нажимаем слева сайта на зеленую полоску (две стрелки >>).<br /><br />
                            Кликаем на ссылку "Настройки площадки".<br /><br />
                            Нужно скопировать предпоследнюю часть URL, ту что после "webartex.ru/site/".<br /><br />
                            Вставляем данное значение в это поле.<br /><br />
                            (Пример : 12345)
                        </span>
                    </a>
                </span>
                <input type="text" name="webartex_id" id="webartex_id" value="[webartex_id]" class="full-length">
            </li>
            <li>
                <span class="title">
                    <a class="tooltip" href="#" onclick="return false;">
                        ID в blogun:
                        <span class="custom help">
                            <img src="images/interface/system-help.png" alt="Помощь" height="48" width="48" />
                            <em>Помощь</em>
                            Заходим в систему. Страница "МОИ ПЛОЩАДКИ".<br /><br />
                            В столбце "Площадка" кликаем мышкой на url сайта.<br /><br />
                            Открывается карточка сайта.<br /><br />
                            Нужно скопировать последнюю часть URL (число).<br /><br />
                            Вставляем данное значение в это поле.<br /><br />
                            (Пример : 138100)
                        </span>
                    </a>
                </span>
                <input type="text" name="blogun_id" id="blogun_id" value="[blogun_id]" class="full-length">
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
                <span class="title">Тематичность: (*)</span>
                <select class="full-length" name="subj_flag" id="subj_flag">
                    <option value=""></option>
                    <option value="Да" [subj_1]>Да</option>
                    <option value="Нет" [subj_0]>Нет</option>
                </select>
            </li>
            <li>
                <span class="title">Размещать задания:  (*) <span class="hint">(порно, казино и т.п)</span></span>
                <select class="full-length" name="bad_flag" id="bad_flag">
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
                <span class="title">Публикация фото:  (*) <span class="hint">(критерии)</span></span>
                <input type="text" class="one-third" value="[pic_width]" placeholder="Ширина пикс." name="pic_width" id="pic_width" />
                <span class="hint">X</span>
                <input type="text" class="one-third" value="[pic_height]" placeholder="Высота пикс." name="pic_height" id="pic_height" />
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
        <input type="submit" value="Сохранить" onclick="addUserSite(); return false;" /><br/><br/>
        <input type="button" value="Вернуться" onclick="history.back();" style="width:196px;">
    </div>
</form>


<script>
function addUserSite()
{
	var error = 0, err_txt="", url=$("#url").val(), cms=$("#cms").val(), url_admin=$("#url_admin").val(), login=$("#loginp").val(), pass=$("#pass").val(), cena=$("#cena").val(), 
	subj_flag=$("#subj_flag").val(), bad_flag=$("#bad_flag").val(), pic_width=$("#pic_width").val(), pic_height=$("#pic_height").val();

	if (!$.trim(url).length)
	{
		err_txt += "Поле URL сайта обязательно для заполнения!\r\n";
		error = 1;
	}

	if (!$.trim(cms).length)
	{
		err_txt += "Поле CMS обязательно для заполнения!\r\n";
		error = 1;
	}

	if (!$.trim(url_admin).length)
	{
		err_txt += "Поле URL админки обязательно для заполнения!\r\n";
		error = 1;
	}

	if (!$.trim(login).length)
	{
		err_txt += "Поле логин обязательно для заполнения!\r\n";
		error = 1;
	}

	if (!$.trim(pass).length)
	{
		err_txt += "Поле пароль обязательно для заполнения!\r\n";
		error = 1;
	}

	if (!$.trim(cena).length)
	{
		err_txt += "Поле Стоимость текста обязательно для заполнения!\r\n";
		error = 1;
	}

	if (!$.trim(subj_flag).length)
	{
		err_txt += "Поле Тематичность обязательно для заполнения!\r\n";
		error = 1;
	}

	if (!$.trim(bad_flag).length)
	{
		err_txt += "Поле Размещать задания обязательно для заполнения!\r\n";
		error = 1;
	}

	if (!$.trim(pic_width).length)
	{
		err_txt += "Поле Ширина пикс обязательно для заполнения!\r\n";
		error = 1;
	}

	if (!$.trim(pic_height).length)
	{
		err_txt += "Поле Высота пикс обязательно для заполнения!\r\n";
		error = 1;
	}

	if (error)
		alert(err_txt);
	else
		$("#admin_form").submit();

}

</script>


