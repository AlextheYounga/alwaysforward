<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\GoalStatus;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'target_value',
        'target_units',
        'completion_type',
        'type',
        'priority',
        'due_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'datetime:Y-m-d',
        'status' => GoalStatus::class,
    ];
}
