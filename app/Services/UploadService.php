<?php

namespace App\Services;

use Faker;
use Throwable;
use App\Models\Slip;
use App\Models\Folder;
use App\Models\Insured;
use App\Models\Service;
use App\Models\Provider;
use App\Models\User\User;
use Illuminate\Bus\Batch;
use Illuminate\Support\Str;
use App\Jobs\ExtractCaseJob;
use App\Models\Misc\Invoice;
use App\Models\Settings\Area;
use App\Models\Supplier\Order;
use App\Addons\DataConstructor;
use App\Events\FolderExtracted;
use App\Models\Misc\InvoiceLine;
use App\Addons\FileUploadHandler;
use App\Models\Supplier\Supplier;
use Illuminate\Support\Facades\DB;
use App\Addons\Misc\ViewsResponder;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Auth;
use App\Models\Transit\TransferOrder;
use Symfony\Component\Process\Process;
use App\Models\Transit\ImportDeclaration;
use App\Services\Misc\UpdloadFileService;
use App\Addons\Misc\EditVariablesResponder;
use App\Addons\Misc\ShowVariablesResponder;
use App\Addons\Misc\IndexVariablesResponder;
use App\Addons\Misc\CreateVariablesResponder;
use Symfony\Component\Process\Exception\ProcessFailedException;

class UploadService 

    {

    use DataConstructor , FileUploadHandler ;

   

        public function createFile($request)
        {

            
            try {
       
    
                $host = $_SERVER['HTTP_HOST'];
                $remote_address = $_SERVER['REMOTE_ADDR'];
                $server_address = $_SERVER['SERVER_ADDR'];
                $server_port = $_SERVER['SERVER_PORT'];
                $filesArray = [];
                $pathsArray = [];
                $upload_file_service = new UpdloadFileService();
                $user = Auth::guard("sanctum")->user();              

                $file_data = ["user_id" => $user->id];
     
                if (array_key_exists("files", $request)) {
                    $files = $request["files"];
    
                    foreach ($files as $file) {

                        $newfile = $upload_file_service->getFileInstance($file);
    //app/public/extracted_doc
                       $s3_path = $upload_file_service->execute(
                            $file,
                           storage_path("app/public/extracted_doc/"),
                            $newfile->path,
                            "extracted_doc",
                        );
    
                      
                       // $newfile->path = $s3_path;
                        $newfile->s3_path = $s3_path;
    
                        $newfile = $this->fillModel($file_data, $newfile);
    
                        array_push($filesArray, $newfile);
                      //  array_push($pathsArray, storage_path("public/extracted_doc/".$newfile->path));
                        array_push($pathsArray, ($s3_path));

                       
                    }


              
                } 


                
                    return [
                    "status" => true,
                    "data"=>[
                    "file"=>$newfile,
                    "slip"=>Str::upper(Str::random(8)),
                    "s3_path"=>$s3_path,
                    "host"=>$host,
                    "filesArray"=>$filesArray,
                    "remote-address"=>$remote_address,
                    "server_address"=>$server_address,
                    "server_port"=>$server_port
                   // "loader"=>$request['loader_id']
                    ]
                   // "output"=>($output),  
                    
                ]; 
            
            } catch (\Throwable $th) {
                DB::rollback();
    
                throw $th;
            }
        }
    

   
}
