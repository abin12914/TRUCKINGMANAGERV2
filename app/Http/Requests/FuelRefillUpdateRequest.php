<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

class FuelRefillUpdateRequest extends FormRequest
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
            'transaction_date'  =>  [
                                        'required',
                                        'date_format:d-m-Y',
                                        'before_or_equal:today',
                                    ],
            'account_id'        =>  [
                                        'required',
                                        Rule::exists('accounts', 'id')->where(function ($query) {
                                            $query->where('company_id', Auth::User()->company_id);
                                        })
                                    ],
            'truck_id'          =>  [
                                        'required',
                                        Rule::exists('trucks', 'id')->where(function ($query) {
                                            $query->where('company_id', Auth::User()->company_id);
                                        })
                                    ],
            'odometer_reading'  =>  [
                                        'nullable',
                                        'numeric',
                                        'min:10',
                                        'max:999999'
                                    ],
            'fuel_quantity'     =>  [
                                        'required',
                                        'numeric',
                                        'min:1',
                                        'max:999'
                                    ],
            'description'       =>  [
                                        'required',
                                        'min:4',
                                        'max:200',
                                    ],
            'amount'            =>  [
                                        'required',
                                        'numeric',
                                        'min:1',
                                        'max:999999',
                                    ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'truck_id.required'     => 'The truck field is required.',
            'truck_id.exists'       => 'Invalid data.',
            'account_id.required'   => 'The supplier field is required.',
            'account_id.exists'     => 'Invalid data.'
        ];
    }
}
