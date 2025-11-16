<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    // 🔹 List all tasks (optional: filter by project)
    public function index(Request $request)
    {
        $projects = Project::pluck('name', 'id');

        $tasks = Task::with(['project', 'assignee', 'timesheets'])
            ->when($request->filled('project_id'), fn($q) => $q->where('project_id', $request->project_id))
            ->latest()
            ->paginate(10);

        return view('tasks.index', compact('tasks', 'projects'));
    }

    // 🔹 Show create form
    public function create()
    {
        $projects = Project::all();
        $users = User::all();
        return view('tasks.create', compact('projects', 'users'));
    }

    // 🔹 Store new task
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'assignee_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,in_review,completed',
            'priority' => 'required|in:Low,Medium,High',
            'due_date' => 'nullable|date',
        ]);

        try {
            Task::create($validated);
            return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
        } catch (\Exception $e) {
            Log::error('Task creation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to create task. Please try again.');
        }
    }

    // 🔹 Show single task
    public function show(Task $task)
    {
        $task->load([
            'project',
            'assignee',
            'comments.user',
            'attachments',
            'timesheets' => fn($q) => $q->orderByDesc('start_time'),
        ]);
        return view('tasks.show', compact('task'));
    }

    // 🔹 Show edit form
    public function edit(Task $task)
    {
        $projects = Project::all();
        $users = User::all();
        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    // 🔹 Update existing task
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'assignee_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,in_review,completed',
            'priority' => 'required|in:Low,Medium,High',
            'due_date' => 'nullable|date',
        ]);

        try {
            $task->update($validated);
            return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
        } catch (\Exception $e) {
            Log::error('Task update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update task. Please try again.');
        }
    }

    // 🔹 Delete task
    public function destroy(Task $task)
    {
        try {
            $task->delete();
            return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Task deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete task. Please try again.');
        }
    }

    // 🔹 Optional: Quick status toggle (useful for dashboards)
    public function toggleStatus(Task $task)
    {
        try {
            $task->status = match ($task->status) {
                'todo' => 'in_progress',
                'in_progress' => 'in_review',
                'in_review' => 'completed',
                default => 'todo',
            };
            $task->save();

            return back()->with('success', 'Task status updated.');
        } catch (\Exception $e) {
            Log::error('Status toggle failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to change task status.');
        }
    }
}
