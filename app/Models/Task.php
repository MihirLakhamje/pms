<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'assignee_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    // 🔹 Relationships

    // Each task belongs to a project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Each task is assigned to one user
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    // Each task can have many comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Each task can have many attachments
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    // Each task can have many timesheet entries
    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }

    // 🔹 Helpers

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isPending(): bool
    {
        return $this->status === 'todo';
    }

    public function isHighPriority(): bool
    {
        return $this->priority === 'High';
    }
}
