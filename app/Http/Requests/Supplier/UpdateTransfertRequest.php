<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransfertRequest extends FormRequest
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
            'transfert'=>'required|string',
            'supplier'=>'required|string',
            //'title'=>'required|string',
            'bank'=>'required|string',
            'currency'=>'required|string',
            'amount'=>'required|numeric',
            'end-amount'=>'required|numeric',
            'deposit'=>'nullable|date',
            'execution'=>'nullable|date',
            
        ];
    }
}
