@extends('layouts.auth')

@section('title', 'Connexion')

@section('content')

<div>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-[#172033]">
            Bon retour !
        </h2>

        <p class="mt-2 text-slate-500">
            Connectez-vous pour accéder à votre espace hôtelier.
        </p>
    </div>

    @if (session('status'))
        <div class="mb-5 rounded-lg bg-green-50 p-4 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-700">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

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
                autofocus
                autocomplete="username"
                placeholder="exemple@hotel.com"
                class="w-full rounded-lg border border-slate-200 bg-white px-4 py-3 outline-none transition focus:border-[#2457A7] focus:ring-2 focus:ring-[#2457A7]/10"
            >
        </div>

        <div>
            <div class="mb-2 flex items-center justify-between">
                <label for="password" class="text-sm font-medium">
                    Mot de passe
                </label>

                <a href="{{ route('password.request') }}"
                   class="text-sm font-medium text-[#2457A7] hover:underline">
                    Mot de passe oublié ?
                </a>
            </div>

            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Votre mot de passe"
                class="w-full rounded-lg border border-slate-200 bg-white px-4 py-3 outline-none transition focus:border-[#2457A7] focus:ring-2 focus:ring-[#2457A7]/10"
            >
        </div>

        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input
                type="checkbox"
                name="remember"
                class="h-4 w-4 rounded border-slate-300 text-[#2457A7]"
            >
            Se souvenir de moi
        </label>

        <button
            type="submit"
            class="w-full rounded-lg bg-[#2457A7] px-5 py-3.5 font-semibold text-white transition hover:bg-[#1D478A]"
        >
            Se connecter
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-slate-500">
        Vous n'avez pas encore de compte ?

        <a href="{{ route('inscription') }}"
           class="font-semibold text-[#2457A7] hover:underline">
            Créer mon hôtel
        </a>
    </p>
</div>

@endsection