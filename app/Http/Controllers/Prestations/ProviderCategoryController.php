<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProviderCategoryRequest;
use App\Http\Requests\UpdateProviderCategoryRequest;
use App\Models\ProviderCategory;

class ProviderCategoryController extends Controller
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
     * @param  \App\Http\Requests\StoreProviderCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProviderCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProviderCategory  $providerCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ProviderCategory $providerCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProviderCategory  $providerCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ProviderCategory $providerCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProviderCategoryRequest  $request
     * @param  \App\Models\ProviderCategory  $providerCategory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProviderCategoryRequest $request, ProviderCategory $providerCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProviderCategory  $providerCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProviderCategory $providerCategory)
    {
        //
    }
}
