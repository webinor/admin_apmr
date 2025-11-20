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
use App\Models\WheelChair;

class WheelChairService  implements
//IndexVariablesResponder,
CreateVariablesResponder,
ShowVariablesResponder,
EditVariablesResponder,
ViewsResponder {

    use DataConstructor , FileUploadHandler  ;


   
    function getIndexVariables()
    {
       

        $wheel_chairs = WheelChair::oldest()
        ->paginate(10)
        ->withQueryString();

        $vars = compact("wheel_chairs");

        return $vars;
    }
    function getCreateVariables()
    {
        $wheel_chair = null; 
        $action = "create";
        $disabled = "";
        $readonly = "";

     

        return compact(
            "wheel_chair",
            "action",
            "disabled",
            "readonly",
        );
    }
    function getShowVariables($folder)
    {
    }
    function getEditVariables($wheel_chair)
    {
        $wheel_chair;//->load(["logo", "documents"]);
        $action = "update";
        $disabled = "";
        $readonly = "";

        return compact(
            "wheel_chair",
            "action",
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
    
    public function createWheelChair(array $details ) 
    {

                    
                    try {
                        
                //     DB::beginTransaction();

                if (WheelChair::select('id')->where('name' ,  $details['name'])->count()) {
                            
                    return [
                            'status'=>false,
                            'errors'=> ["name" => ["Ce type existe deja"]]
                        ];

                }
                    
                        $wheel_chair= new WheelChair();    
                        $columns = ['name','slug'
                        ];
                
                        foreach ($columns as  $column) {
                            array_key_exists($column, $details ) ? $wheel_chair->{$column} = $details[$column] : null ;
                        }
                    
            /* if (Reception::where('name', $details['name'])->first()) {
                        
                        return [
                                'status'=>false,
                                'errors'=> ["name" => ["Un fournisseur avec ce nom existe deja"]]
                            ];

                }*/
                $wheel_chair->code = Str::random(10);
                
                $wheel_chair->admin_id = Auth::guard('sanctum')->user()->id;

                //$mission = Mission::select('id')->whereId($details['mission'])->first();
                


                    $wheel_chair->save();
            
                
                /*   return [
                        'status'=>true,
                        'data'=> [$city] 
                    ];*/


                    //////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\/////////////////////////////
                    
                //   DB::commit();
                    
                    return [
                        'status'=>true,
                        'data'=> $wheel_chair 
                    ];


                } catch (\Throwable $th) {
                // DB::rollback();
                    throw $th;
                }
                    
       
    }

    public function updateWheelChair(array $details , WheelChair $wheel_chair ) 
    {

                    
                    try {
                        
                //     DB::beginTransaction();

                if (WheelChair::select('id')->where('name' ,  $details['name'])
                ->where('code','!=',$details['wheel_chair'])
                ->count()) {
                            
                    return [
                            'status'=>false,
                            'errors'=> ["name" => ["Ce type existe deja"]]
                        ];

                }/**/
                    
                    
                    
            /* if (Reception::where('name', $details['name'])->first()) {
                        
                        return [
                                'status'=>false,
                                'errors'=> ["name" => ["Un fournisseur avec ce nom existe deja"]]
                            ];

                }*/
                
                $wheel_chair->name = $details['name'];
                $wheel_chair->slug = $details['slug'];

                //$mission = Mission::select('id')->whereId($details['mission'])->first();
                


                    $wheel_chair->save();
            
                
                /*   return [
                        'status'=>true,
                        'data'=> [$city] 
                    ];*/


                    //////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\/////////////////////////////
                    
                //   DB::commit();
                    
                    return [
                        'status'=>true,
                        'data'=> $wheel_chair 
                    ];


                } catch (\Throwable $th) {
                // DB::rollback();
                    throw $th;
                }
                    
       
    }


}
