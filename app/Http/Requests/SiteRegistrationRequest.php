<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

class SiteRegistrationRequest extends FormRequest
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
            'name'      =>  [
                                'required',
                                'min:3',
                                'max:100',
                                Rule::unique('sites')->ignore($this->site)
                                    ->where(function ($query) {
                                        $query->where('company_id', Auth::User()->company_id);
                                    }),
                            ],
            'place'     =>  [
                                'required',
                                'min:3',
                                'max:100',
                            ],
            'address'   =>  [
                                'required',
                                'min:3',
                                'max:200',
                            ],
            'site_type' =>  [
                                'required',
                                Rule::in(array_keys(config('constants.siteTypes'))),
                            ],
        ];
    }
}
