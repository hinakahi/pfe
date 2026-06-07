<?php

namespace App\Http\Controllers\Etudiante;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use Illuminate\Http\Request;

class AnnonceController extends Controller
{
    public function index(Request $request)
    {
        $query = Annonce::with('user')->where('publiee', true);

        // Recherche
        if ($request->search) {
            $query->where('titre', 'like', '%' . $request->search . '%')
                  ->orWhere('contenu', 'like', '%' . $request->search . '%');
        }

        // Filtre par auteur
        if ($request->auteur) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('role', $request->auteur);
            });
        }

        // Annonces urgentes séparées (pour le carousel)
        $annoncesUrgentes = Annonce::with('user')
            ->where('urgence', 'urgent')
            ->where('publiee', true)
            ->latest()
            ->get();

        // Toutes les annonces paginées
        $annonces = $query->latest()->paginate(10)->withQueryString();

        return view('etudiante.annonces', compact('annonces', 'annoncesUrgentes'));
    }
}