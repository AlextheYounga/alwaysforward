<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Week;
use App\Models\Board;

class BoardController extends Controller
{

    public function index()
    {
        return Inertia::render('Kanban', [

        ]);
    }

    public function show(Week $week)
    {
        $board = Board::getOrCreateBoard($week);
        
        return Inertia::render('Kanban', [
            'week' => $week,
            'board' => $board,
        ]);
    }   
}