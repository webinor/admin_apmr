<?php

namespace App\Policies;

use App\Models\Operations\Slip;
use App\Models\User\User;
use App\Services\User\UserService;
use Illuminate\Auth\Access\HandlesAuthorization;

class SlipPolicy
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
     * @param  \App\Models\Slip  $slip
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Slip $slip)
    {
        return $this -> user_service -> verifyPermission(5, [2],$user);

    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $this -> user_service -> verifyPermission(5, [1],$user);

    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Slip  $slip
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Slip $slip)
    {
        return $this -> user_service -> verifyPermission(5, [3],$user);
        
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Slip  $slip
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Slip $slip)
    {
       // return true;

        if ($user->isAdministrator()) {
            return true;
        }

        if ($user->isExtractor() && $slip->user_id == $user->id) {
          
           // dd($this -> user_service -> verifyPermission(5, [4],$user));
        
            return  $this -> user_service -> verifyPermission(5, [4],$user);
        }
 
        
        if ($user->isValidator() ) {
          
            return  $this -> user_service -> verifyPermission(5, [4],$user);

        }


        return  false;

    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Slip  $slip
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Slip $slip)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Slip  $slip
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Slip $slip)
    {
        //
    }
}
