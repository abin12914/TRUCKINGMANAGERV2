<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
