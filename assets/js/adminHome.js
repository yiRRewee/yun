$(document).ready(function () {
    $('.card-large, .card-small,.dash-card').on('click', function () {
        const filter = $(this).data('filter') || 'all';
        window.location.href = `orderHistory.php?status=${filter}`;
    });
});