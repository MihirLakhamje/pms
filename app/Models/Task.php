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
        $totalSeconds = 0;

        foreach ($this->timesheets()->whereNotNull('end_time')->get() as $timesheet) {
            $start = Carbon::parse($timesheet->start_time);
            $end = Carbon::parse($timesheet->end_time);

            // Always positive — ensures correct order
            $seconds = abs($end->diffInSeconds($start, false));

            $totalSeconds += $seconds;
        }

        if ($totalSeconds === 0) {
            return '—';
        }

        $days = floor($totalSeconds / 86400);
        $hours = floor(($totalSeconds % 86400) / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);

        if ($days > 0) {
            return sprintf('%dd %02dh %02dm', $days, $hours, $minutes);
        }

        return sprintf('%02dh %02dm', $hours, $minutes);
    }
}
