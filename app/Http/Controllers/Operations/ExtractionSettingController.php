<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExtractionSettingRequest;
use App\Http\Requests\UpdateExtractionSettingRequest;
use App\Models\ExtractionSetting;
use App\Services\ExtractionSettingService;

class ExtractionSettingController extends Controller
{

    protected  $extraction_setting_service;

    public function __construct(ExtractionSettingService $extraction_setting_service) {
          $this->extraction_setting_service = $extraction_setting_service;
          
         // $this->authorizeResource(Supplier::class , 'supplier');
    }
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
     * @param  \App\Http\Requests\StoreExtractionSettingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExtractionSettingRequest $request)
    {
        $response =  $this->extraction_setting_service->createExtractionSettings($request->validated());

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExtractionSetting  $extractionSetting
     * @return \Illuminate\Http\Response
     */
    public function show(ExtractionSetting $extractionSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExtractionSetting  $extractionSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(ExtractionSetting $extractionSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateExtractionSettingRequest  $request
     * @param  \App\Models\ExtractionSetting  $extractionSetting
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateExtractionSettingRequest $request, ExtractionSetting $extractionSetting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExtractionSetting  $extractionSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExtractionSetting $extractionSetting)
    {
        //
    }
}
