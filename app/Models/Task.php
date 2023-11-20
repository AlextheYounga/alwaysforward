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
        'start_date' => 'datetime:Y-m-d',
        'due_date' => 'datetime:Y-m-d',
        'status' => TaskStatus::class,
    ];

    public function weeks() {
        return $this->belongsToMany(Week::class, 'week_task');
    }

    public static function current() {
        $week_id = Week::current()->id;
        return Task::where('week_id', $week_id)->get();
    }
}
