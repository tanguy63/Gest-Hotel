@extends('layouts.auth')

@section('title', 'Créer mon hôtel')

@section('content')

<div>
    <div class="mb-7">
        <h2 class="text-3xl font-bold text-[#172033]">
            Créez votre espace
        </h2>

        <p class="mt-2 text-slate-500">
            Quelques informations pour commencer à gérer votre hôtel.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-700">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="hotel_name" class="mb-2 block text-sm font-medium">
                Nom de votre hôtel
            </label>

            <input
                id="hotel_name"
                type="text"
                name="hotel_name"
                value="{{ old('hotel_name') }}"
                required
                placeholder="Exemple : Hôtel La Paix"
                class="w-full rounded-lg border border-slate-200 bg-white px-4 py-3 outline-none focus:border-[#2457A7] focus:ring-2 focus:ring-[#2457A7]/10"
            >
        </div>

        <div>
            <label for="name" class="mb-2 block text-sm font-medium">
                Votre nom complet
            </label>

            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autocomplete="name"
                placeholder="Nom et prénom"
                class="w-full rounded-lg border border-slate-200 bg-white px-4 py-3 outline-none focus:border-[#2457A7] focus:ring-2 focus:ring-[#2457A7]/10"
            >
        </div>

        <div>
            <label for="email" class="mb-2 block text-sm font-medium">
                Adresse e-mail
            </label>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                placeholder="exemple@hotel.com"
                class="w-full rounded-lg border border-slate-200 bg-white px-4 py-3 outline-none focus:border-[#2457A7] focus:ring-2 focus:ring-[#2457A7]/10"
            >
        </div>

        <div>
            <label for="phone" class="mb-2 block text-sm font-medium">
                Téléphone <span class="font-normal text-slate-400">(facultatif)</span>
            </label>

            <input
                id="phone"
                type="tel"
                name="phone"
                value="{{ old('phone') }}"
                placeholder="+229 01 00 00 00 00"
                class="w-full rounded-lg border border-slate-200 bg-white px-4 py-3 outline-none focus:border-[#2457A7] focus:ring-2 focus:ring-[#2457A7]/10"
            >
        </div>

        <div>
            <label for="password" class="mb-2 block text-sm font-medium">
                Mot de passe
            </label>

            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Minimum 8 caractères"
                class="w-full rounded-lg border border-slate-200 bg-white px-4 py-3 outline-none focus:border-[#2457A7] focus:ring-2 focus:ring-[#2457A7]/10"
            >
        </div>

        <div>
            <label for="password_confirmation" class="mb-2 block text-sm font-medium">
                Confirmer le mot de passe
            </label>

            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Répétez votre mot de passe"
                class="w-full rounded-lg border border-slate-200 bg-white px-4 py-3 outline-none focus:border-[#2457A7] focus:ring-2 focus:ring-[#2457A7]/10"
            >
        </div>

        <button
            type="submit"
            class="mt-2 w-full rounded-lg bg-[#2457A7] px-5 py-3.5 font-semibold text-white transition hover:bg-[#1D478A]"
        >
            Créer mon compte et mon hôtel
        </button>
    </form>

    <p class="mt-7 text-center text-sm text-slate-500">
        Vous avez déjà un compte ?

        <a href="{{ route('login') }}"
           class="font-semibold text-[#2457A7] hover:underline">
            Se connecter
        </a>
    </p>
</div>

@endsection