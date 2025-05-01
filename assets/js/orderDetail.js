$(document).ready(function () {
    $(".button-cancel").click(function (e) {
        e.stopPropagation();
        $("#reason").val("");
        $("#reasonForm").fadeIn();  
    });

    $(document).click(function (event) {
        if (!$(event.target).closest("#reasonForm").length) {
            $("#reasonForm").fadeOut();  
        }
    });

});