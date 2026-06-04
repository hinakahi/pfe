<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Chambre, Maintenance, DemandeRenouvellement, DemandeChangement, Annonce, Periode};

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalUtilisateurs'      => User::count(),
            'totalEtudiantes'        => User::where('role', 'etudiante')->count(),
            'chambresDisponibles'    => Chambre::where('disponible', true)->count(),
            'chambresOccupees'       => Chambre::where('disponible', false)->count(),
            'demandesRenouvellement' => DemandeRenouvellement::where('statut', 'en_attente')->count(),
            'demandesChangement'     => DemandeChangement::where('statut', 'en_attente')->count(),
            'maintenancesEnCours'    => Maintenance::where('statut', 'en_cours')->count(),
            'periodeActive'          => Periode::where('active', true)->whereDate('date_debut', '<=', now())->whereDate('date_fin', '>=', now())->first(),
            'derniersUtilisateurs'   => User::latest()->take(6)->get(),
        ]);
    }
    public function statistiques()
{
    // 1. KPIs Maintenance
    $maintenanceStats = [
        'en_attente' => \App\Models\Maintenance::where('statut', 'en_attente')->count(),
        'en_cours'   => \App\Models\Maintenance::where('statut', 'en_cours')->count(),
        'terminee'   => \App\Models\Maintenance::where('statut', 'terminee')->count(),
    ];

    // 2. KPIs Chambres
    $chambreStats = [
        'disponibles' => \App\Models\Chambre::where('disponible', true)->count(),
        'occupees'    => \App\Models\Chambre::where('disponible', false)->count(),
    ];

    // 3. KPIs Demandes
    $demandeStats = [
        'renouvellement_attente'  => \App\Models\DemandeRenouvellement::where('statut', 'en_attente')->count(),
        'renouvellement_validee'  => \App\Models\DemandeRenouvellement::where('statut', 'validee')->count(),
        'renouvellement_refusee'  => \App\Models\DemandeRenouvellement::where('statut', 'refusee')->count(),
        'changement_attente'      => \App\Models\DemandeChangement::where('statut', 'en_attente')->count(),
        'changement_acceptee'     => \App\Models\DemandeChangement::where('statut', 'acceptee')->count(),
        'changement_refusee'      => \App\Models\DemandeChangement::where('statut', 'refusee')->count(),
    ];

    // 4. Inscriptions par mois (6 derniers mois)
    $inscriptionsMois = \App\Models\User::where('role', 'etudiante')
        ->where('created_at', '>=', now()->subMonths(6))
        ->get()
        ->groupBy(fn($u) => $u->created_at->format('M Y'))
        ->map->count();

    return view('admin.statistiques.statistiques', compact('maintenanceStats', 'chambreStats', 'demandeStats', 'inscriptionsMois'));
}
    
}
