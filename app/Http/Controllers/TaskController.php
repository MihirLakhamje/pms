<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\Timesheet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index(Request $request)
    {

        $projects = Project::pluck('name', 'id');

        $tasks = Task::with(['project', 'assignee', 'timesheets'])
            ->when($request->filled('project_id'), fn ($q) => $q->where('project_id', $request->project_id))
            ->latest()
            ->paginate(10);

        $view = $request->get('task-view', 'kanban'); // default kanban

        return view('tasks.index', compact('tasks', 'projects', 'view'));
    }

    public function create()
    {
        Gate::authorize('create', Task::class);
        $projects = Project::all();
        $users = User::all(); // or scopeVisibleTo() if you want filtering

        return view('tasks.create', compact('projects', 'users'));
    }

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
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        $project = Project::findOrFail($validated['project_id']);

        Gate::authorize('store', [Task::class, $project]);

        $task = Task::create($validated);

        if ($request->hasFile('attachments')) {

            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'r2');

                $task->attachments()->create([
                    'uploaded_by' => auth()->id(),
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        Gate::authorize('view', $task);

        $task->load([
            'project',
            'assignee',
            'timesheets',
            'reviews.user',
        ]);

        $timesheets = Timesheet::where('task_id', $task->id)->orderByDesc('start_time')->with('user')->paginate(5);

        $running = Timesheet::where('user_id', auth()->id())
            ->where('task_id', $task->id)
            ->where('is_running', true)
            ->first();

        $task->load('attachments.user');

        foreach ($task->attachments as $attachment) {

            $attachment->temporary_url =
                Storage::disk('r2')->temporaryUrl(
                    $attachment->file_path,
                    now()->addMinutes(10)
                );
        }

        return view('tasks.show', compact('task', 'running', 'timesheets'));
    }

    public function edit(Task $task)
    {
        Gate::authorize('update', $task);

        $projects = Project::all();
        $users = User::all();

        $task->load('attachments.user');

        foreach ($task->attachments as $attachment) {

            $attachment->temporary_url =
                Storage::disk('r2')->temporaryUrl(
                    $attachment->file_path,
                    now()->addMinutes(10)
                );
        }

        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        Gate::authorize('update', $task);

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'assignee_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,in_review,completed',
            'priority' => 'required|in:Low,Medium,High',
            'due_date' => 'nullable|date',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        $task->update($validated);

        // Upload new attachments
        if ($request->hasFile('attachments')) {

            foreach ($request->file('attachments') as $file) {

                $path = $file->store(
                    'attachments',
                    'r2'
                );

                $task->attachments()->create([
                    'uploaded_by' => auth()->id(),
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }

    // public function toggleStatus(Task $task)
    // {
    //     Gate::authorize('update', $task);

    //     $task->status = match ($task->status) {
    //         'todo' => 'in_progress',
    //         'in_progress' => 'in_review',
    //         'in_review' => 'completed',
    //         default => 'todo',
    //     };

    //     $task->save();

    //     return back()->with('success', 'Task status updated.');
    // }
}
