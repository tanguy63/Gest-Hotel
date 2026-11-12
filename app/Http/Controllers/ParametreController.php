<?php

namespace App\Http\Controllers;

use App\Models\Etablissement;
use App\Models\TypeEtablissement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ParametreController extends Controller
{
    /**
     * Récupérer l'établissement actif de l'utilisateur.
     */
    private function etablissementActif(): Etablissement
    {
        $utilisateur = auth()->user();

        $etablissement = $utilisateur->etablissements()
            ->wherePivot('est_actif', true)
            ->where(
                'etablissements.id',
                session('etablissement_actif')
            )
            ->first();

        abort_unless(
            $etablissement,
            403,
            'Aucun établissement actif autorisé.'
        );

        return $etablissement;
    }

    /**
     * Récupérer le rôle de l'utilisateur.
     */
    private function roleUtilisateur(Etablissement $etablissement): string
    {
        return (string) $etablissement->utilisateurs()
            ->where('utilisateurs.id', auth()->id())
            ->first()?->pivot?->role;
    }

    /**
     * Afficher les paramètres.
     */
    public function index()
    {
        $etablissement = $this->etablissementActif();

        $role = $this->roleUtilisateur($etablissement);

        abort_unless(
            in_array($role, ['proprietaire', 'administrateur'], true),
            403,
            'Vous ne pouvez pas accéder aux paramètres.'
        );

        $typesEtablissements = TypeEtablissement::where('est_actif', true)
            ->orderBy('nom')
            ->get();

        $estProprietaire = $role === 'proprietaire';

        return view('parametres.index', compact(
            'etablissement',
            'typesEtablissements',
            'estProprietaire'
        ));
    }

    /**
     * Modifier les paramètres.
     */
    public function update(Request $request)
    {
        $etablissement = $this->etablissementActif();

        $role = $this->roleUtilisateur($etablissement);

        abort_unless(
            in_array($role, ['proprietaire', 'administrateur'], true),
            403,
            'Vous ne pouvez pas modifier ces paramètres.'
        );

        $estProprietaire = $role === 'proprietaire';

        // Informations modifiables par les deux rôles.
        $regles = [
            'email' => ['nullable', 'email', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'ville' => ['nullable', 'string', 'max:100'],
            'pays' => ['required', 'string', 'max:100'],
        ];

        // Informations réservées au propriétaire.
        if ($estProprietaire) {
            $regles = array_merge($regles, [
                'nom' => ['required', 'string', 'max:255'],

                'type_etablissement_id' => [
                    'required',
                    'integer',
                    Rule::exists('types_etablissements', 'id')
                        ->where('est_actif', true),
                ],

                'devise' => [
                    'required',
                    'string',
                    'size:3',
                ],

                'fuseau_horaire' => [
                    'required',
                    'timezone',
                ],
            ]);
        }

        $donnees = $request->validate($regles);

        // L'administrateur ne peut modifier que les coordonnées.
        if ($estProprietaire) {
            $etablissement->update($donnees);
        } else {
            $etablissement->update([
                'email' => $donnees['email'] ?? null,
                'telephone' => $donnees['telephone'] ?? null,
                'adresse' => $donnees['adresse'] ?? null,
                'ville' => $donnees['ville'] ?? null,
                'pays' => $donnees['pays'],
            ]);
        }

        return redirect()
            ->route('parametres.index')
            ->with('success', 'Les paramètres ont été enregistrés avec succès.');
    }
}
