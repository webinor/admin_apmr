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
use App\Models\Registrator;
use App\Notifications\InviteUserToSetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class RegistratorService  implements
//IndexVariablesResponder,
CreateVariablesResponder,
ShowVariablesResponder,
EditVariablesResponder,
ViewsResponder {

    use DataConstructor , FileUploadHandler  ;


 
    
    function getIndexVariables()
    {
       

        /*$registrators = Registrator::oldest()
        ->paginate(20)
        ->withQueryString();*/

        $registrators = Registrator::select('*')
    ->selectRaw("CONCAT(name, ' ', last_name) AS full_name")
    ->orderBy('full_name')
    ->paginate(20)
    ->withQueryString();


        $vars = compact("registrators");

        return $vars;
    }
    function getCreateVariables()
    {
        $cities = City::get();
        $companies = Company::get();
        $registrator = null;
        $action = "create";
        $disabled = "";
        $readonly = "";

     

        return compact(
            "cities",
            "registrator",
            "companies",
            "action",
            "disabled",
            "readonly",
        );
    }
    function getShowVariables($folder)
    {
    }
    function getEditVariables($registrator)
    {
        $registrator->load(["city"]);
        $cities = City::get();

        $action = "update";
        $disabled = "";
        $readonly = "";

        return compact(
            "registrator",
            "action",
            "disabled",
            "readonly",  
            "cities"
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
    
    public function create(array $details , Request $request ) 
    {

                    
                    try {
                        
                DB::beginTransaction();

                /*if (GroundAgent::select('id')->where('name' ,  $details['name'])->count()) {
 
                    return [
                            'status'=>false,
                            'errors'=> ["name" => ["Cette ville existe deja"]]
                        ];

                }*/
                    
                        $registrator= new Registrator();    
                        
                        $columns = ['name','last_name','email', ];
                
                        foreach ($columns as  $column) {
                            array_key_exists($column, $details ) ? $registrator->{$column} = $details[$column] : null ;
                        }
                    
            /* if (Reception::where('name', $details['name'])->first()) {
                        
                        return [
                                'status'=>false,
                                'errors'=> ["name" => ["Un fournisseur avec ce nom existe deja"]]
                            ];

                }*/
                
                $registrator->code = Str::random(10);
                $registrator->city_id = City::whereCode($details['city'])->first()->id;
                $registrator->password = Hash::make($details['password']);// Str::random(10);
               // $registrator->admin_id = Auth::guard('sanctum')->user()->id;
                //$mission = Mission::select('id')->whereId($details['mission'])->first();


                if ($request->hasFile('file')) {
                    $fileName = $request->token . '_' . time() . '.'. $request->file->extension();  
        
                    $type = $request->file->getClientMimeType();
                    $size = $request->file->getSize();
            
                    $request->file->move(storage_path('app/public/registrators_images'), $fileName);
                   // $request->file->store(storage_path('files'), $fileName);
                   /*File::create([
                        'user_id' => auth()->id(),
                        'slug' => $fileName,
                        'type' => $type,
                        'size' => $size
                    ]);*/
                   
                    $registrator->image_path= $fileName;
                    $registrator->image_size = $size;
                    $registrator->image_type = $type;
                 
            
                }
                


                    $registrator->save();


    $key = Hash::make("123456");


        
       /* $sign = KeySign::create([
            'code'=>Str::random(20),
          //  'model_type'=>(GroundAgent::class),
            'model_type'=>("App\Models\Operations\GroundAgent"),
            'model_id'=>$registrator->id,
            'key'=>$key,
            'hash'=>Hash::make($registrator->id.$key)
        ]);*/
        
    
        $token = Str::random(30);// Password::broker()->createToken($registrator);
        $link = env('APMR_URL').'/registrator-reset-password?token=' . $token . '&registrator=' . urlencode($registrator->code);
        
        $registrator->notify(new InviteUserToSetPassword($link));
            
                
                /*   return [
                        'status'=>true,
                        'data'=> [$city] 
                    ];*/


                    //////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\/////////////////////////////
                    
                   DB::commit();
                    
                    return [
                        'status'=>true,
                        'data'=> $registrator 
                    ];


                } catch (\Throwable $th) {
                 DB::rollback();
                    throw $th;
                }
                    
       
    }

    public function update(array $details , Registrator $registrator , Request $request ) 
    {

                    
                    try {
                        
                //     DB::beginTransaction();

                
                $columns = ['name','last_name','email', ];
        
                foreach ($columns as  $column) {
                    array_key_exists($column, $details ) ? $registrator->{$column} = $details[$column] : null ;
                }
            
                //$mission = Mission::select('id')->whereId($details['mission'])->first();
                
                $registrator->city_id = City::whereCode($details['city'])->first()->id;
               // $registrator->city_id = City::whereCode($details['city'])->first()->id;


                if ($request->hasFile('file')) {
                    $fileName = $request->token . '_' . time() . '.'. $request->file->extension();  
        
                    $type = $request->file->getClientMimeType();
                    $size = $request->file->getSize();
            
                    $request->file->move(storage_path('app/public/registrators_images'), $fileName);
                   // $request->file->store(storage_path('files'), $fileName);
                   /*File::create([
                        'user_id' => auth()->id(),
                        'slug' => $fileName,
                        'type' => $type,
                        'size' => $size
                    ]);*/
                   
                    $registrator->image_path= $fileName;
                    $registrator->image_size = $size;
                    $registrator->image_type = $type;
                 
            
                }

                    $registrator   ->save();



             
            
                
                /*   return [
                        'status'=>true,
                        'data'=> [$registrator ] 
                    ];*/


                    //////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\/////////////////////////////
                    
                //   DB::commit();
                    
                    return [
                        'status'=>true,
                        'data'=> $registrator   ,
                      //  'link'=>$link
                    ];


                } catch (\Throwable $th) {
                // DB::rollback();
                    throw $th;
                }
                    
       
    }


    function generatePassword($length = 8) {
        $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $specials = '!@#$%^&*()_+-=';
        
        // au moins un de chaque
        $password  = $letters[random_int(0, strlen($letters) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $specials[random_int(0, strlen($specials) - 1)];
    
        // compléter le reste avec un mélange
        $all = $letters . $numbers . $specials;
        for ($i = strlen($password); $i < $length; $i++) {
            $password .= $all[random_int(0, strlen($all) - 1)];
        }
    
        // mélanger pour éviter que l'ordre soit prévisible
        return Str::shuffle($password);
    }

            public function deleteRegistrator(Registrator $registrator  ) 
    {

                    
                    try {
                        
                     DB::beginTransaction();
                $registrator->is_active = false;
                $registrator->save();

              
                    //////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\/////////////////////////////
                    
                   DB::commit();
                    
                    return [
                        'status'=>true,
                        'data'=> $registrator 
                    ];


                } catch (\Throwable $th) {
                 DB::rollback();
                    throw $th;
                }
                    
       
    }


}
