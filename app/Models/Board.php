<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $fillable = [
        'week_id',
        'lanes',
        'cards',
        'properties',
    ];

    protected $casts = [
        'lanes' => 'json',
        'cards' => 'json',
        'properties' => 'json'
    ];
}
