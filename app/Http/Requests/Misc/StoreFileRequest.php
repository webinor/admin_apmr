<?php

namespace App\Http\Requests\Misc;

use Illuminate\Foundation\Http\FormRequest;

class StoreFileRequest extends FormRequest
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
            
            /*'instance_type'=>'required|string',
            'identifier'=>'nullable|string',
            'slip'=>'required|string',
            'doc_type'=>'nullable|string',
            'transfert'=>'nullable|string',
            'provider'=>'required|string',
            'loader_id'=>'required|string',
            'validity'=>'nullable|numeric',*/
            'files' => 'nullable|array|min:1|max:40',
            'files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,bmp,png|max:30480',
        


        
        ];
    }
}
