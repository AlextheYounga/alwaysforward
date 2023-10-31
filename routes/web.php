<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LifeWeeksController;
use App\Http\Controllers\BoardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [PagesController::class, 'dashboard'])
->name('dashboard');

Route::get('/life', [LifeWeeksController::class, 'index'])
->name('life');

Route::get('/week/now', [BoardController::class, 'index'])
->name('kanban');

Route::get('/week/{week}', [BoardController::class, 'show'])
->name('kanban');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Route::get('/dashboard', [PagesController::class, 'dashboard'])
// ->middleware(['auth', 'verified'])
// ->name('dashboard');


require __DIR__ . '/auth.php';
