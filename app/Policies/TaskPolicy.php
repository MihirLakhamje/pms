<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    private function isProjectCreator(User $user, Task $task): bool
    {
        return $task->project->created_by === $user->id;
    }

    private function isProjectAssigned(User $user, Task $task): bool
    {
        return $task->project->users()->where('user_id', $user->id)->exists();
    }

    private function isTaskAssigned(User $user, Task $task): bool
    {
        return $user->role === 'employee' && $task->assignee_id === $user->id;
    }

    // --------------------------
    // VIEW ANY (task list page)
    // --------------------------
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    // --------------------------
    // VIEW SINGLE TASK
    // --------------------------
    public function view(User $user, Task $task): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'manager') {
            return $this->isProjectCreator($user, $task)
                || $this->isProjectAssigned($user, $task);
        }

        // employee
        return $this->isTaskAssigned($user, $task);
    }

    public function isAssigned(User $user, Task $task): bool
    {
        return $user->role === 'employee' && $task->assignee_id === $user->id;
    }

    // --------------------------
    // CREATE TASK
    // --------------------------
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    // --------------------------
    // UPDATE TASK
    // --------------------------
    public function update(User $user, Task $task): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'manager') {
            return $this->isProjectCreator($user, $task)
                || $this->isProjectAssigned($user, $task);
        }

        // employee can update their own task (optional)
        return $this->isTaskAssigned($user, $task);
    }

    // --------------------------
    // DELETE TASK
    // --------------------------
    public function delete(User $user, Task $task): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'manager') {
            return $this->isProjectCreator($user, $task)
                || $this->isProjectAssigned($user, $task);
        }

        return false; // employees never delete
    }

    public function restore(User $user, Task $task): bool
    {
        return false;
    }

    public function forceDelete(User $user, Task $task): bool
    {
        return false;
    }
}
