<?php

namespace App\Http\Requests\Operations;

use Illuminate\Foundation\Http\FormRequest;

class StoreExtractRequest extends FormRequest
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

        
        
        return [
            
        
           // 'instance_type'=>'required|string',
            'slip'=>'nullable|string',
            'timer'=>'nullable|string',
            'provider'=>'required|string',
           // 'loader_id'=>'required|string',
            'files' => 'required|string',
          //  'files' => 'required|array|min:1|max:100',
           // 'files.*' => 'required|string',
        


        
        ];
    }
}
