<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'supplier'=> 'required|string',
            'validity'=> 'nullable|numeric',
            'token'=>'required|string',
            'slug'=>'required|string',
        ];
    }
}
