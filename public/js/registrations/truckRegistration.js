$(function () {

    //convert to uppper case
    $('body').on("change", "#reg_number_state_code", function (evt) {
        //append to main registratin number textbox
        appendRegistrationNumber();
    });

    //append to main registratin number textbox
    $('body').on("keyup", "#reg_number_region_code", function (evt) {
        var fieldValue  = $(this).val();

        if(fieldValue) {
            if(fieldValue.length >=2 && !(evt.keyCode == 9 || evt.keyCode == 16)) {
                $('#reg_number_unique_alphabet').focus();
                if(fieldValue.length > 2) {
                    $('#reg_number_region_code').val('');
                }
            }

            //append to main registratin number textbox
            appendRegistrationNumber();
        }
    });

    //convert to uppper case and append to main registratin number textbox
    $('body').on("keyup", "#reg_number_unique_alphabet", function (evt) {
        var fieldValue  = $(this).val();

        if(fieldValue) {
            fieldValue = fieldValue.toUpperCase();
            $(this).val(fieldValue);

            if(fieldValue.length >= 2 && !(evt.keyCode == 9 || evt.keyCode == 16)) {
                evt.preventDefault();
                $('#reg_number_unique_digit').focus();
                if(fieldValue.length > 2) {
                    $('#reg_number_unique_alphabet').val('');
                }
            }

            //append to main registratin number textbox
            appendRegistrationNumber();
        }
    });

    $('body').on("keyup", "#reg_number_unique_digit", function (evt) {
        var fieldValue  = $(this).val();

        if(fieldValue) {
            if(fieldValue.length >=4 && !(evt.keyCode == 9 || evt.keyCode == 16)) {
                $("#reg_number_unique_digit").data("title", "Maximum four digits are allowed in this section").tooltip("show");
                if(fieldValue.length > 4) {
                    $('#reg_number_unique_digit').val('');
                }
            }

            //append to main registratin number textbox
            appendRegistrationNumber();
        }
    });

    //convert to uppper case
    $('body').on("change", "#reg_number_unique_alphabet", function (evt) {
        var fieldValue  = $(this).val();
        if(fieldValue) {
            fieldValue = fieldValue.toUpperCase();
            $(this).val(fieldValue);
        }
    });

    //convert to uppper case and append to main registratin number textbox
    $('body').on("change", "#reg_number_region_code", function (evt) {
        var fieldValue  = $("#reg_number_region_code").val();
        if(fieldValue) {
            if(fieldValue.length == 1 && fieldValue != 0) {
                fieldValue = '0' + fieldValue;
                $("#reg_number_region_code").val(fieldValue);
            } else if(fieldValue == 0) {
                evt.preventDefault();
                $("#reg_number_region_code").data("title", "Invalid region code!").tooltip("show");;
                $("#reg_number_region_code").focus();
                $("#reg_number_region_code").trigger('mouseenter');
            }

            //append to main registratin number textbox
            appendRegistrationNumber();
        }
    });
});
function appendRegistrationNumber() {
    var stateCode   = $('#reg_number_state_code').val();
    var regionCode  = $('#reg_number_region_code').val();
    var alphaCode   = $('#reg_number_unique_alphabet').val();
    var numerisCode = $('#reg_number_unique_digit').val();

    if(alphaCode) {
        var registrationNumber = stateCode + '-' + regionCode + ' ' + alphaCode + '-' + numerisCode;
    } else {
        var registrationNumber = stateCode + '-' + regionCode + ' ' + numerisCode;
    }
    $('#reg_number').val(registrationNumber);
}
