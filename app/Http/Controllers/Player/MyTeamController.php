<?php

namespace App\Http\Controllers\Player;
// Namespace: deze controller hoort bij speler-gerelateerde logica

use App\Http\Controllers\Controller;

use App\Models\Game;
// Model dat gekoppeld is aan de 'matches' database tabel

use Illuminate\Http\Request;
// Laravel Request object (bevat input, query params, headers, etc.)

class MyTeamController extends Controller
{
    // Deze methode wordt aangeroepen via de route:
    // GET /me/my-teams (routes/web.php)
    public function index(Request $request)
    {
        // Haalt de momenteel ingelogde gebruiker op
        // Komt uit Laravel authenticatie (session)
        $user = auth()->user();

        // Haalt de 'location' query parameter uit de URL
        // Bijvoorbeeld: /me/my-teams?location=Amsterdam
        $location = $request->query('location');

        // Haalt alle teams op waar de ingelogde speler aan gekoppeld is
        // Dit gebruikt een relatie in het User model: user -> teams
        // with('competition') laadt de competitie alvast (eager loading)
        $teams = $user->teams()->with('competition')->get();

        // Array waarin per team de wedstrijden worden opgeslagen
        $matchesByTeam = [];

        // Loop door elk team van de speler
        foreach ($teams as $team) {

            // Basisquery voor wedstrijden waarin dit team speelt
            // Game model is gekoppeld aan de 'matches' tabel
            $base = Game::with(['homeTeam', 'awayTeam'])
                // Laadt de thuis- en uitploeg relaties (Game model)
                ->where(function ($q) use ($team) {
                    // Filter: team is thuisteam
                    $q->where('home_team_id', $team->id)
                        // OF team is uitteam
                        ->orWhere('away_team_id', $team->id);
                })
                ->when($location, function ($q) use ($location) {
                    // Wordt alleen uitgevoerd als er een locatie-zoekterm is
                    // Filtert wedstrijden op stadion/locatie (LIKE %zoekterm%)
                    // Veilig tegen SQL-injectie door Eloquent
                    $q->where('location', 'like', '%' . $location . '%');
                });


            // Verdeel de wedstrijden per team in:
            // - komende wedstrijden
            // - recente (afgelopen) wedstrijden
            $matchesByTeam[$team->id] = [
                // Komende wedstrijden (vanaf nu)
                'upcoming' => (clone $base)
                    // Clone voorkomt dat de query overschreven wordt
                    ->where('starts_at', '>=', now())
                    // Alleen toekomstige wedstrijden
                    ->orderBy('starts_at')
                    // Eerstvolgende wedstrijd bovenaan
                    ->limit(10)
                    // Maximaal 10 wedstrijden
                    ->get(),
                // Voert de databasequery uit

                // Recente wedstrijden (verleden)
                'recent'   => (clone $base)
                    ->where('starts_at', '<', now())
                    // Alleen wedstrijden in het verleden
                    ->orderByDesc('starts_at')
                    // Meest recente eerst
                    ->limit(10)
                    // Maximaal 10 wedstrijden
                    ->get(),
                // Voert de databasequery uit
            ];
        }

        // Stuurt alle verzamelde data naar de Blade view
        // resources/views/player/myteams/index.blade.php
        return view('player.myteams.index', [
            'teams' => $teams,
            'matchesByTeam' => $matchesByTeam,
            'location' => $location,
        ]);
    }
}
