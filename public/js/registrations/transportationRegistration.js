$(function () {

    //setting last employee for selected truck
    $('body').on("change", "#truck_id", function() {
        driverByTruck();
    });

    //disabiling same value selection in 2 sites
    $('body').on("change", "#source_id", function() {
        var fieldValue = $('#source_id').val();

        $('#destination_id')
            .children('option[value=' + fieldValue + ']')
            .prop('disabled', true)
            .siblings().prop('disabled', false);

        initializeSelect2();
        //setting last contractor for selected sites
        contractorBySite();
    });

    //disabiling same value selection in 2 sites
    $('body').on("change", "#destination_id", function() {
        var fieldValue = $('#destination_id').val();

        $('#source_id')
            .children('option[value=' + fieldValue + ']')
            .prop('disabled', true)
            .siblings().prop('disabled', false);

        initializeSelect2();
        //setting last contractor for selected sites
        contractorBySite();
    });

    //setting last rent type for selected truck+sites+contractor
    $('body').on("change", "#contractor_account_id", function() {
        rentDetailByCombo();
    });

    //rent type chane event actions
    $('body').on("change", "#rent_type", function (evt) {
        var rentType = $('#rent_type').val();

        //disable and assign value if fixed rent
        if(rentType && rentType == 3) {
            $('#rent_measurement').prop("readonly",true);
            $('#rent_measurement').val(1);
        } else {
            $('#rent_measurement').prop("readonly",false);
            $('#rent_measurement').val('');
        }

        //calculate total rent
        calculateTotalRent();
    });

    //action on rent measurement keyup
    $('body').on("keyup", "#rent_measurement", function (evt) {
        //calculate total rent
        calculateTotalRent();
    });

    //action on rent rate keyup
    $('body').on("keyup, change", "#rent_rate", function (evt) {
        //calculate total rent
        calculateTotalRent();
    });

    //action on rent rate keyup
    $('body').on("change", "#employee_id", function (evt) {
        //calculate total rent
        calculateTotalRent();
    });

    //submit transportation form
    $('body').on("click", "#save_button", function (e) {
        e.preventDefault();
        $("#save_button").prop("disabled", true);
        $('#save_button').parents('form:first').submit();
        $("#wait_modal").modal("show");
        changeMessage();
    });
});

//method for total rent calculation and driver wage calculation
function calculateTotalRent() {
    var quantity    = ($('#rent_measurement').val() > 0 ? $('#rent_measurement').val() : 0 );
    var rate        = ($('#rent_rate').val() > 0 ? $('#rent_rate').val() : 0 );
    var tripRent    = 0;
    var noOfTrip    = ($('#no_of_trip').val() > 0 ? $('#no_of_trip').val() : 0);
    var totalRent   = 0;
    var wageAmount  = 0;
    //driver wage calculation
    var employeeWageType    = $('#employee_id').find(':selected').data('wage-type');
    var employeeWageAmount  = $('#employee_id').find(':selected').data('wage-amount');

    tripRent  = quantity * rate;
    if(tripRent > 0) {
        $('#trip_rent').val(tripRent);

        totalRent = tripRent * noOfTrip;
        if(totalRent > 0) {
            $('#total_rent').val(totalRent);
        } else {
            $('#total_rent').val(0);
            $('#trip_rent').val(0);
            $('#employee_wage').val(0);
        }

        switch(employeeWageType) {
            case 1:
                //Per Trip [%]
                wageAmount = tripRent * (employeeWageAmount/100);
                break;
            case 2:
                //Per Trip [Fixed]
                wageAmount = employeeWageAmount;
                break;
            case 3:
                //Per Month [Fixed]
                wageAmount = 0;
            case 4:
                //Per Day [Fixed]
                wageAmount = 0;
            case 5:
                //Per Month [Fixed]
                // code block
            default:
                // code block
        }
        if(employeeWageType == 3 && employeeWageAmount > 0) {
            wageAmount = totalRent * (employeeWageAmount/100);

            $('#employee_wage').val(wageAmount);
        } else {
            $('#employee_wage').val('');
        }
    } else {
        $('#total_rent').val(0);
        $('#employee_wage').val(0);
    }
}

//function to show messages one by one in modal
function changeMessage() {
    var countFlag = 1;
    setInterval(function() {
        if(countFlag == 1) {
            $("#wait_modal_message_1").hide();
            $("#wait_modal_message_2").show();
            $("#wait_modal_message_3").hide();
            countFlag = 2;
        } else if(countFlag == 2) {
            $("#wait_modal_message_1").hide();
            $("#wait_modal_message_2").hide();
            $("#wait_modal_message_3").show();
            countFlag = 3;
        } else {
            $("#wait_modal_message_1").show();
            $("#wait_modal_message_2").hide();
            $("#wait_modal_message_3").hide();
            countFlag = 1;
        }
    }, 4000 );
}

function driverByTruck() {
    var truckId = $('#truck_id').val();

    if(truckId) {
        $.ajax({
            url: "/last/transportation", //"/transportation/driver",
            method: "get",
            data: {
                truck_id : truckId
            },
            success: function(result) {
                if(result && result.flag) {
                    var driverId  = result.driverId;

                    $('#employee_id').val(driverId);
                    $('#employee_id').trigger('change');
                } else {
                    $('#employee_id').val('');
                    $('#employee_id').trigger('change');
                }
            },
            error: function () {
                $('#employee_id').val('');
                $('#employee_id').trigger('change');
            }
        });
    }
    //if no truck selected
    $('#employee_id').val('');
    $('#employee_id').trigger('change');
}

function contractorBySite() {
    var sourceId        = $('#source_id').val();
    var destinationId   = $('#destination_id').val();

    if(sourceId && destinationId) {
        $.ajax({
            url: "/last/transportation",
            method: "get",
            data: {
                source_id       : sourceId,
                destination_id  : destinationId
            },
            success: function(result) {
                if(result && result.flag) {
                    var contractorAccountId  = result.transportation.transaction.debit_account_id;

                    $('#contractor_account_id').val(contractorAccountId);
                    $('#contractor_account_id').trigger('change');
                } else {
                    $('#contractor_account_id').val('');
                    $('#contractor_account_id').trigger('change');
                }
            },
            error: function () {
                $('#contractor_account_id').val('');
                $('#contractor_account_id').trigger('change');
            }
        });
    }
    // if no site is selected
    $('#contractor_account_id').val('');
    $('#contractor_account_id').trigger('change');
}

function rentDetailByCombo() {
    var truckId             = $('#truck_id').val();
    var sourceId            = $('#source_id').val();
    var destinationId       = $('#destination_id').val();
    var contractorAccountId = $('#contractor_account_id').val();

    if(truckId && sourceId && destinationId && contractorAccountId) {
        $.ajax({
            url: "/last/transportation",
            method: "get",
            data: {
                truck_id                : truckId,
                source_id               : sourceId,
                destination_id          : destinationId,
                contractor_account_id   : contractorAccountId,
            },
            success: function(result) {
                if(result && result.flag) {
                    var rentType    = result.transportation.rent_type;
                    var rentRate    = result.transportation.rent_rate;
                    var materialId  = result.transportation.material_id;

                    $('#rent_type').val(rentType);
                    $('#rent_type').trigger('change');
                    $('#rent_rate').val(rentRate);
                    $('#rent_rate').trigger('change');
                    $('#material_id').val(materialId);
                    $('#material_id').trigger('change');
                } else {
                    $('#rent_type').val('');
                    $('#rent_type').trigger('change');
                    $('#rent_rate').val('');
                    $('#rent_rate').trigger('change');
                    $('#material_id').val('');
                    $('#material_id').trigger('change');
                }
            },
            error: function () {
                $('#rent_type').val('');
                $('#rent_type').trigger('change');
                $('#rent_rate').val('');
                $('#rent_rate').trigger('change');
                $('#material_id').val('');
                $('#material_id').trigger('change');
            }
        });
    }
}
