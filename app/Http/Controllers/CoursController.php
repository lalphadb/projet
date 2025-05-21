<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Ecole;
use App\Models\CoursSession;
use App\Models\Membre;
use App\Models\InscriptionCours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CoursController extends Controller
{
    /**
     * Affiche la liste des cours avec filtres et pagination
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Query de base selon le rôle
        $query = Cours::with(['ecole', 'session', 'inscriptions.membre']);
        
        if ($user->role !== 'superadmin') {
            $query->where('ecole_id', $user->ecole_id);
        }
        
        // Filtres
        if ($request->filled('ecole_id') && $request->ecole_id !== 'all') {
            $query->where('ecole_id', $request->ecole_id);
        }
        
        if ($request->filled('session_id') && $request->session_id !== 'all') {
            $query->where('session_id', $request->session_id);
        }
        
        if ($request->filled('jour') && $request->jour !== 'all') {
            $query->whereJsonContains('jours', $request->jour);
        }
        
        // Tri
        $sortField = $request->get('sort', 'nom');
        $sortDirection = $request->get('direction', 'asc');
        
        if ($sortField === 'session') {
            $query->join('cours_sessions', 'cours.session_id', '=', 'cours_sessions.id')
                  ->orderBy('cours_sessions.nom', $sortDirection)
                  ->select('cours.*');
        } else {
            $query->orderBy($sortField, $sortDirection);
        }
        
        $cours = $query->paginate(15);
        
        // Données pour les filtres
        $ecoles = $user->role === 'superadmin' 
            ? Ecole::orderBy('nom')->get() 
            : collect();
            
        $sessions = $user->role === 'superadmin'
            ? CoursSession::orderBy('nom')->get()
            : CoursSession::where('ecole_id', $user->ecole_id)->orderBy('nom')->get();
        
        return view('cours.index', compact('cours', 'ecoles', 'sessions'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $user = Auth::user();
        
        // Sessions disponibles pour l'école
        $sessions = $user->role === 'superadmin'
            ? CoursSession::orderBy('nom')->get()
            : CoursSession::where('ecole_id', $user->ecole_id)->orderBy('nom')->get();
            
        return view('cours.create', compact('sessions'));
    }

    /**
     * Enregistre un nouveau cours
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'jours' => 'required|array|min:1',
            'jours.*' => 'in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'places_max' => 'required|integer|min:1|max:100',
            'session_id' => 'required|exists:cours_sessions,id',
            'instructeur' => 'nullable|string|max:255',
            'niveau' => 'nullable|in:debutant,intermediaire,avance,tous_niveaux',
            'tarif' => 'nullable|numeric|min:0',
        ]);

        $cours = new Cours();
        $cours->nom = $request->nom;
        $cours->description = $request->description;
        $cours->jours = json_encode($request->jours);
        $cours->heure_debut = $request->heure_debut;
        $cours->heure_fin = $request->heure_fin;
        $cours->places_max = $request->places_max;
        $cours->session_id = $request->session_id;
        $cours->instructeur = $request->instructeur;
        $cours->niveau = $request->niveau;
        $cours->tarif = $request->tarif;
        $cours->ecole_id = $user->role === 'superadmin' 
            ? CoursSession::find($request->session_id)->ecole_id
            : $user->ecole_id;
        $cours->save();

        Log::info("Cours créé : {$cours->nom} par user #{$user->id}");

        return redirect()->route('cours.index')->with('success', 'Cours créé avec succès.');
    }

    /**
     * Affiche les détails d'un cours
     */
    public function show(Cours $cours)
    {
        $user = Auth::user();
        
        if ($user->role !== 'superadmin' && $cours->ecole_id !== $user->ecole_id) {
            abort(403);
        }
        
        $cours->load(['ecole', 'session', 'inscriptions.membre']);
        
        return view('cours.show', compact('cours'));
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(Cours $cours)
    {
        $user = Auth::user();

        if ($user->role !== 'superadmin' && $cours->ecole_id !== $user->ecole_id) {
            abort(403);
        }

        $sessions = $user->role === 'superadmin'
            ? CoursSession::orderBy('nom')->get()
            : CoursSession::where('ecole_id', $user->ecole_id)->orderBy('nom')->get();

        return view('cours.edit', compact('cours', 'sessions'));
    }

    /**
     * Met à jour un cours
     */
    public function update(Request $request, Cours $cours)
    {
        $user = Auth::user();

        if ($user->role !== 'superadmin' && $cours->ecole_id !== $user->ecole_id) {
            abort(403);
        }

        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'jours' => 'required|array|min:1',
            'jours.*' => 'in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'places_max' => 'required|integer|min:' . $cours->inscriptions->count() . '|max:100',
            'session_id' => 'required|exists:cours_sessions,id',
            'instructeur' => 'nullable|string|max:255',
            'niveau' => 'nullable|in:debutant,intermediaire,avance,tous_niveaux',
            'tarif' => 'nullable|numeric|min:0',
            'statut' => 'nullable|in:actif,inactif,complet,annule',
        ]);

        $cours->update([
            'nom' => $request->nom,
            'description' => $request->description,
            'jours' => json_encode($request->jours),
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'places_max' => $request->places_max,
            'session_id' => $request->session_id,
            'instructeur' => $request->instructeur,
            'niveau' => $request->niveau,
            'tarif' => $request->tarif,
            'statut' => $request->statut ?? 'actif',
        ]);

        Log::info("Cours modifié : {$cours->nom} par user #{$user->id}");

        return redirect()->route('cours.index')->with('success', 'Cours modifié avec succès.');
    }

    /**
     * Supprime un cours
     */
    public function destroy(Cours $cours)
    {
        $user = Auth::user();

        if ($user->role !== 'superadmin' && $cours->ecole_id !== $user->ecole_id) {
            abort(403);
        }

        // Supprimer les inscriptions associées
        $cours->inscriptions()->delete();
        $cours->delete();

        Log::info("Cours supprimé : {$cours->nom} par user #{$user->id}");

        return redirect()->route('cours.index')->with('success', 'Cours supprimé avec succès.');
    }

    /**
     * Duplique un cours pour une autre session
     */
    public function duplicate(Request $request, Cours $cours)
    {
        $user = Auth::user();

        if ($user->role !== 'superadmin' && $cours->ecole_id !== $user->ecole_id) {
            abort(403);
        }

        $request->validate([
            'target_session_id' => 'required|exists:cours_sessions,id',
            'copy_inscriptions' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $nouveauCours = $cours->replicate();
            $nouveauCours->session_id = $request->target_session_id;
            $nouveauCours->save();

            // Copier les inscriptions si demandé
            if ($request->copy_inscriptions) {
                foreach ($cours->inscriptions as $inscription) {
                    InscriptionCours::create([
                        'membre_id' => $inscription->membre_id,
                        'cours_id' => $nouveauCours->id,
                        'session_id' => $request->target_session_id,
                        'statut' => 'actif',
                    ]);
                }
            }

            DB::commit();
            Log::info("Cours dupliqué : {$cours->nom} vers session #{$request->target_session_id} par user #{$user->id}");

            return redirect()->route('cours.index')->with('success', 'Cours dupliqué avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la duplication du cours.');
        }
    }

    /**
     * Affiche la page de gestion des inscriptions
     */
    public function inscriptions(Cours $cours)
    {
        $user = Auth::user();

        if ($user->role !== 'superadmin' && $cours->ecole_id !== $user->ecole_id) {
            abort(403);
        }

        $cours->load(['ecole', 'session']);
        
        // Membres inscrits
        $membresInscrits = InscriptionCours::with('membre')
            ->where('cours_id', $cours->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Membres disponibles (non inscrits)
        $membresInscritsIds = $membresInscrits->pluck('membre_id')->toArray();
        
        $membresDisponibles = Membre::where('ecole_id', $cours->ecole_id)
            ->whereNotIn('id', $membresInscritsIds)
            ->where('statut', 'actif')
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        // Session suivante pour réinscription
        $sessionSuivante = CoursSession::where('ecole_id', $cours->ecole_id)
            ->where('date_debut', '>', $cours->session->date_fin)
            ->orderBy('date_debut')
            ->first();

        return view('cours.inscriptions', compact('cours', 'membresInscrits', 'membresDisponibles', 'sessionSuivante'));
    }

    /**
     * Inscrit un membre à un cours
     */
    public function storeInscription(Request $request, Cours $cours)
    {
        $user = Auth::user();

        if ($user->role !== 'superadmin' && $cours->ecole_id !== $user->ecole_id) {
            abort(403);
        }

        $request->validate([
            'membre_id' => 'required|exists:membres,id',
        ]);

        // Vérifier les places disponibles
        if ($cours->inscriptions->count() >= $cours->places_max) {
            return back()->with('error', 'Plus de places disponibles pour ce cours.');
        }

        // Vérifier que le membre n'est pas déjà inscrit
        if (InscriptionCours::where('cours_id', $cours->id)->where('membre_id', $request->membre_id)->exists()) {
            return back()->with('error', 'Ce membre est déjà inscrit à ce cours.');
        }

        InscriptionCours::create([
            'membre_id' => $request->membre_id,
            'cours_id' => $cours->id,
            'session_id' => $cours->session_id,
            'statut' => 'actif',
        ]);

        Log::info("Inscription ajoutée : Membre #{$request->membre_id} au cours #{$cours->id} par user #{$user->id}");

        return back()->with('success', 'Membre inscrit avec succès.');
    }

    /**
     * Désinscrit un membre d'un cours
     */
    public function destroyInscription(Cours $cours, InscriptionCours $inscription)
    {
        $user = Auth::user();

        if ($user->role !== 'superadmin' && $cours->ecole_id !== $user->ecole_id) {
            abort(403);
        }

        $inscription->delete();

        Log::info("Inscription supprimée : Membre #{$inscription->membre_id} du cours #{$cours->id} par user #{$user->id}");

        return back()->with('success', 'Membre désinscrit avec succès.');
    }

    /**
     * Met à jour le statut d'une inscription
     */
    public function updateInscriptionStatut(Request $request, InscriptionCours $inscription)
    {
        $user = Auth::user();

        if ($user->role !== 'superadmin' && $inscription->cours->ecole_id !== $user->ecole_id) {
            abort(403);
        }

        $request->validate([
            'statut' => 'required|in:actif,en_attente,suspendu,annule',
            'raison' => 'nullable|string|max:500',
        ]);

        $inscription->update([
            'statut' => $request->statut,
            'raison_changement' => $request->raison,
        ]);

        Log::info("Statut inscription modifié : #{$inscription->id} -> {$request->statut} par user #{$user->id}");

        return back()->with('success', 'Statut de l\'inscription mis à jour.');
    }

    /**
     * Réinscription automatique pour la session suivante
     */
    public function reinscriptionAuto(Request $request, Cours $cours)
    {
        $user = Auth::user();

        if ($user->role !== 'superadmin' && $cours->ecole_id !== $user->ecole_id) {
            abort(403);
        }

        $request->validate([
            'session_destination_id' => 'required|exists:cours_sessions,id',
            'membres' => 'required|array|min:1',
            'membres.*' => 'exists:membres,id',
        ]);

        DB::beginTransaction();
        try {
            // Trouver ou créer le cours équivalent dans la nouvelle session
            $nouveauCours = Cours::where('nom', $cours->nom)
                ->where('session_id', $request->session_destination_id)
                ->where('jours', $cours->jours)
                ->where('heure_debut', $cours->heure_debut)
                ->where('heure_fin', $cours->heure_fin)
                ->first();

            if (!$nouveauCours) {
                // Créer le cours automatiquement
                $nouveauCours = $cours->replicate();
                $nouveauCours->session_id = $request->session_destination_id;
                $nouveauCours->save();
            }

            // Réinscrire les membres sélectionnés
            $inscriptionsCreees = 0;
            foreach ($request->membres as $membreId) {
                // Vérifier que le membre n'est pas déjà inscrit
                if (!InscriptionCours::where('cours_id', $nouveauCours->id)->where('membre_id', $membreId)->exists()) {
                    InscriptionCours::create([
                        'membre_id' => $membreId,
                        'cours_id' => $nouveauCours->id,
                        'session_id' => $request->session_destination_id,
                        'statut' => 'actif',
                    ]);
                    $inscriptionsCreees++;
                }
            }

            DB::commit();
            Log::info("Réinscription automatique : {$inscriptionsCreees} membres pour cours #{$nouveauCours->id} par user #{$user->id}");

            return back()->with('success', "{$inscriptionsCreees} membre(s) réinscrit(s) avec succès.");
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la réinscription automatique.');
        }
    }
}
