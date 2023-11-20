<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        return Inertia::render('Task', [
            'tasks' => fn() => Task::all()
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
            'duration',
            'time_spent',
            'start_date',
            'due_date',
            'subtasks',
            'notes',
            'status',
        ]);

        Task::create($task);

        return to_route('tasks');
    }

    public function update(Request $request)
    {
        $task = $request->only([
            'goal_id',
            'title',
            'description',
            'type',
            'priority',
            'duration',
            'time_spent',
            'start_date',
            'due_date',
            'subtasks',
            'notes',
            'status',
        ]);

        $existingGoal = Task::find($task['id']);
        $existingGoal->update($task);

        return to_route('tasks');
    }
}
