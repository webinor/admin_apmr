<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExtractionSettingRequest extends FormRequest
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
            'provider'=>'required|string',
            "extract_params"=>'required|string',
            "folder_matcher"=>'required|string',
            "reference_matcher"=>'required|string',
            "service_matcher"=>'required|string',
            "service_switcher"=>'required|string',
            "service_mask"=>'required|string',
            "items_mask"=>'required|string',
            "items_layout"=>'required|string',
            "pure_matcher"=>'required|string',
        ];
    }
}
