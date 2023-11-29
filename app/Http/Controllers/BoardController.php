<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Week;
use App\Models\Board;
use App\Models\Task;
use App\Enums\Priority;
use App\Enums\TaskStatus;
use Carbon\Carbon;

class BoardController extends Controller
{

    public function index()
    {
        $timezone = env('APP_TIMEZONE');
        $today = Carbon::today()->timezone($timezone);
        $week = Week::getWeekByDate($today);
        
        $board = Board::firstOrCreate([
            'week_id' => $week->id,
        ]);

        return Inertia::render('Board', [
            'week' => $week,
            'board' => $board,
        ]);
    }

    public function show(Week $week)
    {
        $board = Board::firstOrCreate([
            'week_id' => $week->id,
        ]);
        
        return Inertia::render('Board', [
            'week' => $week,
            'board' => $board,
        ]);
    }   

    public function update(Request $request)
    {
        $updatedTasks = [];
        $lanes = $request->input('lanes');

        foreach($lanes as $lane) {
            $cards = $lane['cards'];

            foreach($cards as $card) {
                $task = Task::find($card['id']);
                $status = TaskStatus::tryFrom($card['laneId']) ? TaskStatus::from($card['laneId']) : TaskStatus::BACKLOG;

                $task->update([
                    'title' => $card['title'],
                    'description' => $card['description'],
                    'priority' => Priority::fromName($card['label']),
                    'status' => $status,
                ]);

                array_push($updatedTasks, $task);
            }
        }

        return response()->json($updatedTasks); 
    }  
}