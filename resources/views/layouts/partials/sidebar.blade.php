
<aside id="sidebar"
       class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col border-r border-border bg-white transition-transform duration-300 dark:border-slate-800 dark:bg-slate-900 lg:translate-x-0">

    {{-- Logo --}}
    <div class="flex h-20 shrink-0 items-center gap-3 border-b border-border px-6 dark:border-slate-800">

        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary text-xl font-bold text-white">
            G
        </div>

        <div>
            <h1 class="text-lg font-bold tracking-tight text-primary">
                GEST-HOSTEL
            </h1>

            <p class="text-xs text-text-muted dark:text-slate-400">
                Gestion hôtelière
            </p>
        </div>

        <button id="close-sidebar"
                class="ml-auto rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 lg:hidden dark:hover:bg-slate-800"
                aria-label="Fermer le menu">
            ✕
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 space-y-7 overflow-y-auto px-4 py-6">

        {{-- Général --}}
        <div>
            <p class="mb-3 px-3 text-[11px] font-semibold uppercase tracking-widest text-slate-400">
                Général
            </p>

            <div class="space-y-1">

                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition
                   {{ request()->routeIs('dashboard')
                        ? 'bg-blue-50 text-primary dark:bg-blue-950/50 dark:text-blue-300'
                        : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">

                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                    </svg>

                    Tableau de bord
                </a>

                {{-- Catégories --}}
                <a href="{{ route('categories-chambres.index') }}"
                   class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition
                   {{ request()->routeIs('categories-chambres.*')
                        ? 'bg-blue-50 text-primary dark:bg-blue-950/50 dark:text-blue-300'
                        : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">

                    <span class="text-lg">▦</span>
                    Catégories de chambres
                </a>

                {{-- Chambres --}}
                <a href="{{ route('chambres.index') }}"
                   class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition
                   {{ request()->routeIs('chambres.*')
                        ? 'bg-blue-50 text-primary dark:bg-blue-950/50 dark:text-blue-300'
                        : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">

                    <span class="text-lg">▣</span>
                    Chambres
                </a>

                {{-- Clients --}}
                <a href="{{ route('clients.index') }}"
                   class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition
                   {{ request()->routeIs('clients.*')
                        ? 'bg-blue-50 text-primary dark:bg-blue-950/50 dark:text-blue-300'
                        : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">

                    <span class="text-lg">♙</span>
                    Clients
                </a>

                {{-- Réservations --}}
                <a href="{{ route('reservations.index') }}"
                   class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition
                   {{ request()->routeIs('reservations.*')
                        ? 'bg-blue-50 text-primary dark:bg-blue-950/50 dark:text-blue-300'
                        : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">

                    <span class="text-lg">▤</span>
                    Réservations
                </a>
                    @php
    $utilisateurConnecte = auth()->user();

    $estSuperAdministrateur = $utilisateurConnecte
        && $utilisateurConnecte->estSuperAdministrateur();

    $roleEtablissement = null;

    if (!$estSuperAdministrateur) {
        $roleEtablissement = $utilisateurConnecte
            ->etablissements()
            ->wherePivot('est_actif', true)
            ->where('etablissements.id', session('etablissement_actif'))
            ->first()?->pivot?->role;
    }
@endphp
                {{-- Paiements --}}
@if(in_array($roleEtablissement, [
    'proprietaire',
    'administrateur',
    'comptable'
], true))

    <a href="{{ route('paiements.index') }}"
       class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition
       {{ request()->routeIs('paiements.*')
            ? 'bg-blue-50 text-primary dark:bg-blue-950/50 dark:text-blue-300'
            : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">

        <span class="text-lg">◉</span>
        Paiements
    </a>

@endif

            </div>
        </div>

        {{-- Gestion hôtelière --}}
        <div>
            <p class="mb-3 px-3 text-[11px] font-semibold uppercase tracking-widest text-slate-400">
                Gestion hôtelière
            </p>

            <div class="space-y-1">

                <a href="#services"
                   class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">
                    <span class="text-lg">✧</span>
                    Services et consommations
                    <span class="ml-auto rounded-md bg-slate-100 px-2 py-0.5 text-[10px] dark:bg-slate-800">
                        Bientôt
                    </span>
                </a>

                <a href="#menage"
                   class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">
                    <span class="text-lg">⌂</span>
                    Entretien des chambres
                    <span class="ml-auto rounded-md bg-slate-100 px-2 py-0.5 text-[10px] dark:bg-slate-800">
                        Bientôt
                    </span>
                </a>

                <a href="#maintenance"
                   class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">
                    <span class="text-lg">⚙</span>
                    Maintenance
                    <span class="ml-auto rounded-md bg-slate-100 px-2 py-0.5 text-[10px] dark:bg-slate-800">
                        Bientôt
                    </span>
                </a>

                {{-- Personnel --}}
@if(in_array($roleEtablissement, [
    'proprietaire',
    'administrateur'
], true))

    <a href="{{ route('personnel.index') }}"
       class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition
       {{ request()->routeIs('personnel.*')
            ? 'bg-blue-50 text-primary dark:bg-blue-950/50 dark:text-blue-300'
            : 'text-slate-600 hover:bg-slate-100 hover:text-blue-700 dark:text-slate-300 dark:hover:bg-slate-800' }}">

        <span class="text-lg">♧</span>
        <span>Personnel</span>

    </a>

@endif


            </div>
        </div>

        {{-- Administration --}}

        <div>
            <p class="mb-3 px-3 text-[11px] font-semibold uppercase tracking-widest text-slate-400">
                Administration
            </p>
            @if($estSuperAdministrateur)

                <a href="{{ route('super-admin.index') }}"
                class="mb-3 flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition
                {{ request()->routeIs('super-admin.*')
                        ? 'bg-blue-50 text-primary dark:bg-blue-950/50 dark:text-blue-300'
                        : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">

                    <span class="text-lg">♛</span>
                    Administration plateforme

                </a>

            @endif

            <div class="space-y-1">

                <a href="#rapports"
                   class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">
                    <span class="text-lg">▥</span>
                    Rapports
                    <span class="ml-auto rounded-md bg-slate-100 px-2 py-0.5 text-[10px] dark:bg-slate-800">
                        Bientôt
                    </span>
                </a>

                {{-- Paramètres --}}
                @if(in_array($roleEtablissement, [
                    'proprietaire',
                    'administrateur'
                ], true))

                    <a href="{{ route('parametres.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition
                    {{ request()->routeIs('parametres.*')
                            ? 'bg-blue-50 text-primary dark:bg-blue-950/50 dark:text-blue-300'
                            : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">

                        <span class="text-lg">⚙</span>
                        Paramètres

                    </a>

                @endif

            </div>
        </div>

    </nav>

    {{-- Établissement actif --}}
    <div class="shrink-0 border-t border-border p-4 dark:border-slate-800">

        <div class="rounded-xl bg-slate-50 p-3 dark:bg-slate-800">

            <p class="text-xs text-text-muted dark:text-slate-400">
                Établissement actif
            </p>

            <p class="mt-1 truncate text-sm font-semibold text-slate-900 dark:text-white">
                {{ $etablissementActif?->nom ?? 'Aucun établissement' }}
            </p>

            <p class="mt-1 text-xs capitalize text-text-muted dark:text-slate-400">
                {{ str_replace('_', ' ', $roleUtilisateur) }}
            </p>

        </div>

    </div>

</aside>
