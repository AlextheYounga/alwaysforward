<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\CarbonImmutable;
use App\Models\Week;

class LifeEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'week_id',
        'date',
        'title',
        'type',
        'description',
        'notes',
        'properties',
    ];

    protected $casts = [
        'date' => 'date',
        'properties' => 'json'
    ];

    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    public function scopeUpcoming() {
        return $this->where('type', '!=', 'inevitable')
            ->where('date', '>=', CarbonImmutable::now()->toDateString());
    }

    public function isUpcoming()
    {
        return $this->type !== 'inevitable' && $this->date >= CarbonImmutable::now()->toDateString();
    }

    protected function timeLeft(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getTimeLeft()
        );
    }

    public function getTimeLeft()
    {
        if ($this->isUpcoming()) {
            $now = CarbonImmutable::now(env('APP_TIMEZONE'));
            $date = CarbonImmutable::parse($this->date, env('APP_TIMEZONE'));
            return $now->diffAsCarbonInterval($date, false);
        } else {
            return null;
        }
    }
}
