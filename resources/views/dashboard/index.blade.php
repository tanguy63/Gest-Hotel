
@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('page-heading', 'Vue d’ensemble')

@section('content')

@php
    $nomUtilisateur = auth()->user()?->nom_complet ?? 'Utilisateur';

    $maximumRevenu = max(
        1,
        (float) $revenusMensuels->max('montant')
    );

    $couleursStatuts = [
        'en_attente' => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-950 dark:text-yellow-300',
        'confirmee' => 'bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300',
        'en_cours' => 'bg-green-50 text-green-700 dark:bg-green-950 dark:text-green-300',
        'terminee' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
        'annulee' => 'bg-red-50 text-red-700 dark:bg-red-950 dark:text-red-300',
    ];

    $libellesStatuts = [
        'en_attente' => 'En attente',
        'confirmee' => 'Confirmée',
        'en_cours' => 'En cours',
        'terminee' => 'Terminée',
        'annulee' => 'Annulée',
    ];
@endphp

{{-- Bienvenue --}}
<section class="mb-8 flex flex-col justify-between gap-5 sm:flex-row sm:items-center">

    <div>
        <p class="text-sm capitalize text-text-muted dark:text-slate-400">
            {{ now()->translatedFormat('l d F Y') }}
        </p>

        <h1 class="mt-1 text-2xl font-bold tracking-tight sm:text-3xl">
            Bonjour, {{ $nomUtilisateur }} 👋
        </h1>

        <p class="mt-2 text-sm text-text-muted dark:text-slate-400">
            Voici la situation actuelle de
            <strong>{{ $etablissement->nom }}</strong>.
        </p>
    </div>

    <div class="flex flex-wrap gap-3">

        @if($peutVoirFinances)
            <a href="{{ route('paiements.index') }}"
               class="rounded-xl border border-border bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                Voir les paiements
            </a>
        @endif

        @if($peutCreerReservation)
            <a href="{{ route('reservations.create') }}"
               class="rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-hover">
                + Nouvelle réservation
            </a>
        @endif

    </div>

</section>


{{-- Indicateurs principaux --}}
<section class="mb-8 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">

    @foreach ($stats as $stat)

        <article class="rounded-2xl border border-border bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">

            <div class="flex items-start justify-between gap-3">

                <div>
                    <p class="text-sm font-medium text-text-muted dark:text-slate-400">
                        {{ $stat['label'] }}
                    </p>

                    <h2 class="mt-3 text-2xl font-bold tracking-tight">
                        {{ $stat['value'] }}
                    </h2>

                    <p class="mt-1 text-xs text-text-muted dark:text-slate-400">
                        {{ $stat['detail'] }}
                    </p>
                </div>

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl
                    @if($stat['color'] === 'blue')
                        bg-blue-50 text-blue-600 dark:bg-blue-950 dark:text-blue-300
                    @elseif($stat['color'] === 'green')
                        bg-green-50 text-green-600 dark:bg-green-950 dark:text-green-300
                    @elseif($stat['color'] === 'orange')
                        bg-orange-50 text-orange-600 dark:bg-orange-950 dark:text-orange-300
                    @else
                        bg-purple-50 text-purple-600 dark:bg-purple-950 dark:text-purple-300
                    @endif">

                    <span class="text-xl">{{ $stat['icon'] }}</span>

                </div>

            </div>

            <div class="mt-5 h-1.5 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">

                <div class="h-full rounded-full bg-primary"
                     style="width: {{ $stat['color'] === 'blue' ? ($chambresTotal > 0 ? ($chambresOperationnelles / $chambresTotal) * 100 : 0) : 100 }}%">
                </div>

            </div>

        </article>

    @endforeach

</section>


{{-- Graphique et occupation --}}
<section class="mb-8 grid gap-5 {{ $peutVoirFinances ? 'xl:grid-cols-3' : 'xl:grid-cols-1' }}">

    {{-- Revenus --}}
    @if($peutVoirFinances)

        <article class="rounded-2xl border border-border bg-white p-5 shadow-sm xl:col-span-2 dark:border-slate-800 dark:bg-slate-900">

            <div class="flex flex-wrap items-center justify-between gap-3">

                <div>
                    <h2 class="font-semibold">
                        Évolution des revenus
                    </h2>

                    <p class="mt-1 text-xs text-text-muted dark:text-slate-400">
                        Encaissements des 12 derniers mois
                    </p>
                </div>

                <div class="rounded-lg border border-border px-3 py-2 text-xs font-medium dark:border-slate-700">
                    {{ number_format($revenusMois, 0, ',', ' ') }} FCFA ce mois
                </div>

            </div>

            <div class="mt-7 flex h-56 items-end justify-between gap-2 border-b border-l border-border px-2 pb-2 dark:border-slate-700">

                @foreach ($revenusMensuels as $revenu)

                    @php
                        $hauteur = $revenu['montant'] > 0
                            ? max(4, ($revenu['montant'] / $maximumRevenu) * 100)
                            : 2;
                    @endphp

                    <div class="group flex h-full min-w-0 flex-1 items-end justify-center"
                         title="{{ $revenu['label'] }} : {{ number_format($revenu['montant'], 0, ',', ' ') }} FCFA">

                        <div class="w-full max-w-8 rounded-t-md bg-blue-200 transition hover:bg-primary dark:bg-blue-900"
                             style="height: {{ $hauteur }}%">
                        </div>

                    </div>

                @endforeach

            </div>

            <div class="mt-3 flex justify-between gap-1 text-[10px] text-text-muted sm:text-xs dark:text-slate-400">

                @foreach ($revenusMensuels as $revenu)
                    <span>{{ $revenu['label'] }}</span>
                @endforeach

            </div>

            <div class="mt-5 flex flex-wrap items-center justify-between gap-2 border-t border-border pt-4 dark:border-slate-800">

                <div>
                    <p class="text-xs text-text-muted dark:text-slate-400">
                        Encaissements aujourd'hui
                    </p>

                    <p class="mt-1 font-bold">
                        {{ number_format($revenusAujourdhui, 0, ',', ' ') }} FCFA
                    </p>
                </div>

                <a href="{{ route('paiements.index') }}"
                   class="text-sm font-semibold text-primary hover:underline dark:text-blue-300">
                    Consulter les paiements →
                </a>

            </div>

        </article>

    @endif


    {{-- Occupation --}}
    <article class="rounded-2xl border border-border bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">

        <h2 class="font-semibold">
            Occupation des chambres
        </h2>

        <p class="mt-1 text-xs text-text-muted dark:text-slate-400">
            Chambres actuellement occupées
        </p>

        <div class="flex flex-col items-center py-7">

            <div class="relative flex h-40 w-40 items-center justify-center rounded-full"
                 style="background: conic-gradient(#2457A7 0% {{ $tauxOccupation }}%, #E2E8F0 {{ $tauxOccupation }}% 100%)">

                <div class="flex h-32 w-32 flex-col items-center justify-center rounded-full bg-white dark:bg-slate-900">

                    <span class="text-3xl font-bold">
                        {{ $tauxOccupation }}%
                    </span>

                    <span class="text-xs text-text-muted dark:text-slate-400">
                        Occupées
                    </span>

                </div>

            </div>

        </div>

        <div class="space-y-3">

            <div class="flex items-center justify-between text-sm">

                <span class="flex items-center gap-2 text-text-muted dark:text-slate-400">
                    <span class="h-2.5 w-2.5 rounded-full bg-primary"></span>
                    Occupées
                </span>

                <strong>{{ $chambresOccupees }} chambres</strong>

            </div>

            <div class="flex items-center justify-between text-sm">

                <span class="flex items-center gap-2 text-text-muted dark:text-slate-400">
                    <span class="h-2.5 w-2.5 rounded-full bg-slate-300"></span>
                    Disponibles
                </span>

                <strong>{{ $chambresDisponibles }} chambres</strong>

            </div>

            <div class="flex items-center justify-between text-sm">

                <span class="flex items-center gap-2 text-text-muted dark:text-slate-400">
                    <span class="h-2.5 w-2.5 rounded-full bg-orange-400"></span>
                    Indisponibles
                </span>

                <strong>{{ $chambresIndisponibles }} chambres</strong>

            </div>

            <div class="border-t border-border pt-3 text-xs text-text-muted dark:border-slate-800 dark:text-slate-400">
                {{ $chambresOperationnelles }} chambres opérationnelles sur {{ $chambresTotal }} au total.
            </div>

        </div>

    </article>

</section>


{{-- Arrivées et départs du jour --}}
@if($peutVoirReservations)

    <section class="mb-8 grid gap-5 xl:grid-cols-2">

        {{-- Arrivées --}}
        <article class="overflow-hidden rounded-2xl border border-border bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

            <div class="flex items-center justify-between border-b border-border px-5 py-4 dark:border-slate-800">

                <div>
                    <h2 class="font-semibold">
                        Arrivées du jour
                    </h2>

                    <p class="mt-1 text-xs text-text-muted dark:text-slate-400">
                        Clients attendus aujourd'hui
                    </p>
                </div>

                <span class="rounded-full bg-blue-50 px-3 py-1 text-sm font-bold text-blue-700 dark:bg-blue-950 dark:text-blue-300">
                    {{ $arriveesDuJour->count() }}
                </span>

            </div>

            <div class="divide-y divide-border dark:divide-slate-800">

                @forelse($arriveesDuJour as $reservation)

                    <div class="flex items-center justify-between gap-3 px-5 py-4">

                        <div class="min-w-0">

                            <p class="truncate text-sm font-semibold">
                                {{ $reservation->client?->nom_affichage ?? 'Client supprimé' }}
                            </p>

                            <p class="mt-1 text-xs text-text-muted dark:text-slate-400">
                                Chambre {{ $reservation->chambre?->numero ?? '—' }}
                                · {{ $reservation->nombre_personnes }} personne(s)
                            </p>

                        </div>

                        <a href="{{ route('reservations.show', $reservation) }}"
                           class="shrink-0 rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100 dark:bg-blue-950 dark:text-blue-300">
                            Consulter
                        </a>

                    </div>

                @empty

                    <div class="px-5 py-9 text-center">

                        <p class="text-sm font-medium">
                            Aucune arrivée prévue
                        </p>

                        <p class="mt-1 text-xs text-text-muted dark:text-slate-400">
                            Les arrivées du jour apparaîtront ici.
                        </p>

                    </div>

                @endforelse

            </div>

        </article>


        {{-- Départs --}}
        <article class="overflow-hidden rounded-2xl border border-border bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

            <div class="flex items-center justify-between border-b border-border px-5 py-4 dark:border-slate-800">

                <div>
                    <h2 class="font-semibold">
                        Départs du jour
                    </h2>

                    <p class="mt-1 text-xs text-text-muted dark:text-slate-400">
                        Clients devant libérer leur chambre
                    </p>
                </div>

                <span class="rounded-full bg-orange-50 px-3 py-1 text-sm font-bold text-orange-700 dark:bg-orange-950 dark:text-orange-300">
                    {{ $departsAujourdhui->count() }}
                </span>

            </div>

            <div class="divide-y divide-border dark:divide-slate-800">

                @forelse($departsAujourdhui as $reservation)

                    <div class="flex items-center justify-between gap-3 px-5 py-4">

                        <div class="min-w-0">

                            <p class="truncate text-sm font-semibold">
                                {{ $reservation->client?->nom_affichage ?? 'Client supprimé' }}
                            </p>

                            <p class="mt-1 text-xs text-text-muted dark:text-slate-400">
                                Chambre {{ $reservation->chambre?->numero ?? '—' }}
                                · {{ $reservation->nombre_personnes }} personne(s)
                            </p>

                        </div>

                        <a href="{{ route('reservations.show', $reservation) }}"
                           class="shrink-0 rounded-lg bg-orange-50 px-3 py-2 text-xs font-semibold text-orange-700 hover:bg-orange-100 dark:bg-orange-950 dark:text-orange-300">
                            Consulter
                        </a>

                    </div>

                @empty

                    <div class="px-5 py-9 text-center">

                        <p class="text-sm font-medium">
                            Aucun départ prévu
                        </p>

                        <p class="mt-1 text-xs text-text-muted dark:text-slate-400">
                            Les départs du jour apparaîtront ici.
                        </p>

                    </div>

                @endforelse

            </div>

        </article>

    </section>

@endif


{{-- Réservations récentes --}}
@if($peutVoirReservations)

    <section class="overflow-hidden rounded-2xl border border-border bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-border px-5 py-5 dark:border-slate-800">

            <div>
                <h2 class="font-semibold">
                    Réservations récentes
                </h2>

                <p class="mt-1 text-xs text-text-muted dark:text-slate-400">
                    Les sept dernières réservations de votre établissement
                </p>
            </div>

            <a href="{{ route('reservations.index') }}"
               class="text-sm font-semibold text-primary hover:underline dark:text-blue-300">
                Voir toutes les réservations →
            </a>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full min-w-[750px] text-left text-sm">

                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-text-muted dark:bg-slate-800/70 dark:text-slate-400">

                    <tr>
                        <th class="px-5 py-4 font-medium">Client</th>
                        <th class="px-5 py-4 font-medium">Chambre</th>
                        <th class="px-5 py-4 font-medium">Arrivée</th>
                        <th class="px-5 py-4 font-medium">Départ</th>
                        <th class="px-5 py-4 font-medium">Montant</th>
                        <th class="px-5 py-4 font-medium">Statut</th>
                        <th class="px-5 py-4 font-medium">Action</th>
                    </tr>

                </thead>

                <tbody class="divide-y divide-border dark:divide-slate-800">

                    @forelse ($reservationsRecentes as $reservation)

                        @php
                            $nomClient = $reservation->client?->nom_affichage
                                ?? 'Client supprimé';

                            $initialesClient = collect(
                                preg_split('/\s+/', trim($nomClient))
                            )
                                ->filter()
                                ->take(2)
                                ->map(fn ($mot) => mb_strtoupper(mb_substr($mot, 0, 1)))
                                ->implode('');

                            $couleurStatut = $couleursStatuts[$reservation->statut]
                                ?? 'bg-slate-100 text-slate-700';

                            $libelleStatut = $libellesStatuts[$reservation->statut]
                                ?? ucfirst($reservation->statut);
                        @endphp

                        <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40">

                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-50 text-xs font-bold text-primary dark:bg-blue-950 dark:text-blue-300">
                                        {{ $initialesClient ?: 'CL' }}
                                    </div>

                                    <span class="font-medium">
                                        {{ $nomClient }}
                                    </span>

                                </div>

                            </td>

                            <td class="px-5 py-4 text-text-muted dark:text-slate-400">
                                {{ $reservation->chambre?->numero ?? '—' }}
                            </td>

                            <td class="px-5 py-4 text-text-muted dark:text-slate-400">
                                {{ $reservation->date_arrivee?->translatedFormat('d M Y') ?? '—' }}
                            </td>

                            <td class="px-5 py-4 text-text-muted dark:text-slate-400">
                                {{ $reservation->date_depart?->translatedFormat('d M Y') ?? '—' }}
                            </td>

                            <td class="px-5 py-4 font-semibold">
                                {{ number_format((float) $reservation->montant_total, 0, ',', ' ') }}
                                FCFA
                            </td>

                            <td class="px-5 py-4">

                                <span class="inline-flex rounded-full px-3 py-1.5 text-xs font-semibold {{ $couleurStatut }}">
                                    {{ $libelleStatut }}
                                </span>

                            </td>

                            <td class="px-5 py-4">

                                <a href="{{ route('reservations.show', $reservation) }}"
                                   class="font-semibold text-primary hover:underline dark:text-blue-300">
                                    Détails
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="px-5 py-12 text-center">

                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                                    ▣
                                </div>

                                <p class="mt-3 font-semibold">
                                    Aucune réservation pour le moment
                                </p>

                                <p class="mt-1 text-sm text-text-muted dark:text-slate-400">
                                    Les nouvelles réservations apparaîtront ici.
                                </p>

                                @if($peutCreerReservation)

                                    <a href="{{ route('reservations.create') }}"
                                       class="mt-4 inline-flex rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-hover">
                                        Créer une réservation
                                    </a>

                                @endif

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

@endif

@endsection
