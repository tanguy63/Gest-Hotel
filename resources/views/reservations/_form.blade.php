@php
    $reservation = $reservation ?? null;

    $arriveeImmediate = $arriveeImmediate ?? false;

    $dateArrivee = old(
        'date_arrivee',
        $arriveeImmediate && !$reservation
            ? now()->format('Y-m-d\TH:i')
            : ($reservation?->date_arrivee?->format('Y-m-d\TH:i') ?? '')
    );

    $dateDepart = old(
        'date_depart',
        $arriveeImmediate && !$reservation
            ? now()->addDay()->setTime(12, 0)->format('Y-m-d\TH:i')
            : ($reservation?->date_depart?->format('Y-m-d\TH:i') ?? '')
    );

    $clientSelectionne = old('client_id', $reservation?->client_id ?? '');

    $chambreSelectionnee = old('chambre_id', $reservation?->chambre_id ?? '');
@endphp

<div class="space-y-6">

    @if($arriveeImmediate && !isset($reservation))
        <input type="hidden" name="type_arrivee" value="direct">

        <div class="rounded-xl border border-green-200 bg-green-50 p-4 dark:border-green-900 dark:bg-green-900/20">
            <h3 class="font-semibold text-green-800 dark:text-green-300">
                Arrivée directe
            </h3>
            <p class="mt-1 text-sm text-green-700 dark:text-green-400">
                Le client arrive immédiatement. Son arrivée sera enregistrée automatiquement à l'heure actuelle et son séjour passera au statut « En cours ».
            </p>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

        <div>
            <label for="client_id" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Client <span class="text-red-500">*</span>
            </label>

            <select name="client_id" id="client_id" required
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">Sélectionner un client</option>

                @foreach($clients as $client)
                    <option value="{{ $client->id }}"
                        @selected($clientSelectionne == $client->id)>
                        {{ $client->nom_affichage }}
                        @if($client->telephone)
                            — {{ $client->telephone }}
                        @endif
                    </option>
                @endforeach
            </select>

            @error('client_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

            @if($clients->isEmpty())
                <p class="mt-1 text-sm text-red-600">
                    Aucun client enregistré.
                    <a href="{{ route('clients.create') }}" class="underline">
                        Ajouter un client
                    </a>
                </p>
            @endif
        </div>

        <div>
            <label for="chambre_id" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Chambre <span class="text-red-500">*</span>
            </label>

            <select name="chambre_id" id="chambre_id" required
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 dark:border-gray-600 dark:bg-gray-700 dark:text-white">

                <option value="">Sélectionner une chambre</option>

                @foreach($chambres as $chambre)
                    <option value="{{ $chambre->id }}"
                            data-tarif="{{ $chambre->tarif_nuit }}"
                            data-capacite="{{ $chambre->capacite }}"
                            @selected($chambreSelectionnee == $chambre->id)>
                        Chambre {{ $chambre->numero }}
                        — {{ $chambre->categorieChambre->nom ?? 'Sans catégorie' }}
                        — {{ number_format($chambre->tarif_nuit, 0, ',', ' ') }} FCFA/nuit
                    </option>
                @endforeach

            </select>

            @error('chambre_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="date_arrivee" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Date et heure d'arrivée <span class="text-red-500">*</span>
            </label>

            <input type="datetime-local"
                name="date_arrivee"
                id="date_arrivee"
                value="{{ $dateArrivee }}"
                required
                @readonly($arriveeImmediate && !isset($reservation))
                class="w-full rounded-lg border border-gray-300 px-4 py-3 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            @error('date_arrivee')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="date_depart" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Date et heure de départ <span class="text-red-500">*</span>
            </label>

            <input type="datetime-local"
                   name="date_depart"
                   id="date_depart"
                   value="{{ $dateDepart }}"
                   required
                   class="w-full rounded-lg border border-gray-300 px-4 py-3 dark:border-gray-600 dark:bg-gray-700 dark:text-white">

            @error('date_depart')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="nombre_personnes" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Nombre de personnes <span class="text-red-500">*</span>
            </label>

            <input type="number"
                   name="nombre_personnes"
                   id="nombre_personnes"
                   min="1"
                   max="100"
                   value="{{ old('nombre_personnes', $reservation?->nombre_personnes ?? 1) }}"
                   required
                   class="w-full rounded-lg border border-gray-300 px-4 py-3 dark:border-gray-600 dark:bg-gray-700 dark:text-white">

            @error('nombre_personnes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <p id="message-capacite" class="mt-1 text-sm text-gray-500"></p>
        </div>

        @if(!isset($reservation))
    {{-- Avance à l'enregistrement d'une nouvelle réservation --}}
    <div>
        <label for="montant_avance" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Avance versée (FCFA) <span class="text-red-500">*</span>
        </label>

        <input type="number"
               name="montant_avance"
               id="montant_avance"
               min="0"
               step="0.01"
               value="{{ old('montant_avance', 0) }}"
               required
               class="w-full rounded-lg border border-gray-300 px-4 py-3 dark:border-gray-600 dark:bg-gray-700 dark:text-white">

        @error('montant_avance')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Mode de paiement de l'avance --}}
    <div>
        <label for="mode_paiement" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Mode de paiement de l'avance
        </label>

        <select name="mode_paiement"
                id="mode_paiement"
                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 dark:border-gray-600 dark:bg-gray-700 dark:text-white">

            <option value="">Sélectionner un mode de paiement</option>

            <option value="especes" @selected(old('mode_paiement') === 'especes')>
                Espèces
            </option>

            <option value="mobile_money" @selected(old('mode_paiement') === 'mobile_money')>
                Mobile Money
            </option>

            <option value="carte_bancaire" @selected(old('mode_paiement') === 'carte_bancaire')>
                Carte bancaire
            </option>

            <option value="virement" @selected(old('mode_paiement') === 'virement')>
                Virement bancaire
            </option>

            <option value="cheque" @selected(old('mode_paiement') === 'cheque')>
                Chèque
            </option>
        </select>

        @error('mode_paiement')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
@else
    {{-- Avance déjà encaissée : non modifiable ici --}}
    <div>
        <label for="montant_avance" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Total déjà encaissé (FCFA)
        </label>

        <input type="number"
               id="montant_avance"
               name="montant_avance"
               value="{{ $reservation->montant_avance }}"
               readonly
               class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-3 text-gray-600 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">

        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
            Pour enregistrer un nouveau paiement, utilisez le module Paiements.
        </p>
    </div>
@endif

    </div>

    @if(isset($reservation))
        <div>
            <label for="statut" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Statut de la réservation <span class="text-red-500">*</span>
            </label>

            <select name="statut" id="statut" required
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 dark:border-gray-600 dark:bg-gray-700 dark:text-white">

                @foreach([
                    'en_attente' => 'En attente',
                    'confirmee' => 'Confirmée',
                    'en_cours' => 'En cours',
                    'terminee' => 'Terminée',
                    'annulee' => 'Annulée'
                ] as $valeur => $libelle)
                    <option value="{{ $valeur }}"
                        @selected(old('statut', $reservation->statut) === $valeur)>
                        {{ $libelle }}
                    </option>
                @endforeach

            </select>

            @error('statut')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="motif_annulation" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Motif d'annulation (facultatif)
            </label>

            <textarea name="motif_annulation"
                      id="motif_annulation"
                      rows="2"
                      class="w-full rounded-lg border border-gray-300 px-4 py-3 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('motif_annulation', $reservation->motif_annulation) }}</textarea>

            @error('motif_annulation')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    @endif

    <div>
        <label for="notes" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Notes complémentaires
        </label>

        <textarea name="notes"
                  id="notes"
                  rows="3"
                  placeholder="Informations supplémentaires sur la réservation..."
                  class="w-full rounded-lg border border-gray-300 px-4 py-3 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('notes', $reservation?->notes) }}</textarea>

        @error('notes')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="rounded-xl border border-blue-200 bg-blue-50 p-5 dark:border-blue-900 dark:bg-blue-900/20">

        <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">
            Récapitulatif financier
        </h3>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Nombre de nuits</p>
                <p id="recap-nuits" class="mt-1 text-xl font-bold text-gray-900 dark:text-white">0</p>
            </div>

            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Montant total</p>
                <p id="recap-total" class="mt-1 text-xl font-bold text-[#2457A7]">0 FCFA</p>
            </div>

            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Reste à payer</p>
                <p id="recap-reste" class="mt-1 text-xl font-bold text-orange-600">0 FCFA</p>
            </div>

        </div>

        <p id="message-avance" class="mt-3 text-sm text-red-600"></p>
    </div>

    <div class="flex flex-col-reverse justify-end gap-3 border-t border-gray-200 pt-5 sm:flex-row dark:border-gray-700">

        <a href="{{ route('reservations.index') }}"
           class="rounded-lg border border-gray-300 px-6 py-3 text-center text-sm font-semibold text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
            Annuler
        </a>

        <button type="submit" class="rounded-lg bg-[#2457A7] px-6 py-3 text-sm font-semibold text-white hover:bg-blue-800">
            {{ isset($reservation)
                ? 'Enregistrer les modifications'
                : ($arriveeImmediate ? 'Enregistrer l’arrivée du client' : 'Créer la réservation') }}
        </button>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const chambre = document.getElementById('chambre_id');
    const arrivee = document.getElementById('date_arrivee');
    const depart = document.getElementById('date_depart');
    const avance = document.getElementById('montant_avance');
    const modePaiement = document.getElementById('mode_paiement');
    const personnes = document.getElementById('nombre_personnes');

    const recapNuits = document.getElementById('recap-nuits');
    const recapTotal = document.getElementById('recap-total');
    const recapReste = document.getElementById('recap-reste');

    const messageCapacite = document.getElementById('message-capacite');
    const messageAvance = document.getElementById('message-avance');

    function calculer() {

        const option = chambre.options[chambre.selectedIndex];

        const tarif = option && option.dataset.tarif
            ? parseFloat(option.dataset.tarif)
            : 0;

        const capacite = option && option.dataset.capacite
            ? parseInt(option.dataset.capacite)
            : 0;

        const date1 = new Date(arrivee.value);
        const date2 = new Date(depart.value);

        let nuits = 0;

        if (arrivee.value && depart.value && date2 > date1) {
            const debut = new Date(date1);
            const fin = new Date(date2);

            debut.setHours(0, 0, 0, 0);
            fin.setHours(0, 0, 0, 0);

            nuits = Math.max(
                1,
                Math.ceil((fin - debut) / 86400000)
            );
        }

        const total = tarif * nuits;
        const avanceMontant = parseFloat(avance.value) || 0;
        if (modePaiement) {
            modePaiement.required = avanceMontant > 0;
            modePaiement.disabled = avanceMontant <= 0;
        }
        const reste = total - avanceMontant;

        recapNuits.textContent = nuits;

        recapTotal.textContent = total.toLocaleString('fr-FR') + ' FCFA';

        recapReste.textContent = reste.toLocaleString('fr-FR') + ' FCFA';

        messageAvance.textContent = '';

        if (avanceMontant > total) {
            messageAvance.textContent = "L'avance dépasse le montant total.";
        }

        messageCapacite.textContent = '';

        if (capacite && parseInt(personnes.value) > capacite) {
            messageCapacite.textContent =
                "Attention : la capacité maximale de cette chambre est de " + capacite + " personne(s).";
            messageCapacite.classList.add('text-red-600');
        }
    }

    [chambre, arrivee, depart, avance, personnes].forEach(function (element) {
        element.addEventListener('change', calculer);
        element.addEventListener('input', calculer);
    });

    calculer();
});
</script>
