<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Membre;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Ecole;

class ActivationController extends Controller
{
public function index()
{
    $ecoles = Ecole::orderBy('nom')->get();
    return view('auth.activate-account', compact('ecoles'));
}

    public function store(Request $request)
    {
        $request->validate([
            'numero_membre'      => 'required|exists:membres,numero_membre',
            'date_naissance'     => 'required|date',
            'sexe'               => 'required|in:H,F,A',
            'telephone'          => 'required|string|max:20',
            'numero_rue'         => 'required|string|max:20',
            'nom_rue'            => 'required|string|max:150',
            'ville'              => 'required|string|max:100',
            'province'           => 'required|string|max:50',
            'code_postal'        => 'required|string|max:10',
            'email'              => 'required|email|unique:users,email',
            'password'           => 'required|confirmed|min:8',
        ]);

        $membre = Membre::where('numero_membre', $request->numero_membre)
                        ->where('date_naissance', $request->date_naissance)
                        ->first();

        if (!$membre) {
            return back()->withErrors([
                'numero_membre' => 'Aucun membre ne correspond aux informations fournies.',
            ])->withInput();
        }

        // Mise à jour du membre avec les nouvelles infos
        $membre->update([
            'sexe'         => $request->sexe,
            'telephone'    => $request->telephone,
            'numero_rue'   => $request->numero_rue,
            'nom_rue'      => $request->nom_rue,
            'ville'        => $request->ville,
            'province'     => $request->province,
            'code_postal'  => $request->code_postal,
            'email'        => $request->email,
        ]);

        // Création du compte utilisateur lié
        User::create([
            'name'       => $membre->prenom . ' ' . $membre->nom,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'membre_id'  => $membre->id,
        ]);

        return redirect()->route('login')->with('status', 'Compte activé ! Vous pouvez maintenant vous connecter.');
    }
}
