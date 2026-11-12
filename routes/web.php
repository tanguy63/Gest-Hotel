
<?php

use App\Http\Controllers\InscriptionController;
use Illuminate\Support\Facades\Route;
use App\Models\TypeEtablissement;
use App\Http\Controllers\CategorieChambreController;
use App\Http\Controllers\ChambreController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\ParametreController;
use App\Http\Controllers\SuperAdminController;

// ==========================================
// SITE PUBLIC
// ==========================================

Route::get('/', function () {
    return view('welcome');
})->name('home');

// ==========================================
// INSCRIPTION ET VÉRIFICATION E-MAIL
// ==========================================

Route::get('/inscription', function () {
    $typesEtablissements = TypeEtablissement::where('est_actif', true)
        ->orderBy('nom')
        ->get();

    return view('auth.inscription', compact('typesEtablissements'));
})->middleware('guest')->name('inscription');

Route::post('/inscription', [InscriptionController::class, 'store'])
    ->middleware(['guest', 'throttle:5,1'])
    ->name('inscription.store');

Route::get('/inscription/verification', function () {
    return view('auth.verification-inscription', [
        'email' => session('email'),
    ]);
})->middleware('guest')->name('inscription.verification');

// ==========================================
// APPLICATION PRIVÉE
// ==========================================

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'etablissement.actif'])
    ->name('dashboard');

Route::post('/inscription/verification', [InscriptionController::class, 'verifier'])
    ->middleware(['guest', 'throttle:5,1'])
    ->name('inscription.verifier');

Route::post('/inscription/renvoyer-code', [InscriptionController::class, 'renvoyerCode'])
    ->middleware(['guest', 'throttle:3,1'])
    ->name('inscription.renvoyer-code');

Route::middleware(['auth', 'etablissement.actif'])->group(function () {

        // Paramètres de l'établissement
    Route::get('/parametres', [ParametreController::class, 'index'])
        ->name('parametres.index');

    Route::put('/parametres', [ParametreController::class, 'update'])
        ->name('parametres.update');

    Route::patch('personnel/{personnel}/reactiver', [PersonnelController::class, 'reactiver'])
        ->name('personnel.reactiver');

    // Gestion du personnel
    Route::resource('personnel', PersonnelController::class)
        ->except(['show'])
        ->names('personnel');

    Route::resource('categories-chambres', CategorieChambreController::class)
        ->names('categories-chambres');

    Route::resource('chambres', ChambreController::class)
        ->names('chambres');

    Route::resource('clients', ClientController::class);

    Route::resource('reservations', ReservationController::class);

    // Gestion des paiements
    Route::get('/paiements', [PaiementController::class, 'index'])
        ->name('paiements.index');

    Route::post(
        '/reservations/{reservation}/paiements',
        [PaiementController::class, 'store']
    )->name('reservations.paiements.store');

    Route::get(
        '/paiements/{paiement}/recu',
        [PaiementController::class, 'recu']
    )->name('paiements.recu');

    Route::get('/reservations/{reservation}/recu', [ReservationController::class, 'recu'])
    ->name('reservations.recu');

});


// ==========================================
// ESPACE SUPER ADMINISTRATEUR
// ==========================================

Route::middleware(['auth', 'super.admin'])
    ->prefix('super-admin')
    ->name('super-admin.')
    ->group(function () {

        // Tableau de bord et liste des établissements
        Route::get('/', [SuperAdminController::class, 'index'])
            ->name('index');

        // Consultation d'un établissement
        Route::get('/etablissements/{etablissement}', [SuperAdminController::class, 'show'])
            ->name('etablissements.show');

    });
