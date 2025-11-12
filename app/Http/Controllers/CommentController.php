<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    // 🔹 Store new comment
    public function store(Request $request, Task $task)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        try {
            Comment::create([
                'task_id' => $task->id,
                'user_id' => auth()->id(),
                'content' => $validated['content'],
            ]);

            return redirect()->back()->with('success', 'Comment added successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to add comment: ' . $e->getMessage());
            return back()->with('error', 'Unable to add comment.');
        }
    }

    // 🔹 Edit form
    // public function edit(Comment $comment)
    // {
    //     return view('comments.edit', compact('comment'));
    // }

    // 🔹 Update existing comment
    public function update(Request $request, Comment $comment)
    {

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        try {
            $comment->update($validated);
            return redirect()->route('tasks.show', $comment->task_id)->with('success', 'Comment updated successfully.');
        } catch (\Exception $e) {
            Log::error('Comment update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update comment.');
        }
    }

    // 🔹 Delete comment
    public function destroy(Comment $comment)
    {
        try {
            $comment->delete();
            return redirect()->back()->with('success', 'Comment deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete comment: ' . $e->getMessage());
            return back()->with('error', 'Unable to delete comment.');
        }
    }
}
