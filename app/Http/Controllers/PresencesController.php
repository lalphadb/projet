<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\Cours;
use App\Models\Membre;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PresencesController extends Controller
{
    /**
     * 📋 Afficher la liste de toutes les présences (Vue administrative)
     */
    public function index(Request $request)
    {
        $query = Presence::with(['membre', 'cours.ecole']);

        // Filtres de recherche
        if ($request->filled('cours_id')) {
            $query->where('cours_id', $request->cours_id);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('date_presence', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_presence', '<=', $request->date_fin);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('membre_nom')) {
            $query->whereHas('membre', function($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->membre_nom . '%')
                  ->orWhere('prenom', 'like', '%' . $request->membre_nom . '%');
            });
        }

        // Tri par défaut : plus récentes d'abord
        $presences = $query->orderBy('date_presence', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->paginate(25);

        // Données pour les filtres
        $cours = Cours::orderBy('nom')->get();
        $statuts = Presence::getStatuts();

        return view('presences.index', compact('presences', 'cours', 'statuts'));
    }

    /**
     * 📝 Afficher le formulaire de création d'une présence
     */
    public function create()
    {
        $cours = Cours::with('ecole')->orderBy('nom')->get();
        $membres = Membre::where('approuve', true)
                        ->orderBy('nom')
                        ->orderBy('prenom')
                        ->get();
        $statuts = Presence::getStatuts();

        return view('presences.create', compact('cours', 'membres', 'statuts'));
    }

    /**
     * 💾 Enregistrer une nouvelle présence
     */
    public function store(Request $request)
    {
        $request->validate([
            'membre_id' => 'required|exists:membres,id',
            'cours_id' => 'required|exists:cours,id',
            'date_presence' => 'required|date',
            'status' => 'required|in:present,absent,retard',
            'commentaire' => 'nullable|string|max:500'
        ]);

        // Vérifier qu'il n'existe pas déjà une présence pour ce membre/cours/date
        $existeDeja = Presence::where([
            'membre_id' => $request->membre_id,
            'cours_id' => $request->cours_id,
            'date_presence' => $request->date_presence
        ])->exists();

        if ($existeDeja) {
            return back()->withErrors([
                'duplicate' => 'Une présence existe déjà pour ce membre à ce cours à cette date.'
            ])->withInput();
        }

        Presence::create([
            'membre_id' => $request->membre_id,
            'cours_id' => $request->cours_id,
            'date_presence' => $request->date_presence,
            'status' => $request->status,
            'commentaire' => $request->commentaire
        ]);

        return redirect()->route('presences.index')
            ->with('success', '✅ Présence enregistrée avec succès');
    }

    /**
     * 👁️ Afficher les détails d'une présence
     */
    public function show(Presence $presence)
    {
        $presence->load(['membre', 'cours.ecole']);
        
        return view('presences.show', compact('presence'));
    }

    /**
     * ✏️ Afficher le formulaire d'édition
     */
    public function edit(Presence $presence)
    {
        $cours = Cours::with('ecole')->orderBy('nom')->get();
        $membres = Membre::where('approuve', true)
                        ->orderBy('nom')
                        ->orderBy('prenom')
                        ->get();
        $statuts = Presence::getStatuts();

        return view('presences.edit', compact('presence', 'cours', 'membres', 'statuts'));
    }

    /**
     * 🔄 Mettre à jour une présence
     */
    public function update(Request $request, Presence $presence)
    {
        $request->validate([
            'membre_id' => 'required|exists:membres,id',
            'cours_id' => 'required|exists:cours,id',
            'date_presence' => 'required|date',
            'status' => 'required|in:present,absent,retard',
            'commentaire' => 'nullable|string|max:500'
        ]);

        // Vérifier unicité (sauf pour l'enregistrement actuel)
        $existeDeja = Presence::where([
            'membre_id' => $request->membre_id,
            'cours_id' => $request->cours_id,
            'date_presence' => $request->date_presence
        ])->where('id', '!=', $presence->id)->exists();

        if ($existeDeja) {
            return back()->withErrors([
                'duplicate' => 'Une présence existe déjà pour ce membre à ce cours à cette date.'
            ])->withInput();
        }

        $presence->update([
            'membre_id' => $request->membre_id,
            'cours_id' => $request->cours_id,
            'date_presence' => $request->date_presence,
            'status' => $request->status,
            'commentaire' => $request->commentaire
        ]);

        return redirect()->route('presences.index')
            ->with('success', '✅ Présence mise à jour avec succès');
    }

    /**
     * 🗑️ Supprimer une présence
     */
    public function destroy(Presence $presence)
    {
        $membreNom = $presence->membre->nom . ' ' . $presence->membre->prenom;
        $coursNom = $presence->cours->nom;
        $date = $presence->date_presence->format('d/m/Y');

        $presence->delete();

        return redirect()->route('presences.index')
            ->with('success', "✅ Présence supprimée : {$membreNom} - {$coursNom} ({$date})");
    }

    /**
     * 📊 Statistiques de présences
     */
    public function statistiques(Request $request)
    {
        $dateDebut = $request->input('date_debut', Carbon::now()->startOfMonth());
        $dateFin = $request->input('date_fin', Carbon::now()->endOfMonth());

        // Statistiques globales
        $stats = [
            'total_presences' => Presence::periode($dateDebut, $dateFin)->count(),
            'presents' => Presence::periode($dateDebut, $dateFin)->present()->count(),
            'absents' => Presence::periode($dateDebut, $dateFin)->absent()->count(),
            'retards' => Presence::periode($dateDebut, $dateFin)->retard()->count(),
        ];

        // Calcul du taux de présence
        $stats['taux_presence'] = $stats['total_presences'] > 0 
            ? round((($stats['presents'] + $stats['retards']) / $stats['total_presences']) * 100, 1)
            : 0;

        // Top 5 des cours les plus fréquentés
        $coursPopulaires = Presence::with('cours')
            ->periode($dateDebut, $dateFin)
            ->present()
            ->selectRaw('cours_id, COUNT(*) as total')
            ->groupBy('cours_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Évolution des présences par jour
        $evolutionJournaliere = Presence::periode($dateDebut, $dateFin)
            ->selectRaw('DATE(date_presence) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('presences.statistiques', compact(
            'stats', 
            'coursPopulaires', 
            'evolutionJournaliere',
            'dateDebut',
            'dateFin'
        ));
    }

    /**
     * 📥 Import en masse de présences
     */
    public function importMasse()
    {
        $cours = Cours::orderBy('nom')->get();
        
        return view('presences.import', compact('cours'));
    }

    /**
     * 📤 Export des présences
     */
    public function export(Request $request)
    {
        $format = $request->input('format', 'excel'); // excel ou pdf
        
        // Logique d'export à implémenter selon vos besoins
        // Utilisation de Laravel Excel ou DomPDF
        
        return redirect()->back()
            ->with('info', 'Fonctionnalité d\'export en développement');
    }

    /**
     * 🔍 Recherche AJAX pour autocomplete
     */
    public function rechercheMembres(Request $request)
    {
        $terme = $request->input('q');
        
        $membres = Membre::where('approuve', true)
            ->where(function($query) use ($terme) {
                $query->where('nom', 'like', "%{$terme}%")
                      ->orWhere('prenom', 'like', "%{$terme}%")
                      ->orWhere('email', 'like', "%{$terme}%");
            })
            ->limit(10)
            ->get(['id', 'nom', 'prenom', 'email']);

        return response()->json($membres);
    }

    /**
     * 📱 API pour mobile (optionnel)
     */
    public function apiPresencesMembre($membreId)
    {
        $presences = Presence::with('cours')
            ->where('membre_id', $membreId)
            ->orderBy('date_presence', 'desc')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $presences
        ]);
    }

    /**
     * 🎯 Marquer présence rapide (pour les kiosques)
     */
    public function presenceRapide(Request $request)
    {
        $request->validate([
            'membre_id' => 'required|exists:membres,id',
            'cours_id' => 'required|exists:cours,id'
        ]);

        $aujourdhui = Carbon::today();

        $presence = Presence::updateOrCreate(
            [
                'membre_id' => $request->membre_id,
                'cours_id' => $request->cours_id,
                'date_presence' => $aujourdhui
            ],
            [
                'status' => 'present',
                'commentaire' => 'Présence automatique'
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Présence enregistrée',
            'presence' => $presence
        ]);
    }
}
