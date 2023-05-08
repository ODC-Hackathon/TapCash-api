<?php

namespace App\Policies;

use App\Models\FamilyMember;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Response;

class NotificationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
        return $user InstanceOf User? Response::allow() : Response::denyAsNotFound();
        // return $user InstanceOf FamilyMember ? Response::allow() : Response::denyAuthorized();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserNotification  $userNotification
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, UserNotification $userNotification)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserNotification  $userNotification
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, UserNotification $userNotification)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserNotification  $userNotification
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, UserNotification $userNotification)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserNotification  $userNotification
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, UserNotification $userNotification)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserNotification  $userNotification
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, UserNotification $userNotification)
    {
        //
    }
}
