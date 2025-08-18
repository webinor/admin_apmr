<?php

namespace App\Http\Requests\Misc;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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

        $rules =[];
        if ($this->resource =='supplier') {
            $rules = ['supplier'=> 'required|string',
            'identifier'=>'required|string',
            'amount_ht'=> 'required|numeric',
            'amount_ttc'=> 'required|numeric',
            'invoice' => 'nullable|file|mimes:pdf,jpg,jpeg,bmp,png|max:30480',
        ];
        } else if($this->resource =='customer') {
            $rules = ['customer'=> 'required|string'];
        }
        
        return $rules + [
            'resource'=> 'required|string',
            'validity'=> 'nullable|numeric',
            'token'=>'required|string',
            'billing_date'=>'required|date|before_or_equal:12/31/'.date('Y'),
            'deadline'=>'required|date|before_or_equal:12/31/'.date('Y').'|after:billing_date',
            'currency'=>'required|string',
            'slug'=>'nullable|string',
            'tax'=>'required|numeric',
        ];
    }
}
