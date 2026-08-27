<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\Project' => 'App\Policies\ProjectPolicy',
        'App\Models\Task' => 'App\Policies\TaskPolicy',
        'App\Models\Timesheet' => 'App\Policies\TimesheetPolicy',
        'App\Models\TaskReview' => 'App\Policies\TaskReviewPolicy',
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('role', function (User $user, array $roles) {
            return in_array($user->role, $roles);
        });

        Gate::define('viewReports', function ($user) {
            return $user->role === 'admin';
        });
    }
}
