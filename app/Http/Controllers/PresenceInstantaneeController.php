<?php
namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Membre;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PresenceInstantaneeController extends Controller
{
    /**
     * 📆 Dashboard instructeur - Cours du jour
     */
    public function dashboard()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        // Récupérer les cours du jour pour l'école de l'instructeur
        $coursAujourdhui = Cours::whereDate('date_cours', $today)
            ->when($user->ecole_id, function($query, $ecoleId) {
                return $query->where('ecole_id', $ecoleId);
            })
            ->with(['ecole', 'membres'])
            ->orderBy('heure_debut')
            ->get();

        // Identifier le cours en cours (optionnel)
        $coursEnCours = $this->detecterCoursEnCours($coursAujourdhui);

        return view('presences.dashboard', compact('coursAujourdhui', 'coursEnCours'));
    }

    /**
     * 🧾 Interface de prise de présence pour un cours
     */
    public function prendre(Cours $cours)
    {
        $today = Carbon::today();
        
        // Vérifications de sécurité
        if (!$this->peutPrendrePresence($cours)) {
            abort(403, 'Non autorisé à prendre les présences pour ce cours');
        }

        // Récupérer les membres inscrits au cours
        $membres = $cours->membres()
            ->where('approuve', true)
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        // Récupérer les présences déjà enregistrées aujourd'hui
        $presencesExistantes = Presence::where('cours_id', $cours->id)
            ->whereDate('date_presence', $today)
            ->pluck('present', 'membre_id')
            ->toArray();

        return view('presences.prendre', compact('cours', 'membres', 'presencesExistantes'));
    }

    /**
     * 💾 Enregistrer les présences en masse
     */
    public function enregistrer(Request $request, Cours $cours)
    {
        $request->validate([
            'presences' => 'required|array',
            'presences.*' => 'boolean',
            'commentaires' => 'array',
            'commentaires.*' => 'nullable|string|max:255'
        ]);

        if (!$this->peutPrendrePresence($cours)) {
            abort(403, 'Non autorisé');
        }

        $today = Carbon::today();
        $presences = $request->input('presences', []);
        $commentaires = $request->input('commentaires', []);

        DB::transaction(function() use ($cours, $presences, $commentaires, $today) {
            foreach ($presences as $membreId => $present) {
                // Vérifier que le membre est inscrit au cours
                if (!$cours->membres()->where('membre_id', $membreId)->exists()) {
                    continue;
                }

                // Mise à jour ou création de la présence
                Presence::updateOrCreate(
                    [
                        'membre_id' => $membreId,
                        'cours_id' => $cours->id,
                        'date_presence' => $today
                    ],
                    [
                        'present' => (bool) $present,
                        'commentaire' => $commentaires[$membreId] ?? null
                    ]
                );
            }
        });

        return redirect()
            ->route('presences.dashboard')
            ->with('success', "Présences enregistrées pour le cours \"{$cours->nom}\"");
    }

    /**
     * 📊 Voir les présences d'un cours
     */
    public function voir(Cours $cours)
    {
        $presences = Presence::with(['membre'])
            ->where('cours_id', $cours->id)
            ->whereDate('date_presence', Carbon::today())
            ->get()
            ->groupBy('present');

        $presents = $presences->get(1, collect());
        $absents = $presences->get(0, collect());

        return view('presences.voir', compact('cours', 'presents', 'absents'));
    }

    /**
     * 🔍 Détecter le cours actuellement en cours
     */
    private function detecterCoursEnCours($cours)
    {
        $maintenant = Carbon::now()->format('H:i:s');
        
        return $cours->first(function($cours) use ($maintenant) {
            return $cours->heure_debut <= $maintenant && 
                   $cours->heure_fin >= $maintenant;
        });
    }

    /**
     * 🔐 Vérifier les permissions pour prendre les présences
     */
    private function peutPrendrePresence(Cours $cours): bool
    {
        $user = Auth::user();
        
        // Superadmin peut tout faire
        if ($user->hasRole('superadmin')) {
            return true;
        }
        
        // Admin peut gérer toutes les écoles
        if ($user->hasRole('admin')) {
            return true;
        }
        
        // Instructeur ne peut que ses cours de son école
        if ($user->hasRole('instructeur')) {
            return $cours->ecole_id === $user->ecole_id;
        }
        
        return false;
    }
}
