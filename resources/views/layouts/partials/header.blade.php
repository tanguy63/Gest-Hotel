
<header class="sticky top-0 z-30 border-b border-border bg-white/95 backdrop-blur dark:border-slate-800 dark:bg-slate-900/95">

    <div class="flex h-20 items-center justify-between px-4 sm:px-6 lg:px-8">

        {{-- Partie gauche --}}
        <div class="flex min-w-0 items-center gap-3">

            <button id="open-sidebar"
                    type="button"
                    class="rounded-xl border border-border p-2.5 text-slate-600 transition hover:bg-slate-100 lg:hidden dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                    aria-label="Ouvrir le menu">

                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="min-w-0">
                <p class="hidden text-xs text-text-muted sm:block dark:text-slate-400">
                    Espace de gestion
                </p>

                <h2 class="truncate text-base font-bold text-slate-900 sm:text-lg dark:text-white">
                    @yield('page-heading', 'Tableau de bord')
                </h2>

                <p class="truncate text-xs text-slate-500 sm:hidden dark:text-slate-400">
                    {{ $etablissementActif?->nom ?? 'GEST-HOTEL' }}
                </p>
            </div>
        </div>

        {{-- Partie droite --}}
        <div class="flex items-center gap-2 sm:gap-4">

            {{-- Thème --}}
            <button id="theme-toggle"
                    type="button"
                    class="rounded-xl border border-border p-2.5 text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                    aria-label="Changer de thème">

                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="4" stroke-width="2"/>
                    <path stroke-linecap="round" stroke-width="2"
                          d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42"/>
                </svg>
            </button>

            {{-- Profil --}}
            <div class="relative flex items-center gap-3 border-l border-border pl-3 dark:border-slate-700 sm:pl-4">

                <div class="hidden text-right sm:block">
                    <p class="max-w-40 truncate text-sm font-semibold text-slate-900 dark:text-white">
                        {{ $utilisateur?->nom_complet ?? 'Utilisateur' }}
                    </p>

                    <p class="max-w-40 truncate text-xs text-text-muted dark:text-slate-400">
                        {{ str_replace('_', ' ', $roleUtilisateur) }}
                    </p>
                </div>

                <details class="group relative">
                    <summary class="flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-full bg-blue-100 font-bold text-primary transition hover:ring-4 hover:ring-blue-100 dark:bg-blue-950 dark:text-blue-300 dark:hover:ring-blue-950">
                        {{ $initiales }}
                    </summary>

                    <div class="absolute right-0 top-14 z-50 w-64 rounded-2xl border border-border bg-white p-3 shadow-xl dark:border-slate-700 dark:bg-slate-900">

                        <div class="border-b border-border px-3 pb-3 dark:border-slate-700">
                            <p class="truncate text-sm font-bold text-slate-900 dark:text-white">
                                {{ $utilisateur?->nom_complet }}
                            </p>

                            <p class="truncate text-xs text-slate-500 dark:text-slate-400">
                                {{ $utilisateur?->email }}
                            </p>
                        </div>

                        <div class="mt-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button type="submit"
                                        class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-red-600 transition hover:bg-red-50 dark:hover:bg-red-950/30">

                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                              d="M15 17l5-5-5-5m5 5H9m4 5v1a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1"/>
                                    </svg>

                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </details>

            </div>
        </div>

    </div>

    {{-- Navigation secondaire --}}
    @include('layouts.partials.navigation')

</header>
