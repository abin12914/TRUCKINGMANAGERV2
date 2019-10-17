<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransportationRegistrationRequest extends FormRequest
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
            'truck_id'              =>  [
                                            'required',
                                            'exists:trucks,id'
                                        ],
            'transportation_date'   =>  [
                                            'required',
                                            'date_format:d-m-Y',
                                        ],
            'source_id'             =>  [
                                            'required',
                                            'exists:sites,id'
                                        ],
            'destination_id'        =>  [
                                            'required',
                                            'different:source_id',
                                            'exists:sites,id'
                                        ],
            'contractor_account_id' =>  [
                                            'required',
                                            'exists:accounts,id'
                                        ],
            'material_id'           =>  [
                                            'required',
                                            'exists:materials,id'
                                        ],
            'rent_type'             =>  [
                                            'required',
                                            Rule::in(array_keys(config('constants.rentTypes'))),
                                        ],
            'rent_measurement'      =>  [
                                            'required',
                                            'numeric',
                                            'min:1',
                                            'max:500',
                                        ],
            'rent_rate'             =>  [
                                            'required',
                                            'numeric',
                                            'min:0.1',
                                            'max:25000',
                                        ],
            'trip_rent'             =>  [
                                            'required',
                                            'numeric',
                                            'max:99999',
                                            'min:1',
                                        ],
            'no_of_trip'            =>  [
                                            'required',
                                            'integer',
                                            'min:1',
                                            'max:25',
                                        ],
            'total_rent'            =>  [
                                            'required',
                                            'numeric',
                                            'max:999999',
                                            'min:1',
                                        ],
            'driver_id'             =>  [
                                            'required',
                                            Rule::in(Employee::pluck('id')->toArray()),
                                        ],
            'driver_wage'         =>  [
                                            'required',
                                            'numeric',
                                            'max:5000',
                                            'min:10',
                                        ],
            'second_driver_id'             =>  [
                                            'required',
                                            Rule::in(Employee::pluck('id')->toArray()),
                                        ],
            'second_driver_wage'         =>  [
                                            'required',
                                            'numeric',
                                            'max:5000',
                                            'min:10',
                                        ],
        ];
    }
}
