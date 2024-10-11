<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Week;

class PagesController extends Controller
{

    public function dashboard()
    {
        return Inertia::render('Dashboard');
    }
}

