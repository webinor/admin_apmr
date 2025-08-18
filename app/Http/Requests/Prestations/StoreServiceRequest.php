<?php

namespace App\Http\Requests\Prestations;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
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

      //  dd($this->generation_type);

        if ($this->generation_type == "A") {
            
            $rules = [
            'services'=>'required|file',
            'generation_type'=>'required|string',

            ];
        }
        elseif ($this->generation_type == "M") {

            $rules = [
                'generation_type'=>'required|string',
                'is_quoted'=>'required|string',
                'token'=>'required|string',
                //'provider_type'=>'required|string',
                'service_name'=>'required|string',
                'keywords'=>'nullable|string',
                'service_type'=>'required|string',
                
                'coverage'=>'required|string',
  
            ];

            if ($this->is_quoted == "1") {


                $rules+=[
                    'quote'=>'required|numeric',
                ];


            } else {
                
                $rules+=[

                    'public_amount'=>'required|numeric',
                    'private_amount'=>'required|numeric',
                    'parapublic_amount'=>'required|numeric',
                    'confessional_amount'=>'required|numeric',

                ];

                
            }
            
        }

      //  dd($rules);

        return $rules;

    }
}
