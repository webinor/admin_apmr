<?php

namespace App\Http\Requests\Operations;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
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
            "prestations-exist"=>"nullable|string",
            "conform-price"=>"nullable|string",
            "contract-linked"=>"nullable|string",
            "folder-validated"=>"nullable|string",
            "conform-pathology"=>"nullable|string",
            "lock-filter"=>"nullable|string",
            "min-price"=>"required|string",
            "max-price"=>"required|string",
            "slip"=>"required|string",
            "results"=>"nullable|string",
            "qry"=>"nullable|string",
        ];
    }
}
