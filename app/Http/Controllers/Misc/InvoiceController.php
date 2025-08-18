<?php

namespace App\Http\Controllers\Misc;

use App\Http\Controllers\Controller;
use App\Http\Requests\Misc\UpdateReferenceRequest;
use App\Models\Misc\Invoice;
use App\Services\Misc\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    
    protected  $invoice_service;

    public function __construct(InvoiceService $invoice_service) {
          $this->invoice_service = $invoice_service;
          
          //$this->authorizeResource(Folder::class, "folder");

        }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Misc\UpdateReferenceRequest  $request
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function update_reference(UpdateReferenceRequest $request, Invoice $invoice)
    {
        return $this->invoice_service->update_reference($request->validated());
    }
}
