<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroundAgentRequest;
use App\Http\Requests\UpdateGroundAgentRequest;
use App\Models\GroundAgent;
use App\Services\Misc\GroundAgentService;
use Illuminate\Http\Request;

class GroundAgentController extends Controller
{
    protected $groundAgentService;

    public function __construct(GroundAgentService $groundAgentService) {
          
      $this->groundAgentService = $groundAgentService;
        
     // $this->authorizeResource(Company::class, "employee");
  
      }
      /**
       * Display a listing of the resource.
       *
       * @return \Illuminate\Http\Response
       */
      public function index(Request $request)  {
          
          $results = $request->results ? (int)$request->results : 10;
    
    
            $variables = $this->groundAgentService->getIndexVariables($results);
           
            return  $this->groundAgentService->getView('ground_agent.index', $variables);
    
        }
  
      /**
       * Show the form for creating a new resource.
       *
       * @return \Illuminate\Http\Response
       */
      public function create()
      {
          
          $variables = $this->groundAgentService->getCreateVariables();
           
          return  $this->groundAgentService->getView('ground_agent.manage', $variables);
      }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreGroundAgentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGroundAgentRequest $request)
    {
        $response =  $this->groundAgentService->create($request->validated());

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GroundAgent  $groundAgent
     * @return \Illuminate\Http\Response
     */
    public function show(GroundAgent $groundAgent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GroundAgent  $groundAgent
     * @return \Illuminate\Http\Response
     */
    public function edit(GroundAgent $groundAgent)
    {
        $variables = $this->groundAgentService->getEditVariables($groundAgent);
           
        return  $this->groundAgentService->getView('ground_agent.manage', $variables);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGroundAgentRequest  $request
     * @param  \App\Models\GroundAgent  $groundAgent
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGroundAgentRequest $request, GroundAgent $groundAgent)
    {
        $response =  $this->groundAgentService->update($request->validated() , $groundAgent);

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GroundAgent  $groundAgent
     * @return \Illuminate\Http\Response
     */
    public function destroy(GroundAgent $groundAgent)
    {
        //
    }
}
