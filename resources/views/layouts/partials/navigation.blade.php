
@php
    $elementsNavigation = [
        'dashboard' => 'Tableau de bord',
        'categories-chambres.*' => 'Catégories de chambres',
        'chambres.*' => 'Chambres',
        'clients.*' => 'Clients',
        'reservations.*' => 'Réservations',
        'paiements.*' => 'Paiements',
    ];

    $pageActuelle = 'Application';

    foreach ($elementsNavigation as $route => $libelle) {
        if (request()->routeIs($route)) {
            $pageActuelle = $libelle;
            break;
        }
    }
@endphp

<div class="border-t border-border px-4 py-2.5 sm:px-6 lg:px-8 dark:border-slate-800">
    <nav aria-label="Fil d'Ariane" class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
        <a href="{{ route('dashboard') }}"
           class="transition hover:text-primary">
            Accueil
        </a>

        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="m9 18 6-6-6-6"/>
        </svg>

        <span class="font-semibold text-primary dark:text-blue-300">
            {{ $pageActuelle }}
        </span>
    </nav>
</div>
