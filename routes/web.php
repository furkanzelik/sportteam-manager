<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Coach\TeamController;
use App\Http\Controllers\Coach\GameController;
use App\Http\Controllers\Coach\ToggleGameStatusController;
use App\Http\Controllers\Player\ProfileController as PlayerProfileController;
use App\Http\Controllers\Player\MyTeamController;
use App\Http\Controllers\Player\MatchForumController;
use App\Enums\Role; // <<< belangrijk voor rol-check

// Home: ingelogd? -> naar dashboard, anders welcome
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('welcome');
})->name('home');

// Rol-afhankelijk dashboard
Route::get('/dashboard', function () {
    $user = auth()->user();

    if (! $user) {
        return redirect()->route('login');
    }

    // Coach -> coach dashboard
    if ($user->role === Role::Coach) {
        return redirect()->route('coach.dashboard');
    }

    // Speler -> eigen spelersdashboard view
    return view('player.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Auth routes (profiel)
Route::middleware('auth')->group(function () {
    // (Breeze/Jetstream) standaard profiel routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/me/my-teams', [MyTeamController::class, 'index'])->name('player.myteams');
    // Speler-profiel bewerken
    Route::get('/me/profile', [PlayerProfileController::class, 'edit'])->name('player.profile.edit');
    Route::post('/me/profile', [PlayerProfileController::class, 'update'])->name('player.profile.update');

    // Forum over wedstrijden met tekort
    Route::get('/me/forum', [MatchForumController::class, 'index'])->name('player.forum');
    Route::post('/me/forum/{matchRequest}/join', [MatchForumController::class, 'join'])->name('player.forum.join');
    Route::post('/me/forum/{matchRequest}/comment', [MatchForumController::class, 'comment'])->name('player.forum.comment');
    Route::get('/me/forum', [MatchForumController::class, 'index'])->name('player.forum');
    Route::get('/me/forum/{matchRequest}', [MatchForumController::class, 'show'])->name('player.forum.show');
    Route::post('/me/forum/{matchRequest}/comment', [MatchForumController::class, 'comment'])->name('player.forum.comment');

});

// Coach-gebied
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
