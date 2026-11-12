
@php
    $client = $client ?? null;
    $type = old('type', $client?->type ?? 'particulier');
@endphp

<div class="space-y-6">

    {{-- Type de client --}}
    <div>
        <label for="type" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
            Type de client *
        </label>

        <select name="type" id="type" required
                onchange="changerTypeClient(this.value)"
                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-[#2457A7] dark:border-slate-600 dark:bg-slate-900 dark:text-white">
            <option value="particulier" @selected($type === 'particulier')>Particulier</option>
            <option value="entreprise" @selected($type === 'entreprise')>Entreprise</option>
        </select>

        @error('type')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Informations particulier --}}
    <div id="champs-particulier"
         class="{{ $type === 'entreprise' ? 'hidden' : '' }} grid grid-cols-1 gap-5 md:grid-cols-2">

        <div>
            <label for="prenoms" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                Prénom(s) *
            </label>

            <input type="text" name="prenoms" id="prenoms"
                   value="{{ old('prenoms', $client?->prenoms) }}"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-[#2457A7] dark:border-slate-600 dark:bg-slate-900 dark:text-white">

            @error('prenoms')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="nom" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                Nom *
            </label>

            <input type="text" name="nom" id="nom"
                   value="{{ old('nom', $client?->nom) }}"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-[#2457A7] dark:border-slate-600 dark:bg-slate-900 dark:text-white">

            @error('nom')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Informations entreprise --}}
    <div id="champs-entreprise"
         class="{{ $type === 'entreprise' ? '' : 'hidden' }}">

        <label for="raison_sociale" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
            Raison sociale *
        </label>

        <input type="text" name="raison_sociale" id="raison_sociale"
               value="{{ old('raison_sociale', $client?->raison_sociale) }}"
               class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-[#2457A7] dark:border-slate-600 dark:bg-slate-900 dark:text-white">

        @error('raison_sociale')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Coordonnées --}}
    <div class="border-t border-slate-200 pt-6 dark:border-slate-700">
        <h3 class="mb-5 font-semibold text-slate-800 dark:text-white">
            Coordonnées
        </h3>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            <div>
                <label for="telephone" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Téléphone
                </label>

                <input type="text" name="telephone" id="telephone"
                       value="{{ old('telephone', $client?->telephone) }}"
                       placeholder="+229..."
                       class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-[#2457A7] dark:border-slate-600 dark:bg-slate-900 dark:text-white">

                @error('telephone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Adresse e-mail
                </label>

                <input type="email" name="email" id="email"
                       value="{{ old('email', $client?->email) }}"
                       placeholder="client@exemple.com"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-[#2457A7] dark:border-slate-600 dark:bg-slate-900 dark:text-white">

                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="adresse" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Adresse
                </label>

                <textarea name="adresse" id="adresse" rows="2"
                          class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-[#2457A7] dark:border-slate-600 dark:bg-slate-900 dark:text-white">{{ old('adresse', $client?->adresse) }}</textarea>

                @error('adresse')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="ville" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Ville
                </label>

                <input type="text" name="ville" id="ville"
                       value="{{ old('ville', $client?->ville) }}"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-[#2457A7] dark:border-slate-600 dark:bg-slate-900 dark:text-white">

                @error('ville')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="pays" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Pays
                </label>

                <input type="text" name="pays" id="pays"
                       value="{{ old('pays', $client?->pays ?? 'Bénin') }}"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-[#2457A7] dark:border-slate-600 dark:bg-slate-900 dark:text-white">

                @error('pays')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Notes --}}
    <div class="border-t border-slate-200 pt-6 dark:border-slate-700">
        <label for="notes" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
            Notes complémentaires
        </label>

        <textarea name="notes" id="notes" rows="4"
                  placeholder="Informations supplémentaires sur le client..."
                  class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-[#2457A7] dark:border-slate-600 dark:bg-slate-900 dark:text-white">{{ old('notes', $client?->notes) }}</textarea>

        @error('notes')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

</div>

<script>
    function changerTypeClient(type) {
        const particulier = document.getElementById('champs-particulier');
        const entreprise = document.getElementById('champs-entreprise');

        particulier.classList.toggle('hidden', type !== 'particulier');
        entreprise.classList.toggle('hidden', type !== 'entreprise');

        document.getElementById('nom').required = type === 'particulier';
        document.getElementById('prenoms').required = type === 'particulier';
        document.getElementById('raison_sociale').required = type === 'entreprise';
    }

    document.addEventListener('DOMContentLoaded', function () {
        changerTypeClient(document.getElementById('type').value);
    });
</script>
