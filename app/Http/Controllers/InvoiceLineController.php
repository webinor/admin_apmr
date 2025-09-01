<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceLineRequest;
use App\Http\Requests\UpdateInvoiceLineRequest;
use App\Models\InvoiceLine;

class InvoiceLineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreInvoiceLineRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInvoiceLineRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InvoiceLine  $invoiceLine
     * @return \Illuminate\Http\Response
     */
    public function show(InvoiceLine $invoiceLine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InvoiceLine  $invoiceLine
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoiceLine $invoiceLine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateInvoiceLineRequest  $request
     * @param  \App\Models\InvoiceLine  $invoiceLine
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInvoiceLineRequest $request, InvoiceLine $invoiceLine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InvoiceLine  $invoiceLine
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvoiceLine $invoiceLine)
    {
        //
    }
}
