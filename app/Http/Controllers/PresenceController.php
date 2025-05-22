<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    // Affiche une liste des présences
    public function index()
    {
        $presences = Presence::with(['membre', 'cours'])->paginate(15);
        return view('presences.index', compact('presences'));
    }

    // Affiche le formulaire de création d'une nouvelle présence
    public function create()
    {
        return view('presences.create');
    }

    // Stocke une nouvelle présence
    public function store(Request $request)
    {
        $validated = $request->validate([
            'membre_id' => 'required|exists:membres,id',
            'cours_id' => 'required|exists:cours,id',
            'date_presence' => 'required|date',
            'heure_arrivee' => 'nullable|date_format:H:i',
            'heure_depart' => 'nullable|date_format:H:i|after:heure_arrivee',
            'est_present' => 'boolean',
        ]);

        Presence::create($validated);

        return redirect()->route('presences.index')->with('success', 'Présence enregistrée.');
    }

    // Affiche les détails d'une présence spécifique
    public function show(Presence $presence)
    {
        return view('presences.show', compact('presence'));
    }

    // Affiche le formulaire d'édition
    public function edit(Presence $presence)
    {
        return view('presences.edit', compact('presence'));
    }

    // Met à jour la présence dans la base de données
    public function update(Request $request, Presence $presence)
    {
        $validated = $request->validate([
            'membre_id' => 'required|exists:membres,id',
            'cours_id' => 'required|exists:cours,id',
            'date_presence' => 'required|date',
            'heure_arrivee' => 'nullable|date_format:H:i',
            'heure_depart' => 'nullable|date_format:H:i|after:heure_arrivee',
            'est_present' => 'boolean',
        ]);

        $presence->update($validated);

        return redirect()->route('presences.index')->with('success', 'Présence mise à jour.');
    }

    // Supprime une présence
    public function destroy(Presence $presence)
    {
        $presence->delete();

        return redirect()->route('presences.index')->with('success', 'Présence supprimée.');
    }
}
