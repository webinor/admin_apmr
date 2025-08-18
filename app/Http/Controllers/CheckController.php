<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckRequest;
use App\Http\Requests\UpdateCheckRequest;
use App\Models\Misc\Check;
use App\Services\Misc\CheckService;
use Illuminate\Http\Request;

class CheckController extends Controller
{


    protected  $checkService;

    public function __construct(CheckService $checkService) {
        
        $this->checkService = $checkService;
          
      //  $this->authorizeResource(Check::class, "check");

        }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        


      $results = $request->results ? (int)$request->results : 10;
      $display = $request->display ? $request->display : "unprocessed";


     //  dd($results);

    

        if ($request->qry) {

            $query = $request->qry ? $request->qry : "";
    
          //  dd($query);
     
            $index_variables = $this->checkService->searchCheck($query , $display );
    
        } else {
        
        $index_variables = $this->checkService->getIndexVariables($results , $display);
       
        }
      //  dd($index_variables);

        return  $this->checkService->getView('check.index', $index_variables);
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
     * @param  \App\Http\Requests\StoreCheckRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCheckRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Misc\Check  $check
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request , $check)
    {
          //  dd("ok");
        
          $results = $request->results ? (int)$request->results : 10;
        
          //  dd($results);
    
          if ($request->qry) {
    
            $query = $request->qry ? $request->qry : "";
            
    
          //  dd($query);
     
            $variables = $this->checkService->searchInvoices($query);
    
        } else {
    
            
            $variables = $this->checkService->getShowVariables($results , $request);
        }
        
        //  dd($variables);
            return  $this->checkService->getView('check.index', $variables);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Misc\Check  $check
     * @return \Illuminate\Http\Response
     */
    public function edit(Check $check)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCheckRequest  $request
     * @param  \App\Models\Misc\Check  $check
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCheckRequest $request, Check $check)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Misc\Check  $check
     * @return \Illuminate\Http\Response
     */
    public function destroy(Check $check)
    {
        //
    }
}
