<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MatriculeAutorise;
use Illuminate\Http\Request;

class MatriculeController extends Controller
{
    public function index(Request $request)
{
    $query = MatriculeAutorise::latest();

    if ($request->filled('search')) {
        $query->where('matricule', 'like', '%' . $request->search . '%');
    }

    if ($request->statut === 'utilise') {
        $query->where('utilise', true);
    } elseif ($request->statut === 'disponible') {
        $query->where('utilise', false);
    }

    $matricules = $query->paginate(20)->withQueryString();
    return view('admin.matricules.index', compact('matricules'));
}

public function store(Request $request)
{
    $request->validate([
        'matricules' => 'required|string',
    ]);

    $liste = array_filter(array_map('trim', explode("\n", $request->matricules)));
    $count = 0;
    $doublons = 0;

    foreach ($liste as $m) {
        $role = $this->detectRoleFromMatricule($m);

        $result = MatriculeAutorise::firstOrCreate(
            ['matricule' => strtoupper($m)],
            ['role' => $role]
        );

        if ($result->wasRecentlyCreated) {
            $count++;
        } else {
            $doublons++;
        }
    }

    $msg = "$count matricule(s) ajouté(s).";
    if ($doublons > 0) {
        $msg .= " $doublons déjà existant(s) ignoré(s).";
    }

    return back()->with('success', $msg);
}
// AJOUTE CETTE FONCTION (en bas du contrôleur)
private function detectRoleFromMatricule($matricule)
{
    $prefix = strtoupper(substr($matricule, 0, 3));
    
    return match($prefix) {
        'ETU' => 'etudiante',
        'FOY' => 'resp_foyer',
        'TEC' => 'technicien',
        'ADM' => 'admin',
        'HEB' => 'resp_hebergement',
        default => 'etudiante',
    };
}

    public function destroy(MatriculeAutorise $matricule)
    {
        $matricule->delete();
        return back()->with('success', 'Matricule supprimé.');
    }
}