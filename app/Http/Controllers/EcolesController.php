<?php

namespace App\Http\Controllers;

use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB; // Ajouté pour utiliser DB

class EcolesController extends Controller
{
    /**
     * Affiche la liste des écoles.
     */
    public function index()
    {
        // Vérifier si l'utilisateur est superadmin (voit toutes les écoles)
        // ou admin/instructeur (voit seulement son école)
        if (Auth::user()->isSuperAdmin()) {
            $ecoles = Ecole::withCount(['membres', 'cours', 'sessions'])
                          ->orderBy('nom')
                          ->paginate(9);
                          
            // Statistiques globales pour les superadmins - CORRIGÉ
            $totalEcoles = Ecole::count();
            
            // Utilisation de DB::table pour compter directement dans les tables - CORRIGÉ
            $totalMembres = DB::table('membres')->count();
            $totalCours = DB::table('cours')->count();
            $totalSessions = DB::table('cours_sessions')->count();
            
            return view('ecoles.index', compact('ecoles', 'totalEcoles', 'totalMembres', 'totalCours', 'totalSessions'));
        } else {
            // Pour les admins/instructeurs, on récupère seulement leur école
            $ecoles = Ecole::withCount(['membres', 'cours', 'sessions'])
                          ->where('id', Auth::user()->ecole_id)
                          ->paginate(9);
                          
            return view('ecoles.index', compact('ecoles'));
        }
    }

    /**
     * Affiche le formulaire de création d'une école.
     */
    public function create()
    {
        // Seul le superadmin peut créer une école
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('ecoles.index')
                           ->with('error', 'Vous n\'avez pas les droits pour créer une école.');
        }
        
        return view('ecoles.create');
    }

    /**
     * Enregistre une nouvelle école dans la base de données.
     */
    public function store(Request $request)
    {
        // Seul le superadmin peut créer une école
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('ecoles.index')
                           ->with('error', 'Vous n\'avez pas les droits pour créer une école.');
        }
        
        // Validation des données
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'responsable' => 'nullable|string|max:255',
            'active' => 'sometimes|boolean',
        ]);
        
        // Gérer le champ active qui vient d'une checkbox
        $validatedData['active'] = $request->has('active');
        
        // Créer l'école
        $ecole = Ecole::create($validatedData);
        
        return redirect()->route('ecoles.show', $ecole->id)
                       ->with('success', 'École créée avec succès!');
    }

    /**
     * Affiche les détails d'une école spécifique.
     */
    public function show(Ecole $ecole)
    {
        // Vérifier si l'utilisateur a le droit de voir cette école
        if (Auth::user()->isSuperAdmin() || Auth::user()->ecole_id == $ecole->id) {
            // Charger les relations pour les statistiques
            $ecole->loadCount(['membres', 'cours', 'sessions', 'users']);
            
            // Charger les utilisateurs associés
            $ecole->load('users');
            
            return view('ecoles.show', compact('ecole'));
        }
        
        return redirect()->route('ecoles.index')
                       ->with('error', 'Vous n\'avez pas les droits pour voir cette école.');
    }

    /**
     * Affiche le formulaire d'édition d'une école.
     */
    public function edit(Ecole $ecole)
    {
        // Vérifier si l'utilisateur a le droit d'éditer cette école
        if (Auth::user()->isSuperAdmin() || (Auth::user()->isAdmin() && Auth::user()->ecole_id == $ecole->id)) {
            // Charger les relations pour les statistiques
            $ecole->loadCount(['membres', 'cours', 'sessions', 'users']);
            
            return view('ecoles.edit', compact('ecole'));
        }
        
        return redirect()->route('ecoles.index')
                       ->with('error', 'Vous n\'avez pas les droits pour modifier cette école.');
    }

    /**
     * Met à jour les données d'une école dans la base de données.
     */
    public function update(Request $request, Ecole $ecole)
    {
        // Vérifier si l'utilisateur a le droit d'éditer cette école
        if (!(Auth::user()->isSuperAdmin() || (Auth::user()->isAdmin() && Auth::user()->ecole_id == $ecole->id))) {
            return redirect()->route('ecoles.index')
                           ->with('error', 'Vous n\'avez pas les droits pour modifier cette école.');
        }
        
        // Validation des données
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'responsable' => 'nullable|string|max:255',
            'active' => 'sometimes|boolean',
        ]);
        
        // Gérer le champ active qui vient d'une checkbox
        $validatedData['active'] = $request->has('active');
        
        // Mettre à jour l'école
        $ecole->update($validatedData);
        
        return redirect()->route('ecoles.show', $ecole->id)
                       ->with('success', 'École mise à jour avec succès!');
    }

    /**
     * Supprime une école de la base de données.
     */
    public function destroy(Ecole $ecole)
    {
        // Seul le superadmin peut supprimer une école
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('ecoles.index')
                           ->with('error', 'Vous n\'avez pas les droits pour supprimer une école.');
        }
        
        // Vérifier si l'école a des membres, cours, sessions ou users
        $hasDependencies = $ecole->membres()->count() > 0 || 
                          $ecole->cours()->count() > 0 || 
                          $ecole->sessions()->count() > 0 || 
                          $ecole->users()->count() > 0;
        
        if ($hasDependencies) {
            return redirect()->route('ecoles.edit', $ecole->id)
                           ->with('error', 'Impossible de supprimer cette école car elle contient des membres, cours, sessions ou administrateurs. Veuillez d\'abord les supprimer ou les réaffecter.');
        }
        
        // Supprimer l'école
        $ecole->delete();
        
        return redirect()->route('ecoles.index')
                       ->with('success', 'École supprimée avec succès!');
    }
    
    /**
     * Activer/désactiver une école.
     */
    public function toggleStatus(Ecole $ecole)
    {
        // Vérifier si l'utilisateur a le droit de modifier cette école
        if (!(Auth::user()->isSuperAdmin() || (Auth::user()->isAdmin() && Auth::user()->ecole_id == $ecole->id))) {
            return redirect()->route('ecoles.index')
                           ->with('error', 'Vous n\'avez pas les droits pour modifier cette école.');
        }
        
        // Inverser le statut actif
        $ecole->active = !$ecole->active;
        $ecole->save();
        
        $status = $ecole->active ? 'activée' : 'désactivée';
        
        return redirect()->back()
                       ->with('success', "L'école a été $status avec succès!");
    }
}
