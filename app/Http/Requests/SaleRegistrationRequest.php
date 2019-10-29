<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaleRegistrationRequest extends FormRequest
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
        return [
            'customer_account_id'   =>  [
                                            'required',
                                            Rule::exists('accounts', 'id')->where(function ($query) {
                                                $query->where('company_id', Auth::User()->company_id);
                                            })
                                        ],
            'sale_date'             =>  [
                                            'required',
                                            'date_format:d-m-Y',
                                        ],
            'sale_measure_type'     =>  [
                                            'required',
                                            Rule::in(array_keys(config('constants.measureTypes'))),
                                        ],
            'sale_quantity'         =>  [
                                            'required',
                                            'numeric',
                                            'min:1',
                                            'max:1000',
                                        ],
            'sale_rate'             =>  [
                                            'required',
                                            'numeric',
                                            'min:0.1',
                                            'max:50000',
                                        ],
            'sale_bill'             =>  [
                                            'required',
                                            'numeric',
                                            'max:50000',
                                            'min:10',
                                        ],
            'sale_discount'         =>  [
                                            'required',
                                            'numeric',
                                            'max:1000',
                                            'min:0',
                                        ],
            'sale_trip_bill'        =>  [
                                            'required',
                                            'numeric',
                                            'max:99999',
                                            'min:9',
                                        ],
            'sale_no_of_trip'       =>  [
                                            'required',
                                            'integer',
                                            'min:1',
                                            'max:25',
                                        ],
            'sale_total_bill'       =>  [
                                            'required',
                                            'numeric',
                                            'max:999999',
                                            'min:9',
                                        ],
        ];
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
            if (!$this->checkCalculations()) {
                $validator->errors()->add('calculations', 'Something went wrong with the calculations!&emsp; Please try again after reloading the page');
            }
        });
    }

    public function checkCalculations() {
        $quanty     = $this->request->get("sale_quantity");
        $rate       = $this->request->get("sale_rate");
        $tripBill   = $this->request->get("sale_bill");
        $noOfTrip   = $this->request->get("sale_no_of_trip");
        $totalBill  = $this->request->get("sale_total_bill");

        return (($quanty * $rate) == $tripBill && ($tripBill * $noOfTrip) == $totalBill);
    }
}
