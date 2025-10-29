<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class MyTeamController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $location = $request->query('location'); // zoekterm uit de URL (?location=...)

        // speler kan in meerdere teams zitten
        $teams = $user->teams()->with('competition')->get();

        $matchesByTeam = [];

        foreach ($teams as $team) {
            // basisquery: wedstrijden waar dit team in speelt
            $base = Game::with(['homeTeam', 'awayTeam'])
                ->where(function ($q) use ($team) {
                    $q->where('home_team_id', $team->id)
                        ->orWhere('away_team_id', $team->id);
                })
                ->when($location, function ($q) use ($location) {
                    // filter op locatie (LIKE = gedeeltelijke match)
                    $q->where('location', 'like', '%' . $location . '%');
                });

            // verdeel in "komend" en "recent"
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

        // geef de zoekterm mee aan de view
        return view('player.myteams.index', [
            'teams' => $teams,
            'matchesByTeam' => $matchesByTeam,
            'location' => $location,
        ]);
    }
}
