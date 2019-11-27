<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

class AccountRegistrationRequest extends FormRequest
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
            'account_name'          =>  [
                                            'required',
                                            'max:100',
                                            'min:3',
                                            Rule::unique('accounts')->ignore($this->account)
                                                ->where(function ($query) {
                                                    $query->where('company_id', Auth::User()->company_id);
                                                }),
                                        ],
            'description'           =>  [
                                            'nullable',
                                            'max:255',
                                        ],
            'financial_status'      =>  [
                                            'required',
                                            Rule::in([0, 1, 2]),
                                        ],
            'opening_balance'       =>  [
                                            'required',
                                            'numeric',
                                            'min:0',
                                            'max:999999',
                                        ],
            'name'                  =>  [
                                            'required',
                                            'max:100',
                                            'min:3',
                                        ],
            'phone'                 =>  [
                                            'required',
                                            'numeric',
                                            'digits_between:10,13',
                                            Rule::unique('accounts', 'phone')->ignore($this->account)
                                                ->where(function ($query) {
                                                    $query->where('company_id', Auth::User()->company_id);
                                                }),
                                        ],
            'address'               =>  [
                                            'nullable',
                                            'max:255',
                                        ],
            'relation_type'         =>  [
                                            'required',
                                            Rule::in(array_keys(config('constants.accountRelations'))),
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
            'relation_type.required' => 'The relation field is required.',
            'relation_type.in'       => 'Invalid data.',
        ];
    }
}
