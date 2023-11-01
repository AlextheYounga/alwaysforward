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

Route::group(['prefix' => 'week'], function () {
    Route::get('/now', [BoardController::class, 'index'])
        ->name('week.now');
    Route::get('/{week}', [BoardController::class, 'show'])
        ->name('week.show');
});

Route::group(['prefix' => 'goals'], function () {
    Route::get('/', [GoalController::class, 'index'])
        ->name('goal.now');
    Route::post('/new', [GoalController::class, 'new'])
        ->name('goal.show');
});

Route::group(['prefix' => 'tasks'], function () {
    Route::get('/', [TaskController::class, 'index'])
        ->name('task.now');
    Route::post('/new', [TaskController::class, 'new'])
        ->name('task.show');
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
