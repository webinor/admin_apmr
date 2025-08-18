<?php

namespace App\Services\User;

use Carbon\Carbon;
use App\Models\User\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class RegisterUserService
{

  

    
    
    public function execute(array $user_data) : Array
    {

       

        try {


            if (User::whereEmail($user_data['email'])->exists()) {
                
                return [
                    'status'=>false,
                    'errors'=> ["email" => ["Cette adress email est deja utilisée, utilisez une autre."]]
                ];
                
            }


            $user= User::create( [
                'code'=>Str::random(10),
                'employee_id'=>$user_data['employee'],
                'email'=>$user_data['email'],
                'defined_token' => Str::random(40),
               'defined_token_expire_in'=>  Carbon::now()->addDay(),
               'phone_number'=>$user_data['phone_number'],
               'password'=>Hash::make(str::random(30))
      
            ]); 
     
    
          
       
       return [
           'status'=>true,
           'user'=>$user
       ];

    } catch (\Throwable $th) {
      //  DB::rollback();
        throw $th;
    }
    }






}
