<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Coach\TeamController;
use App\Http\Controllers\Coach\GameController;
use App\Http\Controllers\Coach\ToggleGameStatusController;
use App\Http\Controllers\Player\ProfileController as PlayerProfileController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/me/profile', [PlayerProfileController::class, 'edit'])->name('player.profile.edit');
    Route::post('/me/profile', [PlayerProfileController::class, 'update'])->name('player.profile.update');
});

Route::middleware(['auth', 'coach'])
    ->prefix('coach')
    ->name('coach.')
    ->group(function () {
        Route::get('/', fn () => view('coach.dashboard'))->name('dashboard');

        Route::resource('teams', TeamController::class);
        Route::resource('games', GameController::class);

        // Status-toggle (verplichte eis) via POST naar aparte action
        Route::post('/games/{game}/toggle-status', ToggleGameStatusController::class)
            ->name('games.toggle-status');
    });

require __DIR__.'/auth.php';
