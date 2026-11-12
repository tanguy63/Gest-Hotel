<?php

namespace App\Http\Controllers;

use App\Models\Etablissement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChambreController extends Controller
{
    /**
     * Récupérer l'établissement actif de l'utilisateur connecté.
     */
    private function etablissementActif(): Etablissement
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
     * Récupérer le rôle de l'utilisateur dans l'établissement.
     */
    private function roleUtilisateur(Etablissement $etablissement): string
    {
        $role = auth()->user()
            ->etablissements()
            ->wherePivot('est_actif', true)
            ->where('etablissements.id', $etablissement->id)
            ->first()?->pivot?->role;

        abort_unless($role, 403, 'Accès non autorisé.');

        return $role;
    }

    /**
     * Vérifier le droit de consultation des chambres.
     */
    private function autoriserConsultation(string $role): void
    {
        abort_unless(
            in_array($role, [
                'proprietaire',
                'administrateur',
                'agent_exploitation',
                'comptable',
                'entretien',
            ], true),
            403,
            'Vous n\'êtes pas autorisé à consulter les chambres.'
        );
    }

    /**
     * Vérifier le droit de gestion des chambres.
     */
    private function autoriserGestion(string $role): void
    {
        abort_unless(
            in_array($role, [
                'proprietaire',
                'administrateur',
                'agent_exploitation',
            ], true),
            403,
            'Vous n\'êtes pas autorisé à gérer les chambres.'
        );
    }

    /**
     * Liste des chambres.
     */
    public function index(Request $request)
    {
        $etablissement = $this->etablissementActif();

        $role = $this->roleUtilisateur($etablissement);
        $this->autoriserConsultation($role);

        $query = $etablissement->chambres()
            ->with('categorieChambre');

        if ($request->filled('recherche')) {
            $recherche = $request->recherche;
            $query->where('numero', 'like', "%{$recherche}%");
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('categorie')) {
            $query->where('categorie_chambre_id', $request->categorie);
        }

        $chambres = $query->orderBy('numero')
            ->paginate(10)
            ->withQueryString();

        $categories = $etablissement->categoriesChambres()
            ->orderBy('nom')
            ->get();

        return view('chambres.index', compact('chambres', 'categories'));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        $etablissement = $this->etablissementActif();

        $role = $this->roleUtilisateur($etablissement);
        $this->autoriserGestion($role);

        $categories = $etablissement->categoriesChambres()
            ->where('est_active', true)
            ->orderBy('nom')
            ->get();

        return view('chambres.create', compact('categories'));
    }

    /**
     * Enregistrement d'une chambre.
     */
    public function store(Request $request)
    {
        $etablissement = $this->etablissementActif();

        $role = $this->roleUtilisateur($etablissement);
        $this->autoriserGestion($role);

        $donnees = $request->validate([
            'categorie_chambre_id' => [
                'required',
                'integer',
                Rule::exists('categories_chambres', 'id')
                    ->where('etablissement_id', $etablissement->id)
                    ->where('est_active', true),
            ],
            'numero' => [
                'required',
                'string',
                'max:30',
                Rule::unique('chambres', 'numero')
                    ->where('etablissement_id', $etablissement->id),
            ],
            'etage' => ['nullable', 'string', 'max:50'],
            'capacite' => ['required', 'integer', 'min:1', 'max:50'],
            'tarif_nuit' => ['required', 'numeric', 'min:0'],
            'statut' => [
                'required',
                Rule::in([
                    'disponible',
                    'occupee',
                    'maintenance',
                    'hors_service',
                ]),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $etablissement->chambres()->create($donnees);

        return redirect()
            ->route('chambres.index')
            ->with('success', 'Chambre créée avec succès.');
    }

    /**
     * Affichage d'une chambre.
     */
    public function show(string $id)
    {
        $etablissement = $this->etablissementActif();

        $role = $this->roleUtilisateur($etablissement);
        $this->autoriserConsultation($role);

        $chambre = $etablissement->chambres()
            ->with('categorieChambre')
            ->findOrFail($id);

        return view('chambres.show', compact('chambre'));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(string $id)
    {
        $etablissement = $this->etablissementActif();

        $role = $this->roleUtilisateur($etablissement);
        $this->autoriserGestion($role);

        $chambre = $etablissement->chambres()
            ->findOrFail($id);

        $categories = $etablissement->categoriesChambres()
            ->orderBy('nom')
            ->get();

        return view('chambres.edit', compact('chambre', 'categories'));
    }

    /**
     * Mise à jour d'une chambre.
     */
    public function update(Request $request, string $id)
    {
        $etablissement = $this->etablissementActif();

        $role = $this->roleUtilisateur($etablissement);
        $this->autoriserGestion($role);

        $chambre = $etablissement->chambres()
            ->findOrFail($id);

        $donnees = $request->validate([
            'categorie_chambre_id' => [
                'required',
                'integer',
                Rule::exists('categories_chambres', 'id')
                    ->where('etablissement_id', $etablissement->id),
            ],
            'numero' => [
                'required',
                'string',
                'max:30',
                Rule::unique('chambres', 'numero')
                    ->where('etablissement_id', $etablissement->id)
                    ->ignore($chambre->id),
            ],
            'etage' => ['nullable', 'string', 'max:50'],
            'capacite' => ['required', 'integer', 'min:1', 'max:50'],
            'tarif_nuit' => ['required', 'numeric', 'min:0'],
            'statut' => [
                'required',
                Rule::in([
                    'disponible',
                    'occupee',
                    'maintenance',
                    'hors_service',
                ]),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $chambre->update($donnees);

        return redirect()
            ->route('chambres.index')
            ->with('success', 'Chambre modifiée avec succès.');
    }

    /**
     * Suppression d'une chambre.
     */
    public function destroy(string $id)
    {
        $etablissement = $this->etablissementActif();

        $role = $this->roleUtilisateur($etablissement);
        $this->autoriserGestion($role);

        $chambre = $etablissement->chambres()
            ->findOrFail($id);

        $chambre->delete();

        return redirect()
            ->route('chambres.index')
            ->with('success', 'Chambre supprimée avec succès.');
    }
}
