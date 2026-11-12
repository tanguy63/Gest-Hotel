
@extends('layouts.app')

@section('title', 'Administration de la plateforme')

@section('content')

<div class="space-y-6">

    {{-- En-tête --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">
                Administration de la plateforme
            </h1>

            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Supervision générale de tous les établissements GEST-HOTEL.
            </p>
        </div>

        <div class="flex items-center gap-2 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm font-medium text-blue-700 dark:border-blue-900 dark:bg-blue-950/40 dark:text-blue-300">
            <span class="h-2 w-2 rounded-full bg-green-500"></span>
            Supervision en lecture seule
        </div>
    </div>

    {{-- Statistiques principales --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        @php
            $cartes = [
                [
                    'titre' => 'Établissements',
                    'valeur' => $statistiques['total_etablissements'],
                    'couleur' => 'blue',
                    'icone' => '🏨',
                ],
                [
                    'titre' => 'Établissements actifs',
                    'valeur' => $statistiques['etablissements_actifs'],
                    'couleur' => 'green',
                    'icone' => '✓',
                ],
                [
                    'titre' => 'En attente',
                    'valeur' => $statistiques['etablissements_en_attente'],
                    'couleur' => 'orange',
                    'icone' => '◷',
                ],
                [
                    'titre' => 'Suspendus',
                    'valeur' => $statistiques['etablissements_suspendus'],
                    'couleur' => 'red',
                    'icone' => '!',
                ],
                [
                    'titre' => 'Chambres',
                    'valeur' => $statistiques['total_chambres'],
                    'couleur' => 'indigo',
                    'icone' => '🛏',
                ],
                [
                    'titre' => 'Clients',
                    'valeur' => $statistiques['total_clients'],
                    'couleur' => 'cyan',
                    'icone' => '👥',
                ],
                [
                    'titre' => 'Réservations',
                    'valeur' => $statistiques['total_reservations'],
                    'couleur' => 'purple',
                    'icone' => '📅',
                ],
                [
                    'titre' => 'Paiements',
                    'valeur' => $statistiques['total_paiements'],
                    'couleur' => 'emerald',
                    'icone' => '💳',
                ],
            ];

            $couleurs = [
                'blue' => 'bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400',
                'green' => 'bg-green-50 text-green-600 dark:bg-green-950/40 dark:text-green-400',
                'orange' => 'bg-orange-50 text-orange-600 dark:bg-orange-950/40 dark:text-orange-400',
                'red' => 'bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400',
                'indigo' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400',
                'cyan' => 'bg-cyan-50 text-cyan-600 dark:bg-cyan-950/40 dark:text-cyan-400',
                'purple' => 'bg-purple-50 text-purple-600 dark:bg-purple-950/40 dark:text-purple-400',
                'emerald' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400',
            ];
        @endphp

        @foreach($cartes as $carte)

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-800 dark:bg-slate-900">

                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        {{ $carte['titre'] }}
                    </p>

                    <span class="flex h-10 w-10 items-center justify-center rounded-xl text-lg {{ $couleurs[$carte['couleur']] }}">
                        {{ $carte['icone'] }}
                    </span>
                </div>

                <p class="mt-4 text-3xl font-bold text-slate-800 dark:text-white">
                    {{ number_format($carte['valeur'], 0, ',', ' ') }}
                </p>

            </div>

        @endforeach

    </div>

    {{-- Chiffre d'affaires global --}}
    <div class="rounded-2xl border border-blue-100 bg-gradient-to-r from-blue-700 to-blue-500 p-6 text-white shadow-sm dark:border-blue-900">
        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <div>
                <p class="text-sm font-medium text-blue-100">
                    Revenus globaux enregistrés
                </p>

                <p class="mt-2 text-3xl font-bold">
                    {{ number_format($statistiques['revenus_total'], 0, ',', ' ') }}
                    FCFA
                </p>

                <p class="mt-2 text-xs text-blue-100">
                    Total des paiements validés de tous les établissements.
                </p>
            </div>

            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/15 text-2xl">
                FCFA
            </div>
        </div>
    </div>

    {{-- Répartition des statuts --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">

        <h2 class="text-base font-semibold text-slate-800 dark:text-white">
            Répartition des établissements
        </h2>

        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            Situation actuelle des établissements inscrits.
        </p>

        @php
            $totalStatuts = max($statistiques['total_etablissements'], 1);

            $actifs = $statistiques['etablissements_actifs'];
            $attente = $statistiques['etablissements_en_attente'];
            $suspendus = $statistiques['etablissements_suspendus'];

            $pourcentageActifs = ($actifs / $totalStatuts) * 100;
            $pourcentageAttente = ($attente / $totalStatuts) * 100;
            $pourcentageSuspendus = ($suspendus / $totalStatuts) * 100;
        @endphp

        <div class="mt-5 flex h-4 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
            <div class="bg-green-500" style="width: {{ $pourcentageActifs }}%"></div>
            <div class="bg-orange-400" style="width: {{ $pourcentageAttente }}%"></div>
            <div class="bg-red-500" style="width: {{ $pourcentageSuspendus }}%"></div>
        </div>

        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">

            <div class="flex items-center gap-3">
                <span class="h-3 w-3 rounded-full bg-green-500"></span>
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Actifs</p>
                    <p class="font-bold text-slate-800 dark:text-white">
                        {{ $actifs }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span class="h-3 w-3 rounded-full bg-orange-400"></span>
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">En attente</p>
                    <p class="font-bold text-slate-800 dark:text-white">
                        {{ $attente }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span class="h-3 w-3 rounded-full bg-red-500"></span>
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Suspendus</p>
                    <p class="font-bold text-slate-800 dark:text-white">
                        {{ $suspendus }}
                    </p>
                </div>
            </div>

        </div>
    </div>

    {{-- Liste des établissements --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

        <div class="border-b border-slate-200 px-6 py-5 dark:border-slate-800">
            <h2 class="text-lg font-semibold text-slate-800 dark:text-white">
                Établissements de la plateforme
            </h2>

            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Recherchez et consultez les établissements inscrits.
            </p>
        </div>

        {{-- Recherche et filtres --}}
        <form method="GET" action="{{ route('super-admin.index') }}"
              class="grid grid-cols-1 gap-3 border-b border-slate-200 bg-slate-50 p-5 sm:grid-cols-2 xl:grid-cols-4 dark:border-slate-800 dark:bg-slate-950/40">

            <div class="xl:col-span-2">
                <label class="mb-1 block text-xs font-semibold text-slate-600 dark:text-slate-300">
                    Rechercher
                </label>

                <input
                    type="text"
                    name="recherche"
                    value="{{ $recherche }}"
                    placeholder="Nom de l'établissement ou ville..."
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                >
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-600 dark:text-slate-300">
                    Type d'établissement
                </label>

                <select
                    name="type"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                >
                    <option value="">Tous les types</option>

                    @foreach($typesEtablissements as $typeEtablissement)
                        <option
                            value="{{ $typeEtablissement->id }}"
                            @selected((string) $type === (string) $typeEtablissement->id)
                        >
                            {{ $typeEtablissement->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-600 dark:text-slate-300">
                    Statut
                </label>

                <select
                    name="statut"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                >
                    <option value="">Tous les statuts</option>
                    <option value="actif" @selected($statut === 'actif')>Actif</option>
                    <option value="en_attente_verification" @selected($statut === 'en_attente_verification')>
                        En attente de vérification
                    </option>
                    <option value="suspendu" @selected($statut === 'suspendu')>
                        Suspendu
                    </option>
                </select>
            </div>

            <div class="flex flex-wrap items-center gap-2 sm:col-span-2 xl:col-span-4">

                <button
                    type="submit"
                    class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
                >
                    Rechercher
                </button>

                <a
                    href="{{ route('super-admin.index') }}"
                    class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
                >
                    Réinitialiser
                </a>

            </div>
        </form>

        {{-- Tableau --}}
        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm">

                <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-slate-800 dark:text-slate-300">
                    <tr>
                        <th class="px-6 py-4">Établissement</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Localisation</th>
                        <th class="px-6 py-4">Chambres</th>
                        <th class="px-6 py-4">Clients</th>
                        <th class="px-6 py-4">Réservations</th>
                        <th class="px-6 py-4">Paiements</th>
                        <th class="px-6 py-4">Statut</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">

                    @forelse($etablissements as $etablissement)

                        @php
                            $statuts = [
                                'actif' => 'bg-green-100 text-green-700 dark:bg-green-950/50 dark:text-green-300',
                                'en_attente_verification' => 'bg-orange-100 text-orange-700 dark:bg-orange-950/50 dark:text-orange-300',
                                'suspendu' => 'bg-red-100 text-red-700 dark:bg-red-950/50 dark:text-red-300',
                            ];

                            $classeStatut = $statuts[$etablissement->statut] ?? 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300';
                        @endphp

                        <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/50">

                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-800 dark:text-white">
                                    {{ $etablissement->nom }}
                                </div>

                                <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                    {{ $etablissement->email ?? 'Aucun e-mail' }}
                                </div>
                            </td>

                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                {{ $etablissement->typeEtablissement?->nom ?? 'Non défini' }}
                            </td>

                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                {{ $etablissement->ville ?? '—' }},
                                {{ $etablissement->pays }}
                            </td>

                            <td class="px-6 py-4 font-medium text-slate-700 dark:text-slate-200">
                                {{ $etablissement->chambres_count }}
                            </td>

                            <td class="px-6 py-4 font-medium text-slate-700 dark:text-slate-200">
                                {{ $etablissement->clients_count }}
                            </td>

                            <td class="px-6 py-4 font-medium text-slate-700 dark:text-slate-200">
                                {{ $etablissement->reservations_count }}
                            </td>

                            <td class="px-6 py-4 font-medium text-slate-700 dark:text-slate-200">
                                {{ $etablissement->paiements_count }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $classeStatut }}">
                                    {{ ucfirst(str_replace('_', ' ', $etablissement->statut)) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a
                                    href="{{ route('super-admin.etablissements.show', $etablissement) }}"
                                    class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-blue-700"
                                >
                                    Consulter →
                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <p class="font-medium text-slate-600 dark:text-slate-300">
                                    Aucun établissement trouvé.
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    Modifiez votre recherche ou vos filtres.
                                </p>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        <div class="flex flex-col justify-between gap-3 border-t border-slate-200 px-6 py-4 sm:flex-row sm:items-center dark:border-slate-800">

            <p class="text-xs text-slate-500 dark:text-slate-400">
                {{ $etablissements->firstItem() ?? 0 }}
                à
                {{ $etablissements->lastItem() ?? 0 }}
                sur
                {{ $etablissements->total() }}
                établissement(s)
            </p>

            {{ $etablissements->links() }}

        </div>

    </div>

</div>

@endsection
