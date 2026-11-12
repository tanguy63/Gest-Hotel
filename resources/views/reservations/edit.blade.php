
@extends('layouts.app')

@section('title', 'Modifier la réservation')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">

    <div>
        <a href="{{ route('reservations.show', $reservation) }}"
           class="text-sm font-medium text-[#2457A7] hover:underline">
            ← Retour aux détails
        </a>

        <h1 class="mt-3 text-2xl font-bold text-gray-900 dark:text-white">
            Modifier la réservation
        </h1>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Référence : {{ $reservation->reference }}
        </p>
    </div>

    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-900 dark:bg-red-900/20">
            <p class="font-semibold text-red-700 dark:text-red-300">
                Veuillez corriger les erreurs suivantes :
            </p>

            <ul class="mt-2 list-inside list-disc text-sm text-red-600 dark:text-red-400">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('reservations.update', $reservation) }}"
          method="POST"
          class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

        @csrf
        @method('PUT')

        @include('reservations._form')

    </form>

</div>
@endsection
