<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Wedstrijd details</h2>
    </x-slot>

    <div class="p-6 space-y-6">
        {{-- Wedstrijdinformatie --}}
        <div class="border rounded-lg p-6 shadow-sm bg-white">
            <h3 class="text-2xl font-bold mb-3">
                {{ $matchRequest->game->homeTeam->name }} vs {{ $matchRequest->game->awayTeam->name }}
            </h3>

            <p><strong>Datum:</strong> {{ \Carbon\Carbon::parse($matchRequest->game->starts_at)->format('d-m-Y H:i') }}</p>
            <p><strong>Locatie:</strong> {{ $matchRequest->game->location }}</p>
            <p><strong>Positie gezocht:</strong> {{ $matchRequest->position_needed }}</p>
            <p><strong>Spelers tekort:</strong> {{ $matchRequest->players_needed }}</p>
            @if($matchRequest->description)
                <p class="mt-2 text-gray-700">{{ $matchRequest->description }}</p>
            @endif

            <div class="mt-4">
                <a href="{{ route('player.forum') }}"
                   class="text-blue-600 hover:underline text-sm">← Terug naar overzicht</a>
            </div>
        </div>

        {{-- Reacties / forumgedeelte --}}
        <div class="border rounded-lg p-6 shadow-sm bg-gray-50">
            <h4 class="text-lg font-semibold mb-3">Reacties</h4>

            @forelse($matchRequest->comments as $comment)
                <div class="border-t pt-3 mt-3">
                    <strong>{{ $comment->user->name }}</strong>
                    <span class="text-xs text-gray-500">
                        ({{ $comment->created_at->diffForHumans() }})
                    </span>
                    <p class="text-gray-800 mt-1">{{ $comment->message }}</p>
                </div>
            @empty
                <p class="text-gray-500">Nog geen reacties. Wees de eerste!</p>
            @endforelse

            {{-- Reactieformulier --}}
            <form action="{{ route('player.forum.comment', $matchRequest) }}" method="POST" class="mt-6">
                @csrf
                <textarea name="message" rows="3" class="w-full border rounded-md p-2"
                          placeholder="Plaats een reactie..."></textarea>
                <button type="submit" class="mt-2 px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">
                    Reageer
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
