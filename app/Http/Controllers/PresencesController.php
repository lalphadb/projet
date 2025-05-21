<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use Illuminate\Http\Request;

class PresencesController extends Controller
{
    public function index()
    {
        $presences = Presences::paginate(15);
        return view('presences.index', compact('presences'));
    }

    // Ajoute les autres méthodes au besoin
}
