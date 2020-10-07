$(function () {
    //set name values of selected options- useful when editing
    setNameValues();

    //if editing activate siblings disabiling
    if($('#source_id').val() != 'undefined') {
        var sourceId      = $('#source_id').val();
        var destinationId = $('#destination_id').val();

        disableSiblings('#destination_id', sourceId);
        disableSiblings('#source_id', destinationId);
    }

    //setting last employee for selected truck
    $('body').on("change", "#truck_id", function() {
        //set selected truck reg-number
        setNameValues();
        driverByTruck();
    });

    //disabiling same value selection in 2 sites
    $('body').on("change", "#source_id", function() {
        //set name of selected source
        setNameValues();

        var fieldValue = $('#source_id').val();

        disableSiblings('#destination_id', fieldValue);

        //setting last contractor for selected sites
        contractorBySite();
    });

    //disabiling same value selection in 2 sites
    $('body').on("change", "#destination_id", function() {
        //set name of selected destination
        setNameValues();

        var fieldValue = $('#destination_id').val();

        disableSiblings('#source_id', fieldValue);

        initializeSelect2();
        //setting last contractor for selected sites
        contractorBySite();
    });

    //setting name of material
    $('body').on("change", "#material_id", function() {
        //set name of selected material
        setNameValues();
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
        calculateTripRent();
        //calculate driver wage automatic
        calculateDriverWage();
    });

    //action on rent measurement keyup
    $('body').on("keyup", "#rent_measurement", function (evt) {
        //calculate total rent
        calculateTripRent();
        //calculate driver wage automatic
        calculateDriverWage();
    });

    //action on rent rate keyup
    $('body').on("keyup change", "#rent_rate", function (evt) {
        //calculate total rent
        calculateTripRent();
        //calculate driver default wage
        calculateDriverWage();
    });

    //action on no of trip keyup
    $('body').on("keyup change", "#no_of_trip", function (evt) {
        //calculate total rent
        calculateTotalRent();
        //calculate driver wage total
        calculateDriverWageTotal()
    });

    //add second driver
    $('body').on("click", "#add_second_driver_button", function (e) {
        e.preventDefault();
        $('#driver_id_1').prop('disabled', false);
        $('#driver_wage_1').prop('disabled', false);
        $('#driver_total_wage_1').prop('disabled', false);
        $('#add_second_driver_section').hide();
        $('#remove_second_driver_section').show();
    });

    //remove second driver
    $('body').on("click", "#remove_second_driver_button", function(e) {
        e.preventDefault();
        $('#driver_id_1').prop('disabled', true);
        $('#driver_wage_1').prop('disabled', true);
        $('#driver_total_wage_1').prop('disabled', true);
        $('#add_second_driver_section').show();
        $('#remove_second_driver_section').hide();
        $('#driver_id_1').val('');
        $('#driver_id_1').trigger('change');
        $('#driver_wage_1').val(0);
        $('#driver_total_wage_1').val(0);
        calculateSecondDriverWage();
    });

    //action on primary driver keyup
    $('body').on("change", "#driver_id_0", function (evt) {
        //calculate driver default wage
        calculateDriverWage();
    });

    //action on primary driver keyup
    $('body').on("change", "#driver_id_1", function (evt) {
        //calculate second driver default wage
        calculateSecondDriverWage();
    });

    //action on rent rate keyup
    $('body').on("keyup change", "#driver_wage_0", function (evt) {
        //calculate total rent
        var tripWage  = ($('#driver_wage_0').val() > 0 ? $('#driver_wage_0').val() : 0);
        var noOfTrip  = ($('#no_of_trip').val() > 0 ? $('#no_of_trip').val() : 0);
        var totalWage = tripWage * noOfTrip;

        if(totalWage != 'undefined' && totalWage > 0)
        {
            $('#driver_total_wage_0').val(totalWage);
        }
    });

    $('body').on("keyup change", "#driver_wage_1", function (evt) {
        //calculate total driver wage 2 calc
        calculateSecondDriverWageTotal();
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

//setting name params
function setNameValues() {
    if($('#truck_id').val()) {
        //set reg-number of selected truck
        var truckRegNumber = $('#truck_id option:selected').text();
        $('#truck_reg_number').val(truckRegNumber);
    } else {
        $('#truck_reg_number').val('');
    }

    if($('#source_id').val()) {
        //set source name of selected source
        var sourceName = $('#source_id option:selected').text();
        $('#source_name').val(sourceName);
    } else {
        $('#source_name').val('');
    }

    if($('#destination_id').val()) {
        //set destination name of selected destination
        var destinationName = $('#destination_id option:selected').text();
        $('#destination_name').val(destinationName);
    } else {
        $('#destination_name').val('');
    }

    if($('#material_id').val()) {
        //set material name of selected material
        var materialName = $('#material_id option:selected').text();
        $('#material_name').val(materialName);
    } else {
        $('#material_name').val('');
    }
}
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
//method for total rent calculation
function calculateTripRent() {
    var quantity    = ($('#rent_measurement').val() > 0 ? $('#rent_measurement').val() : 0 );
    var rate        = ($('#rent_rate').val() > 0 ? $('#rent_rate').val() : 0 );
    var tripRent    = 0;

    tripRent  = quantity * rate;
    if(tripRent > 0) {
        $('#trip_rent').val(tripRent);
    } else {
        $('#trip_rent').val(0);
    }
}

//method for total rent calculation
function calculateTotalRent() {
    var noOfTrip    = ($('#no_of_trip').val() > 0 ? $('#no_of_trip').val() : 0);
    var tripRent    = ($('#trip_rent').val() > 0 ? $('#trip_rent').val() : 0 );
    var totalRent   = 0;

    totalRent = tripRent * noOfTrip;
    if(totalRent > 0) {
        $('#total_rent').val(totalRent);
    } else {
        $('#total_rent').val(0);
    }
}

//method for total first driver wage calculation
function calculateDriverWageTotal() {
    var noOfTrip    = ($('#no_of_trip').val() > 0 ? $('#no_of_trip').val() : 0);
    var tripWage    = ($('#driver_wage_0').val() > 0 ? $('#driver_wage_0').val() : 0 );
    var totalWage   = 0;

    totalWage = tripWage * noOfTrip;
    if(totalWage > 0) {
        $('#driver_total_wage_0').val(totalWage);
    } else {
        $('#driver_total_wage_0').val(0);
    }
    calculateSecondDriverWageTotal();
}

function calculateDriverWage() {
    var tripRent   = $('#trip_rent').val();
    var tripWage   = 0;
    var totalWage  = 0;

    //wage calculation
    var wageType    = $('#driver_id_0').find(':selected').data('wage-type');
    var wageAmount  = ($('#driver_id_0').find(':selected').data('wage-amount') > 0 ? $('#driver_id_0').find(':selected').data('wage-amount') : 0);
    var noOfTrip    = ($('#no_of_trip').val() > 0 ? $('#no_of_trip').val() : 0);

    if(wageAmount > 0) {
        switch(wageType) {
            case 1:
                //Per Trip [%]
                tripWage = tripRent * (wageAmount/100);
                break;
            case 2:
                //Per Trip [Fixed]
                tripWage = wageAmount;
                break;
            case 3:
                //Per Month [Fixed]
            case 4:
                //Per Day [Fixed]
            default:
                tripWage = 0;
        }
        totalWage = tripWage * noOfTrip;

        $('#driver_wage_0').val(tripWage);
        $('#driver_total_wage_0').val(totalWage);
    } else {
        $('#driver_wage_0').val('');
        $('#driver_total_wage_0').val('');
    }

    calculateSecondDriverWage();
}

function calculateSecondDriverWage() {
    var secondDriver = $('#driver_id_1').val();
    if(secondDriver) {
        var firstDriverWage = $('#driver_wage_0').val();
        var secondDriverWageRatio = ((secondDriverWageRatioGlobal && secondDriverWageRatioGlobal != 'undefined') ? secondDriverWageRatioGlobal : 0);
        var secondDriverWage = firstDriverWage * secondDriverWageRatio;
        var firstDriverUpdatedWage = firstDriverWage - secondDriverWage;
        if(secondDriverWage > 0 && firstDriverUpdatedWage > 0) {
            $('#driver_wage_0').val(firstDriverUpdatedWage);
            $('#driver_wage_1').val(secondDriverWage);
        } else {
            $('#driver_wage_1').val(0);
        }
    } else {
        $('#driver_wage_1').val(0);
    }
    calculateDriverWageTotal();
    calculateSecondDriverWageTotal();
}

//method for total first driver wage calculation
function calculateSecondDriverWageTotal() {
    var noOfTrip    = ($('#no_of_trip').val() > 0 ? $('#no_of_trip').val() : 0);
    var tripWage    = ($('#driver_wage_1').val() > 0 ? $('#driver_wage_1').val() : 0 );
    var totalWage   = 0;

    totalWage = tripWage * noOfTrip;
    if(totalWage > 0) {
        $('#driver_total_wage_1').val(totalWage);
    } else {
        $('#driver_total_wage_1').val(0);
    }
}

//get driver details
function driverByTruck() {
    var truckId = $('#truck_id').val();

    if(truckId) {
        $.ajax({
            url: "/last/transportation", //"/transportation/driver",
            method: "get",
            data: {
                truck_id : truckId,
                type     : 'get-driver'
            },
            success: function(result) {
                if(result && result.flag) {
                    $('#driver_id_0').val(result.employee_id);
                    $('#driver_id_0').trigger('change');
                } else {
                    $('#driver_id_0').val('');
                    $('#driver_id_0').trigger('change');
                }
            },
            error: function () {
                $('#driver_id_0').val('');
                $('#driver_id_0').trigger('change');
            }
        });
    }
    //if no truck selected
    $('#driver_id').val('');
    $('#driver_id').trigger('change');
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
                destination_id  : destinationId,
                type            : 'get-contractor'
            },
            success: function(result) {
                if(result && result.flag) {
                    $('#contractor_account_id').val(result.contractor_account_id);
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
                type                    : 'get-measures'
            },
            success: function(result) {
                if(result && result.flag) {
                    $('#rent_type').val(result.rent_type);
                    $('#rent_type').trigger('change');
                    $('#rent_measurement').val(result.rent_measurement);
                    $('#rent_measurement').trigger('change');
                    $('#rent_rate').val(result.rent_rate);
                    $('#rent_rate').trigger('change');
                    $('#material_id').val(result.material_id);
                    $('#material_id').trigger('change');
                } else {
                    $('#rent_type').val('');
                    $('#rent_type').trigger('change');
                    $('#rent_measurement').val('');
                    $('#rent_measurement').trigger('change');
                    $('#rent_rate').val('');
                    $('#rent_rate').trigger('change');
                    $('#material_id').val('');
                    $('#material_id').trigger('change');
                }
            },
            error: function () {
                $('#rent_type').val('');
                $('#rent_type').trigger('change');
                $('#rent_measurement').val('');
                $('#rent_measurement').trigger('change');
                $('#rent_rate').val('');
                $('#rent_rate').trigger('change');
                $('#material_id').val('');
                $('#material_id').trigger('change');
            }
        });
    }
}
