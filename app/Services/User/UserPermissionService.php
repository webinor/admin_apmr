<?php

namespace App\Services\User;

use App\Models\Misc\Menu;
use App\Models\User\User;
use App\Models\User\ActionMenuUser;

class UserPermissionService
{

  

    
    
    public function createPermission(User $user , array $user_data ) : Array
    {

       
 
      //  try {


           
        $action_menu_user_array = [];
        $now = now();
        foreach (json_decode($user_data['authorize_actions']) as $menu) {
          //  dd(explode(':',$menu)[0]);// explode(':',$menu);
            array_push($action_menu_user_array ,
                [
                    'action_id'=>explode(':',$menu)[1],
                    'menu_id'=>explode(':',$menu)[0],
                    'admin_id'=>$user->id,
                    'created_at'=>$now,
                    'updated_at'=>$now,
                ]
            );
        }
        
        ActionMenuUser::insert($action_menu_user_array);
          
       
       return [
           'status'=>true,
           'user'=>$user
       ];

    //} catch (\Throwable $th) {
      //  DB::rollback();
  //      throw $th;
  //  }

    }




    public function verifyPermission($menu, array $actions, User $user): bool
    {


      //  dd($menu);
       // dd(Menu::where('name',$menu)->first());
        $menu = is_numeric($menu) ? $menu : Menu::where('slug',$menu)->first()->id;
        /* */ $distincts_actions =
            /*session('distincts_actions_map') != null 
        ? session('distincts_actions_map')
         : ActionMenuUser::select('action_menu_admins.menu_id')*/
            ActionMenuUser::select("action_menu_admins.id")
                ->join("actions", "actions.id", "action_menu_admins.action_id")
                ->join("menus", "menus.id", "action_menu_admins.menu_id")
                ->whereIn("action_menu_admins.action_id", $actions) //create read update
                ->where("action_menu_admins.menu_id", $menu) //create read update
                ->where("action_menu_admins.admin_id", $user->id) /*->distinct()*/
                ->count();

        //    dd($distincts_actions);

        if ($distincts_actions > 0) {
            return true;
        }
        return false;
    }



}
