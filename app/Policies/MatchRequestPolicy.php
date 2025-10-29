<?php

namespace App\Policies;

use App\Models\MatchRequest;
use App\Models\User;

class MatchRequestPolicy
{
    public function update(User $user, MatchRequest $request): bool
    {
        return $user->id === $request->created_by;
    }

    public function delete(User $user, MatchRequest $request): bool
    {
        return $user->id === $request->created_by;
    }
}
