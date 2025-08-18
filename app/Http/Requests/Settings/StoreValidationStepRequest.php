<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class StoreValidationStepRequest extends FormRequest
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
            'slug'=> 'required|string|min:1',
            'steps'=> 'required|numeric|min:1',
            'resource_type'=> 'required|string',
            'is_active'=> 'required|boolean',
            'validators'=> 'required|string',
        ];
    }
}
