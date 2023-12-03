<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\Week;
use App\Enums\TaskStatus;
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
            ['id' => 'backlog', 'title' => 'Backlog', 'color' => '#cbd5e1'],
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
                'id' => (string) $task->id,
                'title' => $task->title ?? '',
                'description' => $task->description ?? '',
                'label' => $task->priority->name ?? '',
                'metadata' => [
                    'sha' => Hash::make($task->id, ['rounds' => 4])
                ]
            ]);
        }

        return $cards;
    }

    public function getLanesAttribute()
    {
        $lanes = [];

        foreach(Board::lanes() as $lane) {
            $tasks = Task::where('status', '=', TaskStatus::tryFrom($lane['id']))
                ->get();

            if ($lane['id'] === 'completed') {
                $tasks = Task::where('status', '=', 'completed')
                ->completedThisWeek()
                ->get();
            }

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
