
@extends('layouts.app')

@section('title', 'Gestion des clients')

@section('content')
<div class="space-y-6">

    {{-- En-tête --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">
                Gestion des clients
            </h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Consultez et gérez les clients de votre établissement.
            </p>
        </div>

        <a href="{{ route('clients.create') }}"
           class="inline-flex items-center justify-center rounded-xl bg-[#2457A7] px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-800">
            + Ajouter un client
        </a>
    </div>

    {{-- Messages --}}
    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    {{-- Recherche et filtres --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <form action="{{ route('clients.index') }}" method="GET"
              class="grid grid-cols-1 gap-4 md:grid-cols-3">

            <div class="md:col-span-2">
                <label for="recherche" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Rechercher un client
                </label>

                <input
                    type="text"
                    name="recherche"
                    id="recherche"
                    value="{{ request('recherche') }}"
                    placeholder="Nom, prénom, téléphone ou e-mail..."
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-[#2457A7] focus:ring-2 focus:ring-blue-100 dark:border-slate-600 dark:bg-slate-900 dark:text-white"
                >
            </div>

            <div>
                <label for="type" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Type de client
                </label>

                <select
                    name="type"
                    id="type"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-[#2457A7] dark:border-slate-600 dark:bg-slate-900 dark:text-white"
                >
                    <option value="">Tous les clients</option>
                    <option value="particulier" @selected(request('type') === 'particulier')>
                        Particuliers
                    </option>
                    <option value="entreprise" @selected(request('type') === 'entreprise')>
                        Entreprises
                    </option>
                </select>
            </div>

            <div class="flex flex-wrap gap-2 md:col-span-3">
                <button type="submit"
                        class="rounded-xl bg-[#2457A7] px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">
                    Rechercher
                </button>

                <a href="{{ route('clients.index') }}"
                   class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    {{-- Tableau des clients --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">

        <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-700">
            <h2 class="font-semibold text-slate-800 dark:text-white">
                Liste des clients
            </h2>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                {{ $clients->total() }} client(s) enregistré(s)
            </p>
        </div>

        @if($clients->count() > 0)

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-slate-900/50 dark:text-slate-400">
                        <tr>
                            <th class="px-5 py-4">Client</th>
                            <th class="px-5 py-4">Type</th>
                            <th class="px-5 py-4">Téléphone</th>
                            <th class="px-5 py-4">E-mail</th>
                            <th class="px-5 py-4 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">

                        @foreach($clients as $client)
                            <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-700/30">

                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="font-semibold text-slate-800 dark:text-white">
                                        {{ $client->nom_affichage ?: 'Client sans nom' }}
                                    </div>
                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ $client->ville ?? 'Ville non renseignée' }}
                                    </div>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    @if($client->type === 'entreprise')
                                        <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-700 dark:bg-purple-900/30 dark:text-purple-300">
                                            Entreprise
                                        </span>
                                    @else
                                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                            Particulier
                                        </span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-slate-600 dark:text-slate-300">
                                    {{ $client->telephone ?? '—' }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-slate-600 dark:text-slate-300">
                                    {{ $client->email ?? '—' }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex justify-end gap-2">

                                        <a href="{{ route('clients.show', $client) }}"
                                           class="rounded-lg bg-blue-50 px-3 py-2 text-xs font-medium text-blue-700 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-300">
                                            Voir
                                        </a>

                                        <a href="{{ route('clients.edit', $client) }}"
                                           class="rounded-lg bg-amber-50 px-3 py-2 text-xs font-medium text-amber-700 hover:bg-amber-100 dark:bg-amber-900/30 dark:text-amber-300">
                                            Modifier
                                        </a>

                                        <form action="{{ route('clients.destroy', $client) }}"
                                              method="POST"
                                              onsubmit="return confirm('Voulez-vous vraiment supprimer ce client ?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="rounded-lg bg-red-50 px-3 py-2 text-xs font-medium text-red-700 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-300">
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

            <div class="border-t border-slate-200 px-5 py-4 dark:border-slate-700">
                {{ $clients->links() }}
            </div>

        @else

            <div class="px-5 py-16 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-2xl dark:bg-slate-700">
                    👥
                </div>

                <h3 class="font-semibold text-slate-800 dark:text-white">
                    Aucun client trouvé
                </h3>

                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    Commencez par enregistrer votre premier client.
                </p>

                <a href="{{ route('clients.create') }}"
                   class="mt-5 inline-flex rounded-xl bg-[#2457A7] px-5 py-3 text-sm font-semibold text-white hover:bg-blue-800">
                    Ajouter un client
                </a>
            </div>

        @endif
    </div>

</div>
@endsection
