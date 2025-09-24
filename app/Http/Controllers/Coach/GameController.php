<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Http\Requests\GameRequest;
use App\Models\Game;
use App\Models\Team;

class GameController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Game::class);

        $coachTeamIds = Team::where('coach_id', auth()->id())->pluck('id');

        $games = Game::with(['homeTeam','awayTeam'])
            ->whereIn('home_team_id', $coachTeamIds)
            ->orWhereIn('away_team_id', $coachTeamIds)
            ->orderBy('starts_at')
            ->paginate(10);

        return view('coach.games.index', compact('games'));
    }

    public function create()
    {
        $this->authorize('create', Game::class);
        $teams = Team::where('coach_id', auth()->id())->orderBy('name')->get();
        return view('coach.games.create', compact('teams'));
    }

    public function store(GameRequest $request)
    {
        $this->authorize('create', Game::class);
        Game::create($request->validated());
        return redirect()->route('coach.games.index')->with('success','Wedstrijd ingepland.');
    }

    public function edit(Game $game)
    {
        $this->authorize('update', $game);
        $teams = Team::where('coach_id', auth()->id())->orderBy('name')->get();
        return view('coach.games.edit', compact('game','teams'));
    }

    public function update(GameRequest $request, Game $game)
    {
        $this->authorize('update', $game);
        $game->update($request->validated());
        return redirect()->route('coach.games.index')->with('success','Wedstrijd bijgewerkt.');
    }

    public function destroy(Game $game)
    {
        $this->authorize('delete', $game);
        $game->delete();
        return back()->with('success','Wedstrijd verwijderd.');
    }
}
