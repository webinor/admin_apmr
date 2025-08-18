<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class StoreAreaDurationRequest extends FormRequest
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
        $rules = [];

        return   [
            'area_id'=>'required|numeric',
            'duration_id'=>'required|numeric',
            'min'=>'nullable|numeric|min:0',
            'max'=>'nullable|numeric|min:1',
            
        ];
    }
}
