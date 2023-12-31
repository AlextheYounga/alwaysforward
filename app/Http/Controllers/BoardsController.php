<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Week;
use App\Models\Board;
use App\Models\Task;
use App\Enums\Priority;
use App\Enums\TaskStatus;
use App\Enums\Type;
use Carbon\Carbon;

class BoardsController extends Controller
{
    public function index()
    {
        $today = Carbon::now(env('APP_TIMEZONE', 'UTC'));
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

    /**
     * Create or update tasks from board
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(Request $request)
    {
        $updatedTasks = [];
        $lanes = $request->input('lanes');

        foreach($lanes as $lane) {
            $cards = $lane['cards'];

            foreach($cards as $card) {
                $status = TaskStatus::tryFrom($card['laneId']) ? TaskStatus::from($card['laneId']) : TaskStatus::BACKLOG;
                // Create task
                if (gettype($card['id']) === "string" && str_contains($card['id'], '-')) {
                    $task = Task::create([
                        'title' => $card['title'],
                        'description' => array_key_exists("description", $card) ? $card['description'] : null,
                        'priority' => Priority::NORMAL,
                        'type' => Type::PERSONAL,
                        'status' => $status,
                    ]);
                } else {
                    // Update task
                    $task = Task::find($card['id']);

                    $task->update([
                        'title' => $card['title'],
                        'description' => array_key_exists("description", $card) ? $card['description'] : null,
                        'priority' => Priority::fromName($card['label']),
                        'status' => $status,
                    ]);
                }

                array_push($updatedTasks, $task);
            }
        }

        return response()->json($updatedTasks);
    }
}
