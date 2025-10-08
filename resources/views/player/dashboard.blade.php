<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Speler Dashboard</h2>
    </x-slot>

    <div class="flex  p-6 space-x-10 space-y-1">
        <h1 class="font-extrabold text-xl mb-4 " >Welkom, {{ auth()->user()->name }}!</h1>
        <ul class="flex space-x-4 justify-center" >
            <li><a href="{{ route('player.profile.edit') }}" class="px-4 py-2 border border-gray-400 rounded-md hover:bg-gray-100 transition" >Mijn profiel</a></li>
            <li><a href="{{ route('player.myteams') }}" class="px-4 py-2 border border-gray-400 rounded-md hover:bg-gray-100 transition" >Mijn team & Wedstrijden</a></li>
            <li><a href="#" class="px-4 py-2 border border-gray-400 rounded-md hover:bg-gray-100 transition">Feedback</a></li>
            <li><a href="{{ route('player.forum') }}" class="px-4 py-2 border border-gray-400 rounded-md hover:bg-gray-100 transition">Wedstrijden Forum</a></li>
        </ul>
    </div>
</x-app-layout>
