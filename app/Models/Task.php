<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TaskStatus;
use App\Enums\Priority;
use App\Enums\Type;
use App\Models\Week;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'goal_id',
        'title',
        'description',
        'type',
        'priority',
        'due_date',
        'notes',
        'status',
    ];

    protected $casts = [
        'due_date' => 'datetime:Y-m-d',
        'type' => Type::class,
        'priority' => Priority::class,
        'status' => TaskStatus::class,
    ];

    public function weeks()
    {
        return $this->belongsToMany(Week::class);
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

    public function scopeCurrent(Builder $query): void
    {
        $week = Week::current();
        $query->where('week_id', $week->id);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('status', '!=', TaskStatus::COMPLETED);
    }

    public function scopeWork(Builder $query): void
    {
        $query->where('type', '=', Type::WORK);
    }

    public function scopePersonal(Builder $query): void
    {
        $query->where('type', '=', Type::PERSONAL);
    }
}
