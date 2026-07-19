<?php

namespace App\Http\Controllers\ResponsableHebergement;

use App\Http\Controllers\Controller;
use App\Models\Annonce;

class AnnonceController extends Controller
{
    public function index()
    {
        $annonces = Annonce::with('user')
            ->where(function ($q) {
                $q->where('destinataire', 'staff')
                  ->orWhere('destinataire', 'tous');
            })
            ->where('publiee', true)
            ->latest()
            ->paginate(10);

        $annoncesUrgentes = Annonce::with('user')
            ->where(function ($q) {
                $q->where('destinataire', 'staff')
                  ->orWhere('destinataire', 'tous');
            })
            ->where('publiee', true)
            ->where('urgence', 'urgent')
            ->latest()
            ->take(5)
            ->get();

        return view('hebergement.annonces.index', compact('annonces', 'annoncesUrgentes'));
    }
}