@php
    $categorie = $categorie ?? null;
@endphp

<div class="space-y-6">

    <div>
        <label for="nom" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">
            Nom de la catégorie <span class="text-red-500">*</span>
        </label>
        <input
            type="text"
            name="nom"
            id="nom"
            value="{{ old('nom', $categorie?->nom) }}"
            required
            maxlength="255"
            placeholder="Ex. Chambre standard, Suite, Chambre VIP..."
            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-[#2457A7] focus:ring-2 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
        >
        @error('nom')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
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
            placeholder="Décrivez les caractéristiques de cette catégorie..."
            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-[#2457A7] focus:ring-2 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
        >{{ old('description', $categorie?->description) }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label for="capacite" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">
                Capacité maximale <span class="text-red-500">*</span>
            </label>
            <input
                type="number"
                name="capacite"
                id="capacite"
                min="1"
                max="50"
                value="{{ old('capacite', $categorie?->capacite ?? 2) }}"
                required
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-[#2457A7] focus:ring-2 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
            >
            @error('capacite')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="tarif_base" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">
                Tarif de base par nuit (FCFA) <span class="text-red-500">*</span>
            </label>
            <input
                type="number"
                name="tarif_base"
                id="tarif_base"
                min="0"
                step="0.01"
                value="{{ old('tarif_base', $categorie?->tarif_base ?? 0) }}"
                required
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-[#2457A7] focus:ring-2 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
            >
            @error('tarif_base')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 p-4 dark:border-slate-700">
        <input
            type="checkbox"
            name="est_active"
            value="1"
            @checked(old('est_active', $categorie?->est_active ?? true))
            class="h-4 w-4 rounded border-slate-300 text-[#2457A7] focus:ring-[#2457A7]"
        >
        <span>
            <span class="block text-sm font-semibold text-slate-700 dark:text-slate-200">
                Catégorie active
            </span>
            <span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">
                Une catégorie inactive ne pourra pas être choisie lors de la création d'une chambre.
            </span>
        </span>
    </label>

    <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end dark:border-slate-700">
        <a href="{{ route('categories-chambres.index') }}"
           class="rounded-xl border border-slate-300 px-5 py-3 text-center text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800">
            Annuler
        </a>

        <button type="submit"
                class="rounded-xl bg-[#2457A7] px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">
            {{ $categorie ? 'Enregistrer les modifications' : 'Créer la catégorie' }}
        </button>
    </div>
</div>