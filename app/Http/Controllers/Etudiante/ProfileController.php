<?php

namespace App\Http\Controllers\Etudiante;

use App\Http\Controllers\Controller;
use App\Models\DemandeRenouvellement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    
    public function show()
    {
        $user = auth()->user();
        
        $historiqueHebergement = DemandeRenouvellement::where('etudiante_id', $user->id)
            ->where('statut', 'accepte')
            ->with('chambre')
            ->latest()
            ->limit(5)
            ->get();

        return view('etudiante.profile.show', compact('user', 'historiqueHebergement'));
    }

    /**
     * Formulaire édition
     */
    public function edit()
    {
        $user = auth()->user();
        return view('etudiante.profile.edit', compact('user'));
    }

    /**
     * Mettre à jour profil
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telephone' => 'nullable|regex:/^[\d\s\-\+]+$/|min:10',
            'photo' => 'nullable|image|mimes:jpg,png|max:2048',
        ], [
            'email.unique' => 'Cet email est déjà utilisé',
            'telephone.regex' => 'Format de téléphone invalide',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                \Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('profiles', 'public');
        }

        $user->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
        ]);

        return redirect()->route('etudiante.profile.show')
            ->with('success', '✓ Profil mis à jour avec succès.');
    }

    /**
     * Formulaire changement mot de passe
     */
    public function editPassword()
    {
        return view('etudiante.profile.change-password');
    }

    /**
     * Mettre à jour mot de passe
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/i',
        ], [
            'current_password.current_password' => 'Mot de passe actuel incorrect',
            'password.regex' => 'Le mot de passe doit contenir minuscules, majuscules et chiffres',
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('etudiante.profile.show')
            ->with('success', '✓ Mot de passe modifié avec succès.');
    }
}