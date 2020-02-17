<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MilageStatementRequest extends FormRequest
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
            'from_date' =>  [
                                'required_with:to_date,truck_id',
                                'nullable',
                                'date_format:d-m-Y',
                            ],
            'to_date'   =>  [
                                'required_with:from_date,truck_id',
                                'nullable',
                                'date_format:d-m-Y',
                                'after_or_equal:from_date'
                            ],
            'truck_id'  =>  [
                                'required_with:from_date,to_date',
                                'nullable',
                                'exists:trucks,id'
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
            'truck_id.required_with'  => 'The truck field is required when from date / to date is present.',
            'from_date.required_with' => 'The from date field is required when to date / truck is present.',
            'to_date.required_with'   => 'The to date field is required when from date / truck is present.',
            'truck_id.exists'         => 'Invalid data.'
        ];
    }
}
