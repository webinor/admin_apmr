<?php

namespace App\Http\Controllers\User;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Events\NewUserRegistered;
use App\Services\User\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Notifications\SendVerificationCode;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Requests\User\DefinePasswordRequest;
use App\Http\Requests\User\UpdateCredentialRequest;
use App\Http\Requests\User\VerificationCodeRequest;
use App\Http\Requests\CompleteUserRegistrationRequest;

class UserController extends Controller
{

    
  protected  $user_service;

    public function __construct(UserService $user_service) {
        $this->user_service = $user_service;
    }


    public function showDefinePassword(User $user, $token)
    {


        if ($user->defined_token != $token) {
            abort(403);
        }
        
       // dd($user);
        return view('auth.define_password' , compact('user' , 'token'));
    }

        /**
         * Store a newly created resource in storage.
         *
         * @param  \App\Http\Requests\User\DefinePasswordRequest  $request
         * @return \Illuminate\Http\Response
         */

         public function definePassword(DefinePasswordRequest $request)
         {
     
           $response =  $this->user_service->definePassword($request->validated());
           
           return $response;
     
         }

    public function reset(ResetPasswordRequest $request)
    { 

      $response =  $this->user_service -> resetPassword($request->validated());

      return $response;
    }

    
    public function verify_account(User $user)
    {

        $verification_code = rand(100000,999999) ;//app()->isProduction() ? rand(100000,999999) : 111111;

        $user->verification_code = $verification_code;

        $user->save();

        $user->notify(new SendVerificationCode($verification_code));
     
        return view("auth.verify-account", compact("user"));
        
        
    }

     public function update_credential(UpdateCredentialRequest $request, UserService $user_service)
    {

      $response =  $user_service->updateCredential($request->validated());

      return $response;
    }



    public function store_verify(VerificationCodeRequest $request , UserService $userService)
    {

 

        return $userService->getFinalVerifyParams($request->validated() , $request->userAgent()); ;
       

    }

    

}
