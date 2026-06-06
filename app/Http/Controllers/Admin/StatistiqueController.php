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
        $types = [
            'Électricité',
            'Plomberie',
            'Menuiserie',
            'Chauffage',
            'Réseaux informatiques et Internet',
            'Sécurité incendie et vidéosurveillance',
        ];

        $dateDebut = match($periode) {
            'semaine'  => now()->startOfWeek(),
            'semestre' => now()->subMonths(6),
            default    => now()->startOfMonth(),
        };

        // 1. Pannes par type
        $pannesParType = [];
        foreach ($types as $type) {
            $pannesParType[$type] = Maintenance::where('type', $type)
                ->where('date_signalement', '>=', $dateDebut)
                ->count();
        }

        // 2. Délai moyen de résolution (heures)
        $delaiParType = [];
        foreach ($types as $type) {
            $avg = Maintenance::where('type', $type)
                ->where('statut', 'terminee')
                ->whereNotNull('date_resolution')
                ->where('date_signalement', '>=', $dateDebut)
                ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, date_signalement, date_resolution)) as avg_heures'))
                ->value('avg_heures');
            $delaiParType[$type] = round($avg ?? 0, 1);
        }

        // 3. Chambres les plus problématiques (top 5)
        $chambresProblematiques = Maintenance::select('chambre_id', DB::raw('COUNT(*) as total'))
            ->with('chambre')
            ->where('date_signalement', '>=', $dateDebut)
            ->groupBy('chambre_id')
            ->orderByDesc('total')
            ->take(5)
            ->get()
            ->map(function ($m) {
                return [
                    'chambre' => $m->chambre ? 'Chambre ' . $m->chambre->numero : 'Inconnue',
                    'total'   => $m->total,
                ];
            });

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

        return compact('pannesParType', 'delaiParType', 'chambresProblematiques', 'pannesParMois');
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
