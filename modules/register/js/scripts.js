/*  Author: Reality Group
 *  http://realitygroup.ru/
 */

function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
}
function isValidPhone(phoneNumber) {
    var pattern = new RegExp(/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i);
    return pattern.test(phoneNumber);
}
function isValidDomen(domen) {
    var pattern = new RegExp(/^([\da-z\.-]+)\.([a-z\.]{2,6})$/);
    return pattern.test(domen);
}
function isFindDomenToURL(platform, url) {
    var pos = url.indexOf(platform);
    if (pos > 0) {
        return true;
    } else {
        return false;
    }
}
function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
}
function regNewUser(event) {
    var err = false;
    $(".ErrImg").remove();
    $(".necessarily").each(function(i, element) {
        if ($(this).val() === '') {
            if ($(this).attr("id") !== "password" && $(this).attr("id") !== "confpass") {
                $(this).parent().after('<img class="ErrImg" src="/images/exclamation.png" title="Поле не может быть пустым">');
            } else {
                $(this).after('<img class="ErrImg" src="/images/exclamation.png" title="Поле не может быть пустым">');
            }
            err = true;
        }
    });
    if (!err) {
        if ($("#password").val() !== $("#confpass").val()) {
            $("#password").after('<img class="ErrImg" src="/images/exclamation.png" title="Пароли не совпадают">');
            $("#confpass").after('<img class="ErrImg" src="/images/exclamation.png" title="Пароли не совпадают">');
            err = true;
        }
    }

    if (err) {
        return false;
        event.preventDefault();
    } else if (!isValidEmailAddress($("#email").val())) {
        $("#email").parent().after('<img class="ErrImg" src="/images/exclamation.png" title="Не верный адрес E-Mail">');
    } else {
        $('form').submit();
    }


}
