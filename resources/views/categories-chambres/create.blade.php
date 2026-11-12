@extends('layouts.app')

@section('title', 'Nouvelle catégorie')

@section('content')
<div class="mx-auto max-w-3xl space-y-6 p-4 sm:p-6 lg:p-8">

    <div>
        <a href="{{ route('categories-chambres.index') }}"
           class="text-sm font-medium text-[#2457A7] hover:underline">
            ← Retour aux catégories
        </a>

        <h1 class="mt-4 text-2xl font-bold text-slate-800 dark:text-white">
            Nouvelle catégorie
        </h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            Renseignez les informations de la nouvelle catégorie de chambres.
        </p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900 sm:p-8">
        <form action="{{ route('categories-chambres.store') }}" method="POST">
            @csrf

            @include('categories-chambres._form')
        </form>
    </div>
</div>
@endsection