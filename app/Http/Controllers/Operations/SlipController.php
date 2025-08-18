<?php

namespace App\Http\Controllers\Operations;

use App\Models\Operations\Slip;
use App\Http\Controllers\Controller;
use App\Http\Requests\Misc\FetchSlipsRequest;
use App\Http\Requests\Operations\FilterRequest;
use App\Http\Requests\Operations\StoreSlipRequest;
use App\Http\Requests\UpdateSlipRequest;
use App\Services\Operations\SlipService;
use Illuminate\Http\Request;

class SlipController extends Controller
{

    protected  $slip_service;

    public function __construct(SlipService $slip_service) {
        
        $this->slip_service = $slip_service;
          
        $this->authorizeResource(Slip::class, "slip");

        }

    public function index(Request $request)  {


      $results = $request->results ? (int)$request->results : 10;
      $display = $request->display ? $request->display : "unprocessed";


     //  dd($results);

    

        if ($request->qry) {

            $query = $request->qry ? $request->qry : "";
    
          //  dd($query);
     
            $index_variables = $this->slip_service->searchSlips($query , $display );
    
        } else {
        
        $index_variables = $this->slip_service->getIndexVariables($results , $display);
       
        }
      //  dd($index_variables);

        return  $this->slip_service->getView('slip.index', $index_variables);

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
     * @param  \App\Http\Requests\Operations\StoreSlipRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSlipRequest $request)
    {
        return $this->slip_service->createSlip($request->validated());
        
    }

    public function getSlips(FetchSlipsRequest $request)
    {
        return $this->slip_service->getSlips($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Slip  $slip 
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request , Slip $slip)
    {

        //  dd("ok");
        
        $results = $request->results ? (int)$request->results : 10;
        
      //  dd($results);

      if ($request->qry) {

        $query = $request->qry ? $request->qry : "";
        

      //  dd($query);
 
        $variables = $this->slip_service->searchInvoices($slip , $query);

    } else {

        
        $variables = $this->slip_service->getShowVariables($slip , $results , $request);
    }
    
    //  dd($variables);
        return  $this->slip_service->getView('extract.index', $variables);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Slip  $slip
     * @return \Illuminate\Http\Response
     */
    public function edit(Slip $slip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSlipRequest  $request
     * @param  \App\Models\Slip  $slip
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSlipRequest $request, Slip $slip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Slip  $slip
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slip $slip)
    {
        return $this->slip_service->deleteSlip($slip);
    }
}
