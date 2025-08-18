<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHealthRequest;
use App\Http\Requests\UpdateHealthRequest;
use App\Models\Misc\Health;

class HealthController extends Controller
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
     * @param  \App\Http\Requests\StoreHealthRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreHealthRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Misc\Health  $health
     * @return \Illuminate\Http\Response
     */
    public function show(Health $health)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Misc\Health  $health
     * @return \Illuminate\Http\Response
     */
    public function edit(Health $health)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateHealthRequest  $request
     * @param  \App\Models\Misc\Health  $health
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateHealthRequest $request, Health $health)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Misc\Health  $health
     * @return \Illuminate\Http\Response
     */
    public function destroy(Health $health)
    {
        //
    }
}
