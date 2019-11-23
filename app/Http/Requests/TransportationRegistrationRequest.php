<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

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
                                            Rule::exists('trucks', 'id')->where(function ($query) {
                                                $query->where('company_id', Auth::User()->company_id);
                                            })
                                        ],
            'transportation_date'   =>  [
                                            'required',
                                            'date_format:d-m-Y',
                                            'before_or_equal:today'
                                        ],
            'source_id'             =>  [
                                            'required',
                                            Rule::exists('sites', 'id')->where(function ($query) {
                                                $query->where('company_id', Auth::User()->company_id);
                                            })
                                        ],
            'destination_id'        =>  [
                                            'required',
                                            'different:source_id',
                                            Rule::exists('sites', 'id')->where(function ($query) {
                                                $query->where('company_id', Auth::User()->company_id);
                                            })
                                        ],
            'contractor_account_id' =>  [
                                            'required',
                                            Rule::exists('accounts', 'id')->where(function ($query) {
                                                $query->where('company_id', Auth::User()->company_id);
                                            })
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
                                            'max:99999',
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
                                            Rule::exists('employees', 'id')->where(function ($query) {
                                                $query->where('company_id', Auth::User()->company_id);
                                            })
                                        ],
            'driver_wage'           =>  [
                                            'required',
                                            'numeric',
                                            'max:5000',
                                            'min:10',
                                        ],
            'driver_total_wage'     =>  [
                                            'required',
                                            'numeric',
                                            'min:10',
                                            'max:9999'
                                        ]
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
            if (!$this->checkCalculations() || !$this->hasNameValues()) {
                $validator->errors()->add('calculations', 'Something went wrong! Please try again after reloading the page');
            }
        });
    }

    public function checkCalculations() {
        $quanty             = $this->request->get("rent_measurement");
        $rate               = $this->request->get("rent_rate");
        $tripRent           = $this->request->get("trip_rent");
        $noOfTrip           = $this->request->get("no_of_trip");
        $totalRent          = $this->request->get("total_rent");
        $driverWage         = $this->request->get("driver_wage");
        $driverTotalWage    = $this->request->get("driver_total_wage");

        return (($quanty * $rate) == $tripRent && ($tripRent * $noOfTrip) == $totalRent && ($driverWage * $noOfTrip) == $driverTotalWage);
    }

    public function hasNameValues() {
        $truckRegNumber  = $this->request->get("truck_reg_number");
        $sourceName      = $this->request->get("source_name");
        $destinationName = $this->request->get("destination_name");

        return (!empty($truckRegNumber) && !empty($sourceName) && !empty($destinationName));
    }
}
