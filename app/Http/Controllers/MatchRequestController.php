<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Game;
use App\Models\MatchRequest;
use App\Models\Team;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MatchRequestController extends Controller
{
    protected array $positions = ['Keeper', 'Verdediger', 'Middenvelder', 'Aanvaller'];

    /**
     * Formulier voor aanmaken (coach = alle eigen teams, speler = eigen teams)
     */
    public function create()
    {
        $user = auth()->user();

        if ($user->role === Role::Coach) {
            $teamIds = Team::where('coach_id', $user->id)->pluck('id');
        } else {
            // qualify i.v.m. pivot id
            $teamIds = $user->teams()->select('teams.id')->pluck('teams.id');
        }

        $games = Game::with(['homeTeam', 'awayTeam'])
            ->where(function ($q) use ($teamIds) {
                $q->whereIn('home_team_id', $teamIds)
                    ->orWhereIn('away_team_id', $teamIds);
            })
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at')
            ->get();

        return view('match-requests.form', [
            'matchRequest' => new MatchRequest(),
            'games'        => $games,
            'positions'    => $this->positions,
            'isEdit'       => false,
        ]);
    }

    /**
     * Opslaan (POST)
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'game_id'         => ['required', Rule::exists('matches', 'id')],
            'position_needed' => ['required', Rule::in($this->positions)],
            'players_needed'  => ['required', 'integer', 'min:1', 'max:11'],
            'description'     => ['nullable', 'string', 'max:1000'],
        ]);

        $teamIds = ($user->role === Role::Coach)
            ? Team::where('coach_id', $user->id)->pluck('id')
            : $user->teams()->select('teams.id')->pluck('teams.id'); // qualify

        $isAllowed = Game::where('id', $data['game_id'])
            ->where(function ($q) use ($teamIds) {
                $q->whereIn('home_team_id', $teamIds)
                    ->orWhereIn('away_team_id', $teamIds);
            })
            ->exists();

        if (! $isAllowed) {
            return back()->withErrors(['game_id' => 'Je mag alleen aanvragen maken voor jouw wedstrijden.'])
                ->withInput();
        }

        MatchRequest::create([
            ...$data,
            'created_by' => $user->id,
            'is_active'  => true, // standaard actief
        ]);

        return redirect()->route('player.forum')->with('success', 'Aanvraag aangemaakt.');
    }

    /**
     * Bewerken-formulier (GET)
     */
    public function edit(MatchRequest $matchRequest)
    {
        $this->authorize('update', $matchRequest);

        $user = auth()->user();

        $teamIds = ($user->role === Role::Coach)
            ? Team::where('coach_id', $user->id)->pluck('id')
            : $user->teams()->select('teams.id')->pluck('teams.id'); // qualify

        $games = Game::with(['homeTeam', 'awayTeam'])
            ->where(function ($q) use ($teamIds) {
                $q->whereIn('home_team_id', $teamIds)
                    ->orWhereIn('away_team_id', $teamIds);
            })
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at')
            ->get();

        return view('match-requests.form', [
            'matchRequest' => $matchRequest,
            'games'        => $games,
            'positions'    => $this->positions,
            'isEdit'       => true,
        ]);
    }

    /**
     * Bewerken-opslaan via POST (ipv PUT)
     * @throws AuthorizationException
     */
    public function updatePost(Request $request, MatchRequest $matchRequest): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('update', $matchRequest);

        $data = $request->validate([
            'position_needed' => ['required', Rule::in($this->positions)],
            'players_needed'  => ['required', 'integer', 'min:1', 'max:11'],
            'description'     => ['nullable', 'string', 'max:1000'],
            'is_active'       => ['nullable', 'boolean'],
        ]);

        if ($request->has('is_active')) {
            $data['is_active'] = (bool) $request->boolean('is_active');
        }

        $matchRequest->update($data);

        return redirect()->route('player.forum')->with('success', 'Aanvraag bijgewerkt.');
    }

    /**
     * Verwijderen via POST (ipv DELETE)
     */
    public function destroyPost(Request $request, MatchRequest $matchRequest)
    {
        $this->authorize('delete', $matchRequest);

        $matchRequest->delete();

        return redirect()->route('player.forum')->with('success', 'Aanvraag verwijderd.');
    }

    /**
     * Actief/Inactief togglen via POST
     * @throws AuthorizationException
     */
    public function toggleActive(Request $request, MatchRequest $matchRequest): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('update', $matchRequest);

        $matchRequest->update([
            'is_active' => ! (bool) $matchRequest->is_active,
        ]);

        return back()->with('success', 'Status gewijzigd naar: ' . ($matchRequest->is_active ? 'Actief' : 'Inactief'));
    }

    /**
     * (Nog aanwezig voor compatibiliteit – niet gebruikt als je POST-routes gebruikt)
     */
    public function update(Request $request, MatchRequest $matchRequest): void
    {
        // Laat leeg of verwijs intern door naar updatePost, afhankelijk van jouw routes.
        abort(405);
    }

    public function destroy(MatchRequest $matchRequest): void
    {
        // Laat leeg of verwijs intern door naar destroyPost, afhankelijk van jouw routes.
        abort(405);
    }
}
