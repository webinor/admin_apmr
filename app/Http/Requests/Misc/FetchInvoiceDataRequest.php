<?php

namespace App\Http\Requests\Misc;

use Illuminate\Foundation\Http\FormRequest;

class FetchInvoiceDataRequest extends FormRequest
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
        if ($this->step == 'validate') {
           
            return [
                'folder'=>'required|string',
                'step'=>'required|string',

               // 'invoices'=>'required|array',
               // 'is_draft'=>'required|boolean',
            ];
            
        }
        return [
            'action'=>'required|string',
            'invoice'=>'nullable|string',
            'invoice_line'=>'nullable|string',
            'folder'=>'required|string',
            'service'=>'required|string',
            'step'=>'required|string',
            'description'=>'required|string',
            'quantity'=>'required|numeric',
            'price'=>'required|numeric',
            'should_delete_previous_invoice'=>'required|boolean',
            'should_delete_previous_invoice_line'=>'required|boolean',
        ];
    }
}
