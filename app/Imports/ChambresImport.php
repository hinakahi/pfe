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

        return new Chambre([
            'numero'   => $row['numero'],
            'type'     => $row['type'],
            'bloc'     => $row['bloc'],
            'etage'    => $row['etage'],
            'capacite' => $row['capacite'],
            'statut'   => 'libre',
            'publiee'  => false,
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