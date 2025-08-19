<?php

namespace App\Services\Misc ;

use App\Models\User;
use App\Jobs\SendSmsJob;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Misc\Resource;
use App\Models\Operations\Mission;
use App\Models\Operations\Step;
use App\Models\City;
use App\Models\Storage\Reception;
use App\Models\Storage\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Storage\StockMovement;
use App\Models\Storage\ProductSupplier;
use App\Models\Storage\ProductWarehouse;
use App\Notifications\WelcomeUserNotification;

use App\Addons\DataConstructor;
use App\Addons\FileUploadHandler;
use App\Addons\Misc\CreateVariablesResponder;
use App\Addons\Misc\EditVariablesResponder;
use App\Addons\Misc\IndexVariablesResponder;
use App\Addons\Misc\ShowVariablesResponder;
use App\Addons\Misc\ViewsResponder;
use App\Models\Company;
use App\Models\GroundAgent;
use App\Models\Misc\KeySign;
use Illuminate\Support\Facades\Hash;

class GroundAgentService  implements
//IndexVariablesResponder,
CreateVariablesResponder,
ShowVariablesResponder,
EditVariablesResponder,
ViewsResponder {

    use DataConstructor , FileUploadHandler  ;


    function searchInvoices($slip , $query_search)
    {
        //dd($slip);
        $slip->load([ 
        "folders:id,slip_id",
      //  "folders.user.employee",
      //  "folders.provider",
        "folders.invoices.invoice_lines",
        ])
        ->first();/**/

        $folders = Folder::
        whereHas('invoices',function($query) use ($query_search){
            $query->whereReference(substr($query_search, 0, 8));
        })
        ->with(["provider",
        "seen",
        "user.employee",
        "invoices.invoice_lines.invoice.prestationable",
        "invoices.invoice_lines.invoice.folder",
        "invoices.prestationable"])
        ->whereSlipId($slip->id)
        //->whereIdentification(substr($query, 0, 10))
        ->paginate(1)
        ->withQueryString();

       // dd($folders);

        $folders_success = Folder::
        whereHas('invoices',function($query) use ($query_search){
            $query->whereReference(substr($query_search, 0, 8));
        })
        ->with(["provider",
        "seen",
        "user.employee",
        "invoices.invoice_lines.invoice.prestationable",
        "invoices.invoice_lines.invoice.folder",
        "invoices.prestationable"])
        ->whereSlipId($slip->id)
      //  ->whereIdentification($query)
        ->whereStatus(1)
        ->paginate(10)
        ->withQueryString();

        $folders_reject = Folder::
        whereHas('invoices',function($query) use ($query_search){
            $query->whereReference(substr($query_search, 0, 8));
        })
        ->with(["provider",
        "seen",
        "user.employee",
        "invoices.invoice_lines.invoice.prestationable",
        "invoices.invoice_lines.invoice.folder",
        "invoices.prestationable"])
        ->whereSlipId($slip->id)
       // ->whereIdentification($query)
        ->whereStatus(0)
        ->paginate(10)
        ->withQueryString();

       

        $service_types  = ServiceType::select('id','code','name','fullname')->get();
        $product_types  = ProductType::select('id','code','name','fullname')->get();

       // $folders = $slip->folders;
       
       // dd($folders);
      //  $services = $slip->invoices('service_id')->distinct()->get();

      //  dd($services);

      $header_title = "Supprimer une facture";

      $query_label = "Reference ou nom de l'assuré";


      $typeahead_url = url("/api/getInvoices?slip=".($slip->code));

        return compact(
            "slip",
            "query_label",
            "folders",
            "folders_success",
            "folders_reject",
            "service_types",
            "product_types",
            "typeahead_url",
            "header_title"
        );
    }
    function createFile($request)
    {

                try {
            
                $host = $_SERVER['HTTP_HOST'];
                $remote_address = $_SERVER['REMOTE_ADDR'];
                $server_address = $_SERVER['SERVER_ADDR'];
                $server_port = $_SERVER['SERVER_PORT'];

                //  return $remote_address;
        
                // $filesArray = [];
                //  $pathsArray = [];
                //  $upload_file_service = new UpdloadFileService();
                    $user = Auth::guard("sanctum")->user();
                    $home  = $_SERVER['SERVER_NAME'] == '192.168.43.84' ||/**/ $_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1' ? "marcel" : ( $_SERVER['SERVER_NAME'] == "51.254.121.221" || $_SERVER['SERVER_NAME'] == "51.254.121.44" ?  "ubuntu" : "thsailid64");
                    $provider= Provider::whereName($request['provider'])
                    ->with(['provider_category'])
                    ->first();
                    $timer = null;
                    $slip_identification =  $request['slip'] ? $request['slip'] : Str::upper(Str::random(8));
                    $files = json_decode($request["files"] , true);
                //  $batchId = 0;
                //  $batchs=[];

                    if (! $provider) {
                        return [
                            "status" => false,
                            "errors" => ["provider" => ["Ce prestataire n'existe pas dans la base de données"]],
                        ];
                    }


                    // dd(Carbon::createFromFormat());

                    $slip = Slip::whereIdentification($slip_identification)->first();


                    if ($request['timer']) {

                        $timer = $request['timer'];
                        
                       // $slip = Slip::whereIdentification($slip_identification)->first();
        
                        if ( ! $slip) {
                            $slip = new Slip();
                            $slip->code = Str::random(15);
                            $slip->identification = $slip_identification;
                            $slip->provider_id = $provider->id;
                            $slip->user_id = $user->id;
                            $slip->save();
                        }
                        else{

                            $slip->provider_id = $provider->id;
                         //   $slip->user_id = $user->id;
                            $slip->save();

                        }
                        
                    //  $user->notify(new ExtractionCompleted($slip , sizeof($files)));
                        
                    }

                


                
    
                // $faker = Faker\Factory::create();
                

                // $file_data = ["user_id" => $user->id];
        
                    if (array_key_exists("files", $request)) {

                        $all_folders = sizeof($files);

                      //  $slip=Slip::select('id')->whereIdentification($slip_identification)->first();

                        foreach ($files as $index => $file) {

                        

                            $existing_folder = Folder::whereDocName($file['name'])
                            ->whereSlipId($slip ? $slip->id : 0)
                            ->first();

                        if (!$existing_folder) {


                        //  dd($file['path']);
                            

                            if ($_SERVER['SERVER_ADDR'] == "127.0.0.1") {//https://extract237.s3.eu-west-3.amazonaws.com/

                                if ($request['timer']) {

                                // return($request['timer']);
                                    
                                    $date = Carbon::CreateFromFormat('H:i', $timer);
                                    
                                    $now = Carbon::now()->addHour();

                                    $diff = $date->diffInMinutes($now);

                                    if ($date->isAfter($now)) {
                                        
                                    } else {
                                        $date->addDay();
                                    }
                                    

                                    $delay = $date->subHour();// now()->addMinutes($diff);
                                    
                                    $schedule = new Schedule();
                                    $schedule->start_at = $delay->setTimezone('Africa/Douala');
                                    $slip->schedules()->save($schedule);
                                
                                ExtractCaseJob::dispatch($slip_identification, [$file['path']], "https://extract237-pass24.s3.eu-west-3.amazonaws.com/extracted_doc/".$file['path'], $file['name'] , $home ,$user , $provider , $timer , $all_folders , $index , $host , $remote_address)
                                    //->afterCommit() 
                                    ->delay($delay);


                            
                                    


                                } else {

                                ExtractCaseJob::dispatch($slip_identification, [$file['path']], "https://extract237-pass24.s3.eu-west-3.amazonaws.com/extracted_doc/".$file['path'], $file['name'] , $home ,$user , $provider , $timer , $all_folders , $index , $host , $remote_address)
                                    //->afterCommit() 
                                    ;

                                }
                                
                            }
                            elseif($_SERVER['SERVER_ADDR'] == "51.254.121.221"  || $_SERVER['SERVER_ADDR'] == "51.254.121.44"){
                            
                            $s3_file = Storage::disk('s3')->get("extracted_doc/".($file['path']));
                            $s3 = Storage::disk('public');
                            $s3->put(("extracted_doc/".$file['path']), $s3_file);

                                if ($request['timer']) {

                                    // return($request['timer']);
                                    
                                    $date = Carbon::CreateFromFormat('H:i', $timer);
                                    
                                    $now = Carbon::now()->addHour();
    
                                    //$diff = $date->diffInMinutes($now);
    
                                    if ($date->isAfter($now)) {
                                        
                                    } else {
                                        $date->addDay();
                                    }
                                    
    
                                    $delay = $date->subHour();// now()->addMinutes($diff);
                                    
                                    $schedule = new Schedule();
                                    $schedule->start_at = $delay->setTimezone('Africa/Douala');
                                    $slip->schedules()->save($schedule);
                                
                                ExtractCaseJob::dispatch($slip_identification, [$file['path']], "https://extract237-pass24.s3.eu-west-3.amazonaws.com/extracted_doc/".$file['path'], $file['name'] , $home ,$user , $provider , $timer , $all_folders , $index , $host , $remote_address)
                                //->afterCommit()   
                                ->delay($delay);
    
    
                            
                                    
    
    
                                } else {

                                ExtractCaseJob::dispatchSync($slip_identification, [$file['path']], "https://extract237-pass24.s3.eu-west-3.amazonaws.com/extracted_doc/".$file['path'], $file['name'] , $home ,$user , $provider , $timer , $all_folders , $index , $host , $remote_address);


                                }

                            }
                            else {
                                ExtractCaseJob::dispatchSync($slip_identification, [$file['path']], "https://extract237.s3.eu-west-3.amazonaws.com/extracted_doc/".$file['path'], $file['name'] , $home ,$user , $provider , $timer , $all_folders , $index , $host , $remote_address);
                            }
                            
                        }
                        else {

                            if (!$request['timer']) {

                            $existing_folder->load([
                                "slip:id,code,identification",
                                "user:id,code",
                                "extraction_times"=>function($query) use ($existing_folder) {
                                $query->select('id','folder_id','duration')
                                ->whereFolderId($existing_folder->id)
                                ->orderByDesc('id')
                                ->take(1);
                                },
                                "slip:id,code,identification,amount,user_id",
                                "slip.provider:id,code,name,address,provider_category_id",
                            ]);

                            $has_items = true;
                            $is_new_folder = false;

                      
                            SlipExtracted::dispatch($existing_folder ,$has_items , $is_new_folder , $user /*, $host , $remote_address/*, $_SERVER['REMOTE_ADDR'] , $_SERVER['SERVER_ADDR']*/);
                    
                            
                            }
                        
                        }
                        

                            
                        }


            
                        
                    } 


                    
                        return [
                        "status" => true,
                        "data"=>[
                        "slip_identification"=>$slip_identification,
                    //  "file"=>$file,
                        "host"=>$host,
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
    function getIndexVariables()
    {
       

        $ground_agents = GroundAgent::oldest()
        ->paginate(10)
        ->withQueryString();

        $vars = compact("ground_agents");

        return $vars;
    }
    function getCreateVariables()
    {
        $cities = City::get();
        $companies = Company::get();
        $ground_agent = null;
        $action = "create";
        $disabled = "";
        $readonly = "";

     

        return compact(
            "cities",
            "ground_agent",
            "companies",
            "action",
            "disabled",
            "readonly",
        );
    }
    function getShowVariables($folder)
    {
        $folder->load([
        "slip:id,code,identification,provider_id,user_id",
        "slip.provider:id,code,name,provider_category_id",
        "slip.provider.provider_category:id,code,name",
        "invoices.remote_inserted",
        "invoices.folder",
        "invoices.invoice_lines.validation",
        "invoices.invoice_lines.invoice.prestationable",
        "invoices.invoice_lines.invoice.folder",
        "invoices.prestationable"]);

     //   dd($folder);

        $previous = $folder->previous();
        $next = $folder->next();

      //  dd($previous);

      //  dd($next); 

        $service_types  = ServiceType::select('id','code','name','fullname')->get();
        $product_types  = ProductType::select('id','code','name','fullname')->get();

        //$current_user = Auth::user();

        /*
        if (!Seen::whereSeenableType(Folder::class)->whereSeenableId($folder->id)->whereUserId($current_user->id)->exists()) {
            # code...
            $view = new Seen();
            $view->user_id = $current_user->id ;
            $view->seenable_type = Folder::class ;
            $view->seenable_id = $folder->id ;
            $view->save() ;
        }
        */



        $query_label = "Numero de bordereau";

        $header_title = "Voulez-vous supprimer cette ligne ?";


        $typeahead_url = url("/api/getInvoices?slip=".($folder->slip->code));
        $extractor_typeahead_url = url("/api/getPrestations");
        

        return compact(
            "folder",
            "query_label",
            "header_title",
            "typeahead_url",
            "extractor_typeahead_url",
            "previous",
            "next",
            "service_types",
            "product_types"
        );
    }
    function getEditVariables($ground_agent)
    {
        $ground_agent->load(["company"]);
        $companies = Company::get();

        $action = "update";
        $disabled = "";
        $readonly = "";

        return compact(
            "ground_agent",
            "action",
            "disabled",
            "readonly",  
            "companies"
          );
    }
    function getView($view_name, $vars = [])
    {
        return view($view_name, $vars);
    }
    function delete($model)
    {
        $result = $model->delete();

        if ($result) {
            return [
                "status" => true,
                "success" => [
                    "deleting" => ["Suppression effectuee avec succes"],
                ],
            ];
        }

        return [
            "status" => false,
            "errors" => [
                "deleting" => ["Erreur survenue lors de la suppression"],
            ],
        ];
    }
    
    public function create(array $details ) 
    {

                    
                    try {
                        
                     DB::beginTransaction();

                /*if (GroundAgent::select('id')->where('name' ,  $details['name'])->count()) {
                            
                    return [
                            'status'=>false,
                            'errors'=> ["name" => ["Cette ville existe deja"]]
                        ];

                }*/
                    
                        $ground_agent= new GroundAgent();    
                        $columns = ['first_name','last_name','email',
                        ];
                
                        foreach ($columns as  $column) {
                            array_key_exists($column, $details ) ? $ground_agent->{$column} = $details[$column] : null ;
                        }
                    
            /* if (Reception::where('name', $details['name'])->first()) {
                        
                        return [
                                'status'=>false,
                                'errors'=> ["name" => ["Un fournisseur avec ce nom existe deja"]]
                            ];

                }*/
                
                $ground_agent->code = Str::random(10);
                $ground_agent->company_id = Company::whereCode($details['company'])->first()->id;
                $ground_agent->admin_id = Auth::guard('sanctum')->user()->id;
                //$mission = Mission::select('id')->whereId($details['mission'])->first();
                


                    $ground_agent->save();


                    $id = 4;//Hash::make(6);
    $key = Hash::make("123456");

    $ground_agents = GroundAgent::whereId(4)->get();

        
        $sign = KeySign::create([
            'code'=>Str::random(20),
          //  'model_type'=>(GroundAgent::class),
            'model_type'=>("App\Models\Operations\GroundAgent"),
            'model_id'=>$ground_agent->id,
            'key'=>$key,
            'hash'=>Hash::make($ground_agent->id.$key)
        ]);
        
    
            
                
                /*   return [
                        'status'=>true,
                        'data'=> [$city] 
                    ];*/


                    //////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\/////////////////////////////
                    
                   DB::commit();
                    
                    return [
                        'status'=>true,
                        'data'=> $ground_agent 
                    ];


                } catch (\Throwable $th) {
                 DB::rollback();
                    throw $th;
                }
                    
       
    }

    public function update(array $details , GroundAgent $ground_agent ) 
    {

                    
                    try {
                        
                //     DB::beginTransaction();

                
                $columns = ['first_name','last_name','email',
                ];
        
                foreach ($columns as  $column) {
                    array_key_exists($column, $details ) ? $ground_agent->{$column} = $details[$column] : null ;
                }
            
                //$mission = Mission::select('id')->whereId($details['mission'])->first();
                
                $ground_agent->company_id = Company::whereCode($details['company'])->first()->id;


                    $ground_agent   ->save();
            
                
                /*   return [
                        'status'=>true,
                        'data'=> [$ground_agent ] 
                    ];*/


                    //////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\/////////////////////////////
                    
                //   DB::commit();
                    
                    return [
                        'status'=>true,
                        'data'=> $ground_agent   
                    ];


                } catch (\Throwable $th) {
                // DB::rollback();
                    throw $th;
                }
                    
       
    }


}
