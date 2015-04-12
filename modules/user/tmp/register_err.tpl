			<!-- CANVAS ( WHITE SHEET ) --> 
			<div class="canvas">
				
				<!-- form -->
				<div class="form">
				<form action="register.php" method="post" accept-charset="utf8" id="registration" class="form">				
					<h1>Регистрация</h1>
					[error]
					<a href="http://iforget.ru/pages/registration/" target="_blank">Для чего нужна регистрация?</a><br/><br/>
					
					<ul>
						<li>
							<span class="title">Полное имя:*</span>
							<input name="fio" value="[fio]" type="text">
							<span class="hint"></span>
						</li>
						<li>
							<span class="title">Логин:*</span>
							<input name="login" value="[login]" type="text">
							<span class="hint"></span>
						</li>
						<li>
							<span class="title">E-mail:*</span>
							<input name="email" value="[email]" type="text">
							<span class="hint"></span>
						</li>
						<li>
							<span class="title">Пароль:*</span>
							<input name="password" value="" type="password" class="textfield">
							<span class="hint"></span>
						</li>
						<li>
							<span class="title">Повторить пароль:*</span>
							<input name="confpass" id="confpass" value="" type="password"  class="textfield">
							<span class="hint"></span>
						</li>
						<li class="em">
							<span class="title">Откуда вы о нас узнали?</span>
							<textarea style="width: 475px; height: 32px;" name="knowus" rows="5" cols="55">[knowus]</textarea>
						</li>
						<li>
							<input name="sendmail" value="1" class="checkbox"  type="checkbox" checked="checked"> <label for="mail-me">Присылать уведомления и новости</label>
						</li>
					</ul>
					
					<div class="action-bar">
						<br/>
						<button  OnClick="userRegister1('new_user')">Регистрация</button>
						<span class="note">* Поля необходимые для заполнения</span>
						
					</div>
				<input name="wmid" id="wmid" type="hidden" value='1'>
				<input name="type" type="hidden" value="[user_type]">
				[copywriter]
				</form>
				</div>
				
				<!-- notifications -->

				<div class="notifications">
					
					<div class="notification important">
						
						[register_comment]
						
						<div class="pointer"></div>
					</div>
					
					
				</div>
				
			</div>
			<!-- CANVAS END -->

