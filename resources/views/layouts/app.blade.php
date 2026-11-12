
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Tableau de bord') | GEST-HOSTEL</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

@php
    $utilisateur = auth()->user();

    $etablissementActif = $utilisateur
        ? $utilisateur->etablissements()
            ->wherePivot('est_actif', true)
            ->where('etablissements.id', session('etablissement_actif'))
            ->first()
        : null;

    $initiales = collect(
        preg_split('/\s+/', trim($utilisateur?->nom_complet ?? ''))
    )
        ->filter()
        ->take(2)
        ->map(fn ($mot) => mb_strtoupper(mb_substr($mot, 0, 1)))
        ->implode('');

    $initiales = $initiales ?: 'U';

    $roleUtilisateur = $etablissementActif?->pivot?->role ?? 'Utilisateur';
@endphp

<body class="min-h-screen bg-background font-sans text-text-main antialiased dark:bg-slate-950 dark:text-slate-100">

    <div class="min-h-screen">

        <div id="sidebar-overlay"
             class="fixed inset-0 z-40 hidden bg-slate-950/50 lg:hidden"></div>

        @include('layouts.partials.sidebar')

        <div class="min-h-screen lg:pl-72">

            @include('layouts.partials.header')

            <main class="min-h-[calc(100vh-5rem)] p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>

            @include('layouts.partials.footer')

        </div>
    </div>

    @livewireScripts

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const openButton = document.getElementById('open-sidebar');
            const closeButton = document.getElementById('close-sidebar');

            function openSidebar() {
                sidebar?.classList.remove('-translate-x-full');
                overlay?.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeSidebar() {
                sidebar?.classList.add('-translate-x-full');
                overlay?.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            openButton?.addEventListener('click', openSidebar);
            closeButton?.addEventListener('click', closeSidebar);
            overlay?.addEventListener('click', closeSidebar);

            const themeButton = document.getElementById('theme-toggle');

            themeButton?.addEventListener('click', function () {
                const isDark = document.documentElement.classList.toggle('dark');

                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            });

            const savedTheme = localStorage.getItem('theme');

            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else if (savedTheme === 'light') {
                document.documentElement.classList.remove('dark');
            }
        });
    </script>

</body>
</html>
