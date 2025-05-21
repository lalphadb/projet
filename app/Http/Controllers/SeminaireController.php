<?php

namespace App\Http\Controllers;

use App\Models\Seminaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SeminaireController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $seminaires = Seminaire::orderBy('date', 'desc')->paginate(10);
        return view('seminaires.index', compact('seminaires'));
    }

    public function create()
    {
        return view('seminaires.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $seminaire = Seminaire::create($data);
        Log::info('Séminaire créé', ['id' => $seminaire->id, 'user_id' => auth()->id()]);

        return redirect()->route('seminaires.index')->with('success', 'Séminaire ajouté avec succès.');
    }

    public function show(Seminaire $seminaire)
    {
        $seminaire->load('membres');
        return view('seminaires.show', compact('seminaire'));
    }

    public function edit(Seminaire $seminaire)
    {
        return view('seminaires.edit', compact('seminaire'));
    }

    public function update(Request $request, Seminaire $seminaire)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $seminaire->update($data);
        Log::info('Séminaire mis à jour', ['id' => $seminaire->id, 'user_id' => auth()->id()]);

        return redirect()->route('seminaires.index')->with('success', 'Séminaire mis à jour.');
    }

    public function destroy(Seminaire $seminaire)
    {
        $seminaire->delete();
        Log::warning('Séminaire supprimé', ['id' => $seminaire->id, 'user_id' => auth()->id()]);

        return redirect()->route('seminaires.index')->with('success', 'Séminaire supprimé.');
    }
}
