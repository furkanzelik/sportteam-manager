<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\MatchRequest;
use App\Models\MatchComment;
use Illuminate\Http\Request;

class MatchForumController extends Controller
{
    // overzichtspagina met alle openstaande wedstrijden
    public function index()
    {
        $requests = MatchRequest::with(['game.homeTeam', 'game.awayTeam'])
            ->orderByDesc('created_at')
            ->take(9)
            ->get();

        return view('player.match-forum.index', compact('requests'));
    }

    // detailpagina voor één wedstrijd
    public function show(MatchRequest $matchRequest)
    {
        $matchRequest->load(['game.homeTeam', 'game.awayTeam', 'comments.user']);
        return view('player.match-forum.show', compact('matchRequest'));
    }

    // reactie plaatsen
    public function comment(Request $request, MatchRequest $matchRequest)
    {
        $request->validate(['message' => 'required|string|max:500']);

        MatchComment::create([
            'match_request_id' => $matchRequest->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return back()->with('success', 'Je reactie is geplaatst!');
    }
}
