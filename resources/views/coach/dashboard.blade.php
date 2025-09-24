<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Coach Dashboard</h2>
    </x-slot>

    <div class="p-6">
        <a href="{{ route('coach.teams.index') }}">Teams beheren</a> |
        <a href="{{ route('coach.games.index') }}">Wedstrijden beheren</a>
    </div>
</x-app-layout>
