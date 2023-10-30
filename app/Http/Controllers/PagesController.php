<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Week;
// use Illuminate\Support\Facades\Route;
// use Illuminate\Foundation\Application;

class PagesController extends Controller
{

    public function dashboard()
    {
        return Inertia::render('Dashboard', [
            'weeks' => fn() => Week::all(),
        ]);
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