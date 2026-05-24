<?php

namespace App\Http\Controllers;

use App\Models\TaskReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskReviewController extends Controller
{
    public function store(Request $request)
    {
        Gate::authorize('create', TaskReview::class);
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'content' => 'required|string',
        ]);

        TaskReview::create([
            'task_id' => $validated['task_id'],
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Comment added.');
    }

    public function destroy(TaskReview $taskReview)
    {
        
        Gate::authorize('delete', $taskReview);
        $taskReview->delete();

        return back()->with('success', 'Comment deleted.');
    }
}
