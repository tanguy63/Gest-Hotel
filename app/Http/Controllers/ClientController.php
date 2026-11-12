<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Etablissement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
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
        $utilisateur = $etablissement->utilisateurs()
            ->where('utilisateurs.id', auth()->id())
            ->wherePivot('est_actif', true)
            ->first();

        abort_unless($utilisateur, 403, 'Accès non autorisé.');

        return $utilisateur->pivot->role;
    }

    /**
     * Autoriser la consultation des clients.
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
            'Vous n\'êtes pas autorisé à consulter les clients.'
        );
    }

    /**
     * Autoriser la gestion des clients.
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
            'Vous n\'êtes pas autorisé à gérer les clients.'
        );
    }

    /**
     * Afficher la liste des clients.
     */
    public function index(Request $request)
    {
        $etablissement = $this->etablissementActif();

        $this->autoriserConsultation($etablissement);

        $query = $etablissement->clients();

        if ($request->filled('recherche')) {
            $recherche = $request->recherche;

            $query->where(function ($q) use ($recherche) {
                $q->where('nom', 'like', "%{$recherche}%")
                    ->orWhere('prenoms', 'like', "%{$recherche}%")
                    ->orWhere('raison_sociale', 'like', "%{$recherche}%")
                    ->orWhere('telephone', 'like', "%{$recherche}%")
                    ->orWhere('email', 'like', "%{$recherche}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $clients = $query
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('clients.index', compact('clients'));
    }

    /**
     * Afficher le formulaire de création.
     */
    public function create()
    {
        $etablissement = $this->etablissementActif();

        $this->autoriserGestion($etablissement);

        return view('clients.create');
    }

    /**
     * Enregistrer un nouveau client.
     */
    public function store(Request $request)
    {
        $etablissement = $this->etablissementActif();

        $this->autoriserGestion($etablissement);

        $validated = $request->validate([
            'type' => [
                'required',
                Rule::in(['particulier', 'entreprise']),
            ],

            'nom' => [
                'nullable',
                'required_if:type,particulier',
                'string',
                'max:255',
            ],

            'prenoms' => [
                'nullable',
                'required_if:type,particulier',
                'string',
                'max:255',
            ],

            'raison_sociale' => [
                'nullable',
                'required_if:type,entreprise',
                'string',
                'max:255',
            ],

            'telephone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'adresse' => ['nullable', 'string'],
            'ville' => ['nullable', 'string', 'max:255'],
            'pays' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $etablissement->clients()->create($validated);

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client enregistré avec succès.');
    }

    /**
     * Afficher les informations d'un client.
     */
    public function show(Client $client)
    {
        $etablissement = $this->etablissementActif();

        $this->autoriserConsultation($etablissement);

        abort_unless(
            (int) $client->etablissement_id === (int) $etablissement->id,
            404
        );

        return view('clients.show', compact('client'));
    }

    /**
     * Afficher le formulaire de modification.
     */
    public function edit(Client $client)
    {
        $etablissement = $this->etablissementActif();

        $this->autoriserGestion($etablissement);

        abort_unless(
            (int) $client->etablissement_id === (int) $etablissement->id,
            404
        );

        return view('clients.edit', compact('client'));
    }

    /**
     * Mettre à jour un client.
     */
    public function update(Request $request, Client $client)
    {
        $etablissement = $this->etablissementActif();

        $this->autoriserGestion($etablissement);

        abort_unless(
            (int) $client->etablissement_id === (int) $etablissement->id,
            404
        );

        $validated = $request->validate([
            'type' => [
                'required',
                Rule::in(['particulier', 'entreprise']),
            ],

            'nom' => [
                'nullable',
                'required_if:type,particulier',
                'string',
                'max:255',
            ],

            'prenoms' => [
                'nullable',
                'required_if:type,particulier',
                'string',
                'max:255',
            ],

            'raison_sociale' => [
                'nullable',
                'required_if:type,entreprise',
                'string',
                'max:255',
            ],

            'telephone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'adresse' => ['nullable', 'string'],
            'ville' => ['nullable', 'string', 'max:255'],
            'pays' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $client->update($validated);

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Client modifié avec succès.');
    }

    /**
     * Supprimer un client.
     */
    public function destroy(Client $client)
    {
        $etablissement = $this->etablissementActif();

        $this->autoriserGestion($etablissement);

        abort_unless(
            (int) $client->etablissement_id === (int) $etablissement->id,
            404
        );

        $client->delete();

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client supprimé avec succès.');
    }
}
