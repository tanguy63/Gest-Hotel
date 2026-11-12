
@extends('layouts.auth')

@section('title', 'Créer mon espace')

@section('content')

<div>
    {{-- En-tête --}}
    <div class="mb-7">
        <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-[#2457A7]">
            <span class="h-2 w-2 rounded-full bg-[#2457A7]"></span>
            Essai gratuit de 7 jours
        </div>

        <h2 class="text-3xl font-bold tracking-tight text-[#172033]">
            Créez votre espace
        </h2>

        <p class="mt-2 text-sm leading-6 text-slate-500">
            Quelques informations pour commencer à gérer votre établissement.
        </p>
    </div>

    {{-- Messages d'erreur --}}
    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="mb-2 font-semibold">
                Vérifiez les informations suivantes :
            </p>

            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulaire --}}
    <form
        method="POST"
        action="{{ route('inscription.store') }}"
        class="space-y-5"
    >
        @csrf

        {{-- INFORMATIONS DE L'ÉTABLISSEMENT --}}
        <div class="border-b border-slate-100 pb-3">
            <h3 class="text-sm font-bold text-[#2457A7]">
                01. Votre établissement
            </h3>
            <p class="mt-1 text-xs text-slate-400">
                Présentez-nous votre activité.
            </p>
        </div>

        {{-- Type d'établissement --}}
        <div>
            <label for="type_etablissement_id" class="mb-2 block text-sm font-semibold text-slate-700">
                Type d'établissement <span class="text-red-500">*</span>
            </label>

            <select
                id="type_etablissement_id"
                name="type_etablissement_id"
                required
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#2457A7] focus:ring-4 focus:ring-[#2457A7]/10"
            >
                <option value="">Sélectionnez un type</option>

                @foreach ($typesEtablissements as $type)
                    <option
                        value="{{ $type->id }}"
                        @selected(old('type_etablissement_id') == $type->id)
                    >
                        {{ $type->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Nom établissement --}}
        <div>
            <label for="nom_etablissement" class="mb-2 block text-sm font-semibold text-slate-700">
                Nom de l'établissement <span class="text-red-500">*</span>
            </label>

            <input
                id="nom_etablissement"
                type="text"
                name="nom_etablissement"
                value="{{ old('nom_etablissement') }}"
                required
                maxlength="255"
                placeholder="Ex. : Hôtel La Paix"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#2457A7] focus:ring-4 focus:ring-[#2457A7]/10"
            >
        </div>

        {{-- Pays --}}
        <div>
            <label for="pays" class="mb-2 block text-sm font-semibold text-slate-700">
                Pays <span class="text-red-500">*</span>
            </label>

            <select
                id="pays"
                name="pays"
                required
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#2457A7] focus:ring-4 focus:ring-[#2457A7]/10"
            >
                <option value="Bénin" @selected(old('pays', 'Bénin') === 'Bénin')>
                    Bénin
                </option>
                <option value="Togo" @selected(old('pays') === 'Togo')>
                    Togo
                </option>
                <option value="Côte d'Ivoire" @selected(old('pays') === "Côte d'Ivoire")>
                    Côte d'Ivoire
                </option>
                <option value="Sénégal" @selected(old('pays') === 'Sénégal')>
                    Sénégal
                </option>
                <option value="Burkina Faso" @selected(old('pays') === 'Burkina Faso')>
                    Burkina Faso
                </option>
                <option value="Niger" @selected(old('pays') === 'Niger')>
                    Niger
                </option>
                <option value="Mali" @selected(old('pays') === 'Mali')>
                    Mali
                </option>
                <option value="Ghana" @selected(old('pays') === 'Ghana')>
                    Ghana
                </option>
                <option value="Nigeria" @selected(old('pays') === 'Nigeria')>
                    Nigeria
                </option>
                <option value="Autre" @selected(old('pays') === 'Autre')>
                    Autre pays
                </option>
            </select>
        </div>

        {{-- INFORMATIONS DU GESTIONNAIRE --}}
        <div class="border-b border-slate-100 pb-3 pt-3">
            <h3 class="text-sm font-bold text-[#2457A7]">
                02. Vos informations
            </h3>
            <p class="mt-1 text-xs text-slate-400">
                Informations du responsable de l'établissement.
            </p>
        </div>

        {{-- Nom complet --}}
        <div>
            <label for="nom_complet" class="mb-2 block text-sm font-semibold text-slate-700">
                Votre nom complet <span class="text-red-500">*</span>
            </label>

            <input
                id="nom_complet"
                type="text"
                name="nom_complet"
                value="{{ old('nom_complet') }}"
                required
                autocomplete="name"
                placeholder="Nom et prénom"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#2457A7] focus:ring-4 focus:ring-[#2457A7]/10"
            >
        </div>

        {{-- Téléphone --}}
        <div>
            <label for="telephone" class="mb-2 block text-sm font-semibold text-slate-700">
                Téléphone
                <span class="font-normal text-slate-400">(facultatif)</span>
            </label>

            <input
                id="telephone"
                type="tel"
                name="telephone"
                value="{{ old('telephone') }}"
                placeholder="+229 01 00 00 00 00"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#2457A7] focus:ring-4 focus:ring-[#2457A7]/10"
            >
        </div>

        {{-- COMPTE --}}
        <div class="border-b border-slate-100 pb-3 pt-3">
            <h3 class="text-sm font-bold text-[#2457A7]">
                03. Sécurisez votre compte
            </h3>
            <p class="mt-1 text-xs text-slate-400">
                Votre adresse e-mail sera vérifiée.
            </p>
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                Adresse e-mail <span class="text-red-500">*</span>
            </label>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                placeholder="exemple@email.com"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#2457A7] focus:ring-4 focus:ring-[#2457A7]/10"
            >
        </div>

        {{-- Mot de passe --}}
        <div>
            <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">
                Mot de passe <span class="text-red-500">*</span>
            </label>

            <input
                id="password"
                type="password"
                name="password"
                required
                minlength="8"
                autocomplete="new-password"
                placeholder="Minimum 8 caractères"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#2457A7] focus:ring-4 focus:ring-[#2457A7]/10"
            >
        </div>

        {{-- Confirmation --}}
        <div>
            <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">
                Confirmer le mot de passe <span class="text-red-500">*</span>
            </label>

            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                minlength="8"
                autocomplete="new-password"
                placeholder="Répétez votre mot de passe"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#2457A7] focus:ring-4 focus:ring-[#2457A7]/10"
            >
        </div>

        {{-- Conditions --}}
        <div class="flex items-start gap-3 pt-2">
            <input
                id="conditions"
                type="checkbox"
                name="conditions"
                value="1"
                required
                class="mt-1 h-4 w-4 rounded border-slate-300 accent-[#2457A7]"
                @checked(old('conditions'))
            >

            <label for="conditions" class="text-xs leading-6 text-slate-500">
                J'accepte les conditions générales d'utilisation et la politique de confidentialité de GEST-HOSTEL.
                <span class="text-red-500">*</span>
            </label>
        </div>

        {{-- Bouton --}}
        <button
            type="submit"
            class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#2457A7] px-5 py-4 text-sm font-bold text-white shadow-lg shadow-blue-900/10 transition hover:bg-[#1D478A] focus:ring-4 focus:ring-blue-200"
        >
            Créer mon espace gratuitement
            <span aria-hidden="true">→</span>
        </button>

        <p class="text-center text-xs leading-5 text-slate-400">
            Aucun paiement ne sera demandé pendant votre période d'essai.
        </p>
    </form>

    {{-- Connexion --}}
    <p class="mt-8 text-center text-sm text-slate-500">
        Vous avez déjà un compte ?

        <a
            href="{{ route('login') }}"
            class="font-bold text-[#2457A7] hover:underline"
        >
            Se connecter
        </a>
    </p>
</div>

@endsection
