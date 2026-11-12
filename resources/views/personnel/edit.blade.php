
@extends('layouts.app')

@section('title', 'Modifier un membre du personnel')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">

    <div class="flex items-center gap-4">
        <a href="{{ route('personnel.index') }}"
           class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50">
            ←
        </a>

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Modifier le personnel
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Modifiez les informations et les droits du collaborateur.
            </p>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-inside list-disc space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('personnel.update', $personnel->id) }}"
          method="POST"
          class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        @csrf
        @method('PUT')

        <div class="border-b border-slate-100 px-6 py-5">
            <h2 class="font-semibold text-slate-800">
                Informations du collaborateur
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Compte : {{ $personnel->email }}
            </p>
        </div>

        <div class="space-y-6 p-6">

            <div>
                <label for="nom_complet" class="mb-2 block text-sm font-medium text-slate-700">
                    Nom complet *
                </label>

                <input type="text"
                       name="nom_complet"
                       id="nom_complet"
                       value="{{ old('nom_complet', $personnel->nom_complet) }}"
                       required
                       class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                @error('nom_complet')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-slate-700">
                    Adresse e-mail *
                </label>

                <input type="email"
                       name="email"
                       id="email"
                       value="{{ old('email', $personnel->email) }}"
                       required
                       class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="role" class="mb-2 block text-sm font-medium text-slate-700">
                    Fonction dans l'établissement *
                </label>

                <select name="role"
                        id="role"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                    @foreach($roles as $valeur => $libelle)
                        <option value="{{ $valeur }}"
                            {{ old('role', $personnel->pivot->role) === $valeur ? 'selected' : '' }}>
                            {{ $libelle }}
                        </option>
                    @endforeach
                </select>

                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
                <p class="text-sm font-semibold text-amber-800">
                    Modification du mot de passe
                </p>
                <p class="mt-1 text-sm text-amber-700">
                    Laissez ces champs vides si vous ne souhaitez pas modifier le mot de passe actuel.
                </p>
            </div>

            <div class="grid gap-6 md:grid-cols-2">

                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-slate-700">
                        Nouveau mot de passe
                    </label>

                    <input type="password"
                           name="password"
                           id="password"
                           minlength="8"
                           autocomplete="new-password"
                           placeholder="Minimum 8 caractères"
                           class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-700">
                        Confirmer le nouveau mot de passe
                    </label>

                    <input type="password"
                           name="password_confirmation"
                           id="password_confirmation"
                           minlength="8"
                           autocomplete="new-password"
                           placeholder="Répéter le mot de passe"
                           class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>

            </div>

        </div>

        <div class="flex flex-col-reverse justify-end gap-3 border-t border-slate-100 bg-slate-50 px-6 py-5 sm:flex-row">

            <a href="{{ route('personnel.index') }}"
               class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-center text-sm font-semibold text-slate-700 hover:bg-slate-100">
                Annuler
            </a>

            <button type="submit"
                    class="rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                Enregistrer les modifications
            </button>

        </div>
    </form>
</div>
@endsection
