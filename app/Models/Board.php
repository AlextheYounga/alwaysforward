<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\Week;
use Illuminate\Support\Facades\Hash;

class Board extends Model
{
    use HasFactory;

    protected $appends = [
        'lanes'
    ];

    protected $fillable = [
        'week_id',
        'properties',
    ];

    protected $casts = [
        'properties' => 'json'
    ];

    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    public static function lanes()
    {
        return [
            ['id' => 'todo', 'title' => 'Planned Tasks', 'color' => '#c4b5fd'],
            ['id' => 'in-progress', 'title' => 'In Progress', 'color' => '#fffbeb'],
            ['id' => 'on-hold', 'title' => 'On Hold', 'color' => '#d1d5db'],
            ['id' => 'completed', 'title' => 'Completed', 'color' => '#d1fae5'],
        ];
    }

    public static function createLaneCards($tasks)
    {
        $cards = [];

        foreach($tasks as $task) {
            array_push($cards, [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'label' => $task->priority,
                'metadata' => [
                    'sha' => Hash::make($task->id, ['rounds' => 4])
                ]
            ]);
        }

        return $cards;
    }

    public static function createBacklogLane()
    {
        return [
            'id' => 'backlog',
            'title' => 'Backlog',
            'style' => [
                'backgroundColor' => '#cbd5e1'
            ],
            'cards' => Board::createLaneCards(Task::all())
        ];
    }

    public function getLanesAttribute()
    {
        $lanes = [];
        
        // First build the backlog lane containing all tasks
        array_push($lanes, Board::createBacklogLane());

        // Then add the remaining lanes with associated tasks
        foreach(Board::lanes() as $lane) {
            $week = $this->week()->first();

            $tasks = $week->tasks()
                ->where('status', $lane['id'])
                ->get();

            array_push($lanes, [
                'id' => $lane['id'],
                'title' => $lane['title'],
                'style' => [
                    'backgroundColor' => $lane['color']
                ],
                'cards' => Board::createLaneCards($tasks ?? []),
            ]);
        }

        $data = ['lanes' => $lanes];

        return $data;
    }
}
