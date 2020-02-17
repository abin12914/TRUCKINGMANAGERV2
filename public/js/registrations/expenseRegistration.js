$(function () {
    //set name values of selected options- useful when editing
    setNameValues();

    //setting truck number for selected truck
    $('body').on("change", "#truck_id", function() {
        //set selected truck reg-number
        setNameValues();
    });

    //setting service name for selected service
    $('body').on("change", "#service_id", function() {
        //set selected truck reg-number
        setNameValues();
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

    if($('#service_id').val()) {
        //set service name of selected service
        var serviceName = $('#service_id option:selected').text();
        $('#service_name').val(serviceName);
    } else {
        $('#service_name').val('');
    }
}
