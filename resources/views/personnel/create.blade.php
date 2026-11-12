
@extends('layouts.app')

@section('title', 'Ajouter un membre du personnel')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">

    {{-- En-tête --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('personnel.index') }}"
           class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50">
            ←
        </a>

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Ajouter un membre
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Créez un compte et attribuez un rôle à votre collaborateur.
            </p>
        </div>
    </div>

    {{-- Erreurs --}}
    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="mb-2 font-semibold">Veuillez corriger les erreurs suivantes :</p>

            <ul class="list-inside list-disc space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulaire --}}
    <form action="{{ route('personnel.store') }}"
          method="POST"
          class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        @csrf

        <div class="border-b border-slate-100 px-6 py-5">
            <h2 class="font-semibold text-slate-800">
                Informations du collaborateur
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Renseignez les informations nécessaires à la création du compte.
            </p>
        </div>

        <div class="space-y-6 p-6">

            {{-- Nom --}}
            <div>
                <label for="nom_complet"
                       class="mb-2 block text-sm font-medium text-slate-700">
                    Nom complet <span class="text-red-500">*</span>
                </label>

                <input type="text"
                       name="nom_complet"
                       id="nom_complet"
                       value="{{ old('nom_complet') }}"
                       required
                       placeholder="Ex. : Jean Dupont"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                @error('nom_complet')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email"
                       class="mb-2 block text-sm font-medium text-slate-700">
                    Adresse e-mail <span class="text-red-500">*</span>
                </label>

                <input type="email"
                       name="email"
                       id="email"
                       value="{{ old('email') }}"
                       required
                       placeholder="exemple@email.com"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <p class="mt-1 text-xs text-slate-500">
                    Cette adresse sera utilisée pour se connecter à la plateforme.
                </p>
            </div>

            {{-- Rôle --}}
            <div>
                <label for="role"
                       class="mb-2 block text-sm font-medium text-slate-700">
                    Fonction dans l'établissement <span class="text-red-500">*</span>
                </label>

                <select name="role"
                        id="role"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                    <option value="">Sélectionner une fonction</option>

                    @foreach($roles as $valeur => $libelle)
                        <option value="{{ $valeur }}"
                            {{ old('role') === $valeur ? 'selected' : '' }}>
                            {{ $libelle }}
                        </option>
                    @endforeach
                </select>

                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-6 md:grid-cols-2">

                {{-- Mot de passe --}}
                <div>
                    <label for="password"
                           class="mb-2 block text-sm font-medium text-slate-700">
                        Mot de passe <span class="text-red-500">*</span>
                    </label>

                    <input type="password"
                           name="password"
                           id="password"
                           required
                           minlength="8"
                           autocomplete="new-password"
                           placeholder="Minimum 8 caractères"
                           class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirmation --}}
                <div>
                    <label for="password_confirmation"
                           class="mb-2 block text-sm font-medium text-slate-700">
                        Confirmer le mot de passe <span class="text-red-500">*</span>
                    </label>

                    <input type="password"
                           name="password_confirmation"
                           id="password_confirmation"
                           required
                           minlength="8"
                           autocomplete="new-password"
                           placeholder="Répéter le mot de passe"
                           class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>

            </div>

            {{-- Information --}}
            <div class="rounded-xl border border-blue-100 bg-blue-50 p-4">
                <div class="flex gap-3">
                    <span class="text-xl">ℹ️</span>
                    <div>
                        <p class="text-sm font-semibold text-blue-800">
                            Accès au compte
                        </p>
                        <p class="mt-1 text-sm leading-6 text-blue-700">
                            Le collaborateur pourra utiliser son adresse e-mail
                            et le mot de passe défini pour accéder à la plateforme.
                            Ses droits dépendront de la fonction attribuée.
                        </p>
                    </div>
                </div>
            </div>

        </div>

        {{-- Actions --}}
        <div class="flex flex-col-reverse justify-end gap-3 border-t border-slate-100 bg-slate-50 px-6 py-5 sm:flex-row">

            <a href="{{ route('personnel.index') }}"
               class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                Annuler
            </a>

            <button type="submit"
                    class="rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                Créer le compte
            </button>

        </div>
    </form>
</div>
@endsection
