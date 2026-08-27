<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskReviewPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function delete(User $user, $taskReview): bool
    {
        return $user->id === $taskReview->user_id || $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager', 'employee']);
    }
}
