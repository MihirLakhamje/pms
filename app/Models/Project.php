<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    // Many-to-Many with Users (project members)
    public function users()
    {
        return $this->belongsToMany(User::class, 'project_users')
                    ->withTimestamps();
    }

    // One-to-Many with Tasks
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // 🔹 Accessors / Helpers
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isOnHold(): bool
    {
        return $this->status === 'on_hold';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }
}
