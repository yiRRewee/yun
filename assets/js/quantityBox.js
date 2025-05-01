$(document).ready(function () {
    const $quantityInput = $('.quantity');
    const $qtyBtns = $('.qty-btn');

    $qtyBtns.on('click', function () {
        let currentVal = parseInt($quantityInput.val());
        const isAdd = $(this).text() === '+';

        if (isAdd) {
            if (currentVal < maxStock) {
                $quantityInput.val(currentVal + 1);
            }
        } else {
            if (currentVal > 1) {
                $quantityInput.val(currentVal - 1);
            }
        }
    });
});
