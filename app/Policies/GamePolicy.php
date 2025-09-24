<?php

namespace App\Policies;

use App\Models\Game;
use App\Models\User;

class GamePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role->value === 'coach';
    }

    public function view(User $user, Game $game): bool
    {
        return $user->id === $game->homeTeam->coach_id || $user->id === $game->awayTeam->coach_id;
    }

    public function create(User $user): bool
    {
        return $user->role->value === 'coach';
    }

    public function update(User $user, Game $game): bool
    {
        return $user->id === $game->homeTeam->coach_id || $user->id === $game->awayTeam->coach_id;
    }

    public function delete(User $user, Game $game): bool
    {
        return $user->id === $game->homeTeam->coach_id || $user->id === $game->awayTeam->coach_id;
    }
}
