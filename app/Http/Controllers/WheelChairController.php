<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWheelChairRequest;
use App\Http\Requests\UpdateWheelChairRequest;
use App\Models\WheelChair;
use App\Services\Misc\WheelChairService;
use Illuminate\Http\Request;

class WheelChairController extends Controller
{
    protected $wheelChairService;

    public function __construct(WheelChairService $wheelChairService) {
          
      $this->wheelChairService = $wheelChairService;
        
     // $this->authorizeResource(Company::class, "employee");
  
      }
      /**
       * Display a listing of the resource.
       *
       * @return \Illuminate\Http\Response
       */
      public function index(Request $request)  {
          
          $results = $request->results ? (int)$request->results : 10;
    
    
            $variables = $this->wheelChairService->getIndexVariables();
           
            return  $this->wheelChairService->getView('wheel_chair.index', $variables);
    
        }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $variables = $this->wheelChairService->getCreateVariables();
           
        return  $this->wheelChairService->getView('wheel_chair.manage', $variables);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreWheelChairRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWheelChairRequest $request)
    {
        $response =  $this->wheelChairService->createWheelChair($request->validated());

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WheelChair  $wheelChair
     * @return \Illuminate\Http\Response
     */
    public function show(WheelChair $wheelChair)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WheelChair  $wheelChair
     * @return \Illuminate\Http\Response
     */
    public function edit(WheelChair $wheelChair)
    {
        $variables = $this->wheelChairService->getEditVariables($wheelChair);
           
        return  $this->wheelChairService->getView('wheel_chair.manage', $variables);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateWheelChairRequest  $request
     * @param  \App\Models\WheelChair  $wheelChair
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWheelChairRequest $request, WheelChair $wheelChair)
    {
        $response =  $this->wheelChairService->updateWheelChair($request->validated() , $wheelChair);

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WheelChair  $wheelChair
     * @return \Illuminate\Http\Response
     */
    public function destroy(WheelChair $wheelChair)
    {
        //
    }
}
