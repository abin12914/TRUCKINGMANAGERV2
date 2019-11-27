<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExpenseFilterRequest extends FormRequest
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
            'from_date'     =>  [
                                    'nullable',
                                    'date_format:d-m-Y',
                                ],
            'to_date'       =>  [
                                    'nullable',
                                    'date_format:d-m-Y',
                                    'after_or_equal:from_date'
                                ],
            'service_id'    =>  [
                                    'nullable',
                                    'exists:services,id',
                                ],
            'account_id'    =>  [
                                    'nullable',
                                    'exists:accounts,id'
                                ],
            'truck_id'      =>  [
                                    'nullable',
                                    'exists:trucks,id',
                                ],
            'no_of_records' =>  [
                                    'nullable',
                                    'min:2',
                                    'max:100',
                                    'integer',
                                ],
            'page'          =>  [
                                    'nullable',
                                    'integer',
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
            'service_id.exists' => 'Invalid data.',
            'account_id.exists' => 'Invalid data.',
            'truck_id.exists'   => 'Invalid data.'
        ];
    }
}
