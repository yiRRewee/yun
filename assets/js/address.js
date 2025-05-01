$(document).ready(function () {
    $(".btn-add").click(function () {
        $("#address_id").val("");
        $("#full_name").val("");
        $("#address_line").val("");
        $("#city").val("");
        $("#postcode").val("");
        $("#phone").val("");
        $("#formTitle").text("Add New Address");
        $("#formSubmit").attr("name", "add_address").text("Add Address");
        $("#addForm").fadeIn();  
    });

    $(".btn-edit").click(function (e) {
        e.stopPropagation();

        var btn = $(this);
        $("#address_id").val(btn.data("address_id"));
        $("#full_name").val(btn.data("full_name"));
        $("#address_line").val(btn.data("address_line"));
        $("#city").val(btn.data("city"));
        $("#postcode").val(btn.data("postcode"));
        $("#phone").val(btn.data("phone"));
        $("#formTitle").text("Edit Address");
        $("#formSubmit").attr("name", "update_address").text("Update Address");
        $("#addForm").fadeIn();
    });

    $(document).click(function (event) {
        if (!$(event.target).closest("#addForm, .btn-add","btn-edit").length) {
            $("#addForm").fadeOut();  
        }
    });
});