<script language="javascript" src="/modules/admins/tmp/admin/js/index.js" type="text/javascript"></script>
<h1>Новое задание для сайта [url] пользователя [login]</h1>
<form action="" method="post" id="admin_form">

						<div class="form">
							
							<ul>
								
								<li>
									<span class="title">Система:</span>
									<input type="text" name="sistema" id="sistema" class="full-length">
								</li>
								<li>
									<span class="title">Анкор:</span>
									<input type="text" name="ankor" id="ankor" class="full-length">
								</li>
								<li>
									<span class="title">Ссылка, куда ведёт:</span>
									<input type="text" name="url" id="url" class="full-length">
								</li>
								<li>
									<span class="title">Ключевые слова:</span>
									<textarea name="keywords" id="keywords" class="full-length"></textarea>
								</li>
								<li>
									<span class="title">Тема статьи:</span>
									<input type="text" name="tema" id="tema" class="full-length">
								</li>
								<li>
									<span class="title">Комментарий:</span>
									<textarea name="comments" id="comments" class="full-length"></textarea>
								</li>
								<li>
									<span class="title">Статья:</span>
									<textarea name="text" id="text" class="full-length"></textarea>
								</li>
								<li>
									<span class="title">Картинка:</span>
									<input type="text" name="url_pic" id="url_pic" class="full-length">
								</li>
								<li>
									<span class="title">Ссылка на статью:</span>
									<input type="text" name="url_statyi" id="url_statyi" class="full-length">
								</li>
								<li>
									<span class="title">Стоимость:</span>
									<input type="text" name="price" id="price" class="full-length">
								</li>
							</ul>
						</div>
						
						
						<div class="action_bar">
							<input type="hidden" name="send" value="1">
							<input type="submit" value="Сохранить" /><br/><br/>
							<input type="button" value="Вернуться" onclick="location.href='?module=user&action=zadaniya&uid=[uid]&sid=[sid]'" style="width:196px;">
						</div>
</form>
