$(function () {

    //setting measurement of purchase at source site
    $('body').on("change keyup", "#no_of_trip", function() {
        $('#purchase_no_of_trip').val($(this).val());
        $('#sale_no_of_trip').val($(this).val());
    });

    //handle link to tabs
    var url = document.location.toString();
    if (url.match('#')) {
        $('.nav-tabs-custom a[href="#' + url.split('#')[1] + '"]').tab('show');
    }

    // Change hash for page-reload
    $('.nav-tabs-custom a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });

    $('body').on("click", ".arrows", function () {
        var link = $(this).attr("href");
        $('li a[href="'+ link +'"]').trigger('click');
    });

    //setting measurement of purchase at source site
    $('body').on("change", "#supplier_account_id", function() {
        purchaseDetailsByCombo();
    });

    //measure type change event actions
    $('body').on("change", "#purchase_measure_type", function (evt) {
        var measureType = $('#purchase_measure_type').val();

        //disable and assign value if fixed rent
        if(measureType && measureType == 3) {
            $('#purchase_quantity').prop("readonly",true);
            $('#purchase_quantity').val(1);
        } else {
            $('#purchase_quantity').prop("readonly",false);
            $('#purchase_quantity').val('');
        }

        //calculate total rent
        calculatePurchaseBill();
    });

    //setting measurement of sale at destination site
    $('body').on("change", "#customer_account_id", function() {
        saleDetailsByCombo();
    });

    //measure type change event actions
    $('body').on("change", "#sale_measure_type", function (evt) {
        var measureType = $('#sale_measure_type').val();

        //disable and assign value if fixed rent
        if(measureType && measureType == 3) {
            $('#sale_quantity').val(1);
            $('#sale_quantity').prop("readonly",true);
        } else {
            $('#sale_quantity').prop("readonly",false);
            $('#sale_quantity').val('');
        }

        //calculate total rent
        calculateSaleBill();
    });

    //purchase quantity event actions
    $('body').on("change keyup", "#purchase_quantity", function (evt) {
        //calculate total purchase bill
        calculatePurchaseBill();
    });

    //purchase rate event actions
    $('body').on("change keyup", "#purchase_rate", function (evt) {
        //calculate total purchase bill
        calculatePurchaseBill();
    });

    //purchase rate event actions
    $('body').on("change keyup", "#purchase_discount", function (evt) {
        //calculate total purchase bill
        calculatePurchaseBill();
    });

    //sale quantity event actions
    $('body').on("change keyup", "#sale_quantity", function (evt) {
        //calculate total sale bill
        calculateSaleBill();
    });

    //sale rate event actions
    $('body').on("change keyup", "#sale_rate", function (evt) {
        //calculate total sale bill
        calculateSaleBill();
    });

    //sale rate event actions
    $('body').on("change keyup", "#sale_discount", function (evt) {
        //calculate total sale bill
        calculateSaleBill();
    });
});

//method for total bill calculation of purchase
function calculatePurchaseBill() {
    var quantity    = ($('#purchase_quantity').val() > 0 ? $('#purchase_quantity').val() : 0 );
    var rate        = ($('#purchase_rate').val() > 0 ? $('#purchase_rate').val() : 0 );
    var discount    = ($('#purchase_discount').val() > 0 ? $('#purchase_discount').val() : 0 );
    var noOfTrip    = ($('#purchase_no_of_trip').val() > 0 ? $('#purchase_no_of_trip').val() : 0 );
    var bill        = 0;
    var totalBill   = 0;

    bill  = quantity * rate;
    if(bill > 0) {
        $('#purchase_bill').val(bill);
        if((bill - discount) > 0) {
            tripBill  = bill - discount;
            totalBill = tripBill * noOfTrip;
            $('#purchase_trip_bill').val(tripBill);
            $('#purchase_total_bill').val(totalBill);
        } else {
            $('#purchase_discount').val(0);
            $('#purchase_trip_bill').val(bill);
            $('#purchase_total_bill').val((bill * noOfTrip))
        }
    } else {
        $('#purchase_bill').val(0);
        $('#purchase_discount').val(0);
        $('#purchase_trip_bill').val(0);
        $('#purchase_total_bill').val(0)
    }
}

//method for total bill calculation of sale
function calculateSaleBill() {
    var quantity    = ($('#sale_quantity').val() > 0 ? $('#sale_quantity').val() : 0 );
    var rate        = ($('#sale_rate').val() > 0 ? $('#sale_rate').val() : 0 );
    var discount    = ($('#sale_discount').val() > 0 ? $('#sale_discount').val() : 0 );
    var noOfTrip    = ($('#sale_no_of_trip').val() > 0 ? $('#sale_no_of_trip').val() : 0 );
    var bill        = 0;
    var totalBill   = 0;

    bill  = quantity * rate;
    if(bill > 0) {
        $('#sale_bill').val(bill);
        if((bill - discount) > 0) {
            tripBill  = bill - discount;
            totalBill = tripBill * noOfTrip;
            $('#sale_trip_bill').val(tripBill);
            $('#sale_total_bill').val(totalBill);
        } else {
            $('#sale_discount').val(0);
            $('#sale_trip_bill').val(bill);
            $('#sale_total_bill').vl((bill * noOfTrip));
        }

    } else {
        $('#sale_bill').val(0);
        $('#sale_discount').val(0);
        $('#sale_trip_bill').val(0);
        $('#sale_total_bill').val(0);
    }
}

function purchaseDetailsByCombo() {
    var truckId             = $('#truck_id').val();
    var sourceId            = $('#source_id').val();
    var materialId          = $('#material_id').val();
    var supplierAccountId   = $('#supplier_account_id').val();

    if(truckId && sourceId && materialId && supplierAccountId) {
        $.ajax({
            url: "/last/purchase",
            method: "get",
            data: {
                truck_id            : truckId,
                source_id           : sourceId,
                material_id         : materialId,
                supplier_account_id : supplierAccountId,
            },
            success: function(result) {
                if(result && result.flag) {
                    var measureType = result.measure_type;
                    var quantity    = result.quantity;
                    var rate        = result.rate;

                    $('#purchase_measure_type').val(measureType);
                    $('#purchase_measure_type').trigger('change');
                    $('#purchase_quantity').val(quantity);
                    $('#purchase_quantity').trigger('change');
                    $('#purchase_rate').val(rate);
                    $('#purchase_rate').trigger('change');
                } else {
                    $('#purchase_measure_type').val('');
                    $('#purchase_measure_type').trigger('change');
                    $('#purchase_quantity').val('');
                    $('#purchase_quantity').trigger('change');
                    $('#purchase_rate').val('');
                    $('#purchase_rate').trigger('change');
                }
            },
            error: function () {
                $('#purchase_measure_type').val('');
                $('#purchase_measure_type').trigger('change');
                $('#purchase_quantity').val('');
                $('#purchase_quantity').trigger('change');
                $('#purchase_rate').val('');
                $('#purchase_rate').trigger('change');
            }
        });
    }
}

function saleDetailsByCombo() {
    var truckId             = $('#truck_id').val();
    var destinationId       = $('#destination_id').val();
    var materialId          = $('#material_id').val();
    var customerAccountId   = $('#customer_account_id').val();

    if(truckId && destinationId && materialId && customerAccountId) {
        $.ajax({
            url: '/last/sale',
            method: "get",
            data: {
                truck_id            : truckId,
                destination_id      : destinationId,
                material_id         : materialId,
                customer_account_id : customerAccountId,
            },
            success: function(result) {
                if(result && result.flag) {
                    var saleMeasureType  = result.measure_type;
                    var saleQuantity    = result.quantity;
                    var saleRate        = result.rate;

                    $('#sale_measure_type').val(saleMeasureType);
                    $('#sale_measure_type').trigger('change');
                    $('#sale_quantity').val(saleQuantity);
                    $('#sale_quantity').trigger('change');
                    $('#sale_rate').val(saleRate);
                    $('#sale_rate').trigger('change');
                } else {
                    $('#sale_measure_type').val('');
                    $('#sale_measure_type').trigger('change');
                    $('#sale_quantity').val('');
                    $('#sale_quantity').trigger('change');
                    $('#sale_rate').val('');
                    $('#sale_rate').trigger('change');
                }
            },
            error: function () {
                $('#sale_measure_type').val('');
                $('#sale_measure_type').trigger('change');
                $('#sale_quantity').val('');
                $('#sale_quantity').trigger('change');
                $('#sale_rate').val('');
                $('#sale_rate').trigger('change');
            }
        });
    }
}
