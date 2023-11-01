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
}
