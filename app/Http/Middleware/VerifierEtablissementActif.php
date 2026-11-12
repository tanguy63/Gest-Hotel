<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifierEtablissementActif
{
    public function handle(Request $request, Closure $next): Response
    {
        $utilisateur = $request->user();

        if (!$utilisateur) {
            return redirect()->route('login');
        }

        $etablissementId = session('etablissement_actif');

        // Vérifier que l'utilisateur appartient à cet établissement
        $etablissement = $utilisateur->etablissements()
            ->wherePivot('est_actif', true)
            ->where('etablissements.id', $etablissementId)
            ->first();

        if (!$etablissement) {
            // Ne jamais conserver un établissement non autorisé
            session()->forget('etablissement_actif');

            // Récupérer les établissements accessibles
            $etablissements = $utilisateur->etablissements()
                ->wherePivot('est_actif', true)
                ->get();

            if ($etablissements->isEmpty()) {
                abort(403, 'Aucun établissement actif ne vous est attribué.');
            }

            // Pour le moment, sélectionner automatiquement
            // le seul établissement accessible.
            if ($etablissements->count() === 1) {
                session([
                    'etablissement_actif' => $etablissements->first()->id,
                ]);
            } else {
                abort(403, 'Veuillez sélectionner votre établissement.');
            }
        }

        return $next($request);
    }
}