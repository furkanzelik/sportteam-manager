<x-app-layout>
    <x-slot name="header"><h2>Mijn profiel</h2></x-slot>
    <div class="p-6 space-y-4 mb-4">
        @if(session('success')) <div>{{ session('success') }}</div> @endif
        <form method="POST" action="{{ route('player.profile.update') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4 font-bold" >
                <label>Rugnummer</label>
                <input name="number" type="number" value="{{ old('number', $profile->number) }}">
                @error('number')<div>{{ $message }}</div>@enderror
            </div>
            <div class="mb-4 font-bold">
                <label>Positie</label>
                <input name="position" value="{{ old('position', $profile->position) }}">
                @error('position')<div>{{ $message }}</div>@enderror
            </div>
            <div class="mb-4 font-bold">
                <label>Bio</label>
                <textarea name="bio">{{ old('bio', $profile->bio) }}</textarea>
                @error('bio')<div>{{ $message }}</div>@enderror
            </div>
            <div class="mb-4 font-bold">
                <label>Avatar (jpg/png/webp)</label>
                <input type="file" name="avatar" accept="image/*">
                @error('avatar')<div>{{ $message }}</div>@enderror
                @if($profile->avatar_path)
                    <div class="mt-2">
                        <img src="{{ asset('storage/'.$profile->avatar_path) }}" alt="avatar" style="max-width:120px">
                    </div>
                @endif
            </div>
            <button class=" px-4 mt-4 py-2 border border-gray-400 divide-blue-600" type="submit">Opslaan</button>
        </form>
    </div>
</x-app-layout>
