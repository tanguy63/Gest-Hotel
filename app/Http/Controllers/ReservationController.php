<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Chambre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Models\Etablissement;

class ReservationController extends Controller
{
    /**
     * Récupérer l'établissement actif de l'utilisateur.
     */
    private function etablissementActif()
    {
        $utilisateur = auth()->user();

        $etablissement = $utilisateur->etablissements()
            ->wherePivot('est_actif', true)
            ->where('etablissements.id', session('etablissement_actif'))
            ->first();

        abort_unless(
            $etablissement,
            403,
            'Aucun établissement actif autorisé.'
        );

        return $etablissement;
    }


    /**
     * Récupérer le rôle de l'utilisateur dans l'établissement actif.
     */
    private function roleUtilisateur(Etablissement $etablissement): string
    {
        $utilisateur = $etablissement->utilisateurs()
            ->where('utilisateurs.id', auth()->id())
            ->wherePivot('est_actif', true)
            ->first();

        abort_unless($utilisateur, 403, 'Accès non autorisé.');

        return $utilisateur->pivot->role;
    }

    /**
     * Autoriser la consultation des réservations.
     */
    private function autoriserConsultation(Etablissement $etablissement): void
    {
        $role = $this->roleUtilisateur($etablissement);

        abort_unless(
            in_array($role, [
                'proprietaire',
                'administrateur',
                'agent_exploitation',
                'comptable',
            ], true),
            403,
            'Vous n\'êtes pas autorisé à consulter les réservations.'
        );
    }

    /**
     * Autoriser la gestion des réservations.
     */
    private function autoriserGestion(Etablissement $etablissement): void
    {
        $role = $this->roleUtilisateur($etablissement);

        abort_unless(
            in_array($role, [
                'proprietaire',
                'administrateur',
                'agent_exploitation',
            ], true),
            403,
            'Vous n\'êtes pas autorisé à gérer les réservations.'
        );
    }


    /**
     * Vérifier la disponibilité d'une chambre.
     */
    private function verifierDisponibilite(
        Chambre $chambre,
        Carbon $arrivee,
        Carbon $depart,
        ?int $reservationId = null
    ): void {
        if (in_array($chambre->statut, ['maintenance', 'hors_service'])) {
            throw ValidationException::withMessages([
                'chambre_id' => 'Cette chambre est en maintenance ou hors service.',
            ]);
        }

        $reservations = Reservation::where('etablissement_id', $chambre->etablissement_id)
            ->where('chambre_id', $chambre->id)
            ->whereIn('statut', ['en_attente', 'confirmee', 'en_cours'])
            ->where('date_arrivee', '<', $depart)
            ->where('date_depart', '>', $arrivee);

        if ($reservationId) {
            $reservations->where('id', '!=', $reservationId);
        }

        if ($reservations->exists()) {
            throw ValidationException::withMessages([
                'chambre_id' => 'Cette chambre est déjà réservée pour cette période.',
            ]);
        }
    }

    /**
     * Calculer le nombre de nuits.
     */
    private function calculerNombreNuits(
        Carbon $arrivee,
        Carbon $depart
    ): int {
        return max(
            1,
            (int) $arrivee->copy()->startOfDay()
                ->diff($depart->copy()->startOfDay())->days
        );
    }

    /**
     * Générer une référence unique de paiement.
     */
    private function genererReferencePaiement(int $etablissementId): string
    {
        do {
            $reference = 'PAY-' . Str::upper(Str::random(12));

            $existe = DB::table('paiements')
                ->where('etablissement_id', $etablissementId)
                ->where('reference', $reference)
                ->exists();

        } while ($existe);

        return $reference;
    }

    /**
     * Liste des réservations.
     */
    public function index(Request $request)
    {
        $etablissement = $this->etablissementActif();

        $reservations = $etablissement->reservations()
            ->with(['client', 'chambre'])
            ->when($request->filled('recherche'), function ($query) use ($request) {
                $recherche = $request->recherche;

                $query->where(function ($q) use ($recherche) {
                    $q->where('reference', 'like', "%{$recherche}%")
                        ->orWhereHas('client', function ($client) use ($recherche) {
                            $client->where('nom', 'like', "%{$recherche}%")
                                ->orWhere('prenoms', 'like', "%{$recherche}%")
                                ->orWhere('raison_sociale', 'like', "%{$recherche}%");
                        })
                        ->orWhereHas('chambre', function ($chambre) use ($recherche) {
                            $chambre->where('numero', 'like', "%{$recherche}%");
                        });
                });
            })
            ->when($request->filled('statut'), function ($query) use ($request) {
                $query->where('statut', $request->statut);
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('reservations.index', compact('reservations'));
    }

    /**
     * Formulaire de création.
     */
    public function create(Request $request)
    {
        $etablissement = $this->etablissementActif();

        $clients = $etablissement->clients()
            ->orderBy('nom')
            ->get();

        $chambres = $etablissement->chambres()
            ->whereNotIn('statut', ['maintenance', 'hors_service'])
            ->with('categorieChambre')
            ->orderBy('numero')
            ->get();

        $arriveeImmediate = $request->query('mode') === 'arrivee';

        return view('reservations.create', compact(
            'clients',
            'chambres',
            'arriveeImmediate'
        ));
    }

    /**
     * Enregistrer une réservation et son éventuelle avance.
     */
    public function store(Request $request)
    {
        $etablissement = $this->etablissementActif();

        $validated = $request->validate([
            'client_id' => ['required', 'integer'],
            'chambre_id' => ['required', 'integer'],
            'date_arrivee' => [
                'required_unless:type_arrivee,direct',
                'nullable',
                'date',
            ],
            'date_depart' => ['required', 'date', 'after:date_arrivee'],
            'nombre_personnes' => ['required', 'integer', 'min:1'],
            'montant_avance' => ['required', 'numeric', 'min:0'],
            'mode_paiement' => [
                'nullable',
                'in:especes,mobile_money,carte_bancaire,virement,cheque',
            ],
            'reference_transaction' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'type_arrivee' => ['nullable', 'in:direct'],
        ]);

        $avance = (float) $validated['montant_avance'];

        if ($avance > 0 && empty($validated['mode_paiement'])) {
            throw ValidationException::withMessages([
                'mode_paiement' => 'Veuillez sélectionner le mode de paiement de l’avance.',
            ]);
        }

        $typeArrivee = $validated['type_arrivee'] ?? 'classique';

        $arrivee = $typeArrivee === 'direct'
            ? now()
            : Carbon::parse($validated['date_arrivee']);

        $depart = Carbon::parse($validated['date_depart']);

        if (!$depart->gt($arrivee)) {
            throw ValidationException::withMessages([
                'date_depart' => 'La date de départ doit être postérieure à la date d’arrivée.',
            ]);
        }

        $client = $etablissement->clients()
            ->findOrFail($validated['client_id']);

        $reservation = DB::transaction(function () use (
            $etablissement,
            $validated,
            $arrivee,
            $depart,
            $client,
            $typeArrivee,
            $avance
        ) {
            $chambre = Chambre::where('etablissement_id', $etablissement->id)
                ->where('id', $validated['chambre_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($validated['nombre_personnes'] > $chambre->capacite) {
                throw ValidationException::withMessages([
                    'nombre_personnes' => 'Le nombre de personnes dépasse la capacité de cette chambre.',
                ]);
            }

            $this->verifierDisponibilite(
                $chambre,
                $arrivee,
                $depart
            );

            $nombreNuits = $this->calculerNombreNuits($arrivee, $depart);

            $tarifNuit = (float) $chambre->tarif_nuit;
            $montantTotal = round($tarifNuit * $nombreNuits, 2);

            if ($avance > $montantTotal) {
                throw ValidationException::withMessages([
                    'montant_avance' => 'L’avance ne peut pas dépasser le montant total.',
                ]);
            }

            // Création de la réservation.
            $reservation = $etablissement->reservations()->create([
                'client_id' => $client->id,
                'chambre_id' => $chambre->id,
                'reference' => 'RES-' . Str::upper(Str::random(10)),
                'date_arrivee' => $arrivee,
                'date_depart' => $depart,
                'nombre_personnes' => $validated['nombre_personnes'],
                'tarif_nuit' => $tarifNuit,
                'nombre_nuits' => $nombreNuits,
                'montant_total' => $montantTotal,
                'montant_avance' => $avance,
                'reste_a_payer' => round($montantTotal - $avance, 2),
                'statut' => $typeArrivee === 'direct' ? 'en_cours' : 'en_attente',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Enregistrer automatiquement l'avance comme paiement.
            if ($avance > 0) {
                $reservation->paiements()->create([
                    'etablissement_id' => $etablissement->id,
                    'reference' => $this->genererReferencePaiement($etablissement->id),
                    'montant' => $avance,
                    'mode_paiement' => $validated['mode_paiement'],
                    'date_paiement' => now(),
                    'reference_transaction' => $validated['reference_transaction'] ?? null,
                    'statut' => 'valide',
                    'notes' => 'Avance encaissée lors de la création de la réservation.',
                ]);
            }

            return $reservation;
        });

        return redirect()
            ->route('reservations.show', $reservation)
            ->with('success', 'Réservation créée avec succès.');
    }

    /**
     * Afficher une réservation.
     */
    public function show(Reservation $reservation)
    {
        $etablissement = $this->etablissementActif();

        abort_unless(
            $reservation->etablissement_id == $etablissement->id,
            404
        );

        $reservation->load([
            'client',
            'chambre.categorieChambre',
            'paiements' => function ($query) {
                $query->orderByDesc('date_paiement')
                    ->orderByDesc('id');
            },
        ]);

        $montantEncaisse = (float) $reservation->paiements
            ->where('statut', 'valide')
            ->sum('montant');

        $resteAPayer = round(
            (float) $reservation->montant_total - $montantEncaisse,
            2
        );

        return view('reservations.show', compact(
            'reservation',
            'montantEncaisse',
            'resteAPayer'
        ));
    }

    /**
 * Générer le reçu récapitulatif de tous les paiements.
 */
    public function recu(Reservation $reservation)
    {
        $etablissement = $this->etablissementActif();

        abort_unless(
            $reservation->etablissement_id == $etablissement->id,
            404
        );

        $reservation->load([
            'client',
            'chambre.categorieChambre',
            'paiements' => function ($query) {
                $query->orderBy('date_paiement')
                    ->orderBy('id');
            },
        ]);

        $montantEncaisse = (float) $reservation->paiements
            ->where('statut', 'valide')
            ->sum('montant');

        $montantAnnule = (float) $reservation->paiements
            ->where('statut', 'annule')
            ->sum('montant');

        $resteAPayer = round(
            (float) $reservation->montant_total - $montantEncaisse,
            2
        );

        return view('reservations.recu', compact(
            'reservation',
            'etablissement',
            'montantEncaisse',
            'montantAnnule',
            'resteAPayer'
        ));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(Reservation $reservation)
    {
        $etablissement = $this->etablissementActif();

        abort_unless(
            $reservation->etablissement_id == $etablissement->id,
            404
        );

        $clients = $etablissement->clients()
            ->orderBy('nom')
            ->get();

        $chambres = $etablissement->chambres()
            ->with('categorieChambre')
            ->orderBy('numero')
            ->get();

        return view('reservations.edit', compact(
            'reservation',
            'clients',
            'chambres'
        ));
    }

    /**
     * Modifier une réservation sans modifier directement ses paiements.
     */
    public function update(Request $request, Reservation $reservation)
    {
        $etablissement = $this->etablissementActif();

        abort_unless(
            $reservation->etablissement_id == $etablissement->id,
            404
        );

        $validated = $request->validate([
            'client_id' => ['required', 'integer'],
            'chambre_id' => ['required', 'integer'],
            'date_arrivee' => ['required', 'date'],
            'date_depart' => ['required', 'date', 'after:date_arrivee'],
            'nombre_personnes' => ['required', 'integer', 'min:1'],
            'statut' => [
                'required',
                'in:en_attente,confirmee,en_cours,terminee,annulee',
            ],
            'notes' => ['nullable', 'string', 'max:5000'],
            'motif_annulation' => ['nullable', 'string', 'max:5000'],
        ]);

        $arrivee = Carbon::parse($validated['date_arrivee']);
        $depart = Carbon::parse($validated['date_depart']);

        if (!$depart->gt($arrivee)) {
            throw ValidationException::withMessages([
                'date_depart' => 'La date de départ doit être postérieure à la date d’arrivée.',
            ]);
        }

        $client = $etablissement->clients()
            ->findOrFail($validated['client_id']);

        DB::transaction(function () use (
            $etablissement,
            $reservation,
            $validated,
            $arrivee,
            $depart,
            $client
        ) {
            // Verrouiller la réservation pendant le recalcul.
            $reservationVerrouillee = $etablissement->reservations()
                ->whereKey($reservation->id)
                ->lockForUpdate()
                ->firstOrFail();

            $chambre = Chambre::where('etablissement_id', $etablissement->id)
                ->where('id', $validated['chambre_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($validated['nombre_personnes'] > $chambre->capacite) {
                throw ValidationException::withMessages([
                    'nombre_personnes' => 'Le nombre de personnes dépasse la capacité de cette chambre.',
                ]);
            }

            if (in_array($validated['statut'], [
                'en_attente',
                'confirmee',
                'en_cours',
            ])) {
                $this->verifierDisponibilite(
                    $chambre,
                    $arrivee,
                    $depart,
                    $reservationVerrouillee->id
                );
            }

            $nombreNuits = $this->calculerNombreNuits($arrivee, $depart);

            $tarifNuit = (float) $chambre->tarif_nuit;
            $montantTotal = round($tarifNuit * $nombreNuits, 2);

            // Le montant encaissé vient exclusivement des paiements validés.
            $montantEncaisse = (float) $reservationVerrouillee
                ->paiements()
                ->where('statut', 'valide')
                ->sum('montant');

            if ($montantEncaisse > $montantTotal) {
                throw ValidationException::withMessages([
                    'date_depart' => 'Le nouveau montant total est inférieur aux paiements déjà encaissés. Modifiez les dates ou la chambre.',
                ]);
            }

            $reservationVerrouillee->update([
                'client_id' => $client->id,
                'chambre_id' => $chambre->id,
                'date_arrivee' => $arrivee,
                'date_depart' => $depart,
                'nombre_personnes' => $validated['nombre_personnes'],
                'tarif_nuit' => $tarifNuit,
                'nombre_nuits' => $nombreNuits,
                'montant_total' => $montantTotal,
                'montant_avance' => $montantEncaisse,
                'reste_a_payer' => round($montantTotal - $montantEncaisse, 2),
                'statut' => $validated['statut'],
                'notes' => $validated['notes'] ?? null,
                'motif_annulation' => $validated['motif_annulation'] ?? null,
            ]);
        });

        return redirect()
            ->route('reservations.show', $reservation)
            ->with('success', 'Réservation modifiée avec succès.');
    }

    /**
     * Annuler une réservation sans supprimer son historique de paiements.
     */
    public function destroy(Reservation $reservation)
    {
        $etablissement = $this->etablissementActif();

        abort_unless(
            $reservation->etablissement_id == $etablissement->id,
            404
        );

        if ($reservation->statut === 'terminee') {
            return back()->with(
                'error',
                'Une réservation terminée ne peut pas être annulée.'
            );
        }

        $reservation->update([
            'statut' => 'annulee',
        ]);

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Réservation annulée avec succès.');
    }
}
