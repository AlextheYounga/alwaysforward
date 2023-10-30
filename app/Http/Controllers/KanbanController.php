<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Week;

class KanbanController extends Controller
{

    public function index()
    {
        return Inertia::render('Kanban', [

        ]);
    }

    public function show(Request $request, Week $week_id)
    {
        return Inertia::render('Kanban', [

        ]);
    }
}