<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LifeWeeksController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\TaskController;
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

Route::group(['prefix' => 'board'], function () {
    Route::get('/', [BoardController::class, 'index'])
        ->name('board');
    Route::get('/{week}', [BoardController::class, 'show'])
        ->name('board.show');
    Route::post('/update', [BoardController::class, 'update'])
        ->name('board.update');
});

Route::group(['prefix' => 'goals'], function () {
    Route::get('/', [GoalController::class, 'index'])
        ->name('goals');
    Route::get('/fetch', [GoalController::class, 'fetch'])
        ->name('goals.fetch');
    Route::post('/new', [GoalController::class, 'new'])
        ->name('goal.new');
    Route::post('/update', [GoalController::class, 'update'])
        ->name('goal.update');
});

Route::group(['prefix' => 'tasks'], function () {
    Route::get('/', [TaskController::class, 'index'])
        ->name('tasks');
    Route::post('/new', [TaskController::class, 'new'])
        ->name('task.new');
    Route::post('/update', [TaskController::class, 'update'])
        ->name('task.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Route::get('/dashboard', [PagesController::class, 'dashboard'])
// ->middleware(['auth', 'verified'])
// ->name('dashboard');


require __DIR__ . '/auth.php';
