<?php

namespace App\Http\Controllers;

use App\Models\DemandeInscription;
use Illuminate\Http\Request;

class DemandesInscriptionController extends Controller
{
    public function index()
    {
        $demandes = DemandeInscription::paginate(15);
        return view('demandes-inscriptions.index', compact('demandes'));
    }

    // Ajoute les autres méthodes au besoin
}
