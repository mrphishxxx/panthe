<h3>Тема: [subject]</h3>
<div id="chat">
    [chat]
</div>

<form action="/copywriter.php?action=ticket&action2=answer&tid=[tid]" method="post">
    <h3>Ответ</h3>
    <div class="form">
        <span class="title">Сообщение:</span>
        <textarea class="full-length" cols="10" rows="4" name="msg"></textarea>
    </div>

    <div class="action_bar">
        <input type="submit" value="Ответить на сообщение" />
        <input type="submit" onclick="window.location.href = '/copywriter.php?action=ticket&action2=close&tid=[tid]';
                        return false;" class="red" value="Закрыть тему" />
    </div>
</form>