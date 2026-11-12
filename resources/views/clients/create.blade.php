
@extends('layouts.app')

@section('title', 'Ajouter un client')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">

    <div>
        <a href="{{ route('clients.index') }}"
           class="text-sm font-medium text-[#2457A7] hover:underline">
            ← Retour aux clients
        </a>

        <h1 class="mt-3 text-2xl font-bold text-slate-800 dark:text-white">
            Ajouter un client
        </h1>

        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            Enregistrez un particulier ou une entreprise.
        </p>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400">
            <p class="mb-2 font-semibold">Veuillez corriger les erreurs suivantes :</p>
            <ul class="list-inside list-disc space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('clients.store') }}" method="POST"
          class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">

        @csrf

        @include('clients._form')

        <div class="flex flex-col-reverse justify-end gap-3 border-t border-slate-200 pt-6 sm:flex-row dark:border-slate-700">

            <a href="{{ route('clients.index') }}"
               class="rounded-xl border border-slate-300 px-5 py-3 text-center text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700">
                Annuler
            </a>

            <button type="submit"
                    class="rounded-xl bg-[#2457A7] px-6 py-3 text-sm font-semibold text-white hover:bg-blue-800">
                Enregistrer le client
            </button>

        </div>
    </form>

</div>
@endsection
