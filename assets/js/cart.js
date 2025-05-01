$(document).ready(function () {
    function updateSummary() {
        let selectedCount = 0;
        let totalPrice = 0;

        $(".product-checkbox:checked").each(function () {
            selectedCount++;
            totalPrice += parseFloat($(this).data("price")) * parseInt($(this).closest(".cart-container").find(".quantity").val());
        });

        $("#selectedCount").text(selectedCount);
        $("#totalPrice").text(totalPrice.toFixed(2));

        // Enable/Disable checkout button
        $("#checkoutButton").prop("disabled", selectedCount === 0);
    }

    // Trigger update on checkbox change
    $(".product-checkbox").on("change", updateSummary);

    // Also update when quantity is changed
    $(".plus-btn, .minus-btn").on("click", function () {
        setTimeout(updateSummary, 200); // Delay to allow DB update
    });

    updateSummary(); // Initialize on page load
});

