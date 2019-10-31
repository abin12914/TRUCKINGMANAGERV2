<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

class TruckRegistrationRequest extends FormRequest
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
            'reg_number'                    =>  [
                                                    'required_without:_method',
                                                    'max:13',
                                                    'regex:(([A-Z]){2}(-)(?:[0-9]){2}( )(((?:[A-Z]){1,2}(-)([0-9]){1,4})|(([0-9]){1,4})))',
                                                    Rule::unique('trucks', 'reg_number')->ignore($this->truck)
                                                        ->where(function ($query) {
                                                            $query->where('company_id', Auth::User()->company_id);
                                                        }),
                                                ],
            'reg_number_state_code'         =>  [
                                                    'required_without:_method',
                                                    Rule::in(config('constants.stateCodes')),
                                                ],
            'reg_number_region_code'        =>  [
                                                    'required_without:_method',
                                                    'max:99',
                                                    'min:1',
                                                    'digits:2',
                                                    'numeric',
                                                ],
            'reg_number_unique_alphabet'    =>  [
                                                    'sometimes',
                                                    'nullable',
                                                    'max:2',
                                                ],
            'reg_number_unique_digit'       =>  [
                                                    'required_without:_method',
                                                    'integer',
                                                    'max:9999',
                                                    'min:1',
                                                ],
            'ownership_status'              =>  [
                                                    'nullable',
                                                    Rule::in([1,0]),
                                                ],
            'description'                   =>  [
                                                    'nullable',
                                                    'max:200',
                                                ],
            'truck_type_id'                 =>  [
                                                    'required',
                                                    'integer',
                                                    'exists:truck_types,id'
                                                ],
            'volume'                        =>  [
                                                    'required',
                                                    'integer',
                                                    'min:10',
                                                    'max:999',
                                                ],
            'body_type'                     =>  [
                                                    'required',
                                                    Rule::in(array_keys(config('constants.truckBodyTypes'))),
                                                ],
            'insurance_upto'                =>  [
                                                    'required',
                                                    'date_format:d-m-Y',
                                                ],
            'tax_upto'                      =>  [
                                                    'required',
                                                    'date_format:d-m-Y',
                                                ],
            'permit_upto'                   =>  [
                                                    'required',
                                                    'date_format:d-m-Y',
                                                ],
            'fitness_upto'                  =>  [
                                                    'required',
                                                    'date_format:d-m-Y',
                                                ],
            'pollution_upto'                =>  [
                                                    'required',
                                                    'date_format:d-m-Y',
                                                ],
        ];
    }
}
