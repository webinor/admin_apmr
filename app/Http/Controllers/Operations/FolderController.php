<?php

namespace App\Http\Controllers\Operations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Misc\FetchInvoiceDataRequest;
use App\Http\Requests\Misc\FetchInvoicesRequest;
use App\Http\Requests\Misc\UpdateIdentificationRequest;
use App\Http\Requests\Misc\UpdateReferenceRequest;
use App\Http\Requests\Operations\FilterRequest;
use App\Services\Misc\FolderService;
use App\Http\Requests\StoreFolderRequest;
use App\Http\Requests\Operations\UpdateFolderRequest;
use App\Models\Operations\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{

    protected  $folder_service;

    public function __construct(FolderService $folder_service) {
          $this->folder_service = $folder_service;
          
          //$this->authorizeResource(Folder::class, "folder");

        }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreFolderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFolderRequest $request)
    {
        //
    }


    public function count_filtered_results(Request $request)  {
        
        return $this->folder_service->count_filtered_results($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function fetch_folder( $folder_code)
    {
        $fold = Folder::whereCode($folder_code)->first();

        if (!$fold) {
            return null;
        }

         $folder = Folder::
         with(["user:id,code",
       "extraction_times"=>function($query) use ($fold) {
        $query->select('id','folder_id','duration')
        ->whereFolderId($fold->id)
        ->orderByDesc('id')
        ->take(1);
       },
       "insured:id,code,name,beneficiary",
       "provider:id,code,name,address,category_id",
       "provider.category:id,code,name,slug",
       "invoices:id,code,reference,index_page,service_id,folder_id",
       "invoices.service:id,code,name",
       "invoices.invoice_lines:id,code,invoice_id,description,quantity,price"
       ])
         ->whereCode($folder_code)
         ->first();

        // return $folder;

         return [
            "status" => true,
            "data"=>[
            "folder"=>$folder,
           // "loader"=>$request['loader_id']
           ]

            ];

       //  dd($folder);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function fetch_invoice_data(FetchInvoiceDataRequest $request)
    {
        return $this->folder_service->fetch_invoice_data($request->validated());
    }


    public function getInvoices(FetchInvoicesRequest $request)
    {
        return $this->folder_service->getInvoices($request->validated());
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFolderRequest  $request
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function save_invoices(UpdateFolderRequest $request, Folder $folder)
    {
        return $this->folder_service->save_invoices($request->validated());
    }

    public function validate_invoices(UpdateFolderRequest $request, Folder $folder)
    {
        return $this->folder_service->validate_invoices($request->validated());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Misc\UpdateIdentificationRequest  $request
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function update_identification(UpdateIdentificationRequest $request)
    {
        return $this->folder_service->update_identification($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Folder $folder)
    {
        return $this->folder_service->deleteFolder($folder);
    }
}
