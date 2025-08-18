<?php

namespace App\Http\Requests\Prestations;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceCostRequest extends FormRequest
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
            'token'=>'required|string',
            //'provider_type'=>'required|string',
            'service_name'=>'required|string',
            'keywords'=>'required|string',
            'service_type'=>'required|string',
            'quote'=>'required|numeric',
            'coverage'=>'required|string',
            /*'value_letters'=>'required',
            'value_letter_private'=>'required|numeric',
            'value_letter_confesional'=>'required|numeric',
            'value_letter_parapublic'=>'required|numeric',
            'value_letter_public'=>'required|numeric',*/
        ];
    }
}
