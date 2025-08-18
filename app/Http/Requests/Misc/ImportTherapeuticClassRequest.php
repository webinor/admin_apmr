<?php

namespace App\Http\Requests\Misc;

use Illuminate\Foundation\Http\FormRequest;

class ImportTherapeuticClassRequest extends FormRequest
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
          //  "therapeutic_classes"=>"required|string",
            "therapeutic_classes"=>"required|file",
            "generation_type"=>"required|string",
            
        ];
    }
}
