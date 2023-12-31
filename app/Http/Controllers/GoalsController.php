<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Goal;

class GoalsController extends Controller
{
    public function index()
    {
        return Inertia::render('Goal', [
            'goals' => fn() => Goal::active()
                ->orderBy('created_at', 'desc')
                ->get(),
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
            'type',
            'priority',
            'due_date',
            'has_target',
            'target_value',
            'target_units',
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
            'type',
            'priority',
            'due_date',
            'has_target',
            'target_value',
            'target_units',
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
