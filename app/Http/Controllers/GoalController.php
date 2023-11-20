<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Goal;

class GoalController extends Controller
{
    public function index()
    {
        return Inertia::render('Goal', [
            'goals' => fn() => Goal::all()
        ]);
    }

    public function new(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'status' => 'required',
            'priority' => 'required',
          ]);

        $goal = $request->only([
            'title',
            'description',
            'target_value',
            'target_units',
            'priority',
            'due_date',
            'status',
            'notes',
        ]);

        Goal::create($goal);

        return to_route('goals');
    }

    public function update(Request $request)
    {
        $goal = $request->only([
            'id',
            'title',
            'description',
            'target_value',
            'target_units',
            'priority',
            'due_date',
            'status',
            'notes',
        ]);

        $existingGoal = Goal::find($goal['id']);
        $existingGoal->update($goal);

        return to_route('goals');
    }

    public function fetch() {
        return response()->json(Goal::all());
    }
}
