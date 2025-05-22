<?php

namespace App\Http\Controllers;

use App\Models\JourneePortesOuvertes;
use Illuminate\Http\Request;

class PortesOuvertesController extends Controller
{
    public function index()
    {
        $po = JourneePortesOuvertes::paginate(15);
        return view('portes-ouvertes.index', compact('po'));
    }

    // Ajoute les autres méthodes au besoin
}
