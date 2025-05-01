$(() => {
    // Confirmation message
    $('[data-confirm]').on('click', e => {
        const text = e.currentTarget.dataset.confirm || 'Are you sure?';
        if (!confirm(text)) {
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    });

});