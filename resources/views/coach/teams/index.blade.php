<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl">Teams</h2>
            <a href="{{ route('coach.teams.create') }}"
               class="inline-flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-white text-sm hover:bg-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                 d="M12 4v16m8-8H4"/></svg>
                Team toevoegen
            </a>
        </div>
    </x-slot>

    <div class="p-6 space-y-6">
        {{-- Flash messages --}}
        @if (session('success'))
            <div class="rounded-md border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                🔴 {{ session('error') }}
            </div>
        @endif

        {{-- Zoeken (optioneel) --}}
        <form method="GET" action="{{ route('coach.teams.index') }}" class="flex items-center gap-2">
            <div class="relative w-full max-w-sm">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Zoek op teamnaam of competitie…"
                       class="w-full rounded-md border px-3 py-2 pr-8 text-sm focus:outline-none focus:ring focus:ring-blue-200">
                <span class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-gray-400">
                    🔎
                </span>
            </div>
            <button class="rounded-md border px-3 py-2 text-sm hover:bg-gray-50">Zoek</button>
            @if(request()->has('q') && request('q') !== '')
                <a href="{{ route('coach.teams.index') }}" class="text-sm text-gray-600 hover:underline">Reset</a>
            @endif
        </form>

        @if($teams->count())
            <div class="overflow-x-auto rounded-lg border bg-white shadow-sm">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 sticky top-0">
                    <tr class="text-left text-gray-600">
                        <th class="px-4 py-3 font-medium">Naam</th>
                        <th class="px-4 py-3 font-medium">Competitie</th>
                        <th class="px-4 py-3 font-medium">Spelers</th>
                        <th class="px-4 py-3 font-medium">Aangemaakt</th>
                        <th class="px-4 py-3 font-medium text-right">Acties</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y">
                    @foreach($teams as $team)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">
                                {{ $team->name }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $team->competition->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                {{ optional($team->players)->count() ?? 0 }}
                            </td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ optional($team->created_at)->format('d-m-Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('coach.teams.edit', $team) }}"
                                       class="rounded-md border px-3 py-1.5 text-xs hover:bg-gray-50">
                                        Bewerken
                                    </a>
                                    <form action="{{ route('coach.teams.destroy', $team) }}"
                                          method="POST"
                                          onsubmit="return confirm('Weet je zeker dat je dit team wilt verwijderen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="rounded-md bg-red-600 px-3 py-1.5 text-xs text-white hover:bg-red-700">
                                            Verwijderen
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginatie (indien je paginate() gebruikt) --}}
            @if(method_exists($teams, 'links'))
                <div>
                    {{ $teams->links() }}
                </div>
            @endif
        @else
            {{-- Lege staat --}}
            <div class="rounded-lg border bg-white p-8 text-center shadow-sm">
                <div class="mx-auto mb-3 h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">⚽️</div>
                <h3 class="text-lg font-semibold">Nog geen teams</h3>
                <p class="mt-1 text-sm text-gray-600">Begin met het toevoegen van je eerste team.</p>
                <div class="mt-4">
                    <a href="{{ route('coach.teams.create') }}"
                       class="inline-flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-white text-sm hover:bg-blue-700">
                        + Team toevoegen
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
