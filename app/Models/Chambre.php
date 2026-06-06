<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chambre extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'type',
        'etage',
        'capacite',
        'statut',    // 'libre' ou 'occupee'
        'publiee',   // true/false — visible aux étudiantes
    ];

    protected $casts = [
        'publiee' => 'boolean',
    ];

    // Scopes utiles
    public function scopeLibres($query)
    {
        return $query->where('statut', 'libre');
    }

    public function scopeOccupees($query)
    {
        return $query->where('statut', 'occupee');
    }

    public function scopePubliees($query)
    {
        return $query->where('publiee', true);
    }
}