<?php

namespace App\Http\Controllers;

use App\Mail\CodeVerificationInscription;
use App\Models\InscriptionEnAttente;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Etablissement;
use App\Models\FormuleAbonnement;
use App\Models\Abonnement;
use Throwable;

class InscriptionController extends Controller
{
    /**
     * Enregistrer une demande d'inscription
     * et envoyer le code de vérification.
     */
    public function store(Request $request)
    {
        // Vérifier si une inscription en attente existe déjà.
        $inscriptionExistante = InscriptionEnAttente::where(
            'email',
            Str::lower(trim($request->input('email', '')))
        )->first();

        // Validation des informations.
        $donnees = $request->validate([
            'type_etablissement_id' => [
                'required',
                'exists:types_etablissements,id',
            ],

            'nom_etablissement' => [
                'required',
                'string',
                'max:255',
            ],

            'pays' => [
                'required',
                'string',
                'max:100',
            ],

            'nom_complet' => [
                'required',
                'string',
                'max:255',
            ],

            'telephone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('utilisateurs', 'email'),
                Rule::unique('inscriptions_en_attente', 'email')
                    ->ignore($inscriptionExistante?->id),
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'conditions' => [
                'required',
                'accepted',
            ],
        ]);

        // Générer un code à six chiffres.
        $code = (string) random_int(100000, 999999);

        // Préparer les données de l'inscription.
        // Préparer les données de l'inscription.
        unset($donnees['conditions']);

        $donnees['email'] = Str::lower(trim($donnees['email']));
        $donnees['code_verification'] = $code;
        $donnees['code_expire_le'] = now()->addMinutes(10);
        $donnees['renvoi_possible_le'] = now()->addSeconds(60);
        $donnees['tentatives'] = 0;

        // Créer ou actualiser l'inscription en attente.
        $inscription = InscriptionEnAttente::updateOrCreate(
            ['email' => $donnees['email']],
            $donnees
        );

        try {
            // Envoyer le code par e-mail via Brevo.
            Mail::to($inscription->email)->send(
                new CodeVerificationInscription(
                    $inscription->nom_complet,
                    $code
                )
            );
        } catch (Throwable $exception) {
            Log::error(
                'Erreur envoi du code de vérification GEST-HOTEL',
                [
                    'email' => $inscription->email,
                    'erreur' => $exception->getMessage(),
                ]
            );

            return back()
                ->withInput($request->except([
                    'password',
                    'password_confirmation',
                ]))
                ->withErrors([
                    'email' => "Impossible d'envoyer le code de vérification. Veuillez réessayer.",
                ]);
        }

        return redirect()
            ->route('inscription.verification')
            ->with('email', $inscription->email)
            ->with('success', 'Un code de vérification a été envoyé à votre adresse e-mail.');
    }

    public function verifier(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ], [
            'code.required' => 'Veuillez saisir le code reçu par e-mail.',
            'code.digits' => 'Le code doit contenir exactement 6 chiffres.',
        ]);

        $email = session('email') ?? $request->input('email');

        if (!$email) {
            return redirect()
                ->route('inscription')
                ->withErrors([
                    'email' => 'Votre session de vérification a expiré. Veuillez recommencer.',
                ]);
        }

        $inscription = InscriptionEnAttente::where('email', $email)->first();

        if (!$inscription) {
            return redirect()
                ->route('inscription')
                ->withErrors([
                    'email' => 'Aucune inscription en attente ne correspond à cette adresse.',
                ]);
        }

        if (now()->greaterThan($inscription->code_expire_le)) {
            return back()->withErrors([
                'code' => 'Ce code a expiré. Veuillez demander un nouveau code.',
            ]);
        }

        if ($inscription->tentatives >= 5) {
            return back()->withErrors([
                'code' => 'Vous avez dépassé le nombre de tentatives autorisées.',
            ]);
        }

        if (!Hash::check($request->code, $inscription->code_verification)) {
            $inscription->increment('tentatives');

            return back()->withErrors([
                'code' => 'Le code de vérification est incorrect.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Code correct : création du compte et de l'établissement
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($inscription) {

            $utilisateur = Utilisateur::create([
                'nom_complet' => $inscription->nom_complet,
                'email' => $inscription->email,
                'password' => $inscription->password,
                'email_verified_at' => now(),
            ]);

            $slug = Str::slug($inscription->nom_etablissement);

            $etablissement = Etablissement::create([
                'type_etablissement_id' => $inscription->type_etablissement_id,
                'nom' => $inscription->nom_etablissement,
                'slug' => $slug . '-' . Str::lower(Str::random(6)),
                'email' => $inscription->email,
                'telephone' => $inscription->telephone,
                'pays' => $inscription->pays,
                'devise' => 'XOF',
                'fuseau_horaire' => 'Africa/Porto-Novo',
                'statut' => 'actif',
            ]);

            $etablissement->utilisateurs()->attach($utilisateur->id, [
                'role' => 'proprietaire',
                'est_actif' => true,
            ]);

            $formule = FormuleAbonnement::where('slug', 'essai-gratuit')
                ->where('est_actif', true)
                ->firstOrFail();

            $debutEssai = now();
            $finEssai = $debutEssai->copy()->addDays($formule->duree_essai_jours);

            Abonnement::create([
                'etablissement_id' => $etablissement->id,
                'formule_abonnement_id' => $formule->id,
                'statut' => 'en_essai',
                'date_debut' => $debutEssai,
                'date_fin_essai' => $finEssai,
                'date_debut_periode' => $debutEssai,
                'date_fin_periode' => $finEssai,
                'renouvellement_automatique' => false,
                'montant' => 0,
                'devise' => 'XOF',
            ]);

            $inscription->delete();

            session([
                'etablissement_actif' => $etablissement->id,
            ]);

            auth()->login($utilisateur);
        });

        return redirect()
            ->route('dashboard')
            ->with('success', 'Votre adresse e-mail est vérifiée. Bienvenue sur GEST-HOTEL !');
    }
}
