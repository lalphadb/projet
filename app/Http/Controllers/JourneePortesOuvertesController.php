<?php

namespace App\Http\Controllers;

use App\Models\JourneePortesOuvertes;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JourneePortesOuvertesController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = JourneePortesOuvertes::with('ecole');

        if ($user->role === 'admin') {
            $query->where('ecole_id', $user->ecole_id);
        }

        $jours = $query->orderByDesc('debut')->paginate(15);

        return view('journees-portes-ouvertes.index', compact('jours'));
    }

    public function create()
    {
        $user = Auth::user();
        $ecoles = $user->role === 'superadmin' ? Ecole::all() : null;

        return view('journees-portes-ouvertes.create', compact('ecoles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'debut' => 'required|date',
            'fin' => 'required|date|after_or_equal:debut',
            'active' => 'required|boolean',
            'ecole_id' => 'required|exists:ecoles,id',
        ]);

        JourneePortesOuvertes::create($data);

        return redirect()->route('journees-portes-ouvertes.index')->with('success', 'Journée PO ajoutée.');
    }

    public function edit(JourneePortesOuvertes $journees_portes_ouverte)
    {
        $user = Auth::user();
        if ($user->role === 'admin' && $journees_portes_ouverte->ecole_id !== $user->ecole_id) {
            abort(403);
        }

        $ecoles = $user->role === 'superadmin' ? Ecole::all() : null;

        return view('journees-portes-ouvertes.edit', compact('journees_portes_ouverte', 'ecoles'));
    }

    public function update(Request $request, JourneePortesOuvertes $journees_portes_ouverte)
    {
        $user = Auth::user();
        if ($user->role === 'admin' && $journees_portes_ouverte->ecole_id !== $user->ecole_id) {
            abort(403);
        }

        $data = $request->validate([
            'debut' => 'required|date',
            'fin' => 'required|date|after_or_equal:debut',
            'active' => 'required|boolean',
            'ecole_id' => 'required|exists:ecoles,id',
        ]);

        $journees_portes_ouverte->update($data);

        return redirect()->route('journees-portes-ouvertes.index')->with('success', 'Journée PO mise à jour.');
    }

    public function destroy(JourneePortesOuvertes $journees_portes_ouverte)
    {
        $user = Auth::user();
        if ($user->role === 'admin' && $journees_portes_ouverte->ecole_id !== $user->ecole_id) {
            abort(403);
        }

        $journees_portes_ouverte->delete();

        return redirect()->route('journees-portes-ouvertes.index')->with('success', 'Journée PO supprimée.');
    }
}
