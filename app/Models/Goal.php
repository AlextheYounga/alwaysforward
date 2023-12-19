<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\GoalStatus;
use App\Enums\Priority;
use App\Enums\Type;
use Carbon\CarbonImmutable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'priority',
        'due_date',
        'has_target',
        'target_value',
        'target_units',
        'status',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'type' => Type::class,
        'priority' => Priority::class,
        'status' => GoalStatus::class,
    ];

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
            $dueDate = CarbonImmutable::parse($this->due_date);
            return $now->diffAsCarbonInterval($dueDate);
        } else {
            return null;
        }
    }

    /**
     * Prepare a date for array / JSON serialization.
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d');
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('status', '!=', GoalStatus::COMPLETED);
    }

    public function scopeTimed(Builder $query): void
    {
        $query->where('due_date', '!=', null);
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
