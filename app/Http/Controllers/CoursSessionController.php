<?php

namespace App\Http\Controllers;

use App\Models\CoursSession;
use App\Models\Ecole;
use App\Models\Cours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CoursSessionController extends Controller
{
    /**
     * Affiche la liste des sessions
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Query de base avec les relations
        $query = CoursSession::with(['ecole', 'cours', 'cours.inscriptions'])
            ->withCount('cours');
        
        // Filtre par école pour admin
        if ($user->role !== 'superadmin') {
            $query->where('ecole_id', $user->ecole_id);
        }
        
        // Filtres supplémentaires
        if ($request->filled('ecole_id') && $request->ecole_id !== 'all') {
            $query->where('ecole_id', $request->ecole_id);
        }
        
        if ($request->filled('annee') && $request->annee !== 'all') {
            $query->whereYear('date_debut', $request->annee)
                  ->orWhereYear('date_fin', $request->annee);
        }
        
        if ($request->filled('saison') && $request->saison !== 'all') {
            $query->where('nom', 'like', '%' . ucfirst($request->saison) . '%');
        }
        
        // Tri
        $sortField = $request->get('sort', 'date_debut');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $sessions = $query->paginate(10);
        
        // Données pour filtres
        $ecoles = $user->role === 'superadmin' 
            ? Ecole::orderBy('nom')->get() 
            : collect();
        
        return view('cours.sessions.index', compact('sessions', 'ecoles'));
    }

    /**
     * Affiche le formulaire de création d'une session
     */
    public function create()
    {
        return view('cours.sessions.create');
    }

    /**
     * Enregistre une nouvelle session
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'nom' => 'required|string|max:255',
            'mois' => 'nullable|string|max:50',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'description' => 'nullable|string',
            'activer_inscriptions' => 'nullable|boolean',
            'visible_public' => 'nullable|boolean',
            'date_limite_inscription' => 'nullable|date',
            'couleur' => 'nullable|string|max:7',
        ]);
        
        $session = new CoursSession();
        $session->nom = $request->nom;
        $session->mois = $request->mois;
        $session->date_debut = $request->date_debut;
        $session->date_fin = $request->date_fin;
        $session->description = $request->description;
        $session->inscriptions_actives = $request->activer_inscriptions ?? true;
        $session->visible = $request->visible_public ?? true;
        $session->date_limite_inscription = $request->date_limite_inscription;
        $session->couleur = $request->couleur;
        $session->ecole_id = $user->ecole_id;
        $session->save();
        
        Log::info("Session créée : {$session->nom} par user #{$user->id}");
        
        return redirect()->route('cours.sessions.index')->with('success', 'Session créée avec succès.');
    }

    /**
     * Affiche les détails d'une session
     */
    public function show(CoursSession $session)
    {
        $user = Auth::user();
        
        if ($user->role !== 'superadmin' && $session->ecole_id !== $user->ecole_id) {
            abort(403);
        }
        
        $session->load(['ecole', 'cours', 'cours.inscriptions']);
        
        // Données statistiques
        $totalInscrits = $session->cours->sum(function($cours) {
            return $cours->inscriptions->count();
        });
        
        $totalPlaces = $session->cours->sum('places_max');
        $tauxRemplissage = $totalPlaces > 0 ? round(($totalInscrits / $totalPlaces) * 100) : 0;
        
        return view('cours.sessions.show', compact('session', 'totalInscrits', 'tauxRemplissage'));
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(CoursSession $session)
    {
        $user = Auth::user();
        
        if ($user->role !== 'superadmin' && $session->ecole_id !== $user->ecole_id) {
            abort(403);
        }
        
        return view('cours.sessions.edit', compact('session'));
    }

    /**
     * Met à jour une session
     */
    public function update(Request $request, CoursSession $session)
    {
        $user = Auth::user();
        
        if ($user->role !== 'superadmin' && $session->ecole_id !== $user->ecole_id) {
            abort(403);
        }
        
        $request->validate([
            'nom' => 'required|string|max:255',
            'mois' => 'nullable|string|max:50',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'description' => 'nullable|string',
            'activer_inscriptions' => 'nullable|boolean',
            'visible_public' => 'nullable|boolean',
            'date_limite_inscription' => 'nullable|date',
            'couleur' => 'nullable|string|max:7',
        ]);
        
        $session->update([
            'nom' => $request->nom,
            'mois' => $request->mois,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'description' => $request->description,
            'inscriptions_actives' => $request->activer_inscriptions ?? true,
            'visible' => $request->visible_public ?? true,
            'date_limite_inscription' => $request->date_limite_inscription,
            'couleur' => $request->couleur,
        ]);
        
        Log::info("Session modifiée : {$session->nom} par user #{$user->id}");
        
        return redirect()->route('cours.sessions.index')->with('success', 'Session modifiée avec succès.');
    }

    /**
     * Supprime une session
     */
    public function destroy(CoursSession $session)
    {
        $user = Auth::user();
        
        if ($user->role !== 'superadmin' && $session->ecole_id !== $user->ecole_id) {
            abort(403);
        }
        
        // Vérifier si des cours sont liés
        if ($session->cours()->exists()) {
            return back()->with('error', 'Impossible de supprimer cette session car elle contient des cours.');
        }
        
        $session->delete();
        
        Log::info("Session supprimée : {$session->nom} par user #{$user->id}");
        
        return redirect()->route('cours.sessions.index')->with('success', 'Session supprimée avec succès.');
    }

    /**
     * Génère automatiquement les sessions pour une année
     */
    public function generateSessions(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'year' => 'required|integer|min:2020|max:2040',
            'sessions' => 'required|array|min:1',
            'sessions.*' => 'in:hiver,printemps,ete,automne',
        ]);
        
        $year = $request->year;
        $ecoleId = $user->ecole_id;
        $sessionsCreees = 0;
        
        // Définition des sessions standards
        $sessionsData = [
            'hiver' => [
                'nom' => "Hiver $year",
                'mois' => 'Jan-Mar',
                'date_debut' => "$year-01-01",
                'date_fin' => "$year-03-31",
            ],
            'printemps' => [
                'nom' => "Printemps $year",
                'mois' => 'Avr-Juin',
                'date_debut' => "$year-04-01",
                'date_fin' => "$year-06-30",
            ],
            'ete' => [
                'nom' => "Été $year",
                'mois' => 'Juil-Sep',
                'date_debut' => "$year-07-01",
                'date_fin' => "$year-09-30",
            ],
            'automne' => [
                'nom' => "Automne $year",
                'mois' => 'Oct-Déc',
                'date_debut' => "$year-10-01",
                'date_fin' => "$year-12-31",
            ],
        ];
        
        foreach ($request->sessions as $session) {
            // Vérifier si cette session existe déjà
            $sessionExiste = CoursSession::where('ecole_id', $ecoleId)
                ->where('nom', $sessionsData[$session]['nom'])
                ->exists();
                
            if (!$sessionExiste) {
                // Créer la session
                CoursSession::create([
                    'ecole_id' => $ecoleId,
                    'nom' => $sessionsData[$session]['nom'],
                    'mois' => $sessionsData[$session]['mois'],
                    'date_debut' => $sessionsData[$session]['date_debut'],
                    'date_fin' => $sessionsData[$session]['date_fin'],
                    'inscriptions_actives' => true,
                    'visible' => true,
                    'couleur' => '#17a2b8',
                ]);
                
                $sessionsCreees++;
            }
        }
        
        Log::info("$sessionsCreees sessions générées automatiquement pour l'année $year par user #{$user->id}");
        
        return redirect()->route('cours.sessions.index')
            ->with('success', "$sessionsCreees session(s) générée(s) avec succès pour l'année $year.");
    }
}
