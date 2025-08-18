<?php

namespace App\Services\Misc ;

use App\Models\Misc\Menu;
use App\Models\User\ActionMenuUser;
use App\Models\User\User;
use Illuminate\Support\Facades\Redis;

class CacheService 
{
    
  

  //  public function generateSideBarVar(User $user)
  public function getDistinctsActions(User $user )
  {

    Redis::del('distincts_actions_' . $user->id);
    $cached_action = Redis::get('distincts_actions_'.$user->id);

    //dd(($cached_action));

  if(isset($cached_action)) {

    $distincts_actions = json_decode($cached_action, FALSE);

   // dd($distincts_actions);
      return [
          'status_code' => 201,
          'message' => 'Fetched from redis',
          'distincts_actions' => $distincts_actions,
      ];
  }else {

    
    $distincts_actions = ActionMenuUser::select('action_menu_admins.menu_id')->join('actions' , 'actions.id' , 'action_menu_admins.action_id')
    ->join('menus' , 'menus.id' , 'action_menu_admins.admin_id')
    ->where('admin_id', $user->id)
    ->distinct()
    ->get();
 

     //dd($distincts_actions);

      Redis::set('distincts_actions_'.$user->id, ($distincts_actions));

      return [
          'status_code' => 201,
          'message' => 'Fetched from database',
          'distincts_actions' => $distincts_actions,
      ]; 
  }/**/


 
       
  }


  public function getMenus()
  {


    $cached_menu = Redis::get('menus');


   // Redis::del('menus');

  //  dd(($cached_menu));



  if(isset($cached_menu)) {
      $menus = json_decode($cached_menu, false);

      return [
          'status_code' => 201,
          'message' => 'Fetched from redis',
          'menus' => $menus,
      ];
  }else {
      
   //  $menus = Menu::where('admin_id' , null)->with('submenus')->get();

     $menus = Menu::where('menu_id' , null)
    ->where('is_visible' , true)
    
    ->with(['submenus'=> function ($query) {
        // $query->selectRaw('id,detailed_commercial_quote_id,sub_detailed_commercial_quote_id,slug,price,observation');
       //  $query->orderBy('position', 'Asc');
         $query->where('is_visible' , true);
     }])
    ->get();
      

      Redis::set('menus', ($menus->toJson()));

      return [
          'status_code' => 201,
          'message' => 'Fetched from database',
          'menus' => $menus,
      ]; 
  }/**/


 
       
  }







}
