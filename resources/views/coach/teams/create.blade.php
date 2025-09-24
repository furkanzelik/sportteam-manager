<x-app-layout>
    <x-slot name="header"><h2>Nieuw team</h2></x-slot>
    <div class="p-6">
        <form method="POST" action="{{ route('coach.teams.store') }}">
            @csrf
            <div>
                <label>Naam</label>
                <input name="name" value="{{ old('name') }}">
                @error('name') <div>{{ $message }}</div> @enderror
            </div>
            <div class="mt-2">
                <label>Competitie</label>
                <select name="competition_id">
                    @foreach($competitions as $id=>$name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                @error('competition_id') <div>{{ $message }}</div> @enderror
            </div>
            <button class="mt-4" type="submit">Opslaan</button>
        </form>
    </div>
</x-app-layout>
