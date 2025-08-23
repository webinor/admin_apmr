<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistratorRequest extends FormRequest
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
            "name"=>"required|string",
            "last_name"=>"required|string",
            "email"=>"nullable|string|email",
            "city"=>"required|string",
            "signature"=>"nullable|string",
            'file' => 'required|file|mimes:jpg,jpeg,png|max:10240,dimensions:min_width=80,min_height=80',
        ];
    }
}
