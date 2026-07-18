<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UtilisateurController extends Controller
{
    private function deriveRoleFromMatricule(string $matricule): ?string
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

    public function index(Request $request)
    {
        $statut = $request->query('statut', 'actif'); // 'actif' par défaut

        $query = User::query();

        if ($statut === 'archive') {
            $query->onlyTrashed();
        }
        // sinon, Eloquent exclut déjà les comptes archivés par défaut

        $utilisateurs = $query->latest()->paginate(15)->withQueryString();

        $countActifs   = User::count(); // exclut les trashed par défaut
        $countArchives = User::onlyTrashed()->count();

        return view('admin.utilisateurs.index', compact(
            'utilisateurs', 'statut', 'countActifs', 'countArchives'
        ));
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

        if ($request->hasFile('photo')) {
            if ($utilisateur->photo) {
                \Storage::disk('public')->delete($utilisateur->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $utilisateur->update($data);

        return redirect()->route('admin.utilisateurs.index')
            ->with('success', 'Utilisateur modifié avec succès.');
    }

    // Archiver un seul compte (soft delete)
    public function destroy(User $utilisateur)
    {
        if ($utilisateur->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas archiver votre propre compte.');
        }

        $utilisateur->delete();

        return redirect()->route('admin.utilisateurs.index')
            ->with('success', 'Utilisateur archivé.');
    }

    // Réactiver un compte archivé
    public function restore($id)
    {
        $utilisateur = User::onlyTrashed()->findOrFail($id);
        $utilisateur->restore();

        return back()->with('success', 'Utilisateur réactivé.');
    }

    // Suppression définitive (optionnel)
    public function forceDelete($id)
    {
        $utilisateur = User::onlyTrashed()->findOrFail($id);

        if ($utilisateur->photo) {
            \Storage::disk('public')->delete($utilisateur->photo);
        }

        $utilisateur->forceDelete();

        return back()->with('success', 'Utilisateur supprimé définitivement.');
    }

    // Archiver plusieurs comptes sélectionnés
    public function bulkArchive(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'exists:users,id',
        ]);

        $ids = array_filter($request->ids, fn($id) => (int) $id !== auth()->id());

        if (empty($ids)) {
            return back()->with('error', 'Aucun compte valide sélectionné.');
        }

        User::whereIn('id', $ids)->delete();

        return back()->with('success', count($ids) . ' compte(s) archivé(s).');
    }

    // Réactiver plusieurs comptes sélectionnés
    public function bulkRestore(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'exists:users,id',
        ]);

        User::onlyTrashed()->whereIn('id', $request->ids)->restore();

        return back()->with('success', count($request->ids) . ' compte(s) réactivé(s).');
    }
}