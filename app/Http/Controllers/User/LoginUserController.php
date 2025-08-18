<?php

namespace App\Http\Controllers\User;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Services\User\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\User\LoginUserRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Requests\User\DefinePasswordRequest;

class LoginUserController extends Controller
{
   
    protected  $user_service;

    public function __construct(UserService $user_service) {
        $this->user_service = $user_service;
    }
        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index(Request $request , User $user)
        {
           
            $user_params = $user->getLoginParams($request);
            
            return view('auth.login',$user_params);
    
        }
    
        function logout()
        {
        Session::flush();
    
        Auth::logout(); // logging out user
        
        return Redirect::to('login'); // redirection to login screen

        }


        public function showDefinePassword( $code, $token , User $user)
        {
    
            $user_params = $user->getDefinePasswordParams($code, $token );
            return view('authentication.define_password' , $user_params);
        }

        public function showResetPassword(User $user)
        {
          

            $user_params = $user->getResetPasswordParams();

            return view('authentication.reset_password', $user_params);


        }

        public function resetPassword(ResetPasswordRequest $request)
        {
         

            return  $this->user_service->getUser($request->validated());

    
        }

 
    
        /**
         * Store a newly created resource in storage.
         *
         * @param  \App\Http\Requests\User\LoginUserRequest  $request
         * @return \Illuminate\Http\Response
         */
        public function authenticate(LoginUserRequest $request)
        {
          
           $response =  $this->user_service->authenticateUser($request->validated());


           return $response;

           

            
        
        }
    
    }
    