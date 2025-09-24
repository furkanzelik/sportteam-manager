<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePlayerProfileRequest;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $profile = $user->playerProfile()->firstOrCreate([]); // maak aan indien niet bestaat
        return view('player.profile.edit', compact('user','profile'));
    }

    public function update(UpdatePlayerProfileRequest $request)
    {
        $user = auth()->user();
        $profile = $user->playerProfile()->firstOrCreate([]);

        $data = $request->validated();

        // avatar upload
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars','public');
            $data['avatar_path'] = $path;
        }

        $profile->update($data);

        return back()->with('success','Profiel opgeslagen.');
    }
}
