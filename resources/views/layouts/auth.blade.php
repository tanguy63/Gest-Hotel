<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Authentification') - GEST-HOTEL</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F5F7FB] font-sans text-[#172033]">

    <main class="flex min-h-screen">

        {{-- Partie présentation --}}
        <section class="relative hidden w-1/2 flex-col justify-between overflow-hidden bg-[#2457A7] p-12 text-white lg:flex">

            <div class="relative z-10">
                <a href="/" class="text-3xl font-bold tracking-wide">
                    GEST-HOTEL
                </a>
                <p class="mt-2 text-sm text-blue-100">
                    La gestion hôtelière simplifiée.
                </p>
            </div>

            <div class="relative z-10 max-w-lg">
                <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-white/15">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="32" height="32" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="1.7" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M3 21h18"/>
                        <path d="M5 21V7l8-4v18"/>
                        <path d="M19 21V11l-6-4"/>
                        <path d="M9 9v.01M9 12v.01M9 15v.01M9 18v.01"/>
                        <path d="M16 13v.01M16 16v.01M16 19v.01"/>
                    </svg>
                </div>

                <h1 class="text-4xl leading-tight font-bold">
                    Gérez votre hôtel en toute simplicité.
                </h1>

                <p class="mt-5 text-lg leading-8 text-blue-100">
                    Réservations, chambres, clients, paiements et personnel :
                    retrouvez toute votre activité dans un seul espace.
                </p>
            </div>

            <p class="relative z-10 text-sm text-blue-100">
                © {{ date('Y') }} GEST-HOTEL. Tous droits réservés.
            </p>

            <div class="absolute -right-32 -bottom-32 h-96 w-96 rounded-full border-[45px] border-white/5"></div>
            <div class="absolute top-1/3 -right-20 h-64 w-64 rounded-full bg-white/5"></div>
        </section>

        {{-- Formulaire --}}
        <section class="flex w-full items-center justify-center px-5 py-10 lg:w-1/2 lg:px-12">

            <div class="w-full max-w-md">

                <div class="mb-8 text-center lg:hidden">
                    <a href="/" class="text-2xl font-bold text-[#2457A7]">
                        GEST-HOTEL
                    </a>
                    <p class="mt-1 text-sm text-slate-500">
                        La gestion hôtelière simplifiée.
                    </p>
                </div>

                @yield('content')

                <p class="mt-10 text-center text-xs text-slate-400">
                    © {{ date('Y') }} GEST-HOTEL
                </p>

            </div>
        </section>

    </main>

</body>
</html>