
@extends('layouts.app')

@section('title', 'Paramètres de l’établissement')

@section('content')

<div class="mx-auto max-w-5xl space-y-6">

    {{-- En-tête --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
            Paramètres de l'établissement
        </h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            Gérez les informations générales de votre établissement.
        </p>
    </div>

    {{-- Message de succès --}}
    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700 dark:border-green-900 dark:bg-green-950/30 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    {{-- Erreurs --}}
    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/30 dark:text-red-300">
            <p class="mb-2 font-semibold">Veuillez corriger les erreurs suivantes :</p>
            <ul class="list-inside list-disc space-y-1">
                @foreach($errors->all() as $erreur)
                    <li>{{ $erreur }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('parametres.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Informations générales --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">

            <div class="mb-6 border-b border-slate-100 pb-4 dark:border-slate-800">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">
                    Informations générales
                </h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Identité et coordonnées de votre établissement.
                </p>
            </div>

            <div class="grid gap-5 md:grid-cols-2">

                {{-- Nom --}}
                <div>
                    <label for="nom" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Nom de l'établissement
                    </label>
                    <input
                        id="nom"
                        type="text"
                        name="nom"
                        value="{{ old('nom', $etablissement->nom) }}"
                        @readonly(!$estProprietaire)
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white {{ !$estProprietaire ? 'cursor-not-allowed bg-slate-50 dark:bg-slate-800/50' : '' }}"
                    >
                    @error('nom')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Type --}}
                <div>
                    <label for="type_etablissement_id" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Type d'établissement
                    </label>
                    <select
                        id="type_etablissement_id"
                        name="type_etablissement_id"
                        @disabled(!$estProprietaire)
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white {{ !$estProprietaire ? 'cursor-not-allowed bg-slate-50 dark:bg-slate-800/50' : '' }}"
                    >
                        @foreach($typesEtablissements as $type)
                            <option
                                value="{{ $type->id }}"
                                @selected(old('type_etablissement_id', $etablissement->type_etablissement_id) == $type->id)
                            >
                                {{ $type->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Adresse e-mail
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', $etablissement->email) }}"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Téléphone --}}
                <div>
                    <label for="telephone" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Téléphone
                    </label>
                    <input
                        id="telephone"
                        type="text"
                        name="telephone"
                        value="{{ old('telephone', $etablissement->telephone) }}"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                    >
                    @error('telephone')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Adresse --}}
                <div class="md:col-span-2">
                    <label for="adresse" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Adresse
                    </label>
                    <input
                        id="adresse"
                        type="text"
                        name="adresse"
                        value="{{ old('adresse', $etablissement->adresse) }}"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                    >
                    @error('adresse')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ville --}}
                <div>
                    <label for="ville" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Ville
                    </label>
                    <input
                        id="ville"
                        type="text"
                        name="ville"
                        value="{{ old('ville', $etablissement->ville) }}"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                    >
                    @error('ville')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pays --}}
                <div>
                    <label for="pays" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Pays
                    </label>
                    <input
                        id="pays"
                        type="text"
                        name="pays"
                        value="{{ old('pays', $etablissement->pays) }}"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                    >
                    @error('pays')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Configuration régionale --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">

            <div class="mb-6 border-b border-slate-100 pb-4 dark:border-slate-800">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">
                    Configuration régionale
                </h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Devise et fuseau horaire utilisés dans l'application.
                </p>
            </div>

            <div class="grid gap-5 md:grid-cols-2">

                {{-- Devise --}}
                <div>
                    <label for="devise" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Devise
                    </label>
                    <select
                        id="devise"
                        name="devise"
                        @disabled(!$estProprietaire)
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                    >
                        @foreach(['XOF' => 'Franc CFA BCEAO (XOF)', 'XAF' => 'Franc CFA BEAC (XAF)', 'EUR' => 'Euro (EUR)', 'USD' => 'Dollar américain (USD)'] as $code => $libelle)
                            <option
                                value="{{ $code }}"
                                @selected(old('devise', $etablissement->devise) === $code)
                            >
                                {{ $libelle }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Fuseau horaire --}}
                <div>
                    <label for="fuseau_horaire" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Fuseau horaire
                    </label>
                    <select
                        id="fuseau_horaire"
                        name="fuseau_horaire"
                        @disabled(!$estProprietaire)
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                    >
                        @foreach(['Africa/Porto-Novo' => 'Bénin (UTC+1)', 'Africa/Lagos' => 'Nigeria (UTC+1)', 'Africa/Abidjan' => 'Côte d’Ivoire (UTC+0)', 'Africa/Douala' => 'Cameroun (UTC+1)', 'Europe/Paris' => 'France (heure variable)'] as $zone => $libelle)
                            <option
                                value="{{ $zone }}"
                                @selected(old('fuseau_horaire', $etablissement->fuseau_horaire) === $zone)
                            >
                                {{ $libelle }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

        {{-- Statut --}}
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-800/50">
            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                Statut de l'établissement
            </p>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Ce statut est géré par la plateforme et ne peut pas être modifié depuis cet écran.
            </p>
            <span class="mt-3 inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-950 dark:text-blue-300">
                {{ str_replace('_', ' ', ucfirst($etablissement->statut)) }}
            </span>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col-reverse justify-end gap-3 sm:flex-row">
            <a
                href="{{ route('dashboard') }}"
                class="rounded-xl border border-slate-200 px-6 py-3 text-center text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
            >
                Annuler
            </a>

            <button
                type="submit"
                class="rounded-xl bg-primary px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:opacity-90"
            >
                Enregistrer les paramètres
            </button>
        </div>

    </form>

</div>

@endsection
