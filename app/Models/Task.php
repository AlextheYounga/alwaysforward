<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TaskStatus;
use App\Enums\Priority;
use App\Enums\Type;
use App\Models\Week;
use Carbon\CarbonImmutable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        self::updating(function ($model) {
            $originalStatus = $model->getOriginal('status');
            $markedAsCompleted = $model->status === TaskStatus::COMPLETED && $originalStatus !== TaskStatus::COMPLETED;

            if ($markedAsCompleted) {
                $model->date_completed = CarbonImmutable::now(env('APP_TIMEZONE', 'UTC'));
            }
        });
    }

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
        'due_date' => 'datetime',
        'type' => Type::class,
        'priority' => Priority::class,
        'status' => TaskStatus::class,
    ];

    public function weeks()
    {
        return $this->belongsToMany(Week::class);
    }

    /**
     * Prepare a date for array / JSON serialization.
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d');
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
            $now = CarbonImmutable::now(env('APP_TIMEZONE', 'UTC'));
            $dueDate = CarbonImmutable::parse($this->due_date, env('APP_TIMEZONE', 'UTC'));
            return $now->diffAsCarbonInterval($dueDate, false);
        } else {
            return null;
        }
    }

    public function scopeCurrent(Builder $query): void
    {
        $week = Week::current();
        $query->where('week_id', $week->id);
    }

    public function scopeCompletedThisWeek(Builder $query): void
    {
        $week = Week::current();
        $query->where('date_completed', '>=', $week->start)
            ->where('date_completed', '<=', $week->end);
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
