<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Game;

class ToggleGameStatusController extends Controller
{
    public function __invoke(Game $game)
    {
        $this->authorize('update', $game);

        $game->status = $game->status === 'scheduled' ? 'completed' : 'scheduled';
        $game->save();

        return back()->with('success', 'Wedstrijdstatus gewijzigd.');
    }
}
