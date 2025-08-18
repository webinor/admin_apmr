<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssistanceAgentRequest;
use App\Http\Requests\UpdateAssistanceAgentRequest;
use App\Models\AssistanceAgent;
use App\Services\Misc\AssistanceAgentService;
use Illuminate\Http\Request;

class AssistanceAgentController extends Controller
{
    protected $assistance_agent_service;

    public function __construct(AssistanceAgentService $assistance_agent_service) {
          
      $this->assistance_agent_service = $assistance_agent_service;
        
     // $this->authorizeResource(Company::class, "employee");
  
      }
      /**
       * Display a listing of the resource.
       *
       * @return \Illuminate\Http\Response
       */
      public function index(Request $request)  {
          
          $results = $request->results ? (int)$request->results : 10;
    
    
            $variables = $this->assistance_agent_service->getIndexVariables($results);
           
            return  $this->assistance_agent_service->getView('assistance_agent.index', $variables);
    
        }
  
      /**
       * Show the form for creating a new resource.
       *
       * @return \Illuminate\Http\Response
       */
      public function create()
      {
          
          $variables = $this->assistance_agent_service->getCreateVariables();
           
          return  $this->assistance_agent_service->getView('assistance_agent.manage', $variables);
      }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAssistanceAgentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAssistanceAgentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AssistanceAgent  $assistanceAgent
     * @return \Illuminate\Http\Response
     */
    public function show(AssistanceAgent $assistanceAgent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AssistanceAgent  $assistanceAgent
     * @return \Illuminate\Http\Response
     */
    public function edit(AssistanceAgent $assistanceAgent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAssistanceAgentRequest  $request
     * @param  \App\Models\AssistanceAgent  $assistanceAgent
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAssistanceAgentRequest $request, AssistanceAgent $assistanceAgent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AssistanceAgent  $assistanceAgent
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssistanceAgent $assistanceAgent)
    {
        //
    }
}
