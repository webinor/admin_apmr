<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdjustmentRequest;
use App\Http\Requests\UpdateAdjustmentRequest;
use App\Models\Adjustment;

class AdjustmentController extends Controller
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
     * @param  \App\Http\Requests\StoreAdjustmentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAdjustmentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Adjustment  $adjustment
     * @return \Illuminate\Http\Response
     */
    public function show(Adjustment $adjustment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Adjustment  $adjustment
     * @return \Illuminate\Http\Response
     */
    public function edit(Adjustment $adjustment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAdjustmentRequest  $request
     * @param  \App\Models\Adjustment  $adjustment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdjustmentRequest $request, Adjustment $adjustment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Adjustment  $adjustment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Adjustment $adjustment)
    {
        //
    }
}
