<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Annonce, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Notification, Storage};
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
        // ✅ Validation
        $validated = $request->validate([
            'titre'        => 'required|string|max:191',
            'contenu'      => 'required|string',
            'destinataire' => 'required|in:tous,etudiantes,staff',
            'urgence'      => 'required|in:general,urgent',
            'photos'       => 'nullable|array|max:5',
            'photos.*'     => 'image|mimes:jpg,jpeg,png,webp|max:3072',
        ], [
            'photos.max'      => 'Maximum 5 photos autorisées.',
            'photos.*.image'  => 'Chaque fichier doit être une image.',
            'photos.*.mimes'  => 'Formats acceptés : JPG, PNG, WebP.',
            'photos.*.max'    => 'Chaque photo ne doit pas dépasser 3 Mo.',
        ]);

        // ✅ Upload des photos
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $photos[] = $file->store('annonces', 'public');
            }
        }

        // ✅ Création
        $annonce = Annonce::create([
            'user_id'      => auth()->id(),
            'titre'        => $validated['titre'],
            'contenu'      => $validated['contenu'],
            'categorie'    => 'generale',
            'destinataire' => $validated['destinataire'],
            'urgence'      => $validated['urgence'],
            'publiee'      => true,
            'photos'       => $photos,
        ]);

        // ✅ Notifications automatiques
        $users = match($validated['destinataire']) {
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
        // ✅ Validation
        $validated = $request->validate([
            'titre'         => 'required|string|max:191',
            'contenu'       => 'required|string',
            'photos'        => 'nullable|array|max:5',
            'photos.*'      => 'image|mimes:jpg,jpeg,png,webp|max:3072',
            'remove_photos' => 'nullable|array',
        ], [
            'photos.max'      => 'Maximum 5 photos autorisées.',
            'photos.*.image'  => 'Chaque fichier doit être une image.',
            'photos.*.mimes'  => 'Formats acceptés : JPG, PNG, WebP.',
            'photos.*.max'    => 'Chaque photo ne doit pas dépasser 3 Mo.',
        ]);

        // ✅ Photos actuelles
        $photos = $annonce->photos ?? [];

        // ✅ Supprimer les photos cochées
        if (!empty($validated['remove_photos'])) {
            foreach ($validated['remove_photos'] as $old) {
                Storage::disk('public')->delete($old);
            }
            $photos = array_values(array_diff($photos, $validated['remove_photos']));
        }

        // ✅ Ajouter les nouvelles photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $photos[] = $file->store('annonces', 'public');
            }
        }

        // ✅ Mise à jour
        $annonce->update([
            'titre'   => $validated['titre'],
            'contenu' => $validated['contenu'],
            'photos'  => $photos,
        ]);

        return redirect()->route('admin.annonces.index')
            ->with('success', 'Annonce modifiée.');
    }

    public function destroy(Annonce $annonce)
    {
        // ⚠️ Les photos sont supprimées automatiquement
        // grâce au boot() du modèle Annonce
        $annonce->delete();

        return redirect()->route('admin.annonces.index')
            ->with('success', 'Annonce supprimée.');
    }
}