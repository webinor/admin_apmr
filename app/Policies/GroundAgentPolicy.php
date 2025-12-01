<?php

namespace App\Policies;

use App\Models\GroundAgent;
use App\Models\User\User;
use App\Services\User\UserService;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroundAgentPolicy
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
            
        return $this -> user_service -> verifyPermission("liste_des_chefs_de_vol", [2],$user);
    
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\GroundAgent  $groundAgent
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, GroundAgent $groundAgent)
    {
                return $this -> user_service -> verifyPermission("liste_des_chefs_de_vol", [2],$user);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
                return $this -> user_service -> verifyPermission("liste_des_chefs_de_vol", [1],$user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\GroundAgent  $groundAgent
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, GroundAgent $groundAgent)
    {
                return $this -> user_service -> verifyPermission("liste_des_chefs_de_vol", [3],$user);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\GroundAgent  $groundAgent
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, GroundAgent $groundAgent)
    {
                return $this -> user_service -> verifyPermission("liste_des_chefs_de_vol", [4],$user);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\GroundAgent  $groundAgent
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, GroundAgent $groundAgent)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\GroundAgent  $groundAgent
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, GroundAgent $groundAgent)
    {
        //
    }
}
