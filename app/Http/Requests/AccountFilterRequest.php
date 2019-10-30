<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountFilterRequest extends FormRequest
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
            'name'          =>  [
                                    'nullable',
                                    'string'
                                ],
            'relation_type' =>  [
                                    'nullable',
                                    Rule::in(array_keys(config('constants.accountRelations'))),
                                ],
            'account_id'    =>  [
                                    'nullable',
                                    'exists:accounts,id'
                                ],
            'no_of_records' =>  [
                                    'nullable',
                                    'integer',
                                    'min:2',
                                    'max:100',
                                ],
            'page'          =>  [
                                    'nullable',
                                    'integer',
                                ],
        ];
    }
}
