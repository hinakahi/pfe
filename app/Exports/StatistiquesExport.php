<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class StatistiquesExport implements WithMultipleSheets
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            new PannesParTypeSheet($this->data['pannesParType']),
            new DelaiResolutionSheet($this->data['delaiParType']),
            new ChambresSheet($this->data['chambresProblematiques']),
        ];
    }
}

class PannesParTypeSheet implements FromArray, WithHeadings, WithTitle
{
    protected array $data;
    public function __construct(array $data) { $this->data = $data; }
    public function title(): string { return 'Pannes par type'; }
    public function headings(): array { return ['Type de panne', 'Nombre']; }
    public function array(): array {
        return collect($this->data)->map(fn($v, $k) => [$k, $v])->values()->toArray();
    }
}

class DelaiResolutionSheet implements FromArray, WithHeadings, WithTitle
{
    protected array $data;
    public function __construct(array $data) { $this->data = $data; }
    public function title(): string { return 'Délai moyen résolution'; }
    public function headings(): array { return ['Type de panne', 'Délai moyen (heures)']; }
    public function array(): array {
        return collect($this->data)->map(fn($v, $k) => [$k, $v])->values()->toArray();
    }
}

class ChambresSheet implements FromArray, WithHeadings, WithTitle
{
    protected $data;
    public function __construct($data) { $this->data = $data; }
    public function title(): string { return 'Chambres problématiques'; }
    public function headings(): array { return ['Chambre', 'Nombre de pannes']; }
    public function array(): array {
        return $this->data->map(fn($c) => [$c['chambre'], $c['total']])->toArray();
    }
}
