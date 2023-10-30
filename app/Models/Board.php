<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\Week;

class Board extends Model
{
    use HasFactory;

    protected $fillable = [
        'week_id',
        'lanes',
        'properties',
    ];

    protected $casts = [
        'lanes' => 'json',
        'properties' => 'json'
    ];

    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public static function lanes()
    {
        return [
            ['id' => 'todo', 'title' => 'Planned Tasks', 'color' => '#cbd5e1'],
            ['id' => 'in-progress', 'title' => 'In Progress', 'color' => '#fffbeb'],
            ['id' => 'on-hold', 'title' => 'On Hold', 'color' => '#d1d5db'],
            ['id' => 'completed', 'title' => 'Completed', 'color' => '#d1fae5'],
        ];
    }

    public static function createBoardLanes()
    {
        // Build Board Lanes
        $lanes = [];
        
        foreach(Board::lanes() as $lane) {
            array_push($lanes, [
                'id' => $lane['id'],
                'title' => $lane['title'],
                'style' => [
                    'backgroundColor' => $lane['color']
                ],
                'cards' => [],
            ]);
        }

        $data = ['lanes' => $lanes];

        return $data;
    }

    public static function getOrCreateBoard($week)
    {
        if ($week->board()->exists()) {
            return $week->board;
        }

        $lanes = Board::createBoardLanes();

        // Create board with week relation
        $week->board()->create([
            'lanes' => $lanes,
            'properties' => [],
        ]);

        return $week->board();
    }
}
