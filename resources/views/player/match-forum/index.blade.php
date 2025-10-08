<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Wedstrijden met spelers tekort</h2>
    </x-slot>

    <div class="p-6">
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
