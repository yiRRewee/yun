$(document).ready(function () {
    // Hide all sections except Customer Reviews by default
    $(".nav-content").hide();
    $("#reviews").show();

    // Click event for nav buttons
    $(".nav-btn").click(function () {
        let target = $(this).data("target");
        $(".nav-content").hide();
        $("#" + target).show();
    });

    // Click event for Size Guide link
    $(".size-guide").click(function (event) {
        event.preventDefault(); // Prevent default anchor behavior

        // Simulate clicking the Size Guide nav button
        $(".nav-btn[data-target='size-guide']").click();

        // Smooth scroll to the navigation section
        $("html, body").animate({
            scrollTop: $(".product-nav").offset().top
        }, 500);
    });
});

document.querySelector('.size-guide').addEventListener('click', function(event) {
    event.preventDefault();
});
