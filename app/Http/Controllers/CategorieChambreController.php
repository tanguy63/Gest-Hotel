<?php

namespace App\Http\Controllers;

use App\Models\CategorieChambre;
use App\Models\Etablissement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategorieChambreController extends Controller
{
    /**
     * Récupère l'établissement actif de l'utilisateur connecté.
     */
    private function etablissementActif(): Etablissement
    {
        $utilisateur = auth()->user();

        $etablissement = $utilisateur->etablissements()
            ->wherePivot('est_actif', true)
            ->where('etablissements.id', session('etablissement_actif'))
            ->first();

        abort_unless($etablissement, 403, 'Aucun établissement actif autorisé.');

        return $etablissement;
    }

    /**
     * Récupère le rôle de l'utilisateur dans l'établissement actif.
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
     * Autorise les rôles pouvant consulter les catégories.
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
                'entretien',
            ], true),
            403,
            'Vous n\'êtes pas autorisé à consulter les catégories.'
        );
    }

    /**
     * Autorise les rôles pouvant gérer les catégories.
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
            'Vous n\'êtes pas autorisé à gérer les catégories de chambres.'
        );
    }

    /**
     * Liste des catégories.
     */
    public function index()
    {
        $etablissement = $this->etablissementActif();

        $this->autoriserConsultation($etablissement);

        $categories = $etablissement->categoriesChambres()
            ->withCount('chambres')
            ->orderBy('nom')
            ->paginate(10);

        return view('categories-chambres.index', compact('categories'));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        $etablissement = $this->etablissementActif();

        $this->autoriserGestion($etablissement);

        return view('categories-chambres.create');
    }

    /**
     * Enregistrement d'une catégorie.
     */
    public function store(Request $request)
    {
        $etablissement = $this->etablissementActif();

        $this->autoriserGestion($etablissement);

        $donnees = $request->validate([
            'nom' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories_chambres', 'nom')
                    ->where('etablissement_id', $etablissement->id),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'capacite' => ['required', 'integer', 'min:1', 'max:50'],
            'tarif_base' => ['required', 'numeric', 'min:0'],
            'est_active' => ['nullable', 'boolean'],
        ]);

        $donnees['est_active'] = $request->boolean('est_active');

        $etablissement->categoriesChambres()->create($donnees);

        return redirect()
            ->route('categories-chambres.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Affichage d'une catégorie.
     */
    public function show(string $id)
    {
        $etablissement = $this->etablissementActif();

        $this->autoriserConsultation($etablissement);

        $categorie = $etablissement->categoriesChambres()
            ->with('chambres')
            ->findOrFail($id);

        return view('categories-chambres.show', compact('categorie'));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(string $id)
    {
        $etablissement = $this->etablissementActif();

        $this->autoriserGestion($etablissement);

        $categorie = $etablissement->categoriesChambres()
            ->findOrFail($id);

        return view('categories-chambres.edit', compact('categorie'));
    }

    /**
     * Mise à jour d'une catégorie.
     */
    public function update(Request $request, string $id)
    {
        $etablissement = $this->etablissementActif();

        $this->autoriserGestion($etablissement);

        $categorie = $etablissement->categoriesChambres()
            ->findOrFail($id);

        $donnees = $request->validate([
            'nom' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories_chambres', 'nom')
                    ->where('etablissement_id', $etablissement->id)
                    ->ignore($categorie->id),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'capacite' => ['required', 'integer', 'min:1', 'max:50'],
            'tarif_base' => ['required', 'numeric', 'min:0'],
            'est_active' => ['nullable', 'boolean'],
        ]);

        $donnees['est_active'] = $request->boolean('est_active');

        $categorie->update($donnees);

        return redirect()
            ->route('categories-chambres.index')
            ->with('success', 'Catégorie modifiée avec succès.');
    }

    /**
     * Suppression d'une catégorie.
     */
    public function destroy(string $id)
    {
        $etablissement = $this->etablissementActif();

        $this->autoriserGestion($etablissement);

        $categorie = $etablissement->categoriesChambres()
            ->findOrFail($id);

        if ($categorie->chambres()->exists()) {
            return redirect()
                ->route('categories-chambres.index')
                ->with('error', 'Impossible de supprimer cette catégorie : des chambres y sont encore associées.');
        }

        $categorie->delete();

        return redirect()
            ->route('categories-chambres.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}
