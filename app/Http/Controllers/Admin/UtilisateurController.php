<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UtilisateurController extends Controller
{
    public function index()
    {
        $utilisateurs = User::latest()->paginate(15);
        return view('admin.utilisateurs.index', compact('utilisateurs'));
    }

    public function create()
    {
        return view('admin.utilisateurs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:191',
            'matricule' => 'required|string|max:191|unique:users',
            'email'     => 'required|email|max:191|unique:users',
            'role'      => 'required|in:admin,etudiante,resp_hebergement,technicien,resp_foyer',
            'phone'     => 'nullable|string|max:20',
            'password'  => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name'      => $request->name,
            'matricule' => $request->matricule,
            'email'     => $request->email,
            'role'      => $request->role,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
        ]);

        return redirect()->route('admin.utilisateurs.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $utilisateur)
    {
        return view('admin.utilisateurs.edit', compact('utilisateur'));
    }

   public function update(Request $request, User $utilisateur)
{
    $request->validate([
        'name'      => 'required|string|max:191',
        'matricule' => 'required|string|max:191|unique:users,matricule,' . $utilisateur->id,
        'email'     => 'required|email|max:191|unique:users,email,' . $utilisateur->id,
        'role'      => 'required|in:admin,etudiante,resp_hebergement,technicien,resp_foyer',
        'phone'     => 'nullable|string|max:20',
        'password'  => 'nullable|string|min:6|confirmed',
        'photo'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // ← ajouté
    ]);

    $data = $request->only('name', 'matricule', 'email', 'role', 'phone');

    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    // 
    if ($request->hasFile('photo')) {
        // Supprimer l'ancienne photo
        if ($utilisateur->photo) {
            \Storage::disk('public')->delete($utilisateur->photo);
        }
        $data['photo'] = $request->file('photo')->store('photos', 'public');
    }

    $utilisateur->update($data);

    return redirect()->route('admin.utilisateurs.index')
        ->with('success', 'Utilisateur modifié avec succès.');
}

    public function destroy(User $utilisateur)
    {
        if ($utilisateur->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        $utilisateur->delete();
        return redirect()->route('admin.utilisateurs.index')
            ->with('success', 'Utilisateur supprimé.');
    }
}