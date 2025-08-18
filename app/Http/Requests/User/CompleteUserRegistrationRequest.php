<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompleteUserRegistrationRequest extends FormRequest
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
       
        if ($this->subscribe_method=="email") {
            
            $rules['email'] = "required|string|email|max:255";
            
        }

        if ($this->subscribe_method=="sms") {
            
            $rules['country_code'] = "required|string|max:10";
            $rules['phone_number'] = "required|string|max:50";

            
        }
        return $rules + [
            "user"=>"required|string",
            "first_name"=>"required|string",
            "last_name"=>"required|string",
            "password"=>"required|string",
            "subscribe_method"=>"required|string",
    
    ];
   
    }
}
