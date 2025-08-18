<?php

namespace App\Http\Controllers\Operations;

use App\Models\ServiceCost;
use App\Http\Controllers\Controller;
use App\Http\Requests\Prestations\StoreServiceCostRequest;
use App\Http\Requests\Prestations\UpdateServiceCostRequest;
use App\Services\Operations\ServiceCostService;

class ServiceCostController extends Controller
{
    
    protected  $service_cost_service;

    public function __construct(ServiceCostService $service_cost_service) {
        
        $this->service_cost_service = $service_cost_service;
          
        //$this->authorizeResource(ProductCost::class, "product");

        }



    public function index()  {
        
        $index_variables = $this->service_cost_service->getIndexVariables();
       
        return  $this->service_cost_service->getView('service_costs.index', $index_variables);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $index_variables = $this->service_cost_service->getCreateVariables();
       
        return  $this->service_cost_service->getView('service_costs.manage', $index_variables);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Prestations\StoreServiceCostRequest  $request
     * @return \Illuminate\Http\Response
     */ 
    public function store(StoreServiceCostRequest $request)
    {
        $response =  $this->service_cost_service->createServiceCost($request->validated());

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ServiceCost  $serviceCost
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceCost $serviceCost)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ServiceCost  $serviceCost
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceCost $serviceCost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateServiceCostRequest  $request
     * @param  \App\Models\ServiceCost  $serviceCost
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateServiceCostRequest $request, ServiceCost $serviceCost)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ServiceCost  $serviceCost
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceCost $serviceCost)
    {
        //
    }
}
