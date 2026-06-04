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
            'matricule' => 'required|string|max:191',
            'email'     => 'required|email|unique:users,email',
            'phone'     => 'nullable|string|max:20',
            'password'  => 'required|string|min:6|confirmed',
            'photo'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Vérifier si le matricule est autorisé
        $matricule = MatriculeAutorise::where('matricule', $request->matricule)
                                      ->where('utilise', false)
                                      ->first();

        if (!$matricule) {
            return back()->withErrors([
                'matricule' => 'Ce matricule n\'est pas autorisé ou a déjà été utilisé.'
            ])->withInput();
        }

        // Vérifier que le matricule n'est pas déjà utilisé dans users
        if (User::where('matricule', $request->matricule)->exists()) {
            return back()->withErrors([
                'matricule' => 'Ce matricule est déjà associé à un compte.'
            ])->withInput();
        }

        // 1. Gérer le fichier photo AVANT de créer l'utilisateur
$photoPath = null;
if ($request->hasFile('photo')) {
    $photoPath = $request->file('photo')->store('users', 'public');
}

// 2. Créer l'utilisateur
User::create([
    'name'      => $request->name,
    'matricule' => $request->matricule,
    'photo'     => $photoPath, // <--- On met ici la variable qui contient le chemin
    'email'     => $request->email,
    'phone'     => $request->phone,
    'password'  => Hash::make($request->password),
    'role'      => 'etudiante',
]);

        // Marquer le matricule comme utilisé
        $matricule->update(['utilise' => true]);

        return redirect()->route('login')
            ->with('success', 'Compte créé avec succès ! Connectez-vous.');
    }
}