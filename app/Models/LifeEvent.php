<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonImmutable;
use App\Models\Week;

class LifeEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'week_id',
        'date',
        'title',
        'description',
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
}
