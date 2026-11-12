@php
    $chambre = $chambre ?? null;
@endphp

<div class="space-y-6">

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label for="numero" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">
                Numéro de chambre <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                name="numero"
                id="numero"
                value="{{ old('numero', $chambre?->numero) }}"
                maxlength="30"
                required
                placeholder="Ex. 101, 102, VIP01..."
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none focus:border-[#2457A7] focus:ring-2 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
            >
            @error('numero')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="categorie_chambre_id" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">
                Catégorie <span class="text-red-500">*</span>
            </label>
            <select
                name="categorie_chambre_id"
                id="categorie_chambre_id"
                required
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none focus:border-[#2457A7] dark:border-slate-700 dark:bg-slate-800 dark:text-white"
            >
                <option value="">Choisir une catégorie</option>
                @foreach($categories as $categorie)
                    <option value="{{ $categorie->id }}"
                        @selected(old('categorie_chambre_id', $chambre?->categorie_chambre_id) == $categorie->id)>
                        {{ $categorie->nom }} — {{ $categorie->capacite }} personne(s)
                    </option>
                @endforeach
            </select>
            @error('categorie_chambre_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

            @if($categories->isEmpty())
                <p class="mt-2 text-xs text-amber-600">
                    Vous devez d'abord créer une catégorie active.
                    <a href="{{ route('categories-chambres.create') }}" class="font-semibold underline">
                        Créer une catégorie
                    </a>
                </p>
            @endif
        </div>

        <div>
            <label for="etage" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">
                Étage
            </label>
            <input
                type="text"
                name="etage"
                id="etage"
                value="{{ old('etage', $chambre?->etage) }}"
                maxlength="50"
                placeholder="Ex. Rez-de-chaussée, 1er étage..."
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none focus:border-[#2457A7] dark:border-slate-700 dark:bg-slate-800 dark:text-white"
            >
            @error('etage')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="capacite" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">
                Capacité <span class="text-red-500">*</span>
            </label>
            <input
                type="number"
                name="capacite"
                id="capacite"
                min="1"
                max="50"
                value="{{ old('capacite', $chambre?->capacite ?? 2) }}"
                required
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none focus:border-[#2457A7] dark:border-slate-700 dark:bg-slate-800 dark:text-white"
            >
            @error('capacite')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="tarif_nuit" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">
                Tarif par nuit (FCFA) <span class="text-red-500">*</span>
            </label>
            <input
                type="number"
                name="tarif_nuit"
                id="tarif_nuit"
                min="0"
                step="0.01"
                value="{{ old('tarif_nuit', $chambre?->tarif_nuit ?? 0) }}"
                required
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none focus:border-[#2457A7] dark:border-slate-700 dark:bg-slate-800 dark:text-white"
            >
            @error('tarif_nuit')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="statut" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">
                Statut <span class="text-red-500">*</span>
            </label>
            <select
                name="statut"
                id="statut"
                required
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none focus:border-[#2457A7] dark:border-slate-700 dark:bg-slate-800 dark:text-white"
            >
                <option value="disponible" @selected(old('statut', $chambre?->statut ?? 'disponible') === 'disponible')>Disponible</option>
                <option value="occupee" @selected(old('statut', $chambre?->statut) === 'occupee')>Occupée</option>
                <option value="maintenance" @selected(old('statut', $chambre?->statut) === 'maintenance')>En maintenance</option>
                <option value="hors_service" @selected(old('statut', $chambre?->statut) === 'hors_service')>Hors service</option>
            </select>
            @error('statut')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="description" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">
            Description
        </label>
        <textarea
            name="description"
            id="description"
            rows="4"
            maxlength="1000"
            placeholder="Informations complémentaires sur la chambre..."
            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none focus:border-[#2457A7] dark:border-slate-700 dark:bg-slate-800 dark:text-white"
        >{{ old('description', $chambre?->description) }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end dark:border-slate-700">
        <a href="{{ route('chambres.index') }}"
           class="rounded-xl border border-slate-300 px-5 py-3 text-center text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800">
            Annuler
        </a>

        <button type="submit"
                @disabled($categories->isEmpty())
                class="rounded-xl bg-[#2457A7] px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800 disabled:cursor-not-allowed disabled:opacity-50">
            {{ $chambre ? 'Enregistrer les modifications' : 'Créer la chambre' }}
        </button>
    </div>
</div>