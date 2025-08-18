<?php

namespace App\Policies;

use App\Models\Misc\Invoice;
use App\Models\Operations\Folder;
use App\Models\User\User;
use App\Services\User\UserService;
use Illuminate\Auth\Access\HandlesAuthorization;

class FolderPolicy
{
    use HandlesAuthorization;

    protected  $user_service;

    public function __construct(UserService $user_service) {
        $this->user_service = $user_service;
    }
    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
       
         return $this -> user_service -> verifyPermission(5, [2],$user);
        
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Folder $folder)
    {
        

     
        //dd($user->isValidator());
        
        if ($user->isAdministrator()) {
            return true;
        }

        if ($user->isExtractor() && $folder->save_at == null) {
          
            return  $this -> user_service -> verifyPermission(5, [2],$user);
        } 
        
        if ($user->isValidator() && $folder->save_at != null) {
          
           // dd($folder->seen_by_anyvalidator()->count());
          //  if (!$folder->seen || ($folder->seen && $folder->seen->user_id == session('user')->id))

            return $folder->seen|| ($folder->seen_by_anyvalidator()->count()==0)  /**/;
          //  return  $this -> user_service -> verifyPermission(5, [2],$user);
        }


        return  false;


    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        
        return  $this -> user_service -> verifyPermission(5, [1],$user);
        
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Folder $folder)
    {
        
        
        /*if ($user->isExtractor() && $user->id == $folder->user->id) {
            return true;
        }

        if ($user->isValidator() && $folder->save_at != null) {
          
            return  $this -> user_service -> verifyPermission(5, [3],$user);
        }
            
        return false;//$this -> user_service -> verifyPermission(8, [3],$user);
*/

        if ($user->isAdministrator()) {
            return true;
        }

        if ($user->isExtractor() && $folder->save_at == null) {
          
            return  $this -> user_service -> verifyPermission(5, [3],$user);
        }

        
        if ($user->isValidator() && $folder->save_at != null) {
          
            return  $this -> user_service -> verifyPermission(5, [3],$user);
        }


        return  false;

    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Folder $folder)
    {
        if ($user->isAdministrator()) {
            return true;
        }

        if ($user->isExtractor() && $folder->save_at == null) {
          
            return  $this -> user_service -> verifyPermission(5, [3],$user);
        }
 
        
        if ($user->isValidator() && $folder->save_at != null) {
          
            return  $this -> user_service -> verifyPermission(5, [3],$user);
        }


        return  false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Folder $folder)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Folder $folder)
    {
        //
    }
}
