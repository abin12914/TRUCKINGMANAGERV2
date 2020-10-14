$(function () {
    //setting last employee for selected truck
    $('body').on("change", "#truck_id", function() {
        lastFuelReFillByTruck();
    });

    //calc fuel rate on text change
    $('body').on("change", "#fuel_quantity", function (evt) {
        //calculate fuel rate
        calculateFuelRate();
    });

    //calc fuel rate on text change
    $('body').on("change", "#amount", function (evt) {
        //calculate fuel rate
        calculateFuelRate();
    });
});

function lastFuelReFillByTruck() {
    var truckId = $('#truck_id').val();

    if(truckId) {
        $.ajax({
            url: "/last/fuel-refill",
            method: "get",
            data: {
                truck_id : truckId
            },
            success: function(result) {
                if(result.flag) {
                    $('#last_odometer_reading').val(result.lastFuelRefillReading);
                } else {
                    $('#last_odometer_reading').val(0);
                }
            },
            error: function () {
                $('#last_odometer_reading').val('');
            }
        });
    }
    //if no truck selected
    $('#last_odometer_reading').val('');
}

//method for calculating fuel rate
function calculateFuelRate() {
    var quantity = ($('#fuel_quantity').val() > 0 ? $('#fuel_quantity').val() : 0 );
    var amount   = ($('#amount').val() > 0 ? $('#amount').val() : 0 );
    var rate     = 0;

    rate  = amount / quantity;
    if(rate > 0) {
        $('#fuel_rate').val(rate);
    } else {
        $('#fuel_rate').val(0);
    }
}
