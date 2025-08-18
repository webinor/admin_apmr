<?php

namespace App\Http\Requests\Prestations;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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

        if ($this->generation_type == "A") {
            
            $rules = [
            'medicines'=>'required|file',
            'generation_type'=>'required|string',

            ];
        }
        elseif ($this->generation_type == "M") {

            $rules = [
                'product'=>'required|string',
                'generation_type'=>'required|string',
                'token'=>'required|string',
                'product_type'=>'required|string',
                'therapeutic_class'=>'nullable|string',
                'name'=>'required|string',
                'family'=>'required|string',
               // 'dci'=>'required|string',
                'price'=>'required|numeric',
                'coverage'=>'required|numeric',
                
            ];
        }

        return $rules;
    }
}
