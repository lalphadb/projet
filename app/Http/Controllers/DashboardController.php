<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cours;
use App\Models\Ecole;
use App\Models\Membre;
use App\Models\Presence;
use App\Models\JourneePortesOuvertes;
use App\Models\CoursSession;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now();
        $user = Auth::user();
        
        // Vérifier que l'utilisateur a une école assignée (sauf superadmin)
        if (!$user->ecole_id && $user->role !== 'superadmin') {
            return redirect()->route('login')
                ->with('error', 'Votre compte n\'est pas correctement configuré. Contactez un administrateur.');
        }
        
        if ($user->role === 'superladmin') {
            $totalMembres = Membre::count();
            $totalEcoles = Ecole::count();
            $totalPresences = Presence::whereDate('date_presence', $today)->count();
            $portesOuvertes = JourneePortesOuvertes::whereDate('debut', '<=', $today)
                                    ->whereDate('fin', '>=', $today)->count();
            $membresEnAttente = Membre::where('approuve', false)->count();
            $absents = Presence::whereDate('date_presence', $today)->where('status', 'absent')->count();
            $derniersMembres = Membre::latest()->take(5)->get();
        } else {
            $ecoleId = $user->ecole_id;
            $totalMembres = Membre::where('ecole_id', $ecoleId)->count();
            $totalEcoles = 1;
            $totalPresences = Presence::whereHas('membre', function ($q) use ($ecoleId) {
                $q->where('ecole_id', $ecoleId);
            })->whereDate('date_presence', $today)->count();
            
            // CORRECTION : Supprimer le filtre par ecole_id pour les portes ouvertes
            $portesOuvertes = JourneePortesOuvertes::whereDate('debut', '<=', $today)
                                ->whereDate('fin', '>=', $today)->count();
            
            $membresEnAttente = Membre::where('ecole_id', $ecoleId)->where('approuve', false)->count();
            $absents = Presence::whereHas('membre', function ($q) use ($ecoleId) {
                $q->where('ecole_id', $ecoleId);
            })->whereDate('date_presence', $today)->where('status', 'absent')->count();
            $derniersMembres = Membre::where('ecole_id', $ecoleId)->latest()->take(5)->get();
        }
        
        // Correction pour les sessions
        $sessions = CoursSession::query()
            ->when($user->role === 'admin' || $user->role === 'instructor', function ($q) use ($user) {
                // Si la table cours_sessions a une colonne ecole_id, décommentez la ligne suivante
                // return $q->where('ecole_id', $user->ecole_id);
                return $q; // Temporairement, on prend toutes les sessions
            })
            ->orderByDesc('date_debut')
            ->take(10) // Limiter à 10 résultats
            ->get();
        
        return view('dashboard', [
            'totalMembres' => $totalMembres,
            'totalEcoles' => $totalEcoles,
            'totalPresences' => $totalPresences,
            'portesOuvertes' => $portesOuvertes,
            'membresEnAttente' => $membresEnAttente,
            'absents' => $absents,
            'derniersMembres' => $derniersMembres,
            'sessions' => $sessions,
        ]);
    }
}
