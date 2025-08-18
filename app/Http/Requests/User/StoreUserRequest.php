<?php

namespace App\Http\Requests\Merchand;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
        return  [
            "email"=>"required|string",
            "password"=>[
                'required',
                Password::min(8)
                    ->letters()
            ],
            "first_name"=>"required|string",
            "last_name"=>"required|string",
            "phone_number"=>"required|string|max:50",
            "whatsapp_phone_number"=>"nullable|string|max:50",
            "country_code"=>"required|string|max:10",
         //   "city"=>"required|string",
        ];
    }
}
