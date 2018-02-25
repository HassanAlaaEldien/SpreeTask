<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentsPolicy
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
     * Handles the ability to list all comments.
     *
     * @param User $user
     * @return bool
     */
    public function listComments(User $user)
    {
        return $user->hasRole('Admin');
    }

    /**
     * Handles the ability to create comment.
     *
     * @param User $user
     * @return bool
     */
    public function createComment(User $user)
    {
        return $user->hasRole('Admin') || $user->hasRole('User');
    }

    /**
     * Handles the ability to update comment.
     *
     * @param User $user
     * @return bool
     */
    public function updateComment(User $user)
    {
        return $user->hasRole('Admin') || $user->hasRole('User');
    }

    /**
     * Handles the ability to approve comment.
     *
     * @param User $user
     * @return bool
     */
    public function approveComment(User $user)
    {
        return $user->hasRole('Admin');
    }

    /**
     * Handles the ability to delete Comment.
     *
     * @param User $user
     * @return bool
     */
    public function deleteComment(User $user)
    {
        return $user->hasRole('Admin');
    }

    /**
     * Handles the ability to list all approved Comment.
     *
     * @param User $user
     * @return bool
     */
    public function getApprovedComments(User $user)
    {
        return $user->hasRole('Admin') || $user->hasRole('User');
    }
}
