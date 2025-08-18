<?php

namespace App\Services\User;

use App\Models\User\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\HumanResource\Employee;
use App\Notifications\Auth\PasswordRequest;

class UpdateUserService
{

  

    
    
    public function setPassword(array $user_data) : bool
    {


        try {
            DB::beginTransaction();
    
            $password = Hash::make($user_data['password']);
            $affected_row = User::where('defined_token' , $user_data['defined_token'])
            ->update(['password'=>$password] );
     
            
            DB::commit();
    
            return true ;
    
    
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    
      
      
    
       

    
    }


    public function resetPassword($user_data)  {
        

        try {

            //  DB::beginTransaction();
             
          // return 
            $employee = Employee::whereCode($user_data['employee_code'])
            ->has('user')
            ->with('user')
  
            ->first(); 
  
            $user = $employee->user;
  
  
             $user->defined_token = Str::random(40);
             $user->save();
  
         
          
       
    
       $user->notify(new PasswordRequest());
        
        //  DB::commit(); 
         
  
    
          return [
              'status'=>true,
              'user'=> $user
          ];
  
  
      } catch (\Throwable $th) {
         // DB::rollback();
          throw $th;
          return $th;
      }

      

    }



    public function updateUser(array $userDetails , $is_update = true , $request ) : User
    {

        $user= User::whereId($userDetails['user'])->first();

        if (!$is_update) {
            # code...
            $user->password = Hash::make($userDetails['password']);
        }

         $nullbable_coloumns = [    
        "first_name",
         "last_name",
         "birthday",
        "gender",
        "address",
        "phone_number",
      
        ];
 
         foreach ($nullbable_coloumns as  $nullbable_coloumn) {
             array_key_exists($nullbable_coloumn, $userDetails ) ? $user->{$nullbable_coloumn} = $userDetails[$nullbable_coloumn] : null ;
         }


         if ($request->hasFile('file')) {
            //return "okokok";
            $fileName = $request->token . '_' . time() . '.'. $request->file->extension();  

            $type = $request->file->getClientMimeType();
            $size = $request->file->getSize();
    
            $request->file->move(storage_path('app/public/user_images'), $fileName);
           // $request->file->store(storage_path('files'), $fileName);
           /*File::create([
                'user_id' => auth()->id(),
                'slug' => $fileName,
                'type' => $type,
                'size' => $size
            ]);*/
           
           $user->profile_picture= $fileName;
           //$user->image_size = $size;
           //$user->image_type = $type;

          
         
    
        }
 
 
         $user->save();
        
        return $user ;
    }






}
