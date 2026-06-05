<?php

namespace App\Http\Controllers\Etudiante;

use App\Http\Controllers\Controller;
use App\Models\Annonce;

class AnnonceController extends Controller
{
    public function index()
    {
        $annonces = Annonce::where('destinataire', 'etudiantes')
                           ->latest()
                           ->paginate(10);

        return view('etudiante.annonces.index', compact('annonces'));
    }
}