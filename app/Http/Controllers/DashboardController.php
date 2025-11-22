<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $auth = auth()->user();

        $taskTimeData = Task::with('timesheets')
            ->get()
            ->map(function ($task) {
                $totalSeconds = $task->timesheets
                    ->whereNotNull('end_time')
                    ->reduce(function ($sum, $t) {
                        return $sum + Carbon::parse($t->start_time)
                            ->diffInSeconds($t->end_time);
                    }, 0);

                return [
                    'task'    => $task->title,
                    'seconds' => $totalSeconds,
                    'minutes' => round($totalSeconds / 60, 2),
                    'hours'   => round($totalSeconds / 3600, 2),
                ];
            });

        /* -----------------------------------
         | ADMIN METRICS
         |----------------------------------- */
        if ($auth->role === 'admin') {

            $totalProjects = Project::count();
            $totalTasks = Task::count();
            $totalUsers = User::count();

            $statusCounts = Task::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status');

            $topEmployees = User::where('role', 'employee')
                ->withCount('tasks')
                ->orderByDesc('tasks_count')
                ->limit(5)
                ->get();

            $recentTasks = Task::with(['project', 'assignee'])
                ->latest()
                ->limit(6)
                ->get();

            return view('dashboard', compact(
                // Admin
                'totalProjects',
                'totalTasks',
                'totalUsers',
                'statusCounts',
                'topEmployees',
                'recentTasks',

                // Common
                'taskTimeData'
            ));
        }


        /* -----------------------------------
         | MANAGER METRICS
         |----------------------------------- */
        if ($auth->role === 'manager') {

            // Projects managed by this manager
            $managerProjects = Project::where('created_by', $auth->id)->count();

            // Tasks inside manager's projects
            $managerTasks = Task::whereHas('project', function ($q) use ($auth) {
                $q->where('created_by', $auth->id);
            })->count();

            // Employees assigned under manager
            $managerEmployees = User::whereHas('projects', function ($q) use ($auth) {
                $q->where('created_by', $auth->id);
            })->distinct()->count();

            // Recent tasks under manager
            $managerRecentTasks = Task::with(['project', 'assignee'])
                ->whereHas('project', fn($q) => $q->where('created_by', $auth->id))
                ->latest()
                ->limit(6)
                ->get();

            return view('dashboard', compact(

                // Manager
                'managerProjects',
                'managerTasks',
                'managerEmployees',
                'managerRecentTasks',

                // Common
                'taskTimeData'
            ));
        }


        /* -----------------------------------
 | EMPLOYEE METRICS
 |----------------------------------- */
        if ($auth->role === 'employee') {

            // Projects where employee is a member
            $employeeProjects = $auth->projects()->count();

            // Total tasks assigned to employee
            $employeeTasks = Task::where('assignee_id', $auth->id)->count();

            // Completed tasks
            $employeeCompleted = Task::where('assignee_id', $auth->id)
                ->where('status', 'completed')
                ->count();

            // Recent tasks
            $employeeRecentTasks = Task::with(['project'])
                ->where('assignee_id', $auth->id)
                ->latest()
                ->limit(6)
                ->get();

            // Time spent on tasks (for chart)
            $employeeTimeData = Task::where('assignee_id', $auth->id)
                ->with('timesheets')
                ->get()
                ->map(function ($task) {
                    $totalSeconds = $task->timesheets
                        ->whereNotNull('end_time')
                        ->reduce(
                            fn($sum, $t) =>
                            $sum + Carbon::parse($t->start_time)->diffInSeconds($t->end_time),
                            0
                        );

                    return [
                        'task'  => $task->title,
                        'hours' => round($totalSeconds / 3600, 2),
                    ];
            });

            return view('dashboard', compact(
                // Employee
                'employeeProjects',
                'employeeTasks',
                'employeeCompleted',
                'employeeRecentTasks',
                'employeeTimeData',

                // Common
                'taskTimeData'
            ));
        }
    }
}
