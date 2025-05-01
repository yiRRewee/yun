$(document).ready(function () {
    $(".address-box").click(function () {
        window.location.href = '../component/selectAddress.php';
    });

});

$('#card-number').on('input', function () {
    let value = $(this).val().replace(/\D/g, '');      
    let spaced = value.replace(/(.{4})/g, '$1 ').trim(); 
    $(this).val(spaced);                                  
});