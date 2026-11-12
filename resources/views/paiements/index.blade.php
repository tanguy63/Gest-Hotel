
@extends('layouts.app')

@section('title', 'Paiements')

@section('content')
<div class="space-y-6">

    {{-- En-tête --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Paiements et encaissements
        </h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Consultez et suivez tous les paiements de votre établissement.
        </p>
    </div>

    {{-- Statistiques --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Total encaissé
            </p>
            <p class="mt-2 text-2xl font-bold text-green-600">
                {{ number_format($totalEncaisse, 0, ',', ' ') }} FCFA
            </p>
            <p class="mt-1 text-xs text-gray-400">
                Paiements validés
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Encaissements aujourd'hui
            </p>
            <p class="mt-2 text-2xl font-bold text-blue-600">
                {{ number_format($encaisseAujourdHui, 0, ',', ' ') }} FCFA
            </p>
            <p class="mt-1 text-xs text-gray-400">
                Paiements du jour
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Paiements annulés
            </p>
            <p class="mt-2 text-2xl font-bold text-red-600">
                {{ number_format($totalAnnule, 0, ',', ' ') }} FCFA
            </p>
            <p class="mt-1 text-xs text-gray-400">
                Montant des paiements annulés
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Nombre de paiements
            </p>
            <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">
                {{ number_format($nombrePaiements, 0, ',', ' ') }}
            </p>
            <p class="mt-1 text-xs text-gray-400">
                Toutes opérations confondues
            </p>
        </div>

    </div>

    {{-- Filtres --}}
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

        <form action="{{ route('paiements.index') }}" method="GET">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">

                <div class="xl:col-span-2">
                    <label for="recherche" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Recherche
                    </label>
                    <input
                        type="text"
                        name="recherche"
                        id="recherche"
                        value="{{ request('recherche') }}"
                        placeholder="Référence, client..."
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    >
                </div>

                <div>
                    <label for="mode_paiement" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Mode de paiement
                    </label>
                    <select
                        name="mode_paiement"
                        id="mode_paiement"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    >
                        <option value="">Tous les modes</option>
                        <option value="especes" @selected(request('mode_paiement') === 'especes')>Espèces</option>
                        <option value="mobile_money" @selected(request('mode_paiement') === 'mobile_money')>Mobile Money</option>
                        <option value="carte_bancaire" @selected(request('mode_paiement') === 'carte_bancaire')>Carte bancaire</option>
                        <option value="virement" @selected(request('mode_paiement') === 'virement')>Virement</option>
                        <option value="cheque" @selected(request('mode_paiement') === 'cheque')>Chèque</option>
                    </select>
                </div>

                <div>
                    <label for="statut" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Statut
                    </label>
                    <select
                        name="statut"
                        id="statut"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    >
                        <option value="">Tous les statuts</option>
                        <option value="valide" @selected(request('statut') === 'valide')>Validé</option>
                        <option value="annule" @selected(request('statut') === 'annule')>Annulé</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button
                        type="submit"
                        class="flex-1 rounded-lg bg-blue-700 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800"
                    >
                        Filtrer
                    </button>

                    <a
                        href="{{ route('paiements.index') }}"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                    >
                        Effacer
                    </a>
                </div>

                <div>
                    <label for="date_debut" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Date début
                    </label>
                    <input
                        type="date"
                        name="date_debut"
                        id="date_debut"
                        value="{{ request('date_debut') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    >
                </div>

                <div>
                    <label for="date_fin" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Date fin
                    </label>
                    <input
                        type="date"
                        name="date_fin"
                        id="date_fin"
                        value="{{ request('date_fin') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    >
                </div>

            </div>
        </form>
    </div>

    {{-- Tableau des paiements --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

        <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-700">
            <h2 class="font-semibold text-gray-900 dark:text-white">
                Historique des paiements
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-300">
                            Référence
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-300">
                            Client
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-300">
                            Réservation
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-300">
                            Montant
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-300">
                            Mode
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-300">
                            Date
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-300">
                            Statut
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-300">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                    @forelse($paiements as $paiement)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">

                            <td class="whitespace-nowrap px-5 py-4 text-sm font-semibold text-blue-700 dark:text-blue-400">
                                {{ $paiement->reference }}
                            </td>

                            <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                                @if($paiement->reservation?->client)
                                    @php
                                        $client = $paiement->reservation->client;
                                    @endphp

                                    {{ $client->raison_sociale ?: trim($client->nom . ' ' . $client->prenoms) }}
                                @else
                                    <span class="text-gray-400">Client indisponible</span>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                                {{ $paiement->reservation?->reference ?? '—' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                {{ number_format((float) $paiement->montant, 0, ',', ' ') }} FCFA
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                                @switch($paiement->mode_paiement)
                                    @case('especes')
                                        Espèces
                                        @break
                                    @case('mobile_money')
                                        Mobile Money
                                        @break
                                    @case('carte_bancaire')
                                        Carte bancaire
                                        @break
                                    @case('virement')
                                        Virement
                                        @break
                                    @case('cheque')
                                        Chèque
                                        @break
                                    @default
                                        {{ $paiement->mode_paiement }}
                                @endswitch
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $paiement->date_paiement?->format('d/m/Y H:i') ?? '—' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                @if($paiement->statut === 'valide')
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                        Validé
                                    </span>
                                @else
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                        Annulé
                                    </span>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                <a
                                    href="{{ route('paiements.recu', $paiement->id) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center gap-2 rounded-lg bg-[#2457A7] px-3 py-2 text-xs font-semibold text-white transition hover:bg-blue-800"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6z"
                                        />
                                    </svg>

                                    Voir / Imprimer
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
                                <p class="font-medium text-gray-700 dark:text-gray-300">
                                    Aucun paiement trouvé.
                                </p>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Les avances et les autres encaissements apparaîtront ici.
                                </p>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($paiements->hasPages())
            <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-700">
                {{ $paiements->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
