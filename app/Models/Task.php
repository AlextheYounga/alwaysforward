<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Status;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'user_id',
        'week_id',
        'goal_id',
        'board_id',
        'title',
        'description',
        'type',
        'priority',
        'duration',
        'time_spent',
        'start_date',
        'due_date',
        'subtasks',
        'notes',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'status' => Status::class,
    ];

    public function board() {
        return $this->belongsTo(Board::class);
    }
}
