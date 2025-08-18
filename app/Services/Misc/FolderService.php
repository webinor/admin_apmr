<?php

namespace App\Services\Misc;


use GuzzleHttp\Client;
use App\Models\Misc\Seen;
use Illuminate\Support\Str;
use App\Models\Misc\Invoice;
use Illuminate\Http\Request;
use App\Addons\DataConstructor;
use App\Models\Operations\Slip;
use App\Models\Misc\InvoiceLine;
use Illuminate\Http\Client\Pool;
use App\Addons\FileUploadHandler;
use App\Models\Misc\RemoteFileInserted;
use App\Models\Operations\Folder;
use Illuminate\Support\Facades\DB;
use App\Models\Misc\RemoteInserted;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Operations\Validation;
use App\Models\Prestations\ProductType;
use App\Models\Prestations\ServiceType;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class FolderService 
{
    use DataConstructor , FileUploadHandler  ;


    public function fetch_invoice_data($folder_data){


        try {
      
        DB::beginTransaction();
       

    

        $folder = Folder::select("id","slip_id","code")->
        with(["slip:id,code,provider_id",
        "slip.provider:id,code,name,provider_category_id",
        "slip.provider.provider_category:id,code,name"])
        ->whereCode($folder_data['folder'])->first();


        $service = $folder_data['service'];

      //  $prestation_type = $folder->slip->provider->get_category() == "pharmacie" ? get_class(ProductType::whereFullname($service)->first()) : get_class(ServiceType::whereFullname($service)->first()) ;


      //  return $folder->get_all_prestations(); 

      if ($folder_data['action']=='delete') {
     
        $invoice_line=InvoiceLine::whereCode($folder_data['invoice_line'])
       ->with('invoice')->first();

       $invoice = $invoice_line->invoice;
       
       $invoice_line->delete();

       if ($invoice->invoice_lines()->get()->count() == 0) {
        
        $invoice->delete();
        
       };

      } else if($folder_data['action']=='add') {
     
        $should_delete_previous_invoice=$folder_data["should_delete_previous_invoice"];
        $should_delete_previous_invoice_line =$folder_data["should_delete_previous_invoice_line"];

        if (!array_key_exists($service,$folder->get_all_prestations())) {//////////we create the new invoice
            

           $invoice = $this->create_invoice($folder,$service);

            
        }else{

        


        if ($folder->slip->provider->get_category() == "pharmacie" ) {


            $product_type = ProductType::whereFullname($service)->first();
            $invoice = Invoice::whereFolderId($folder->id)
            ->wherePrestationableType(get_class($product_type))
            ->wherePrestationableId($product_type->id)
            ->first();


        } else {


            $service_type = ServiceType::whereFullname($service)->first();
              $invoice = Invoice::whereFolderId($folder->id)
            ->wherePrestationableType(get_class($service_type))
            ->wherePrestationableId($service_type->id)
            ->first();

        }


    }


    if ($should_delete_previous_invoice) {
        

          $previous_invoice = Invoice::whereCode($folder_data['invoice'])
        ->first();
       // ->delete();

       if ($previous_invoice) {
        $previous_invoice->delete();
       }
        
    } 
    
    if ($should_delete_previous_invoice_line) {
        

        $previous_invoice_line = InvoiceLine::whereCode($folder_data['invoice_line'])
       ->first();
       
       if ($previous_invoice_line) {
        $previous_invoice_line->delete();
       }
        
    }
    
    $invoice_line = $this->create_invoice_line($invoice,$folder_data);
   

}
elseif($folder_data['action']=='update'){

    

    
    $invoice_line = $this->update_invoice_line($folder_data);
    
    $invoice = $invoice_line->invoice;

    


}


        DB::commit();

       return [
        "status" => true,
        "data" => [
            "invoice" => $invoice,
            "invoice_line" => $invoice_line,
            "prestation_type" =>$invoice_line->invoice->prestationable->get_prestationable_type(),// $prestation_type,
            "prestation" =>$service,
            "prestation_code"=>$invoice->prestationable->get_code(),
            "provider_category"=>$invoice_line->invoice->folder->slip->provider->provider_category->get_name()
        ],
    ];


} catch (\Throwable $th) {
    DB::rollback();
    throw $th;
}

    }


    public function create_invoice($folder , $prestation)  {
        

        //   foreach ($prestations as $prestation) {
               $invoice_code = Str::random(10);
               $array_items = [];
   
               ///////////////////////////end folder
             //  //echo "\n----------p----------".(json_encode($prestation))."-------------p-----------\n\n";
   
               // //echo
              // $invoice_reference = $prestation["reference"]; //!= "UNDEFINED REFERENCE" ? $prestation['reference'] : "";
               $invoice_string ="";// $prestation["invoice_string"]; 
   
               $reference = "REFERENCE INTROUVABLE";//$this->extract_reference($invoice_string )["reference"];
              // $reference_matched = $this->extract_reference($invoice_string )["reference_matched"];
              
   
              // //echo "refenrence == $reference \n";
               
   
               $invoice = new Invoice();
               $invoice->code = $invoice_code;
               $invoice->reference = $reference;
               $invoice->items_string = $invoice_string;
               $invoice->folder_id = $folder->id;
               $invoice->index_page = 1;
   
               //  //echo $this->provider->get_category() == "pharmacie" || $prestation['name'] == "pharmacie" ;
   
             //  //echo "prestation == " . $prestation . " \n\n";
   
               // if (true) {
               if (
                   $folder->slip->provider->get_category() == "pharmacie" ||
                   $prestation == "PHARMACIE"
               ) {
                   $invoice->prestationable_type = ProductType::class;
                   $product_type = ProductType::whereFullname(
                       Str::upper($prestation)
                   )->first();
                   $invoice->prestationable_id = $product_type
                       ? $product_type->id
                       : 0;
               } else {
                   $invoice->prestationable_type = ServiceType::class;
   
                   //echo "provider == " .
                       $folder->slip->provider->get_category() .
                       " \n\n";
                   //echo "prestation == " .
                       Str::upper($prestation) .
                       " \n\n";
   
                   $service_type = ServiceType::whereFullname(
                       Str::upper($prestation)
                   )->first();
   
                   $invoice->prestationable_id = $service_type
                       ? $service_type->id
                       : 0;
               }
   
               $invoice->save();
   
         
   
   
            return $invoice;
   
   
       }



       public function create_invoice_line($invoice , $folder_data )  {
        
            $invoice_line = new InvoiceLine();
            $invoice_line->code = Str::random(10);
            $invoice_line->description = $folder_data["description"];
            $invoice_line->quantity = $folder_data["quantity"];
            $invoice_line->price = $folder_data["price"];
   
            $invoice->invoice_lines()->save($invoice_line);

            return $invoice_line;
   
   
            
   
       }

       public function update_invoice_line( $folder_data )  {
        
     
        $invoice_line = InvoiceLine::
        with('invoice')
        ->whereCode($folder_data['invoice_line'])
        ->first();

        $invoice_line->description = $folder_data["description"];
        $invoice_line->quantity = $folder_data["quantity"];
        $invoice_line->price = $folder_data["price"];

        $invoice_line->save();

        return $invoice_line;


        

   }

    public function fetch_folder_data($folder_data){

        $folder = Folder::select("id","slip_id","code")->
        with(["slip:id,code,provider_id",
        "slip.provider:id,code,name,provider_category_id",
        "slip.provider.provider_category:id,code,name"])
        ->whereCode($folder_data['folder'])->first();

        
        if ($folder->slip->provider->get_category() == "pharmacie" ) {
            
          
            $product_type = ProductType::whereFullname($folder_data['service'])->first();
            $invoice = Invoice::whereFolderId($folder->id)
            ->wherePrestationableType(get_class($product_type))
            ->wherePrestationableId($product_type->id)
            ->first();


        } else {
           

            $service_type = ServiceType::whereFullname($folder_data['service'])->first();
              $invoice = Invoice::whereFolderId($folder->id)
            ->wherePrestationableType(get_class($service_type))
            ->wherePrestationableId($service_type->id)
            ->first();

        }


       


       return [
        "status" => true,
        "data" => [
            "invoice" => $invoice,
        ],
    ];

    }

    public function constructRemote($folder , $invoices){


        try {//lorsque la PEC existe deja : {"success":false,"resultId":-4,"message":"Problème lors de la création de la facture (P0001). Facture créé sans prises en charges"}
            


           


            $bodies = [];

            $not_already_create_in_remote = 0;

            
            foreach ($invoices as $inv_code => $lines) {


               // if (condition) {
                    # code...
                


                 $rows = [];

                $final_amount = 0;
               


                $invoice_lines = [];

              //return $lines;

                $invoice_code = $inv_code;

                $current_invoice = Invoice::whereCode($invoice_code)
                ->doesntHave('remote_inserted')
                ->first();


              //  dd($invoices);

              if ($current_invoice) {
               
              
              
                

             //InvoiceLine::where('invoice_id', $current_invoice->id)->delete();
                
                foreach ($lines as $line) {

                

                array_push($rows  ,  [
                    "refPec"=> $current_invoice->reference,
                    "amount"=> $line["price"],
                    "details"=> [
                      [
                        "qty"=> $line["quantity"],
                        "unitPrice"=> $line["price"],
                        "exclusion"=> 0,
                        "reference"=> $current_invoice->reference,
                        ///"comment"=> "string",
                        "label"=> $line["description"],
                       // "unitPriceRegMed"=> 0,
                       // "qtyRegMed"=> 0,
                       // "type"=> 0,
                       // "specialRefund"=> true,
                        "ligneId"=> 0
                      ]
                    ],
                    "affection"=> 0,
                    "origine"=> 0,
                    "affectionAssocCodes"=> [
                      "string"
                    ]
                  ] );


                $final_amount+=(int)$line["price"];

                
            }


            array_push($bodies  , [
                "amount"=> $final_amount,
                "ticket"=> 0,
                "ref"=> $current_invoice->reference,
                "rows"=> $rows,
                "exclusion"=> 0,
                "regmedValidation"=> true,
                "regMedUserName"=> "string",
                "motifExclusion"=> "string"
            ]);


            $not_already_create_in_remote ++;

            }
            else{

               

            }

           

         //   }
            
            
            
        }

      //  dd($bodies);

        
        return ["bodies"=>$bodies , "not_already_create_in_remote"=> $not_already_create_in_remote];



        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function constructRemoteValidate($invoices_codes){


        try {//lorsque la PEC existe deja : {"success":false,"resultId":-4,"message":"Problème lors de la création de la facture (P0001). Facture créé sans prises en charges"}
            


            $bodies = [];

            $not_already_validated_in_remote = 0;

         

            
            foreach ($invoices_codes as $inv_code => $invoice_code) {


               // if (condition) {
                    # code...
                


                 $rows = [];

                $final_amount = 0;
               


                $invoice_lines = [];

              //return $lines;

             //   $invoice_code = $inv_code;

                $current_invoice = Invoice::whereCode($invoices_codes)
                ->whereDoesntHave('remote_inserted',function ($query)  {
                    
                    $query->whereIsValidatedInRemote(true);

                })
                ->with('remote_inserted')
                ->with('invoice_lines.validation')
                ->first();


              //  dd($invoices);

              if ($current_invoice) {
      
             //InvoiceLine::where('invoice_id', $current_invoice->id)->delete();
                
                foreach ($current_invoice->invoice_lines as $line) {

                

                array_push($rows  ,  [
                    "refPec"=> $current_invoice->reference,
                    "amount"=> $line["price"],
                    "details"=> [
                      [
                        "qty"=> $line->validation->suggested_quantity,
                        "unitPrice"=> $line->validation->suggested_price,
                        "exclusion"=> 0,
                        "reference"=> $current_invoice->reference,
                       // "comment"=> $validated->comment,
                        "label"=> $line->description,
                     //   "unitPriceRegMed"=> $validation->validated_quantity,
                       // "qtyRegMed"=> 0,
                       // "type"=> 0,
                       // "specialRefund"=> true,
                       // "ligneId"=> 0
                      ]
                    ],
                    "affection"=> 0,
                    "origine"=> 0,
                    "affectionAssocCodes"=> [
                      "string"
                    ]
                  ] );


                $final_amount+=(int)$line->validation->suggested_price;

                
            }


            array_push($bodies  , [
                "amount"=> $final_amount,
                "ticket"=> 0,
                "ref"=> $current_invoice->reference,
                "rows"=> $rows,
                "exclusion"=> 0,
                "regmedValidation"=> true,
                "regMedUserName"=> "MAR1",
                "motifExclusion"=> "string"
            ]);


            $not_already_validated_in_remote ++;

            }
            else{

               

            }

           

         //   }
            
            
            
        }

      //  dd($bodies);

        
        return ["bodies"=>$bodies , "not_already_validated_in_remote"=> $not_already_validated_in_remote];



        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function insertRemote($bodies){


                        try {//lorsque la PEC existe deja : {"success":false,"resultId":-4,"message":"Problème lors de la création de la facture (P0001). Facture créé sans prises en charges"}
                            


                        //  dd($bodies);

                            $cookie_name = ".ASPXAUTH";
                            $cookie_value = decrypt(session($cookie_name));
                            $apiToken =  decrypt(session("accessToken"));

                            $url = 'https://pass24api.insighttelematics.tn/pass24/CreateProjetFacture';
                            $headers = ['Accept' => 'text/plain','Content-Type'=>'application/json',
                            "Authorization" => "Bearer $apiToken",
                            'Cookie' => "$cookie_name=$cookie_value",];




                        
                            $responses = Http::pool(function (Pool $pool) use ($url, $headers, $bodies) {
                                $requests = [];
                            
                                foreach ($bodies as $key => $body) {
                                    $requests[] = $pool->as($key)
                                        ->withHeaders($headers)
                                        ->post($url, $body); // tu peux changer en PUT, PATCH si besoin
                                }
                            
                                return $requests;
                            });


                            /*

                            foreach ($responses as $key => $response) {
                                if ($response->successful()) {
                                    echo "$key: OK - " . json_encode($response->json()) . "\n";
                                } else {
                                    echo "$key: ERREUR - " . $response->status() . "\n";
                                }
                            }

                        /**/

                        // Tableau pour stocker les résultats
                        $all_request_successful = true;
                        $results = [
                            'succeeded' => [],
                            'files_url'=>[],
                            'failed' => [],
                            'should_login'=>false,
                            'all_request_successful'=> true
                        // 'target'=>url()->$request->fullUrl()
                        ];

                foreach ($responses as $key => $response) {
                    $bodySent = $bodies[$key] ?? null;

                    if ($response->successful()) {
                        $results['succeeded'][] = [
                            'key' => $key,
                            'status' => $response->status(),
                            'body_sent' => $bodySent,
                            'body_received' => $response->json(),
                        ];


                    // dd($response->json()["success"]==false);


                        if($response->json()["success"]){///on sauvegarde la facture qui a reussi

                            $invoice =  Invoice::whereReference($bodySent["ref"])
                            ->with('folder')->first() ;

                            $remote_invoice_id = $response->json()["resultId"] ;
                            $succeed = new RemoteInserted();
                            $succeed->invoice_id = $invoice->id;
                            $succeed->remote_invoice_id = $remote_invoice_id;
                            $succeed->save();

                        //  dd($succeed);

                        /**/$response_import = $_SERVER['SERVER_ADDR'] == "127.0.0.1" ?  $this->export_file_to_erp_from_disk($invoice) : $this->export_file_to_erp_from_s3($invoice);

                        if (filter_var($response_import, FILTER_VALIDATE_URL)) {
                            
                            $remote_file = new RemoteFileInserted();
                            $remote_file->invoice_id = $invoice->id;
                            $remote_file->remote_invoice_id = $remote_invoice_id;
                            $remote_file->url = $response_import;
                            $remote_file->save();

                            array_push($results["files_url"] , $response_import);

                        }else{

                           // dd($response_import);   
                           // $succeed->delete();

                        }
                    

                        }
                        else{// on detacte si une facture a echoue

                            $all_request_successful = false;



                            

                        }

                        if (array_key_exists('message', $response->json()) && $response->json()["message"] ==="account.login.notauthenticated") {
                            //$should_login = true;

                            Auth::logout();
                            session()->invalidate();
                            session()->regenerateToken();

                            $results['should_login']=true;
                        }




                        

                    } else {
                        $all_request_successful = false;

                        $results['failed'][] = [
                            'key' => $key,
                            'status' => $response->status(),
                            'body_sent' => $bodySent,
                            'error' => $response->body(), // ou ->json() si c’est du JSON
                        ];
                    }
                }
                    

                        //  dd($results);
                $results['all_request_successful']=$all_request_successful;
                        
                                return $results;

                        
                        
                        } catch (\Throwable $th) {
                            throw $th;
                        }

    }


    public function validateRemote($bodies){


        try {//lorsque la PEC existe deja : {"success":false,"resultId":-4,"message":"Problème lors de la création de la facture (P0001). Facture créé sans prises en charges"}
            


            
            $cookie_name = ".ASPXAUTH";
            $cookie_value = decrypt(session($cookie_name));


            $url = 'https://pass24api.insighttelematics.tn/pass24/UpdateProjetFacture';
            $headers = ['Accept' => 'text/plain','Content-Type'=>'application/json',
        'Cookie' => "$cookie_name=$cookie_value"];




           
            $responses = Http::pool(function (Pool $pool) use ($url, $headers, $bodies) {
                $requests = [];
            
                foreach ($bodies as $key => $body) {
                    
                    $requests[] = $pool->as($key)
                        ->withHeaders($headers)
                        ->post($url, $body); // tu peux changer en PUT, PATCH si besoin
                }
            
                return $requests;
            });


            /*

            foreach ($responses as $key => $response) {
                if ($response->successful()) {
                    echo "$key: OK - " . json_encode($response->json()) . "\n";
                } else {
                    echo "$key: ERREUR - " . $response->status() . "\n";
                }
            }

           /**/

           // Tableau pour stocker les résultats
           $all_request_successful = true;
        $results = [
            'succeeded' => [],
            'files_url'=>[],
            'failed' => [],
            'should_login'=>false,
            'all_request_successful'=> true
        // 'target'=>url()->$request->fullUrl()
        ];

foreach ($responses as $key => $response) {
    $bodySent = $bodies[$key] ?? null;

    if ($response->successful()) {
        $results['succeeded'][] = [
            'key' => $key,
            'status' => $response->status(),
            'body_sent' => $bodySent,
            'body_received' => $response->json(),
        ];


       // dd($response->json()["success"]==false);


        if($response->json()["success"]){///on sauvegarde la facture qui a reussi  is_validated_in_remote

            $invoice =  Invoice::whereReference($bodySent["ref"])
            ->with('folder')
            ->with('remote_inserted')
            ->first() ;

           // dd($invoice);


            $remote_inserted= $invoice->remote_inserted;
            $remote_inserted->is_validated_in_remote=true;
            $remote_inserted->remotre_validated_id=$response->json()["resultId"];
            $remote_inserted->save();



            //dd($remote_inserted);

          /*$response_import = $_SERVER['SERVER_ADDR'] == "127.0.0.1" ?  $this->export_file_to_erp_from_disk($invoice) : $this->export_file_to_erp_from_s3($invoice);

          if (filter_var($response_import, FILTER_VALIDATE_URL)) {
            
            $remote_file = new RemoteFileInserted();
            $remote_file->invoice_id = $invoice->id;
            $remote_file->remote_invoice_id = $remote_invoice_id;
            $remote_file->url = $response_import;
            $remote_file->save();

            array_push($results["files_url"] , $response_import);

          }*/
       

        }
        else{// on detacte si une facture a echoue

            $all_request_successful = false;

        }

        if (array_key_exists('message', $response->json()) && $response->json()["message"] ==="account.login.notauthenticated") {
            //$should_login = true;

            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();

            $results['should_login']=true;
        }




        

    } else {
        $all_request_successful = false;

        $results['failed'][] = [
            'key' => $key,
            'status' => $response->status(),
            'body_sent' => $bodySent,
            'error' => $response->body(), // ou ->json() si c’est du JSON
        ];
    }
}
      

         //  dd($results);
$results['all_request_successful']=$all_request_successful;
           
                return $results;

        
        
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function checkAllPEC($folder , $invoices){

       

        try {
            
            

            foreach ($invoices as $inv_code => $lines) {

                $invoice_lines = [];

              //return $lines;

                $invoice_code = $inv_code;

                $current_invoice = Invoice::whereCode($invoice_code)->first();


                
                

                InvoiceLine::where('invoice_id', $current_invoice->id)->delete();
                
                foreach ($lines as $line) {

                    if ($folder->slip->provider->get_category() == "pharmacie" ||  $line["prestation"]== "PHARMACIE" ) {
            
                        $current_invoice->prestationable_type = ProductType::class;
                        $current_invoice->prestationable_id = ProductType::whereFullname($line["prestation"])->first()->id;
            
            
                    } else {

                        
                        $current_invoice->prestationable_type = ServiceType::class;
                        $current_invoice->prestationable_id = ServiceType::whereFullname($line["prestation"])->first()->id;
            
                    }


                $invoice_line = [
                    'code'=>Str::random(10),
                    'invoice_id'=>$current_invoice->id,
                    'description' => $line["description"],
                'quantity' => $line["quantity"],
                'price' => $line["price"] ];

                array_push($invoice_lines  , $invoice_line );
                
            }

            //return $invoice_lines;

            InvoiceLine::insert($invoice_lines);

            $current_invoice ->save();

        }




        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function export_file_to_erp_from_s3(Invoice $invoice){

                    $cookie_name = ".ASPXAUTH";
                    $cookie_value = decrypt(session($cookie_name));
                    $apiToken =  decrypt(session("accessToken"));

                    $url = 'https://pass24api.insighttelematics.tn/api/v1/pass24/priseEnChargeUploadFile';

                //  $s3_file = Storage::disk('s3')->get("extracted_doc/".($file['path']));

            $s3 = Storage::disk('s3');

            $filename = basename(parse_url($invoice->folder->s3_path, PHP_URL_PATH));
            $path = "extracted_doc/$filename";

            $filePath =  storage_path("app/public/extracted_doc/$filename") ;
            
            /*$formFields = [
                'projetRef' => $invoice->reference,
                'attachemebnNumber' => rand(10000 , 99999 ),
            ];*/

            $formFields = [
                'PecRef' => $invoice->reference,
                'attachemebnNumber' => 1,
            ];

            //$_SERVER['SERVER_ADDR'] == "127.0.0.1" ? 

            if (!$s3->exists($path)) {
                throw new \InvalidArgumentException("Le fichier n'existe pas sur S3 : $path");
            }

            // Lire le contenu du fichier depuis S3
            $stream = $s3->readStream($path);

            if (!$stream) {
                throw new \RuntimeException("Impossible de lire le fichier sur S3 : $path");
            }

            $client = new Client();
            try {
                $response = $client->request('POST', $url, [
                    'multipart' => array_merge(
                        [
                            [
                                'name'     => 'fileInformation',
                                'contents' => $stream,
                                'filename' => $filename,
                            ]
                        ],
                        array_map(function ($key, $value) {
                            return [
                                'name'     => $key,
                                'contents' => $value,
                            ];
                        }, array_keys($formFields), $formFields)
                    ),
                    'headers' => [
                        'Accept' => 'application/json',
                        'Cookie' => "$cookie_name=$cookie_value",
                        "Authorization" => "Bearer $apiToken",
                    ],
                ]);

                return $response->getBody()->getContents();

            } catch (RequestException $e) {
                return $e->hasResponse()
                    ? $e->getResponse()->getBody()->getContents()
                    : "Erreur lors de la requête : " . $e->getMessage();
            }
    }

    public function export_file_to_erp_from_disk(Invoice $invoice){



                    $cookie_name = ".ASPXAUTH";
                    $cookie_value = decrypt(session($cookie_name));
                    $apiToken =  decrypt(session("accessToken"));

            $url = 'https://pass24api.insighttelematics.tn/api/v1/pass24/priseEnChargeUploadFile';

            $filename = basename(parse_url($invoice->folder->doc_path, PHP_URL_PATH));

            $filePath =  storage_path("app/public/extracted_doc/$filename") ;
            /*$formFields = [
                'projetRef' => $invoice->reference,
                'attachemebnNumber' => rand(10000 , 99999 ),
            ];*/

            $formFields = [
                'PecRef' => $invoice->reference,
                'attachemebnNumber' => 1,
            ];
            
            $client = new Client();
            
                if (!file_exists($filePath) || !is_readable($filePath)) {
                    throw new \InvalidArgumentException("Le fichier n'existe pas ou n'est pas lisible : $filePath");
                }
            
            
                try {
                    $response = $client->request('POST', $url, [
                        'multipart' => array_merge(
                            // Le fichier
                            [[
                                'name'     => 'fileInformation', // le nom du champ attendu côté serveur
                                'contents' => fopen($filePath, 'r'),
                                'filename' => basename($filePath),
                            ]],
                            // Les autres champs du formulaire
                            array_map(function($key, $value) {
                                return [
                                    'name'     => $key,
                                    'contents' => $value,
                                ];
                            }, array_keys($formFields), $formFields)
                        ),
                        'headers' => [
                            'Accept' => 'application/json',
                            'Cookie' => "$cookie_name=$cookie_value",
                            'Authorization' => "Bearer $apiToken", // ⬅️ Ici on ajoute le token Bearer
                        ],
                        // Timeout, etc, peuvent être ajoutés ici
                    ]);
            
                    return $response->getBody()->getContents();
            
                } catch (RequestException $e) {
                    if ($e->hasResponse()) {
                        return $e->getResponse()->getBody()->getContents();
                    }
                    return "Erreur lors de la requête : " . $e->getMessage();
                }
            
            



    }

        public function save_invoices($folder_data){


        try {
            

            DB::beginTransaction();

            $is_draft = $folder_data['is_draft'];
            $invoices = $folder_data['invoices'];
            $folder = Folder::select("id","slip_id","code")->
        with(["slip:id,code,provider_id",
        "slip.provider:id,code,name,provider_category_id",
        "slip.provider.provider_category:id,code,name"])
        ->whereCode($folder_data['folder'])->first();

        $all_data = [];
        $responses = [];

     // return  $this->checkAllPEC($folder , $invoices);


  


      if (!$is_draft ) {
            
        $all_data =  $this->constructRemote($folder , $invoices);

        $responses =  $this->insertRemote($all_data["bodies"]);

        if ($responses['all_request_successful']) {
            
           
            
        }

    }


            foreach ($invoices as $invoice_code => $lines) {

                $invoice_lines = [];

              //return $lines;

                $invoice_code = $invoice_code;

                $current_invoice = Invoice::whereCode($invoice_code)->first();


                
                

              //  InvoiceLine::where('invoice_id', $current_invoice->id)->delete();
                
                foreach ($lines as $line) {

                    if ($folder->slip->provider->get_category() == "pharmacie" ||  $line["prestation"]== "PHARMACIE" ) {
            
                        $current_invoice->prestationable_type = ProductType::class;
                        $current_invoice->prestationable_id = ProductType::whereFullname($line["prestation"])->first()->id;
            
            
                    } else {

                        
                        $current_invoice->prestationable_type = ServiceType::class;
                        $current_invoice->prestationable_id = ServiceType::whereFullname($line["prestation"])->first()->id;
            
                    }


                $invoice_line = [
                    'code'=>Str::random(10),
                    'invoice_id'=>$current_invoice->id,
                    'description' => $line["description"],
                'quantity' => $line["quantity"],
                'price' => $line["price"] ];

                array_push($invoice_lines  , $invoice_line );
                
            }


            //InvoiceLine::insert($invoice_lines);

            //$current_invoice ->save();


         
           


        }
        /**/

        
        $not_already_create_in_remote = $all_data["not_already_create_in_remote"];
        $invoices_of_folder = $folder->invoices->count();

        $already_create_in_remote = $invoices_of_folder - $not_already_create_in_remote ;


        $all_data["already_create_in_remote"]= $already_create_in_remote;

        if (!$is_draft && $invoices_of_folder > 0 && $invoices_of_folder == $already_create_in_remote) {
            
          //  echo "saving \n\n";
            $folder->save_at = now();
        }

        $folder->save();

        $current_user = Auth::guard('sanctum')->user();

        if (!Seen::whereSeenableType(Folder::class)->whereSeenableId($folder->id)->whereUserId($current_user->id)->exists()) {
            # code...
            $view = new Seen();
            $view->user_id = $current_user->id ;
            $view->seenable_type = Folder::class ;
            $view->seenable_id = $folder->id ;
            $view->save() ;
        }

        $slip = $folder->slip;
        
        if (!Seen::whereSeenableType(Slip::class)->whereSeenableId($slip->id)->whereUserId($current_user->id)->exists()) {
            # code...
            $view = new Seen();
            $view->user_id = $current_user->id ;
            $view->seenable_type = Slip::class ;
            $view->seenable_id = $slip->id ;
            $view->save() ;
        }




            DB::commit();

             

            return [
                "status" => true,
                "success" => [
                    "all_data"=>$all_data,
                    'responses'=>$responses,
                    "lines"=>$invoice_lines,
                    "all_folder_invoices"=>$invoices_of_folder,
                    "data" => ["Sauvegarde effectuee avec succes"],
                ],
            ];

        
            
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }


    }
    

    
    
    public function getInvoices($folder_data){

        $slip = Slip::whereCode($folder_data["slip"])
        ->first();

        if (!$slip) {
            
            abort(403,"Ce bordereau est introuvable");
            
        }


        $folders = Folder::select('id','code','doc_name')->
        /**/whereHas('invoices',function($query){
            $query->where('reference','!=','UNDEFINED REFERENCE');
        })
        ->with([//"provider",
        "invoices",
      //  "invoices.invoice_lines.invoice.prestationable",
      //  "invoices.invoice_lines.invoice.folder",
      //  "invoices.prestationable"
        ])
        ->whereSlipId($slip->id)
        ->orderBy("doc_name")
        ->get();

      //  dd($folders->toArray());


        $details = [];

        foreach ($folders->toArray() as $key => $folder) {
        
         //   dd($folder);

            foreach ($folder['invoices'] as $key => $invoice) {

            array_push(
                $details,
                Str::upper($invoice["reference"]) .
                    " ( " .
                    $folder["doc_name"] .
                    " )"
            );
            }
        }

        // dd($details);

        //  $sorted = collect($details)->sort();

        //$sorted->values()->all();

        sort($details);

        return $details;

    }
    

    public function validate_invoices($folder_data){


        try {
            

            DB::beginTransaction();


            $invoices = $folder_data['invoices'];
            $folder = Folder::whereCode($folder_data['folder'])->first();
              $is_draft = $folder_data['is_draft'];

  

            foreach ($invoices as $invoice_code => $lines) {

                $invoice_lines = [];
                $validation_lines = [];

              //return $lines;

                $invoice_code = $invoice_code;

                $current_invoice = Invoice::whereCode($invoice_code)->first();

               // $invoice_id = $current_invoice->id;

                
                

               InvoiceLine::where('invoice_id', $current_invoice->id)->delete();
                
                foreach ($lines as $line) {

                    /*if ($folder->provider->get_category() == "pharmacie" ||  $line["prestation"]== "PHARMACIE" ) {
            
                        $current_invoice->prestationable_type = ProductType::class;
                        $current_invoice->prestationable_id = ProductType::whereFullname($line["prestation"])->first()->id;
            
            
                    } else {

                        
                        $current_invoice->prestationable_type = ServiceType::class;
                        $current_invoice->prestationable_id = ServiceType::whereFullname($line["prestation"])->first()->id;
            
                    }*/


                    $invoice_line = new InvoiceLine();
                    $invoice_line->code=Str::random(5);
                    $invoice_line->invoice_id = $current_invoice->id;
                    $invoice_line->description = $line["description"];
                    $invoice_line->quantity = $line["quantity"];
                    $invoice_line->observation = $line["observation"];
                    $invoice_line->coverage = $line["coverage"] == "Couverte" ? 1 : 0;
                    $invoice_line->price = $line["price"];

                    $invoice_line ->save();




                  /*  $invoice_line = [
                        'code'=>Str::random(5),
                        'invoice_id'=>$current_invoice->id,
                        'description' => $line["description"],
                    'quantity' => $line["quantity"],
                    'observation' => $line["observation"],
                    'coverage' => $line["coverage"] == "Couverte" ? 1 : 0,
                    'price' => $line["price"] ];*/
    
                    array_push($invoice_lines  , $invoice_line );



                   /* $validation_line = [
                        'code'=>Str::random(5),
                        'invoice_line_id'=>$line["id"],
                        'suggested_quantity' => $line["quantity"],
                        'validated_quantity' => $line["quantity"],
                        'suggested_price' => $invoice_line_instance->get_suggest_price(),
                        'validated_price' => $line["price"],
                    'observation' => $line["observation"],
                    'coverage' => $line["coverage"] == "Couverte" ? 1 : 0,
                 ];*/
    
                 
                 

                    $validation_line = new Validation();
                    $validation_line->code=Str::random(5);
                    $validation_line->invoice_line_id = $invoice_line->id;

                    $validation_line->suggested_quantity = $line["quantity"];
                    $validation_line->validated_quantity = $line["quantity"];

                    $validation_line->suggested_price = $line["suggested_price"];
                    $validation_line->validated_price = $line["price"];

                    // $validation_line->comment = $line["comment"];

                    $validation_line->coverage = $line["coverage"] == "Couverte" ? 1 : 0;
                    
                    if (!$is_draft) {
            
                        $validation_line ->save();

                        $full_validated = Validation::whereId($validation_line->id)
                        ->with(['invoice_line.invoice.remote_inserted'])
                        ->first();

                        if (!in_array( $full_validated->invoice_line->invoice->code , $validation_lines  )) {
                            
                            array_push($validation_lines  , $full_validated->invoice_line->invoice->code ); 
                            
                        }

                    
                    }
                    
                    
                  
                  //  return  $validation_line ;
                
            }




            //return $invoice_lines;

         //   InvoiceLine::insert($invoice_lines);

         //   $current_invoice ->save();


        }

       // return $validation_lines;

         $all_data = $this->constructRemoteValidate($validation_lines);

        $validated_response = $this->validateRemote($all_data["bodies"]);



      $not_already_validated_in_remote = $all_data["not_already_validated_in_remote"];
      
      $invoices_of_folder = $folder->invoices->count();

      $already_validated_in_remote = $invoices_of_folder - $not_already_validated_in_remote ;

      $all_data["already_validated_in_remote"]= $already_validated_in_remote;

      if (!$is_draft && $invoices_of_folder > 0 &&  $validated_response["all_request_successful"] ){// $invoices_of_folder == $already_validated_in_remote) {

          $folder->validate_at = now();

      }



         $folder->save();

            DB::commit();

             

            return [
                "status" => true,
                "success" => [
                    "invoices_lines" => $invoice_lines,
                    "validation_lines" => $validation_lines,
                    "all_data"=>$all_data,
                    "responses"=>$validated_response,
                    //"lines"=>$invoice_lines,
                    "all_folder_invoices"=>$invoices_of_folder,
                    "data" => ["Sauvegarde effectuee avec succes"],
                ],
            ];


         

        
            
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }


    }

    public function get_pagination($filters ,$folders)  {
        

        $currentPage = $request->page ?? 1;
        $itemsPerPage = $filters->input('results') ? $filters->input('results') : 10 ;
        
       // dd($currentPage);
        // You should manually make a slice of array for current page
        $items = array_slice($folders, ($currentPage - 1) * $itemsPerPage, $itemsPerPage);
        //$items = $folders->forPage($currentPage, $itemsPerPage);
        //$pagination = new LengthAwarePaginator($items, count($folders), $itemsPerPage, $currentPage);
             
        $pagination= new LengthAwarePaginator($items, count($folders), $itemsPerPage, $currentPage, [
            'path' => $filters->url(),
            'query' => $filters->query(),
        ]);
        
        
            
        
                return $pagination ;

    }

    public function count_filtered_results(Request $filters)  {
        
        
        
        $filtered_results = count($this->new_get_filtered_results($filters));



        return [
            "status" => true,
            "folders" => $filtered_results,
            "filter-button-text" => __($filtered_results." dossier(s) trouvé(s)"),
            
        ];

    }

    public function get_filtered_results(Request $filters , Slip $binded_slip = null)  {
        
        $slip =   $binded_slip ? $binded_slip : Slip::whereCode($filters->input('slip'))->first() ;


       

        $query = Folder::query()->
        with([
            "seen:id,seenable_type,seenable_id,user_id",
            "validator:id,seenable_type,seenable_id,user_id",
            "validator.user:id,employee_id",
            "validator.user.employee:id,role_id,first_name,last_name",
            "user:id,employee_id",
            "user.employee:id,role_id,first_name,last_name",
            "invoices.invoice_lines.invoice.prestationable",
            "invoices.invoice_lines.invoice.folder.slip.provider.provider_type",
            "invoices.prestationable"])
            ->whereSlipId($slip -> id);

            if (($filters->input('folder-validated'))) {
            
                $query->where('save_at','!=',null);
                $query->where('validate_at',null);
            }

            if (($filters->input('contract-linked'))) {

                $query->has('contract');
    
            }

            if (($filters->input('conform-pathology'))) {
                //$query->has('pathology');
            }
    


        
            $folders = $query->get();

        

        
        
      
    
    
        if (($filters->input('prestations-exist'))) {
            
            $folders = $folders->filter(function ($folder, $key) {
                return $folder->all_prestations_exist();
            });
            
        }

        
        
        if (($filters->input('conform-price'))) {
            
            $folders = $folders->filter(function ($folder, $key) {
                return $folder->all_prices_are_coherent();
            });

        }

        $min_price = $filters->input('min-price') ? $filters->input('min-price') : 0;
        $max_price = $filters->input('max-price') ? $filters->input('max-price') : 200000;
        $folders = $folders->filter(function ($folder, $key) use ($min_price,$max_price) {
            return $folder->get_amount() >= $min_price &&  $folder->get_amount() <= $max_price;
        });

       

       

     

        if (($filters->input('lock-filter'))) {
            # code...
        }

         
        $folders = $folders->all();
        
        
       $currentPage = $request->page ?? 1;
$itemsPerPage = $filters->input('results') ? $filters->input('results') : 10 ;

// You should manually make a slice of array for current page
$items = array_slice($folders, ($currentPage - 1) * $itemsPerPage, $itemsPerPage);
//$items = $folders->forPage($currentPage, $itemsPerPage);
//$pagination = new LengthAwarePaginator($items, count($folders), $itemsPerPage, $currentPage);
     
$pagination= new LengthAwarePaginator($items, count($folders), $itemsPerPage, $currentPage, [
    'path' => $filters->url(),
    'query' => $filters->query(),
]);


       //  dd($pagination);

        return $pagination ;
        


    }


    public function new_get_filtered_results(Request $filters , Slip $binded_slip = null)  {
        
        $slip =   $binded_slip ? $binded_slip : Slip::whereCode($filters->input('slip'))->first() ;


     //   dd($slip);
       

        $query = Folder::query()->
        with([
            "seen:id,seenable_type,seenable_id,user_id",
            "validator:id,seenable_type,seenable_id,user_id",
            "validator.user:id,employee_id",
            "validator.user.employee:id,role_id,first_name,last_name",
            "user:id,employee_id",
            "user.employee:id,role_id,first_name,last_name",
            "invoices.invoice_lines.invoice.prestationable",
            "invoices.invoice_lines.invoice.folder.slip.provider.provider_type",
            "invoices.prestationable"]);
           
            if ($slip) {
               $query ->whereSlipId($slip -> id);
            }

            if (($filters->input('folder-validated'))) {
            
                $query->where('save_at','!=',null);
                $query->where('validate_at',null);
            }

            if (($filters->input('contract-linked'))) {

                $query->has('contract');
    
            }

            if (($filters->input('conform-pathology'))) {
                //$query->has('pathology');
            }
    


        
            $folders = $query->get();

        

        
        
      
    
    
            if (($filters->input('prestations-exist'))) {
            
                $folders = $folders->filter(function ($folder, $key) {
                    return !$folder->all_prestations_exist();
                });
                
            }


            if (($filters->input('should-be-validated'))) {
            
                $folders = $folders->filter(function ($folder, $key) {
                    return $folder->should_be_validated();
                });
                
            }

            

        
        
        if (($filters->input('conform-price'))) {
            
            $folders = $folders->filter(function ($folder, $key) {
                return !$folder->all_prices_are_coherent();
            });

        }

        $min_price = $filters->input('min-price') ? $filters->input('min-price') : 0;
        $max_price = $filters->input('max-price') ? $filters->input('max-price') : 200000;
        $folders = $folders->filter(function ($folder, $key) use ($min_price,$max_price) {
            return $folder->get_amount() >= $min_price &&  $folder->get_amount() <= $max_price;
        });

       

       

     

        if (($filters->input('lock-filter'))) {
            # code...
        }

         
     return   $folders = $folders->all();
        
        
      
        


    }


    public function update_identification($folder_data){


        try {
            

            DB::beginTransaction();

            $identification = $folder_data['reference'];
            $folder = Folder::whereCode($folder_data['folder'])->first();
            $existing_folder = Folder::whereIdentification($identification)->first();


            if (!$folder) {
               
                return [
                    "status" => false,
                    "errors" => [
                        "reference" => [__("Dossier introuvable")],
                    ],
                ];
            }
            
            /*if ($existing_folder && ($folder->code != $existing_folder->code) ) {
                
               return [
                    "status" => false,
                    "errors" => [
                        "reference" => [__("Un dossier avec ce numero existe déja")],
                    ],
                ];
                
            }*/
            
            //dd( $existing_folder && ($folder->code != $existing_folder->code));

            $folder->identification = Str::upper($identification);
            $folder->save();

            DB::commit();

             

            return [
                "status" => true,
                "success" => [
                    "data" => ["Sauvegarde effectuee avec succes"],
                ],
            ];

        
            
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }


    } 


    public function deleteFolder($folder)
    {
        // $folder = folder::whereCode($folder_details['folder'])->first();

        if (!$folder) {
            return [
                "status" => false,
                "errors" => [
                    "model" => ["Dossier invalide"],
                ],
            ];
        }

        


    

        try {
            //code...

            DB::beginTransaction();

        //$result = $folder->delete();
        
        $time = 1;

        if (true) {


            $folder->delete();

            $response =  [
                "status" => true,
                "success" => "Dossier supprimé avec succès, la page rechargera dans $time seconde(s)",
                "time" => $time,
            ];
        }
        else{

            $response =  [
                "status" => false,
                "errors" => [
                    "deleting" => ["Erreur survenue lors de la suppression"],
                ],
            ];

        }


        DB::commit();

        return $response ;


     


          

        } catch (\Throwable $th) {
            DB::rollback();
                throw $th;
        }
    }

}
