function admin_add(){
	var error = 0;
	
	if(document.getElementById('login').value == '' && error == 0){
		alert('Вы не ввели логин');
		error = 1;
	}
	
	login = ajax_check_login();
	if(login != 1 && error == 0){
		error = 1;
		alert('Такой логин уже существует в базе');
	}
	
	if(document.getElementById('pass').value == '' && error == 0){
		alert('Вы не ввели пароль');
		error = 1;
	}

	
	if(error == 0) document.getElementById('admin_form').submit();
}



//-------------------------------------AJAX--------------------------------------

function ajax_check_login() {
	var xmlhttp = getXmlHttp();
	var login = document.getElementById('login').value;
	var url = 'http://' + document.domain + '/modules/admins/php/ajax.php?action=login_check&login=' + login;
	xmlhttp.open('GET', url, false);
	xmlhttp.send(null);
	if(xmlhttp.status == 200) {
	  return xmlhttp.responseText;
	}
}

$(document).ready(function(){
    var sistems = new Array();
    var ids_sistems = new Array();
    $('#lay_out').change(function(){
        if($(this).is(":checked")){
            $('#price').val("15");
        } else {
            $('#price').val("");
        }
    });
    
 
    $("#sistema").change(function(){
        if($("#sistema :selected").text() === "http://miralinks.ru/"){
            //$("#url2").show();
            //$("#url3").show();
            $('#price').val("60");
            //$('#price').attr("disabled","disabled");
        } else {
            //$("#url2").hide();
            //$("#url3").hide();
            $('#price').val("");
            //$('#price').removeAttr("disabled");
        }
    
    });
});