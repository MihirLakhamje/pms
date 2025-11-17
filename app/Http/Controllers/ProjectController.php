<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    // 🔹 List all projects
    public function index()
    {
        $projects = Project::with('users')->latest()->paginate(10);
        return view('projects.index', compact('projects'));
    }

    // 🔹 Show create form
    public function create()
    {
        $users = User::whereNotIn('role', ['admin', 'client'])->get();
        return view('projects.create', compact('users'));
    }

    public function projectUsers(Project $project)
    {
        // dd($project->users()->select('user_id', 'name')->get());
        return response()->json(
            $project->users()->select('users.id', 'users.name')->get()
        );
    }

    // 🔹 Store new project
    public function store(Request $request)
    {
        Gate::authorize('create', Project::class);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:in_progress,on_hold,completed',
            'deadline' => 'nullable|date',
            'users' => 'array'
        ]);

        try {
            $project = new Project($validated);

            $project->created_by = auth()->user()->id;
            $project->save();

            if ($request->has('users')) {
                $project->users()->sync($request->users);
            }

            return redirect()->route('projects.index')->with('success', 'Project created successfully.');
        } catch (\Exception $e) {
            Log::error('Project creation failed: ' . $e->getMessage());
            // dd($e->getMessage());
            return back()->with('error', 'Failed to create project. Please try again.');
        }
    }

    // 🔹 Show project details
    public function show(Project $project)
    {
        Gate::authorize('view', $project);
        $users = $project->users()->paginate(5);
        $tasks = $project->tasks()->paginate(5);
        return view('projects.show', compact('project', 'users', 'tasks'));
    }

    // 🔹 Show edit form
    public function edit(Project $project)
    {
        Gate::authorize('update', $project);
        $users = User::whereNotIn('role', ['admin', 'client'], 'id')->get();
        $project->load('users');
        return view('projects.edit', compact('project', 'users'));
    }

    // 🔹 Update project
    public function update(Request $request, Project $project)
    {
        Gate::authorize('update', $project);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:in_progress,on_hold,completed',
            'deadline' => 'nullable|date',
            'users' => 'array'
        ]);

        try {
            $project->update($validated);

            if ($request->has('users')) {
                $project->users()->sync($request->users);
            }

            return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
        } catch (\Exception $e) {
            Log::error('Project update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update project. Please try again.');
        }
    }

    // 🔹 Delete project
    public function destroy(Project $project)
    {
        Gate::authorize('delete', $project);
        try {
            $project->delete();
            return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Project deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete project. Please try again.');
        }
    }
}
