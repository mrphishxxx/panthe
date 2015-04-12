function menu_add(){
	var error = 0;
	
	if(document.getElementById('name').value == '' && error == 0){
		alert('Вы не ввели название меню');
		error = 1;
	}
	
	if(error == 0) document.getElementById('menu_form').submit();
}

function url_add(){
	var error = 0;
	
	if(document.getElementById('url').value == '' && error == 0){
		alert('Вы не ввели url ссылки');
		error = 1;
	}
	
	if(document.getElementById('text').value == '' && error == 0){
		alert('Вы не ввели текст ссылки');
		error = 1;
	}
	
	if(error == 0) document.getElementById('menu_form').submit();
}

function template_add(){
	var error = 0;
	
	if(document.getElementById('template').value == '' && error == 0){
		alert('Вы не ввели название шаблона');
		error = 1;
	}
	
	var template = document.getElementById('template').value;
	if(template != ''){
		template = template.replace(/^\s+|\s+$/g, '');
		if(!(/^[a-z0-9_]{2,}$/i).test(template) && error == 0){
			error = 1;
			alert('Некорректное название шаблона. Оно должно состоять из англ. букв и цифр');
		}
	}
	
	template = ajax_check_template();
	if(template != 1 && error == 0){
		error = 1;
		alert('Шаблон с таким названием уже существует в базе');
	}
	
	if(document.getElementById("menu").options[document.getElementById('menu').selectedIndex].value == 0 && error == 0){
		alert('Вы не выбрали меню');
		error = 1;
	}
	
	if(error == 0) document.getElementById('menu_form').submit();
}


//-------------------------------------AJAX--------------------------------------



function ajax_check_template() {
	var xmlhttp = getXmlHttp();
	var template = document.getElementById('template').value;
	var id = document.getElementById('tid').value;
	var url = 'http://' + document.domain + '/modules/menu/php/ajax.php?action=template_check&template=' + template + '&id=' + id;
	xmlhttp.open('GET', url, false);
	xmlhttp.send(null);
	if(xmlhttp.status == 200) {
	  return xmlhttp.responseText;
	}
}
