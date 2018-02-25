<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NewsPolicy
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
     * Handles the ability to list all news.
     *
     * @param User $user
     * @return bool
     */
    public function listNews(User $user)
    {
        return $user->hasRole('Admin');
    }

    /**
     * Handles the ability to create news.
     *
     * @param User $user
     * @return bool
     */
    public function createNews(User $user)
    {
        return $user->hasRole('Admin') || $user->hasRole('User');
    }

    /**
     * Handles the ability to update news.
     *
     * @param User $user
     * @return bool
     */
    public function updateNews(User $user)
    {
        return $user->hasRole('Admin') || $user->hasRole('User');
    }

    /**
     * Handles the ability to approve user news.
     *
     * @param User $user
     * @return bool
     */
    public function approveNews(User $user)
    {
        return $user->hasRole('Admin');
    }

    /**
     * Handles the ability to delete news.
     *
     * @param User $user
     * @return bool
     */
    public function deleteNews(User $user)
    {
        return $user->hasRole('Admin');
    }
}
