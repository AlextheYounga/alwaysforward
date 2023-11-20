<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TaskStatus;
use App\Enums\Priority;
use App\Models\Week;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'goal_id',
        'title',
        'description',
        'priority',
        'due_date',
        'notes',
        'status',
    ];

    protected $casts = [
        'due_date' => 'datetime:Y-m-d',
        'priority' => Priority::class,
        'status' => TaskStatus::class,
    ];

    public function weeks()
    {
        return $this->belongsToMany(Week::class);
    }

    public static function current()
    {
        $week = Week::current();
        return $week->tasks()->get();
    }

    protected function timeLeft(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getTimeLeft()
        );
    }

    public function getTimeLeft()
    {
        if ($this->due_date) {
            $now = CarbonImmutable::now(env('APP_TIMEZONE', 'America/New_York'));
            $dueDate = CarbonImmutable::parse($this->due_date);
            return $now->diffAsCarbonInterval($dueDate);
        } else {
            return null;
        }
    }
}
