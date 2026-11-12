@extends('layouts.auth')

@section('title', 'Réinitialiser le mot de passe')

@section('content')

<div>
    <h2 class="text-3xl font-bold text-[#172033]">
        Nouveau mot de passe
    </h2>

    <p class="mt-2 mb-7 text-slate-500">
        Choisissez un nouveau mot de passe sécurisé.
    </p>

    @if ($errors->any())
        <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-700">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="mb-2 block text-sm font-medium">
                Adresse e-mail
            </label>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email', $request->email) }}"
                required
                autocomplete="email"
                class="w-full rounded-lg border border-slate-200 bg-white px-4 py-3 outline-none focus:border-[#2457A7] focus:ring-2 focus:ring-[#2457A7]/10"
            >
        </div>

        <div>
            <label for="password" class="mb-2 block text-sm font-medium">
                Nouveau mot de passe
            </label>

            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                class="w-full rounded-lg border border-slate-200 bg-white px-4 py-3 outline-none focus:border-[#2457A7] focus:ring-2 focus:ring-[#2457A7]/10"
            >
        </div>

        <div>
            <label for="password_confirmation" class="mb-2 block text-sm font-medium">
                Confirmer le nouveau mot de passe
            </label>

            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                class="w-full rounded-lg border border-slate-200 bg-white px-4 py-3 outline-none focus:border-[#2457A7] focus:ring-2 focus:ring-[#2457A7]/10"
            >
        </div>

        <button
            type="submit"
            class="w-full rounded-lg bg-[#2457A7] px-5 py-3.5 font-semibold text-white hover:bg-[#1D478A]"
        >
            Réinitialiser mon mot de passe
        </button>
    </form>
</div>

@endsection