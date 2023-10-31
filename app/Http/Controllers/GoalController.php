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
        $goal = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'due_date' => 'required',
            'status' => 'required',
            'priority' => 'required',
        ]);

        Goal::create($goal);

        return response()->json([
            'message' => 'Goal created successfully',
        ], 201);
    }
}
