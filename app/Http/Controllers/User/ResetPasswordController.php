<?php

namespace App\Http\Controllers\User;

use App\Models\User\User;
use App\Services\User\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\DefinePasswordRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;

class ResetPasswordController extends Controller
{
   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( User $user)
    {
        $user_params = $user->getResetPasswordParams();

        return view('common.reset-password', $user_params);
    }

    /**
     * reset a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reset(ResetPasswordRequest $request,  UserService $user_service)
    {
        $credentials = $user_service->credentials($request->validated());

        $reponse = $user_service->check_user($credentials);
        $user=$reponse['user'];
        $error=$reponse['error'];
        if ($user) {
            
         return    $user_service->notify_user($user);

        }
        
        return [
            'status'=>false,
            'errors'=> ["login" => [$error]
            ]
        ];

       
         
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($code, $token , User $user)
    {
        $user_params = $user->getDefinePasswordParams($code, $token );

        return view('common.define-password', $user_params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DefinePasswordRequest $request, UserService $user_service)
    {
        $fields = $request->validated();

       
       
        $credentials = $user_service->credentials($fields);
        $user= $user_service->check_user($credentials)['user'];
        

         if(!$user) {
        
        
             $response = \response()->json([
                   'status'=>false,
                   'errors'=> ["login" => ["Une erreur s'est produite lors de la modification veuillez ressayer"]]
               ]);
           }


           $user->password = Hash::make($fields['password']);
           $user->save();

           
          
           if(Auth::attempt($credentials,true)){
               
            Auth::logoutOtherDevices($credentials['password']);
 
                    
               $token =  $user->createToken('MyAuthApp')->plainTextToken; 
                $request->session()->regenerate();
               $request->session()->put(['user'=>$user]);

               $user_service->generateSideBar($user , $request);

      
              $response = \response()->json([
                  'status'=>true,
                  'token'=>$token,
                  'redirect'=>url(RouteServiceProvider::HOME)
               ]);
   
           }
           else{
   
              
               $response = \response()->json([
                   'status'=>false,
                   'errors'=> ["password" => ["une erreur est survenue lors de la tentative de connexion"]]
               ]);
     }
     
           return  $response ;
    }

  
}
