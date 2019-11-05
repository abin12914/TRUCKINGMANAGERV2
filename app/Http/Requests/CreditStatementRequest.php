<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreditStatementRequest extends FormRequest
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
            'to_date'       =>  [
                                    'nullable',
                                    'date_format:d-m-Y',
                                ],
            'relation_type' =>  [
                                    'nullable',
                                    Rule::in(array_keys(config('constants.accountRelations'))),
                                ],
        ];
    }
}
