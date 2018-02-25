<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Handles the authorization of all abilities.
     *
     * @param User $user
     *
     * @return bool|null
     */
    public function before(User $user)
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return null;
    }

    /**
     * Handles the ability to list all users.
     *
     * @param User $user
     * @return bool
     */
    public function listUsers(User $user)
    {
        return $user->hasRole('Admin');
    }

    /**
     * Handles the ability to create user.
     *
     * @param User $user
     * @return bool
     */
    public function createUser(User $user)
    {
        return $user->hasRole('Admin');
    }

    /**
     * Handles the ability to update user info.
     *
     * @param User $user
     * @return bool
     */
    public function updateUserInfo(User $user)
    {
        return $user->hasRole('Admin');
    }

    /**
     * Handles the ability to update user password.
     *
     * @param User $user
     * @return bool
     */
    public function updateUserPassword(User $user)
    {
        return $user->hasRole('Admin');
    }

    /**
     * Handles the ability to update user.
     *
     * @param User $user
     * @return bool
     */
    public function deleteUser(User $user)
    {
        return $user->hasRole('Admin');
    }
}
