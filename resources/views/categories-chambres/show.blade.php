@extends('layouts.app')

@section('title', 'Détail de la catégorie')

@section('content')
<div class="mx-auto max-w-4xl space-y-6 p-4 sm:p-6 lg:p-8">

    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <a href="{{ route('categories-chambres.index') }}"
               class="text-sm font-medium text-[#2457A7] hover:underline">
                ← Retour aux catégories
            </a>

            <h1 class="mt-4 text-2xl font-bold text-slate-800 dark:text-white">
                {{ $categorie->nom }}
            </h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Détails de cette catégorie de chambres.
            </p>
        </div>

        <a href="{{ route('categories-chambres.edit', $categorie->id) }}"
           class="rounded-xl bg-[#2457A7] px-5 py-3 text-center text-sm font-semibold text-white hover:bg-blue-800">
            Modifier la catégorie
        </a>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-700 dark:bg-slate-900">
            <p class="text-sm text-slate-500 dark:text-slate-400">Capacité maximale</p>
            <p class="mt-2 text-2xl font-bold text-slate-800 dark:text-white">
                {{ $categorie->capacite }} personnes
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-700 dark:bg-slate-900">
            <p class="text-sm text-slate-500 dark:text-slate-400">Tarif de base</p>
            <p class="mt-2 text-2xl font-bold text-slate-800 dark:text-white">
                {{ number_format((float) $categorie->tarif_base, 0, ',', ' ') }} FCFA
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-700 dark:bg-slate-900">
            <p class="text-sm text-slate-500 dark:text-slate-400">Nombre de chambres</p>
            <p class="mt-2 text-2xl font-bold text-slate-800 dark:text-white">
                {{ $categorie->chambres->count() }}
            </p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-900">
        <h2 class="text-lg font-bold text-slate-800 dark:text-white">Informations générales</h2>

        <div class="mt-5 space-y-4">
            <div>
                <p class="text-xs font-semibold uppercase text-slate-500">Description</p>
                <p class="mt-1 text-sm text-slate-700 dark:text-slate-300">
                    {{ $categorie->description ?: 'Aucune description renseignée.' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase text-slate-500">Statut</p>
                <p class="mt-2">
                    @if($categorie->est_active)
                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700 dark:bg-green-950 dark:text-green-300">Active</span>
                    @else
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">Inactive</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-900">
        <h2 class="text-lg font-bold text-slate-800 dark:text-white">Chambres associées</h2>

        @forelse($categorie->chambres as $chambre)
            <div class="mt-4 flex items-center justify-between gap-4 rounded-xl bg-slate-50 p-4 dark:bg-slate-800">
                <div>
                    <p class="font-semibold text-slate-800 dark:text-white">
                        Chambre {{ $chambre->numero }}
                    </p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        {{ number_format((float) $chambre->tarif_nuit, 0, ',', ' ') }} FCFA / nuit
                    </p>
                </div>

                <a href="{{ route('chambres.show', $chambre->id) }}"
                   class="text-sm font-semibold text-[#2457A7] hover:underline">
                    Consulter
                </a>
            </div>
        @empty
            <p class="mt-4 rounded-xl bg-slate-50 p-5 text-sm text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                Aucune chambre n'est encore associée à cette catégorie.
            </p>
        @endforelse
    </div>
</div>
@endsection