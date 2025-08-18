<?php

namespace App\Http\Requests\Operations;

use Illuminate\Foundation\Http\FormRequest;

class StoreProviderRequest extends FormRequest
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
            'name'=>'required|string',
            'token'=>'required|string',
            'category'=>'required|string',
            'type'=>'required|string',
            'city_id'=>'nullable|numeric|min:1',
            'address'=>'nullable|string',
            'unique_identification_number'=>'nullable|string',
            'main_phone_number'=>'nullable|string',
            'auxiliary_phone_number'=>'nullable|string',
            'whatsapp_phone_number'=>'nullable|string',
            'email'=>'nullable|string',
            'logo' => 'nullable|file|mimes:jpg,jpeg,png|max:10240,dimensions:min_width=80,min_height=80',
        ];
    }
}
