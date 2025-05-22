<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\CoursSessionController;
use App\Http\Controllers\EcolesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MembresController;
use App\Http\Controllers\PresencesController;
use App\Http\Controllers\PresenceInstantaneeController;
use App\Http\Controllers\PortesOuvertesController;
use App\Http\Controllers\JourneePortesOuvertesController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\ActivationController;
use App\Http\Controllers\SeminaireController;

// ---------------------
// Routes publiques
// ---------------------
Route::get('/', fn() => redirect()->route('login'));
Route::view('/politique-confidentialite', 'politique')->name('politique');
Route::get('/activer-compte', [ActivationController::class, 'index'])->name('activation.index');
Route::post('/activer-compte', [ActivationController::class, 'store'])->name('activation.store');

// ---------------------
// Dashboard sécurisé (email vérifié)
// ---------------------
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// ---------------------
// Routes protégées par authentification
// ---------------------
Route::middleware(['auth'])->group(function () {

    // Fiche personnelle (Mon compte)
    Route::get('/mon-compte', function () {
        $membre = Auth::user()->membre;

        if (!$membre) {
            abort(404, 'Aucune fiche membre liée à ce compte.');
        }

        $ceintures = $membre->ceintures ?? [];
        $seminaires = $membre->seminaires ?? [];
        $cours = $membre->cours ?? [];

        return view('mon-compte', compact('membre', 'ceintures', 'seminaires', 'cours'));
    })->name('mon-compte');

    // Membres
    Route::resource('membres', MembresController::class)->names('membres');
    Route::get('/membres/attente', [MembresController::class, 'attente'])->name('membres.attente');
    Route::post('/membres/{id}/approuver', [MembresController::class, 'approuver'])
        ->whereNumber('id')->name('membres.approuver');

    // Séminaires associés à un membre
    Route::post('/membres/{membre}/seminaire', [MembresController::class, 'ajouterSeminaire'])
        ->name('membres.seminaire.inscrire');
    Route::delete('/membres/{membre}/seminaire/{seminaire}', [MembresController::class, 'retirerSeminaire'])
        ->name('membres.seminaire.retirer');

    // Ceintures associées à un membre
    Route::post('/membres/{membre}/ceinture', [MembresController::class, 'ajouterCeinture'])
        ->name('membres.ceinture.ajouter');
    Route::delete('/membres/{membre}/ceinture/{ceinture}', [MembresController::class, 'retirerCeinture'])
        ->name('membres.ceinture.retirer');

    // Cours et Sessions - Structure complète
    Route::prefix('cours')->name('cours.')->group(function () {
        // Routes principales pour les cours
        Route::get('/', [CoursController::class, 'index'])->name('index');
        Route::get('/create', [CoursController::class, 'create'])->name('create');
        Route::post('/', [CoursController::class, 'store'])->name('store');
        Route::get('/{cours}', [CoursController::class, 'show'])->name('show');
        Route::get('/{cours}/edit', [CoursController::class, 'edit'])->name('edit');
        Route::put('/{cours}', [CoursController::class, 'update'])->name('update');
        Route::delete('/{cours}', [CoursController::class, 'destroy'])->name('destroy');

        // Routes pour la duplication de cours
        Route::post('/{cours}/duplicate', [CoursController::class, 'duplicate'])->name('duplicate');
        
        // Routes pour les inscriptions
        Route::get('/{cours}/inscriptions', [CoursController::class, 'inscriptions'])->name('inscriptions');
        Route::post('/{cours}/inscriptions', [CoursController::class, 'storeInscription'])->name('inscriptions.store');
        Route::delete('/{cours}/inscriptions/{inscription}', [CoursController::class, 'destroyInscription'])->name('inscriptions.destroy');
        Route::patch('/inscriptions/{inscription}/statut', [CoursController::class, 'updateInscriptionStatut'])->name('inscriptions.statut');
        
        // Route pour réinscription automatique
        Route::post('/{cours}/reinscription-auto', [CoursController::class, 'reinscriptionAuto'])->name('reinscription.auto');
        
        // Routes sessions (préfixe sessions)
        Route::prefix('sessions')->name('sessions.')->group(function () {
            Route::get('/', [CoursSessionController::class, 'index'])->name('index');
            Route::get('/create', [CoursSessionController::class, 'create'])->name('create');
            Route::post('/', [CoursSessionController::class, 'store'])->name('store');
            Route::get('/{session}', [CoursSessionController::class, 'show'])->name('show');
            Route::get('/{session}/edit', [CoursSessionController::class, 'edit'])->name('edit');
            Route::put('/{session}', [CoursSessionController::class, 'update'])->name('update');
            Route::delete('/{session}', [CoursSessionController::class, 'destroy'])->name('destroy');
            
            // Route pour génération automatique des sessions
            Route::post('/generate', [CoursSessionController::class, 'generateSessions'])->name('generate');
        });
    });
    
    // Écoles
    Route::resource('ecoles', EcolesController::class)->names('ecoles');
    Route::put('ecoles/{ecole}/toggle-status', [EcolesController::class, 'toggleStatus'])->name('ecoles.toggle-status');

    // Présences - CRUD Administratif
    Route::resource('presences', PresencesController::class)->names('presences');

    // Présences Quotidiennes - Interface Instructeur
    Route::prefix('quotidien')->name('quotidien.')->group(function () {
        Route::get('/mes-cours', [PresenceInstantaneeController::class, 'dashboard'])->name('dashboard');
        Route::get('/cours/{cours}/presences', [PresenceInstantaneeController::class, 'prendre'])->name('prendre');
        Route::post('/cours/{cours}/presences', [PresenceInstantaneeController::class, 'enregistrer'])->name('enregistrer');
        Route::get('/cours/{cours}/rapport', [PresenceInstantaneeController::class, 'voir'])->name('voir');
    });

    // Portes ouvertes
    Route::resource('portes-ouvertes', PortesOuvertesController::class)->names('portes-ouvertes');

    // Séminaires
    Route::resource('seminaires', SeminaireController::class)->names('seminaires');

    // Journées portes ouvertes
    Route::resource('journees-portes-ouvertes', JourneePortesOuvertesController::class)
        ->names('journees-portes-ouvertes');

    // Export PDF / Excel
    Route::get('/export/membres/excel', [ExportController::class, 'exportMembresExcel'])
        ->name('export.membres.excel');
    Route::get('/export/membres/pdf', [ExportController::class, 'exportMembresPdf'])
        ->name('export.membres.pdf');

    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Gestion des utilisateurs (admin & superadmin)
    Route::middleware(['checkrole:admin,superadmin'])->prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/promote', [UserController::class, 'promote'])->name('promote');
        Route::post('/promote', [UserController::class, 'promoteStore'])->name('promote.store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::patch('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        
        // Routes manquantes - AJOUT ICI
        Route::put('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::put('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
    });

    // Middleware de test
    Route::get('/test-checkrole', fn() => "✅ Middleware 'checkrole' chargé et fonctionnel.")
        ->middleware('checkrole:admin,superladmin');
});


// Route de test temporaire
Route::get('/test-login', function() {
    $user = \App\Models\User::where('email', 'test@test.ca')->first();
    if ($user && \Hash::check('AdminPass123', $user->password)) {
        \Auth::login($user);
        return redirect('/dashboard');
    }
    return "Échec du test de connexion";
});
// Auth Laravel Breeze / Fortify
require __DIR__.'/auth.php';
