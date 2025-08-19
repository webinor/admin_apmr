<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegistratorRequest;
use App\Http\Requests\UpdateRegistratorRequest;
use App\Models\Registrator;

class RegistratorController extends Controller
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
     * @param  \App\Http\Requests\StoreRegistratorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRegistratorRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Registrator  $registrator
     * @return \Illuminate\Http\Response
     */
    public function show(Registrator $registrator)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Registrator  $registrator
     * @return \Illuminate\Http\Response
     */
    public function edit(Registrator $registrator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRegistratorRequest  $request
     * @param  \App\Models\Registrator  $registrator
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRegistratorRequest $request, Registrator $registrator)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Registrator  $registrator
     * @return \Illuminate\Http\Response
     */
    public function destroy(Registrator $registrator)
    {
        //
    }
}
