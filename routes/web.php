<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LifeWeeksController;
use App\Http\Controllers\BoardsController;
use App\Http\Controllers\GoalsController;
use App\Http\Controllers\TasksController;
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
    Route::get('/', [BoardsController::class, 'index'])
        ->name('board');
    Route::get('/{week}', [BoardsController::class, 'show'])
        ->name('board.show');
    Route::post('/update', [BoardsController::class, 'update'])
        ->name('board.update');
});

Route::group(['prefix' => 'goals'], function () {
    Route::get('/', [GoalsController::class, 'index'])
        ->name('goals');
    Route::get('/fetch', [GoalsController::class, 'fetch'])
        ->name('goals.fetch');
    Route::post('/new', [GoalsController::class, 'new'])
        ->name('goal.new');
    Route::post('/update', [GoalsController::class, 'update'])
        ->name('goal.update');
});

Route::group(['prefix' => 'tasks'], function () {
    Route::get('/', [TasksController::class, 'index'])
        ->name('tasks');
    Route::post('/new', [TasksController::class, 'new'])
        ->name('task.new');
    Route::post('/update', [TasksController::class, 'update'])
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
