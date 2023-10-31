<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Week;
use App\Models\LifeEvent;

class LifeWeeksController extends Controller
{
    public function index()
    {
        return Inertia::render('Life', [
            'weeks' => fn() => Week::with('events')->get(),
            'events' => fn() => LifeEvent::getDefaultLifeEventDateMapping(),
        ]);
    }
}