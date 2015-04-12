//-------------------------------------AJAX--------------------------------------

function getXmlHttp(){
	var xmlhttp;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

function checkBirja()
{
	if (!$("#birja").val())
	{
		alert("Пожалуйста, выберите биржу!");
		return false;
	}
}


jQuery(document).ready(function($) {
/*	$('.datepicker').datepicker();*/

	$("#zayavki").tablesorter();
    
    $('.chbox-user').change(function(){
       var uid = $(this).val();
       if($(this).attr('checked')){
        $.post(
            "?module=admins&action=change_status_on",
            {
                uid: uid
            },
            onStatusCh
            
        );
        
        alert('Пользователь активирован!');
       }else{

           $.post(
            "?module=admins&action=change_status_off",
            {
                uid: uid
            },
            onStatusCh
        );
        alert('Пользователь деактивирован!');
       }
       
    });
    
    $('.chbox-for-copywriter').change(function(){
       var zid = $(this).val();
       if($(this).attr('checked')){
            $.post(
                "?module=admins&action=copywriters&action2=change_task_on",
                {
                    zid: zid
                },
                onCopywriterCh

            );
            alert('Задание выставлено копирайтерам!');
       }else{
            $.post(
                "?module=admins&action=copywriters&action2=change_task_off",
                {
                    zid: zid
                },
                onCopywriterCh
            );
            alert('Задание убрано от копирайтеров!');
       }
       
    });
    
    $('#add_polsh').leanModal({closeButton: "#modal_close"});
    
});

function onStatusCh (){
    window.location.replace("?module=admins&action=viewusers");
}

function onCopywriterCh (){
    //window.location.replace("/admin.php?module=admins&action=articles");
}
