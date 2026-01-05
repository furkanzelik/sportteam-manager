<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Coach\TeamController;
use App\Http\Controllers\Coach\GameController;
use App\Http\Controllers\Coach\ToggleGameStatusController;
use App\Http\Controllers\Player\ProfileController as PlayerProfileController;
use App\Http\Controllers\Player\MyTeamController;
use App\Http\Controllers\Player\MatchForumController;
use App\Http\Controllers\MatchRequestController;
use App\Enums\Role;



// Startpagina stuurt door naar dashboard als gebruiker is ingelogd
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('welcome');
})->name('home');

// Dashboard is alleen bereikbaar voor ingelogde & geverifieerde gebruikers
Route::get('/dashboard', function () {
    $user = auth()->user();

    if (! $user) {
        return redirect()->route('login');
    }

    //  coach dashboard
    if ($user->role === Role::Coach) {
        return redirect()->route('coach.dashboard');
    }

    //  spelersdashboard
    return view('player.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');




// Alleen ingelogde gebruikers hebben hier toegang toe.
Route::middleware('auth')->group(function () {

    // Breeze profiel (mail, password)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Spelerprofiel (Nummer, positie, ect..)
    Route::get('/me/profile', [PlayerProfileController::class, 'edit'])->name('player.profile.edit');
    Route::post('/me/profile', [PlayerProfileController::class, 'update'])->name('player.profile.update');



    // Toont alleen teams en wedstrijden waar de ingelogde speler aan gekoppeld is.

    Route::get('/me/my-teams', [MyTeamController::class, 'index'])->name('player.myteams');



    // MATCH FORUM (WEDSTRIJDEN MET SPELERS TEKORT)

    Route::get('/me/forum', [MatchForumController::class, 'index'])->name('player.forum');
    Route::get('/me/forum/{matchRequest}', [MatchForumController::class, 'show'])->name('player.forum.show');
    Route::post('/me/forum/{matchRequest}/comment', [MatchForumController::class, 'comment'])->name('player.forum.comment');
    Route::post('/me/forum/{matchRequest}/join', [MatchForumController::class, 'join'])->name('player.forum.join');



    // MATCH REQUESTS (AANVRAGEN VOOR EXTRA SPELERS)

    Route::get('/match-requests/create', [MatchRequestController::class, 'create'])->name('match-requests.create');
    Route::post('/match-requests', [MatchRequestController::class, 'store'])->name('match-requests.store');
    Route::get('/match-requests/{matchRequest}/edit', [MatchRequestController::class, 'edit'])->name('match-requests.edit');
    Route::post('/match-requests/{matchRequest}/update', [MatchRequestController::class, 'updatePost'])->name('match-requests.update.post');
    Route::post('/match-requests/{matchRequest}/delete', [MatchRequestController::class, 'destroyPost'])->name('match-requests.destroy.post');
    Route::post('/match-requests/{matchRequest}/toggle', [MatchRequestController::class, 'toggleActive'])->name('match-requests.toggle');
});


// Alleen toegankelijk voor ingelogde Coach
Route::middleware(['auth', 'coach'])
    ->prefix('coach')
    ->name('coach.')
    ->group(function () {
        Route::get('/', fn () => view('coach.dashboard'))->name('dashboard');
        Route::resource('teams', TeamController::class);
        Route::resource('games', GameController::class);

        // Extra route voor status toggle
        Route::post('/games/{game}/toggle-status', ToggleGameStatusController::class)
            ->name('games.toggle-status');
    });



// Laravel’s standaard authenticatie-routes (login, register, logout, etc.)
require __DIR__.'/auth.php';
