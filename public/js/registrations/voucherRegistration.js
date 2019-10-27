$(function () {
    //append to main registratin number textbox
    $('body').on("click", ".transaction_type", function (evt) {
        if($('#transaction_type_credit').is(':checked')) {
            $('#account_label').html('Reciever');
        } else {
            $('#account_label').html('Giver');
        }
    });
});
