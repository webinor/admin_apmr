<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePrestationTypeRequest;
use App\Http\Requests\UpdatePrestationTypeRequest;
use App\Models\Prestations\PrestationType;

class PrestationTypeController extends Controller
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
     * @param  \App\Http\Requests\StorePrestationTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePrestationTypeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prestations\PrestationType  $prestationType
     * @return \Illuminate\Http\Response
     */
    public function show(PrestationType $prestationType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prestations\PrestationType  $prestationType
     * @return \Illuminate\Http\Response
     */
    public function edit(PrestationType $prestationType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePrestationTypeRequest  $request
     * @param  \App\Models\Prestations\PrestationType  $prestationType
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePrestationTypeRequest $request, PrestationType $prestationType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prestations\PrestationType  $prestationType
     * @return \Illuminate\Http\Response
     */
    public function destroy(PrestationType $prestationType)
    {
        //
    }
}
