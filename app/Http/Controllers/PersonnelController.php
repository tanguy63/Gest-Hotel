<?php

namespace App\Http\Controllers;

use App\Models\Etablissement;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PersonnelController extends Controller
{
    /**
     * Récupérer l'établissement actif de l'utilisateur.
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
     * Réactiver l'accès d'un membre du personnel.
     */
    public function reactiver(string $id)
    {
        $etablissement = $this->etablissementActif();
        $roleGestionnaire = $this->roleGestionnaire($etablissement);

        $personnel = $etablissement->personnel()
            ->where('utilisateurs.id', $id)
            ->firstOrFail();

        if (
            $roleGestionnaire === 'administrateur'
            && $personnel->pivot->role === 'administrateur'
        ) {
            abort(403, 'Vous ne pouvez pas réactiver ce compte.');
        }

        $etablissement->utilisateurs()->updateExistingPivot($personnel->id, [
            'est_actif' => true,
        ]);

        return redirect()
            ->route('personnel.index')
            ->with('success', 'L’accès de ce membre du personnel a été réactivé.');
    }


    /**
     * Vérifier que l'utilisateur peut gérer le personnel.
     */
    private function roleGestionnaire(Etablissement $etablissement): string
    {
        $role = auth()->user()
            ->etablissements()
            ->wherePivot('est_actif', true)
            ->where('etablissements.id', $etablissement->id)
            ->first()?->pivot?->role;

        abort_unless(
            in_array($role, ['proprietaire', 'administrateur'], true),
            403,
            'Vous n\'êtes pas autorisé à gérer le personnel.'
        );

        return $role;
    }

    /**
     * Rôles que l'utilisateur connecté peut attribuer.
     */
    private function rolesDisponibles(string $roleGestionnaire): array
    {
        if ($roleGestionnaire === 'proprietaire') {
            return [
                'administrateur' => 'Administrateur',
                'agent_exploitation' => 'Agent d’exploitation',
                'comptable' => 'Comptable',
                'entretien' => 'Personnel d’entretien',
            ];
        }

        return [
            'agent_exploitation' => 'Agent d’exploitation',
            'comptable' => 'Comptable',
            'entretien' => 'Personnel d’entretien',
        ];
    }

    /**
     * Liste du personnel.
     */
    public function index()
    {
        $etablissement = $this->etablissementActif();
        $roleGestionnaire = $this->roleGestionnaire($etablissement);

        $personnels = $etablissement->personnel()
            ->orderBy('utilisateurs.nom_complet')
            ->paginate(10);

        return view('personnel.index', compact(
            'personnels',
            'roleGestionnaire'
        ));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        $etablissement = $this->etablissementActif();
        $roleGestionnaire = $this->roleGestionnaire($etablissement);

        $roles = $this->rolesDisponibles($roleGestionnaire);

        return view('personnel.create', compact('roles'));
    }

    /**
     * Enregistrer un nouveau membre du personnel.
     */
    public function store(Request $request)
    {
        $etablissement = $this->etablissementActif();
        $roleGestionnaire = $this->roleGestionnaire($etablissement);

        $roles = $this->rolesDisponibles($roleGestionnaire);

        $donnees = $request->validate([
            'nom_complet' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:utilisateurs,email',
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(array_keys($roles))],
        ]);

        $personnel = Utilisateur::create([
            'nom_complet' => $donnees['nom_complet'],
            'email' => $donnees['email'],
            'password' => $donnees['password'],
        ]);

        $etablissement->utilisateurs()->attach($personnel->id, [
            'role' => $donnees['role'],
            'est_actif' => true,
        ]);

        return redirect()
            ->route('personnel.index')
            ->with('success', 'Le membre du personnel a été créé avec succès.');
    }

    /**
     * Formulaire de modification.
     */
    public function edit(string $id)
    {
        $etablissement = $this->etablissementActif();
        $roleGestionnaire = $this->roleGestionnaire($etablissement);

        $personnel = $etablissement->personnel()
            ->where('utilisateurs.id', $id)
            ->firstOrFail();

        // Un administrateur ne peut pas modifier un autre administrateur.
        if (
            $roleGestionnaire === 'administrateur'
            && $personnel->pivot->role === 'administrateur'
        ) {
            abort(403, 'Vous ne pouvez pas modifier ce compte.');
        }

        $roles = $this->rolesDisponibles($roleGestionnaire);

        return view('personnel.edit', compact('personnel', 'roles'));
    }

    /**
     * Mettre à jour un membre du personnel.
     */
    public function update(Request $request, string $id)
    {
        $etablissement = $this->etablissementActif();
        $roleGestionnaire = $this->roleGestionnaire($etablissement);

        $personnel = $etablissement->personnel()
            ->where('utilisateurs.id', $id)
            ->firstOrFail();

        if (
            $roleGestionnaire === 'administrateur'
            && $personnel->pivot->role === 'administrateur'
        ) {
            abort(403, 'Vous ne pouvez pas modifier ce compte.');
        }

        $roles = $this->rolesDisponibles($roleGestionnaire);

        $donnees = $request->validate([
            'nom_complet' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('utilisateurs', 'email')->ignore($personnel->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(array_keys($roles))],
        ]);

        $personnel->nom_complet = $donnees['nom_complet'];
        $personnel->email = $donnees['email'];

        if (!empty($donnees['password'])) {
            $personnel->password = $donnees['password'];
        }

        $personnel->save();

        $etablissement->utilisateurs()->updateExistingPivot($personnel->id, [
            'role' => $donnees['role'],
        ]);

        return redirect()
            ->route('personnel.index')
            ->with('success', 'Le membre du personnel a été modifié avec succès.');
    }

    /**
     * Désactiver l'accès d'un membre du personnel.
     * Le compte utilisateur n'est pas supprimé.
     */
    public function destroy(string $id)
    {
        $etablissement = $this->etablissementActif();
        $roleGestionnaire = $this->roleGestionnaire($etablissement);

        $personnel = $etablissement->personnel()
            ->where('utilisateurs.id', $id)
            ->firstOrFail();

        abort_if(
            (int) $personnel->id === (int) auth()->id(),
            403,
            'Vous ne pouvez pas désactiver votre propre compte.'
        );

        if (
            $roleGestionnaire === 'administrateur'
            && $personnel->pivot->role === 'administrateur'
        ) {
            abort(403, 'Vous ne pouvez pas désactiver ce compte.');
        }

        $etablissement->utilisateurs()->updateExistingPivot($personnel->id, [
            'est_actif' => false,
        ]);

        return redirect()
            ->route('personnel.index')
            ->with('success', 'L’accès de ce membre du personnel a été désactivé.');
    }
}
