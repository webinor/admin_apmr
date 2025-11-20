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

class CityService  implements
//IndexVariablesResponder,
CreateVariablesResponder,
ShowVariablesResponder,
EditVariablesResponder,
ViewsResponder {

    use DataConstructor , FileUploadHandler  ;


  
    function getIndexVariables()
    {
       

        $cities = City::oldest()
        ->with(['admin.employee'])
        ->paginate(10)
        ->withQueryString();

        $vars = compact("cities");

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

    }
    function getEditVariables($city)
    {
        $city;//->load(["logo", "documents"]);
        $action = "update";
        $disabled = "";
        $readonly = "";

        return compact(
            "city",
            "action",
            "disabled",
            "readonly"        );
    }
    function getView($view_name, $vars = [])
    {
        return view($view_name, $vars);
    }
    function delete($model)
    {

    }
    
    public function createCity(array $city_details ) 
    {

                    
                    try {
                        
                //     DB::beginTransaction();

                if (City::select('id')->where('name' ,  $city_details['name'])->count()) {
                            
                    return [
                            'status'=>false,
                            'errors'=> ["name" => ["Cette ville existe deja"]]
                        ];

                }
                    
                        $city= new City();    
                        $columns = ['name',
                        ];
                
                        foreach ($columns as  $column) {
                            array_key_exists($column, $city_details ) ? $city->{$column} = $city_details[$column] : null ;
                        }
                    
            /* if (Reception::where('name', $city_details['name'])->first()) {
                        
                        return [
                                'status'=>false,
                                'errors'=> ["name" => ["Un fournisseur avec ce nom existe deja"]]
                            ];

                }*/
                
                $city->code = Str::random(10);
                $city->slug = $city_details['name'];
                $city->admin_id = Auth::guard('sanctum')->user()->id;

                //$mission = Mission::select('id')->whereId($city_details['mission'])->first();
                


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

    public function updateCity(array $city_details , City $city ) 
    {

                    
                    try {
                        
                //     DB::beginTransaction();

                if (City::select('id')->where('name' ,  $city_details['name'])
                ->where('code','!=',$city_details['city'])
                ->count()) {
                            
                    return [
                            'status'=>false,
                            'errors'=> ["name" => ["Cette ville existe deja"]]
                        ];

                }/**/
                    
                    //$city= City::find($city_details['city']);    
                    
                    
            /* if (Reception::where('name', $city_details['name'])->first()) {
                        
                        return [
                                'status'=>false,
                                'errors'=> ["name" => ["Un fournisseur avec ce nom existe deja"]]
                            ];

                }*/
                
                $city->name = $city_details['name'];

                //$mission = Mission::select('id')->whereId($city_details['mission'])->first();
                


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


           public function deleteCity(City $city  ) 
    {

                    
                    try {
                        
                     DB::beginTransaction();
                $city->is_active = false;
                $city->save();

              
                    //////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\/////////////////////////////
                    
                   DB::commit();
                    
                    return [
                        'status'=>true,
                        'data'=> $city 
                    ];


                } catch (\Throwable $th) {
                 DB::rollback();
                    throw $th;
                }
                    
       
    }


}
