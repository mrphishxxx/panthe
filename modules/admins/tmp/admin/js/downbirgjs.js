$(document).ready(function(){
    $('.downbirgjs').click(function(){
        if($(this).parent().parent().next().is(':hidden'))
            $(this).parent().parent().next().show(400);
        else         
            $(this).parent().parent().next().hide(400);

    });
    
});


