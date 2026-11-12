@extends('layouts.app')

@section('title', 'Gestion des chambres')

@section('content')
<div class="space-y-6 p-4 sm:p-6 lg:p-8">

    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">
                Gestion des chambres
            </h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Consultez et gérez les chambres de votre établissement.
            </p>
        </div>

        <a href="{{ route('chambres.create') }}"
           class="inline-flex items-center justify-center rounded-xl bg-[#2457A7] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">
            + Ajouter une chambre
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700 dark:border-green-900 dark:bg-green-950 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-900 dark:bg-red-950 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('chambres.index') }}" method="GET"
          class="grid gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900 md:grid-cols-4">

        <div>
            <label for="recherche" class="mb-2 block text-xs font-semibold text-slate-600 dark:text-slate-300">
                Numéro de chambre
            </label>
            <input
                type="text"
                name="recherche"
                id="recherche"
                value="{{ request('recherche') }}"
                placeholder="Ex. 101"
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none focus:border-[#2457A7] focus:ring-2 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
            >
        </div>

        <div>
            <label for="categorie" class="mb-2 block text-xs font-semibold text-slate-600 dark:text-slate-300">
                Catégorie
            </label>
            <select name="categorie" id="categorie"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none focus:border-[#2457A7] dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $categorie)
                    <option value="{{ $categorie->id }}" @selected(request('categorie') == $categorie->id)>
                        {{ $categorie->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="statut" class="mb-2 block text-xs font-semibold text-slate-600 dark:text-slate-300">
                Statut
            </label>
            <select name="statut" id="statut"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none focus:border-[#2457A7] dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                <option value="">Tous les statuts</option>
                <option value="disponible" @selected(request('statut') === 'disponible')>Disponible</option>
                <option value="occupee" @selected(request('statut') === 'occupee')>Occupée</option>
                <option value="maintenance" @selected(request('statut') === 'maintenance')>En maintenance</option>
                <option value="hors_service" @selected(request('statut') === 'hors_service')>Hors service</option>
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button type="submit"
                    class="flex-1 rounded-xl bg-[#2457A7] px-4 py-3 text-sm font-semibold text-white hover:bg-blue-800">
                Filtrer
            </button>
            <a href="{{ route('chambres.index') }}"
               class="rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800">
                Effacer
            </a>
        </div>
    </form>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">

        @if($chambres->count())
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                        <tr>
                            <th class="px-6 py-4">Chambre</th>
                            <th class="px-6 py-4">Catégorie</th>
                            <th class="px-6 py-4">Étage</th>
                            <th class="px-6 py-4">Capacité</th>
                            <th class="px-6 py-4">Tarif / nuit</th>
                            <th class="px-6 py-4">Statut</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($chambres as $chambre)
                            @php
                                $statuts = [
                                    'disponible' => ['Disponible', 'bg-green-100 text-green-700 dark:bg-green-950 dark:text-green-300'],
                                    'occupee' => ['Occupée', 'bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-300'],
                                    'maintenance' => ['En maintenance', 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300'],
                                    'hors_service' => ['Hors service', 'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-300'],
                                ];
                                $statut = $statuts[$chambre->statut] ?? [$chambre->statut, 'bg-slate-100 text-slate-600'];
                            @endphp

                            <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-800 dark:text-white">
                                        {{ $chambre->numero }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                    {{ $chambre->categorieChambre?->nom ?? 'Catégorie supprimée' }}
                                </td>

                                <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                    {{ $chambre->etage ?: '—' }}
                                </td>

                                <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                    {{ $chambre->capacite }} personne(s)
                                </td>

                                <td class="px-6 py-4 font-medium text-slate-700 dark:text-slate-200">
                                    {{ number_format((float) $chambre->tarif_nuit, 0, ',', ' ') }} FCFA
                                </td>

                                <td class="px-6 py-4">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statut[1] }}">
                                        {{ $statut[0] }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('chambres.show', $chambre->id) }}"
                                           class="font-medium text-[#2457A7] hover:underline">
                                            Voir
                                        </a>

                                        <a href="{{ route('chambres.edit', $chambre->id) }}"
                                           class="font-medium text-amber-600 hover:underline">
                                            Modifier
                                        </a>

                                        <form action="{{ route('chambres.destroy', $chambre->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Voulez-vous vraiment supprimer cette chambre ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 hover:underline">
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-6 py-4 dark:border-slate-800">
                {{ $chambres->links() }}
            </div>
        @else
            <div class="px-6 py-16 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-3xl dark:bg-blue-950">
                    🛏️
                </div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">
                    Aucune chambre trouvée
                </h3>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    Ajoutez une chambre ou modifiez vos critères de recherche.
                </p>
                <a href="{{ route('chambres.create') }}"
                   class="mt-5 inline-flex rounded-xl bg-[#2457A7] px-5 py-3 text-sm font-semibold text-white hover:bg-blue-800">
                    Ajouter une chambre
                </a>
            </div>
        @endif
    </div>
</div>
@endsection