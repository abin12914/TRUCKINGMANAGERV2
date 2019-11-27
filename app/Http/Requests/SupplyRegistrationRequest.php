<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\TransportationRegistrationRequest;
use App\Http\Requests\PurchaseRegistrationRequest;
use App\Http\Requests\SaleRegistrationRequest;

class SupplyRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $transportationRules = (new TransportationRegistrationRequest())->rules();
        $purchaseRules       = (new PurchaseRegistrationRequest())->rules();
        $saleRules           = (new SaleRegistrationRequest())->rules();
        $supplyRules         = [
            'sale_no_of_trip'       =>  [
                                            'same:no_of_trip'
                                        ],
            'purchase_no_of_trip'   =>  [
                                            'same:no_of_trip'
                                        ]
        ];

        return array_merge(
            $transportationRules,
            $purchaseRules,
            $saleRules,
            $supplyRules
        );
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $transportationMessages = (new TransportationRegistrationRequest())->messages();
        $purchaseMessages       = (new PurchaseRegistrationRequest())->messages();
        $saleMessages           = (new SaleRegistrationRequest())->messages();
        $supplyMessages         = [
            'sale_no_of_trip.same'     => 'Invalid data.',
            'purchase_no_of_trip.same' => 'Invalid data.',
        ];

        return array_merge(
            $transportationMessages,
            $purchaseMessages,
            $saleMessages,
            $supplyMessages
        );
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->checkTransportationCalculations() || !$this->checkPurchaseCalculations() || !$this->checkSaleCalculations()) {
                $validator->errors()->add('calculations', 'Something went wrong with the calculations! Please try again after reloading the page');
            }
        });
    }

    public function checkTransportationCalculations() {
        $quanty             = $this->request->get("rent_measurement");
        $rate               = $this->request->get("rent_rate");
        $tripRent           = $this->request->get("trip_rent");
        $noOfTrip           = $this->request->get("no_of_trip");
        $totalRent          = $this->request->get("total_rent");
        $driverWage         = $this->request->get("driver_wage");
        $driverTotalWage    = $this->request->get("driver_total_wage");

        return (($quanty * $rate) == $tripRent && ($tripRent * $noOfTrip) == $totalRent && ($driverWage * $noOfTrip) == $driverTotalWage);
    }

    public function checkPurchaseCalculations() {
        $quanty     = $this->request->get("purchase_quantity");
        $rate       = $this->request->get("purchase_rate");
        $tripBill   = $this->request->get("purchase_bill");
        $noOfTrip   = $this->request->get("purchase_no_of_trip");
        $totalBill  = $this->request->get("purchase_total_bill");

        return (($quanty * $rate) == $tripBill && ($tripBill * $noOfTrip) == $totalBill);
    }

    public function checkSaleCalculations() {
        $quanty     = $this->request->get("sale_quantity");
        $rate       = $this->request->get("sale_rate");
        $tripBill   = $this->request->get("sale_bill");
        $noOfTrip   = $this->request->get("sale_no_of_trip");
        $totalBill  = $this->request->get("sale_total_bill");

        return (($quanty * $rate) == $tripBill && ($tripBill * $noOfTrip) == $totalBill);
    }
}
