<?php

namespace App\Http\Requests\HumanResource;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
        if ($this->has('habilitations')) {
            $rules = ["habilitations"=>"required|string",];
        }

        return $rules + [
            
            "first_name"=>"required|string",
            "last_name"=>"required|string",
            "address"=>"nullable|string",
            "main_phone_number"=>"required|string",
            "personal_email"=>"required|string|email",
            "role"=>"required|string",
            "token"=>"required|numeric"
           //"password"=>"required|string|confirmed",
        ];
    }
}
