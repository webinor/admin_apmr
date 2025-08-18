<?php

namespace App\Http\Controllers\Misc;


use App\Models\Misc\File;
use App\Http\Controllers\Controller;
use App\Http\Requests\Misc\StoreFileRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Http\Requests\Supplier\StoreSupplierLogoRequest;
use App\Services\UploadService;

class FileController extends Controller
{
   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Misc\StoreFileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFileRequest $request, UploadService $file_service)
    {

      $response =  $file_service->createFile($request->validated());

      return $response;
    }
    public function store_supplier_logo(StoreSupplierLogoRequest $request, UploadService $file_service)
    {

      $response =  $file_service->createFile($request->validated());

      return $response;
    }

   

   

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Misc\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
       // return $file;
        $result =  $file->delete();
  
        if ($result) {
         return [
             'status'=>true,
            // 'data'=>$data,
             'success'=> ["deleting" => ["Suppression effectuee avec succes"]]
         ];
        }
 
        return [
         'status'=>false,
         'errors'=> ["deleting" => ["Erreur survenue lors de la suppression"]]
     ];
    }
}
