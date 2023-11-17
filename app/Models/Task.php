<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TaskStatus;
use App\Models\Week;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'week_id',
        'goal_id',
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
        'status' => TaskStatus::class,
    ];

    public function week() {
        return $this->belongsTo(Week::class);
    }

    public static function current() {
        $week_id = Week::current()->id;
        return Task::where('week_id', $week_id)->get();
    }
}
