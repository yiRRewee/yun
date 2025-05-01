/* Product Display change image */
$(document).ready(function () {
    $(".image-slider").each(function () {
        let $slider = $(this);
        let $images = $slider.find("img");
        let index = 0;
        let interval; // Variable to store interval

        function changeImage() {
            $images.removeClass("active"); // Remove active from all images
            index = (index + 1) % $images.length; // Loop back to the first image
            $images.eq(index).addClass("active"); // Show the new image
        }

        // Start changing images when hovering
        $slider.hover(
            function () {
                interval = setInterval(changeImage, 1500); // Change every 1s when hovering
            },
            function () {
                clearInterval(interval); // Stop when mouse leaves
                index = 0; // Reset index to first image
                $images.removeClass("active"); // Hide all images
                $images.eq(0).addClass("active"); // Show first image
            }
        );
    });
});

  //product detail click image
  $(document).ready(function () {
    $(".image-select").click(function () {
        let newSrc = $(this).attr("src"); // Get the clicked image's source
        $("#image-display").attr("src", newSrc); // Update the big image
        $(".image-select").removeClass("active"); // Remove active class from all
        $(this).addClass("active"); // Add active class to clicked image
    });
});

