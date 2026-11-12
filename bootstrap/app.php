<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\VerifierEtablissementActif;
use App\Http\Middleware\VerifierSuperAdministrateur;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Reconnaître les proxys de l'hébergeur et le HTTPS
        $middleware->trustProxies(at: '*');

        // Middlewares personnalisés
        $middleware->alias([
            'etablissement.actif' => VerifierEtablissementActif::class,
            'super.admin' => VerifierSuperAdministrateur::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();