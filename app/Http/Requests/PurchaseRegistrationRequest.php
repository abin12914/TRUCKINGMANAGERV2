<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

class PurchaseRegistrationRequest extends FormRequest
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
            'supplier_account_id'   =>  [
                                            'required',
                                            Rule::exists('accounts', 'id')->where(function ($query) {
                                                $query->where('company_id', Auth::User()->company_id);
                                            })
                                        ],
            'purchase_date'         =>  [
                                            'required',
                                            'date_format:d-m-Y',
                                        ],
            'purchase_measure_type' =>  [
                                            'required',
                                            Rule::in(array_keys(config('constants.measureTypes'))),
                                        ],
            'purchase_quantity'     =>  [
                                            'required',
                                            'numeric',
                                            'min:1',
                                            'max:9999',
                                        ],
            'purchase_rate'         =>  [
                                            'required',
                                            'numeric',
                                            'min:0.01',
                                            'max:999999',
                                        ],
            'purchase_bill'         =>  [
                                            'required',
                                            'numeric',
                                            'min:1',
                                            'max:999999',
                                        ],
            'purchase_discount'     =>  [
                                            'required',
                                            'numeric',
                                            'max:99999',
                                            'min:0',
                                        ],
            'purchase_trip_bill'    =>  [
                                            'required',
                                            'numeric',
                                            'max:999999',
                                            'min:1',
                                        ],
            'purchase_no_of_trip'   =>  [
                                            'required',
                                            'integer',
                                            'min:1',
                                            'max:10',
                                        ],
            'purchase_total_bill'   =>  [
                                            'required',
                                            'numeric',
                                            'max:999999',
                                            'min:1',
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
        $quanty     = $this->request->get("purchase_quantity");
        $rate       = $this->request->get("purchase_rate");
        $tripBill   = $this->request->get("purchase_bill");
        $noOfTrip   = $this->request->get("purchase_no_of_trip");
        $totalBill  = $this->request->get("purchase_total_bill");

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
            'supplier_account_id.required'  => 'The supplier field is required.',
            'supplier_account_id.exists'    => 'Invalid data.',
            'purchase_trip_bill.required'   => 'The trip bill field is required.',
            'purchase_trip_bill.numeric'    => 'Invalid data.',
            'purchase_trip_bill.max'        => 'The trip bill value is exceeded the maximum value. [should be less than 10 Lakhs]',
            'purchase_trip_bill.min'        => 'The trip bill value is expected to a minimum.',
            'purchase_total_bill.required'  => 'The total bill field is required.',
            'purchase_total_bill.numeric'   => 'Invalid data.',
            'purchase_total_bill.max'       => 'The total bill value is exceeded the maximum value. [should be less than 10 Lakhs]',
            'purchase_total_bill.min'       => 'The total bill value is expected to a minimum.',
        ];
    }
}
