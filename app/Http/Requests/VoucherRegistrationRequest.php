<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

class VoucherRegistrationRequest extends FormRequest
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
            'transaction_type'  =>  [
                                        'required',
                                        Rule::in([1, 2, 3]),
                                    ],
            'debit_account_id'  =>  [
                                        'required_if:transaction_type,2,3',
                                        'nullable',
                                        // 'different:credit_account_id',
                                        Rule::exists('accounts', 'id')->where(function ($query) {
                                            $query->where('company_id', Auth::User()->company_id);
                                        })
                                    ],
            'credit_account_id' =>  [
                                        'required_if:transaction_type,1,3',
                                        'nullable',
                                        // 'different:debit_account_id',
                                        Rule::exists('accounts', 'id')->where(function ($query) {
                                            $query->where('company_id', Auth::User()->company_id);
                                        })
                                    ],
            'transaction_date'  =>  [
                                        'required',
                                        'date_format:d-m-Y',
                                        'before_or_equal:today',
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

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'debit_account_id.required_if'  => 'The reciever account field is required.',
            'credit_account_id.required_if' => 'The giver account field is required.',
            'debit_account_id.exists'       => 'Invalid data.',
            'credit_account_id.exists'      => 'Invalid data.'
        ];
    }
}
