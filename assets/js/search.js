$(document).ready(function () {
    $("#search-trigger").click(function () {
        $("#search-popup").toggle();
    });

    $(document).click(function (event) {
        if (!$(event.target).closest("#search-popup, #search-trigger").length) {
            $("#search-popup").hide();
        }
    });
});
    