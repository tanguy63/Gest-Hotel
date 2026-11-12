@extends('layouts.app')

@section('title', 'Détail de la chambre')

@section('content')
<div class="mx-auto max-w-4xl space-y-6 p-4 sm:p-6 lg:p-8">

    @php
        $statuts = [
            'disponible' => ['Disponible', 'bg-green-100 text-green-700 dark:bg-green-950 dark:text-green-300'],
            'occupee' => ['Occupée', 'bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-300'],
            'maintenance' => ['En maintenance', 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300'],
            'hors_service' => ['Hors service', 'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-300'],
        ];

        $statut = $statuts[$chambre->statut] ?? [$chambre->statut, 'bg-slate-100 text-slate-600'];
    @endphp

    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <a href="{{ route('chambres.index') }}"
               class="text-sm font-medium text-[#2457A7] hover:underline">
                ← Retour aux chambres
            </a>

            <h1 class="mt-4 text-2xl font-bold text-slate-800 dark:text-white">
                Chambre {{ $chambre->numero }}
            </h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Informations détaillées de la chambre.
            </p>
        </div>

        <a href="{{ route('chambres.edit', $chambre->id) }}"
           class="rounded-xl bg-[#2457A7] px-5 py-3 text-center text-sm font-semibold text-white hover:bg-blue-800">
            Modifier la chambre
        </a>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-700 dark:bg-slate-900">
            <p class="text-sm text-slate-500 dark:text-slate-400">Tarif par nuit</p>
            <p class="mt-2 text-2xl font-bold text-slate-800 dark:text-white">
                {{ number_format((float) $chambre->tarif_nuit, 0, ',', ' ') }} FCFA
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-700 dark:bg-slate-900">
            <p class="text-sm text-slate-500 dark:text-slate-400">Statut actuel</p>
            <p class="mt-3">
                <span class="rounded-full px-3 py-1.5 text-sm font-semibold {{ $statut[1] }}">
                    {{ $statut[0] }}
                </span>
            </p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-900">
        <h2 class="text-lg font-bold text-slate-800 dark:text-white">
            Informations de la chambre
        </h2>

        <dl class="mt-5 grid gap-6 sm:grid-cols-2">
            <div>
                <dt class="text-xs font-semibold uppercase text-slate-500">Numéro</dt>
                <dd class="mt-1 text-sm font-medium text-slate-800 dark:text-white">
                    {{ $chambre->numero }}
                </dd>
            </div>

            <div>
                <dt class="text-xs font-semibold uppercase text-slate-500">Catégorie</dt>
                <dd class="mt-1 text-sm font-medium text-slate-800 dark:text-white">
                    {{ $chambre->categorieChambre?->nom ?? 'Non renseignée' }}
                </dd>
            </div>

            <div>
                <dt class="text-xs font-semibold uppercase text-slate-500">Étage</dt>
                <dd class="mt-1 text-sm font-medium text-slate-800 dark:text-white">
                    {{ $chambre->etage ?: 'Non renseigné' }}
                </dd>
            </div>

            <div>
                <dt class="text-xs font-semibold uppercase text-slate-500">Capacité</dt>
                <dd class="mt-1 text-sm font-medium text-slate-800 dark:text-white">
                    {{ $chambre->capacite }} personne(s)
                </dd>
            </div>

            <div class="sm:col-span-2">
                <dt class="text-xs font-semibold uppercase text-slate-500">Description</dt>
                <dd class="mt-1 text-sm text-slate-700 dark:text-slate-300">
                    {{ $chambre->description ?: 'Aucune description renseignée.' }}
                </dd>
            </div>
        </dl>
    </div>

    <div class="flex justify-end">
        <form action="{{ route('chambres.destroy', $chambre->id) }}"
              method="POST"
              onsubmit="return confirm('Voulez-vous vraiment supprimer cette chambre ?')">
            @csrf
            @method('DELETE')

            <button type="submit"
                    class="rounded-xl border border-red-200 px-5 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-50 dark:border-red-900 dark:hover:bg-red-950">
                Supprimer cette chambre
            </button>
        </form>
    </div>
</div>
@endsection