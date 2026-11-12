
@extends('layouts.app')

@section('title', 'Réservations')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Réservations
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Gérez les réservations et les séjours de votre établissement.
            </p>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row">

            <a href="{{ route('reservations.create', ['mode' => 'arrivee']) }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-5 py-3 text-sm font-semibold text-white hover:bg-green-700">
                + Nouvelle arrivée
            </a>

            <a href="{{ route('reservations.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2457A7] px-5 py-3 text-sm font-semibold text-white hover:bg-blue-800">
                + Nouvelle réservation
            </a>

        </div>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-green-100 p-4 text-green-800 dark:bg-green-900/30 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-lg bg-red-100 p-4 text-red-800 dark:bg-red-900/30 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

        <form action="{{ route('reservations.index') }}" method="GET"
              class="grid grid-cols-1 gap-4 md:grid-cols-3">

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Recherche
                </label>
                <input type="text"
                       name="recherche"
                       value="{{ request('recherche') }}"
                       placeholder="Référence, client ou chambre..."
                       class="w-full rounded-lg border border-gray-300 px-4 py-2.5 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Statut
                </label>
                <select name="statut"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Tous les statuts</option>
                    @foreach([
                        'en_attente' => 'En attente',
                        'confirmee' => 'Confirmée',
                        'en_cours' => 'En cours',
                        'terminee' => 'Terminée',
                        'annulee' => 'Annulée'
                    ] as $valeur => $libelle)
                        <option value="{{ $valeur }}" @selected(request('statut') === $valeur)>
                            {{ $libelle }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                        class="rounded-lg bg-[#2457A7] px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">
                    Rechercher
                </button>

                <a href="{{ route('reservations.index') }}"
                   class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-5 py-4">Référence</th>
                        <th class="px-5 py-4">Client</th>
                        <th class="px-5 py-4">Chambre</th>
                        <th class="px-5 py-4">Arrivée</th>
                        <th class="px-5 py-4">Départ</th>
                        <th class="px-5 py-4">Montant</th>
                        <th class="px-5 py-4">Statut</th>
                        <th class="px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                    @forelse($reservations as $reservation)
                        @php
                            $couleurs = [
                                'en_attente' => 'bg-yellow-100 text-yellow-800',
                                'confirmee' => 'bg-blue-100 text-blue-800',
                                'en_cours' => 'bg-green-100 text-green-800',
                                'terminee' => 'bg-gray-100 text-gray-800',
                                'annulee' => 'bg-red-100 text-red-800',
                            ];

                            $libelles = [
                                'en_attente' => 'En attente',
                                'confirmee' => 'Confirmée',
                                'en_cours' => 'En cours',
                                'terminee' => 'Terminée',
                                'annulee' => 'Annulée',
                            ];
                        @endphp

                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">

                            <td class="px-5 py-4 font-semibold text-[#2457A7]">
                                {{ $reservation->reference }}
                            </td>

                            <td class="px-5 py-4 text-gray-900 dark:text-white">
                                {{ $reservation->client->nom_affichage ?? 'Client indisponible' }}
                            </td>

                            <td class="px-5 py-4 text-gray-700 dark:text-gray-300">
                                {{ $reservation->chambre->numero ?? '—' }}
                            </td>

                            <td class="px-5 py-4 text-gray-700 dark:text-gray-300">
                                {{ $reservation->date_arrivee->format('d/m/Y H:i') }}
                            </td>

                            <td class="px-5 py-4 text-gray-700 dark:text-gray-300">
                                {{ $reservation->date_depart->format('d/m/Y H:i') }}
                            </td>

                            <td class="px-5 py-4 font-medium text-gray-900 dark:text-white">
                                {{ number_format($reservation->montant_total, 0, ',', ' ') }} FCFA
                            </td>

                            <td class="px-5 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $couleurs[$reservation->statut] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $libelles[$reservation->statut] ?? $reservation->statut }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-right">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('reservations.show', $reservation) }}"
                                       class="font-medium text-blue-600 hover:underline">
                                        Voir
                                    </a>

                                    @if($reservation->statut !== 'terminee' && $reservation->statut !== 'annulee')
                                        <a href="{{ route('reservations.edit', $reservation) }}"
                                           class="font-medium text-orange-600 hover:underline">
                                            Modifier
                                        </a>

                                        <form action="{{ route('reservations.destroy', $reservation) }}"
                                              method="POST"
                                              onsubmit="return confirm('Voulez-vous vraiment annuler cette réservation ?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="font-medium text-red-600 hover:underline">
                                                Annuler
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
                                <div class="text-gray-400">
                                    <p class="text-lg font-semibold">Aucune réservation</p>
                                    <p class="mt-1 text-sm">
                                        Aucune réservation ne correspond à votre recherche.
                                    </p>
                                    <a href="{{ route('reservations.create') }}"
                                       class="mt-4 inline-block font-semibold text-[#2457A7] hover:underline">
                                        Créer une réservation
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-700">
            {{ $reservations->links() }}
        </div>
    </div>

</div>
@endsection
