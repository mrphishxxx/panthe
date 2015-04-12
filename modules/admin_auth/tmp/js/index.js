function reg(){
	var error = 0;
	var mail = document.getElementById('mail').value;
	if(mail == '' && error == 0){
		error = 1;
		alert('Вы не ввели email');
	}
	
	mail = mail.replace(/^\s+|\s+$/g, '');
	if(!(/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,4}$/i).test(mail) && error == 0){
		error = 1;
		alert('Некорректный email');
	}
	
	mail = ajax_check_mail();
	if(mail != 1 && error == 0){
		error = 1;
		alert('Такой email уже существует в базе');
	}
	
	if(document.getElementById('login').value == '' && error == 0){
		error = 1;
		alert('Вы не ввели логин');
	}
	
	login = ajax_check_login();
	if(login != 1 && error == 0){
		error = 1;
		alert('Такой логин уже существует в базе');
	}
	
	if(document.getElementById('pass').value == '' && error == 0){
		error = 1;
		alert('Вы не ввели пароль');
	}
	
	if(document.getElementById('pass2').value == '' && error == 0){
		error = 1;
		alert('Вы не ввели повтор пароля');
	}
	
	if(document.getElementById('pass').value != document.getElementById('pass2').value && error == 0){
		error = 1;
		alert('Пароль и повтор пароля не совпадают');
	}
	
	if(document.getElementById('kod').value == '' && error == 0){
		error = 1;
		alert('Вы не код с капчи');
	}
	
	var captcha_err = ajax_captcha_check();
	if(captcha_err != 1 && error == 0){
		error = 1;
		alert('Неверно введен код с каптчи');
	}
	
	if(error == 0) document.getElementById('reg_form').submit();
}

function captcha_change(){
	var v = Math.random();
	document.getElementById('captcha').src = '/includes/captcha/index.php?v='+v;
}

//-------------------------------------AJAX--------------------------------------



function ajax_captcha_check() {
	var xmlhttp = getXmlHttp();
	var captcha = document.getElementById('kod').value;
	var url = 'http://' + document.domain + '/modules/auth/ajax.php?action=captcha_check&captcha=' + captcha;
	xmlhttp.open('GET', url, false);
	xmlhttp.send(null);
	if(xmlhttp.status == 200) {
	  return xmlhttp.responseText;
	}
}

function ajax_check_mail() {
	var xmlhttp = getXmlHttp();
	var mail = document.getElementById('mail').value;
	var url = 'http://' + document.domain + '/modules/auth/ajax.php?action=mail_check&mail=' + mail;
	xmlhttp.open('GET', url, false);
	xmlhttp.send(null);
	if(xmlhttp.status == 200) {
	  return xmlhttp.responseText;
	}
}

function ajax_check_login() {
	var xmlhttp = getXmlHttp();
	var login = document.getElementById('login').value;
	var url = 'http://' + document.domain + '/modules/auth/ajax.php?action=login_check&login=' + login;
	xmlhttp.open('GET', url, false);
	xmlhttp.send(null);
	if(xmlhttp.status == 200) {
	  return xmlhttp.responseText;
	}
}
