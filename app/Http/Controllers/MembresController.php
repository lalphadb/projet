<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\Ecole;
use App\Models\Seminaire;
use App\Models\Ceinture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MembresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $ecoles = Ecole::orderBy('nom')->get();

        $sort = $request->get('sort', 'nom');
        $direction = $request->get('direction', 'asc');

        $query = Membre::select('membres.*')
            ->join('ecoles', 'membres.ecole_id', '=', 'ecoles.id')
            ->with('ecole');

        if ($user->role === 'superadmin') {
            if ($request->filled('ecole_id') && $request->ecole_id !== 'all' && is_numeric($request->ecole_id)) {
                $query->where('membres.ecole_id', $request->ecole_id);
            }
        } elseif ($user->role === 'admin') {
            $query->where('membres.ecole_id', $user->ecole_id);
        }

        if (in_array($sort, ['nom', 'email', 'telephone'])) {
            $query->orderBy("membres.$sort", $direction);
        } elseif ($sort === 'ecole') {
            $query->orderBy('ecoles.nom', $direction);
        } else {
            $query->orderBy('membres.nom', 'asc');
        }

        $membres = $query->paginate(15)->appends([
            'sort' => $sort,
            'direction' => $direction,
            'ecole_id' => $request->ecole_id,
        ]);

        return view('membres.index', compact('membres', 'ecoles'));
    }

    public function attente()
    {
        $user = Auth::user();
        $query = Membre::with('ecole')->where('approuve', false);

        if ($user->role === 'admin') {
            $query->where('ecole_id', $user->ecole_id);
        }

        $membres = $query->orderBy('nom')->paginate(15);
        return view('membres.index', compact('membres'));
    }

    public function create()
    {
        $user = Auth::user();
        $ecoles = $user->role === 'superadmin' ? Ecole::all() : null;
        return view('membres.create', compact('ecoles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date',
            'sexe' => 'nullable|in:H,F',
            'numero_rue' => 'nullable|string|max:255',
            'nom_rue' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:50',
            'code_postal' => 'nullable|string|max:10',
            'telephone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'ecole_id' => 'required|exists:ecoles,id',
        ]);

        $data['approuve'] = true;

        $membre = Membre::create($data);
        Log::info('Membre créé', ['membre_id' => $membre->id, 'user_id' => Auth::id()]);

        return redirect()->route('membres.index')->with('success', 'Membre ajouté avec succès.');
    }

    public function show(Membre $membre)
    {
        $user = Auth::user();

        if ($user->role === 'admin' && $membre->ecole_id !== $user->ecole_id) {
            abort(403);
        }

        $seminaires = Seminaire::orderBy('date', 'desc')->get();
        $ceintures = Ceinture::orderBy('ordre')->get();
        $membre->load('ceintures', 'seminaires', 'ecole');

        return view('membres.fiche', compact('membre', 'seminaires', 'ceintures'));
    }

    public function edit(Membre $membre)
    {
        $user = Auth::user();

        if ($user->role === 'admin' && $membre->ecole_id !== $user->ecole_id) {
            abort(403);
        }

        $ecoles = $user->role === 'superadmin' ? Ecole::all() : null;
        return view('membres.edit', compact('membre', 'ecoles'));
    }

    public function update(Request $request, Membre $membre)
    {
        $user = Auth::user();

        if ($user->role === 'admin' && $membre->ecole_id !== $user->ecole_id) {
            abort(403);
        }

        $data = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date',
            'sexe' => 'nullable|in:H,F',
            'numero_rue' => 'nullable|string|max:255',
            'nom_rue' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:50',
            'code_postal' => 'nullable|string|max:10',
            'telephone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'ecole_id' => 'required|exists:ecoles,id',
        ]);

        $membre->update($data);
        Log::info('Membre modifié', ['membre_id' => $membre->id, 'user_id' => Auth::id()]);

        return redirect()->route('membres.index')->with('success', 'Membre mis à jour.');
    }

    public function approuver($id)
    {
        $user = Auth::user();
        $membre = Membre::findOrFail($id);

        if ($user->role === 'admin' && $membre->ecole_id !== $user->ecole_id) {
            abort(403);
        }

        $membre->update(['approuve' => true]);
        Log::notice('Membre approuvé', ['membre_id' => $membre->id, 'user_id' => Auth::id()]);

        return redirect()->back()->with('success', 'Membre approuvé.');
    }

    public function ajouterSeminaire(Request $request, Membre $membre)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'date' => 'required|date',
            'lieu' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $seminaire = Seminaire::create($data);
        $membre->seminaires()->attach($seminaire->id);

        Log::info('Séminaire ajouté', [
            'membre_id' => $membre->id,
            'seminaire_id' => $seminaire->id,
            'admin_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Séminaire ajouté au membre.');
    }

    public function retirerSeminaire(Membre $membre, Seminaire $seminaire)
    {
        $membre->seminaires()->detach($seminaire->id);

        Log::info('Séminaire retiré', [
            'membre_id' => $membre->id,
            'seminaire_id' => $seminaire->id,
            'admin_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Séminaire retiré du membre.');
    }

    public function ajouterCeinture(Request $request, Membre $membre)
    {
        $data = $request->validate([
            'ceinture_id' => 'required|exists:ceintures,id',
            'date_obtention' => 'required|date',
        ]);

        if (!$membre->ceintures->contains($data['ceinture_id'])) {
            $membre->ceintures()->attach($data['ceinture_id'], [
                'date_obtention' => $data['date_obtention'],
            ]);

            Log::info('Ceinture ajoutée', [
                'membre_id' => $membre->id,
                'ceinture_id' => $data['ceinture_id'],
                'date' => $data['date_obtention'],
                'admin_id' => Auth::id(),
            ]);

            return redirect()->back()->with('success', 'Ceinture ajoutée au membre.');
        } else {
            return redirect()->back()->with('error', 'Cette ceinture a déjà été attribuée à ce membre.');
        }
    }

    public function retirerCeinture(Membre $membre, $ceinture_id)
    {
        $membre->ceintures()->detach($ceinture_id);

        Log::info('Ceinture retirée', [
            'membre_id' => $membre->id,
            'ceinture_id' => $ceinture_id,
            'admin_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Ceinture retirée du membre.');
    }
}
