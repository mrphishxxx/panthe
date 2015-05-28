<h1>Создать тикет [title_page]</h1>
<!-- add exchange -->
<div class="add-exchange">
    <h3>Тема обращения:</h3>
    <!-- form -->
    <form action="" method="post">
        <div class="form">

            <ul>
                <li>
                    <span class="title">Кому:</span>
                    <select class="wide" name="ticket_to">
                        [ticket_to]
                    </select>
                </li>
                <li>
                    <span class="title">Тема обращения:</span>
                    <input type="text" class="full-length" value="[subject]" placeholder="" name="subject" id="subject" />
                    <input type="hidden" name="tid" value="[tid]" />
                </li>
                <li>
                    <span class="title">URL площадки:</span>
                    <input type="text" class="wide" value="[site]" placeholder="" name="site" />
                </li>
                <li>
                    <span class="title">Вопрос связан с:</span>
                    <select class="wide" name="theme">
                        [ticket_subjects]
                    </select>
                </li>
                <li>
                    <span class="title">Сообщение:</span>
                    <textarea cols="10" class="full-length" rows="4" name="msg"></textarea>
                </li>
            </ul>

        </div>

        <div class="action_bar">
            <input type="submit" id="send" value="Создать тикет" />
        </div>

    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#send").click(function() {
            if ($("#subject").val() === "") {
                alert("Введите тему обращения!");
                return false;
            }

        });
    });
</script>