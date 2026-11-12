<?php

namespace App\Http\Controllers;

use App\Models\Chambre;
use App\Models\Paiement;
use App\Models\Reservation;

class DashboardController extends Controller
{
    public function index()
    {
        $utilisateur = auth()->user();

        // Établissement actif autorisé
        $etablissement = $utilisateur->etablissements()
            ->wherePivot('est_actif', true)
            ->where('etablissements.id', session('etablissement_actif'))
            ->first();

        abort_unless(
            $etablissement,
            403,
            'Aucun établissement actif autorisé.'
        );

        $etablissementId = $etablissement->id;
        $roleEtablissement = $etablissement->pivot->role;
        $maintenant = now();

        // Droits du tableau de bord
        $peutVoirReservations = in_array($roleEtablissement, [
            'proprietaire',
            'administrateur',
            'agent_exploitation',
            'comptable',
        ], true);

        $peutVoirFinances = in_array($roleEtablissement, [
            'proprietaire',
            'administrateur',
            'comptable',
        ], true);

        $peutCreerReservation = in_array($roleEtablissement, [
            'proprietaire',
            'administrateur',
            'agent_exploitation',
        ], true);

        $estPersonnelEntretien = $roleEtablissement === 'entretien';

        /*
        |--------------------------------------------------------------------------
        | Chambres
        |--------------------------------------------------------------------------
        */

        $chambresTotal = Chambre::query()
            ->where('etablissement_id', $etablissementId)
            ->count();

        $chambresIndisponibles = Chambre::query()
            ->where('etablissement_id', $etablissementId)
            ->whereIn('statut', ['maintenance', 'hors_service'])
            ->count();

        $chambresOperationnelles = $chambresTotal - $chambresIndisponibles;

        /*
        |--------------------------------------------------------------------------
        | Occupation
        |--------------------------------------------------------------------------
        */

        $sejoursEnCours = Reservation::query()
            ->where('etablissement_id', $etablissementId)
            ->where('statut', 'en_cours')
            ->where('date_arrivee', '<=', $maintenant)
            ->where('date_depart', '>', $maintenant);

        $chambresOccupeesIds = (clone $sejoursEnCours)
            ->pluck('chambre_id')
            ->unique()
            ->values();

        $chambresOccupees = min(
            $chambresOccupeesIds->count(),
            $chambresOperationnelles
        );

        $clientsPresents = $peutVoirReservations
            ? (clone $sejoursEnCours)->sum('nombre_personnes')
            : 0;

        $chambresDisponibles = max(
            0,
            $chambresOperationnelles - $chambresOccupees
        );

        $tauxOccupation = $chambresOperationnelles > 0
            ? (int) round(
                ($chambresOccupees / $chambresOperationnelles) * 100
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Valeurs par défaut
        |--------------------------------------------------------------------------
        */

        $reservationsMois = 0;
        $arriveesAujourdhui = 0;
        $reservationsRecentes = collect();
        $arriveesDuJour = collect();
        $departsAujourdhui = collect();

        $revenusMois = 0;
        $revenusAujourdhui = 0;
        $revenusMensuels = collect();

        $debutMois = $maintenant->copy()->startOfMonth();
        $finMois = $maintenant->copy()->endOfMonth();

        /*
        |--------------------------------------------------------------------------
        | Réservations et informations opérationnelles
        |--------------------------------------------------------------------------
        */

        if ($peutVoirReservations) {

            $reservationsMois = Reservation::query()
                ->where('etablissement_id', $etablissementId)
                ->whereBetween('created_at', [$debutMois, $finMois])
                ->count();

            $arriveesAujourdhui = Reservation::query()
                ->where('etablissement_id', $etablissementId)
                ->whereDate('date_arrivee', $maintenant->toDateString())
                ->whereIn('statut', [
                    'en_attente',
                    'confirmee',
                    'en_cours',
                ])
                ->count();

            $reservationsRecentes = Reservation::query()
                ->where('etablissement_id', $etablissementId)
                ->with(['client', 'chambre'])
                ->orderByDesc('created_at')
                ->limit(7)
                ->get();

            $arriveesDuJour = Reservation::query()
                ->where('etablissement_id', $etablissementId)
                ->whereDate('date_arrivee', $maintenant->toDateString())
                ->whereIn('statut', [
                    'en_attente',
                    'confirmee',
                    'en_cours',
                ])
                ->with(['client', 'chambre'])
                ->orderBy('date_arrivee')
                ->get();

            $departsAujourdhui = Reservation::query()
                ->where('etablissement_id', $etablissementId)
                ->whereDate('date_depart', $maintenant->toDateString())
                ->where('statut', 'en_cours')
                ->with(['client', 'chambre'])
                ->orderBy('date_depart')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Revenus encaissés
        |--------------------------------------------------------------------------
        */

        if ($peutVoirFinances) {

            $revenusMois = (float) Paiement::query()
                ->where('etablissement_id', $etablissementId)
                ->where('statut', 'valide')
                ->whereBetween('date_paiement', [
                    $debutMois,
                    $finMois,
                ])
                ->sum('montant');

            $revenusAujourdhui = (float) Paiement::query()
                ->where('etablissement_id', $etablissementId)
                ->where('statut', 'valide')
                ->whereDate(
                    'date_paiement',
                    $maintenant->toDateString()
                )
                ->sum('montant');

            /*
            |--------------------------------------------------------------------------
            | Graphique des revenus sur 12 mois
            |--------------------------------------------------------------------------
            */

            $debutGraphique = $maintenant->copy()
                ->startOfMonth()
                ->subMonths(11);

            $revenusGroupes = Paiement::query()
                ->where('etablissement_id', $etablissementId)
                ->where('statut', 'valide')
                ->whereBetween('date_paiement', [
                    $debutGraphique,
                    $finMois,
                ])
                ->selectRaw(
                    'YEAR(date_paiement) as annee,
                     MONTH(date_paiement) as mois,
                     SUM(montant) as total'
                )
                ->groupByRaw(
                    'YEAR(date_paiement), MONTH(date_paiement)'
                )
                ->get();

            $revenusIndex = [];

            foreach ($revenusGroupes as $revenu) {
                $cle = sprintf(
                    '%04d-%02d',
                    $revenu->annee,
                    $revenu->mois
                );

                $revenusIndex[$cle] = (float) $revenu->total;
            }

            $revenusMensuels = collect(range(0, 11))
                ->map(function ($index) use (
                    $debutGraphique,
                    $revenusIndex
                ) {
                    $mois = $debutGraphique->copy()
                        ->addMonths($index);

                    return [
                        'label' => $mois->translatedFormat('M'),
                        'montant' => $revenusIndex[
                            $mois->format('Y-m')
                        ] ?? 0,
                    ];
                });
        }

        /*
        |--------------------------------------------------------------------------
        | Indicateurs selon le rôle
        |--------------------------------------------------------------------------
        */

        $stats = [
            [
                'label' => 'Chambres',
                'value' => number_format($chambresTotal, 0, ',', ' '),
                'detail' => number_format(
                    $chambresOperationnelles,
                    0,
                    ',',
                    ' '
                ) . ' opérationnelles',
                'icon' => '▤',
                'color' => 'blue',
            ],
        ];

        if ($peutVoirReservations) {
            $stats[] = [
                'label' => 'Réservations du mois',
                'value' => number_format(
                    $reservationsMois,
                    0,
                    ',',
                    ' '
                ),
                'detail' => $arriveesAujourdhui
                    . ' arrivée(s) aujourd’hui',
                'icon' => '▣',
                'color' => 'green',
            ];

            $stats[] = [
                'label' => 'Clients présents',
                'value' => number_format(
                    $clientsPresents,
                    0,
                    ',',
                    ' '
                ),
                'detail' => 'Séjours actuellement en cours',
                'icon' => '♙',
                'color' => 'orange',
            ];
        }

        if ($peutVoirFinances) {
            $stats[] = [
                'label' => 'Revenus du mois',
                'value' => number_format(
                    $revenusMois,
                    0,
                    ',',
                    ' '
                ),
                'detail' => 'FCFA encaissés',
                'icon' => '◉',
                'color' => 'purple',
            ];
        }

        return view('dashboard.index', compact(
            'etablissement',
            'roleEtablissement',
            'peutVoirReservations',
            'peutVoirFinances',
            'peutCreerReservation',
            'estPersonnelEntretien',
            'stats',
            'chambresTotal',
            'chambresOperationnelles',
            'chambresIndisponibles',
            'chambresOccupees',
            'chambresDisponibles',
            'tauxOccupation',
            'clientsPresents',
            'reservationsMois',
            'arriveesAujourdhui',
            'reservationsRecentes',
            'revenusMois',
            'revenusAujourdhui',
            'arriveesDuJour',
            'departsAujourdhui',
            'revenusMensuels'
        ));
    }
}
