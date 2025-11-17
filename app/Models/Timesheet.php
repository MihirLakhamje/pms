<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

// app/Models/Timesheet.php
// app/Models/Timesheet.php
class Timesheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'task_id',
        'date',
        'start_time',
        'end_time',
        'note',
        'is_running',
    ];

    protected $casts = [
        'is_running' => 'boolean',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function getDurationAttribute(): string
    {
        if (!$this->end_time) {
            return '-';
        }

        $seconds = Carbon::parse($this->start_time)
            ->diffInSeconds($this->end_time);

        $days = intdiv($seconds, 86400);
        $hours = intdiv($seconds % 86400, 3600);
        $minutes = intdiv($seconds % 3600, 60);

        if ($days > 0) {
            return sprintf('%dd %02dhr %02dmin', $days, $hours, $minutes);
        }

        return sprintf('%02dhr %02dmin', $hours, $minutes);
    }

    // Convert start_time to app timezone for display
    public function getStartTimeLocalAttribute()
    {
        return $this->start_time ? $this->start_time->timezone(config('app.timezone')) : null;
    }

    public function getEndTimeLocalAttribute()
    {
        return $this->end_time ? $this->end_time->timezone(config('app.timezone')) : null;
    }
}
