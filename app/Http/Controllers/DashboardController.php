<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $auth = auth()->user();

        if (! $auth) {
            abort(403);
        }

        $taskTimeData = $this->getTaskTimeData();

        if ($auth->role === 'admin') {
            return $this->adminDashboard($taskTimeData);
        }

        if ($auth->role === 'manager') {
            return $this->managerDashboard($auth, $taskTimeData);
        }

        if ($auth->role === 'employee') {
            return $this->employeeDashboard($auth, $taskTimeData);
        }

        abort(403);
    }

    private function adminDashboard($taskTimeData)
    {
        $totalProjects = Project::count();
        $totalTasks = Task::count();
        $totalUsers = User::count();

        $statusCounts = Task::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $topEmployees = Task::select('assignee_id', DB::raw('count(*) as total'))
            ->groupBy('assignee_id')
            ->where('status', 'completed')
            ->orderByDesc('total')
            ->limit(5)
            ->with('assignee')
            ->get();

        $recentTasks = Task::with(['project', 'assignee'])
            ->latest()
            ->limit(6)
            ->get();

        return view('dashboard', compact(
            'totalProjects',
            'totalTasks',
            'totalUsers',
            'statusCounts',
            'topEmployees',
            'recentTasks',
            'taskTimeData'
        ));
    }

    private function managerDashboard($auth, $taskTimeData)
    {
        $managerProjects = Project::where('created_by', $auth->id)->count();

        $managerTasks = Task::whereHas('project', function ($q) use ($auth) {
            $q->where('created_by', $auth->id);
        })->count();

        $managerEmployees = User::whereHas('projects', function ($q) use ($auth) {
            $q->where('created_by', $auth->id);
        })->distinct()->count();

        $managerRecentTasks = Task::with(['project', 'assignee'])
            ->whereHas('project', fn($q) => $q->where('created_by', $auth->id))
            ->latest()
            ->limit(6)
            ->get();

        return view('dashboard', compact(
            'managerProjects',
            'managerTasks',
            'managerEmployees',
            'managerRecentTasks',
            'taskTimeData'
        ));
    }

    private function employeeDashboard($auth, $taskTimeData)
    {
        $employeeProjects = $auth->projects()->count();

        $employeeTasks = Task::where('assignee_id', $auth->id)->count();

        $employeeCompleted = Task::where('assignee_id', $auth->id)
            ->where('status', 'completed')
            ->count();

        $employeeRecentTasks = Task::with(['project'])
            ->where('assignee_id', $auth->id)
            ->latest()
            ->limit(6)
            ->get();

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
                    'task' => $task->title,
                    'hours' => round($totalSeconds / 3600, 2),
                ];
            });

        return view('dashboard', compact(
            'employeeProjects',
            'employeeTasks',
            'employeeCompleted',
            'employeeRecentTasks',
            'employeeTimeData',
            'taskTimeData'
        ));
    }

    private function getTaskTimeData()
    {
        return Task::with('timesheets')
            ->get()
            ->map(function ($task) {

                $totalSeconds = $task->timesheets
                    ->whereNotNull('end_time')
                    ->reduce(function ($sum, $t) {
                        return $sum + Carbon::parse($t->start_time)
                            ->diffInSeconds($t->end_time);
                    }, 0);

                return [
                    'task' => Str::limit($task->title, 20),
                    'seconds' => $totalSeconds,
                    'minutes' => round($totalSeconds / 60, 2),
                    'hours' => round($totalSeconds / 3600, 2),
                ];
            });
    }
}
