
@extends('layouts.app')

@section('title', 'Consultation établissement')

@section('content')

<div class="space-y-6">

    {{-- En-tête --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

        <div>
            <a href="{{ route('super-admin.index') }}"
               class="text-sm font-medium text-blue-600 hover:underline">
                ← Retour aux établissements
            </a>

            <h1 class="mt-3 text-2xl font-bold text-slate-800 dark:text-white">
                {{ $etablissement->nom }}
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                {{ $etablissement->typeEtablissement?->nom ?? 'Type non défini' }}
                · {{ $etablissement->ville ?? 'Ville non renseignée' }},
                {{ $etablissement->pays }}
            </p>
        </div>

        <span class="inline-flex w-fit rounded-full bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700 dark:bg-blue-950 dark:text-blue-300">
            {{ ucfirst(str_replace('_', ' ', $etablissement->statut)) }}
        </span>

    </div>

    {{-- Informations générales --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">

        <h2 class="mb-5 text-lg font-semibold text-slate-800 dark:text-white">
            Informations générales
        </h2>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

            <div>
                <p class="text-xs text-slate-500">Nom</p>
                <p class="mt-1 font-medium text-slate-800 dark:text-white">
                    {{ $etablissement->nom }}
                </p>
            </div>

            <div>
                <p class="text-xs text-slate-500">E-mail</p>
                <p class="mt-1 font-medium text-slate-800 dark:text-white">
                    {{ $etablissement->email ?? 'Non renseigné' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-slate-500">Téléphone</p>
                <p class="mt-1 font-medium text-slate-800 dark:text-white">
                    {{ $etablissement->telephone ?? 'Non renseigné' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-slate-500">Adresse</p>
                <p class="mt-1 font-medium text-slate-800 dark:text-white">
                    {{ $etablissement->adresse ?? 'Non renseignée' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-slate-500">Devise</p>
                <p class="mt-1 font-medium text-slate-800 dark:text-white">
                    {{ $etablissement->devise }}
                </p>
            </div>

            <div>
                <p class="text-xs text-slate-500">Fuseau horaire</p>
                <p class="mt-1 font-medium text-slate-800 dark:text-white">
                    {{ $etablissement->fuseau_horaire }}
                </p>
            </div>

        </div>

    </div>

    {{-- Statistiques --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">

        @php
            $cartes = [
                ['label' => 'Chambres', 'valeur' => $statistiques['chambres'], 'couleur' => 'blue'],
                ['label' => 'Clients', 'valeur' => $statistiques['clients'], 'couleur' => 'indigo'],
                ['label' => 'Réservations', 'valeur' => $statistiques['reservations'], 'couleur' => 'orange'],
                ['label' => 'Paiements', 'valeur' => $statistiques['paiements'], 'couleur' => 'purple'],
                ['label' => 'Personnel actif', 'valeur' => $statistiques['personnel'], 'couleur' => 'green'],
                ['label' => 'Revenus encaissés', 'valeur' => number_format((float) $statistiques['revenus'], 0, ',', ' ') . ' ' . $etablissement->devise, 'couleur' => 'emerald'],
            ];
        @endphp

        @foreach($cartes as $carte)

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">

                <p class="text-sm text-slate-500">
                    {{ $carte['label'] }}
                </p>

                <p class="mt-2 text-2xl font-bold text-slate-800 dark:text-white">
                    {{ $carte['valeur'] }}
                </p>

            </div>

        @endforeach

    </div>

    {{-- Chambres --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

        <div class="border-b border-slate-200 p-5 dark:border-slate-800">
            <h2 class="font-semibold text-slate-800 dark:text-white">
                Dernières chambres
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">

                <thead class="bg-slate-50 text-slate-500 dark:bg-slate-800">
                    <tr>
                        <th class="px-5 py-3">Numéro</th>
                        <th class="px-5 py-3">Catégorie</th>
                        <th class="px-5 py-3">Capacité</th>
                        <th class="px-5 py-3">Tarif / nuit</th>
                        <th class="px-5 py-3">Statut</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">

                    @forelse($chambres as $chambre)
                        <tr>
                            <td class="px-5 py-3 font-medium">
                                {{ $chambre->numero }}
                            </td>
                            <td class="px-5 py-3">
                                {{ $chambre->categorieChambre?->nom ?? '—' }}
                            </td>
                            <td class="px-5 py-3">
                                {{ $chambre->capacite }} personne(s)
                            </td>
                            <td class="px-5 py-3">
                                {{ number_format((float) $chambre->tarif_nuit, 0, ',', ' ') }}
                                {{ $etablissement->devise }}
                            </td>
                            <td class="px-5 py-3">
                                {{ ucfirst($chambre->statut) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-6 text-center text-slate-500">
                                Aucune chambre enregistrée.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    {{-- Clients --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

        <div class="border-b border-slate-200 p-5 dark:border-slate-800">
            <h2 class="font-semibold text-slate-800 dark:text-white">
                Derniers clients
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">

                <thead class="bg-slate-50 text-slate-500 dark:bg-slate-800">
                    <tr>
                        <th class="px-5 py-3">Nom / Raison sociale</th>
                        <th class="px-5 py-3">Téléphone</th>
                        <th class="px-5 py-3">E-mail</th>
                        <th class="px-5 py-3">Type</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">

                    @forelse($clients as $client)
                        <tr>
                            <td class="px-5 py-3 font-medium">
                                {{ $client->nom_affichage }}
                            </td>
                            <td class="px-5 py-3">
                                {{ $client->telephone ?? '—' }}
                            </td>
                            <td class="px-5 py-3">
                                {{ $client->email ?? '—' }}
                            </td>
                            <td class="px-5 py-3">
                                {{ ucfirst($client->type) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-6 text-center text-slate-500">
                                Aucun client enregistré.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    {{-- Réservations --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

        <div class="border-b border-slate-200 p-5 dark:border-slate-800">
            <h2 class="font-semibold text-slate-800 dark:text-white">
                Dernières réservations
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">

                <thead class="bg-slate-50 text-slate-500 dark:bg-slate-800">
                    <tr>
                        <th class="px-5 py-3">Référence</th>
                        <th class="px-5 py-3">Client</th>
                        <th class="px-5 py-3">Chambre</th>
                        <th class="px-5 py-3">Arrivée</th>
                        <th class="px-5 py-3">Départ</th>
                        <th class="px-5 py-3">Montant total</th>
                        <th class="px-5 py-3">Statut</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">

                    @forelse($reservations as $reservation)
                        <tr>
                            <td class="px-5 py-3 font-medium">
                                {{ $reservation->reference }}
                            </td>
                            <td class="px-5 py-3">
                                {{ $reservation->client?->nom_affichage ?? 'Client supprimé' }}
                            </td>
                            <td class="px-5 py-3">
                                {{ $reservation->chambre?->numero ?? '—' }}
                            </td>
                            <td class="px-5 py-3">
                                {{ $reservation->date_arrivee?->format('d/m/Y') }}
                            </td>
                            <td class="px-5 py-3">
                                {{ $reservation->date_depart?->format('d/m/Y') }}
                            </td>
                            <td class="px-5 py-3">
                                {{ number_format((float) $reservation->montant_total, 0, ',', ' ') }}
                                {{ $etablissement->devise }}
                            </td>
                            <td class="px-5 py-3">
                                {{ ucfirst(str_replace('_', ' ', $reservation->statut)) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-6 text-center text-slate-500">
                                Aucune réservation enregistrée.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    {{-- Paiements --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

        <div class="border-b border-slate-200 p-5 dark:border-slate-800">
            <h2 class="font-semibold text-slate-800 dark:text-white">
                Derniers paiements
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">

                <thead class="bg-slate-50 text-slate-500 dark:bg-slate-800">
                    <tr>
                        <th class="px-5 py-3">Référence</th>
                        <th class="px-5 py-3">Réservation</th>
                        <th class="px-5 py-3">Montant</th>
                        <th class="px-5 py-3">Mode</th>
                        <th class="px-5 py-3">Date</th>
                        <th class="px-5 py-3">Statut</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">

                    @forelse($paiements as $paiement)
                        <tr>
                            <td class="px-5 py-3 font-medium">
                                {{ $paiement->reference }}
                            </td>
                            <td class="px-5 py-3">
                                {{ $paiement->reservation?->reference ?? '—' }}
                            </td>
                            <td class="px-5 py-3">
                                {{ number_format((float) $paiement->montant, 0, ',', ' ') }}
                                {{ $etablissement->devise }}
                            </td>
                            <td class="px-5 py-3">
                                {{ ucfirst(str_replace('_', ' ', $paiement->mode_paiement)) }}
                            </td>
                            <td class="px-5 py-3">
                                {{ $paiement->date_paiement?->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-5 py-3">
                                {{ ucfirst($paiement->statut) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-6 text-center text-slate-500">
                                Aucun paiement enregistré.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>


@php
    $rolesLibelles = [
        'administrateur' => 'Administrateur',
        'agent_exploitation' => 'Agent d’exploitation',
        'comptable' => 'Comptable',
        'entretien' => 'Personnel d’entretien',
    ];
@endphp

{{-- Personnel de l'établissement --}}
<div class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">

    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-6 py-5 dark:border-slate-700">
        <div>
            <h2 class="text-lg font-semibold text-slate-800 dark:text-white">
                Personnel de l'établissement
            </h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Comptes du personnel actuellement actifs
            </p>
        </div>

        <span class="rounded-full bg-blue-50 px-3 py-1 text-sm font-medium text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">
            {{ $statistiques['personnel'] }} membre(s)
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                <tr>
                    <th class="px-6 py-4">Nom complet</th>
                    <th class="px-6 py-4">Adresse e-mail</th>
                    <th class="px-6 py-4">Rôle</th>
                    <th class="px-6 py-4">Accès</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($personnel as $membre)
                    <tr>
                        <td class="whitespace-nowrap px-6 py-4 font-medium text-slate-800 dark:text-white">
                            {{ $membre->nom_complet }}
                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-slate-600 dark:text-slate-300">
                            {{ $membre->email }}
                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-slate-600 dark:text-slate-300">
                            {{ $rolesLibelles[$membre->pivot->role] ?? ucfirst(str_replace('_', ' ', $membre->pivot->role)) }}
                        </td>

                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700 dark:bg-green-500/10 dark:text-green-400">
                                Actif
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                            Aucun membre du personnel actif dans cet établissement.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


    <p class="text-xs text-slate-500">
        Consultation en lecture seule : aucune modification des données de cet établissement n'est possible depuis cet espace.
    </p>

</div>

@endsection
