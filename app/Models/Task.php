<?php

namespace App\Models;

use Carbon\Carbon;
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

    protected $casts = [
        'due_date' => 'date',
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
    public function getTotalDurationAttribute(): string
    {
        $records = $this->timesheets
            ->whereNotNull('end_time');

        if ($records->isEmpty()) {
            return '—';
        }

        $totalSeconds = $records->reduce(function ($carry, $t) {
            return $carry + Carbon::parse($t->start_time)->diffInSeconds($t->end_time);
        }, 0);

        $days = intdiv($totalSeconds, 86400);
        $hours = intdiv($totalSeconds % 86400, 3600);
        $minutes = intdiv($totalSeconds % 3600, 60);

        if ($days > 0) {
            return sprintf('%dd %02dh %02dm', $days, $hours, $minutes);
        }

        return sprintf('%02dh %02dm', $hours, $minutes);
    }
}
