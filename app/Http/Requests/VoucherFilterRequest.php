<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VoucherFilterRequest extends FormRequest
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
            'from_date'         =>  [
                                        'nullable',
                                        'date_format:d-m-Y',
                                    ],
            'to_date'           =>  [
                                        'nullable',
                                        'date_format:d-m-Y',
                                    ],
            'transaction_type'  =>  [
                                        'nullable',
                                        Rule::in([1, 2])
                                    ],
            'account_id'        =>  [
                                        'nullable',
                                        'exists:accounts,id'
                                    ],
            'no_of_records'     =>  [
                                        'nullable',
                                        'integer',
                                        'min:2',
                                        'max:100',
                                    ],
            'page'                  =>  [
                                        'nullable',
                                        'integer',
                                    ]
        ];
    }
}
