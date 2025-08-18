<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProviderTypeRequest;
use App\Http\Requests\UpdateProviderTypeRequest;
use App\Models\ProviderType;

class ProviderTypeController extends Controller
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
     * @param  \App\Http\Requests\StoreProviderTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProviderTypeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProviderType  $providerType
     * @return \Illuminate\Http\Response
     */
    public function show(ProviderType $providerType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProviderType  $providerType
     * @return \Illuminate\Http\Response
     */
    public function edit(ProviderType $providerType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProviderTypeRequest  $request
     * @param  \App\Models\ProviderType  $providerType
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProviderTypeRequest $request, ProviderType $providerType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProviderType  $providerType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProviderType $providerType)
    {
        //
    }
}
