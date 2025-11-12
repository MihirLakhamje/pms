<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    // 🔹 List all attachments for a task
    public function index(Task $task)
    {
        return Attachment::where('task_id', $task->id)
            ->with('uploader')
            ->latest()
            ->get();
    }

    // 🔹 Store a new attachment
    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'file' => 'required|file|max:5120', // 5MB limit
        ]);

        $file = $request->file('file');
        $path = $file->store('attachments', 'public');

        $attachment = Attachment::create([
            'task_id' => $request->task_id,
            'uploaded_by' => auth()->id(),
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
        ]);

        return response()->json($attachment, 201);
    }

    // 🔹 Download file
    // public function download(Attachment $attachment)
    // {
    //     return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    // }

    // 🔹 Delete attachment
    public function destroy(Attachment $attachment)
    {
        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return response()->noContent();
    }
}
