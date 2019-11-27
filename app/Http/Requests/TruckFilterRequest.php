<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TruckFilterRequest extends FormRequest
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
            'truck_id'          =>  [
                                        'nullable',
                                        'exists:trucks,id'
                                    ],
            'truck_type'        =>  [
                                        'nullable',
                                        'exists:truck_types,id'
                                    ],
            'ownership_status'  =>  [
                                        'nullable',
                                        Rule::in([1, 0])
                                    ],
            'page'              =>  [
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
            'truck_id.exists' => 'Invalid data.',
        ];
    }
}
