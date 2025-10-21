<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\MatchRequest;
use App\Models\MatchComment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MatchForumController extends Controller
{
    /**
     * Overzicht met kaarten (max 9), met filter op positie.
     */
    public function index(Request $request)
    {
        // Posities die in jouw app voorkomen (pas gerust aan)
        $positions = ['Keeper', 'Verdediger', 'Middenvelder', 'Aanvaller'];

        // Valideer (optionele) filter uit de querystring
        $validated = $request->validate([
            'position' => ['nullable', Rule::in($positions)],
        ]);

        $position = $validated['position'] ?? null;

        $requests = MatchRequest::with(['game.homeTeam', 'game.awayTeam'])
            ->where('players_needed', '>', 0)                                // alleen echte tekorten
            ->whereHas('game', fn ($q) => $q->where('starts_at', '>=', now()))// alleen toekomstige wedstrijden
            ->when($position, fn ($q) => $q->where('position_needed', $position))
            ->orderByDesc('created_at')
            ->limit(9) // exact 9 cards
            ->get();

        // Geef de lijst + huidige selectie mee aan de view
        return view('player.match-forum.index', [
            'requests'  => $requests,
            'positions' => $positions,
            'filter'    => $position,
        ]);
    }

    /**
     * Detailpagina van één verzoek met comments.
     */
    public function show(MatchRequest $matchRequest)
    {
        $matchRequest->load(['game.homeTeam', 'game.awayTeam', 'comments.user']);

        return view('player.match-forum.show', compact('matchRequest'));
    }

    /**
     * Nieuwe reactie plaatsen op een verzoek.
     */
    public function comment(Request $request, MatchRequest $matchRequest)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        MatchComment::create([
            'match_request_id' => $matchRequest->id,
            'user_id'          => auth()->id(),
            'message'          => $request->message,
        ]);

        return back()->with('success', 'Je reactie is geplaatst!');
    }
}
