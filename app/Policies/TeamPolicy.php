<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;

class TeamPolicy
{
    public function viewAny(User $user): bool // geeft de rol aan coah om teams te beheren
    {
        return $user->role->value === 'coach';
    }

    public function view(User $user, Team $team): bool
    {
        return $user->id === $team->coach_id;
    }

    public function create(User $user): bool
    {
        return $user->role->value === 'coach';
    }

    public function update(User $user, Team $team): bool
    {
        return $user->id === $team->coach_id;
    }

    public function delete(User $user, Team $team): bool
    {
        return $user->id === $team->coach_id;
    }
}
