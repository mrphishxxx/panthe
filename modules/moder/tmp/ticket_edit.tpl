<div class="add-exchange" style="margin-top:0; padding-top: 0;">
    <h3>Тема обращения:</h3>
    <!-- form -->
    <form action="/user.php?action=ticket&action2=edit&tid=[tid]" method="post">
        <div class="form">
            <ul>
                <li>
                    <span class="title">Тема обращения:</span>
                    <input type="text" class="full-length" value="[subject]" placeholder="" name="subject" />
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
                    <textarea cols="10" class="full-length" rows="4" name="msg">[msg]</textarea>
                </li>
            </ul>
        </div>
    </form>
</div>