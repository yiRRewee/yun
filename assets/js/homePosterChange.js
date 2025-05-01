$(document).ready(function () {
    $(".change-title").click(function () {
        $("#title").val("");
        $(".form-section").hide();
        $("#changes-title").fadeIn();
    });

    $(".change-image").click(function () {
        $("#image").val(null);
        $(".form-section").hide();
        $("#changes-image").fadeIn();
    });

    $(".change-video").click(function () {
        $("#video").val(null);
        $(".form-section").hide();
        $("#changes-video").fadeIn();
    });

    $(document).click(function (event) {
        if (!$(event.target).closest(".form-section, .change-title, .change-image, .change-video").length) {
            $(".form-section").fadeOut();  
        }
    });    
});
