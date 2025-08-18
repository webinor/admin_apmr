<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExamWeightRequest;
use App\Http\Requests\UpdateExamWeightRequest;
use App\Models\Operations\ExamWeight;

class ExamWeightController extends Controller
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
     * @param  \App\Http\Requests\StoreExamWeightRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExamWeightRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Operations\ExamWeight  $examWeight
     * @return \Illuminate\Http\Response
     */
    public function show(ExamWeight $examWeight)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Operations\ExamWeight  $examWeight
     * @return \Illuminate\Http\Response
     */
    public function edit(ExamWeight $examWeight)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateExamWeightRequest  $request
     * @param  \App\Models\Operations\ExamWeight  $examWeight
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateExamWeightRequest $request, ExamWeight $examWeight)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Operations\ExamWeight  $examWeight
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExamWeight $examWeight)
    {
        //
    }
}
