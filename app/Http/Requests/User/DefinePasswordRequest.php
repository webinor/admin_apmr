<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class DefinePasswordRequest extends FormRequest
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
            "login"=>"required|string",
            "password"=>[
                'required',
                Password::min(8)
                    ->letters()
                   // ->mixedCase()
            ],
            "defined_token"=>"required|string"
        ];
    }
}
