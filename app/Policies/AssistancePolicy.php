<?php

namespace App\Policies;

use App\Models\Operations\Assistance;
use App\Models\User\User;
use App\Services\User\UserService;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssistancePolicy
{
    use HandlesAuthorization;


    protected $user_service;

    public function __construct(UserService $user_service)
    {
        $this->user_service = $user_service;

        // $this->authorizeResource(Company::class, "employee");
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
                return $this->user_service->verifyPermission("liste_des_apmr_signées", [2],$user);

    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Operations\Assistance  $assistance
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Assistance $assistance)
    {
                return $this->user_service->verifyPermission("liste_des_apmr_signées", [2],$user);
        
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Operations\Assistance  $assistance
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Assistance $assistance)
    {
                return $this->user_service->verifyPermission("liste_des_apmr_signées", [3],$user);
        
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Operations\Assistance  $assistance
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Assistance $assistance)
    {
        return $this->user_service->verifyPermission("liste_des_apmr_signées", [4],$user);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Operations\Assistance  $assistance
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Assistance $assistance)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Operations\Assistance  $assistance
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Assistance $assistance)
    {
        //
    }
}
