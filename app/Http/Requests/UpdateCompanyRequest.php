<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
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
            'company'=>'required|string',
            'name'=>'required|string',
            "alias"=>"required|string",
            'city'=>'required|string',
            'prefix'=>'required|string',
            "mensual_fee"=>"required|numeric",
            'billing_address'=>'nullable|string',
            'email' => [
                'nullable',
                'email',
                Rule::unique('companies', 'email')->ignore($this->route('company')),
                 // $this->user correspond au paramètre {user} de ta route
            ],
           // 'email' => 'required|email|unique:companies,email',
            'post_box'=>'nullable|string',
            'uni'=>'nullable|string',
            'rc'=>'nullable|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png|max:10240,dimensions:min_width=80,min_height=80',

        ];
    }
}
