<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateCredentialRequest extends FormRequest
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
            "email"=>"required|string|email",
            "authorize_actions"=>"required|string",
            "employee"=>"required|numeric",
            "token"=>"required|numeric",
            "password"=>[
                'nullable',
                Password::min(8)
                    ->letters()
                   // ->mixedCase()
            ],
        ];
    }
}
