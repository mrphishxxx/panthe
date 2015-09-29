<script language="javascript" src="/modules/admins/tmp/admin/js/index.js" type="text/javascript"></script>
<h1>Задание для sape #[id]</h1>
<form action="" method="post" id="admin_form">
    <input type="hidden" id="error" value="[error]" />
    <input type="hidden" name="id" value="[id]" />
    <div class="form">

        <ul>
            <li>
                <span class="title" >Тип задания:</span>
                <select id="type" name="type">
                    <option value="0" [type0]>Статья</option>
                    <option value="1" [type1]>Обзор</option>
                    <option value="2" [type2]>Новость</option>
                </select>
            </li>
            <li>
                <span class="title">Анкор 1:</span>
                <input type="text" name="ankor" id="ankor" class="full-length" value='[ankor]'>
            </li>
            <li id="ankor2">
                <span class="title">Анкор 2:</span>
                <input type="text" name="ankor2" class="full-length" value='[ankor2]'>
            </li>
            <li id="ankor3">
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
                <span class="title">URL 1:</span>
                <input type="text" name="url" id="url" class="full-length" value='[url]'>
            </li>
            <li id="url2">
                <span class="title">URL 2:</span>
                <input type="text" name="url2" class="full-length" value='[url2]'>
            </li>
            <li id="url3">
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
                <span class="title">Заголовок статьи:</span>
                <input type="text" name="tema" id="tema" class="full-length" value='[tema]'>
            </li>

            <li>
                <span class="title">поле Title:</span>
                <input type="text" name="title" id="title" class="full-length" value='[title]'>
            </li>
            <li>
                <span class="title">поле Keywords:</span>
                <input type="text" name="keywords" id="keywords" class="full-length" value='[keywords]'>
            </li>
            <li>
                <span class="title">поле Description:</span>
                <input type="text" name="description" id="description" class="full-length" value='[description]'>
            </li>
            <li>
                <span class="title">Комментарий:</span>
                <textarea name="comments" id="comments" class="full-length" rows="7">[comments]</textarea>
            </li>
            <li>
                <span class="title">Количество символов:</span>
                <input type="text" name="nof_chars" id="nof_chars" class="full-length" value='[nof_chars]' />
            </li>
            <li>
                <span class="title">Дата добавления:</span>
                <input type="text" name="date" id="date" class="full-length" value='[date]' readonly='readonly' />
            </li>
            <li>
                <span class="title">Статья:</span>
                <textarea name="text" id="text" class="full-length" rows="10">[text]</textarea>
            </li>
            [send_task]
            <li>
                <span class="title" style="top: 0">Перезаписать, если битая кодировка:</span>
                <input type="checkbox" name="overwrite" value="1" [over] />
            </li>
            <li>
                <span class="title">Уникальность текста:</span>
                <input type="text" class="full-length" value='[uniq]%' readonly='readonly' />
            </li>
            <li style="float:left; margin-right: 20px;">
                <span class="title">Написать заказчику:</span>
                <input type="text" class="half-length" id="message" name="message" value='' />
            </li>
            <input id="send_message" type="button" class="button" value="Отправить сообщение" onclick="return false;" style="margin-top: 5px;"> <!-- location.href = ''; -->
            <div class="clear"></div>

            <li>
                <span class="title">История сообщений:</span>
                <textarea class="full-length" readonly='readonly' rows="3">[message]</textarea>
            </li>
            <li>
                <span class="title">Sape_ID:</span>
                <input type="text" name="sape_id" value='[sape_id]' id="sape_id" class="full-length" readonly='readonly'>
            </li>
            <li>
                <span class="title">Etxt:</span>
                <input type="text" name="etxt" value='[etxt]' id="etxt" class="full-length" />
            </li>


            
            <li>
                <span class="title"></span>
                <input id="order_recomplite" type="button" class="button" value="Отправить заказчику без доработки" onclick="return false;" style="margin-top: 0px;"> <!-- location.href = ''; -->
                <br/><br/>  
            </li>
            <li>
                <span class="title">Статус:</span>
                <input type="radio" name="task_status" value="activen" [activen]> Активен
            </li>
            <li>
                <span class="title"></span>
                <input type="radio" name="task_status" value="vrabote" [vrabote]> В работе
            </li>
            <li>
                <span class="title"></span>
                <input type="radio" name="task_status" value="navyklad" [navyklad]> Готов
            </li>
            <li [display_vilojeno]>
                <span class="title"></span>
                <input type="radio" name="task_status" value="vilojeno" [vilojeno]> Выложено
            </li>
            <li [display_dorabotka]>
                <span class="title"></span>
                <input type="radio" name="task_status" value="dorabotka" [dorabotka]> На доработке из Sape
            </li>
            <li [display_rework]>
                <span class="title"></span>
                <input type="radio" name="task_status" value="rework" [rework]> На доработке у Копирайтера
            </li>
            
            <li>
                <span class="title"></span>
                <input type="radio" name="task_status" id="rectificate" value="rectificate" [rectificate]> Отклонить
            </li>
            
        </ul>
    </div>
    
    <div [display]>
        <div style="background: #d0dde7; padding: 10px 5px;" class="form">
            <ul>
                <li style="float:left; margin: 10px; width: 100%">
                    <span>Напишите комментарий копирайтеру, чтобы он знал, что ему нужно переделать. Затем нажмите кнопку "Отправить задачу на доработку Копирайтеру"!</span>
                </li>
                <input id="send_task_to_copywriter" type="button" class="button" value="Отправить задачу на доработку Копирайтеру" onclick="return false;" style="margin: 0 10px;"> 
            </ul>
        </div>
    </div>
            
    
    <div [display]>
        <div style="background: #e7e7e7; padding: 10px 5px;" class="form">
            <ul>
                <li style="float:left; margin-right: 20px;">
                    <span class="title">Написать копирайтеру:</span>
                    <input type="text" class="half-length" id="message_copywriter" name="message_copywriter" value='' />
                </li>
                <input id="send_message_copywriter" type="button" class="button" value="Отправить сообщение" onclick="return false;" style="margin-top: -3px;"> <!-- location.href = ''; -->
                <div class="clear"></div>

                <li>
                    <span class="title">История сообщений с копирайтером:</span>
                    <textarea class="full-length" readonly='readonly' rows="[message_copywriter_count]">[message_copywriter]</textarea>
                </li>
            </ul>
        </div>
    </div>

    <div class="action_bar">
        <input type="hidden" name="send" value="1">
        <input type="hidden" id="send_sape" name="send_sape" value="">
        <input type="submit" id="send_form" value="Сохранить" onclick="return false;" /><br/><br/>
        <input type="button" value="Вернуться" onclick="location.href = '?module=admins&action=articles'" style="width:196px;">
    </div>
</form>
<script>
                $(document).ready(function() {
                    $("#send_sape_button").click(function() {
                        var keywords = $("#keywords").val();
                        var description = $("#description").val();
                        var title = $("#title").val();
                        var tema = $("#tema").val();
                        var err = "Незаполнены некоторые обязательные поля: \r\n\r\n";

                        if (keywords === "") {
                            err += "Поле Keywords\r\n";
                        }
                        if (description === "") {
                            err += "Поле Description\r\n";
                        }
                        if (title === "") {
                            err += "Поле Title\r\n";
                        }
                        if (tema === "") {
                            err += "Поле 'Заголовок статьи'\r\n";
                        }

                        if (tema === "" || title === "" || description === "" || keywords === "") {
                            alert(err);
                            return false;
                        }

                        $("#send_sape").val("1");
                        $("#admin_form").submit();


                    });
                    $("#order_recomplite").click(function() {
                        $("#admin_form").attr("action", "?module=admins&action=articles&action2=order_recomplite");
                        $("#admin_form").submit();
                    });

                    $("#send_message").click(function() {
                        var message = $("#message").val();
                        if (message === "") {
                            alert("Поле 'Написать заказчику пустое'\r\n");
                        } else {
                            $("#admin_form").attr("action", "?module=admins&action=articles&action2=send_message");
                            $("#admin_form").submit();
                        }
                    });

                    $("#send_message_copywriter").click(function() {
                        var message = $("#message_copywriter").val();
                        if (message === "") {
                            alert("Поле 'Написать заказчику пустое'\r\n");
                        } else {
                            $("#admin_form").attr("action", "?module=admins&action=chat&action2=send_message_copywriter");
                            $("#admin_form").submit();
                        }
                    });
                    
                    $("#send_task_to_copywriter").click(function() {
                        $("#admin_form").attr("action", "?module=admins&action=copywriters&action2=change_status_task&status=dorabotka");
                        $("#admin_form").submit();
                    });
                    
                    $("#send_form").click(function() {
                        if($("#rectificate").prop("checked") && $.trim($("#message").val()) === "") {
                            alert("Если хотите отклонить задачу, то поле Написать заказчику обязателен для заполнения!\r\n Введите в это поле причину отклонения!");
                            return false;
                        } else {
                            $("#admin_form").submit();
                        }
                    });

                    var error = $("#error").val();
                    if (error !== "") {
                        alert("[error]");
                    }



                });
</script>