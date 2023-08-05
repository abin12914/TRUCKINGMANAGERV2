<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

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
                                            'max:9999',
                                        ],
            'sale_rate'             =>  [
                                            'required',
                                            'numeric',
                                            'min:0.01',
                                            'max:999999',
                                        ],
            'sale_bill'             =>  [
                                            'required',
                                            'numeric',
                                            'min:1',
                                            'max:999999',
                                        ],
            'sale_discount'         =>  [
                                            'required',
                                            'numeric',
                                            'max:99999',
                                            'min:0',
                                        ],
            'sale_trip_bill'        =>  [
                                            'required',
                                            'numeric',
                                            'max:999999',
                                            'min:1',
                                        ],
            'sale_no_of_trip'       =>  [
                                            'required',
                                            'integer',
                                            'min:1',
                                            'max:10',
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

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'customer_account_id.required'  => 'The customer field is required.',
            'customer_account_id.exists'    => 'Invalid data.',
            'sale_trip_bill.required'       => 'The trip bill field is required.',
            'sale_trip_bill.numeric'        => 'Invalid data.',
            'sale_trip_bill.max'            => 'The trip bill value is exceeded the maximum value. [should be less than 10 Lakhs].',
            'sale_trip_bill.min'            => 'The trip bill value is expected to a minimum.',
            'sale_total_bill.required'      => 'The total bill field is required.',
            'sale_total_bill.numeric'       => 'Invalid data.',
            'sale_total_bill.max'           => 'The total bill value is exceeded the maximum value. [should be less than 10 Lakhs].',
            'sale_total_bill.min'           => 'The total bill value is expected to a minimum.',
        ];
    }
}
