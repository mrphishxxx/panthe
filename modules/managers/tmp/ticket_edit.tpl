<!-- add exchange -->
<div class="add-exchange" style="margin-top:0; padding-top: 0;">
	<h3>Тема обращения:</h3>
	<!-- form -->
	<form action="?module=managers&action=ticket&action2=edit&tid=[tid]" method="post">
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
					<option value="Общими вопросами" [Общими вопросами]>Общими вопросами</option>
					<option value="Обработкой заявок" [Обработкой заявок]>Обработкой заявок</option>
					<option value="Наполнением контентом" [Наполнением контентом]>Наполнением контентом</option>
				</select>
			</li>
			<li>
				<span class="title">Сообщение:</span>
				<textarea cols="10" class="full-length" rows="4" name="msg">[msg]</textarea>
			</li>
		</ul>
		
	</div>
	
	<div class="action_bar">
		<input type="hidden" name="send" value="1" />
		<input type="submit" value="Сохранить тикет" />
	</div>
	
	</form>
</div>