<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\CoursSessionController;
use App\Http\Controllers\ReinscriptionController;
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

// =============================================
// ROUTES PUBLIQUES
// =============================================
Route::get('/', fn() => redirect()->route('login'));
Route::view('/politique-confidentialite', 'politique')->name('politique');

// Activation des comptes
Route::get('/activer-compte', [ActivationController::class, 'index'])->name('activation.index');
Route::post('/activer-compte', [ActivationController::class, 'store'])->name('activation.store');

// =============================================
// DASHBOARD SÉCURISÉ (Email vérifié)
// =============================================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// =============================================
// ROUTES PROTÉGÉES PAR AUTHENTIFICATION  
// =============================================
Route::middleware(['auth'])->group(function () {

    // -------------------------------------------
    // PROFIL MEMBRE - Mon Compte
    // -------------------------------------------
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

    // -------------------------------------------
    // GESTION DES MEMBRES
    // -------------------------------------------
    Route::resource('membres', MembresController::class)->names('membres');
    Route::get('/membres/attente', [MembresController::class, 'attente'])->name('membres.attente');
    Route::post('/membres/{id}/approuver', [MembresController::class, 'approuver'])
        ->whereNumber('id')->name('membres.approuver');

    // Séminaires - Association membre
    Route::post('/membres/{membre}/seminaire', [MembresController::class, 'ajouterSeminaire'])
        ->name('membres.seminaire.inscrire');
    Route::delete('/membres/{membre}/seminaire/{seminaire}', [MembresController::class, 'retirerSeminaire'])
        ->name('membres.seminaire.retirer');

    // Ceintures - Association membre
    Route::post('/membres/{membre}/ceinture', [MembresController::class, 'ajouterCeinture'])
        ->name('membres.ceinture.ajouter');
    Route::delete('/membres/{membre}/ceinture/{ceinture}', [MembresController::class, 'retirerCeinture'])
        ->name('membres.ceinture.retirer');

    // -------------------------------------------
    // GESTION DES COURS ET SESSIONS
    // -------------------------------------------
    Route::prefix('cours')->name('cours.')->group(function () {
        
        // Routes principales pour les cours
        Route::get('/', [CoursController::class, 'index'])->name('index');
        Route::get('/create', [CoursController::class, 'create'])->name('create');
        Route::post('/', [CoursController::class, 'store'])->name('store');
        Route::get('/{cours}', [CoursController::class, 'show'])->name('show');
        Route::get('/{cours}/edit', [CoursController::class, 'edit'])->name('edit');
        Route::put('/{cours}', [CoursController::class, 'update'])->name('update');
        Route::delete('/{cours}', [CoursController::class, 'destroy'])->name('destroy');

        // Duplication de cours
        Route::post('/{cours}/duplicate', [CoursController::class, 'duplicate'])->name('duplicate');
        
        // Gestion des inscriptions aux cours
        Route::get('/{cours}/inscriptions', [CoursController::class, 'inscriptions'])->name('inscriptions');
        Route::post('/{cours}/inscriptions', [CoursController::class, 'storeInscription'])->name('inscriptions.store');
        Route::delete('/{cours}/inscriptions/{inscription}', [CoursController::class, 'destroyInscription'])->name('inscriptions.destroy');
        Route::patch('/inscriptions/{inscription}/statut', [CoursController::class, 'updateInscriptionStatut'])->name('inscriptions.statut');
        
        // Réinscription automatique
        Route::post('/{cours}/reinscription-auto', [CoursController::class, 'reinscriptionAuto'])->name('reinscription.auto');
        
        // -------------------------------------------
        // SOUS-MODULE : SESSIONS
        // -------------------------------------------
        Route::prefix('sessions')->name('sessions.')->group(function () {
            // CRUD Sessions
            Route::get('/', [CoursSessionController::class, 'index'])->name('index');
            Route::get('/create', [CoursSessionController::class, 'create'])->name('create');
            Route::post('/', [CoursSessionController::class, 'store'])->name('store');
            Route::get('/{session}', [CoursSessionController::class, 'show'])->name('show');
            Route::get('/{session}/edit', [CoursSessionController::class, 'edit'])->name('edit');
            Route::put('/{session}', [CoursSessionController::class, 'update'])->name('update');
            Route::delete('/{session}', [CoursSessionController::class, 'destroy'])->name('destroy');
            
            // Génération automatique des sessions
            Route::post('/generate', [CoursSessionController::class, 'generateSessions'])->name('generate');
            
            // 🆕 NOUVELLES FONCTIONNALITÉS - Duplication et Réinscriptions
            Route::post('/{session}/dupliquer', [CoursSessionController::class, 'dupliquer'])->name('dupliquer');
            Route::patch('/{session}/activer-reinscriptions', [CoursSessionController::class, 'activerReinscriptions'])->name('activer-reinscriptions');
            Route::patch('/{session}/fermer-reinscriptions', [CoursSessionController::class, 'fermerReinscriptions'])->name('fermer-reinscriptions');
        });
    });

    // -------------------------------------------
    // MODULE RÉINSCRIPTION MEMBRE
    // -------------------------------------------
    Route::prefix('reinscription')->name('reinscription.')->group(function () {
        Route::get('/', [ReinscriptionController::class, 'index'])->name('index');
        Route::post('/confirmer', [ReinscriptionController::class, 'confirmer'])->name('confirmer');
        Route::post('/annuler', [ReinscriptionController::class, 'annuler'])->name('annuler');
    });
    
    // -------------------------------------------
    // GESTION DES ÉCOLES
    // -------------------------------------------
    Route::resource('ecoles', EcolesController::class)->names('ecoles');
    Route::put('ecoles/{ecole}/toggle-status', [EcolesController::class, 'toggleStatus'])->name('ecoles.toggle-status');

    // -------------------------------------------
    // GESTION DES PRÉSENCES
    // -------------------------------------------
    
    // Présences - CRUD Administratif
    Route::resource('presences', PresencesController::class)->names('presences');

    // Présences Quotidiennes - Interface Instructeur
    Route::prefix('quotidien')->name('quotidien.')->group(function () {
        Route::get('/mes-cours', [PresenceInstantaneeController::class, 'dashboard'])->name('dashboard');
        Route::get('/cours/{cours}/presences', [PresenceInstantaneeController::class, 'prendre'])->name('prendre');
        Route::post('/cours/{cours}/presences', [PresenceInstantaneeController::class, 'enregistrer'])->name('enregistrer');
        Route::get('/cours/{cours}/rapport', [PresenceInstantaneeController::class, 'voir'])->name('voir');
    });

    // -------------------------------------------
    // AUTRES MODULES
    // -------------------------------------------
    
    // Portes ouvertes
    Route::resource('portes-ouvertes', PortesOuvertesController::class)->names('portes-ouvertes');
    
    // Journées portes ouvertes
    Route::resource('journees-portes-ouvertes', JourneePortesOuvertesController::class)
        ->names('journees-portes-ouvertes');

    // Séminaires
    Route::resource('seminaires', SeminaireController::class)->names('seminaires');

    // -------------------------------------------
    // EXPORTS (PDF / Excel)
    // -------------------------------------------
    Route::prefix('export')->name('export.')->group(function () {
        Route::get('/membres/excel', [ExportController::class, 'exportMembresExcel'])->name('membres.excel');
        Route::get('/membres/pdf', [ExportController::class, 'exportMembresPdf'])->name('membres.pdf');
    });

    // -------------------------------------------
    // PROFIL UTILISATEUR
    // -------------------------------------------
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // -------------------------------------------
    // GESTION DES UTILISATEURS (Admin & SuperAdmin)
    // -------------------------------------------
    Route::middleware(['checkrole:admin,superadmin'])->prefix('users')->name('users.')->group(function () {
        // CRUD Utilisateurs
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::patch('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        
        // Fonctions avancées utilisateurs
        Route::get('/promote', [UserController::class, 'promote'])->name('promote');
        Route::post('/promote', [UserController::class, 'promoteStore'])->name('promote.store');
        Route::put('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::put('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
    });

    // -------------------------------------------
    // ROUTE DE TEST (Middleware)
    // -------------------------------------------
    Route::get('/test-checkrole', fn() => "✅ Middleware 'checkrole' chargé et fonctionnel.")
        ->middleware('checkrole:admin,superladmin');
});

// =============================================
// ROUTE DE TEST TEMPORAIRE (À SUPPRIMER EN PROD)
// =============================================
Route::get('/test-login', function() {
    $user = \App\Models\User::where('email', 'test@test.ca')->first();
    if ($user && \Hash::check('AdminPass123', $user->password)) {
        \Auth::login($user);
        return redirect('/dashboard');
    }
    return "Échec du test de connexion";
});

// =============================================
// ROUTES D'AUTHENTIFICATION (Laravel Breeze/Fortify)
// =============================================
require __DIR__.'/auth.php';
