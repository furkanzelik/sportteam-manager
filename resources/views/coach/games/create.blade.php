<x-app-layout>
    <x-slot name="header"><h2>Wedstrijd plannen</h2></x-slot>
    <div class="p-6">
        <form method="POST" action="{{ route('coach.games.store') }}">
            @csrf
            <div>
                <label>Thuisteam</label>
                <select name="home_team_id">
                    @foreach($teams as $t)
                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                    @endforeach
                </select>
                @error('home_team_id') <div>{{ $message }}</div> @enderror
            </div>
            <div class="mt-2">
                <label>Uitteam</label>
                <select name="away_team_id">
                    @foreach($teams as $t)
                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                    @endforeach
                </select>
                @error('away_team_id') <div>{{ $message }}</div> @enderror
            </div>
            <div class="mt-2">
                <label>Datum & tijd</label>
                <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}">
                @error('starts_at') <div>{{ $message }}</div> @enderror
            </div>
            <div class="mt-2">
                <label>Locatie</label>
                <input name="location" value="{{ old('location') }}">
                @error('location') <div>{{ $message }}</div> @enderror
            </div>
            <button class="mt-4" type="submit">Opslaan</button>
        </form>
    </div>
</x-app-layout>
