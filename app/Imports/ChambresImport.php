<?php
namespace App\Imports;

use App\Models\Chambre;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ChambresImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Ignore si le numéro existe déjà
        if (Chambre::where('numero', $row['numero'])->exists()) {
            return null;
        }

        // etudiante_2 seulement pour les doubles
        $etudiante2 = null;
        if (isset($row['type']) && $row['type'] === 'double') {
            $etudiante2 = $row['etudiante_2'] ?? null;
        }

        $etudiante1 = $row['etudiante_1'] ?? null;

        // Calcul du statut
        $occupied = collect([$etudiante1, $etudiante2])->filter()->count();

        $statut = match($occupied) {
            0 => 'libre',
            1 => $row['type'] === 'double' ? 'partielle' : 'occupee',
            2 => 'occupee',
        };

        return new Chambre([
            'numero'      => $row['numero'],
            'type'        => $row['type'],
            'bloc'        => $row['bloc'],
            'etage'       => $row['etage'],
            'capacite'    => $row['capacite'],
            'etudiante_1' => $etudiante1,
            'etudiante_2' => $etudiante2,
            'statut'      => $statut,
            'publiee'     => false,
        ]);
    }

    public function rules(): array
    {
        return [
            'numero'   => 'required',
            'type'     => 'required|in:individuelle,double',
            'bloc'     => 'required',
            'etage'    => 'required|integer',
            'capacite' => 'required|integer',
        ];
    }
}