<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\Etablissement;

class PaiementController extends Controller
{

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
     * Autoriser la consultation de la liste des paiements.
     */
    private function autoriserConsultationListe(Etablissement $etablissement): void
    {
        $role = $this->roleUtilisateur($etablissement);

        abort_unless(
            in_array($role, [
                'proprietaire',
                'administrateur',
                'comptable',
            ], true),
            403,
            'Vous n\'êtes pas autorisé à consulter la liste des paiements.'
        );
    }

    /**
     * Autoriser l'enregistrement d'un paiement.
     */
    private function autoriserEnregistrement(Etablissement $etablissement): void
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
            'Vous n\'êtes pas autorisé à enregistrer un paiement.'
        );
    }

    /**
     * Autoriser la consultation d'un reçu.
     */
    private function autoriserConsultationRecu(Etablissement $etablissement): void
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
            'Vous n\'êtes pas autorisé à consulter ce reçu.'
        );
    }

/**
 * Afficher le reçu d'un paiement.
 */
    public function recu(Paiement $paiement)
    {
        $etablissement = $this->etablissementActif();
        $this->autoriserConsultationRecu($etablissement);

        // Vérifier que le paiement appartient à l'établissement actif.
        abort_unless(
            $paiement->etablissement_id == $etablissement->id,
            404
        );

        // Charger les informations nécessaires au reçu.
        $paiement->load([
            'reservation.client',
            'reservation.chambre.categorieChambre',
            'etablissement',
        ]);

        return view('paiements.recu', compact(
            'paiement',
            'etablissement'
        ));
    }
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
 * Générer une référence unique pour un paiement.
 */
private function genererReferencePaiement(int $etablissementId): string
{
    do {
        $reference = 'PAY-' . Str::upper(Str::random(12));

        $existe = Paiement::where('etablissement_id', $etablissementId)
            ->where('reference', $reference)
            ->exists();

    } while ($existe);

    return $reference;
}

    /**
     * Enregistrer un paiement supplémentaire sur une réservation.
     */
    public function store(Request $request, Reservation $reservation)
    {
        $etablissement = $this->etablissementActif();
        $this->autoriserEnregistrement($etablissement);

        abort_unless(
            $reservation->etablissement_id == $etablissement->id,
            404
        );

        $validated = $request->validate([
            'montant' => ['required', 'numeric', 'gt:0'],
            'mode_paiement' => [
                'required',
                'in:especes,mobile_money,carte_bancaire,virement,cheque',
            ],
            'reference_transaction' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        DB::transaction(function () use (
            $etablissement,
            $reservation,
            $validated
        ) {
            // Verrouiller la réservation pour éviter les doubles encaissements.
            $reservationVerrouillee = $etablissement->reservations()
                ->whereKey($reservation->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($reservationVerrouillee->statut === 'annulee') {
                throw ValidationException::withMessages([
                    'montant' => 'Impossible d\'encaisser un paiement sur une réservation annulée.',
                ]);
            }

            // Calculer le montant réellement encaissé.
            $montantEncaisse = (float) $reservationVerrouillee
                ->paiements()
                ->where('statut', 'valide')
                ->sum('montant');

            $resteAPayer = round(
                (float) $reservationVerrouillee->montant_total - $montantEncaisse,
                2
            );

            $montant = round((float) $validated['montant'], 2);

            if ($resteAPayer <= 0) {
                throw ValidationException::withMessages([
                    'montant' => 'Cette réservation est déjà entièrement payée.',
                ]);
            }

            if ($montant > $resteAPayer) {
                throw ValidationException::withMessages([
                    'montant' => 'Le montant saisi dépasse le reste à payer de '
                        . number_format($resteAPayer, 0, ',', ' ')
                        . ' FCFA.',
                ]);
            }

            // Créer le paiement.
            $reservationVerrouillee->paiements()->create([
                'etablissement_id' => $etablissement->id,
                'reference' => $this->genererReferencePaiement($etablissement->id),
                'montant' => $montant,
                'mode_paiement' => $validated['mode_paiement'],
                'date_paiement' => now(),
                'reference_transaction' => $validated['reference_transaction'] ?? null,
                'statut' => 'valide',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Recalculer les montants de la réservation.
            $nouveauMontantEncaisse = round(
                $montantEncaisse + $montant,
                2
            );

            $reservationVerrouillee->update([
                'montant_avance' => $nouveauMontantEncaisse,
                'reste_a_payer' => round(
                    (float) $reservationVerrouillee->montant_total
                    - $nouveauMontantEncaisse,
                    2
                ),
            ]);
        });

        return redirect()
            ->route('reservations.show', $reservation)
            ->with('success', 'Paiement enregistré avec succès.');
    }


    /**
     * Afficher la liste des paiements.
     */
    public function index(Request $request)
    {
        $etablissement = $this->etablissementActif();
        $this->autoriserConsultationListe($etablissement);

        $query = Paiement::query()
            ->where('etablissement_id', $etablissement->id)
            ->with([
                'reservation.client',
                'reservation.chambre',
            ]);

        // Recherche par référence, réservation ou client.
        if ($request->filled('recherche')) {
            $recherche = $request->recherche;

            $query->where(function ($q) use ($recherche) {
                $q->where('reference', 'like', "%{$recherche}%")
                    ->orWhereHas('reservation', function ($reservation) use ($recherche) {
                        $reservation->where(
                            'reference',
                            'like',
                            "%{$recherche}%"
                        );
                    })
                    ->orWhereHas('reservation.client', function ($client) use ($recherche) {
                        $client->where('nom', 'like', "%{$recherche}%")
                            ->orWhere('prenoms', 'like', "%{$recherche}%")
                            ->orWhere('raison_sociale', 'like', "%{$recherche}%");
                    });
            });
        }

        // Filtrer par mode de paiement.
        if ($request->filled('mode_paiement')) {
            $query->where(
                'mode_paiement',
                $request->mode_paiement
            );
        }

        // Filtrer par statut.
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtrer par période.
        if ($request->filled('date_debut')) {
            $query->whereDate(
                'date_paiement',
                '>=',
                $request->date_debut
            );
        }

        if ($request->filled('date_fin')) {
            $query->whereDate(
                'date_paiement',
                '<=',
                $request->date_fin
            );
        }

        $paiements = $query
            ->orderByDesc('date_paiement')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        // Statistiques limitées à l'établissement actif.
        $statistiques = Paiement::query()
            ->where('etablissement_id', $etablissement->id);

        $totalEncaisse = (clone $statistiques)
            ->where('statut', 'valide')
            ->sum('montant');

        $totalAnnule = (clone $statistiques)
            ->where('statut', 'annule')
            ->sum('montant');

        $encaisseAujourdHui = (clone $statistiques)
            ->where('statut', 'valide')
            ->whereDate('date_paiement', today())
            ->sum('montant');

        $nombrePaiements = (clone $statistiques)
            ->count();

        return view('paiements.index', compact(
            'paiements',
            'totalEncaisse',
            'totalAnnule',
            'encaisseAujourdHui',
            'nombrePaiements'
        ));
    }
}
