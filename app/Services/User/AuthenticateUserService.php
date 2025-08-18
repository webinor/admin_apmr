<?php

namespace App\Services\User;

use App\Models\Settings\Setting;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use App\Services\Misc\CacheService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthenticateUserService
{



  public function check_remote_user($credentials)  {
        
  //  $username = $credentials["username"];
  //  $password = $credentials["password"];
  
  
  //  $response = Http::post("https://pass24api.insighttelematics.tn/pass24/login?username=$username&password=$password");
  
   /* $response = Http::get('https://pass24api.insighttelematics.tn/pass24/login', [
      'username' => $username,
      'password' => $password,
  ]);*/
  
  //dd($response->json()["success"]);
  // Récupération du CookieJar (GuzzleHttp\Cookie\CookieJar)
//  $cookieJar = $response->cookies();
  
  // Trouver le cookie par nom (ex: 'session_id')
 // $sessionCookie = collect($cookieJar->toArray())->firstWhere('Name', '.ASPXAUTH');
  
 /* if ($sessionCookie) {
  
      $cookieName = $sessionCookie['Name'];
      $cookieValue = $sessionCookie['Value'];
  
      // Sauvegarder
      // Stocker le cookie en session
  session([$cookieName => encrypt($cookieValue)]);
  }*/
  
    return $response->json()["success"];
  
      
   
  }


public function getApiToken($credentials)  {
        
  $username = $credentials["username"];
  $password = $credentials["password"];


  $response = Http::post("https://pass24api.insighttelematics.tn/api/v1/pass24/createToken",[
    'username' => $username,
    'password' => $password,
  ]);

 /* $response = Http::get('https://pass24api.insighttelematics.tn/pass24/login', [
    'username' => $username,
    'password' => $password,
]);*/

if ($response->successful()) {
  $data = $response->json(); // Par exemple ['token' => 'abc123', ...]
  // Tu peux accéder au token par exemple :
  $accessToken = $data['accessToken'];

 // dd($accessToken);

  session(["accessToken" => encrypt($accessToken)]);
} else {

  // En cas d’erreur
  Log::error('Erreur API', [
      'status' => $response->status(),
      'body' => $response->body(),
  ]);
}
    
 
}

public function execute($user_data)  {
        
  return $this->getUserData($user_data);

}

    public function getUserData($login_data) : JsonResponse
    {
       
       
        $credentials = $this->credentials($login_data);
        $reponse = $this->check_user($credentials);
        $user=$reponse['user'];
        $reponse['credentials']=$credentials;
        $error=$reponse['error'];


        if (!$user) {
            return \response()->json([
                'status'=>false,
                'errors'=> ["login" => [$error]]
            ]);
        }

        /*if ($user && !$this->check_remote_user($credentials)) {
          
          return \response()->json([
            'status'=>false,
            'errors'=> ["password" => ["Mot de passe incorrect"]]
        ]);
          
        }/**/

       



       return $this->logUserIn($reponse);


    }


    public function logUserIn($user_checked) : JsonResponse
    {
        

        $credentials = $user_checked['credentials'];



        $remember = false;
        // true;//$request->remember != null ? true : false ;

        if (Auth::attempt($credentials , $remember)) {

          $user=$user_checked['user'];
          $to = $user->isExtractor() ? url('/') : url("/");

            //$apiToken =$this->getApiToken($credentials);
         
            return $this->setSession($user_checked , redirect()->intended($to)->getTargetUrl());

        }
 
        return \response()->json([
            'status'=>false,
            'errors'=> ["password" => ["Mot de passe incorrect"]]
        ]);


    }

    public function setSession($user_checked , $target  ) : JsonResponse
    {

        $credentials = $user_checked['credentials'];
        $user=$user_checked['user'];

       // Auth::logoutOtherDevices($credentials['password']);

                
        $token =  $user->createToken('MyAuthApp')->plainTextToken; 
        session()->regenerate();
        session()->put(['user'=>$user,
      //  'currency' => Setting::where("key" , "preferedCurrency")->first()->value
      ]);

        

        $this->generateSideBar($user);


        $redirect = \response()->json([
            'status'=>true,
            'token'=>$token,
            'redirect'=>$target
        ]);
    
    


    return $redirect ;

    }




    /**
   * Get the needed authorization credentials from the request.
   *
   * @param  array fields
   * @return array
   */
  public function credentials($fields)
  {
    if (array_key_exists("password",$fields)) {
   
    if(is_numeric($fields['login'])){
      return ['phone_number'=>$fields['login'],'password'=>$fields['password']];
    }
    elseif (filter_var($fields['login'], FILTER_VALIDATE_EMAIL)) {
      return ['email' => $fields['login'], 'password'=>$fields['password']];
    }
    return ['username' => $fields['login'], 'password'=>$fields['password']];

  }
  else{

    if(is_numeric($fields['login'])){
      return ['phone_number'=>$fields['login'],];
    }
    elseif (filter_var($fields['login'], FILTER_VALIDATE_EMAIL)) {
      return ['email' => $fields['login'], ];
    }
    return ['username' => $fields['login'], ];

  }

  }


    /**
   * check if user exists with the given credentials.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function check_user($credentials)
  {

    $error='';
    
  if (array_key_exists('email',$credentials)) {
            
    $user = User::select("id","code", "employee_id" , "email" , "phone_number")
->whereEmail($credentials['email'])
->with('login_details')
->with('employee:id,code,first_name,last_name,role_id')
->with('employee.role:id,role_name,role_slug')
->first();

$error= "Cette Adresse email n'est pas enregistrée sur ce site";
   
  } else if(array_key_exists('phone_number',$credentials)) {
   
    $user = User::select("id","code", "employee_id" , "email" , "phone_number")
->wherePhoneNumber($credentials['phone_number'])
//->whereCountryCode("237")
->with('login_details')
->with('employee:id,code,first_name,last_name')
->with('employee.role:id,role_name,role_slug')

->first();

$error = "Ce contact téléphonique n'est pas enregistré sur ce site";
   
  }
  else{

    $user = User::select("id","code", "employee_id" , "email" , "phone_number")
    ->whereUsername($credentials['username'])
    ->with('login_details')
    ->with('employee:id,code,first_name,last_name')
->with('employee.role:id,role_name,role_slug')

    ->first();
    
    $error = "Nom d'utilisateur introuvable";

  }

  return ["user"=> $user , "error"=>$error];

}


public function generateSideBar(User $user )
{


   $cache_service = new CacheService();

   $distincts_actions = $cache_service->getDistinctsActions($user)['distincts_actions']; /*ActionMenuUser::select('action_menu_users.menu_id')->join('actions' , 'actions.id' , 'action_menu_users.action_id')
    ->join('menus' , 'menus.id' , 'action_menu_users.menu_id')
    ->where('user_id', $user->id)->distinct()->get();/**/

   // dd($distincts_actions); 


 // return  dd($cache_service->getDistinctsActions($user));

  //  dd($distincts_actions);
   $distincts_actions_map = [];
   foreach ($distincts_actions as $value) {
    array_push($distincts_actions_map ,$value->menu_id);
   }

   $unread_messages = [];// $cache_service->getUnreadMessages($user)['unread_messages'];
   

   $menus = $cache_service->getMenus($user)['menus']; 

   
   // dd($distincts_actions_map); 

  
  
  /*$request->/**/session()->put(['user'=>$user,'unread_messages'=> $unread_messages ,'menus'=> $menus, 'distincts_actions_map'=>$distincts_actions_map]);
  //  session(['menus'=> $menus, 'distincts_actions_map'=>$distincts_actions_map]);
 // dd(session('menus'));

}







}
