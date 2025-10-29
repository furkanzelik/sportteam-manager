<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl mt-5 ">
            {{ $isEdit ? 'Aanvraag bewerken' : 'Nieuwe aanvraag maken' }}
        </h2>
    </x-slot>
    <div class="max-w-xl mx-auto p-6 bg-white shadow rounded">
        <form method="POST"
              action="{{ $isEdit
                    ? route('match-requests.update.post', $matchRequest)
                    : route('match-requests.store') }}">
            @csrf
            {{-- Let op: we gebruiken POST-routes, dus GEEN @method('PUT') hier --}}

            <div class="mb-4">
                <label for="game_id" class="block font-medium">Wedstrijd</label>

                {{-- Bij bewerken tonen we de wedstrijd read-only (disabled) --}}
                <select name="game_id" id="game_id"
                        class="w-full border rounded px-2 py-1"
                    {{ $isEdit ? 'disabled' : '' }}>
                    <option value="">-- Kies een wedstrijd --</option>
                    @foreach($games as $game)
                        <option value="{{ $game->id }}"
                            @selected(old('game_id', $matchRequest->game_id ?? '') == $game->id)>
                            {{ $game->homeTeam->name }} vs {{ $game->awayTeam->name }}
                            — {{ \Carbon\Carbon::parse($game->starts_at)->format('d-m-Y H:i') }}
                        </option>
                    @endforeach
                </select>

                {{-- Als disabled, verstuurt de browser het veld niet. Zorg dat we de waarde toch meesturen. --}}
                @if($isEdit && !empty($matchRequest->game_id))
                    <input type="hidden" name="game_id" value="{{ $matchRequest->game_id }}">
                @endif

                @error('game_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="position_needed" class="block font-medium">Positie</label>
                <select name="position_needed" id="position_needed" class="w-full border rounded px-2 py-1">
                    <option value="">-- Kies positie --</option>
                    @foreach($positions as $pos)
                        <option value="{{ $pos }}"
                            @selected(old('position_needed', $matchRequest->position_needed ?? '') == $pos)>
                            {{ $pos }}
                        </option>
                    @endforeach
                </select>
                @error('position_needed') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="players_needed" class="block font-medium">Aantal spelers nodig</label>
                <input type="number" name="players_needed" id="players_needed"
                       class="w-full border rounded px-2 py-1"
                       value="{{ old('players_needed', $matchRequest->players_needed ?? 1) }}"
                       min="1" max="11">
                @error('players_needed') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block font-medium">Beschrijving</label>
                <textarea name="description" id="description"
                          class="w-full border rounded px-2 py-1"
                          placeholder="Optioneel beschrijving...">{{ old('description', $matchRequest->description ?? '') }}</textarea>
                @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            @if($isEdit)
                <div class="mb-4">
                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" name="is_active" value="1"
                            {{ old('is_active', $matchRequest->is_active ? 1 : 0) ? 'checked' : '' }}>
                        <span>Actief</span>
                    </label>
                    @error('is_active') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            @endif

            <div class="flex justify-end">
                <a href="{{ route('player.forum') }}" class="text-gray-600 mr-4">Annuleren</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    {{ $isEdit ? 'Opslaan' : 'Aanvraag plaatsen' }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
