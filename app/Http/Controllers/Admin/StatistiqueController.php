<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StatistiquesExport;

class StatistiqueController extends Controller
{
    private function getData(string $periode): array
    {
        // Code en base => Libellé affiché
        $types = [
            'electricite' => 'Électricité',
            'plomberie'   => 'Plomberie',
            'menuiserie'  => 'Menuiserie',
            'chauffage'   => 'Chauffage',
            'reseaux'     => 'Réseaux informatiques et Internet',
            'securite'    => 'Sécurité incendie et vidéosurveillance',
        ];

        $dateDebut = match($periode) {
            'semaine'  => now()->startOfWeek(),
            'semestre' => now()->subMonths(6),
            default    => now()->startOfMonth(),
        };

        // 1. Pannes par type
        $pannesParType = [];
        foreach ($types as $code => $libelle) {
            $pannesParType[$libelle] = Maintenance::where('type', $code)
                ->where('date_signalement', '>=', $dateDebut)
                ->count();
        }

        // 2. Délai moyen de résolution
$delaiParType = [];         // Valeurs numériques en minutes (pour Chart.js)
$delaiParTypeFormatted = []; // Valeurs formatées (pour les tooltips)

foreach ($types as $code => $libelle) {
    $avg = Maintenance::where('type', $code)
        ->where('statut', 'terminee')
        ->whereNotNull('date_resolution')
        ->where('date_signalement', '>=', $dateDebut)
        ->whereRaw('date_resolution > date_signalement')
        ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, date_signalement, date_resolution)) as avg_minutes'))
        ->value('avg_minutes');

    if ($avg === null) {
        $delaiParType[$libelle] = 0;
        $delaiParTypeFormatted[$libelle] = '—';
        continue;
    }

    $minutes = round($avg);
    $delaiParType[$libelle] = $minutes; // ✅ Nombre pour Chart.js

    // Version formatée pour l'affichage
    if ($minutes >= 1440) {
        $jours = intdiv($minutes, 1440);
        $heures = intdiv($minutes % 1440, 60);
        $delaiParTypeFormatted[$libelle] = $jours . 'j ' . $heures . 'h';
    } elseif ($minutes >= 60) {
        $delaiParTypeFormatted[$libelle] = intdiv($minutes, 60) . 'h ' . str_pad($minutes % 60, 2, '0', STR_PAD_LEFT) . 'min';
    } else {
        $delaiParTypeFormatted[$libelle] = $minutes . 'min';
    }
}

        // 3. Chambres les plus problématiques (top 5) — uniquement les chambres réelles
        $chambresProblematiques = Maintenance::select('chambre_id', DB::raw('COUNT(*) as total'))
            ->with('chambre')
            ->where('date_signalement', '>=', $dateDebut)
            ->whereNotNull('chambre_id')
            ->groupBy('chambre_id')
            ->orderByDesc('total')
            ->take(5)
            ->get()
            ->filter(fn($m) => $m->chambre !== null)
            ->map(function ($m) {
                return [
                    'chambre' => 'Chambre ' . $m->chambre->numero,
                    'total'   => $m->total,
                ];
            })
            ->values();

        // 4. Évolution des pannes par mois
        $pannesParMois = Maintenance::select(
                DB::raw("DATE_FORMAT(date_signalement, '%Y-%m') as mois"),
                DB::raw('COUNT(*) as total')
            )
            ->where('date_signalement', '>=', $dateDebut)
            ->groupBy('mois')
            ->orderBy('mois')
            ->get()
            ->mapWithKeys(fn($m) => [$m->mois => $m->total]);

        return compact('pannesParType', 'delaiParType', 'delaiParTypeFormatted', 'chambresProblematiques', 'pannesParMois');
    }

    public function index(Request $request)
    {
        $periode = $request->get('periode', 'mois');
        $data = $this->getData($periode);
        return view('admin.statistiques.statistiques', array_merge($data, ['periode' => $periode]));
    }

    public function exportPdf(Request $request)
    {
        $periode = $request->get('periode', 'mois');
        $data = $this->getData($periode);
        $data['periode'] = $periode;
        $pdf = Pdf::loadView('admin.statistiques.pdf', $data)->setPaper('a4', 'landscape');
        return $pdf->download('statistiques-maintenance.pdf');
    }

    public function exportExcel(Request $request)
    {
        $periode = $request->get('periode', 'mois');
        $data = $this->getData($periode);
        return Excel::download(new StatistiquesExport($data), 'statistiques-maintenance.xlsx');
    }
}