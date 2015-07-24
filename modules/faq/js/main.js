$(document).ready(function () {
    $('.modal-trigger').leanModal();
    $('select').material_select();
    $(".button-collapse").sideNav();
    $(".dropdown-button").dropdown({
        constrain_width: false, // Does not change width of dropdown to that of the activator
        hover: true, // Activate on hover
        gutter: -25, // Spacing from edge
        belowOrigin: false // Displays dropdown below the button
    });
});