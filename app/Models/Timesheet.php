<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Timesheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'task_id',
        'date',
        'start_time',
        'end_time',
        'duration_seconds',
        'note',
        'is_running',
    ];

    protected $casts = [
        'is_running' => 'boolean',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'date' => 'date',
    ];

    // 🔹 Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    // 🔹 Helpers

    public function getDurationInMinutesAttribute(): ?int
    {
        return $this->duration_seconds
            ? floor($this->duration_seconds / 60)
            : null;
    }

    public function getFormattedDurationAttribute(): ?string
    {
        if (!$this->duration_seconds) return null;

        $hours = floor($this->duration_seconds / 3600);
        $minutes = floor(($this->duration_seconds % 3600) / 60);

        return sprintf('%02d hr %02d min', $hours, $minutes);
    }

    // 🔹 Utility (pure calculation, no DB write)

    public function calculateDuration(): ?int
    {
        if (!$this->end_time || !$this->start_time) return null;

        return $this->end_time()->diffInSeconds($this->start_time);
    }
}
