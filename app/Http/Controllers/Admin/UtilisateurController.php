<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UtilisateurController extends Controller
{   private function deriveRoleFromMatricule(string $matricule): ?string
{
    $matricule = strtoupper($matricule);

    return match (true) {
        str_starts_with($matricule, 'ETU')  => 'etudiante',
        str_starts_with($matricule, 'HEB')  => 'resp_hebergement',
        str_starts_with($matricule, 'TECH') => 'technicien',
        str_starts_with($matricule, 'FOY')  => 'resp_foyer',
        str_starts_with($matricule, 'ADM')  => 'admin',
        default => null,
    };
} 

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
            
            'phone'     => 'nullable|string|max:20',
            'password'  => 'required|string|min:6|confirmed',
        ]);

        User::create([
    'name'      => $request->name,
    'matricule' => $request->matricule,
    'email'     => $request->email,
    'role'      => $this->deriveRoleFromMatricule($request->matricule) ?? 'etudiante',
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
        
        'phone'     => 'nullable|string|max:10',
        'password'  => 'nullable|string|min:8|confirmed',
        'photo'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
    ]);

    $data = $request->only('name', 'matricule', 'email', 'phone');
    $data['role'] = $this->deriveRoleFromMatricule($request->matricule) ?? $utilisateur->role;

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