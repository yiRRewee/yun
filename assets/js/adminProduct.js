$(document).ready(function () {
    $(".btn-filter").click(function () {
        $("#filterForm").fadeToggle();
    });

    $(document).click(function (event) {
        if (!$(event.target).closest("#filterForm, .btn-filter").length) {
            $("#filterForm").fadeOut();
        }
    });
});
