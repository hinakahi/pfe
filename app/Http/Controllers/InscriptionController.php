<?php
namespace App\Http\Controllers;

use App\Models\{User, MatriculeAutorise};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InscriptionController extends Controller
{
    public function create()
    {
        return view('auth.inscription');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:191',
            'matricule' => 'required|string|max:191|unique:users,matricule',
            'email'     => 'required|email|unique:users,email',
            'phone'     => 'nullable|string|max:10',
            'password'  => 'required|string|min:6|confirmed',
            'photo'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $matricule = MatriculeAutorise::where('matricule', $request->matricule)
                                      ->where('utilise', false)
                                      ->first();

        if (!$matricule) {
            return back()->withErrors([
                'matricule' => 'Ce matricule n\'est pas autorisé ou a déjà été utilisé.'
            ])->withInput();
        }

        if (User::where('matricule', $request->matricule)->exists()) {
            return back()->withErrors([
                'matricule' => 'Ce matricule est déjà associé à un compte.'
            ])->withInput();
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('users', 'public');
        }

        // Créer l'utilisateur et le capturer
        $newUser = User::create([
            'name'      => $request->name,
            'matricule' => $request->matricule,
            'photo'     => $photoPath,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
            'role'      => $matricule->role,
        ]);

        // Marquer le matricule comme utilisé
        $matricule->update(['utilise' => true]);

        // Notifier l'admin
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new \App\Notifications\NouvelUtilisateurAdmin($newUser));
        }

        return redirect()->route('login')
            ->with('success', 'Compte créé avec succès ! Connectez-vous.');
    }
}