<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
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
            'supplier'=>'required|string',
            'area_id'=>'required|numeric',
            'social_reason'=>'required|string',
            'customer_type_id'=>'nullable|numeric|min:0',
            'token'=>'required|string',
            'activity_area_id'=>'nullable|string',
            'activities'=>'nullable|string',
            'city_id'=>'nullable|numeric|min:1',
            'address'=>'nullable|string',
            'unique_identification_number'=>'nullable|string',
            'main_phone_number'=>'nullable|string',
            'auxiliary_phone_number'=>'nullable|string',
            'whatsapp_phone_number'=>'nullable|string',
            'email'=>'nullable|string',
            'logo' => 'nullable|file|mimes:jpg,jpeg,png|max:10240,dimensions:min_width=80,min_height=80',
            //'file' => 'nullable|file|mimes:jpg,jpeg,png|max:10240,dimensions:min_width=80,min_height=80',
            'begining_collaboration'=>'nullable|date|before_or_equal:today',
        ];
    }
}
