<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Annonce, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NouvelleAnnonceNotification;

class AnnonceController extends Controller
{
    public function index()
    {
        $annonces = Annonce::with('user')->latest()->paginate(10);
        return view('admin.annonces.index', compact('annonces'));
    }

    public function create()
    {
        return view('admin.annonces.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre'        => 'required|string|max:191',
            'contenu'      => 'required|string',
            'destinataire' => 'required|in:tous,etudiantes,staff',
        ]);

        $annonce = Annonce::create([
            'user_id'      => auth()->id(),
            'titre'        => $request->titre,
            'contenu'      => $request->contenu,
            'categorie'    => 'generale',
            'destinataire' => $request->destinataire,
        ]);

        // Notification automatique
        $users = match($request->destinataire) {
            'etudiantes' => User::where('role', 'etudiante')->get(),
            'staff'      => User::whereIn('role', ['resp_hebergement','technicien','resp_foyer'])->get(),
            default      => User::where('id', '!=', auth()->id())->get(),
        };
        Notification::send($users, new NouvelleAnnonceNotification($annonce));

        return redirect()->route('admin.annonces.index')
            ->with('success', 'Annonce publiée et utilisateurs notifiés.');
    }

    public function edit(Annonce $annonce)
    {
        return view('admin.annonces.edit', compact('annonce'));
    }

    public function update(Request $request, Annonce $annonce)
    {
        $request->validate([
            'titre'   => 'required|string|max:191',
            'contenu' => 'required|string',
        ]);
        $annonce->update($request->only('titre', 'contenu'));
        return redirect()->route('admin.annonces.index')
            ->with('success', 'Annonce modifiée.');
    }

    public function destroy(Annonce $annonce)
    {
        $annonce->delete();
        return redirect()->route('admin.annonces.index')
            ->with('success', 'Annonce supprimée.');
    }
}