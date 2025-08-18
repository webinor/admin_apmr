<?php

namespace App\Http\Controllers\Operations;

use App\Models\Slip;
use App\Models\Extract;
use Illuminate\Http\Request;
use App\Services\ExtractService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Operations\StoreExtractRequest;
use App\Http\Requests\UpdateExtractRequest;
use App\Models\Operations\Folder;

class ExtractController extends Controller
{

    protected  $extract_service;

    public function __construct(ExtractService $extract_service) {
        
        $this->extract_service = $extract_service;
          
       // $this->authorizeResource(Folder::class, "extract");

        }


    public function index()  {
        
        $index_variables = $this->extract_service->getIndexVariables();
 
       
        return  $this->extract_service->getView('extract.index', $index_variables);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function extract_data()
    {

            return [];
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $create_variables = $this->extract_service->getCreateVariables();
       
        return  $this->extract_service->getView('extract.new_create', $create_variables);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Operations\StoreExtractRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExtractRequest $request)
    {
        $response =  $this->extract_service->createFile($request->validated());

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */


     public function show(Request $request, Folder $folder)  {

      //  dd($folder);

            
      $folder->load(['slip',"slip.provider:id,code,name"]);

    
    if ($request->qry) {
        
        $quer = $request->qry ? $request->qry : "";
        
        $new_folder = Folder::
        whereHas('invoices',function($query) use ($quer){
            $query->whereReference(substr($quer, 0, 8));
        })
        ->whereSlipId($folder -> slip -> id)
        ->first();
        
        //
        //  dd($new_folder);

      if (!$new_folder) {
        
        abort("403","Dossier introuvable");
      }
 
        $show_variables = $this->extract_service->getShowVariables($new_folder);

    } else {
    
    $show_variables = $this->extract_service->getShowVariables($folder);
   
    }

  

      //  $show_variables = $this->extract_service->getShowVariables($folder);
       
        return  $this->extract_service->getView('extract.show', $show_variables);

    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Extract  $extract
     * @return \Illuminate\Http\Response
     */
    public function edit(Extract $extract)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateExtractRequest  $request
     * @param  \App\Models\Extract  $extract
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateExtractRequest $request, Extract $extract)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Extract  $extract
     * @return \Illuminate\Http\Response
     */
    public function destroy(Extract $extract)
    {
        //
    }
}
