
@extends('layouts.app')

@section('title', 'Détails de la réservation')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">

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

    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

        <div>
            <a href="{{ route('reservations.index') }}"
               class="text-sm font-medium text-[#2457A7] hover:underline">
                ← Retour aux réservations
            </a>

            <h1 class="mt-3 text-2xl font-bold text-gray-900 dark:text-white">
                Détails de la réservation
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Référence : {{ $reservation->reference }}
            </p>
        </div>

        <div class="flex flex-wrap gap-2">

    <a href="{{ route('reservations.recu', $reservation) }}"
        target="_blank"
        rel="noopener noreferrer"
        class="rounded-lg bg-green-600 px-5 py-3 text-sm font-semibold text-white hover:bg-green-700">
        Reçu de paiement
    </a>

    @if(!in_array($reservation->statut, ['terminee', 'annulee']))
                <a href="{{ route('reservations.edit', $reservation) }}"
                   class="rounded-lg bg-[#2457A7] px-5 py-3 text-sm font-semibold text-white hover:bg-blue-800">
                    Modifier
                </a>

                <form action="{{ route('reservations.destroy', $reservation) }}"
                      method="POST"
                      onsubmit="return confirm('Voulez-vous vraiment annuler cette réservation ?')">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="rounded-lg bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-700">
                        Annuler
                    </button>
                </form>
            @endif

        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Référence</p>
                <p class="mt-1 text-xl font-bold text-[#2457A7]">
                    {{ $reservation->reference }}
                </p>
            </div>

            <span class="rounded-full px-4 py-2 text-sm font-semibold {{ $couleurs[$reservation->statut] ?? 'bg-gray-100 text-gray-700' }}">
                {{ $libelles[$reservation->statut] ?? $reservation->statut }}
            </span>
        </div>

    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <h2 class="mb-5 text-lg font-bold text-gray-900 dark:text-white">
                Informations du client
            </h2>

            <div class="space-y-4">

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nom du client</p>
                    <p class="mt-1 font-semibold text-gray-900 dark:text-white">
                        {{ $reservation->client->nom_affichage ?? 'Client indisponible' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Téléphone</p>
                    <p class="mt-1 text-gray-900 dark:text-white">
                        {{ $reservation->client->telephone ?? 'Non renseigné' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                    <p class="mt-1 text-gray-900 dark:text-white">
                        {{ $reservation->client->email ?? 'Non renseigné' }}
                    </p>
                </div>

            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <h2 class="mb-5 text-lg font-bold text-gray-900 dark:text-white">
                Informations de la chambre
            </h2>

            <div class="space-y-4">

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Numéro de chambre</p>
                    <p class="mt-1 font-semibold text-gray-900 dark:text-white">
                        {{ $reservation->chambre->numero ?? 'Chambre indisponible' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Catégorie</p>
                    <p class="mt-1 text-gray-900 dark:text-white">
                        {{ $reservation->chambre->categorieChambre->nom ?? 'Non renseignée' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nombre de personnes</p>
                    <p class="mt-1 text-gray-900 dark:text-white">
                        {{ $reservation->nombre_personnes }}
                    </p>
                </div>

            </div>
        </div>

    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

        <h2 class="mb-5 text-lg font-bold text-gray-900 dark:text-white">
            Période du séjour
        </h2>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">

            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Arrivée</p>
                <p class="mt-1 font-semibold text-gray-900 dark:text-white">
                    {{ $reservation->date_arrivee->format('d/m/Y à H:i') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Départ</p>
                <p class="mt-1 font-semibold text-gray-900 dark:text-white">
                    {{ $reservation->date_depart->format('d/m/Y à H:i') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Nombre de nuits</p>
                <p class="mt-1 font-semibold text-gray-900 dark:text-white">
                    {{ $reservation->nombre_nuits }} nuit(s)
                </p>
            </div>

        </div>

    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

        <h2 class="mb-5 text-lg font-bold text-gray-900 dark:text-white">
            Récapitulatif financier
        </h2>

        <div class="space-y-4">

            <div class="flex justify-between gap-4">
                <span class="text-gray-500 dark:text-gray-400">Tarif par nuit</span>
                <span class="font-medium text-gray-900 dark:text-white">
                    {{ number_format($reservation->tarif_nuit, 0, ',', ' ') }} FCFA
                </span>
            </div>

            <div class="flex justify-between gap-4">
                <span class="text-gray-500 dark:text-gray-400">Nombre de nuits</span>
                <span class="font-medium text-gray-900 dark:text-white">
                    {{ $reservation->nombre_nuits }}
                </span>
            </div>

            <div class="flex justify-between gap-4 border-t border-gray-200 pt-4 dark:border-gray-700">
                <span class="font-semibold text-gray-900 dark:text-white">Montant total</span>
                <span class="text-lg font-bold text-[#2457A7]">
                    {{ number_format($reservation->montant_total, 0, ',', ' ') }} FCFA
                </span>
            </div>

            <div class="flex justify-between gap-4">
                <span class="text-gray-500 dark:text-gray-400">Avance versée</span>
                <span class="font-semibold text-green-600">
                    {{ number_format($montantEncaisse, 0, ',', ' ') }} FCFA
                </span>
            </div>

            <div class="flex justify-between gap-4 rounded-lg bg-orange-50 p-4 dark:bg-orange-900/20">
                <span class="font-semibold text-orange-800 dark:text-orange-300">Reste à payer</span>
                <span class="text-lg font-bold text-orange-700 dark:text-orange-300">
                    {{ number_format($resteAPayer, 0, ',', ' ') }} FCFA
                </span>
            </div>

        </div>
    </div>


{{-- HISTORIQUE DES PAIEMENTS --}}
<div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                Historique des paiements
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Liste des encaissements associés à cette réservation.
            </p>
        </div>

        <span class="rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-800">
            {{ $reservation->paiements->count() }} paiement(s)
        </span>
    </div>

    @if($reservation->paiements->isEmpty())

        <div class="rounded-lg bg-gray-50 p-5 text-center text-sm text-gray-500 dark:bg-gray-700 dark:text-gray-300">
            Aucun paiement enregistré pour cette réservation.
        </div>

    @else

        <div class="overflow-x-auto">
            <table class="w-full min-w-[650px] text-left text-sm">

                <thead class="border-b border-gray-200 text-gray-500 dark:border-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-3 py-3">Référence</th>
                        <th class="px-3 py-3">Montant</th>
                        <th class="px-3 py-3">Mode</th>
                        <th class="px-3 py-3">Date</th>
                        <th class="px-3 py-3">Statut</th>
                        <th class="px-3 py-3">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">

                    @foreach($reservation->paiements as $paiement)

                        @php
                            $modesPaiement = [
                                'especes' => 'Espèces',
                                'mobile_money' => 'Mobile Money',
                                'carte_bancaire' => 'Carte bancaire',
                                'virement' => 'Virement',
                                'cheque' => 'Chèque',
                            ];
                        @endphp

                        <tr>
                            <td class="px-3 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $paiement->reference }}
                            </td>

                            <td class="px-3 py-4 font-semibold text-gray-900 dark:text-white">
                                {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                            </td>

                            <td class="px-3 py-4 text-gray-600 dark:text-gray-300">
                                {{ $modesPaiement[$paiement->mode_paiement] ?? $paiement->mode_paiement }}
                            </td>

                            <td class="px-3 py-4 text-gray-600 dark:text-gray-300">
                                {{ $paiement->date_paiement->format('d/m/Y à H:i') }}
                            </td>

                            <td class="px-3 py-4">
                                @if($paiement->statut === 'valide')
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        Validé
                                    </span>
                                @else
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                        Annulé
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-4">
                                @if($paiement->statut === 'valide')
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        Validé
                                    </span>
                                @else
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                        Annulé
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-4">
                                <a href="{{ route('paiements.recu', $paiement->id) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex rounded-lg bg-green-600 px-3 py-2 text-xs font-semibold text-white hover:bg-green-700">
                                    Imprimer le reçu
                                </a>
                            </td>
                        </tr>

                    @endforeach

                </tbody>
            </table>
        </div>

    @endif
</div>

    {{-- FORMULAIRE NOUVEAU PAIEMENT --}}
@php
    $roleEtablissement = auth()->user()
        ->etablissements()
        ->wherePivot('est_actif', true)
        ->where('etablissements.id', session('etablissement_actif'))
        ->first()?->pivot?->role;
@endphp

@if(
    in_array($roleEtablissement, [
        'proprietaire',
        'administrateur',
        'agent_exploitation',
        'comptable'
    ], true)
    && $reservation->statut !== 'annulee'
    && $resteAPayer > 0
)


    @if($reservation->notes)
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <h2 class="mb-3 font-bold text-gray-900 dark:text-white">
                Notes complémentaires
            </h2>

            <p class="whitespace-pre-line text-gray-700 dark:text-gray-300">
                {{ $reservation->notes }}
            </p>
        </div>
    @endif

    @if($reservation->motif_annulation)
        <div class="rounded-xl border border-red-200 bg-red-50 p-6 dark:border-red-900 dark:bg-red-900/20">
            <h2 class="mb-3 font-bold text-red-800 dark:text-red-300">
                Motif d'annulation
            </h2>

            <p class="whitespace-pre-line text-red-700 dark:text-red-300">
                {{ $reservation->motif_annulation }}
            </p>
        </div>
    @endif

    <div class="text-xs text-gray-400">
        Créée le {{ $reservation->created_at->format('d/m/Y à H:i') }}
        — Dernière modification : {{ $reservation->updated_at->format('d/m/Y à H:i') }}
    </div>

</div>
@endsection
