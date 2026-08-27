<?php

namespace App\Http\Controllers;

use App\Models\Timesheet;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class TimesheetController extends Controller
{
    // 🔹 List all timesheet entries for current user
    public function showTimesheets(Task $task)
    {
        Gate::authorize('view', Timesheet::class);
        $timesheets = Timesheet::where('task_id', $task->id)->orderByDesc('start_time')->with('user')->paginate(5);

        return response()->json($timesheets);
    }

    // 🔹 Start timer for a task
    public function startTimer(Request $request)
    {
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
        ]);
        $taskId = $validated['task_id'];
        $task = Task::findOrFail($taskId);

        Gate::authorize('isAssigned', $task);

        $userId = auth()->id();

        try {
            // Prevent multiple running timers
            $running = Timesheet::where('user_id', $userId)
                ->where('is_running', true)
                ->first();

            if ($running) {
                return back()->with('error', 'You already have a running timer.');
            }

            Timesheet::create([
                'user_id' => $userId,
                'task_id' => $validated['task_id'],
                'date' => now()->toDateString(),
                'start_time' => Carbon::parse(now())->timezone(auth()->user()->timezone ?? 'Asia/Kolkata'),
                'is_running' => true,
            ]);

            return back()->with('success', 'Timer started successfully.');
        } catch (\Exception $e) {
            Log::error('Timer start failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to start timer.');
        }
    }

    // 🔹 Stop running timer
    public function stopTimer(Request $request)
    {

        $validated = $request->validate([
            'note' => 'nullable|string|max:255',
            'task_id' => 'required|exists:tasks,id',
        ]);
        $task = Task::findOrFail($validated['task_id']);

        Gate::authorize('isAssigned', $task);

        $userId = auth()->id();

        try {
            $entry = Timesheet::where('user_id', $userId)
                ->where('is_running', true)
                ->latest()
                ->first();

            if (!$entry) {
                return back()->with('error', 'No running timer found.');
            }

            $endTime = Carbon::parse(now())->timezone(auth()->user()->timezone ?? 'Asia/Kolkata');
            $duration = $endTime->diffInSeconds($entry->start_time);

            $entry->update([
                'end_time' => $endTime,
                'duration_seconds' => $duration,
                'note' => $validated['note'] ?? null,
                'is_running' => false,
            ]);

            return back()->with('success', 'Timer stopped successfully.');
        } catch (\Exception $e) {
            Log::error('Timer stop failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to stop timer.');
        }
    }

    // 🔹 View all times for a specific task (with total)
    // public function showByTask(Task $task)
    // {
    //     $userId = auth()->id();

    //     $entries = Timesheet::where('task_id', $task->id)
    //         ->where('user_id', $userId)
    //         ->orderByDesc('date')
    //         ->get();

    //     $totalSeconds = $entries->sum('duration_seconds');
    //     $hours = floor($totalSeconds / 3600);
    //     $minutes = floor(($totalSeconds % 3600) / 60);

    //     $formattedTotal = sprintf('%02d hr %02d min', $hours, $minutes);

    //     return view('timesheets.task', compact('task', 'entries', 'formattedTotal'));
    // }

    // 🔹 Delete a timesheet entry (if needed)
    public function destroy(Timesheet $timesheet)
    {

        try {
            $timesheet->delete();
            return back()->with('success', 'Timesheet entry deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete timesheet entry: ' . $e->getMessage());
            return back()->with('error', 'Unable to delete timesheet entry.');
        }
    }
}
