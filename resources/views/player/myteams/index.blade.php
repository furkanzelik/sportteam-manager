<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Mijn teams & wedstrijden</h2>
    </x-slot>

    <div class="p-6 space-y-8">
        @forelse($teams as $team)
            <section class="border-b pb-6">
                <h3 class="text-lg font-semibold">
                    {{ $team->name }} — {{ $team->competition->name ?? 'Zonder competitie' }}
                </h3>

                {{-- Komende wedstrijden --}}
                <div class="mt-3">
                    <h4 class="font-semibold">Komende wedstrijden</h4>
                    @if(($matchesByTeam[$team->id]['upcoming'] ?? collect())->isEmpty())
                        <p class="text-sm text-gray-600">Geen geplande wedstrijden.</p>
                    @else
                        <ul class="list-disc ml-5">
                            @foreach($matchesByTeam[$team->id]['upcoming'] as $g)
                                @php
                                    $isHome = $g->home_team_id === $team->id;
                                    $opponent = $isHome ? $g->awayTeam->name : $g->homeTeam->name;
                                @endphp
                                <li>
                                    {{ $isHome ? 'Thuis' : 'Uit' }} tegen <strong>{{ $opponent }}</strong>
                                    — {{ \Carbon\Carbon::parse($g->starts_at)->format('d-m-Y H:i') }}
                                    @if($g->location) — {{ $g->location }} @endif
                                    ({{ $g->status }})
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                {{-- Recente wedstrijden --}}
                <div class="mt-4">
                    <h4 class="font-semibold">Recente wedstrijden</h4>
                    @if(($matchesByTeam[$team->id]['recent'] ?? collect())->isEmpty())
                        <p class="text-sm text-gray-600">Geen recente wedstrijden.</p>
                    @else
                        <ul class="list-disc ml-5">
                            @foreach($matchesByTeam[$team->id]['recent'] as $g)
                                @php
                                    $isHome = $g->home_team_id === $team->id;
                                    $opponent = $isHome ? $g->awayTeam->name : $g->homeTeam->name;
                                @endphp
                                <li>
                                    {{ $isHome ? 'Thuis' : 'Uit' }} tegen <strong>{{ $opponent }}</strong>
                                    — {{ \Carbon\Carbon::parse($g->starts_at)->format('d-m-Y H:i') }}
                                    @if($g->location) — {{ $g->location }} @endif
                                    ({{ $g->status }})
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </section>
        @empty
            <p>Je bent nog niet aan een team gekoppeld.</p>
        @endforelse
    </div>
</x-app-layout>
