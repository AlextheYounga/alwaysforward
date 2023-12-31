<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Task;
use App\Models\Week;

class TasksController extends Controller
{
    public function index()
    {
        return Inertia::render('Task', [
            'tasks' => fn() => Task::active()
                ->orderBy('created_at', 'desc')
                ->get(),
        ]);
    }

    public function new(Request $request)
    {
        $request->validate([
            'title' => 'required',
          ]);

        $task = $request->only([
            'goal_id',
            'title',
            'description',
            'type',
            'priority',
            'due_date',
            'notes',
            'status',
        ]);

        $week = Week::current();
        $taskRecord = Task::create($task);
        $week->tasks()->attach($taskRecord->id);

        return to_route('tasks');
    }

    public function update(Request $request)
    {
        $task = $request->only([
            'id',
            'goal_id',
            'title',
            'description',
            'type',
            'priority',
            'due_date',
            'notes',
            'status',
        ]);

        $existingTask = Task::find($task['id']);
        $existingTask->update($task);

        return to_route('tasks');
    }
}
