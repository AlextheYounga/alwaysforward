<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Week;
use App\Models\Board;
use Carbon\Carbon;

class BoardController extends Controller
{

    public function index()
    {
        $timezone = env('APP_TIMEZONE', 'America/New_York');
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
}