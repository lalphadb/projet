<?php

namespace App\Http\Controllers;

use App\Models\CoursSession;
use App\Models\InscriptionCours;
use App\Models\Membre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReinscriptionController extends Controller
{
    /**
     * Page de réinscription pour un membre
     */
    public function index(Request $request)
    {
        // Pour demo - plus tard authentification membre
        $membreId = $request->get('membre_id', 1);
        
        $membre = Membre::with(['inscriptionsCours.cours.horaires', 'inscriptionsCours.session'])
            ->findOrFail($membreId);
        
        // Session actuelle
        $sessionActuelle = CoursSession::where('ecole_id', $membre->ecole_id)
            ->where('date_fin', '>=', now())
            ->orderBy('date_debut')
            ->first();
            
        // Prochaine session avec réinscriptions ouvertes
        $prochaineSession = CoursSession::where('ecole_id', $membre->ecole_id)
            ->where('inscriptions_actives', true)
            ->where('date_debut', '>', now())
            ->orderBy('date_debut')
            ->first();
        
        if (!$prochaineSession) {
            return view('reinscription.ferme', compact('membre'));
        }
        
        // Cours actuels du membre
        $coursActuels = $membre->inscriptionsCours()
            ->where('session_id', $sessionActuelle->id ?? 0)
            ->with(['cours.horaires'])
            ->get();
        
        // Cours disponibles pour réinscription (groupés par nom)
        $coursDisponibles = $prochaineSession->cours()
            ->with(['horaires' => function($query) {
                $query->where('active', true)->orderBy('jour');
            }, 'inscriptions'])
            ->get()
            ->groupBy('nom');
        
        return view('reinscription.index', compact(
            'membre', 'sessionActuelle', 'prochaineSession', 
            'coursActuels', 'coursDisponibles'
        ));
    }
    
    /**
     * Confirme la réinscription
     */
    public function confirmer(Request $request)
    {
        $request->validate([
            'membre_id' => 'required|exists:membres,id',
            'session_id' => 'required|exists:cours_sessions,id',
            'cours_choisis' => 'required|array|min:1',
            'cours_choisis.*' => 'exists:cours,id',
        ]);
        
        DB::beginTransaction();
        try {
            $membre = Membre::findOrFail($request->membre_id);
            $session = CoursSession::findOrFail($request->session_id);
            
            // Vérifier que les réinscriptions sont ouvertes
            if (!$session->inscriptions_actives) {
                return back()->with('error', 'Les réinscriptions ne sont pas ouvertes pour cette session.');
            }
            
            // Supprimer anciennes inscriptions pour cette session
            InscriptionCours::where('membre_id', $request->membre_id)
                ->where('session_id', $request->session_id)
                ->delete();
            
            // Créer nouvelles inscriptions
            $coursInscrits = [];
            foreach ($request->cours_choisis as $coursId) {
                $inscription = InscriptionCours::create([
                    'membre_id' => $request->membre_id,
                    'cours_id' => $coursId,
                    'session_id' => $request->session_id,
                    'statut' => 'actif',
                ]);
                
                $coursInscrits[] = $inscription->cours->nom;
            }
            
            DB::commit();
            
            Log::info("Réinscription confirmée pour membre #{$membre->id} à la session #{$session->id}", [
                'cours' => $coursInscrits
            ]);
            
            return redirect()->back()->with('success', 
                'Réinscription confirmée avec succès ! Vous êtes inscrit(e) à ' . count($coursInscrits) . ' cours.');
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Erreur lors de la réinscription", [
                'membre_id' => $request->membre_id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Erreur lors de la réinscription. Veuillez réessayer.');
        }
    }
    
    /**
     * Annule une inscription
     */
    public function annuler(Request $request)
    {
        $request->validate([
            'inscription_id' => 'required|exists:inscriptions_cours,id',
            'raison' => 'nullable|string|max:255',
        ]);
        
        try {
            $inscription = InscriptionCours::findOrFail($request->inscription_id);
            
            $inscription->update([
                'statut' => 'annule',
                'raison_changement' => $request->raison,
            ]);
            
            Log::info("Inscription annulée : membre #{$inscription->membre_id} cours #{$inscription->cours_id}");
            
            return back()->with('success', 'Inscription annulée avec succès.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'annulation.');
        }
    }
}
