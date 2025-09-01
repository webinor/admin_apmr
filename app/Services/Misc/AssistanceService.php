<?php

namespace App\Services\Misc ;

use App\Jobs\SendSmsJob;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Misc\Resource;
use App\Models\Operations\Mission;
use App\Models\Operations\Step;
use App\Models\City;
use App\Models\Company;
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
use App\Models\AssistanceAgent;
use App\Models\Operations\Assistance;
use App\Models\Registrator;
use App\Models\User\User;
use App\Models\WheelChair;
use Carbon\Carbon;

class AssistanceService  implements
//IndexVariablesResponder,
CreateVariablesResponder,
ShowVariablesResponder,
EditVariablesResponder,
ViewsResponder {

    use DataConstructor , FileUploadHandler  ;


    protected string $apmerServiceBaseUrl;

    public function __construct()
    {
        // À mettre dans .env par exemple USER_SERVICE_BASE_URL
        $this->apmerServiceBaseUrl = config('services.apmr_service.base_url');
    }



    public function count_filtered_results(Request $filters)  {
        
        
        
        $filtered_results = count($this->new_get_filtered_results($filters));



        return [
            "status" => true,
            "folders" => $filtered_results,
            "filter-button-text" => __($filtered_results." dossier(s) trouvé(s)"),
            
        ];

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

        $folders = Assistance::
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

        $folders_success = Assistance::
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

        $folders_reject = Assistance::
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

       

        $service_types  = Assistance::select('id','code','name','fullname')->get();
        $product_types  = Assistance::select('id','code','name','fullname')->get();

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
                    $provider= Assistance::whereName($request['provider'])
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

                    $slip = Assistance::whereIdentification($slip_identification)->first();


                    if ($request['timer']) {

                        $timer = $request['timer'];
                        
                       // $slip = Slip::whereIdentification($slip_identification)->first();
        
                        if ( ! $slip) {
                            $slip = new Assistance();
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

                        

                            $existing_folder = Assistance::whereDocName($file['name'])
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
                                    
                                    $schedule = new Assistance();
                                    $schedule->start_at = $delay->setTimezone('Africa/Douala');
                                    $slip->schedules()->save($schedule);
                                
                                Assistance::dispatch($slip_identification, [$file['path']], "https://extract237-pass24.s3.eu-west-3.amazonaws.com/extracted_doc/".$file['path'], $file['name'] , $home ,$user , $provider , $timer , $all_folders , $index , $host , $remote_address)
                                    //->afterCommit() 
                                    ->delay($delay);


                            
                                    


                                } else {

                                Assistance::dispatch($slip_identification, [$file['path']], "https://extract237-pass24.s3.eu-west-3.amazonaws.com/extracted_doc/".$file['path'], $file['name'] , $home ,$user , $provider , $timer , $all_folders , $index , $host , $remote_address)
                                    //->afterCommit() 
                                    ;

                                }
                                
                            }
                            elseif($_SERVER['SERVER_ADDR'] == "51.254.121.221"  || $_SERVER['SERVER_ADDR'] == "51.254.121.44"){
                            
                            $s3_file = Assistance::disk('s3')->get("extracted_doc/".($file['path']));
                            $s3 = Assistance::disk('public');
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
                                    
                                    $schedule = new Assistance();
                                    $schedule->start_at = $delay->setTimezone('Africa/Douala');
                                    $slip->schedules()->save($schedule);
                                
                                    Assistance::dispatch($slip_identification, [$file['path']], "https://extract237-pass24.s3.eu-west-3.amazonaws.com/extracted_doc/".$file['path'], $file['name'] , $home ,$user , $provider , $timer , $all_folders , $index , $host , $remote_address)
                                //->afterCommit()   
                                ->delay($delay);
    
    
                            
                                    
    
    
                                } else {

                                    Assistance::dispatchSync($slip_identification, [$file['path']], "https://extract237-pass24.s3.eu-west-3.amazonaws.com/extracted_doc/".$file['path'], $file['name'] , $home ,$user , $provider , $timer , $all_folders , $index , $host , $remote_address);


                                }

                            }
                            else {
                                Assistance::dispatchSync($slip_identification, [$file['path']], "https://extract237.s3.eu-west-3.amazonaws.com/extracted_doc/".$file['path'], $file['name'] , $home ,$user , $provider , $timer , $all_folders , $index , $host , $remote_address);
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

                      
                        Assistance::dispatch($existing_folder ,$has_items , $is_new_folder , $user /*, $host , $remote_address/*, $_SERVER['REMOTE_ADDR'] , $_SERVER['SERVER_ADDR']*/);
                    
                            
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
       

        $assistances = Assistance::oldest()
        ->has('signature')
        ->with([
       // 'signature',
        'registrator',
        //'assistance_lines.assistance_agent',
       // 'assistance_lines.wheel_chair',
        'ground_agent.company.ground_agents'])
        ->paginate(10)
        ->withQueryString();

        $companies = Company::get();
        $agents = AssistanceAgent::get();
        $users = Registrator::get();
        $cities = City::get();
        $wheel_chairs = WheelChair::get();

      //  dd($assistances);

        $vars = compact("assistances" , "companies" , "agents" , "users" , "cities" , "wheel_chairs");

        return $vars;
    }
    function getCreateVariables()
    {
        $city = null; 
        $action = "create";
        $disabled = "";
        $readonly = "";

     

        return compact(
            "city",
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

        $service_types  = Assistance::select('id','code','name','fullname')->get();
        $product_types  = Assistance::select('id','code','name','fullname')->get();

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
    function getEditVariables($assistance)
    {

        $assistance = $assistance->load([
            'signature',
            'assistance_lines.assistance_agent',
            'assistance_lines.wheel_chair',
            'ground_agent.company.ground_agents']);
    
    
         //   dd($assistance);
    
           $current_city_id = $assistance->ground_agent->company->city->id;
    
           //dd($current_city_id);
    
    
            $cities = City::orderBy('name')->get();
            $companies = Company::orderBy('name')->whereCityId($current_city_id)->get();
    
           //dd($companies);
    
            $wheel_chairs = $assistance->ground_agent ? $assistance->ground_agent->company->wheel_chairs : [];
            $agents = AssistanceAgent::whereCityId($current_city_id)->get();
            $action = "update";
            $disabled =  $assistance->signature ? "disabled" : "" ;
            $readonly = $assistance->signature ? "readonly" : "" ;
            $apmr_url = $this->apmerServiceBaseUrl;
       
            return compact(
                "assistance",
                "apmr_url",
                "cities",
                "companies", "assistance",
                "wheel_chairs",
                "agents",
                "action",
                "disabled",
                "readonly",
            );

        $assistance->load(["logo", "documents"]);
        $action = "update";
        $disabled = "";
        $readonly = "";

        return compact(
            "supplier",
            "action",
            "disabled",
            "readonly",
            "areas"
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
    
    public function createCity(array $company_details ) 
    {

                    
                    try {
                        
                //     DB::beginTransaction();

                if (City::select('id')->where('name' ,  $company_details['name'])->count()) {
                            
                    return [
                            'status'=>false,
                            'errors'=> ["name" => ["Cette ville existe deja"]]
                        ];

                }
                    
                        $city= new City();    
                        $columns = ['name',
                        ];
                
                        foreach ($columns as  $column) {
                            array_key_exists($column, $company_details ) ? $city->{$column} = $company_details[$column] : null ;
                        }
                    
            /* if (Reception::where('name', $company_details['name'])->first()) {
                        
                        return [
                                'status'=>false,
                                'errors'=> ["name" => ["Un fournisseur avec ce nom existe deja"]]
                            ];

                }*/
                
                $city->admin_id = $company_details['token'];

                //$mission = Mission::select('id')->whereId($company_details['mission'])->first();
                


                    $city->save();
            
                
                /*   return [
                        'status'=>true,
                        'data'=> [$city] 
                    ];*/


                    //////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\/////////////////////////////
                    
                //   DB::commit();
                    
                    return [
                        'status'=>true,
                        'data'=> $city 
                    ];


                } catch (\Throwable $th) {
                // DB::rollback();
                    throw $th;
                }
                    
       
    }

    public function updateCity(array $company_details ) 
    {

                    
                    try {
                        
                //     DB::beginTransaction();

                if (City::select('id')->where('name' ,  $company_details['name'])
                ->where('id','!=',$company_details['city'])
                ->count()) {
                            
                    return [
                            'status'=>false,
                            'errors'=> ["name" => ["Cette ville existe deja"]]
                        ];

                }/**/
                    
                        $city= City::find($company_details['city']);    
                    
                    
            /* if (Reception::where('name', $company_details['name'])->first()) {
                        
                        return [
                                'status'=>false,
                                'errors'=> ["name" => ["Un fournisseur avec ce nom existe deja"]]
                            ];

                }*/
                
                $city->name = $company_details['name'];

                //$mission = Mission::select('id')->whereId($company_details['mission'])->first();
                


                    $city->save();
            
                
                /*   return [
                        'status'=>true,
                        'data'=> [$city] 
                    ];*/


                    //////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\/////////////////////////////
                    
                //   DB::commit();
                    
                    return [
                        'status'=>true,
                        'data'=> $city 
                    ];


                } catch (\Throwable $th) {
                // DB::rollback();
                    throw $th;
                }
                    
       
    }


}
