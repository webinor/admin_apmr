<?php

namespace App\Policies;

use App\Models\Operations\Provider;
use App\Models\User\User;
use App\Services\User\UserService;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProviderPolicy
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
        return $this -> user_service -> verifyPermission(2, [2],$user);

    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Operations\Provider  $provider
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Provider $provider)
    {
        return $this -> user_service -> verifyPermission(2, [2],$user);

    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $this -> user_service -> verifyPermission(2, [1],$user);

    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Operations\Provider  $provider
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Provider $provider)
    {
        return $this -> user_service -> verifyPermission(2, [3],$user);

    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Operations\Provider  $provider
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Provider $provider)
    {
        return $this -> user_service -> verifyPermission(2, [4],$user);

    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Operations\Provider  $provider
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Provider $provider)
    {

    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Operations\Provider  $provider
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Provider $provider)
    {

    }
}
