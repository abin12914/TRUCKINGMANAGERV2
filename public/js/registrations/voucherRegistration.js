$(function () {
    //if editing activate siblings disabiling
    if($('#credit_account_id').val() != 'undefined') {
        var creditAccountId = $('#credit_account_id').val();
        var debitAccountId  = $('#debit_account_id').val();

        disableSiblings('#credit_account_id', debitAccountId);
        disableSiblings('#debit_account_id', creditAccountId);
    }
    //handle payer and reciever
    $('body').on("change", "#transaction_type", function (evt) {
        var transactionType = $(this).val();
        if(transactionType == 1) {
            //Receipt [Cash Received]
            $('#credit_account_id').prop('disabled', false);
            $('#debit_account_id').prop('disabled', true);
        } else if(transactionType == 2) {
            // Payment [cash paid]
            $('#credit_account_id').prop('disabled', true);
            $('#debit_account_id').prop('disabled', false);
        } else {
            // Receipt [Cash Received]
            $('#credit_account_id').prop('disabled', false);
            $('#debit_account_id').prop('disabled', false);
        }
    });

    $('body').on("change", "#debit_account_id", function (evt) {
        var debitAccountId = $(this).val();
        disableSiblings('#credit_account_id', debitAccountId);
    });

    $('body').on("change", "#credit_account_id", function (evt) {
        var creditAccountId = $(this).val();
        disableSiblings('#debit_account_id', creditAccountId);
    });
});

//disable siblings's elements
function disableSiblings(element, fieldValue) {
    if(fieldValue && fieldValue != 'undefined')
    {
        $(element)
            .children('option[value=' + fieldValue + ']')
            .prop('disabled', true)
            .siblings().prop('disabled', false);

        initializeSelect2();
    } else {
        $(element).children().prop('disabled', false);
    }
}
