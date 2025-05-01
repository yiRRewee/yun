let selectedFiles = [];

$(document).on('change', '#imageUpload', function () {
    const preview = $('#newImagePreview');
    preview.empty();

    selectedFiles = Array.from(this.files); 

    selectedFiles.forEach((file, index) => {
        if (!file.type.startsWith('image/')) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            const wrapper = $('<div>', { class: 'image-preview-box', 'data-index': index });

            const img = $('<img>', {
                src: e.target.result,
                class: 'preview-image'
            });

            const deleteBtn = $('<span>', {
                class: 'delete-image-btn',
                text: 'X',
                click: function () {
                    const idx = wrapper.data('index');
                    selectedFiles.splice(idx, 1); // Remove the selected file
                    updateFileInput(); // Replace the input's FileList
                    renderPreview();   // Re-render the preview
                }
            });

            wrapper.append(img, deleteBtn);
            preview.append(wrapper);
        };
        reader.readAsDataURL(file);
    });
});

function updateFileInput() {
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => dataTransfer.items.add(file));
    $('#imageUpload')[0].files = dataTransfer.files;
}

function renderPreview() {
    const preview = $('#newImagePreview');
    preview.empty();

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function (e) {
            const wrapper = $('<div>', { class: 'image-preview-box', 'data-index': index });

            const img = $('<img>', {
                src: e.target.result,
                class: 'preview-image'
            });

            const deleteBtn = $('<span>', {
                class: 'delete-image-btn',
                text: 'X',
                click: function () {
                    const idx = wrapper.data('index');
                    selectedFiles.splice(idx, 1);
                    updateFileInput();
                    renderPreview();
                }
            });

            wrapper.append(img, deleteBtn);
            preview.append(wrapper);
        };
        reader.readAsDataURL(file);
    });
}
