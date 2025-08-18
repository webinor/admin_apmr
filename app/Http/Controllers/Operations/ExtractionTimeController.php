<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExtractionTimeRequest;
use App\Http\Requests\UpdateExtractionTimeRequest;
use App\Models\ExtractionTime;

class ExtractionTimeController extends Controller
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
     * @param  \App\Http\Requests\StoreExtractionTimeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExtractionTimeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExtractionTime  $extractionTime
     * @return \Illuminate\Http\Response
     */
    public function show(ExtractionTime $extractionTime)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExtractionTime  $extractionTime
     * @return \Illuminate\Http\Response
     */
    public function edit(ExtractionTime $extractionTime)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateExtractionTimeRequest  $request
     * @param  \App\Models\ExtractionTime  $extractionTime
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateExtractionTimeRequest $request, ExtractionTime $extractionTime)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExtractionTime  $extractionTime
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExtractionTime $extractionTime)
    {
        //
    }
}
