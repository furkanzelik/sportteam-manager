<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sportteam Manager') }}</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
{{-- Topbar --}}
<header class="border-b bg-white/90 backdrop-blur">
    <div class="mx-auto max-w-6xl px-4 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="h-8 w-8 rounded-md bg-emerald-600 flex items-center justify-center text-white font-bold">⚽️</div>
            <span class="font-semibold">{{ config('app.name', 'Sportteam Manager') }}</span>
        </div>

        <nav class="flex items-center gap-3">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm hover:underline">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm rounded-md border px-3 py-1.5 hover:bg-gray-50">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-sm rounded-md bg-emerald-600 px-3 py-1.5 text-white hover:bg-emerald-700">
                            Register
                        </a>
                    @endif
                @endauth
            @endif
        </nav>
    </div>
</header>

<main>
    {{-- Hero: Voetbalveld-look --}}
    <section class="relative">
        <div class="mx-auto max-w-6xl px-4 py-12 lg:py-16 grid gap-10 lg:grid-cols-2 items-center">
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight">
                    Beheer je <span class="text-emerald-600">teams</span>, plan <span class="text-emerald-600">wedstrijden</span> en vind snel <span class="text-emerald-600">invallers</span>.
                </h1>
                <p class="mt-4 text-gray-600">
                    Alles wat je nodig hebt als coach of speler: teams aanmaken, spelers beheren,
                    wedstrijden plannen en een forum voor spelerstekorten — overzichtelijk en simpel.
                </p>
                <div class="mt-6 flex items-center gap-3">
                    @guest
                        <a href="{{ route('register') }}"
                           class="rounded-md bg-emerald-600 px-5 py-2.5 text-white font-medium hover:bg-emerald-700">
                            Aan de slag
                        </a>
                        <a href="{{ route('login') }}"
                           class="rounded-md border px-5 py-2.5 font-medium hover:bg-gray-50">
                            Ik heb al een account
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}"
                           class="rounded-md bg-emerald-600 px-5 py-2.5 text-white font-medium hover:bg-emerald-700">
                            Naar dashboard
                        </a>
                    @endguest
                </div>

                {{-- Highlights --}}
                <div class="mt-10 grid sm:grid-cols-2 gap-4">
                    <div class="rounded-lg border bg-white p-4">
                        <div class="text-2xl">📋</div>
                        <h3 class="mt-2 font-semibold">Teams & Competities</h3>
                        <p class="text-sm text-gray-600 mt-1">Maak teams aan, koppel spelers en beheer competities.</p>
                    </div>
                    <div class="rounded-lg border bg-white p-4">
                        <div class="text-2xl">📅</div>
                        <h3 class="mt-2 font-semibold">Wedstrijden plannen</h3>
                        <p class="text-sm text-gray-600 mt-1">Plan, bewerk en toggle eenvoudig wedstrijdstatussen.</p>
                    </div>
                    <div class="rounded-lg border bg-white p-4">
                        <div class="text-2xl">🧑‍🤝‍🧑</div>
                        <h3 class="mt-2 font-semibold">Spelerstekort forum</h3>
                        <p class="text-sm text-gray-600 mt-1">Maak een oproep en laat spelers reageren/aanhaken.</p>
                    </div>
                    <div class="rounded-lg border bg-white p-4">
                        <div class="text-2xl">🛡️</div>
                        <h3 class="mt-2 font-semibold">Beveiliging</h3>
                        <p class="text-sm text-gray-600 mt-1">Rollen & rechten, valide invoer en CSRF-bescherming.</p>
                    </div>
                </div>
            </div>

            {{-- “Voetbalveld” kaart --}}
            <div class="order-first lg:order-none">
                <div class="rounded-2xl border shadow-sm overflow-hidden bg-emerald-700">
                    <div class="aspect-video relative">
                        {{-- veld achtergrond --}}
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-700 to-emerald-600"></div>

                        {{-- veld lijnen (SVG) --}}
                        <svg class="absolute inset-0 h-full w-full opacity-90" viewBox="0 0 100 56" preserveAspectRatio="none">
                            {{-- buitenlijn --}}
                            <rect x="3" y="3" width="94" height="50" fill="none" stroke="#f0fdf4" stroke-width="0.6" rx="1.5"/>
                            {{-- middenlijn + middencirkel --}}
                            <line x1="50" y1="3" x2="50" y2="53" stroke="#f0fdf4" stroke-width="0.5"/>
                            <circle cx="50" cy="28" r="5" fill="none" stroke="#f0fdf4" stroke-width="0.5"/>

                            {{-- 16-meter gebieden --}}
                            <rect x="3"  y="16" width="12" height="24" fill="none" stroke="#f0fdf4" stroke-width="0.5"/>
                            <rect x="85" y="16" width="12" height="24" fill="none" stroke="#f0fdf4" stroke-width="0.5"/>

                            {{-- doelgebieden --}}
                            <rect x="3"  y="22" width="6" height="12" fill="none" stroke="#f0fdf4" stroke-width="0.5"/>
                            <rect x="91" y="22" width="6" height="12" fill="none" stroke="#f0fdf4" stroke-width="0.5"/>

                            {{-- penaltystippen --}}
                            <circle cx="12" cy="28" r="0.6" fill="#f0fdf4"/>
                            <circle cx="88" cy="28" r="0.6" fill="#f0fdf4"/>
                        </svg>

                        {{-- “scorebord” overlay --}}
                        <div class="absolute top-4 left-4 rounded-md bg-white/95 px-3 py-2 shadow-sm text-sm">
                            <div class="font-semibold text-gray-900">Amsterdam FC</div>
                            <div class="text-gray-600 text-xs">vs Rotterdam United</div>
                        </div>

                        {{-- bal --}}
                        <div class="absolute bottom-4 right-4 h-10 w-10 rounded-full bg-white shadow flex items-center justify-center">
                            <span class="text-xl">⚽️</span>
                        </div>
                    </div>
                </div>

                {{-- kleine legenda / USP onder de kaart --}}
                <div class="mt-4 grid sm:grid-cols-3 gap-3 text-sm">
                    <div class="rounded-md border bg-white p-3">
                        <div class="font-semibold">Coach</div>
                        <div class="text-gray-600">Beheert teams & wedstrijden</div>
                    </div>
                    <div class="rounded-md border bg-white p-3">
                        <div class="font-semibold">Speler</div>
                        <div class="text-gray-600">Ziet eigen team & reageert op oproepen</div>
                    </div>
                    <div class="rounded-md border bg-white p-3">
                        <div class="font-semibold">Validatie</div>
                        <div class="text-gray-600">Reageren na 5 login-dagen</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA band --}}
    <section class="mt-6 border-t bg-white">
        <div class="mx-auto max-w-6xl px-4 py-10 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold">Direct starten met {{ config('app.name', 'Sportteam Manager') }}</h3>
                <p class="text-gray-600 text-sm mt-1">Log in of registreer en bouw je selectie op.</p>
            </div>
            <div class="flex items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="rounded-md border px-4 py-2 text-sm font-medium hover:bg-gray-50">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                            Registreer
                        </a>
                    @endif
                @else
                    <a href="{{ route('dashboard') }}" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                        Naar dashboard
                    </a>
                @endguest
            </div>
        </div>
    </section>
</main>

<footer class="border-t bg-white">
    <div class="mx-auto max-w-6xl px-4 py-6 text-sm text-gray-500">
        © {{ date('Y') }} {{ config('app.name', 'Sportteam Manager') }} · Gemaakt voor voetbalteams
    </div>
</footer>
</body>
</html>
