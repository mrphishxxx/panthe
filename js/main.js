$(document).ready(function () {
    if ($("#mycarousel").length)
        $('#mycarousel').jcarousel();

    if ($("#domain_f").length)
    {
        $("#domain_f").change(function (e) {
            window.location.href = "http://iforget.ru/user.php?domain_f=" + $(this).val();
        });
    }

    if ($(".fancybox").length)
        $(".fancybox").fancybox();

    if ($(".datepicker input").length)
        $(".datepicker input").datepicker();

    // BACK END TABLES MARKUP HELPER
    $('tbody tr:odd').addClass('odd');
    $('tbody tr:even').addClass('even');
    $('tbody tr').each(function () {
        var el = $(this);
        el.find('td:first').addClass('first');
        el.find('td:last').addClass('last');
    });

    // WRAP FORM ELEMENT
    $('.form input:text, #header input:text').wrap('<div class="textfield" />');
    $('.form textarea').wrap('<div class="textarea" />');
    $('.form select').wrap('<div class="select" />');

    $(".slider-item").each(function (tmpCnt) {
        if (tmpCnt > 0)
        {
            $(this).hide();
        }
    });

    $(".illustration").each(function (tmpCnt) {
        if (tmpCnt > 0)
        {
            $(this).hide();
        }
    });

    $("#slider .navigation a").click(function (e) {
        var goto_slide = $(this).attr("rel");
        e.preventDefault();

        if (!$(this).hasClass("current"))
        {
            $(".navigation a").removeClass("current");
            $(this).addClass("current");

            $(".slider-item").hide();
            $(".slider-item").each(function (tmpCnt) {
                if (tmpCnt == goto_slide)
                {
                    $(this).show(800);
                    return false;
                }
            });

            $(".illustration").hide();
            $(".illustration").each(function (tmpCnt) {
                if (tmpCnt == goto_slide)
                {
                    $(this).show(800);
                    return false;
                }
            });
        }
    });

    function hideTextExample(object, not_hide)
    {
        object.each(function (index) {
            if(index !== (not_hide - 1)) {
                $(this).remove();
            }
        });
    }
    function getRandomInt(min, max)
    {
        var value = Math.floor(Math.random() * (max - min + 1)) + min;
        return value;
    }
    hideTextExample($('.15001'), getRandomInt(1, $('.15001').length));
    hideTextExample($('.15002'), getRandomInt(1, $('.15002').length));
    hideTextExample($('.15003'), getRandomInt(1, $('.15003').length));
    hideTextExample($('.20001'), getRandomInt(1, $('.20001').length));
    hideTextExample($('.20002'), getRandomInt(1, $('.20002').length));
    hideTextExample($('.20003'), getRandomInt(1, $('.20003').length));
    
    $('#calculate_price').find("#15001").show();
    

    $('#calculate').click(function () {
        var lenght = $('#lenght option:selected').val();
        var quality = $("input:checked").val();
        var price = "62 руб.";
        if (lenght !== "" && quality !== "") {
            if (parseInt(lenght) === 1500) {
                switch (parseInt(quality)) {
                    case 1:
                        price = "62 руб.";
                        break;
                    case 2:
                        price = "78 руб.";
                        break;
                    case 3:
                        price = "110 руб.";
                        break;
                }
            } else {
                switch (parseInt(quality)) {
                    case 1:
                        price = "77 руб.";
                        break;
                    case 2:
                        price = "93 руб.";
                        break;
                    case 3:
                        price = "128 руб.";
                        break;
                }
            }
            $("#" + lenght + quality).parent().find("div.text_example").each(function () {
                $(this).hide();
            });
            $("#" + lenght + quality).show();
            $('#price').children("h2").text(price);
        }
        return false;
    });

    $('#webmasters_ru_informer_200x147_white_2').find("a").attr("rel", "nofollow");

});
