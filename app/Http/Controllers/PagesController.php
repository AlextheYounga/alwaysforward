<?php

namespace App\Http\Controllers;

// use Illuminate\Support\Facades\Route;
// use Illuminate\Foundation\Application;
use Inertia\Inertia;
use App\Models\Week;
use App\Models\PlatformConfig;

class PagesController extends Controller
{

    public function dashboard()
    {
        return Inertia::render('Dashboard', [
            'weeks' => fn() => Week::all(),
        ]);
    }

    public function life()
    {
        return Inertia::render('Life', [
            'weeks' => fn() => Week::all(),
            'birthday' => fn() => PlatformConfig::whereKey('birthday')->getValue(),
            'death' => fn() => PlatformConfig::whereKey('death_age')->getValue(),
            'deathDate' => fn() => PlatformConfig::getDeathDate(),
        ]);
    }

    public function kanban()
    {
        return Inertia::render('Kanban');
    }
}

// public function home()
// {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// }