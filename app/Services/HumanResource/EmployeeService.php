<?php

namespace App\Services\HumanResource ;

use App\Models\User\User;
use Illuminate\Support\Str;;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\HumanResource\Employee;
use App\Models\HumanResource\Role;
use App\Models\Misc\Menu;
use App\Models\User\ActionMenuUser;
use App\Providers\RouteServiceProvider;
use App\Services\Misc\CacheService;

class EmployeeService 
{
    
    public function createEmployee(array $employee_details ) 
    { 

        try {

            DB::beginTransaction();
           
            $employee  = Employee::selectRaw('id,personal_email')
            ->with('user:id,employee_id,email')
            ->where('personal_email', $employee_details['personal_email'])
            ->first();
        
        if ($employee) {
                
            return [
                    'status'=>false,
                    'errors'=> ["personal_email" => ["Cette adresse email est deja associee à un autre collaborateur"]]
                ];

        }/**/

        $employee= Employee::make([
            'code'=>Str::random(15),
            'first_name'=>$employee_details['first_name'],
            'last_name'=>$employee_details['last_name'],
            'personal_email'=>$employee_details['personal_email'],
            'created_by'=>$employee_details['token'],
        ]);

       

        $role  = Role::select('id')->whereId( $employee_details['role'])->first() ;

        if (!$role) {
                
            return [
                    'status'=>false,
                    'errors'=> ["role" => ["Ce role n'existe pas"]]
                ];

        }/**/
        $role->employees()->save($employee);

          array_key_exists('habilitations' , $employee_details) ? 

   $employee->habilitations()->attach(json_decode($employee_details['habilitations']))

    : null ;
 
  //  $employee->notify(new PasswordRequest());
      
        DB::commit();
       

  
        //$user->refresh();
            // dd($user->goal);
        //return $user ;
        return [
            'status'=>true,
            'data'=> ["employee" => $employee]
        ];


    } catch (\Throwable $th) {
        DB::rollback();
        throw $th;
        //return $th;
        /* [
            'status'=>false,
            'errors'=> ["name" => $th]
        ];*/
    }

       
    }

    public function updateEmployee(array $employee_details ) 
    { 

        try {

            DB::beginTransaction();
           
            $other_employee  = Employee::selectRaw('id,personal_email')
            ->with('user:id,employee_id,email')
            ->where('personal_email', $employee_details['personal_email'])
            ->where('id', '!=' , $employee_details['employee'])
            ->first();
        
        if ($other_employee) {
                
            return [
                    'status'=>false,
                    'errors'=> ["personal_email" => ["Cette adresse email est deja associee à un autre collaborateur"]]
                ];

        }/**/

        $columns= [
            'first_name',
            'last_name',
            'personal_email',
            'main_phone_number',
            'address'
        ];

        $employee = Employee::find($employee_details['employee']);
        
        foreach ($columns as  $column) {
            array_key_exists($column, $employee_details ) ? $employee->{$column} = $employee_details[$column] : null ;
        }
        
        
        
        $role  = Role::select('id')->whereId( $employee_details['role'])->first() ;

        if (!$role) {
                
            return [
                    'status'=>false,
                    'errors'=> ["role" => ["Ce role n'existe pas"]]
                ];

        }
        $employee -> role_id = $employee_details['role'] ;
     //   return $employee;
       $role->employees()->save($employee);

          array_key_exists('habilitations' , $employee_details) ? 

   $employee->habilitations()->sync(json_decode($employee_details['habilitations']))

    : null ;
 
  //  $employee->notify(new PasswordRequest());
      
        DB::commit();
       

  
        //$user->refresh();
            // dd($user->goal);
        //return $user ;
        return [
            'status'=>true,
            'data'=> ["employee" => $employee]
        ];


    } catch (\Throwable $th) {
        DB::rollback();
        throw $th;
       return $th;
       /* [
            'status'=>false,
            'errors'=> ["name" => $th]
        ];*/
    }

       
    }

  //  public function generateSideBarVar(User $user)
  public function generateSideBarVar(User $user , $request  )
  {


     $cache_service = new CacheService();



 

      $distincts_actions = $cache_service->getDistinctsActions($user)['distincts_actions']; /*ActionMenuUser::select('action_menu_users.menu_id')->join('actions' , 'actions.id' , 'action_menu_users.action_id')
      ->join('menus' , 'menus.id' , 'action_menu_users.menu_id')
      ->where('user_id', $user->id)->distinct()->get();/**/

   // return  dd($cache_service->getDistinctsActions($user));

     $distincts_actions_map = [];
     foreach ($distincts_actions as $value) {
      array_push($distincts_actions_map ,$value->menu_id);
     }

     
    // $menus = Menu::select("id", "menu_name" ,"menu_slug"  , "menu_icon")
     $menus = $cache_service->getMenus($user)['menus']; 
     //Redis::del('distincts_actions_' . $user->id);
    // Redis::del('menus_' . $user->id);
    // Redis::del('menus');

   //  dd($cache_service->getMenus($user)['menus'][0]->submenus);
    // dd($menus[0]);
    
     
     $request->session()->put(['user'=>$user, 'menus'=> $menus, 'distincts_actions_map'=>$distincts_actions_map]);
    //  session(['menus'=> $menus, 'distincts_actions_map'=>$distincts_actions_map]);

  }

  public function definePassword($request )
    {
        try {
            DB::beginTransaction();
        
         $employee_details = $request->validated();

       //  dd(User::where('defined_token' , $employee_details['defined_token'])->first());
        $password = Hash::make($employee_details['password']);
          $affected_row = User::where('defined_token' , $employee_details['defined_token'])
          ->update(['password'=>$password] );
          DB::commit();

        //  $user = User::where('email',$fields['email'])->first();
          if ($affected_row!=1) {
            $response = \response()->json([
                  'status'=>false,
                  'errors'=> ["email" => ["Une erreur s'est produite lors de la modification veuillez ressayer"]]
              ]);/**/
          }
         
          else{ 
         
          if(Auth::attempt(['email' => $employee_details['email'], 'password' => $employee_details['password']],false)){
              
            $user = User::select("id" ,"first_name","last_name","email" ,"profile_url" ,"role_id")
            ->where('email' , $employee_details['email'] )->first();

            

              $request->session()->regenerate();
  
             // $user_service->generateSideBarVar($user);
              $this->generateSideBarVar($user , $request);
   
              //return redirect()->intended(RouteServiceProvider::HOME);
  
            /*  $admin = User::where('email' , 'marcel@shiftechnologies.com')->get();
              if ($user->email != 'marcel@shiftechnologies.com') {
              //$admin->notify(new UserLogin($user));
              Notification::send($admin,new UserLogin($user));
              }*/
  
              $response = \response()->json([
                  'status'=>true,
                  'redirect'=>url(RouteServiceProvider::HOME)
              ]);/**/
  
          }
          else{
  
              /*return back()->withErrors([
                  'email' => 'Email ou mot de passe incorrect',
              ]);*/
  
              $response= \response()->json([
                  'status'=>false,
                  'errors'=> ["password" => ["une erreur est survenue lors de la tentative de connexion"]]
              ]);/**/
    }
    
          }

          return $response ;


} catch (\Throwable $th) {
    DB::rollback();
    throw $th;
}
         
   
}

    public static function verifyPermission( $menu , Array $actions ,User $user) : bool
    {
       /* */$distincts_actions = /*session('distincts_actions_map') != null 
        ? session('distincts_actions_map')
         : ActionMenuUser::select('action_menu_users.menu_id')*/
        ActionMenuUser::select('action_menu_users.id')
       ->join('actions' , 'actions.id' , 'action_menu_users.action_id')
       ->join('menus' , 'menus.id' , 'action_menu_users.menu_id')
       ->where('action_id', $actions)//create read update
       ->where('action_menu_users.menu_id', $menu)//create read update
       ->where('user_id', $user->id)/*->distinct()*/->count();

       if ($distincts_actions > 0) {
        return true ;
     }
     return false;
      // dd(session('distincts_actions_map'));
       dd($distincts_actions);

       $submenus=Menu::select('menu_link')->WhereIn('id', $distincts_actions)->get();
      
      //  dd($submenus);
      
      foreach ($submenus as $submenu) {

       if (Str::contains(url()->current(), $submenu->menu_link)) {
          return true ;
       }
    }

    return false;

}

}
