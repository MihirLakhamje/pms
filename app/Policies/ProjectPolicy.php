<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Helper: check if user is assigned to project.
     */
    private function isAssigned(User $user, Project $project): bool
    {
        return $project->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Helper: check if user created the project.
     */
    private function isCreator(User $user, Project $project): bool
    {
        return $project->created_by === $user->id;
    }

    /**
     * viewAny: admin & manager can view project list
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    /**
     * view:
     * admin: all
     * manager: assigned OR creator
     * employee: assigned
     */
    public function view(User $user, Project $project): bool
    {
       return in_array($user->role, ['admin', 'manager', 'employee']);
    }

    /**
     * create:
     * admin + manager
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    /**
     * update:
     * admin: yes
     * manager: assigned OR creator
     */
    public function update(User $user, Project $project): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'manager') {
            return $this->isAssigned($user, $project) || $this->isCreator($user, $project);
        }

        return false;
    }

    /**
     * delete:
     * admin: yes
     * manager: assigned OR creator
     */
    public function delete(User $user, Project $project): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'manager') {
            return $this->isAssigned($user, $project) || $this->isCreator($user, $project);
        }

        return false;
    }

    public function restore(User $user, Project $project): bool
    {
        return false;
    }

    public function forceDelete(User $user, Project $project): bool
    {
        return false;
    }
}
