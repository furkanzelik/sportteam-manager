<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl">Wedstrijden</h2>

            <a href="{{ route('coach.games.create') }}"
               class="inline-flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-white text-sm hover:bg-blue-700"> + Nieuwe wedstrijd</a>
        </div>
    </x-slot>

    <div class="p-6 space-y-6">
        {{-- melding voor toevoegingen --}}
        @if(session('success'))
            <div class="rounded-md border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                🔴 {{ session('error') }}
            </div>
        @endif

        @if($games->count())
            <div class="overflow-x-auto rounded-lg border bg-white shadow-sm">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                    <tr class="text-left text-gray-600">
                        <th class="px-4 py-3 font-medium">Thuis</th>
                        <th class="px-4 py-3 font-medium">Uit</th>
                        <th class="px-4 py-3 font-medium">Datum</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium text-right">Acties</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y">
                    @foreach($games as $g)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">
                                {{ $g->homeTeam->name }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $g->awayTeam->name }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                @php
                                    $dt = $g->starts_at instanceof \Carbon\Carbon
                                        ? $g->starts_at
                                        : \Carbon\Carbon::parse($g->starts_at);
                                @endphp
                                {{ $dt->format('d-m-Y H:i') }}
                                @if(!empty($g->location))
                                    <span class="text-gray-400">·</span> {{ $g->location }}
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($g->status === 'completed')
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">Voltooid</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-700">Gepland</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Toggle status --}}
                                    <form method="POST" action="{{ route('coach.games.toggle-status', $g) }}">
                                        @csrf
                                        <button type="submit"
                                                class="rounded-md border px-3 py-1.5 text-xs hover:bg-gray-50">
                                            {{ $g->status === 'scheduled' ? 'Markeer voltooid' : 'Zet terug naar gepland' }}
                                        </button>
                                    </form>

                                    {{-- Bewerken --}}
                                    <a href="{{ route('coach.games.edit', $g) }}"
                                       class="rounded-md border px-3 py-1.5 text-xs hover:bg-gray-50">
                                        Bewerken
                                    </a>

                                    {{-- Verwijderen --}}
                                    <form method="POST" action="{{ route('coach.games.destroy', $g) }}"
                                          onsubmit="return confirm('Weet je zeker dat je deze wedstrijd wilt verwijderen?');">
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

            {{-- Paginatie --}}
            <div class="pt-2">
                {{ $games->links() }}
            </div>
        @else
            {{-- als er geen wedstrijden zijn --}}
            <div class="rounded-lg border bg-white p-8 text-center shadow-sm">
                <div class="mx-auto mb-3 h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">📅</div>
                <h3 class="text-lg font-semibold">Nog geen wedstrijden</h3>
                <p class="mt-1 text-sm text-gray-600">Plan je eerste wedstrijd om te beginnen.</p>
                <div class="mt-4">
                    <a href="{{ route('coach.games.create') }}"
                       class="inline-flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-white text-sm hover:bg-blue-700">
                        + Nieuwe wedstrijd
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
