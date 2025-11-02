<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\MatchRequest;
use App\Models\MatchComment;
use App\Models\LoginEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MatchForumController extends Controller
{
    /**
     * Overzicht met kaarten (max 9), met filter op positie.
     */
    public function index(Request $request)
    {
        $positions = ['Keeper', 'Verdediger', 'Middenvelder', 'Aanvaller'];

        // filter uit de querystring
        $validated = $request->validate([
            'position' => ['nullable', Rule::in($positions)],
        ]);

        $position = $validated['position'] ?? null;

        // Haal aanvragen op met filters
        $requests = MatchRequest::with(['game.homeTeam', 'game.awayTeam'])
            ->where('players_needed', '>', 0) // alleen echte tekorten
            ->whereHas('game', fn($q) => $q->where('starts_at', '>=', now())) // alleen toekomstige wedstrijden
            ->when($position, fn($q) => $q->where('position_needed', $position))
            ->orderByDesc('created_at')
            ->limit(9)
            ->get();

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




    // Nieuwe reactie plaatsen op een verzoek.
    public function comment(Request $request, MatchRequest $matchRequest)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        $userId = auth()->id();

        // Diepe validatie wat cursushandleiding wilde gebruiker moet op minstens 5 verschillende dagen hebben ingelogd
        $distinctDays = LoginEvent::where('user_id', $userId)
            ->select(DB::raw('COUNT(DISTINCT DATE(created_at)) as days'))
            ->value('days');

        if ($distinctDays < 5) {
            return back()
                ->withErrors([
                    'message' => "Je moet op minimaal 5 verschillende dagen ingelogd zijn om te mogen reageren. Je bent tot nu toe {$distinctDays} dag(en) ingelogd."
                ])
                ->withInput();
        }

        // Reactie opslaan
        MatchComment::create([
            'match_request_id' => $matchRequest->id,
            'user_id'          => $userId,
            'message'          => $request->message,
        ]);

        return redirect()
            ->route('player.forum.show', $matchRequest->id)
            ->with('success', 'Je reactie is geplaatst!');
    }
}
