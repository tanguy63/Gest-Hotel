@extends('layouts.auth')

@section('title', 'Mot de passe oublié')

@section('content')

<div>
    <h2 class="text-3xl font-bold text-[#172033]">
        Mot de passe oublié ?
    </h2>

    <p class="mt-3 mb-7 text-sm leading-6 text-slate-500">
        Indiquez votre adresse e-mail. Nous vous enverrons un lien
        pour réinitialiser votre mot de passe.
    </p>

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

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
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
                autocomplete="email"
                placeholder="exemple@hotel.com"
                class="w-full rounded-lg border border-slate-200 bg-white px-4 py-3 outline-none focus:border-[#2457A7] focus:ring-2 focus:ring-[#2457A7]/10"
            >
        </div>

        <button
            type="submit"
            class="w-full rounded-lg bg-[#2457A7] px-5 py-3.5 font-semibold text-white hover:bg-[#1D478A]"
        >
            Envoyer le lien de réinitialisation
        </button>
    </form>

    <p class="mt-7 text-center text-sm">
        <a href="{{ route('login') }}" class="font-semibold text-[#2457A7] hover:underline">
            Retour à la connexion
        </a>
    </p>
</div>

@endsection