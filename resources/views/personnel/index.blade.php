
@extends('layouts.app')

@section('title', 'Gestion du personnel')

@section('content')
@php
    $roleEtablissement = auth()->user()
        ->etablissements()
        ->wherePivot('est_actif', true)
        ->where('etablissements.id', session('etablissement_actif'))
        ->first()?->pivot?->role;

    $estProprietaire = $roleEtablissement === 'proprietaire';
    $estAdministrateur = $roleEtablissement === 'administrateur';
@endphp
<div class="mx-auto max-w-7xl space-y-6">

    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Gestion du personnel
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Gérez les accès et les rôles des membres de votre établissement.
            </p>
        </div>

        @if($estProprietaire || $estAdministrateur)
    <a href="{{ route('personnel.create') }}"
       class="inline-flex items-center justify-center rounded-xl bg-[#2457A7] px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-800">
        + Ajouter un membre
    </a>
@endif
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-inside list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="font-semibold text-slate-800">
                Membres du personnel
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                {{ $personnels->total() }} membre(s) enregistré(s)
            </p>
        </div>

        @if($personnels->isEmpty())
            <div class="px-6 py-14 text-center">
                <div class="mb-3 text-4xl">👥</div>
                <h3 class="font-semibold text-slate-800">
                    Aucun membre du personnel
                </h3>
                <p class="mt-2 text-sm text-slate-500">
                    Commencez par ajouter un membre à votre équipe.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-5 py-4 font-semibold">Nom</th>
                            <th class="px-5 py-4 font-semibold">Adresse e-mail</th>
                            <th class="px-5 py-4 font-semibold">Rôle</th>
                            <th class="px-5 py-4 font-semibold">Accès</th>
                            <th class="px-5 py-4 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach($personnels as $personnel)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-5 py-4 font-medium text-slate-800">
                                    {{ $personnel->nom_complet }}
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $personnel->email }}
                                </td>

                                <td class="px-5 py-4">
                                    @php
                                        $roles = [
                                            'administrateur' => 'Administrateur',
                                            'agent_exploitation' => 'Agent d’exploitation',
                                            'comptable' => 'Comptable',
                                            'entretien' => 'Personnel d’entretien',
                                        ];
                                    @endphp

                                    <span class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700">
                                        {{ $roles[$personnel->pivot->role] ?? $personnel->pivot->role }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    @if($personnel->pivot->est_actif)
                                        <span class="rounded-lg bg-green-50 px-3 py-1.5 text-xs font-medium text-green-700">
                                            Actif
                                        </span>
                                    @else
                                        <span class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700">
                                            Désactivé
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    @php
    $estAdministrateurCible = $personnel->pivot->role === 'administrateur';
    $estSonPropreCompte = $personnel->id === auth()->id();

    $peutModifier = $estProprietaire
        || ($estAdministrateur && !$estAdministrateurCible);

    $peutDesactiver = $peutModifier && !$estSonPropreCompte;
@endphp

<div class="flex items-center justify-end gap-3">

    {{-- Modification --}}
    @if($peutModifier)

        <a href="{{ route('personnel.edit', $personnel->id) }}"
           class="font-medium text-blue-600 hover:text-blue-800">
            Modifier
        </a>

    @endif

    {{-- Désactivation ou réactivation --}}
    @if($peutDesactiver)

        @if($personnel->pivot->est_actif)

            <form action="{{ route('personnel.destroy', $personnel->id) }}"
                  method="POST"
                  onsubmit="return confirm('Voulez-vous vraiment désactiver l’accès de ce membre ?')">

                @csrf
                @method('DELETE')

                <button type="submit"
                        class="font-medium text-red-600 hover:text-red-800">
                    Désactiver
                </button>
            </form>

        @else

            <form action="{{ route('personnel.reactiver', $personnel->id) }}"
                  method="POST"
                  onsubmit="return confirm('Voulez-vous réactiver l’accès de ce membre ?')">

                @csrf
                @method('PATCH')

                <button type="submit"
                        class="font-medium text-green-600 hover:text-green-800">
                    Réactiver
                </button>
            </form>

        @endif

    @endif

</div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-5 py-4">
                {{ $personnels->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
