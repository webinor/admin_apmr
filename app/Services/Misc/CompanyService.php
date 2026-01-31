<?php

namespace App\Services\Misc ;

use App\Addons\DataConstructor;
use App\Addons\FileUploadHandler;
use App\Addons\Misc\CreateVariablesResponder;
use App\Addons\Misc\EditVariablesResponder;
use App\Addons\Misc\IndexVariablesResponder;
use App\Addons\Misc\ShowVariablesResponder;
use App\Addons\Misc\ViewsResponder;
use App\Models\User;
use App\Jobs\SendSmsJob;
use App\Models\City;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Misc\Resource;
use App\Models\Operations\Mission;
use App\Models\Operations\Step;
use App\Models\Storage\Reception;
use App\Models\Storage\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Storage\StockMovement;
use App\Models\Storage\Productcompany;
use App\Models\Storage\ProductWarehouse;
use App\Models\WheelChair;
use App\Notifications\WelcomeUserNotification;

class CompanyService  implements
//IndexVariablesResponder,
CreateVariablesResponder,
ShowVariablesResponder,
EditVariablesResponder,
ViewsResponder {

    use DataConstructor , FileUploadHandler  ;


 
    function getIndexVariables($results)
    {
       

        $compagnies = Company::oldest()
        ->paginate($results)
        ->withQueryString();

        $vars = compact("compagnies");

        return $vars;
    }
    function getCreateVariables()
    {
        $companies = [];
        $company = null; 
        $action = "create";
        $disabled = "";
        $readonly = "";
        $cities  = City::select('id','code','name')->get();
        $wheel_chairs  = WheelChair::select('id','code','name')->orderBy('name')->get();

     

        return compact(
            "company",
            "wheel_chairs",
            "action",
            "cities",
            "disabled",
            "readonly",
        );
    }
    function getShowVariables($folder)
    {
    }
    function getEditVariables($company)
    {
        $company->load(["city"]);
        $action = "update";
        $disabled = "";
        $readonly = "";
        $cities = City::get();
        $wheel_chairs  = WheelChair::select('id','code','name')->orderBy('name')->get();

        return compact(
            "company",
            "action",
            "disabled",
            "readonly",
            "cities",
            "wheel_chairs"
        );
    }
    function getView($view_name, $vars = [])
    {
        return view($view_name, $vars);
    }
   
    
    public function createCompany(array $details , Request $request ) 
    {

                    
                    try {
                        
                //     DB::beginTransaction();

                if (Company::select('id')->where('name' ,  $details['name'])->count()) {
                            
                    return [
                            'status'=>false,
                            'errors'=> ["name" => ["Cette compagnie existe deja"]]
                        ];

                }
                    
                        $company= new Company();    
                        $columns = ['name','alias','prefix','billing_address','mensual_fee','post_box','uni','rc' , 'email'];
                
                        foreach ($columns as  $column) {
                            array_key_exists($column, $details ) ? $company->{$column} = $details[$column] : $company->{$column} = null ;
                        }
                    
            /* if (Reception::where('name', $details['name'])->first()) {
                        
                        return [
                                'status'=>false,
                                'errors'=> ["name" => ["Un fournisseur avec ce nom existe deja"]]
                            ];

                }*/
                $company->code = Str::random(10);
                
                $company->city_id = City::whereCode($details['city'])->first()->id;
                $company->admin_id = Auth::guard('sanctum')->user()->id;


                if ($request->hasFile('file')) {
                    $fileName = $request->token . '_' . time() . '.'. $request->file->extension();  
        
                    $type = $request->file->getClientMimeType();
                    $size = $request->file->getSize();
            
                    $request->file->move(storage_path('app/public/company_images'), $fileName);
                   // $request->file->store(storage_path('files'), $fileName);
                   /*File::create([
                        'user_id' => auth()->id(),
                        'slug' => $fileName,
                        'type' => $type,
                        'size' => $size
                    ]);*/
                   
                    $company->image_path= $fileName;
                    $company->image_size = $size;
                    $company->image_type = $type;
                 
            
                }


                    $company->save();
            
                
                /*   return [
                        'status'=>true,
                        'data'=> [$city] 
                    ];*/


                    //////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\/////////////////////////////
                    
                //   DB::commit();
                    
                    return [
                        'status'=>true,
                        'data'=> $company 
                    ];


                } catch (\Throwable $th) {
                // DB::rollback();
                    throw $th;
                }
                    
       
    }

    public function updateCompany(array $details , Company $company , Request $request  ) 
    {

                    
                    try {
                        
                //     DB::beginTransaction();

                if (Company::select('id')->where('name' ,  $details['name'])
                ->where('code','!=',$details['company'])
                ->count()) {
                            
                    return [
                            'status'=>false,
                            'errors'=> ["name" => ["Cette compagnie existe deja"]]
                        ];

                }/**/
                    
                       // $company= Company::find($details['company']);    
                    
                    
            /* if (Reception::where('name', $details['name'])->first()) {
                        
                        return [
                                'status'=>false,
                                'errors'=> ["name" => ["Un fournisseur avec ce nom existe deja"]]
                            ];

                }*/
                
                $columns = ['name','alias','prefix','billing_address','mensual_fee','post_box','uni','rc','email'];
    
            foreach ($columns as  $column) {
                            
                array_key_exists($column, $details ) ? $company->{$column} = $details[$column] : $company->{$column} = null ;
                
            }
        


                $company->city_id = City::whereCode($details['city'])->first()->id;

                //$mission = Mission::select('id')->whereId($details['mission'])->first();
                
                if ($request->hasFile('file')) {
                    $fileName = $request->token . '_' . time() . '.'. $request->file->extension();  
        
                    $type = $request->file->getClientMimeType();
                    $size = $request->file->getSize();
            
                    $request->file->move(storage_path('app/public/company_images'), $fileName);
                   // $request->file->store(storage_path('files'), $fileName);
                   /*File::create([
                        'user_id' => auth()->id(),
                        'slug' => $fileName,
                        'type' => $type,
                        'size' => $size
                    ]);*/
                   
                    $company->image_path= $fileName;
                    $company->image_size = $size;
                    $company->image_type = $type;
                 
            
                }


                    $company->save();
            
                
                /*   return [
                        'status'=>true,
                        'data'=> [$city] 
                    ];*/


                    //////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\/////////////////////////////
                    
                //   DB::commit();
                    
                    return [
                        'status'=>true,
                        'data'=> $company 
                    ];


                } catch (\Throwable $th) {
                // DB::rollback();
                    throw $th;
                }
                    
       
    }


    public function deleteCompany(Company $company  ) 
    {

                    
                    try {
                        
                     DB::beginTransaction();
                $company->is_active = false;
                $company->save();

              
                    //////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\/////////////////////////////
                    
                   DB::commit();
                    
                    return [
                        'status'=>true,
                        'data'=> $company 
                    ];


                } catch (\Throwable $th) {
                 DB::rollback();
                    throw $th;
                }
                    
       
    }
    

}
