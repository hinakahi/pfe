<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Periode, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NouvelleperiodeNotification;

class PeriodeController extends Controller
{
    public function index()
    {
        $periodes = Periode::latest()->get();
        return view('admin.periodes.index', compact('periodes'));
    }

    public function create()
    {
        return view('admin.periodes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'       => 'required|in:renouvellement,changement',
            'libelle'    => 'required|string|max:191',
            'date_debut' => 'required|date',
            'date_fin'   => 'required|date|after:date_debut',
            'active'     => 'boolean',
        ]);

        $periode = Periode::create([
            'admin_id'   => auth()->id(),
            'type'       => $request->type,
            'libelle'    => $request->libelle,
            'date_debut' => $request->date_debut,
            'date_fin'   => $request->date_fin,
            'active'     => $request->boolean('active', true), 
        ]);

        $etudiantes = User::where('role', 'etudiante')->get();
        Notification::send($etudiantes, new NouvelleperiodeNotification($periode));

        return redirect()->route('admin.periodes.index')
            ->with('success', 'Période créée et étudiantes notifiées.');
    }

    public function edit(Periode $periode)
    {
        return view('admin.periodes.edit', compact('periode'));
    }

    public function update(Request $request, Periode $periode)
    {
        $request->validate([
            'libelle'    => 'required|string|max:191',
            'date_debut' => 'required|date',
            'date_fin'   => 'required|date|after:date_debut',
            'active'     => 'boolean',
        ]);

        $periode->update([
            'libelle'    => $request->libelle,
            'date_debut' => $request->date_debut,
            'date_fin'   => $request->date_fin,
            'active'     => $request->boolean('active'),
        ]);

        return redirect()->route('admin.periodes.index')
            ->with('success', 'Période modifiée.');
    }

    public function toggle(Periode $periode)
    {
        $periode->update(['active' => !$periode->active]);

        return redirect()->route('admin.periodes.index')
            ->with('success', 'Statut mis à jour.');
    }

    public function destroy(Periode $periode)
    {
        $periode->delete();
        return redirect()->route('admin.periodes.index')
            ->with('success', 'Période supprimée.');
    }
}