							<form method="post" action="/write.html">
								<input style="width:400px;" name="fio" type="text" onfocus="if (this.value=='Фио') this.value='';" onblur="if (this.value=='') this.value='Фио';" value="Фио" class="form-text" /><br />
								<input style="width:400px;" name="mail" type="text" onfocus="if (this.value=='Укажите E-mail') this.value='';" onblur="if (this.value=='') this.value='Укажите E-mail';" value="Укажите E-mail" class="form-text" /><br />
								<input style="width:400px;" name="phone" type="text" onfocus="if (this.value=='Укажите телефон') this.value='';" onblur="if (this.value=='') this.value='Укажите телефон';" value="Укажите телефон" class="form-text" /><br />
								<textarea style="width:400px; height:80px;" name="message" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;">Текст вашего сообщения...</textarea><br />					
								<input style="width:400px;" type="submit" value="Отправить" class="form-submit" />

							</form>
