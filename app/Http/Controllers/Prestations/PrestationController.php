<?php

namespace App\Http\Controllers\Prestations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Misc\FetchPrestationsRequest;
use App\Http\Requests\Misc\FetchProductsRequest;
use App\Models\Prestations\Prestation;
use App\Http\Requests\StorePrestationRequest;
use App\Http\Requests\UpdatePrestationRequest;
use App\Services\Prestations\PrestationService;

class PrestationController extends Controller
{
    protected  $prestation_service;

    public function __construct(PrestationService $prestation_service) {
        
        $this->prestation_service = $prestation_service;
          
        //$this->authorizeResource(ProductCost::class, "product");

        }


    public function index()  {
        
        $index_variables = $this->prestation_service->getIndexVariables();
       
        return  $this->prestation_service->getView('prestations.index', $index_variables);

    }


    public function getPrestations(FetchPrestationsRequest $request)
    {
        return $this->prestation_service->getPrestations($request->validated());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $index_variables = $this->prestation_service->getCreateVariables();
       
        return  $this->prestation_service->getView('prestations.manage', $index_variables);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePrestationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePrestationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prestations\Prestation  $prestation
     * @return \Illuminate\Http\Response
     */
    public function show(Prestation $prestation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prestations\Prestation  $prestation
     * @return \Illuminate\Http\Response
     */
    public function edit(Prestation $prestation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePrestationRequest  $request
     * @param  \App\Models\Prestations\Prestation  $prestation
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePrestationRequest $request, Prestation $prestation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prestations\Prestation  $prestation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prestation $prestation)
    {
        //
    }
}
