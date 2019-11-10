<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

class CertificateUpdateRequest extends FormRequest
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
                                        'required',
                                        Rule::exists('trucks', 'id')->where(function ($query) {
                                            $query->where('company_id', Auth::User()->company_id);
                                        })
                                    ],
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
            'certificate_type'  =>  [
                                        'required',
                                        Rule::in(config('constants.certificateTypes'))
                                    ],
            'updated_date'      =>  [
                                        'required',
                                        'date_format:d-m-Y',
                                        'after:today',
                                    ],
        ];
    }
}
