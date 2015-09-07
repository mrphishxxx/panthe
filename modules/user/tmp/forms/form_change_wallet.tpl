<form action="?action=change_wallet" method="post">
    <div class="form">
        <ul>
            <li>
                <span class="title">Прежний кошелек:</span>
                <span class="title">[old_wallet]</span>
            </li>
            <li>
                <span class="title">Новый кошелек:</span>
                <input type="text" name="wallet" id="wallet" class="full-length">
            </li>
        </ul>
    </div>

    <div class="action_bar">
        <input type="hidden" name="send" value="[send]">
        <button type="submit" class="button">Отправить</button>
        <input type="button" class="button" value="Вернуться" onclick="history.back();" /><br/><br/>
    </div>
</form>