<x-app-layout>
    <x-slot name="header"><h2>Wedstrijden</h2></x-slot>
    <div class="p-6">
        <a href="{{ route('coach.games.create') }}">+ Wedstrijd</a>
        @if(session('success')) <div class="mt-2">{{ session('success') }}</div> @endif

        <table class="mt-4">
            <thead><tr><th>Thuis</th><th>Uit</th><th>Datum</th><th>Status</th><th>Acties</th></tr></thead>
            <tbody>
            @foreach($games as $g)
                <tr>
                    <td>{{ $g->homeTeam->name }}</td>
                    <td>{{ $g->awayTeam->name }}</td>
                    <td>{{ $g->starts_at->format('d-m-Y H:i') }}</td>
                    <td>{{ $g->status }}</td>
                    <td>
                        <form method="POST" action="{{ route('coach.games.toggle-status', $g) }}" style="display:inline">
                            @csrf
                            <button type="submit">
                                {{ $g->status === 'scheduled' ? 'Markeer voltooid' : 'Terug naar gepland' }}
                            </button>
                        </form>
                        <a href="{{ route('coach.games.edit', $g) }}">Bewerken</a>
                        <form method="POST" action="{{ route('coach.games.destroy', $g) }}" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Verwijderen?')">Verwijder</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $games->links() }}
    </div>
</x-app-layout>
