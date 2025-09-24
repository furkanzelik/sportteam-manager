<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamRequest;
use App\Models\Competition;
use App\Models\Team;

class TeamController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Team::class);

        $teams = Team::with('competition')
            ->where('coach_id', auth()->id())
            ->latest()->paginate(10);

        return view('coach.teams.index', compact('teams'));
    }

    public function create()
    {
        $this->authorize('create', Team::class);
        $competitions = Competition::orderBy('name')->pluck('name','id');
        return view('coach.teams.create', compact('competitions'));
    }

    public function store(TeamRequest $request)
    {
        $this->authorize('create', Team::class);

        Team::create([
            'name' => $request->name,
            'coach_id' => auth()->id(),
            'competition_id' => $request->competition_id,
        ]);

        return redirect()->route('coach.teams.index')->with('success', 'Team aangemaakt.');
    }

    public function edit(Team $team)
    {
        $this->authorize('update', $team);
        $competitions = Competition::orderBy('name')->pluck('name','id');
        return view('coach.teams.edit', compact('team','competitions'));
    }

    public function update(TeamRequest $request, Team $team)
    {
        $this->authorize('update', $team);
        $team->update($request->only('name','competition_id'));
        return redirect()->route('coach.teams.index')->with('success', 'Team bijgewerkt.');
    }

    public function destroy(Team $team)
    {
        $this->authorize('delete', $team);
        $team->delete();
        return back()->with('success','Team verwijderd.');
    }
}
