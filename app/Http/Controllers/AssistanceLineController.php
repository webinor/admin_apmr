<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssistanceLineRequest;
use App\Http\Requests\UpdateAssistanceLineRequest;
use App\Models\AssistanceLine;

class AssistanceLineController extends Controller
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
     * @param  \App\Http\Requests\StoreAssistanceLineRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAssistanceLineRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AssistanceLine  $assistanceLine
     * @return \Illuminate\Http\Response
     */
    public function show(AssistanceLine $assistanceLine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AssistanceLine  $assistanceLine
     * @return \Illuminate\Http\Response
     */
    public function edit(AssistanceLine $assistanceLine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAssistanceLineRequest  $request
     * @param  \App\Models\AssistanceLine  $assistanceLine
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAssistanceLineRequest $request, AssistanceLine $assistanceLine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AssistanceLine  $assistanceLine
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssistanceLine $assistanceLine)
    {
        //
    }
}
