@extends('layouts.auth')

@section('title', 'Vérification de votre adresse e-mail')

@section('content')

<div class="text-center">

    <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-[#2457A7]">
        <svg xmlns="http://www.w3.org/2000/svg"
             width="32" height="32" viewBox="0 0 24 24"
             fill="none" stroke="currentColor"
             stroke-width="1.7" stroke-linecap="round"
             stroke-linejoin="round">
            <rect x="3" y="5" width="18" height="14" rx="2"/>
            <path d="m3 7 9 6 9-6"/>
        </svg>
    </div>

    <h2 class="text-2xl font-bold text-[#172033]">
        Vérifiez votre adresse e-mail
    </h2>

    <p class="mt-3 text-sm leading-6 text-slate-500">
        Saisissez le code à 6 chiffres envoyé à votre adresse e-mail.
    </p>

    @if(session('success'))
        <div class="mt-5 rounded-xl bg-green-50 p-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mt-5 rounded-xl bg-red-50 p-3 text-left text-sm text-red-600">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if($email)
        <div class="mt-5 rounded-xl bg-blue-50 p-4">
            <p class="text-sm font-semibold text-[#2457A7]">
                {{ $email }}
            </p>
        </div>

        <form action="{{ route('inscription.verifier') }}"
              method="POST"
              class="mt-6 text-left">

            @csrf

            <input type="hidden" name="email" value="{{ $email }}">

            <label for="code" class="mb-2 block text-sm font-semibold text-slate-700">
                Code de vérification
            </label>

            <input
                type="text"
                id="code"
                name="code"
                inputmode="numeric"
                autocomplete="one-time-code"
                maxlength="6"
                pattern="[0-9]{6}"
                required
                autofocus
                placeholder="000000"
                value="{{ old('code') }}"
                class="w-full rounded-xl border border-slate-300 px-4 py-4 text-center text-2xl font-bold tracking-[0.5em] text-[#2457A7] outline-none transition focus:border-[#2457A7] focus:ring-2 focus:ring-blue-100"
            >

            <button
                type="submit"
                class="mt-5 w-full rounded-xl bg-[#2457A7] px-6 py-4 font-semibold text-white transition hover:bg-blue-800"
            >
                Vérifier mon adresse e-mail
            </button>

        </form>

        <div class="mt-6 border-t border-slate-200 pt-5">

            <p class="text-sm text-slate-500">
                Vous n'avez pas reçu le code ?
            </p>

            <form action="{{ route('inscription.renvoyer-code') }}"
                  method="POST"
                  class="mt-3">

                @csrf

                <input type="hidden" name="email" value="{{ $email }}">

                <button
                    type="submit"
                    class="text-sm font-semibold text-[#2457A7] hover:underline"
                >
                    Renvoyer le code
                </button>

            </form>

        </div>
    @else
        <div class="mt-6 rounded-xl bg-amber-50 p-4 text-sm text-amber-700">
            Aucune adresse e-mail n'a été trouvée.
            Veuillez recommencer votre inscription.
        </div>

        <a href="{{ route('inscription') }}"
           class="mt-5 inline-block font-semibold text-[#2457A7] hover:underline">
            Recommencer l'inscription
        </a>
    @endif

</div>

@endsection