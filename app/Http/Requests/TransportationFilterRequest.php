<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransportationFilterRequest extends FormRequest
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
            'from_date'             =>  [
                                            'nullable',
                                            'date_format:d-m-Y',
                                        ],
            'to_date'               =>  [
                                            'nullable',
                                            'date_format:d-m-Y',
                                        ],
            'contractor_account_id' =>  [
                                            'nullable',
                                            'exists:accounts,id'
                                        ],
            'truck_id'              =>  [
                                            'nullable',
                                            'exists:trucks,id'
                                        ],
            'source_id'             =>  [
                                            'nullable',
                                            'exists:sites,id'
                                        ],
            'destination_id'        =>  [
                                            'nullable',
                                            'exists:sites,id'
                                        ],
            'driver_id'             =>  [
                                            'nullable',
                                            'exists:employees,id'
                                        ],
            'material_id'           =>  [
                                            'nullable',
                                            'exists:materials,id'
                                        ],
            'page'                  =>  [
                                            'nullable',
                                            'integer',
                                        ],
            'no_of_records'         =>  [
                                            'nullable',
                                            'min:2',
                                            'max:100',
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
            'contractor_account_id.exists'  => 'Invalid data.',
            'truck_id.exists'               => 'Invalid data.',
            'source_id.exists'              => 'Invalid data.',
            'destination_id.exists'         => 'Invalid data.',
            'driver_id.exists'              => 'Invalid data.',
            'material_id.exists'            => 'Invalid data.',
        ];
    }
}
