<!-- the table -->
<div class="wider" style="border-bottom: 1px solid #ccc; margin-bottom: 40px; padding-bottom: 20px;">
    <form action="remove" method="post">
        <table>
            <thead>

                <tr>
                    <th class="edit"></th>
                    <th class="tickets-title">Тема</th>
                    <th class="tickets-state-text">Статус</th>
                    <th class="tickets-date">Дата</th>
                    <th class="edit"></th>
                </tr>
            </thead>
            <tbody>
                [tickets]
            </tbody>
        </table>
    </form>
</div>

<!-- add exchange -->
<div class="add-exchange">
    <h3>Тема обращения:</h3>
    <!-- form -->
    <form action="/copywriter.php?action=ticket&action2=add" method="post">
        <div class="form">

            <ul>
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