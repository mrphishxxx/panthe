<h1>Задания сайта [url] пользователя [login]</h1>

<div class="well">
	<form action="user.php?module=user&action=zadaniya&uid=[uid]&sid=[sid]" method="POST" class="form">
		<div><b>Фильтр</b></div>
		C: <div class="input-append date datepicker" id="dp3" data-date="[dfrom]" data-date-format="dd-mm-yyyy">
		  <input class="span2 full-length" size="16" type="text" value="[dfrom]" name="date-from">
		  <span class="add-on"><i class="icon-th"></i></span>
		</div>

		По: <div class="input-append date datepicker" id="dp4" data-date="[dto]" data-date-format="dd-mm-yyyy">
		  <input class="span2 full-length" size="16" type="text" value="[dto]" name="date-to">
		  <span class="add-on"><i class="icon-th"></i></span>
		</div>

		<div>
			<input type="submit" value="Применить" class="btn" />
		</div>
	</form>
</div>

<a href="?module=user&action=sayty&uid=[uid]">вернуться в список сайтов</a> | <a href="/user.php?act=getXLS&uid=[uid]&sid=[sid]" target="_blank">Выгрузить в Эксель</a> | <a href="?module=user&action=zadaniya&action2=ggl&uid=[uid]&sid=[sid]" target="_blank">Загрузить из GGL</a> | <a href="?module=user&action=zadaniya&uid=[uid]&sid=[sid]&showall=1">Показать ВСЕ заявки</a>


<br/><br/>
[zadaniya]
<br/>
<a href="?module=user&action=sayty&uid=[uid]">вернуться в список сайтов</a>
