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
use App\Notifications\InviteUserToSetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class GroundAgentService  implements
//IndexVariablesResponder,
CreateVariablesResponder,
ShowVariablesResponder,
EditVariablesResponder,
ViewsResponder {

    use DataConstructor , FileUploadHandler  ;


    
    function getIndexVariables($results)
    {
       

      //  /** @var \Illuminate\Pagination\LengthAwarePaginator $ground_agents */
        $ground_agents = GroundAgent::
        //has('company.city')
        with('company.city')->oldest()
        ->paginate($results)
        ->appends(request()->query());
        //->withQueryString();

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


                if ($request->hasFile('file')) {
                    $fileName = $request->token . '_' . time() . '.'. $request->file->extension();  
        
                    $type = $request->file->getClientMimeType();
                    $size = $request->file->getSize();
            
                    $request->file->move(storage_path('app/public/ground_agents_images'), $fileName);
                   // $request->file->store(storage_path('files'), $fileName);
                   /*File::create([
                        'user_id' => auth()->id(),
                        'slug' => $fileName,
                        'type' => $type,
                        'size' => $size
                    ]);*/
                   
                    $ground_agent->image_path= $fileName;
                    $ground_agent->image_size = $size;
                    $ground_agent->image_type = $type;
                 
            
                }
                


                    $ground_agent->save();


    $key = Hash::make("123456");


        
        $sign = KeySign::create([
            'code'=>Str::random(20),
          //  'model_type'=>(GroundAgent::class),
            'model_type'=>("App\Models\Operations\GroundAgent"),
            'model_id'=>$ground_agent->id,
            'key'=>$key,
            'hash'=>Hash::make($ground_agent->id.$key)
        ]);
        
    
        $token = Str::random(30);// Password::broker()->createToken($ground_agent);
        $link = env('APMR_URL').'/ground-agent-reset-password?token=' . $token . '&ground-agent=' . urlencode($ground_agent->code);
        
        $ground_agent->notify(new InviteUserToSetPassword($link));
            
                
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

    public function update(array $details , GroundAgent $ground_agent , Request $request ) 
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


                if ($request->hasFile('file')) {
                    $fileName = $request->token . '_' . time() . '.'. $request->file->extension();  
        
                    $type = $request->file->getClientMimeType();
                    $size = $request->file->getSize();
            
                    $request->file->move(storage_path('app/public/ground_agents_images'), $fileName);
                   // $request->file->store(storage_path('files'), $fileName);
                   /*File::create([
                        'user_id' => auth()->id(),
                        'slug' => $fileName,
                        'type' => $type,
                        'size' => $size
                    ]);*/
                   
                    $ground_agent->image_path= $fileName;
                    $ground_agent->image_size = $size;
                    $ground_agent->image_type = $type;
                 
            
                }

                    $ground_agent   ->save();



             
            
                
                /*   return [
                        'status'=>true,
                        'data'=> [$ground_agent ] 
                    ];*/


                    //////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\/////////////////////////////
                    
                //   DB::commit();
                    
                    return [
                        'status'=>true,
                        'data'=> $ground_agent   ,
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


           public function deleteGroundAgent(GroundAgent $ground_agent  ) 
    {

                    
                    try {
                        
                     DB::beginTransaction();
                $ground_agent->is_active = false;
                $ground_agent->save();

              
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


}
