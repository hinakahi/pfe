<?php
namespace App\Http\Controllers\Etudiante;

use App\Http\Controllers\Controller;
use App\Models\Reclamation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReclamationController extends Controller
{
    // Sécurité : l'étudiante ne voit que SES réclamations
    private function myReclamations()
    {
        return Reclamation::where('etudiante_id', Auth::id());
    }

    public function index()
    {
        $reclamations = $this->myReclamations()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('etudiante.reclamations.index', compact('reclamations'));
    }

    public function create()
    {
        return view('etudiante.reclamations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'sujet'   => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ], [
            'sujet.required'   => 'Le sujet est obligatoire.',
            'message.required' => 'Le message est obligatoire.',
            'message.min'      => 'Le message doit contenir au moins 10 caractères.',
        ]);

        Reclamation::create([
            'etudiante_id' => Auth::id(),
            'sujet'        => $request->sujet,
            'message'      => $request->message,
            'statut'       => 'en_attente',
        ]);

        return redirect()
            ->route('etudiante.reclamations.index')
            ->with('success', 'Votre réclamation a été envoyée avec succès.');
    }

    public function show(Reclamation $reclamation)
    {
        abort_if($reclamation->etudiante_id !== Auth::id(), 403);
        return view('etudiante.reclamations.show', compact('reclamation'));
    }

    // ─── Admin ──────────────────────
    public function indexAdmin(Request $request)
{
    $query = Reclamation::with('etudiante')->latest();

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where('sujet', 'like', "%$search%")
              ->orWhereHas('etudiante', fn($q) => $q->where('name', 'like', "%$search%"));
    }

    if ($request->filled('statut')) {
        $query->where('statut', $request->statut);
    }

    $reclamations = $query->paginate(15)->withQueryString();
    return view('admin.reclamations.index', compact('reclamations'));
}

    public function showAdmin(Reclamation $reclamation)
    {
        return view('admin.reclamations.show', compact('reclamation'));
    }

    public function updateAdmin(Request $request, Reclamation $reclamation)
    {
        $reclamation->update([
            'statut' => $request->statut,
            'reponse' => $request->reponse,
        ]);
        return back()->with('success', 'Mise à jour réussie');
    }
}