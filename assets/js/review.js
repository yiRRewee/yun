$(document).on('change', 'input[type="file"][name^="photos"]', function () {
    const productId = $(this).data('product-id');
    const preview = $('#previewContainer_' + productId);

    preview.empty(); // Clear old previews

    const files = this.files;
    if (!files.length) return;

    Array.from(files).forEach(file => {
        if (!file.type.startsWith('image/')) return; // Only images

        const reader = new FileReader();
        reader.onload = function (e) {
            const img = $('<img>', {
                src: e.target.result,
                class: 'preview-image'
            });
            preview.append(img);
        };
        reader.readAsDataURL(file);
    });
});


$(document).on('click', '.upload', function () {
    const productId = $(this).data('product-id');
    $(`input[data-product-id="${productId}"]`).click();
});

$(function () {
    $('.star-rating').each(function () {
        const $container = $(this);
        const $stars = $container.find('.star');
        const $input = $container.find('input[type="hidden"]');

        $stars.on('mouseenter', function () {
            const val = $(this).data('value');
            $stars.each(function () {
                $(this).toggleClass('hovered', $(this).data('value') <= val);
            });
        });

        $container.on('mouseleave', function () {
            $stars.removeClass('hovered');
        });

        $stars.on('click', function () {
            const val = $(this).data('value');
            $input.val(val);
            $stars.each(function () {
                $(this).toggleClass('selected', $(this).data('value') <= val);
            });
        });
    });
});

$('form').on('submit', function (e) {
    let isValid = true;

    $('.star-rating').each(function () {
        const rating = $(this).find('input[type="hidden"]').val();
        if (rating == 0) {
            isValid = false;
            $(this).addClass('missing-rating');
        } else {
            $(this).removeClass('missing-rating');
        }
    });

    if (!isValid) {
        e.preventDefault();
        alert('Please select a rating for all products before submitting.');
    }
});
