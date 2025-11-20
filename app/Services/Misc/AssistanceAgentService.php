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
use App\Models\Setting\Company;
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

class AssistanceAgentService  implements
//IndexVariablesResponder,
CreateVariablesResponder,
ShowVariablesResponder,
EditVariablesResponder,
ViewsResponder {

    use DataConstructor , FileUploadHandler  ;


   
  
    function getIndexVariables($results)
    {
       

        $assistance_agents = AssistanceAgent::oldest()
        ->paginate($results)
        ->withQueryString();

        $vars = compact("assistance_agents");

        return $vars;
    }
    function getCreateVariables()
    {
        $assistance_agent = null; 
        $cities = City::get(); 
        $action = "create";
        $disabled = "";
        $readonly = "";

     

        return compact(
            "assistance_agent",
            "action",
            "disabled",
            "readonly",
            "cities"
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

       // $service_types  = ServiceType::select('id','code','name','fullname')->get();
       // $product_types  = ProductType::select('id','code','name','fullname')->get();

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
    function getEditVariables($assistance_agent)
    {
        $assistance_agent->load(["city"]);
        $cities = City::get(); 

        $action = "update";
        $disabled = "";
        $readonly = "";

        return compact(
            "assistance_agent",
            "action",
            "cities",
            "disabled",
            "readonly"
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
                        
                //     DB::beginTransaction();

                    
                        $assistance_agent= new AssistanceAgent();    
                        $columns = ['first_name','last_name','email',
                        ];
                
                        foreach ($columns as  $column) {
                            array_key_exists($column, $details ) ? $assistance_agent->{$column} = $details[$column] : null ;
                        }
                
                $assistance_agent->code = Str::random(10);
                $assistance_agent->city_id = City::whereCode($details['city'])->first()->id;
                $assistance_agent->admin_id = Auth::guard('sanctum')->user()->id;

                //$mission = Mission::select('id')->whereId($city_details['mission'])->first();
                


                    $assistance_agent->save();

            
            


                    //////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\/////////////////////////////
                    
                //   DB::commit();
                    
                    return [
                        'status'=>true,
                        'data'=> $assistance_agent 
                    ];


                } catch (\Throwable $th) {
                // DB::rollback();
                    throw $th;
                }
                    
       
    }

    public function update(array $details , AssistanceAgent $assistance_agent ) 
    {

                    
                    try {
                        
                //     DB::beginTransaction();

               
                    
                    
                $columns = ['first_name','last_name','email',
                        ];
                
                        foreach ($columns as  $column) {
                            array_key_exists($column, $details ) ? $assistance_agent->{$column} = $details[$column] : null ;
                        }


                        $assistance_agent->city_id = City::whereCode($details['city'])->first()->id;

                    $assistance_agent->save();
            
                
                /*   return [
                        'status'=>true,
                        'data'=> [$city] 
                    ];*/


                    //////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\/////////////////////////////
                    
                //   DB::commit();
                    
                    return [
                        'status'=>true,
                        'data'=> $assistance_agent 
                    ];


                } catch (\Throwable $th) {
                // DB::rollback();
                    throw $th;
                }
                    
       
    }

        public function deleteAssistanceAgent(AssistanceAgent $assistance_agent  ) 
    {

                    
                    try {
                        
                     DB::beginTransaction();
                $assistance_agent->is_active = false;
                $assistance_agent->save();

              
                    //////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\/////////////////////////////
                    
                   DB::commit();
                    
                    return [
                        'status'=>true,
                        'data'=> $assistance_agent 
                    ];


                } catch (\Throwable $th) {
                 DB::rollback();
                    throw $th;
                }
                    
       
    }


}
