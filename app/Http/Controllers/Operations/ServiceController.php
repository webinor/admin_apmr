<?php

namespace App\Http\Controllers\Operations;

use App\Models\Prestations\Service;
use App\Http\Controllers\Controller;
use App\Http\Requests\Misc\FetchServicesRequest;
use App\Http\Requests\Prestations\StoreServiceRequest;
use App\Http\Requests\Prestations\UpdateServiceRequest;
use App\Services\Operations\ServiceService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected  $service_service;

    public function __construct(ServiceService $service_service) {
        
        $this->service_service = $service_service;
          
        $this->authorizeResource(Service::class, "service");

        }



        public function index(Request $request)  {

            $results = $request->results ? (int)$request->results : 10;
            
            //  dd($results);
      
            if ($request->qry && $request->qry != "") {
      
              $query = $request->qry ? $request->qry : "";
                 
              $index_variables = $this->service_service->searchServices($query);
      
          }
    
          else{
    
            $index_variables = $this->service_service->getIndexVariables($results);    
          }
        
            
           
            return  $this->service_service->getView('services.index', $index_variables);
    
        }


        public function GetServices(FetchServicesRequest $request)
        {
            return $this->service_service->GetServices($request->validated());
        }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $create_variables = $this->service_service->getCreateVariables($request);
       
        return  $this->service_service->getView('services.manage', $create_variables);
    }

    public function getServiceNames()
    {
        return $this->service_service->getServiceNames();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreServiceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreServiceRequest $request)
    {
        $response =  $this->service_service->createService($request->validated());

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prestations\Services  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        $show_variables = $this->service_service->getShowVariables($service);    
 
      return  $this->service_service->getView('services.manage', $show_variables);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prestations\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        $edit_variables = $this->service_service->getEditVariables($service);    
 
        return  $this->service_service->getView('services.manage', $edit_variables);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateServiceRequest  $request
     * @param  \App\Models\Prestations\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        $response =  $this->service_service->updateService($request->validated());

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prestations\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        $response =  $this->service_service->deleteService($service);

        return $response;
    }
}
