<h1>Перезапуск задач в ETXT</h1>
<p>
    Выполняется проверка и перезапуск задач, которые в статусе "Просрочен" в системе ETXT.
</p>
<div class="loading">
    <img src="/images/interface/ajax-loader.gif" alt="" />
</div>
<div class="form" style="display: none;">
    <label>Вывод результата</label>
    <textarea style="color:#767676;height:220px;" class="entire_length" id="out" spellcheck="false" readonly="readonly">

    </textarea>

</div>

<script type="text/javascript">
    $(document).ready(function() {
        $.post("/cron/verify_compliance_deadlines_order.php",
                function(data) {
                    $('.loading').hide();
                    $('.form').show();
                    $('#out').text(data);
                    //alert(data);
                });
    });
</script>