<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MatriculeAutorise;
use Illuminate\Http\Request;

class MatriculeController extends Controller
{
    public function index()
    {
        $matricules = MatriculeAutorise::latest()->paginate(20);
        return view('admin.matricules.index', compact('matricules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'matricules' => 'required|string',
        ]);

        $liste = array_filter(array_map('trim', explode("\n", $request->matricules)));
        $count = 0;
        foreach ($liste as $m) {
            MatriculeAutorise::firstOrCreate(['matricule' => strtoupper($m)]);
            $count++;
        }

        return back()->with('success', "$count matricule(s) ajouté(s).");
    }

    public function destroy(MatriculeAutorise $matricule)
    {
        $matricule->delete();
        return back()->with('success', 'Matricule supprimé.');
    }
}