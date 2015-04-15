$(document).ready(function() {

    $("#task_all_off").change(function() {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
        if ($(this).prop("checked")) {
            $(".task_all_off_label").text('Снять все задачи с копирайтеров');
            $.post("?module=admins&action=copywriters&action2=change_task_all",
                    {
                        status: 1
                    }, function(data) {
                alert(data + " новых задания выставлены копирайтерам!");
            });
        } else {
            $(".task_all_off_label").text('Отдать все задачи копирайтерам');
            $.post("?module=admins&action=copywriters&action2=change_task_all",
                    {
                        status: 0
                    }, function(data) {
                alert(data + " новых задания убраны от копирайтеров!");
            });

        }
    });



});