<?php

namespace App\Http\Controllers;

use App\Models\Etablissement;
use App\Models\Chambre;
use App\Models\Client;
use App\Models\Reservation;
use App\Models\Paiement;
use App\Models\TypeEtablissement;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    /**
     * Tableau de bord général de la plateforme.
     */
    public function index(Request $request)
    {
        // Recherche et filtres
        $recherche = trim($request->input('recherche', ''));
        $statut = $request->input('statut', '');
        $type = $request->input('type', '');

        // Liste des établissements
        $query = Etablissement::with('typeEtablissement')
            ->withCount([
                'chambres',
                'clients',
                'reservations',
                'paiements',
            ]);

        if ($recherche !== '') {
            $query->where(function ($q) use ($recherche) {
                $q->where('nom', 'like', "%{$recherche}%")
                    ->orWhere('ville', 'like', "%{$recherche}%");
            });
        }

        if ($statut !== '') {
            $query->where('statut', $statut);
        }

        if ($type !== '') {
            $query->where('type_etablissement_id', $type);
        }

        $etablissements = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Répartition réelle des établissements par statut
        $repartitionStatuts = Etablissement::selectRaw(
            'statut, COUNT(*) as total'
        )
            ->groupBy('statut')
            ->pluck('total', 'statut');

        // Statistiques globales
        $statistiques = [
            'total_etablissements' => Etablissement::count(),

            'etablissements_actifs' => Etablissement::where(
                'statut',
                'actif'
            )->count(),

            'etablissements_en_attente' => Etablissement::where(
                'statut',
                'en_attente_verification'
            )->count(),

            'etablissements_suspendus' => Etablissement::where(
                'statut',
                'suspendu'
            )->count(),

            'total_chambres' => Chambre::count(),

            'total_clients' => Client::count(),

            'total_reservations' => Reservation::count(),

            'total_paiements' => Paiement::count(),

            'revenus_total' => Paiement::where(
                'statut',
                'valide'
            )->sum('montant'),

            'repartition_statuts' => $repartitionStatuts,
        ];

        // Types disponibles pour le filtre
        $typesEtablissements = TypeEtablissement::orderBy('nom')
            ->get();

        return view('super-admin.index', compact(
            'etablissements',
            'statistiques',
            'typesEtablissements',
            'recherche',
            'statut',
            'type'
        ));
    }

    /**
     * Consultation détaillée d'un établissement.
     * Accès strictement en lecture seule.
     */
    public function show(Etablissement $etablissement)
    {
        $etablissement->load('typeEtablissement');

        $statistiques = [
            'chambres' => $etablissement->chambres()->count(),

            'clients' => $etablissement->clients()->count(),

            'reservations' => $etablissement->reservations()->count(),

            'paiements' => $etablissement->paiements()->count(),

            'revenus' => $etablissement->paiements()
                ->where('statut', 'valide')
                ->sum('montant'),

            'personnel' => $etablissement->personnel()
                ->wherePivot('est_actif', true)
                ->count(),
        ];

        $chambres = $etablissement->chambres()
            ->with('categorieChambre')
            ->latest()
            ->take(8)
            ->get();

        $clients = $etablissement->clients()
            ->latest()
            ->take(8)
            ->get();

        $reservations = $etablissement->reservations()
            ->with(['client', 'chambre'])
            ->latest()
            ->take(8)
            ->get();

        $paiements = $etablissement->paiements()
            ->with(['reservation.client', 'reservation.chambre'])
            ->latest()
            ->take(8)
            ->get();

        // Personnel actif de l'établissement
        $personnel = $etablissement->personnel()
            ->wherePivot('est_actif', true)
            ->orderBy('utilisateurs.nom_complet')
            ->take(8)
            ->get();

        return view('super-admin.etablissements.show', compact(
            'etablissement',
            'statistiques',
            'chambres',
            'clients',
            'reservations',
            'paiements',
            'personnel'
        ));
    }
}
