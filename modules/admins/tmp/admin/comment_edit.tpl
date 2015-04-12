<div class="add-exchange" style="margin-top:0; padding-top:0;">
    <form action="?module=admins&action=birj&action2=edit_comment&id=[id]" method="post">
        <h3>Редактирование комментария</h3>
        <div class="form">
            <ul>
                <li>
                    <span class="title">Комментарий:</span>
                    <textarea class="full-length" cols="10" rows="4" placeholder="Введите текст" name="comment_viklad">[comment_viklad]</textarea>
                </li>
            </ul>
        </div>

        <div class="action_bar">
            <input type="hidden" value="[bid]" name="bid" />
            <input type="hidden" value="1" name="send" />
            <input type="hidden" value="[HTTP_REFERER]" name="REFERER" />
            <input type="submit" value="Сохранить" />
        </div>
    </form>
</div>
