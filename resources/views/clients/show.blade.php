
@extends('layouts.app')

@section('title', 'Fiche client')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">

    {{-- En-tête --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

        <div>
            <a href="{{ route('clients.index') }}"
               class="text-sm font-medium text-[#2457A7] hover:underline">
                ← Retour aux clients
            </a>

            <h1 class="mt-3 text-2xl font-bold text-slate-800 dark:text-white">
                Fiche client
            </h1>

            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Informations détaillées du client.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">

            <a href="{{ route('clients.edit', $client) }}"
               class="rounded-xl bg-[#2457A7] px-5 py-3 text-sm font-semibold text-white hover:bg-blue-800">
                Modifier
            </a>

            <form action="{{ route('clients.destroy', $client) }}"
                  method="POST"
                  onsubmit="return confirm('Voulez-vous vraiment supprimer ce client ?')">

                @csrf
                @method('DELETE')

                <button type="submit"
                        class="rounded-xl border border-red-200 px-5 py-3 text-sm font-semibold text-red-600 hover:bg-red-50 dark:border-red-800 dark:hover:bg-red-900/20">
                    Supprimer
                </button>
            </form>

        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    {{-- Identité --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">

            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-100 text-2xl font-bold text-[#2457A7] dark:bg-blue-900/30 dark:text-blue-300">
                {{ strtoupper(substr($client->nom_affichage ?: 'C', 0, 1)) }}
            </div>

            <div>
                <h2 class="text-xl font-bold text-slate-800 dark:text-white">
                    {{ $client->nom_affichage ?: 'Client sans nom' }}
                </h2>

                <div class="mt-2">
                    @if($client->type === 'entreprise')
                        <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-700 dark:bg-purple-900/30 dark:text-purple-300">
                            Entreprise
                        </span>
                    @else
                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                            Particulier
                        </span>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- Coordonnées --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">

        <h3 class="mb-6 font-semibold text-slate-800 dark:text-white">
            Informations et coordonnées
        </h3>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

            @if($client->type === 'particulier')
                <div>
                    <p class="text-xs font-medium uppercase text-slate-400">Prénom(s)</p>
                    <p class="mt-2 text-sm text-slate-800 dark:text-white">
                        {{ $client->prenoms ?? 'Non renseigné' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase text-slate-400">Nom</p>
                    <p class="mt-2 text-sm text-slate-800 dark:text-white">
                        {{ $client->nom ?? 'Non renseigné' }}
                    </p>
                </div>
            @else
                <div class="sm:col-span-2">
                    <p class="text-xs font-medium uppercase text-slate-400">Raison sociale</p>
                    <p class="mt-2 text-sm text-slate-800 dark:text-white">
                        {{ $client->raison_sociale ?? 'Non renseignée' }}
                    </p>
                </div>
            @endif

            <div>
                <p class="text-xs font-medium uppercase text-slate-400">Téléphone</p>
                <p class="mt-2 text-sm text-slate-800 dark:text-white">
                    {{ $client->telephone ?? 'Non renseigné' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase text-slate-400">E-mail</p>
                <p class="mt-2 text-sm text-slate-800 dark:text-white">
                    {{ $client->email ?? 'Non renseigné' }}
                </p>
            </div>

            <div class="sm:col-span-2">
                <p class="text-xs font-medium uppercase text-slate-400">Adresse</p>
                <p class="mt-2 text-sm text-slate-800 dark:text-white">
                    {{ $client->adresse ?? 'Non renseignée' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase text-slate-400">Ville</p>
                <p class="mt-2 text-sm text-slate-800 dark:text-white">
                    {{ $client->ville ?? 'Non renseignée' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase text-slate-400">Pays</p>
                <p class="mt-2 text-sm text-slate-800 dark:text-white">
                    {{ $client->pays ?? 'Non renseigné' }}
                </p>
            </div>

        </div>
    </div>

    {{-- Notes --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">

        <h3 class="mb-4 font-semibold text-slate-800 dark:text-white">
            Notes complémentaires
        </h3>

        <p class="whitespace-pre-line text-sm text-slate-600 dark:text-slate-300">
            {{ $client->notes ?: 'Aucune note enregistrée.' }}
        </p>

    </div>

    {{-- Dates --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">

        <h3 class="mb-4 font-semibold text-slate-800 dark:text-white">
            Historique
        </h3>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

            <div>
                <p class="text-xs font-medium uppercase text-slate-400">Date de création</p>
                <p class="mt-2 text-sm text-slate-800 dark:text-white">
                    {{ $client->created_at?->format('d/m/Y à H:i') ?? '—' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase text-slate-400">Dernière modification</p>
                <p class="mt-2 text-sm text-slate-800 dark:text-white">
                    {{ $client->updated_at?->format('d/m/Y à H:i') ?? '—' }}
                </p>
            </div>

        </div>
    </div>

</div>
@endsection
