<?php

namespace App\Http\Controllers\Prestations;

use App\Models\Services\Medicine;
use App\Http\Controllers\Controller;
use App\Http\Requests\Prestations\StoreMedicineRequest;
use App\Http\Requests\UpdateMedicineRequest;
use App\Services\Prestations\MedicineService;

class MedicineController extends Controller
{

    protected  $medicine_service;

    public function __construct(MedicineService $medicine_service) {
        
        $this->medicine_service = $medicine_service;

        //$this->authorizeResource(ProductCost::class, "product");

        }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $variables = $this->medicine_service->getIndexVariables();
        

        $view = $this->medicine_service->getView("active_ingredients.index", $variables);

        return $view;
    }

    public function getDci()
    {
        return $this->medicine_service->getDci();
    }


    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $variables = $this->medicine_service->getCreateVariables();

        $view = $this->medicine_service->getView("active_ingredients.manage", $variables);

        return $view;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMedicineRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMedicineRequest $request)
    {
        
        return $this->medicine_service->storeMedicine($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Services\Medicine  $medicine
     * @return \Illuminate\Http\Response
     */
    public function show(Medicine $medicine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Services\Medicine  $medicine
     * @return \Illuminate\Http\Response
     */
    public function edit(Medicine $medicine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMedicineRequest  $request
     * @param  \App\Models\Services\Medicine  $medicine
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMedicineRequest $request, Medicine $medicine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Services\Medicine  $medicine
     * @return \Illuminate\Http\Response
     */
    public function destroy(Medicine $medicine)
    {
        //
    }
}
