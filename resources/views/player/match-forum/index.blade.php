<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Wedstrijden met spelers tekort</h2>
    </x-slot>
    <div class="flex justify-end mt-5">
        <a href="{{ route('match-requests.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition">
            + Nieuwe aanvraag maken
        </a>
    </div>
    <div class="p-6">
        <div class="flex justify-end mb-4">
            <form method="GET" action="{{ route('player.forum') }}">
                <label for="position" class="text-sm font-medium mr-2"></label>
                <select name="position" id="position" class="border rounded-md px-5 py-1 text-sm" onchange="this.form.submit()">
                    <option value="">Alle posities</option>
                    @foreach($positions as $pos)
                        <option value="{{ $pos }}" @selected($filter === $pos)>{{ $pos }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($requests as $req)
                <div class="border rounded-lg shadow hover:shadow-md transition p-4 bg-white">
                    <h3 class="text-lg font-bold mb-1">
                        {{ $req->game->homeTeam->name }} vs {{ $req->game->awayTeam->name }}
                    </h3>
                    <p class="text-sm text-gray-700 mb-1">
                        📅 {{ \Carbon\Carbon::parse($req->game->starts_at)->format('d-m-Y H:i') }}
                    </p>
                    <p class="text-sm text-gray-700 mb-1">🏟️ {{ $req->game->location }}</p>
                    <p class="text-sm mb-1">
                        🎯 <strong>{{ $req->position_needed }}</strong> gezocht
                    </p>
                    <p class="text-sm text-gray-600 mb-3">
                        👥 Nog {{ $req->players_needed }} speler(s) nodig
                    </p>
                    <a href="{{ route('player.forum.show', $req) }}"
                       class="inline-block mt-auto bg-blue-600 text-white text-sm px-4 py-2 rounded-md hover:bg-blue-700">
                        Meer info →
                    </a>
                </div>
            @empty
                <p class="text-gray-600">Er zijn momenteel geen wedstrijden met spelers tekort.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
