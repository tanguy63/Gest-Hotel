@extends('layouts.app')

@section('title', 'Catégories de chambres')

@section('content')
<div class="space-y-6 p-4 sm:p-6 lg:p-8">

    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">
                Catégories de chambres
            </h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Organisez vos chambres par type et capacité.
            </p>
        </div>

        <a href="{{ route('categories-chambres.create') }}"
           class="inline-flex items-center justify-center rounded-xl bg-[#2457A7] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">
            + Nouvelle catégorie
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

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
        @if($categories->count())
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                        <tr>
                            <th class="px-6 py-4">Catégorie</th>
                            <th class="px-6 py-4">Capacité</th>
                            <th class="px-6 py-4">Tarif de base</th>
                            <th class="px-6 py-4">Chambres</th>
                            <th class="px-6 py-4">Statut</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($categories as $categorie)
                            <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800 dark:text-white">
                                        {{ $categorie->nom }}
                                    </div>
                                    <div class="mt-1 max-w-xs truncate text-xs text-slate-500 dark:text-slate-400">
                                        {{ $categorie->description ?: 'Aucune description' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                    {{ $categorie->capacite }} personne(s)
                                </td>

                                <td class="px-6 py-4 font-medium text-slate-700 dark:text-slate-200">
                                    {{ number_format((float) $categorie->tarif_base, 0, ',', ' ') }} FCFA
                                </td>

                                <td class="px-6 py-4">
                                    <span class="rounded-lg bg-blue-50 px-3 py-1.5 font-semibold text-[#2457A7] dark:bg-blue-950 dark:text-blue-300">
                                        {{ $categorie->chambres_count }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    @if($categorie->est_active)
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700 dark:bg-green-950 dark:text-green-300">
                                            Active
                                        </span>
                                    @else
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('categories-chambres.show', $categorie->id) }}"
                                           class="font-medium text-[#2457A7] hover:underline">
                                            Voir
                                        </a>

                                        <a href="{{ route('categories-chambres.edit', $categorie->id) }}"
                                           class="font-medium text-amber-600 hover:underline">
                                            Modifier
                                        </a>

                                        @if($categorie->chambres_count === 0)
                                            <form action="{{ route('categories-chambres.destroy', $categorie->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Voulez-vous vraiment supprimer cette catégorie ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 hover:underline">
                                                    Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-6 py-4 dark:border-slate-800">
                {{ $categories->links() }}
            </div>
        @else
            <div class="px-6 py-16 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-3xl dark:bg-blue-950">
                    🛏️
                </div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">
                    Aucune catégorie pour le moment
                </h3>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    Créez votre première catégorie pour commencer à organiser vos chambres.
                </p>
                <a href="{{ route('categories-chambres.create') }}"
                   class="mt-5 inline-flex rounded-xl bg-[#2457A7] px-5 py-3 text-sm font-semibold text-white hover:bg-blue-800">
                    Créer une catégorie
                </a>
            </div>
        @endif
    </div>
</div>
@endsection