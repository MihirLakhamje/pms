<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'content',
    ];

    // 🔹 Relationships

    // Each comment belongs to a task
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    // Each comment is made by a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔹 Helpers

    public function shortContent($limit = 50)
    {
        return strlen($this->content) > $limit
            ? substr($this->content, 0, $limit) . '...'
            : $this->content;
    }
}
