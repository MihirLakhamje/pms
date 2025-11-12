<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
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

    // 🔹 Store new project
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:in_progress,on_hold,completed',
            'deadline' => 'nullable|date',
            'users' => 'array'
        ]);

        try {
            $project = Project::create($validated);

            if ($request->has('users')) {
                $project->users()->sync($request->users);
            }

            return redirect()->route('projects.index')->with('success', 'Project created successfully.');
        } catch (\Exception $e) {
            Log::error('Project creation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to create project. Please try again.');
        }
    }

    // 🔹 Show project details
    public function show(Project $project)
    {
        $project->load(['users', 'tasks']);
        return view('projects.show', compact('project'));
    }

    // 🔹 Show edit form
    public function edit(Project $project)
    {
        $users = User::whereNotIn('role', ['admin', 'client'])->get();
        $project->load('users');
        return view('projects.edit', compact('project', 'users'));
    }

    // 🔹 Update project
    public function update(Request $request, Project $project)
    {
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
        try {
            $project->delete();
            return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Project deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete project. Please try again.');
        }
    }
}
