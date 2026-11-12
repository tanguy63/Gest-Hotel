<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifierSuperAdministrateur
{
    public function handle(Request $request, Closure $next): Response
    {
        $utilisateur = $request->user();

        if (!$utilisateur || !$utilisateur->estSuperAdministrateur()) {
            abort(403, 'Accès réservé au Super Administrateur de la plateforme.');
        }

        return $next($request);
    }
}
