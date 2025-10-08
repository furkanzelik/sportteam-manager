<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\Game;

class MyTeamController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // speler kan in meerdere teams zitten
        $teams = $user->teams()->with('competition')->get();

        $matchesByTeam = [];
        foreach ($teams as $team) {
            $base = Game::with(['homeTeam','awayTeam'])
                ->where(function ($q) use ($team) {
                    $q->where('home_team_id', $team->id)
                        ->orWhere('away_team_id', $team->id);
                });

            $matchesByTeam[$team->id] = [
                'upcoming' => (clone $base)
                    ->where('starts_at', '>=', now())
                    ->orderBy('starts_at')
                    ->limit(10)
                    ->get(),
                'recent'   => (clone $base)
                    ->where('starts_at', '<', now())
                    ->orderByDesc('starts_at')
                    ->limit(10)
                    ->get(),
            ];
        }

        return view('player.myteams.index', compact('teams', 'matchesByTeam'));
    }
}
