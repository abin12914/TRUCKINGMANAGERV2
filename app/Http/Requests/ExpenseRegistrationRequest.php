<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRegistrationRequest extends FormRequest
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
                                        'exists:accounts,id',
                                    ],
            'truck_id'          =>  [
                                        'required',
                                        'exists:trucks,id',
                                    ],
            'service_id'        =>  [
                                        'required',
                                        'exists:services,id',
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
}
