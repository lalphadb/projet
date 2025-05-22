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

        if ($user->role === 'superadmin') {
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

            $portesOuvertes = JourneePortesOuvertes::where('ecole_id', $ecoleId)
                                ->whereDate('debut', '<=', $today)
                                ->whereDate('fin', '>=', $today)->count();

            $membresEnAttente = Membre::where('ecole_id', $ecoleId)->where('approuve', false)->count();

            $absents = Presence::whereHas('membre', function ($q) use ($ecoleId) {
                $q->where('ecole_id', $ecoleId);
            })->whereDate('date_presence', $today)->where('status', 'absent')->count();

            $derniersMembres = Membre::where('ecole_id', $ecoleId)->latest()->take(5)->get();
        }

        $sessions = CoursSession::withCount('cours')
            ->when($user->role === 'admin', function ($q) use ($user) {
                return $q->where('ecole_id', $user->ecole_id);
            })
            ->orderByDesc('date_debut')
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
