<x-app-layout>
    <x-slot name="header"><h2>Teams</h2></x-slot>
    <div class="p-6">
        <a href="{{ route('coach.teams.create') }}">+ Team</a>
        @if(session('success')) <div class="mt-2">{{ session('success') }}</div> @endif
        <table class="mt-4">
            <thead><tr><th>Naam</th><th>Competitie</th><th>Acties</th></tr></thead>
            <tbody>
            @foreach($teams as $team)
                <tr>
                    <td>{{ $team->name }}</td>
                    <td>{{ $team->competition->name ?? '-' }}</td>
                    <td>
                        <a href="{{ route('coach.teams.edit', $team) }}">Bewerken</a>
                        <form method="POST" action="{{ route('coach.teams.destroy', $team) }}" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Verwijderen?')">Verwijder</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $teams->links() }}
    </div>
</x-app-layout>
