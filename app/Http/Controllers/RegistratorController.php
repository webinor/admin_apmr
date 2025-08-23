<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegistratorRequest;
use App\Http\Requests\UpdateRegistratorRequest;
use App\Models\Registrator;
use App\Services\Misc\RegistratorService;
use Illuminate\Http\Request;

class RegistratorController extends Controller
{

    protected $registratorService;

    public function __construct(RegistratorService $registratorService) {
          
      $this->registratorService = $registratorService;
        
     // $this->authorizeResource(Company::class, "employee");
  
      }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)  
        {
       
        $results = $request->results ? (int)$request->results : 20;
    
    
        $variables = $this->registratorService->getIndexVariables($results);
       
        return  $this->registratorService->getView('registrator.index', $variables);

    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function create()
    {
        $variables = $this->registratorService->getCreateVariables();
           
        return  $this->registratorService->getView('registrator.manage', $variables);
    }
   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRegistratorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRegistratorRequest $request)
    {
        $response =  $this->registratorService->create($request->validated() , $request);

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Registrator  $registrator
     * @return \Illuminate\Http\Response
     */
    public function show(Registrator $registrator)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Registrator  $registrator
     * @return \Illuminate\Http\Response
     */
    public function edit(Registrator $registrator)
    {
        $variables = $this->registratorService->getEditVariables($registrator);
           
        return  $this->registratorService->getView('registrator.manage', $variables);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRegistratorRequest  $request
     * @param  \App\Models\Registrator  $registrator
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRegistratorRequest $request, Registrator $registrator)
    {
        $response =  $this->registratorService->update($request->validated() , $registrator , $request );

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Registrator  $registrator
     * @return \Illuminate\Http\Response
     */
    public function destroy(Registrator $registrator)
    {
        //
    }
}
