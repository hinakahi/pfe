<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annonce;
use App\Models\ContactMessage;

class PublicController extends Controller
{
    public function index()
    {
        $annonces = Annonce::latest()->take(5)->get();
        return view('public.welcome', compact('annonces'));
    }

    public function envoyerMessage(Request $request)
    {
        $request->validate([
            'nom'     => 'required|string|max:191',
            'email'   => 'required|email|max:191',
            'objet'   => 'required|string|max:191',
            'message' => 'required|string|max:2000',
        ]);

        ContactMessage::create([
            'nom'     => $request->nom,
            'email'   => $request->email,
            'objet'   => $request->objet,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Votre message a été envoyé avec succès.');
    }
}